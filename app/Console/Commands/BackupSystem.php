<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BackupDatabase extends Command
{
    protected $signature = 'db:backup';
    protected $description = 'Backup the SQLite database';

    public function handle()
    {
        $timestamp = now()->format('Y-m-d_H-i-s');

        $db = database_path('database.sqlite');
        $backupPath = storage_path("backups/backup_$timestamp.sqlite");

        // Ensure backup directory exists
        if (!is_dir(storage_path('backups'))) {
            mkdir(storage_path('backups'), 0777, true);
        }

        copy($db, $backupPath);

        $this->info("Backup created: $backupPath");
    }
}
