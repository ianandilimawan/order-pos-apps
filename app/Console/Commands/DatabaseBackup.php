<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class DatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically backup the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filename = 'backup-auto-' . date('Y-m-d-H-i-s') . '.sql';
        
        if (!Storage::exists('backups')) {
            Storage::makeDirectory('backups');
        }
        $path = Storage::path('backups/' . $filename);

        $dbHost = config('database.connections.mysql.host');
        if ($dbHost === 'localhost') {
            $dbHost = '127.0.0.1'; // Force TCP connection to avoid socket issues
        }
        $dbPort = config('database.connections.mysql.port');
        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');
        $dbPass = config('database.connections.mysql.password');

        $passwordParam = empty($dbPass) ? '' : "-p'{$dbPass}'";
        
        $mysqldumpPath = $this->getMysqlPath('mysqldump');
        $command = "{$mysqldumpPath} -h {$dbHost} -P {$dbPort} -u {$dbUser} {$passwordParam} {$dbName} > '{$path}'";

        try {
            $process = Process::fromShellCommandline($command);
            $process->setTimeout(300); // 5 minutes timeout
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            $this->info("Backup created successfully at {$path}");
            Log::info("Automatic database backup created successfully: {$filename}");
        } catch (\Exception $e) {
            $this->error('Backup failed: ' . $e->getMessage());
            Log::error('Automatic backup failed: ' . $e->getMessage());
        }
    }

    private function getMysqlPath($command)
    {
        $paths = [
            '/opt/homebrew/bin/' . $command,
            '/usr/local/bin/' . $command,
            '/usr/bin/' . $command,
        ];
        
        foreach ($paths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }
        
        return $command; // fallback
    }
}
