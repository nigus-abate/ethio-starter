<?php

namespace App\Jobs;

use App\Models\Backup;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use ZipArchive;

class RestoreBackup implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $backup;
    protected $restoreDatabase;
    protected $restoreStorage;
    protected $user;

    public function __construct(Backup $backup, bool $restoreDatabase, bool $restoreStorage, User $user)
    {
        $this->backup = $backup;
        $this->restoreDatabase = $restoreDatabase;
        $this->restoreStorage = $restoreStorage;
        $this->user = $user;
    }

    public function handle()
    {
        $this->backup->update(['status' => 'restoring']);
        
        try {
            // Download backup file
            $tempPath = storage_path('app/restore-temp/' . $this->backup->name);
            if (!file_exists(dirname($tempPath))) {
                mkdir(dirname($tempPath), 0755, true);
            }
            
            file_put_contents(
                $tempPath,
                Storage::disk($this->backup->disk)->get($this->backup->path)
            );

            // Extract backup
            $extractPath = storage_path('app/restore-temp/' . $this->backup->id);
            $zip = new ZipArchive;
            
            if ($zip->open($tempPath) === true) {
                $zip->extractTo($extractPath);
                $zip->close();
            } else {
                throw new \Exception('Failed to extract backup archive');
            }

            // Restore database if requested
            if ($this->restoreDatabase) {
                $this->restoreDatabase($extractPath);
            }

            // Restore storage if requested
            if ($this->restoreStorage) {
                $this->restoreStorage($extractPath);
            }

            // Cleanup
            $this->cleanup($tempPath, $extractPath);

            $this->backup->update(['status' => 'completed']);
            $this->notifyUser('Backup restored successfully');

        } catch (\Exception $e) {
            $this->backup->update([
                'status' => 'failed',
                'metadata' => array_merge(
                    $this->backup->metadata ?? [],
                    ['restore_error' => $e->getMessage()]
                )
            ]);
            
            $this->notifyUser('Backup restore failed: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function restoreDatabase($extractPath)
    {
        $dbDumpPath = $extractPath . '/database.sql';
        
        if (!file_exists($dbDumpPath)) {
            throw new \Exception('Database dump not found in backup');
        }

        // Empty all tables
        $tables = DB::select('SHOW TABLES');
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        foreach ($tables as $table) {
            $tableName = reset($table);
            if ($tableName !== 'migrations') {
                DB::table($tableName)->truncate();
            }
        }

        // Import SQL
        $command = sprintf(
            'mysql -h%s -u%s -p%s %s < %s',
            config('database.connections.mysql.host'),
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.database'),
            $dbDumpPath
        );

        $process = Process::fromShellCommandline($command);
        $process->setTimeout(3600);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \Exception('Database restore failed: ' . $process->getErrorOutput());
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    protected function restoreStorage($extractPath)
    {
        $storagePath = $extractPath . '/storage';
        
        if (!file_exists($storagePath)) {
            throw new \Exception('Storage backup not found');
        }

        // Clear existing storage
        $directories = Storage::disk('documents')->directories();
        foreach ($directories as $directory) {
            Storage::disk('documents')->deleteDirectory($directory);
        }

        // Copy restored files
        $this->copyDirectory(
            $storagePath . '/documents',
            Storage::disk('documents')->path('')
        );
    }

    protected function copyDirectory($source, $destination)
    {
        $process = new Process([
            'cp', '-r', 
            rtrim($source, '/') . '/.', 
            rtrim($destination, '/') . '/'
        ]);
        
        $process->setTimeout(3600);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \Exception('Storage restore failed: ' . $process->getErrorOutput());
        }
    }

    protected function cleanup($tempPath, $extractPath)
    {
        $process = new Process(['rm', '-rf', $tempPath, $extractPath]);
        $process->run();
    }

    protected function notifyUser($message)
    {
        // Implement your notification system (email, database notification, etc.)
        Log::info("Backup {$this->backup->id} restore: " . $message);
        
        // Example: Database notification
        $this->user->notify(new BackupNotification(
            $this->backup,
            $message,
            $this->backup->status === 'completed'
        ));
    }
}