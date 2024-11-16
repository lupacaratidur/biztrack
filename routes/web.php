<?php

use App\Http\Controllers\CabangController;
use App\Http\Controllers\DataPenjualanController;
use App\Http\Controllers\HakAksesController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LaporanPenjualanController;
use App\Http\Controllers\MakananController;
use App\Http\Controllers\MenuKasirController;
use App\Http\Controllers\MinumanController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\RekapPemasukanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TrenPenjualanController;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\ReportIssueController;

Auth::routes();

// Group route dengan middleware 'auth' dan 'filterCabang'
Route::middleware(['auth', 'filterCabang'])->group(function() {

    // Group dengan middleware checkRole untuk beberapa role
    Route::group(['middleware' => 'checkRole:administrator,owner,admin,kasir,teknisi'], function() {

        // Route Home, redirect teknisi ke halaman backup
        Route::get('/', function() {
            if (auth()->check() && auth()->user()->role === 'teknisi') {
                return redirect()->route('backup');
            }
            return app(HomeController::class)->index();
        })->name('home');
        
        // Route Data Penjualan
        Route::get('/data-penjualan/get-data/', [DataPenjualanController::class, 'getData']);
        Route::resource('/data-penjualan', DataPenjualanController::class);

        // Route untuk Tren Penjualan
        Route::get('/tren-penjualan', [TrenPenjualanController::class, 'index'])->name('tren-penjualan');

        // Route untuk Backup dan Restore Database
        Route::get('/backup', [DatabaseController::class, 'index'])->name('backup');
        Route::get('/backup-database', [DatabaseController::class, 'backup'])->name('backup.database');
        Route::post('/restore-database', [DatabaseController::class, 'restore'])->name('restore.database');

        // Group routes terkait report-issues
        Route::prefix('report-issues')->group(function() {
            // Route untuk melihat daftar laporan user
            Route::get('/', [ReportIssueController::class, 'index'])->name('report-issues.index');

            // Route untuk menampilkan form pembuatan laporan
            Route::get('/create', [ReportIssueController::class, 'create'])->name('report-issues.create');

            // Route untuk menyimpan laporan yang baru dibuat
            Route::post('/store', [ReportIssueController::class, 'store'])->name('report-issues.store');


        });

    });

    // Group khusus dengan middleware 'checkRole' untuk role tertentu
    Route::group(['middleware' => 'checkRole:administrator,owner,admin'], function() {
        // Routes untuk Makanan
        Route::get('/makanan/get-data', [MakananController::class, 'getData']);
        Route::resource('/makanan', MakananController::class);

        // Routes untuk Minuman
        Route::get('/minuman/get-data', [MinumanController::class, 'getData']);
        Route::resource('/minuman', MinumanController::class);

        // Routes untuk Laporan Penjualan
        Route::get('/laporan-penjualan/get-data', [LaporanPenjualanController::class, 'getData']);
        Route::get('/laporan-penjualan/print-laporan-penjualan', [LaporanPenjualanController::class, 'getData']);
        Route::resource('/laporan-penjualan', LaporanPenjualanController::class);

        // Routes untuk Rekap Pemasukan
        Route::get('/rekap-pemasukan/get-data', [RekapPemasukanController::class, 'getData']);
        Route::get('/rekap-pemasukan/print-rekap-pemasukan', [RekapPemasukanController::class, 'getData']);
        Route::resource('/rekap-pemasukan', RekapPemasukanController::class);
    });

    // Group khusus dengan middleware 'checkRole' untuk administrator dan owner
    Route::group(['middleware' => 'checkRole:administrator,owner'], function() {
        // Routes untuk Cabang
        Route::get('/cabang/get-data', [CabangController::class, 'getData']);
        Route::resource('/cabang', CabangController::class);
    });

    // Group khusus dengan middleware 'checkRole' untuk administrator
    Route::group(['middleware' => 'checkRole:administrator,owner'], function() {
        // Routes untuk Menu Kasir
        Route::resource('/menu-kasir', MenuKasirController::class);

        // Routes untuk Pengguna
        Route::get('/pengguna/get-data', [UserController::class, 'getData']);
        Route::get('/api/role/', [UserController::class, 'getRole']);
        Route::get('/api/cabang/', [UserController::class, 'getCabang']);
        Route::resource('/pengguna', UserController::class);

        // Routes untuk Hak Akses
        Route::get('/hak-akses/get-data', [HakAksesController::class, 'getData']);
        Route::resource('/hak-akses', HakAksesController::class);
    });

    // Group khusus dengan middleware 'checkRole' untuk administrator dan kasir
    Route::group(['middleware' => 'checkRole:administrator,kasir'], function() {
        Route::resource('/menu-kasir', MenuKasirController::class);
        Route::post('/menu-kasir', [PembelianController::class, 'pembelian']);
        Route::post('/menu-kasir/paid', [PembelianController::class, 'updateStatusPembayaran']);
    });
});
