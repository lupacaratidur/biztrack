<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use ZipArchive;
use Artisan;

class DatabaseController extends Controller
{

    public function index(){
        return view('backup-database.backup-restore');
    }


    public function backup()
    {
        Log::info('Memulai proses backup database.');
    
        // Nama file backup
        $timestamp = now()->format('Y-m-d_H-i-s');
        $backupFileName = storage_path("app/backup/database_backup_{$timestamp}.sql");
    
        // Pastikan direktori backup ada
        if (!File::exists(storage_path('app/backup'))) {
            File::makeDirectory(storage_path('app/backup'), 0755, true);
            Log::info('Direktori backup dibuat: ' . storage_path('app/backup'));
        }
    
        // Ambil kredensial database dari konfigurasi
        $dbHost = config('database.connections.mysql.host');
        $dbDatabase = config('database.connections.mysql.database');
        $dbUsername = config('database.connections.mysql.username');
        $dbPassword = config('database.connections.mysql.password');
    
        // Path lengkap ke mysqldump di XAMPP
        $mysqldumpPath = 'C:\\xampp\\mysql\\bin\\mysqldump.exe';
    
        // Melakukan dump database menggunakan mysqldump
        $command = sprintf(
            '"%s" --user=%s --password=%s --host=%s %s > %s',
            $mysqldumpPath,
            escapeshellarg($dbUsername),
            escapeshellarg($dbPassword),
            escapeshellarg($dbHost),
            escapeshellarg($dbDatabase),
            escapeshellarg($backupFileName)
        );
    
        // Eksekusi command
        exec($command, $output, $status);
    
        if ($status === 0 && File::exists($backupFileName)) {
            Log::info('Backup berhasil dilakukan. File backup: ' . $backupFileName);
            return back()->with('success', 'Backup berhasil dilakukan.');
        } else {
            $errorMessage = 'Backup gagal dilakukan! Pastikan Anda memiliki akses yang cukup untuk menjalankan mysqldump.';
            if (!File::exists($mysqldumpPath)) {
                $errorMessage .= ' File mysqldump tidak ditemukan di: ' . $mysqldumpPath;
            }
            Log::error($errorMessage);
            return back()->with('error', $errorMessage);
        }
    }



    public function restore(Request $request)
    {
        Log::info('Memulai proses restore database.');

        try {
            $request->validate([
                'database_file' => 'required|file'
            ]);

            $file = $request->file('database_file');
            if ($file->getClientOriginalExtension() !== 'sql') {
                throw new \Exception('File yang diunggah harus berformat .sql');
            }

            $filePath = storage_path('app/backup/' . $file->getClientOriginalName());
            File::copy($file->getRealPath(), $filePath);

            $this->dropAllTables();

            $dbUser = config('database.connections.mysql.username');
            $dbHost = config('database.connections.mysql.host');
            $dbName = config('database.connections.mysql.database');
            $mysqlPath = 'C:\\xampp\\mysql\\bin\\mysql.exe';

            $command = sprintf(
                '"%s" --user=%s --host=%s %s < %s',
                $mysqlPath,
                escapeshellarg($dbUser),
                escapeshellarg($dbHost),
                escapeshellarg($dbName),
                escapeshellarg($filePath)
            );

            exec($command, $output, $status);

            if ($status === 0) {
                Log::info('Database berhasil di-restore!');
                return back()->with('success', 'Database berhasil di-restore!');
            } else {
                $errorMessage = implode("\n", $output);
                Log::error('Restore gagal: ' . $errorMessage);
                return back()->with('error', "Restore gagal dilakukan! Error: {$errorMessage}");
            }
        } catch (\Exception $e) {
            Log::error('Error selama proses restore: ' . $e->getMessage());
            return back()->with('error', 'Terjadi error selama proses restore. ' . $e->getMessage());
        }
    }

    
    
    
    public function dropAllTables()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        $tables = DB::select('SHOW TABLES');
        $dbName = config('database.connections.mysql.database');
        $tableKey = 'Tables_in_' . $dbName;
        
        foreach ($tables as $table) {
            DB::statement('DROP TABLE IF EXISTS ' . $table->$tableKey);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
