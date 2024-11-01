<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use App\Models\Cabang;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

class RekapPemasukanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user               = auth()->user();
        $userRole           = auth()->user()->role->role;
        $bulanIni           = Carbon::now()->format('m');
        $hariIni            = Carbon::now()->format('Y-m-d');


        if ($userRole === 'administrator' || $userRole === 'kepala restoran') {
            $pemasukanBulanIni  = Pembelian::whereMonth('tgl_transaksi', $bulanIni)
                ->where('status', '=', 'paid')
                ->sum('total_harga');
            $pemasukanBulanLalu = Pembelian::whereMonth('tgl_transaksi', '=', Carbon::now()->subMonth()->format('m'))
                ->where('status', '=', 'paid')
                ->sum('total_harga');

            $pemasukanHariIni   = Pembelian::whereDate('tgl_transaksi', $hariIni)
                ->where('status', '=', 'paid')
                ->sum('total_harga');
            $pemasukanKemarin   = Pembelian::whereDate('tgl_transaksi', '=', Carbon::now()->subDay()->format('Y-m-d'))
                ->where('status', '=', 'paid')
                ->sum('total_harga');
        } else {
            $pemasukanBulanIni  = Pembelian::whereMonth('tgl_transaksi', $bulanIni)
                ->where('cabang_id', $user->cabang_id)
                ->where('status', '=', 'paid')
                ->sum('total_harga');
            $pemasukanBulanLalu = Pembelian::whereMonth('tgl_transaksi', '=', Carbon::now()->subMonth()->format('m'))
                ->where('cabang_id', $user->cabang_id)
                ->where('status', '=', 'paid')
                ->sum('total_harga');

            $pemasukanHariIni   = Pembelian::whereDate('tgl_transaksi', $hariIni)
                ->where('cabang_id', $user->cabang_id)
                ->where('status', '=', 'paid')
                ->sum('total_harga');
            $pemasukanKemarin   = Pembelian::whereDate('tgl_transaksi', '=', Carbon::now()->subDay()->format('Y-m-d'))
                ->where('cabang_id', $user->cabang_id)
                ->where('status', '=', 'paid')
                ->sum('total_harga');
        }
        return view('rekap-pemasukan.index', [
            'cabangs'               => Cabang::all(),
            'pemasukanBulanIni'     => $pemasukanBulanIni,
            'pemasukanBulanLalu'    => $pemasukanBulanLalu,
            'pemasukanHariIni'      => $pemasukanHariIni,
            'pemasukanKemarin'      => $pemasukanKemarin,
        ]);
    }

    /**
     * Get Data 
     */
    public function getData(Request $request)
    {
        $user           = auth()->user();
        $selectedOption = $request->input('opsi');
        $tanggalMulai   = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');

        if ($user->role->role === 'administrator' || $user->role->role === 'kepala restoran') {
            if ($selectedOption == '' || $selectedOption === 'Semua Cabang') {
                $pembelians = Pembelian::where('status', '=', 'paid')->orderBy('id', 'DESC')->get();
            } else {
                $pembelians = Pembelian::where('cabang_id', $selectedOption)->where('status', '=', 'paid')->orderBy('id', 'DESC')->get();
            }
        } else {
            if ($selectedOption == '' || $selectedOption === 'Semua Cabang') {
                $pembelians = Pembelian::where('cabang_id', $user->cabang_id)->where('status', '=', 'paid')->orderBy('id', 'DESC')->get();
            } else {
                $pembelians = Pembelian::where('cabang_id', $user->cabang_id)->where('cabang_id', $selectedOption)->where('status', '=', 'paid')->orderBy('id', 'DESC')->get();
            }
        }


        if ($tanggalMulai !== null && $tanggalSelesai !== null) {
            $pembelians = $pembelians->whereBetween('tgl_transaksi', [$tanggalMulai, $tanggalSelesai]);
        }
        $totalPemasukan = $pembelians->sum('total_harga');

        if ($request->has('print_pdf')) {
            $data = [
                'pembelians'        => $pembelians,
                'selectedOption'    => $selectedOption,
                'tanggalMulai'      => $tanggalMulai,
                'tanggalSelesai'    => $tanggalSelesai,
                'totalPemasukan'    => $totalPemasukan
            ];
            $dompdf = new Dompdf();
            $dompdf->setPaper('A4', 'portrait');
            $html = view('/rekap-pemasukan/print-rekap-pemasukan', compact('data'))->render();
            $dompdf->loadHtml($html);
            $dompdf->render();
            $dompdf->stream('rekap_pemasukan.pdf');

            return response()->json([
                'success' => true,
                'totalPemasukan' => $totalPemasukan
            ]);
        }

        return response()->json([
            'success'   => true,
            'data'      => $pembelians,
            'totalPemasukan' => $totalPemasukan
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}