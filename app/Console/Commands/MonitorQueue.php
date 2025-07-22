<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MonitorQueue extends Command
{
    protected $signature = 'queue:monitor';
    protected $description = 'Monitor the job queue health';

    public function handle()
    {
        $pending = DB::table('jobs')->count();
        $failed = DB::table('failed_jobs')->count();
        
        $this->info("Pending jobs: {$pending}");
        $this->info("Failed jobs: {$failed}");
        
        if ($failed > 0) {
            $this->error("There are failed jobs that need attention!");
        }
    }
}