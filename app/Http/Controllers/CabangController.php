<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CabangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('cabang.index');
    }

    /**
     * Get Data cabang
     */
    public function getData()
    {
        $cabangs = Cabang::all();

        return response()->json([
            'success'   => true,
            'data'      => $cabangs
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cabang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cabang'    => 'required',
            'alamat'    => 'required'
        ], [
            'cabang.required'   => 'Form Cabang Wajib Diisi !',
            'alamat.required'   => 'Form Alamat Wajib Diisi !'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $cabang = Cabang::create([
            'cabang'    => $request->cabang,
            'alamat'    => $request->alamat
        ]);

        return response()->json([
            'success'   => true,
            'message'   => 'Data Berhasil Disimpan !',
            'data'      => $cabang
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $cabang = Cabang::findOrFail($id);
        return response()->json([
            'json'      => true,
            'message'   => 'Edit Data',
            'data'      => $cabang
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $cabang = Cabang::find($id);

        $validator = Validator::make($request->all(), [
            'cabang'    => 'required',
            'alamat'    => 'required'
        ], [
            'cabang.required'   => 'Form Cabang Wajib Diisi !',
            'alamat.required'   => 'Form Alamat Wajib Diisi !'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $cabang->update([
            'cabang'    => $request->cabang,
            'alamat'    => $request->alamat,
        ]);

        return response()->json([
            'success'   => true,
            'message'   => 'Data Berhasil Terupdate',
            'data'      => $cabang
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Cabang::find($id)->delete();

        return response()->json([
            'success'   => true,
            'message'   => 'Data Berhasil Dihapus!'
        ]);
    }
}