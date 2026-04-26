<?php

namespace App\Http\Controllers;

use App\Models\Komunitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KomunitasController extends Controller
{
    /**
     * Store a new community registration.
     */
    public function store(Request $request)
    {
        // 1. Validation
        $validated = $request->validate([
            'nama_komunitas'   => 'required|string|max:255',
            'penanggung_jawab' => 'required|string|max:255',
            'jumlah_anggota'   => 'required|integer',
            'no_hp'            => 'required|string',
            'email'            => 'required|email|unique:komunitas,Email',
            'password'         => 'required|min:8',
        ]);

        // 2. Mapping to database columns
        $dataToSave = [
            'Nama'                  => $validated['nama_komunitas'],
            'Nama_penanggung_jawab' => $validated['penanggung_jawab'],
            'Jumlah_anggota'        => $validated['jumlah_anggota'],
            'Nomor_hp'              => $validated['no_hp'],
            'Email'                 => $validated['email'],
            'Password'              => Hash::make($validated['password']),
        ];

        // 3. Save to database
        Komunitas::create($dataToSave);

        return redirect()->back()->with('success', 'Registrasi Komunitas berhasil!');
    }
}