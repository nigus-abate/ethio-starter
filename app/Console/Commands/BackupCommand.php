<?php

namespace App\Console\Commands;

use App\Services\BackupService;
use Illuminate\Console\Command;

class BackupCommand extends Command
{
    protected $signature = 'backup:create {type=full}';
    protected $description = 'Create a new backup';

    public function handle(BackupService $backupService)
    {
        $type = $this->argument('type');
        
        if (!in_array($type, ['full', 'incremental', 'differential'])) {
            $this->error('Invalid backup type. Use full, incremental, or differential.');
            return 1;
        }

        $this->info("Starting {$type} backup...");
        
        try {
            $backup = $backupService->createBackup($type);
            $this->info("Backup completed successfully: {$backup->name}");
            return 0;
        } catch (\Exception $e) {
            $this->error("Backup failed: " . $e->getMessage());
            return 1;
        }
    }
}

/*
protected function schedule(Schedule $schedule)
{
    // Daily full backup at 2am
    $schedule->command('backup:create full')
             ->dailyAt('02:00')
             ->onSuccess(function () {
                 // Notify success
             })
             ->onFailure(function () {
                 // Notify failure
             });

    // Hourly incremental backups
    $schedule->command('backup:create incremental')
             ->hourly()
             ->between('8:00', '20:00');
}
*/