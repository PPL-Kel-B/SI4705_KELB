<?php

namespace App\Http\Controllers;

use App\Models\Komunitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class KomunitasController extends Controller
{
    /**
     * Display the community registration form.
     */
    public function index()
    {
        return view('RegisterKomunitas');
    }

    /**
     * Show the form for creating a new community.
     */
    public function create()
    {
        return view('RegisterKomunitas');
    }

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
            'no_hp'            => 'required|numeric|digits_between:10,14',
            'email'            => 'required|email|unique:komunitas,Email',
            'password'         => ['required', Password::min(8)->mixedCase()->numbers()->symbols()],
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

    /**
     * Display the specified community.
     */
    public function show(Komunitas $register)
    {
        return redirect()->route('registerkomunitas');
    }

    /**
     * Show the form for editing the specified community.
     */
    public function edit(Komunitas $register)
    {
        return redirect()->route('registerkomunitas');
    }

    /**
     * Update the specified community in storage.
     */
    public function update(Request $request, Komunitas $register)
    {
        return redirect()->route('registerkomunitas');
    }

    /**
     * Remove the specified community from storage.
     */
    public function destroy(Komunitas $register)
    {
        return redirect()->route('registerkomunitas');
    }
}