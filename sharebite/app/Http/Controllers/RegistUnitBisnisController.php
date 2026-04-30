<?php

namespace App\Http\Controllers;

use App\Models\UnitBisnisProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegistUnitBisnisController extends Controller
{
    public function create()
    {
        return view('unit_bisnis.create');
    }

    public function store(Request $request)
    {
        // Validasi — pakai nama field sesuai form (PascalCase)
        $validated = $request->validate([
            'Nama_Usaha'  => 'required|string|max:100',
            'Jenis_Usaha' => 'required|string|max:100',
            'Alamat'      => 'required|string|max:255',
            'NIB_File'    => 'nullable|file|mimes:pdf,jpg,jpeg|max:5120',
            'Nomor_hp'    => 'nullable|string|max:20',
            'Email'       => 'required|email|max:100|unique:users,email',
            'Password'    => 'required|string|min:6',
            'Latitude'    => 'nullable|string',
            'Longitude'   => 'nullable|string',
        ]);

        // Handle NIB file upload
        $nibPath = null;
        if ($request->hasFile('NIB_File')) {
            $file    = $request->file('NIB_File');
            $nibPath = $file->store('nib_files', 'public');
        }

        // Buat user terlebih dahulu
        $user = User::create([
            'name'     => $validated['Nama_Usaha'],
            'email'    => $validated['Email'],
            'password' => Hash::make($validated['Password']),
            'no_hp'    => $validated['Nomor_hp'] ?? null,
            'role'     => 'unit_bisnis',
        ]);

        // Buat unit bisnis profile
        UnitBisnisProfile::create([
            'user_id'           => $user->id,
            'nama_usaha'        => $validated['Nama_Usaha'],
            'jenis_usaha'       => $validated['Jenis_Usaha'],
            'alamat'            => $validated['Alamat'],
            'nib_file'          => $nibPath,
            'status_verifikasi' => 'pending',
        ]);

        return redirect()->route('unit-bisnis.create')
                         ->with('success', 'Pendaftaran Unit Bisnis berhasil! Akun Anda sedang dalam proses verifikasi.');
    }
}
