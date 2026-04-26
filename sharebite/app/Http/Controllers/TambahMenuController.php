<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Makanan; 
use Illuminate\Support\Facades\Auth;

class TambahMenuController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi Data
        $request->validate([
            'jumlah_porsi' => 'required|integer',
            'batas_waktu' => 'required',
        ]);

        // 2. Simpan ke database 
        Makanan::create([
            'Nama_Makanan'            => 'Nasi Campur Bali Organik', // Contoh statis dulu
            'Jumlah_porsi'            => $request->jumlah_porsi,
            'Batas_waktu_pengambilan' => $request->batas_waktu,
            'Harga'                   => $request->has('is_free') ? 0 : 12000,
            'Foto'                    => 'nasi_bali.jpg', 
            'Status'                  => 'Tersedia',
            'UnitBisnisID'            => 1, 
        ]);

        
        return redirect()->back()->with('success', 'Menu Berhasil Dipublikasikan!');
    }
}