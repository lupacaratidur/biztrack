<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Cabang;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pengguna.index', [
            'roles'     => Role::all(),
            'cabangs'   => Cabang::all()
        ]);
    }

    public function getData()
    {
        return response()->json([
            'success'   => true,
            'data'      => User::with(['role', 'cabang'])->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pengguna.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required',
            'password'  => 'required|min:4',
            'role_id'   => 'required',
            'cabang_id' => 'required'
        ], [
            'name.required'     => 'Form Nama Wajib Di isi !',
            'email.required'    => 'Form Email Wajib Di isi !',
            'password.required' => 'Form Password Wajib Di isi !',
            'password.min'      => 'Password minimal 4 Huruf/Angka/Karakter !',
            'role_id.required'  => 'Wajib Pilih Role !',
            'cabang_id'         => 'Wajib Pilih cabang !'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $pengguna = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role_id'   => $request->role_id,
            'cabang_id' => $request->cabang_id
        ]);

        return response()->json([
            'success'   => true,
            'message'   => 'Data Berhasil Tersimpan',
            'data'      => $pengguna
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return response()->json([
            'success'   => true,
            'message'   => 'Data Berhasil Tersimpan',
            'data'      => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required',
            'role_id'   => 'required',
            'cabang_id' => 'required'
        ], [
            'name.required'     => 'Form Nama Wajib Di isi !',
            'email.required'    => 'Form Email Wajib Di isi !',
            'role_id.required'  => 'Wajib Pilih Role !',
            'cabang_id'         => 'Wajib Pilih cabang !'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $userData = [
            'name'      => $request->name,
            'email'     => $request->email,
            'role_id'   => $request->role_id,
            'cabang_id' => $request->cabang_id
        ];

        if (!empty($request->password)) {
            $validatorPassword = Validator::make($request->all(), [
                'password'  => 'min:4'
            ], [
                'password.min' => 'Password Minimal 4 Huruf/Angka/Karakter'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        return response()->json([
            'success'   => true,
            'message'   => 'Data Berhasil Terupdate',
            'data'      => $user
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        User::find($id)->delete($id);
        return response()->json([
            'success'   => true,
            'message'   => 'Data Berhasil Dihapus'
        ]);
    }
}