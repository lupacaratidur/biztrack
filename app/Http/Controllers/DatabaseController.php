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

    // Ambil kredensial database dari konfigurasi (bukan langsung dari .env)
    $dbHost = config('database.connections.mysql.host');
    $dbDatabase = config('database.connections.mysql.database');
    $dbUsername = config('database.connections.mysql.username');
    $dbPassword = config('database.connections.mysql.password');

    // Log untuk memastikan kredensial terbaca dengan benar
    Log::info('DB_DATABASE dari config: ' . $dbDatabase);
    Log::info('DB_USERNAME dari config: ' . $dbUsername);
    Log::info('DB_PASSWORD dari config: ' . ($dbPassword ? 'Set' : 'Tidak ada password'));

    // Path lengkap ke mysqldump
    $mysqldumpPath = 'D:\\Application\\laragon\\bin\\mysql\\mysql-8.0.30-winx64\\bin\\mysqldump.exe';

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

    // Log perintah yang akan dieksekusi
    Log::info('Perintah mysqldump yang akan dieksekusi: ' . $command);

    // Eksekusi command
    exec($command, $output, $status);

    // Log hasil eksekusi
    Log::info('Status eksekusi command: ' . $status);
    Log::info('Output eksekusi command: ' . implode("\n", $output));

    // Mengecek hasil eksekusi command
    if ($status === 0 && File::exists($backupFileName)) {
        Log::info('Backup berhasil dilakukan. File backup: ' . $backupFileName);
        return back()->with('success', 'Backup berhasil dilakukan.');
    } else {
        $errorMessage = 'Backup gagal dilakukan! Pastikan Anda memiliki akses yang cukup untuk menjalankan mysqldump.';
        if (!File::exists($mysqldumpPath)) {
            $errorMessage .= ' File mysqldump tidak ditemukan di: ' . $mysqldumpPath;
        }
        if (!File::exists(storage_path('app/backup'))) {
            $errorMessage .= ' Direktori backup tidak ada atau tidak dapat dibuat.';
        }
        Log::error($errorMessage);
        return back()->with('error', $errorMessage);
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

    public function restore(Request $request)
    {
        Log::info('Memulai proses restore database');
    
        try {
            // Validasi bahwa file ada
            $request->validate([
                'database_file' => 'required|file'
            ]);
            Log::info('File berhasil divalidasi ada.');
    
            // Pastikan file memiliki ekstensi .sql
            $file = $request->file('database_file');
            if ($file->getClientOriginalExtension() !== 'sql') {
                throw new \Exception('File yang diunggah harus berformat .sql');
            }
    
            Log::info('File memiliki ekstensi .sql');
            
            // Salin file ke lokasi permanen
            $filePath = storage_path('app' . DIRECTORY_SEPARATOR . 'backup' . DIRECTORY_SEPARATOR . $file->getClientOriginalName());
            File::copy($file->getRealPath(), $filePath);
            Log::info('File path untuk restore (permanen): ' . $filePath);
    
            // Hapus semua tabel sebelum restore
            $this->dropAllTables();
            Log::info('Semua tabel di database telah dihapus.');
    
            // Ambil kredensial database dari config
            $dbUser = config('database.connections.mysql.username');
            $dbHost = config('database.connections.mysql.host');
            $dbName = config('database.connections.mysql.database');
    
            // Path lengkap ke mysql yang kamu berikan
            $mysqlPath = 'D:\\Application\\laragon\\bin\\mysql\\mysql-8.0.30-winx64\\bin\\mysql.exe';
    
            // Buat file batch sementara untuk menjalankan perintah restore
            $batchFile = storage_path('app' . DIRECTORY_SEPARATOR . 'backup' . DIRECTORY_SEPARATOR . 'restore.bat');
            $batchCommand = sprintf(
                'cd /d D:\\Application\\laragon\\bin\\mysql\\mysql-8.0.30-winx64\\bin && mysql --force --user=%s --host=%s %s < %s 2>&1',
                escapeshellarg($dbUser),
                escapeshellarg($dbHost),
                escapeshellarg($dbName),
                escapeshellarg($filePath)
            );
    
            // Tulis command ke file batch
            file_put_contents($batchFile, $batchCommand);
            Log::info('File batch dibuat: ' . $batchFile);
    
            // Eksekusi file batch dan tangkap output
            exec('cmd /c ' . escapeshellarg($batchFile), $output, $status);
            Log::info('Status eksekusi batch file: ' . $status);
            Log::info('Output eksekusi batch file: ' . implode("\n", $output));
    
            // Hapus file batch setelah selesai
            unlink($batchFile);
    
            if ($status === 0) {
                Log::info('Database berhasil di-restore!');
                return back()->with('success', 'Database berhasil di-restore!');
            } else {
                // Jika terjadi error, kirim output error sebagai pesan tambahan
                $errorMessage = implode("\n", $output);
                Log::error('Restore gagal: ' . $errorMessage);
                return back()->with('error', "Restore gagal dilakukan! Pastikan file yang di-upload valid dan formatnya benar.\nError: {$errorMessage}");
            }
        } catch (\Exception $e) {
            Log::error('Error selama proses restore: ' . $e->getMessage());
            return back()->with('error', 'Terjadi error selama proses restore. ' . $e->getMessage());
        }
    }

    
    
}
