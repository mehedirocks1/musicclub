<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Notifications\Notification;
use BackedEnum; 

class Backup extends Page
{
    use \Livewire\WithFileUploads;

    protected static ?string $navigationLabel = 'Backup';
    protected static ?string $title = 'Backup';
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-cloud-arrow-down';
    protected string $view = 'filament.pages.backup';

    public int $websiteProgress = 0;
    public int $databaseProgress = 0;
    public string $statusMessage = '';

    /**
     * REAL Website Backup
     */
    public function backupWebsite()
    {
        set_time_limit(0); 
        ini_set('memory_limit', '-1');

        $this->websiteProgress = 0;
        $this->statusMessage = 'Scanning files...';
        $this->updateProgress('website', 0); // ✅ Helper method

        $rootPath = base_path(); 
        $backupDir = storage_path('backups');

        if (!file_exists($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $zipFile = $backupDir . '/website-backup-' . date('Y-m-d-His') . '.zip';
        $zip = new \ZipArchive();

        if ($zip->open($zipFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== TRUE) {
            $this->notifyError('Failed to create ZIP file permission error.');
            return;
        }

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($rootPath, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        $counter = 0;
        $ignoredFolders = ['vendor', 'node_modules', '.git', 'storage/backups'];

        foreach ($files as $file) {
            if (!$file->isFile()) continue;

            $filePath = $file->getRealPath();
            $relativePath = ltrim(str_replace($rootPath, '', $filePath), DIRECTORY_SEPARATOR);
            $relativePathForwardSlash = str_replace('\\', '/', $relativePath); 

            $shouldSkip = false;
            foreach ($ignoredFolders as $ignore) {
                if (str_starts_with($relativePathForwardSlash, $ignore)) {
                    $shouldSkip = true;
                    break;
                }
            }
            if ($shouldSkip) continue;

            $zip->addFile($filePath, $relativePath);
            $counter++;

            // Update UI every 50 files
            if ($counter % 50 === 0) {
                $this->statusMessage = "Archiving: " . basename($relativePath);
                // Just animate the bar slowly since calculating total files is slow
                $this->websiteProgress = ($this->websiteProgress >= 95) ? 95 : $this->websiteProgress + 1;
                $this->updateProgress('website', $this->websiteProgress);
            }
        }

        $zip->close();

        $this->statusMessage = 'Website backup completed!';
        $this->updateProgress('website', 100);
        $this->notifySuccess('Website backup created successfully.');
    }

    /**
     * REAL Database Backup (Using mysqldump)
     */
    public function backupDatabase()
    {
        $this->databaseProgress = 0;
        $this->statusMessage = 'Preparing database connection...';
        $this->updateProgress('database', 5);

        $filename = "db-backup-" . date('Y-m-d-His') . ".sql";
        $backupDir = storage_path('backups');
        if (!file_exists($backupDir)) mkdir($backupDir, 0755, true);
        
        $path = $backupDir . '/' . $filename;

        // 1. Get DB Credentials
        $dbHost = config('database.connections.mysql.host');
        $dbPort = config('database.connections.mysql.port');
        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');
        $dbPass = config('database.connections.mysql.password');

        // 2. Build Command
        // NOTE: On Windows (Laragon), mysqldump might need full path if not in ENV variables
        // e.g. "C:/laragon/bin/mysql/mysql-8.0/bin/mysqldump"
        $mysqldump = 'mysqldump'; 

        $command = sprintf(
            '%s --user=%s --password=%s --host=%s --port=%s %s > %s',
            $mysqldump,
            escapeshellarg($dbUser),
            escapeshellarg($dbPass),
            escapeshellarg($dbHost),
            escapeshellarg($dbPort),
            escapeshellarg($dbName),
            escapeshellarg($path)
        );

        $this->statusMessage = 'Exporting database (this may take a moment)...';
        $this->updateProgress('database', 30);

        // 3. Execute Command
        try {
            // exec() returns the last line of output, $returnVar returns exit code (0 = success)
            exec($command, $output, $returnVar);

            if ($returnVar !== 0) {
                // If it fails, usually it's because mysqldump is not in the system path
                throw new \Exception("mysqldump failed. Exit code: $returnVar. Make sure mysqldump is in your System PATH.");
            }

            // Optional: Zip the SQL file to save space
            $this->statusMessage = 'Compressing SQL file...';
            $this->updateProgress('database', 80);
            
            $zipPath = $path . '.zip';
            $zip = new \ZipArchive();
            if ($zip->open($zipPath, \ZipArchive::CREATE) === TRUE) {
                $zip->addFile($path, $filename);
                $zip->close();
                unlink($path); // Delete the raw SQL file
            }

        } catch (\Exception $e) {
            $this->statusMessage = 'Error: ' . $e->getMessage();
            $this->notifyError($e->getMessage());
            return;
        }

        $this->statusMessage = 'Database backup completed!';
        $this->updateProgress('database', 100);
        $this->notifySuccess('Database backup created successfully.');
    }

    /**
     * Helper to dispatch the correct event name to the frontend
     */
    protected function updateProgress($type, $percent)
    {
        if ($type === 'website') {
            $this->websiteProgress = $percent;
        } else {
            $this->databaseProgress = $percent;
        }

        // ✅ FIX: Dispatch 'updateProgress' (matching your JS)
        // Passes 'progress' in the detail
        $this->dispatch('updateProgress', progress: $percent);
    }

    protected function notifySuccess($msg)
    {
        Notification::make()->title($msg)->success()->send();
    }

    protected function notifyError($msg)
    {
        Notification::make()->title('Backup Failed')->body($msg)->danger()->send();
    }
}