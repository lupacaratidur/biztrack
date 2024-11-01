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


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();
Route::middleware('auth', 'filterCabang')->group(function(){
    Route::group(['middleware' => 'checkRole:administrator,kepala restoran,admin,kasir'], function(){
        Route::get('/', [HomeController::class, 'index'])->name('home');

        Route::get('/data-penjualan/get-data/', [DataPenjualanController::class, 'getData']);
        Route::resource('/data-penjualan', DataPenjualanController::class);
    });

    Route::group(['middleware' => 'checkRole:administrator,kepala restoran,admin'], function(){
        Route::get('/makanan/get-data', [MakananController::class, 'getData']);
        Route::resource('/makanan', MakananController::class);
    
        Route::get('/minuman/get-data', [MinumanController::class, 'getData']);
        Route::resource('/minuman', MinumanController::class);

        Route::get('/laporan-penjualan/get-data', [LaporanPenjualanController::class, 'getData']);
        Route::get('/laporan-penjualan/print-laporan-penjualan', [LaporanPenjualanController::class, 'getData']);
        Route::resource('/laporan-penjualan', LaporanPenjualanController::class);

        Route::get('/rekap-pemasukan/get-data', [RekapPemasukanController::class, 'getData']);
        Route::get('/rekap-pemasukan/print-rekap-pemasukan', [RekapPemasukanController::class, 'getData']);
        Route::resource('/rekap-pemasukan', RekapPemasukanController::class);
    });

    Route::group(['middleware' => 'checkRole:administrator,kepala restoran'], function(){
        Route::get('/cabang/get-data', [CabangController::class, 'getData']);
        Route::resource('/cabang', CabangController::class);
    });

    Route::group(['middleware' => 'checkRole:administrator'], function(){
        Route::resource('/menu-kasir', MenuKasirController::class);
        
        Route::get('/pengguna/get-data', [UserController::class, 'getData']);
        Route::get('/api/role/', [UserController::class, 'getRole']);
        Route::get('/api/cabang/', [UserController::class, 'getCabang']);
        Route::resource('/pengguna', UserController::class);
    
        Route::get('/hak-akses/get-data', [HakAksesController::class, 'getData']);
        Route::resource('/hak-akses', HakAksesController::class);
    });


    Route::group(['middleware' => 'checkRole:administrator,kasir'], function(){
        Route::resource('/menu-kasir', MenuKasirController::class);
        Route::post('/menu-kasir', [PembelianController::class, 'pembelian']);
        Route::post('/menu-kasir/paid', [PembelianController::class, 'updateStatusPembayaran']);
    });



});


