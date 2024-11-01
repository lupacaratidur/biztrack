<?php

namespace App\Http\Controllers;

use App\Models\Makanan;
use App\Models\Minuman;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\DetailPembelian;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Cabang;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $trxHariIni = Carbon::now()->format('Y-m-d');
        $user       = auth()->user();
        $userRole   = $user->role->role;

        $pemasukanPerCabang = Pembelian::select('cabang_id', DB::raw('SUM(total_harga) as total_pemasukan'))
            ->groupBy('cabang_id')
            ->pluck('total_pemasukan', 'cabang_id');
        $cabangNames = Cabang::whereIn('id', $pemasukanPerCabang->keys())->pluck('cabang', 'id');

        if ($userRole === 'administrator' || $userRole === 'kepala restoran') {
            $totalTransaksi     = Pembelian::count();
            $pemasukanHariIni   = Pembelian::whereDate('tgl_transaksi', $trxHariIni)
                ->sum('total_harga');
            $semuaPemasukan     = Pembelian::sum('total_harga');

            $grafikPenjualan = Pembelian::selectRaw('DATE(tgl_transaksi) as date, COUNT(*) as total')
                ->whereBetween('tgl_transaksi', [
                    Carbon::now()->startOfWeek(Carbon::MONDAY), // Mulai dari hari Senin pada minggu ini
                    Carbon::now()->endOfWeek(Carbon::SUNDAY) // Sampai dengan hari Minggu pada minggu ini
                ])
                ->groupBy('date')
                ->get()
                ->map(function ($data) {
                    $data->date = Carbon::parse($data->date)->format('Y-m-d');
                    $data->total = (int)$data->total;
                    return $data;
            });
        } else {
            $totalTransaksi = Pembelian::where('cabang_id', $user->cabang_id)->count();
            $pemasukanHariIni = Pembelian::whereDate('tgl_transaksi', $trxHariIni)
                ->where('cabang_id', $user->cabang_id)
                ->sum('total_harga');
            $semuaPemasukan = Pembelian::where('cabang_id', $user->cabang_id)->sum('total_harga');
            $grafikPenjualan  = Pembelian::where('cabang_id', $user->cabang_id)->selectRaw('DATE(tgl_transaksi) as date, COUNT(*) as total')
                ->groupBy('date')
                ->get()
                ->map(function ($data) {
                    $data->date = Carbon::parse($data->date)->format('Y-m-d');
                    $data->total = (int) $data->total;
                    return $data;
                });
        }

        return view('dashboard', [
            'totalTransaksi'    => $totalTransaksi,
            'pemasukanHariIni'  => $pemasukanHariIni,
            'semuaPemasukan'    => $semuaPemasukan,
            'grafikPenjualan'   => $grafikPenjualan,
            'pemasukanPerCabang'=> $pemasukanPerCabang,
            'cabangNames'       => $cabangNames,
        ]); 
    }

}
