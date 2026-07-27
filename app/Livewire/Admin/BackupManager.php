<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class BackupManager extends Component
{
    use WithFileUploads;

    public $backups = [];
    public $uploadFile;

    public function mount()
    {
        if (!auth()->user()->hasPermissionTo('view-backups')) {
            abort(403, 'Unauthorized action.');
        }
        
        $this->ensureBackupDirectoryExists();
        $this->loadBackups();
    }

    protected function ensureBackupDirectoryExists()
    {
        if (!Storage::exists('backups')) {
            Storage::makeDirectory('backups');
        }
    }

    public function loadBackups()
    {
        $this->backups = [];
        
        // Ensure directory exists
        if (!Storage::exists('backups')) {
            Storage::makeDirectory('backups');
        }

        $files = Storage::files('backups');
        
        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
                $this->backups[] = [
                    'name' => basename($file),
                    'path' => $file,
                    'size' => $this->formatBytes(Storage::size($file)),
                    'date' => Carbon::createFromTimestamp(Storage::lastModified($file))->format('Y-m-d H:i:s'),
                    'timestamp' => Storage::lastModified($file),
                ];
            }
        }
        
        // Sort by newest first
        usort($this->backups, function ($a, $b) {
            return $b['timestamp'] <=> $a['timestamp'];
        });
    }

    protected function getMysqlPath($command = 'mysqldump')
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

    public function createBackup()
    {
        if (!auth()->user()->hasPermissionTo('create-backup')) {
            $this->dispatch('notify', ['message' => 'Unauthorized', 'type' => 'error']);
            return;
        }

        $filename = 'backup-' . date('Y-m-d-H-i-s') . '.sql';
        
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

            $this->dispatch('notify', ['message' => 'Backup created successfully!', 'type' => 'success']);
            $this->loadBackups();
        } catch (\Exception $e) {
            Log::error('Backup failed: ' . $e->getMessage());
            $this->dispatch('notify', ['message' => 'Failed to create backup. See logs for details.', 'type' => 'error']);
        }
    }

    public function deleteBackup($filename)
    {
        if (!auth()->user()->hasPermissionTo('delete-backup')) {
            $this->dispatch('notify', ['message' => 'Unauthorized', 'type' => 'error']);
            return;
        }

        $path = 'backups/' . $filename;
        if (Storage::exists($path)) {
            Storage::delete($path);
            $this->dispatch('notify', ['message' => 'Backup deleted successfully!', 'type' => 'success']);
            $this->loadBackups();
        }
    }

    public function downloadBackup($filename)
    {
        if (!auth()->user()->hasPermissionTo('view-backups')) {
            return;
        }

        $path = 'backups/' . $filename;
        if (Storage::exists($path)) {
            return Storage::download($path);
        }
    }

    public function restoreBackup($filename)
    {
        if (!auth()->user()->hasPermissionTo('restore-backup')) {
            $this->dispatch('notify', ['message' => 'Unauthorized', 'type' => 'error']);
            return;
        }

        $path = Storage::path('backups/' . $filename);
        
        if (!Storage::exists('backups/' . $filename)) {
            $this->dispatch('notify', ['message' => 'Backup file not found.', 'type' => 'error']);
            return;
        }

        $dbHost = config('database.connections.mysql.host');
        if ($dbHost === 'localhost') {
            $dbHost = '127.0.0.1'; // Force TCP connection to avoid socket issues
        }
        $dbPort = config('database.connections.mysql.port');
        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');
        $dbPass = config('database.connections.mysql.password');

        $passwordParam = empty($dbPass) ? '' : "-p'{$dbPass}'";
        
        $mysqlPath = $this->getMysqlPath('mysql');
        $command = "{$mysqlPath} -h {$dbHost} -P {$dbPort} -u {$dbUser} {$passwordParam} {$dbName} < '{$path}'";

        try {
            $process = Process::fromShellCommandline($command);
            $process->setTimeout(300); // 5 minutes timeout
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            $this->dispatch('notify', ['message' => 'Database restored successfully!', 'type' => 'success']);
        } catch (\Exception $e) {
            Log::error('Restore failed: ' . $e->getMessage());
            $this->dispatch('notify', ['message' => 'Failed to restore backup. See logs for details.', 'type' => 'error']);
        }
    }

    public function uploadBackupFile()
    {
        if (!auth()->user()->hasPermissionTo('create-backup')) {
            $this->dispatch('notify', ['message' => 'Unauthorized', 'type' => 'error']);
            return;
        }

        $this->validate([
            'uploadFile' => 'required|file|mimes:sql,txt|max:102400', // max 100MB (mime sometimes detected as txt)
        ]);

        $filename = 'uploaded-' . date('Y-m-d-H-i-s') . '.sql';
        $this->uploadFile->storeAs('backups', $filename);
        
        $this->uploadFile = null;
        $this->dispatch('notify', ['message' => 'Backup uploaded successfully!', 'type' => 'success']);
        $this->loadBackups();
    }

    protected function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    public function render()
    {
        return view('livewire.admin.backup-manager')
            ->extends('admin.layouts.app')
            ->section('content');
    }
}
