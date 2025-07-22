<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Backup;
use App\Services\BackupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Traits\LogsActivity; // Your custom trait
use App\Jobs\RestoreBackup;

class BackupController extends Controller
{
    protected $backupService;

    public function __construct(BackupService $backupService)
    {
        $this->backupService = $backupService;
       // $this->middleware('can:manage_backups');
        $this->middleware('can:view backups')->only('index', 'show');
        $this->middleware('can:create backups')->only('create', 'store');
        $this->middleware('can:edit backups')->only('edit', 'update');
        $this->middleware('can:delete backups')->only('destroy');
        $this->middleware('can:download backups')->only('download');
        $this->middleware('can:restore backups')->only('restore');
    }

    public function index()
    {
        $backups = Backup::latest()->paginate(15);
        return view('admin.backups.index', compact('backups'));
    }

    public function create()
    {
        return view('admin.backups.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:full,incremental,differential'
        ]);

        try {
            $backup = $this->backupService->createBackup(
                $request->type,
                auth()->id()
            );

            return redirect()->route('backups.index')
                   ->with('success', 'Backup created successfully');

        } catch (\Exception $e) {
            return redirect()->back()
                   ->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    public function show(Backup $backup)
    {
        return view('admin.backups.show', compact('backup'));
    }

   public function destroy(Backup $backup)
	{
	    try {
	        // Check if path exists and is not null
	        if ($backup->path && Storage::disk($backup->disk)->exists($backup->path)) {
	            Storage::disk($backup->disk)->delete($backup->path);

	            // Log file deletion separately
                $backup->logActivity(
                    'file_deleted', 
                    auth()->user(),
                    ['path' => $backup->path, 'disk' => $backup->disk],
                    "Backup file deleted from storage"
                );
	        }

	        $backup->delete();

	        return redirect()->route('backups.index')
	               ->with('success', 'Backup deleted successfully');
	               
	    } catch (\Exception $e) {

	    	// Log the error
            $backup->logActivity(
                'deletion_failed',
                auth()->user(),
                ['error' => $e->getMessage()],
                "Failed to delete backup"
            );
	        return redirect()->back()
	               ->with('error', 'Failed to delete backup: ' . $e->getMessage());
	    }
	}

    public function download(Backup $backup)
    {
        if (!Storage::disk($backup->disk)->exists($backup->path)) {
            abort(404);
        }

        return Storage::disk($backup->disk)->download($backup->path);
    }

    public function restore(Backup $backup, Request $request)
	{
	    //$this->authorize('restore', $backup);

	    // Validate restore options
	    $validated = $request->validate([
	        'restore_database' => 'sometimes|boolean',
	        'restore_storage' => 'sometimes|boolean'
	    ]);

	    try {
	        // Create restore job
	        RestoreBackup::dispatch(
	            $backup,
	            $validated['restore_database'] ?? false,
	            $validated['restore_storage'] ?? false,
	            auth()->user()
	        );

	        $backup->logActivity(
		        'restored',
		        auth()->user(),
		        ['path' => $backup->path],
		        "Backup '{$backup->name}' restored"
		    );

	        return redirect()->route('backups.index')
	               ->with('success', 'Restore process has been queued. You will be notified when complete.');

	    } catch (\Exception $e) {
	        return redirect()->back()
	               ->with('error', 'Failed to initiate restore: ' . $e->getMessage());
	    }
	}
}