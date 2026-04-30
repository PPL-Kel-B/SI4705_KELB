<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterMakanan;
use Illuminate\Support\Facades\Auth;

class TambahMenuController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi Data
        $request->validate([
            'jumlah_porsi' => 'required|integer|min:1',
            'batas_waktu'  => 'required|date',
        ]);

        // 2. Simpan ke database menggunakan MasterMakanan
        MasterMakanan::create([
            'nama_makanan'   => 'Nasi Campur Bali Organik', // Contoh statis
            'kategori'       => 'Nasi Kotak',
            'harga'          => $request->has('is_free') ? 0 : 12000,
            'berat'          => 0.5,
            'foto'           => null,
            'unit_bisnis_id' => Auth::id() ?? null,
        ]);

        return redirect()->back()->with('success', 'Menu Berhasil Dipublikasikan!');
    }
}