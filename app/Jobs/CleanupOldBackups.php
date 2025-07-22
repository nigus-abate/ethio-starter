<?php

namespace App\Jobs;

use App\Models\Backup;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class CleanupOldBackups implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    { 
        $limit = {{ setting('backup_retention_days', 30) }};
        $backups = Backup::completed()
                   ->orderBy('created_at', 'desc')
                   ->skip($limit)
                   ->take(PHP_INT_MAX)
                   ->get();

        foreach ($backups as $backup) {
            Storage::disk($backup->disk)->delete($backup->path);
            $backup->delete();
        }
    }
}