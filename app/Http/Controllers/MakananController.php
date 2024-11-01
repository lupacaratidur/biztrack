<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Makanan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MakananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        if ($user->role->role === 'administrator' || $user->role->role === 'kepala restoran') {
            $cabang = Cabang::all();
        } else {
            $cabang = Cabang::where('id', auth()->user()->cabang_id)->get();
        }

        return view('makanan.index', [
            'cabangs'   => $cabang
        ]);
    }

    /**
     * Get Data Makanan
     */
    public function getData()
    {
        $user = auth()->user();
        if ($user->role->role === 'administrator' || $user->role->role === 'kepala restoran') {
            $makanans = Makanan::with('cabang')->orderBy('id', 'DESC')->get();
        } else {
            $makanans = Makanan::with('cabang')->where('cabang_id', auth()->user()->cabang_id)->orderBy('id', 'DESC')->get();
        }

        return response()->json([
            'success'   => true,
            'data'      => $makanans
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('makanan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gambar'        => 'required|mimes:jpeg,jpg,png',
            'nama_makanan'  => 'required',
            'deskripsi'     => 'required',
            'harga'         => 'required',
            'cabang_id'     => 'required'
        ], [
            'gambar.required'        => 'Tambahkan Gambar !',
            'gambar.mimes'           => 'Gunakan Gambar Yang Memiliki Format jpeg, png, jpg !',
            'nama_makanan.required'  => 'Form Nama makanan Wajib Di Isi !',
            'deskripsi.required'     => 'Form Deskripsi Wajib Di Isi !',
            'harga'                  => 'Tambahkan Harga !',
            'cabang_id'              => 'Pilih Cabang !'
        ]);

        if ($request->hasFile('gambar')) {
            $path       = 'gambar/';
            $file       = $request->file('gambar');
            $fileName   = $file->getClientOriginalName();
            $gambar     = $file->storeAs($path, $fileName, 'public');
        } else {
            $gambar = null;
        }

        $kode_makanan = 'MKN - ' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $request->merge([
            'kode_makanan'   => $kode_makanan,
            'gambar'         => $gambar,
            'user_id'        => auth()->user()->id
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $makanan = Makanan::create([
            'nama_makanan'      => $request->nama_makanan,
            'deskripsi'         => $request->deskripsi,
            'user_id'           => $request->user_id,
            'kode_makanan'      => $request->kode_makanan,
            'harga'             => $request->harga,
            'gambar'            => $path . $fileName,
            'cabang_id'         => $request->cabang_id
        ]);

        return response()->json([
            'success'   => true,
            'message'   => 'Data Berhasil Ditambahkan !',
            'data'      => $makanan
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Makanan $makanan)
    {
        return response()->json([
            'success'   => true,
            'message'   => 'Edit Data',
            'data'      => $makanan
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Makanan $makanan)
    {
        return response()->json([
            'success'   => true,
            'message'   => 'Edit Data',
            'data'      => $makanan
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Makanan $makanan)
    {
        $validator = Validator::make($request->all(), [
            'gambar'        => 'required|mimes:jpeg,jpg,png',
            'nama_makanan'  => 'required',
            'deskripsi'     => 'required',
            'harga'         => 'required',
            'cabang_id'     => 'required'
        ], [
            'gambar.required'        => 'Tambahkan Gambar !',
            'gambar.mimes'           => 'Gunakan Gambar Yang Memiliki Format jpeg, png, jpg !',
            'nama_makanan.required'  => 'Form Nama makanan Wajib Di Isi !',
            'deskripsi.required'     => 'Form Deskripsi Wajib Di Isi !',
            'harga'                  => 'Tambahkan Harga !',
            'cabang_id'              => 'Pilih Cabang !'
        ]);

        if ($request->hasFile('gambar')) {
            if ($makanan->gambar) {
                unlink('.' . Storage::url($makanan->gambar));
            }
            $path      = 'gambar/';
            $file      = $request->file('gambar');
            $fileName  = $file->getClientOriginalName();
            $gambar    = $file->storeAs($path, $fileName, 'public');
            $path      .= $fileName;
        } else {
            $validator = Validator::make($request->all(), [
                'gambar'        => 'required',
                'nama_makanan'  => 'required',
                'deskripsi'     => 'required',
                'harga'         => 'required',
                'cabang_id'     => 'required'
            ], [
                'gambar.required'        => 'Tambahkan Gambar !',
                'nama_makanan.required'  => 'Form Nama makanan Wajib Di Isi !',
                'deskripsi.required'     => 'Form Deskripsi Wajib Di Isi !',
                'harga'                  => 'Tambahkan Harga !',
                'cabang_id'              => 'Pilih Cabang !'
            ]);

            $path = $makanan->gambar;
        }

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $makanan->update([
            'gambar'        => $path,
            'nama_makanan'  => $request->nama_makanan,
            'deskripsi'     => $request->deskripsi,
            'harga'         => $request->harga,
            'user_id'       => auth()->user()->id,
            'cabang_id'     => $request->cabang_id
        ]);

        return response()->json([
            'success'   => true,
            'message'   => 'Data Berhasil Terupdate',
            'data'      => $makanan
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Makanan $makanan)
    {
        unlink('.' . Storage::url($makanan->gambar));

        Makanan::destroy($makanan->id);

        return response()->json([
            'success'   => true,
            'message'   => 'Data Berhasil Dihapus'
        ]);
    }
}