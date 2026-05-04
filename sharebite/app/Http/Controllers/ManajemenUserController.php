<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManajemenUserController extends Controller
{
    public function index(Request $request)
    {
        // Ambil parameter tab dari URL, default ke 'unit_bisnis'
        $tab = $request->query('tab', 'unit_bisnis');

        // Statistik (Card Atas) - query ke tabel yang benar sesuai migrasi
        $stats = [
            'total_bisnis'       => DB::table('unit_bisnis_profiles')->count(),
            'pending_verifikasi' => DB::table('unit_bisnis_profiles')
                                      ->where('status_verifikasi', 'pending')
                                      ->count(),
            // Alias untuk view yang menggunakan 'pending_verifikasi' label lama

            'aktif_komunitas'    => DB::table('komunitas_profiles')->count()
                                    + DB::table('individu_profiles')->count(),
        ];

        // Logika Filter Tabel berdasarkan Tab
        if ($tab == 'komunitas') {
            // Data komunitas dari komunitas_profiles join users
            $komunitas = DB::table('komunitas_profiles')
                ->join('users', 'komunitas_profiles.user_id', '=', 'users.id')
                ->select(
                    'users.id',
                    'komunitas_profiles.nama_komunitas as name',
                    DB::raw("'Komunitas' as type"),
                    'users.email as Email',
                    'users.alamat',
                    'users.foto_profil',
                    'komunitas_profiles.created_at'
                )->get();

            // Data individu dari individu_profiles join users
            $individu = DB::table('individu_profiles')
                ->join('users', 'individu_profiles.user_id', '=', 'users.id')
                ->select(
                    'users.id',
                    'users.name as name',
                    DB::raw("'Individu' as type"),
                    'users.email as Email',
                    'users.alamat',
                    'users.foto_profil',
                    'individu_profiles.created_at'
                )->get();

            $users = $komunitas->merge($individu);
        } else {
            // Default: Unit Bisnis
            $users = DB::table('unit_bisnis_profiles')
                ->join('users', 'unit_bisnis_profiles.user_id', '=', 'users.id')
                ->select(
                    'users.id',
                    'unit_bisnis_profiles.nama_usaha as name',
                    'unit_bisnis_profiles.jenis_usaha as type',
                    'users.email as Email',
                    'users.alamat',
                    'users.foto_profil',
                    'unit_bisnis_profiles.status_verifikasi',
                    'unit_bisnis_profiles.created_at'
                )->get();
        }

        return view('admin.manajemen_user', compact('users', 'stats', 'tab'));
    }

    public function create()
    {
        return redirect()->route('manajemen-user.index');
    }

    public function store(Request $request)
    {
        return redirect()->route('manajemen-user.index');
    }

    public function show($id)
    {
        return redirect()->route('manajemen-user.index');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.manajemen_user_edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            // Update alamat di tabel users (email tidak diupdate karena readonly)
            $dataToUpdate = [];
            if ($request->filled('alamat')) {
                $dataToUpdate['alamat'] = $request->alamat;
            }

            // Update nama: tergantung role
            if ($request->filled('name')) {
                if ($user->role == 'unit_bisnis') {
                    DB::table('unit_bisnis_profiles')
                        ->where('user_id', $user->id)
                        ->update(['nama_usaha' => $request->name]);
                } elseif ($user->role == 'komunitas') {
                    DB::table('komunitas_profiles')
                        ->where('user_id', $user->id)
                        ->update(['nama_komunitas' => $request->name]);
                } else {
                    $dataToUpdate['name'] = $request->name;
                }
            }

            // Update status_verifikasi untuk unit bisnis (fitur verifikasi admin)
            if ($request->filled('status_verifikasi') && $user->role == 'unit_bisnis') {
                DB::table('unit_bisnis_profiles')
                    ->where('user_id', $user->id)
                    ->update(['status_verifikasi' => $request->status_verifikasi]);
            }

            // Update foto profil jika ada upload
            if ($request->hasFile('foto_profil')) {
                $path = $request->file('foto_profil')->store('profiles', 'public');
                $dataToUpdate['foto_profil'] = $path;
            }

            // Update tabel users jika ada data yang berubah
            if (!empty($dataToUpdate)) {
                $user->update($dataToUpdate);
            }

            return redirect()->route('admin.manajemen_pengguna', ['tab' => $request->tab ?? 'unit_bisnis'])
                             ->with('success', 'Data pengguna berhasil diperbarui');

        } catch (\Exception $e) {
            return redirect()->route('admin.manajemen_pengguna', ['tab' => $request->tab ?? 'unit_bisnis'])
                             ->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * UPDATE via POST body (user_id dari form hidden input, bukan URL)
     * Dipakai oleh modal edit di manajemen_user.blade.php
     */
    public function updateByPost(Request $request)
    {
        try {
            $id   = $request->input('user_id');
            $user = User::findOrFail($id);

            // Update status_verifikasi unit bisnis (prioritas utama: fitur verifikasi admin)
            if ($request->filled('status_verifikasi') && $user->role === 'unit_bisnis') {
                DB::table('unit_bisnis_profiles')
                    ->where('user_id', $user->id)
                    ->update([
                        'status_verifikasi' => $request->status_verifikasi,
                        'updated_at'        => now(),
                    ]);
            }

            // Update nama usaha
            if ($request->filled('name')) {
                if ($user->role === 'unit_bisnis') {
                    DB::table('unit_bisnis_profiles')
                        ->where('user_id', $user->id)
                        ->update(['nama_usaha' => $request->name, 'updated_at' => now()]);
                } elseif ($user->role === 'komunitas') {
                    DB::table('komunitas_profiles')
                        ->where('user_id', $user->id)
                        ->update(['nama_komunitas' => $request->name, 'updated_at' => now()]);
                } else {
                    $user->update(['name' => $request->name]);
                }
            }

            // Update alamat di tabel users
            if ($request->filled('alamat')) {
                $user->update(['alamat' => $request->alamat]);
            }

            // Upload foto profil
            if ($request->hasFile('foto_profil')) {
                $path = $request->file('foto_profil')->store('profiles', 'public');
                $user->update(['foto_profil' => $path]);
            }

            $tab = $request->input('tab', 'unit_bisnis');
            return redirect()->route('admin.manajemen_pengguna', ['tab' => $tab])
                             ->with('success', 'Data pengguna berhasil diperbarui!');

        } catch (\Exception $e) {
            return redirect()->route('admin.manajemen_pengguna', ['tab' => $request->input('tab', 'unit_bisnis')])
                             ->with('error', 'Gagal memperbarui: ' . $e->getMessage());
        }
    }

    /**
     * DESTROY via POST body (user_id dari form hidden input, bukan URL)
     */
    public function destroyByPost(Request $request)
    {
        try {
            $id   = $request->input('user_id');
            $user = User::findOrFail($id);
            $user->delete();

            $tab = $request->input('tab', 'unit_bisnis');
            return redirect()->route('admin.manajemen_pengguna', ['tab' => $tab])
                             ->with('success', 'Pengguna berhasil dihapus!');

        } catch (\Exception $e) {
            return redirect()->route('admin.manajemen_pengguna', ['tab' => $request->input('tab', 'unit_bisnis')])
                             ->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }
}