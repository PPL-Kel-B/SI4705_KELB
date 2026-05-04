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
        } elseif ($tab == 'verifikasi_nib') {
            $users = DB::table('unit_bisnis_profiles')
                ->join('users', 'unit_bisnis_profiles.user_id', '=', 'users.id')
                ->where('unit_bisnis_profiles.status_verifikasi', 'pending')
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
        $user = User::findOrFail($id);
        
        $dataToUpdate = $request->only(['email', 'alamat']);
        
        if ($request->has('name')) {
            if ($user->role == 'unit_bisnis') {
                $user->unitBisnisProfile()->update([
                    'nama_usaha' => $request->name
                ]);
            } elseif ($user->role == 'komunitas') {
                $user->komunitasProfile()->update(['nama_komunitas' => $request->name]);
            } else {
                $dataToUpdate['name'] = $request->name;
            }
        }

        if ($request->hasFile('foto_profil')) {
            $path = $request->file('foto_profil')->store('profiles', 'public');
            $dataToUpdate['foto_profil'] = $path;
        }

        $user->update($dataToUpdate);

        return redirect()->route('admin.manajemen_pengguna', ['tab' => $request->tab])
                         ->with('success', 'Data berhasil diperbarui');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.manajemen_pengguna')->with('success', 'Data berhasil dihapus');
    }

    public function reviewNib($id)
    {
        $profile = \App\Models\UnitBisnisProfile::where('user_id', $id)->firstOrFail();
        return view('admin.review_nib', compact('profile'));
    }

    public function processNib(Request $request, $id)
    {
        $profile = \App\Models\UnitBisnisProfile::where('user_id', $id)->firstOrFail();
        
        $request->validate([
            'status' => 'required|in:terverifikasi,ditolak',
            'reviewer_notes' => 'nullable|string'
        ]);

        $profile->update([
            'status_verifikasi' => $request->status,
            'reviewer_notes' => $request->status == 'ditolak' ? $request->reviewer_notes : null
        ]);

        $message = $request->status == 'terverifikasi' ? 'NIB berhasil diverifikasi.' : 'Verifikasi ditolak.';
        
        return redirect()->route('admin.manajemen_pengguna', ['tab' => 'unit_bisnis'])
                         ->with('success', $message);
    }
}