<?php

namespace App\Http\Controllers;

use App\Models\UnitBisnisProfile;
use App\Models\User;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegistUnitBisnisController extends Controller
{
    public function create()
    {
        $totalUnitBisnis = UnitBisnisProfile::count();
        $totalMakananTerselamatkan = Pesanan::whereIn('status', ['selesai', 'siap_diambil', 'dibayar'])->sum('jumlah_porsi');
        $totalPenerimaManfaat = Pesanan::whereIn('status', ['selesai', 'siap_diambil', 'dibayar'])->distinct('user_id')->count('user_id');

        if (session()->has('retry_user_id')) {
            $user = User::with('unitBisnisProfile')->find(session('retry_user_id'));
            if ($user && $user->unitBisnisProfile) {
                session()->flashInput([
                    'Nama_Usaha' => $user->unitBisnisProfile->nama_usaha,
                    'Jenis_Usaha' => $user->unitBisnisProfile->jenis_usaha,
                    'Alamat' => $user->alamat,
                    'Email' => $user->email,
                    'Nomor_hp' => $user->no_hp,
                    'Latitude' => $user->latitude,
                    'Longitude' => $user->longitude,
                    'NIB_File_Name' => $user->unitBisnisProfile->nib_file ? basename($user->unitBisnisProfile->nib_file) : null,
                ]);
            }
        }

        return view('auth.register_unit_bisnis', compact(
            'totalUnitBisnis',
            'totalMakananTerselamatkan',
            'totalPenerimaManfaat'
        ));
    }

    public function store(Request $request)
    {
        $existingUser = User::where('email', $request->Email)->orWhere('no_hp', $request->Nomor_hp)->first();
        
        $emailRule = 'required|email|max:100|unique:users,email';
        $phoneRule = ['required', 'string', 'regex:/^(\+62|0)[0-9]{9,13}$/', 'unique:users,no_hp'];
        $passwordRule = 'required|string|min:8';

        $isRejectedUser = false;

        if ($existingUser) {
            // Jika user sudah ada dan statusnya DITOLAK, kita izinkan mereka untuk daftar/update ulang
            if ($existingUser->role === 'unit_bisnis' && $existingUser->unitBisnisProfile && $existingUser->unitBisnisProfile->status_verifikasi === 'ditolak') {
                $isRejectedUser = true;
                // Abaikan unique constraint untuk ID user ini
                $emailRule = 'required|email|max:100|unique:users,email,' . $existingUser->id;
                $phoneRule = ['required', 'string', 'regex:/^(\+62|0)[0-9]{9,13}$/', 'unique:users,no_hp,' . $existingUser->id];
                $passwordRule = 'required|string|min:8'; // Password tetap wajib diisi kembali
            }
        }

        // Validasi — pakai nama field sesuai form (PascalCase)
        $validated = $request->validate([
            'Nama_Usaha'  => 'required|string|max:100',
            'Jenis_Usaha' => 'required|string|max:100',
            'Alamat'      => 'required|string|max:255',
            'NIB_File'    => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'Nomor_hp'    => $phoneRule,
            'Email'       => $emailRule,
            'Password'    => $passwordRule,
            'Latitude'    => 'nullable|string',
            'Longitude'   => 'nullable|string',
        ]);

        // Handle NIB file upload
        $nibPath = null;
        if ($request->hasFile('NIB_File')) {
            $file    = $request->file('NIB_File');
            $nibPath = $file->store('nib_files', 'public');
        }

        if ($isRejectedUser && $existingUser) {
            // UPDATE: user memperbaiki pendaftaran karena NIB ditolak
            $userData = [
                'name'     => $validated['Nama_Usaha'],
                'email'    => $validated['Email'],
                'no_hp'    => $validated['Nomor_hp'] ?? null,
                'alamat'   => $validated['Alamat'],
                'latitude' => $validated['Latitude'] ?? $existingUser->latitude,
                'longitude'=> $validated['Longitude'] ?? $existingUser->longitude,
            ];

            if (!empty($validated['Password'])) {
                $userData['password'] = Hash::make($validated['Password']);
            }

            $existingUser->update($userData);

            $profileData = [
                'nama_usaha'        => $validated['Nama_Usaha'],
                'jenis_usaha'       => $validated['Jenis_Usaha'],
                'status_verifikasi' => 'pending',
                'reviewer_notes'    => null, // Reset pesan penolakan
            ];

            if ($nibPath) {
                // Opsional: Hapus file NIB lama jika ada (bisa ditambahkan storage::delete disini)
                $profileData['nib_file'] = $nibPath;
            }

            $existingUser->unitBisnisProfile->update($profileData);

            return redirect()->route('login')
                             ->with('success', 'Pendaftaran ulang berhasil! Akun Anda kembali dalam proses verifikasi.');
        } else {
            // CREATE: pendaftaran user baru
            $user = User::create([
                'name'     => $validated['Nama_Usaha'],
                'email'    => $validated['Email'],
                'password' => Hash::make($validated['Password']),
                'no_hp'    => $validated['Nomor_hp'] ?? null,
                'role'     => 'unit_bisnis',
                'alamat'   => $validated['Alamat'],
                'latitude' => $validated['Latitude'] ?? null,
                'longitude'=> $validated['Longitude'] ?? null,
            ]);

            UnitBisnisProfile::create([
                'user_id'           => $user->id,
                'nama_usaha'        => $validated['Nama_Usaha'],
                'jenis_usaha'       => $validated['Jenis_Usaha'],
                'nib_file'          => $nibPath,
                'status_verifikasi' => 'pending',
            ]);

            return redirect()->route('login')
                             ->with('success', 'Pendaftaran Unit Bisnis berhasil! Akun Anda sedang dalam proses verifikasi.');
        }
    }
}
