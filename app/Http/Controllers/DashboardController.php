<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Backup;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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

        return view('admin.dashboard.index', compact('stats', 'latestUsers', 'recentBackups', 'recentActivities'));
    }
}
