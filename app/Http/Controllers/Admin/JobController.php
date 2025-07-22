<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;

class JobController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:view jobs')->only('index', 'show', 'stats');
        $this->middleware('can:retry jobs')->only('retry');
    }
    public function index()
    {
        $jobs = DB::table('jobs')
            ->orderBy('available_at', 'desc')
            ->paginate(15);

        $failedJobs = DB::table('failed_jobs')
            ->orderBy('failed_at', 'desc')
            ->paginate(15);
            
        return view('admin.jobs.index', compact('jobs', 'failedJobs'));
    }

    public function stats()
    {
        $stats = [
            'today' => DB::table('jobs')->whereDate('created_at', today())->count(),
            'failed_today' => DB::table('failed_jobs')->whereDate('failed_at', today())->count(),
            'avg_time' => DB::table('jobs')
                ->whereNotNull('reserved_at')
                ->whereNotNull('completed_at')
                ->avg(DB::raw('completed_at - reserved_at')),
        ];

        // Last 7 days trend
        $jobTrends = [
            'dates' => collect(range(6, 0))->map(function ($days) {
                return now()->subDays($days)->format('D');
            }),
            'counts' => collect(range(6, 0))->map(function ($days) {
                return DB::table('jobs')
                    ->whereDate('created_at', now()->subDays($days))
                    ->count();
            })
        ];

        // Queue distribution
        $queueDistribution = DB::table('jobs')
            ->select('queue', DB::raw('count(*) as count'))
            ->groupBy('queue')
            ->get();

        // Recent jobs
        $recentJobs = DB::table('jobs')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.jobs.stats', compact('stats', 'jobTrends', 'queueDistribution', 'recentJobs'));
    }

    public function show($id)
    {
        $job = DB::table('jobs')->find($id);
        return view('admin.jobs.show', compact('job'));
    }

    public function retry($id)
    {
        $failedJob = DB::table('failed_jobs')->find($id);
        
        dispatch(json_decode($failedJob->payload)->job);
        
        DB::table('failed_jobs')->where('id', $id)->delete();

        return redirect()->route('jobs.index')
               ->with('success', 'Job requeued successfully');
    }
}