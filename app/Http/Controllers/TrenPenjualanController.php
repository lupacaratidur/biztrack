<?php

namespace App\Http\Controllers;

use App\Models\DetailPembelian;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Cabang;

class TrenPenjualanController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $selectedBranch = $request->query('cabang_id'); // Menangkap ID cabang dari filter

        // Fetch branch names
        $pemasukanPerCabang = Pembelian::select('cabang_id', DB::raw('SUM(total_harga) as total_pemasukan'))
            ->groupBy('cabang_id')
            ->pluck('total_pemasukan', 'cabang_id');
        $cabangNames = Cabang::pluck('cabang', 'id'); // Ambil semua nama cabang

        // Query for Product Sales Trend Chart with optional date and branch filtering
        $query = DetailPembelian::select('nama', 'pembelians.cabang_id', DB::raw('SUM(quantity) as total_sold'))
            ->join('pembelians', 'detail_pembelians.pembelian_id', '=', 'pembelians.id')
            ->groupBy('nama', 'pembelians.cabang_id')
            ->orderBy('nama');

        // Apply date filtering if both start and end dates are provided
        if ($startDate && $endDate) {
            $query->whereBetween('pembelians.tgl_transaksi', [$startDate, $endDate]);
        }

        // Apply branch filtering if a specific branch is selected
        if ($selectedBranch) {
            $query->where('pembelians.cabang_id', $selectedBranch);
        }

        $grafikTrenProduk = $query->get()->groupBy('nama');

        return view('tren-penjualan.index', [
            'grafikTrenProduk' => $grafikTrenProduk,
            'cabangNames' => $cabangNames,
            'selectedBranch' => $selectedBranch, // Kirim cabang yang dipilih
        ]);
    }

}
