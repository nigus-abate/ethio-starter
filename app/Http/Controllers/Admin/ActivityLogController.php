<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
	public function __construct() {
        $this->middleware('can:view activity logs')->only('index');
        $this->middleware('can:delete activity logs')->only('destroySelected', 'clearAll');
    }

	public function index(Request $request)
	{
	    $query = ActivityLog::with(['causer', 'subject'])->latest();

	    if ($request->filled('action')) {
	        $query->where('action', 'like', '%' . $request->action . '%');
	    }

	    if ($request->filled('causer')) {
	        $query->whereHas('causer', function ($q) use ($request) {
	            $q->where('name', 'like', '%' . $request->causer . '%');
	        });
	    }

	    if ($request->filled('date')) {
	        $query->whereDate('created_at', $request->date);
	    }

	    $logs = $query->paginate(20)->appends($request->except('page'));

	    return view('admin.activity-logs.index', compact('logs'));
	}

	public function destroySelected(Request $request)
	{
	    $ids = $request->input('selected_logs', []);

	    if (empty($ids)) {
	        return redirect()->back()->with('warning', 'No logs selected.');
	    }

	    ActivityLog::whereIn('id', $ids)->delete();

	    return redirect()->back()->with('success', 'Selected logs deleted successfully.');
	}

	public function clearAll()
	{
	    ActivityLog::truncate(); // or ->delete() if you want events triggered

	    return redirect()->back()->with('success', 'All activity logs have been cleared.');
	}

}
