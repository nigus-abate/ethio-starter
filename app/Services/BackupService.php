<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\DbDumper\Databases\MySql;
use Symfony\Component\Process\Process;
use App\Models\Backup;
use ZipArchive;
class BackupService
{
    protected $backupName;
    protected $disk;
    protected $backupPath;
    protected $tempPath;
    protected $lockFile;

    public function __construct()
    {
        $this->disk = config('backup.disk', 'local');
        $this->tempPath = storage_path('app/backup-temp');
        $this->backupPath = 'backups/' . Carbon::now()->format('Y-m-d');
        $this->backupName = 'backup-' . Carbon::now()->format('Y-m-d-H-i-s');
        $this->lockFile = storage_path('app/backup.lock');
        
        // Clean up any stale lock files (older than 2 hours)
        if (file_exists($this->lockFile) && filemtime($this->lockFile) < (time() - 7200)) {
            $this->removeLockFile();
        }

        if (!class_exists('ZipArchive')) {
        throw new \RuntimeException('ZipArchive class not available - please enable php-zip extension');
        }
    }

    public function createBackup($type = 'full', $userId = null)
    {
        // Check for existing lock
        if ($this->isBackupRunning()) {
            throw new \Exception('Another backup process is already running.');
        }

        // Create lock file
        $this->createLockFile();

        $backup = Backup::create([
            'name' => $this->backupName,
            'disk' => $this->disk,
            'type' => $type,
            'user_id' => $userId ?? auth()->id(),
            'status' => 'running'
        ]);

        try {
            // Cleanup any existing temp files first
            if (file_exists($this->tempPath)) {
                $this->cleanup();
            }

            // Create temp directory
            if (!file_exists($this->tempPath)) {
                if (!mkdir($this->tempPath, 0755, true) && !is_dir($this->tempPath)) {
                    throw new \RuntimeException(sprintf('Directory "%s" was not created', $this->tempPath));
                }
            }

            // 1. Backup database
            $this->backupDatabase();

            // 2. Backup storage
            $this->backupStorage();

            // 3. Create archive
            $archivePath = $this->createArchive();

            // 4. Store archive
            $finalPath = $this->storeArchive($archivePath);

            // 5. Cleanup
            $this->cleanup();

            $backup->update([
                'path' => $finalPath,
                'size' => Storage::disk($this->disk)->size($finalPath),
                'status' => 'completed',
                'completed_at' => now(),
                'metadata' => [
                    'type' => $type,
                    'disk' => $this->disk,
                    'contents' => [
                        'database' => true,
                        'storage' => true,
                        'restore_database' => true,
                        'restore_storage' => true
                    ]
                ]
            ]);

            return $backup;

        } catch (\Exception $e) {
            $backup->update([
                'status' => 'failed',
                'metadata' => [
                    'error' => $e->getMessage()
                ]
            ]);
            throw $e;
        } finally {
            // Always remove lock file when done
            $this->removeLockFile();
        }
    }

    protected function isBackupRunning()
{
    if (!file_exists($this->lockFile)) {
        return false;
    }

    $lockContent = @file_get_contents($this->lockFile);
    if ($lockContent === false) {
        return false;
    }

    $pid = (int)trim($lockContent);

    // On Unix-like systems, check if process exists
    if (function_exists('posix_kill')) {
        return posix_kill($pid, 0);
    }

    // On Windows or without posix, check if process exists
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        $process = shell_exec("tasklist /FI \"PID eq $pid\"");
        return strpos($process, strval($pid)) !== false;
    }

    // Fallback: Check if lock file is older than 2 hours
    return filemtime($this->lockFile) > (time() - 7200);
}
 
    protected function createLockFile()
{
    $tempFile = $this->lockFile . '.' . getmypid();
    file_put_contents($tempFile, getmypid());
    rename($tempFile, $this->lockFile);
}

    protected function removeLockFile()
    {
        if (file_exists($this->lockFile)) {
            unlink($this->lockFile);
        }
    }

    protected function backupDatabase()
    {
        $dbDumpPath = $this->tempPath . '/database.sql';
        
        try {
            MySql::create()
            ->setDbName(config('database.connections.mysql.database'))
            ->setUserName(config('database.connections.mysql.username'))
            ->setPassword(config('database.connections.mysql.password'))
            ->dumpToFile($dbDumpPath);

        } catch (\Exception $e) {
            $mysqldumpPath = $this->findMysqldumpPath();
            
            if ($mysqldumpPath) {
                MySql::create()
                ->setDumpBinaryPath($mysqldumpPath)
                ->setDbName(config('database.connections.mysql.database'))
                ->setUserName(config('database.connections.mysql.username'))
                ->setPassword(config('database.connections.mysql.password'))
                ->dumpToFile($dbDumpPath);
            } else {
                $this->phpBackupDatabase($dbDumpPath);
            }
        }
    }

    protected function findMysqldumpPath()
    {
        $paths = [
            '"C:\\Program Files\\MySQL\\MySQL Server 8.0\\bin\\mysqldump.exe"',
            '"C:\\Program Files\\MySQL\\MySQL Server 5.7\\bin\\mysqldump.exe"',
            '/usr/bin/mysqldump',
            '/usr/local/mysql/bin/mysqldump',
            '/opt/homebrew/bin/mysqldump'
        ];
        
        foreach ($paths as $path) {
            if (is_executable(trim($path, '"'))) {
                return $path;
            }
        }
        
        return null;
    }

    protected function phpBackupDatabase($dbDumpPath)
    {
        $tables = \DB::select('SHOW TABLES');
        $output = "";
        
        foreach ($tables as $table) {
            $tableName = reset($table);
            
            if ($tableName === 'migrations') continue;
            
            $structure = \DB::select("SHOW CREATE TABLE `$tableName`");
            $output .= "\n\n-- Table structure for `$tableName`\n";
            $output .= "DROP TABLE IF EXISTS `$tableName`;\n";
            $output .= $structure[0]->{'Create Table'} . ";\n\n";
            
            $rows = \DB::table($tableName)->get();
            if (count($rows) > 0) {
                $output .= "-- Data for `$tableName`\n";
                $output .= "LOCK TABLES `$tableName` WRITE;\n";
                
                foreach ($rows as $row) {
                    $values = array_map(function($value) {
                        if (is_null($value)) return 'NULL';
                        if (is_numeric($value)) return $value;
                        return "'" . addslashes($value) . "'";
                    }, (array)$row);
                    
                    $output .= "INSERT INTO `$tableName` VALUES (" . implode(', ', $values) . ");\n";
                }
                
                $output .= "UNLOCK TABLES;\n";
            }
        }
        
        file_put_contents($dbDumpPath, $output);
    }

    protected function backupStorage()
{
    $storagePath = $this->tempPath . '/storage';

    if (!file_exists($storagePath)) {
        if (!mkdir($storagePath, 0755, true) && !is_dir($storagePath)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $storagePath));
        }
    }

    // Backup documents disk if configured
    if (config('filesystems.disks.documents')) {
        $documentsPath = Storage::disk('documents')->path('');
        $destination = $storagePath . '/documents';

        if (!file_exists($destination)) {
            mkdir($destination, 0755, true);
        }

        $this->copyDirectory($documentsPath, $destination);
    }

    // Backup the main storage but exclude the backup-temp directory
    $this->copyDirectory(
        storage_path('app'),
        $storagePath . '/storage',
        ['backup-temp'] // exclude this directory
    );
}

protected function copyDirectory($source, $destination, array $exclude = [])
{
    $source = rtrim($source, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    $destination = rtrim($destination, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

    if (!file_exists($destination)) {
        if (!mkdir($destination, 0755, true) && !is_dir($destination)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $destination));
        }
    }

    $dir = opendir($source);
    if (!$dir) {
        throw new \Exception("Failed to open source directory: {$source}");
    }

    while (($file = readdir($dir)) !== false) {
        if ($file === '.' || $file === '..' || in_array($file, $exclude)) {
            continue;
        }

        $sourceFile = $source . $file;
        $destFile = $destination . $file;

        if (is_dir($sourceFile)) {
            $this->copyDirectory($sourceFile, $destFile, $exclude);
        } else {
            if (!copy($sourceFile, $destFile)) {
                throw new \Exception("Failed to copy file: {$sourceFile} to {$destFile}");
            }
        }
    }
    closedir($dir);
}

    protected function createArchive()
{
    $archivePath = $this->tempPath . '/' . $this->backupName . '.zip';
    
    $zip = new ZipArchive();
    if ($zip->open($archivePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
        throw new \Exception('Failed to create zip archive');
    }

    // Add files from the temp directory to the zip
    $files = new \RecursiveIteratorIterator(
        new \RecursiveDirectoryIterator($this->tempPath),
        \RecursiveIteratorIterator::LEAVES_ONLY
    );

    // Count total files for progress (optional)
    $totalFiles = iterator_count($files);
    $processedFiles = 0;
    
    // Rewind iterator after counting
    $files->rewind();

    foreach ($files as $name => $file) {
        // Skip directories (they would be added automatically)
        if (!$file->isDir()) {
            // Get real and relative path for current file
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($this->tempPath) + 1);

            // Add current file to archive
            $zip->addFile($filePath, $relativePath);
            
            // Set compression for each file (optional)
           // $zip->setCompressionName($relativePath, ZipArchive::CM_DEFLATE);
            $zip->setCompressionName($relativePath, ZipArchive::CM_STORE); // No compression (faster) for large file
            
            // Update progress (you could store this in cache/session/database)
            $processedFiles++;
            $this->updateBackupProgress($processedFiles, $totalFiles);
        }
    }

    // Zip archive will be created only after closing object
    if (!$zip->close()) {
        throw new \Exception('Failed to finalize zip archive');
    }

    return $archivePath;
}

protected function updateBackupProgress($processed, $total)
{
    // Example: Store progress in cache (adjust as needed)
    $progress = [
        'processed' => $processed,
        'total' => $total,
        'percentage' => $total > 0 ? round(($processed / $total) * 100) : 0,
        'timestamp' => now()
    ];
    
    cache()->put('backup_progress_'.auth()->id(), $progress, now()->addMinutes(10));
}

    protected function storeArchive($archivePath)
    {
        $finalPath = $this->backupPath . '/' . basename($archivePath);
        
        // Ensure backup directory exists
        if (!Storage::disk($this->disk)->exists($this->backupPath)) {
            Storage::disk($this->disk)->makeDirectory($this->backupPath);
        }
        
        Storage::disk($this->disk)->put($finalPath, file_get_contents($archivePath));
        return $finalPath;
    }

    protected function cleanup()
    {
        if (file_exists($this->tempPath)) {
            $this->deleteDirectory($this->tempPath);
        }
    }

    protected function deleteDirectory($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }

        return rmdir($dir);
    }

    
}