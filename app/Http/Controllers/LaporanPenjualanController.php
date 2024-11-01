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

        if ($userRole === 'administrator' || $userRole === 'kepala restoran') {
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
        $user           = auth()->user();
        $selectedOption = $request->input('opsi');
        $tanggalMulai   = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');

        // Logika pemilihan data berdasarkan cabang dan rentang tanggal
        if ($user->role->role === 'administrator' || $user->role->role === 'kepala restoran') {
            if ($selectedOption == '' || $selectedOption === 'Semua Cabang') {
                $pembelians = Pembelian::with('detailPembelians')->orderBy('id', 'DESC')->get();
            } else {
                $pembelians = Pembelian::with('detailPembelians')
                    ->where('cabang_id', $selectedOption)
                    ->orderBy('id', 'DESC')
                    ->get();
            }
        } else {
            if ($selectedOption == '' || $selectedOption === 'Semua Cabang') {
                $pembelians = Pembelian::with('detailPembelians')
                    ->where('cabang_id', $user->cabang_id)
                    ->orderBy('id', 'DESC')
                    ->get();
            } else {
                $pembelians = Pembelian::with('detailPembelians')
                    ->where('cabang_id', $user->cabang_id)
                    ->where('cabang_id', $selectedOption)
                    ->orderBy('id', 'DESC')
                    ->get();
            }
        }

        if ($tanggalMulai !== null && $tanggalSelesai !== null) {
            $pembelians = $pembelians->whereBetween('tgl_transaksi', [$tanggalMulai, $tanggalSelesai]);
        }

        if ($request->has('print_pdf')) {
            $data = [
                'pembelians'        => $pembelians,
                'selectedOption'    => $selectedOption,
                'tanggalMulai'      => $tanggalMulai,
                'tanggalSelesai'    => $tanggalSelesai
            ];
            $dompdf = new Dompdf();
            $dompdf->setPaper('A4', 'portrait');
            $html = view('/laporan-penjualan/print-laporan-penjualan', compact('data'))->render();
            $dompdf->loadHtml($html);
            $dompdf->render();
            $dompdf->stream('laporan_penjualan.pdf');
        }

        return response()->json([
            'success'   => true,
            'data'      => $pembelians
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }
}