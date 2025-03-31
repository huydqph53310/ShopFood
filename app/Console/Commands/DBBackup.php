<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;

class DBBackup extends Command
{
    protected $signature = 'app:dbbackup';
    protected $description = 'Backup MySQL database';

    public function handle()
    {
        $mysqlDumpPath = 'C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysqldump.exe';
        $path = storage_path("app/backup/" . now()->format("d-m-Y-H-i-s") . ".sql");

        // Kiểm tra và tạo thư mục nếu chưa có
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }

        // Lệnh backup
        $command = [
            "\"$mysqlDumpPath\"",
            "--user=" . env('DB_USERNAME'),
            "--password=" . env('DB_PASSWORD'),
            "--host=" . env('DB_HOST'),
            env('DB_DATABASE'),
            "> \"$path\""
        ];

        // Chạy lệnh backup
        $process = Process::run(implode(' ', $command));

        if ($process->successful()) {
            $this->info('Database backup saved as: ' . basename($path));
        } else {
            $this->error('Backup failed: ' . $process->errorOutput());
        }
    }
}
