<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\PDF;
use App\Models\Cabang;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Dompdf\Dompdf;

class LaporanPenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user       = auth()->user();
        $userRole   = auth()->user()->role->role;
        $bulanIni   = Carbon::now()->format('m');
        $hariIni    = Carbon::now()->format('Y-m-d');

        if ($userRole === 'administrator' || $userRole === 'owner') {
            $transaksiBulanIni   = Pembelian::whereMonth('tgl_transaksi', $bulanIni)
                ->count();
            $transaksiBulanLalu  = Pembelian::whereMonth('tgl_transaksi', '=', Carbon::now()->subMonth()->format('m'))
                ->count();

            $transaksiHariIni    = Pembelian::whereDate('tgl_transaksi', $hariIni)
                ->count();
            $transaksiKemarin    = Pembelian::whereDate('tgl_transaksi', '=', Carbon::now()->subDay()->format('Y-m-d'))
                ->count();
        } else {
            $transaksiBulanIni   = Pembelian::whereMonth('tgl_transaksi', $bulanIni)
                ->where('cabang_id', $user->cabang_id)
                ->count();
            $transaksiBulanLalu  = Pembelian::whereMonth('tgl_transaksi', '=', Carbon::now()->subMonth()->format('m'))
                ->where('cabang_id', $user->cabang_id)
                ->count();

            $transaksiHariIni    = Pembelian::whereDate('tgl_transaksi', $hariIni)
                ->where('cabang_id', $user->cabang_id)
                ->count();
            $transaksiKemarin    = Pembelian::whereDate('tgl_transaksi', '=', Carbon::now()->subDay()->format('Y-m-d'))
                ->where('cabang_id', $user->cabang_id)
                ->count();
        }

        return view('laporan-penjualan.index', [
            'cabangs'               => Cabang::all(),
            'transaksiBulanIni'     => $transaksiBulanIni,
            'transaksiBulanLalu'    => $transaksiBulanLalu,
            'transaksiHariIni'      => $transaksiHariIni,
            'transaksiKemarin'      => $transaksiKemarin
        ]);
    }

    /**
     * Get Data Penjualan
     */
    public function getData(Request $request)
    {
        $startDate = $request->query('tanggal_mulai');
        $endDate = $request->query('tanggal_selesai');
        $selectedBranch = $request->query('cabang_id'); // Menangkap ID cabang dari filter
        $user = auth()->user();
    
        try {
            // Query dasar untuk data pembelian
            $query = Pembelian::with('detailPembelians')->orderBy('id', 'DESC');
    
            // Filter berdasarkan cabang
            if ($user->role->role === 'administrator' || $user->role->role === 'owner') {
                if ($selectedBranch && $selectedBranch !== 'Semua Cabang') {
                    $query->where('cabang_id', $selectedBranch);
                }
            } else {
                $query->where('cabang_id', $user->cabang_id);
            }
    
            // Filter berdasarkan rentang tanggal (jika tanggal diberikan)
            if ($startDate && $endDate) {
                $startDate = Carbon::parse($startDate)->format('Y-m-d');
                $endDate = Carbon::parse($endDate)->format('Y-m-d');
                $query->whereBetween('tgl_transaksi', [$startDate, $endDate]);
            }
    
            // Ambil data setelah filter
            $pembelians = $query->get();
    
            // Format data untuk grafik atau tampilan tabel
            $dataFormatted = $pembelians->map(function ($item) {
                return [
                    'id' => $item->id,
                    'kode_pembelian' => $item->kode_pembelian,
                    'total_harga' => $item->total_harga,
                    'tgl_transaksi' => $item->tgl_transaksi,
                    'cabang_id' => $item->cabang_id,
                    'detail_pembelians' => $item->detailPembelians->map(function ($detail) {
                        return [
                            'nama' => $detail->nama,
                            'quantity' => $detail->quantity
                        ];
                    }),
                ];
            });
    
            // Kembalikan data sebagai JSON
            return response()->json([
                'success' => true,
                'data' => $dataFormatted
            ]);
        } catch (\Exception $e) {
            // Tangkap error dan kembalikan response
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
    



    
    public function show(string $id)
    {
        //
    }
}