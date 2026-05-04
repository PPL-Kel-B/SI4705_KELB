<?php

namespace App\Http\Controllers;

use App\Models\KomunitasProfile;
use App\Models\User;
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
        $totalKomunitas = User::where('role', 'komunitas')->count();
        return view('auth.register_komunitas', compact('totalKomunitas'));
    }

    /**
     * Show the form for creating a new community.
     */
    public function create()
    {
        $totalKomunitas = User::where('role', 'komunitas')->count();
        return view('auth.register_komunitas', compact('totalKomunitas'));
    }

    /**
     * Store a new community registration.
     */
    public function store(Request $request)
    {
        // 1. Validation
        $validated = $request->validate([
            'nama_komunitas'   => 'required|string|max:255',
            'penanggung_jawab' => ['required', 'string', 'max:255', 'regex:/^[\pL\s]+$/u'],
            'jumlah_anggota'   => 'required|integer|min:1',
            'no_hp'            => ['required', 'numeric', 'digits_between:10,14', 'unique:users,no_hp'],
            'email'            => 'required|email|unique:users,email',
            'password'         => ['required', Password::min(8)],
        ]);

        // 2. Buat user terlebih dahulu
        $user = User::create([
            'name'     => $validated['nama_komunitas'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => 'komunitas',
            'no_hp'    => $validated['no_hp'],
        ]);

        // 3. Buat komunitas profile
        KomunitasProfile::create([
            'user_id'          => $user->id,
            'nama_komunitas'   => $validated['nama_komunitas'],
            'penanggung_jawab' => $validated['penanggung_jawab'],
            'jumlah_anggota'   => $validated['jumlah_anggota'],
        ]);

        return redirect()->route('login')->with('success', 'Registrasi Komunitas berhasil!');
    }

    /**
     * Display the specified community.
     */
    public function show($id)
    {
        return redirect()->route('registerkomunitas');
    }

    /**
     * Show the form for editing the specified community.
     */
    public function edit($id)
    {
        return redirect()->route('registerkomunitas');
    }

    /**
     * Update the specified community in storage.
     */
    public function update(Request $request, $id)
    {
        return redirect()->route('registerkomunitas');
    }

    /**
     * Remove the specified community from storage.
     */
    public function destroy($id)
    {
        return redirect()->route('registerkomunitas');
    }
}