<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Backup;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => Cache::remember('total_users', 60, fn() => User::count()),
            'admin_count' => User::role('admin')->count(),
            'regular_count' => User::role('user')->count(),
            'backup_count' => Backup::count(),
            'completed_backups' => Backup::completed()->count(),
            'failed_jobs' => DB::table('failed_jobs')->count(),
            'pending_jobs' => DB::table('jobs')->count(),
        ];

        $latestUsers = User::latest()->take(5)->get();
        $recentBackups = Backup::latest()->take(5)->get();
        $recentActivities = ActivityLog::latest()->take(10)->get();

        // Get user count grouped by month (last 12 months)
        $userGrowth = User::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->pluck('count', 'month');

        // Prepare chart data
        $months = collect(range(1, 12))->map(function ($m) {
            return Carbon::create()->month($m)->format('M');
        });

        $userGrowthData = $months->map(function ($monthName, $index) use ($userGrowth) {
            return $userGrowth->get($index + 1, 0); // Months are 1-indexed
        });

        return view('admin.dashboard.index', compact('stats',
            'latestUsers',
            'recentBackups',
            'recentActivities',
            'userGrowthData',
            'months'
        ));
    }
}
