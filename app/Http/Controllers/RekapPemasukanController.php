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


        if ($userRole === 'administrator' || $userRole === 'owner') {
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
        $user = auth()->user();
        $selectedOption = $request->input('opsi');
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');

        try {
            // Query dasar
            $query = Pembelian::with('detailPembelians')->where('status', '=', 'paid')->orderBy('id', 'DESC');

            // Filter berdasarkan cabang
            if ($user->role->role === 'administrator' || $user->role->role === 'owner') {
                if ($selectedOption && $selectedOption !== 'Semua Cabang') {
                    $query->where('cabang_id', $selectedOption);
                }
            } else {
                $query->where('cabang_id', $user->cabang_id);
            }

            // Filter berdasarkan rentang tanggal
            if ($tanggalMulai && $tanggalSelesai) {
                $tanggalMulai = Carbon::parse($tanggalMulai)->format('Y-m-d');
                $tanggalSelesai = Carbon::parse($tanggalSelesai)->format('Y-m-d');
                $query->whereBetween('tgl_transaksi', [$tanggalMulai, $tanggalSelesai]);
            }

            // Ambil data setelah filter
            $pembelians = $query->get();

            // Format data
            $dataFormatted = $pembelians->map(function ($item) {
                return [
                    'id' => $item->id,
                    'kode_pembelian' => $item->kode_pembelian,
                    'total_harga' => $item->total_harga,
                    'tgl_transaksi' => $item->tgl_transaksi,
                    'detail_pembelians' => $item->detailPembelians->map(function ($detail) {
                        return [
                            'nama' => $detail->nama,
                            'quantity' => $detail->quantity
                        ];
                    }),
                ];
            });

            // Kembalikan data
            return response()->json([
                'success' => true,
                'data' => $dataFormatted
            ]);
        } catch (\Exception $e) {
            // Tangkap error dan kembalikan response
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
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