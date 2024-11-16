<?php

namespace App\Http\Controllers;

use App\Models\Makanan;
use App\Models\Minuman;
use Illuminate\Http\Request;

class MenuKasirController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        if ($user->role->role === 'administrator' || $user->role->role === 'owner') {
            $makanans = Makanan::all();
            $minumans = Minuman::all();
        } else {
            $makanans = Makanan::where('cabang_id', auth()->user()->cabang_id)->get();
            $minumans = Minuman::where('cabang_id', auth()->user()->cabang_id)->get();
        }

        return view('menu-kasir.index', [
            'makanans'  => $makanans,
            'minumans'  => $minumans
        ]);
    }
}