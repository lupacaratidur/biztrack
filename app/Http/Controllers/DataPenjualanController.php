<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use function Symfony\Component\String\b;

class DataPenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('data-penjualan.index', [
            'cabangs'   => Cabang::all()
        ]);
    }

    /**
     * Get Data Penjualan
     */
    public function getData(Request $request)
    {
        $user = auth()->user();
        $selectedOption = $request->input('opsi');

        if ($user->role->role === 'administrator' || $user->role->role === 'kepala restoran') {
            if ($selectedOption == '' || $selectedOption === 'Semua Cabang') {
                $pembelians = Pembelian::with('detailPembelians')->orderBy('id', 'DESC')->get();
            } else {
                $pembelians = Pembelian::with('detailPembelians')->where('cabang_id', $selectedOption)->orderBy('id', 'DESC')->get();
            }
        } else {
            $pembelians = Pembelian::with('detailPembelians')->where('cabang_id', auth()->user()->cabang_id)->orderBy('id', 'DESC')->get();
        }

        return response()->json([
            'success' => true,
            'data' => $pembelians
        ]);
    }
}