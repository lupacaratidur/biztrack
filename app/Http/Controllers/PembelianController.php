<?php

namespace App\Http\Controllers;

use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\DetailPembelian;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PembelianController extends Controller
{
    public function pembelian(Request $request)
    {
        $pembelians     = $request->input('pembelian');
        $kodePembelian  = uniqid('TRX-');
        $totalHarga     = $request->input('total_harga');
    
        // Simpan transaksi dengan kode pembelian yang sama
        $transaksi = new Pembelian();
        $transaksi->kode_pembelian = $kodePembelian;
        $transaksi->total_harga = $totalHarga;
        $transaksi->tgl_transaksi = Carbon::now()->format('Y-m-d');
        $transaksi->user_id = auth()->user()->id;
        $transaksi->cabang_id = auth()->user()->cabang_id;
        $transaksi->save();
    
        // Simpan detail pembelian dalam tabel detail_pembelian dengan kode pembelian yang sama
        foreach ($pembelians as $pembelian) {
            $detailPembelian = new DetailPembelian();
            $detailPembelian->pembelian_id = $transaksi->id;
            $detailPembelian->nama = $pembelian['nama'];
            $detailPembelian->harga = $pembelian['harga'];
            $detailPembelian->quantity = $pembelian['quantity'];
            $detailPembelian->save();
        }
        
        $response['id'] = $transaksi->id;
        $response['kode_pembelian'] = $kodePembelian;
        return response()->json($response);
    }

    public function updateStatusPembayaran(Request $request)
    {
        $pembelianId = $request->input('id');
  
        // Cari pembelian berdasarkan ID
        $pembelian = Pembelian::findOrFail($pembelianId);
      
        if ($pembelian) {
            $pembelian->status = 'paid';
            $pembelian->save();
          
            return response()->json(['message' => 'Status berhasil diubah menjadi "paid".']);
        } else {
            return response()->json(['error' => 'Pembelian tidak ditemukan.']);
        }
    }

       
}
