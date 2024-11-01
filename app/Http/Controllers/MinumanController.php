<?php

namespace App\Http\Controllers;


use App\Models\Cabang;
use App\Models\Minuman;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MinumanController extends Controller
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

        return view('minuman.index', [
            'cabangs'   => $cabang
        ]);
    }

    /**
     * Get Data Minuman
     */
    public function getData()
    {
        $user = auth()->user();
        if ($user->role->role === 'administrator' || $user->role->role === 'kepala restoran') {
            $minumans = Minuman::with('cabang')->orderBy('id', 'DESC')->get();
        } else {
            $minumans = Minuman::with('cabang')->where('cabang_id', auth()->user()->cabang_id)->orderBy('id', 'DESC')->get();
        }


        return response()->json([
            'success'   => true,
            'data'      => $minumans
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('minuman.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gambar'        => 'required|mimes:jpeg,jpg,png',
            'nama_minuman'  => 'required',
            'deskripsi'     => 'required',
            'harga'         => 'required',
            'cabang_id'     => 'required'
        ], [
            'gambar.required'        => 'Tambahkan Gambar !',
            'gambar.mimes'           => 'Gunakan Gambar Yang Memiliki Format jpeg, png, jpg !',
            'nama_minuman.required'  => 'Form Nama minuman Wajib Di Isi !',
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

        $kode_minuman = 'MKN - ' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $request->merge([
            'kode_minuman'   => $kode_minuman,
            'gambar'         => $gambar,
            'user_id'        => auth()->user()->id
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $minuman = Minuman::create([
            'nama_minuman'      => $request->nama_minuman,
            'deskripsi'         => $request->deskripsi,
            'user_id'           => $request->user_id,
            'kode_minuman'      => $request->kode_minuman,
            'harga'             => $request->harga,
            'gambar'            => $path . $fileName,
            'cabang_id'         => $request->cabang_id
        ]);

        return response()->json([
            'success'   => true,
            'message'   => 'Data Berhasil Ditambahkan !',
            'data'      => $minuman
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Minuman $minuman)
    {
        return response()->json([
            'success'   => true,
            'message'   => 'Edit Data',
            'data'      => $minuman
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Minuman $minuman)
    {
        return response()->json([
            'success'   => true,
            'message'   => 'Edit Data',
            'data'      => $minuman
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Minuman $minuman)
    {
        $validator = Validator::make($request->all(), [
            'gambar'        => 'required|mimes:jpeg,jpg,png',
            'nama_minuman'  => 'required',
            'deskripsi'     => 'required',
            'harga'         => 'required',
            'cabang_id'     => 'required'
        ], [
            'gambar.required'        => 'Tambahkan Gambar !',
            'gambar.mimes'           => 'Gunakan Gambar Yang Memiliki Format jpeg, png, jpg !',
            'nama_minuman.required'  => 'Form Nama minuman Wajib Di Isi !',
            'deskripsi.required'     => 'Form Deskripsi Wajib Di Isi !',
            'harga'                  => 'Tambahkan Harga !',
            'cabang_id'              => 'Pilih Cabang !'
        ]);

        if ($request->hasFile('gambar')) {
            if ($minuman->gambar) {
                unlink('.' . Storage::url($minuman->gambar));
            }
            $path      = 'gambar/';
            $file      = $request->file('gambar');
            $fileName  = $file->getClientOriginalName();
            $gambar    = $file->storeAs($path, $fileName, 'public');
            $path      .= $fileName;
        } else {
            $validator = Validator::make($request->all(), [
                'gambar'        => 'required',
                'nama_minuman'  => 'required',
                'deskripsi'     => 'required',
                'harga'         => 'required'
            ], [
                'gambar.required'        => 'Tambahkan Gambar !',
                'nama_minuman.required'  => 'Form Nama minuman Wajib Di Isi !',
                'deskripsi.required'     => 'Form Deskripsi Wajib Di Isi !',
                'harga'                  => 'Tambahkan Harga !'
            ]);

            $path = $minuman->gambar;
        }

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $minuman->update([
            'gambar'        => $path,
            'nama_minuman'  => $request->nama_minuman,
            'deskripsi'     => $request->deskripsi,
            'harga'         => $request->harga,
            'user_id'       => auth()->user()->id,
            'cabang_id'     => $request->cabang_id
        ]);

        return response()->json([
            'success'   => true,
            'message'   => 'Data Berhasil Terupdate',
            'data'      => $minuman
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Minuman $minuman)
    {
        unlink('.' . Storage::url($minuman->gambar));

        Minuman::destroy($minuman->id);

        return response()->json([
            'success'   => true,
            'message'   => 'Data Berhasil Dihapus'
        ]);
    }
}