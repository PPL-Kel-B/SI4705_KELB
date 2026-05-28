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
            'total_bisnis'       => DB::table('users')->where('role', 'unit_bisnis')->count(),
            'pending_verifikasi' => DB::table('unit_bisnis_profiles')
                                      ->where('status_verifikasi', 'pending')
                                      ->count(),
            'aktif_komunitas'    => DB::table('users')->whereIn('role', ['komunitas', 'individu'])->count(),
        ];

        // Logika Filter Tabel berdasarkan Tab
        if ($tab == 'komunitas') {
            // Data komunitas dari users leftJoin komunitas_profiles
            $komunitas = DB::table('users')
                ->leftJoin('komunitas_profiles', 'users.id', '=', 'komunitas_profiles.user_id')
                ->where('users.role', 'komunitas')
                ->select(
                    'users.id',
                    DB::raw('COALESCE(komunitas_profiles.nama_komunitas, users.name) as name'),
                    DB::raw("'Komunitas' as type"),
                    'users.email as Email',
                    'users.alamat',
                    'users.foto_profil',
                    DB::raw('COALESCE(komunitas_profiles.created_at, users.created_at) as created_at')
                )->get();

            // Data individu dari users leftJoin individu_profiles
            $individu = DB::table('users')
                ->leftJoin('individu_profiles', 'users.id', '=', 'individu_profiles.user_id')
                ->where('users.role', 'individu')
                ->select(
                    'users.id',
                    'users.name as name',
                    DB::raw("'Individu' as type"),
                    'users.email as Email',
                    'users.alamat',
                    'users.foto_profil',
                    DB::raw('COALESCE(individu_profiles.created_at, users.created_at) as created_at')
                )->get();

            $users = $komunitas->merge($individu);
        } elseif ($tab == 'verifikasi_nib') {
            $users = DB::table('users')
                ->join('unit_bisnis_profiles', 'users.id', '=', 'unit_bisnis_profiles.user_id')
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
            // Default: Unit Bisnis dari users leftJoin unit_bisnis_profiles
            $users = DB::table('users')
                ->leftJoin('unit_bisnis_profiles', 'users.id', '=', 'unit_bisnis_profiles.user_id')
                ->where('users.role', 'unit_bisnis')
                ->select(
                    'users.id',
                    DB::raw('COALESCE(unit_bisnis_profiles.nama_usaha, users.name) as name'),
                    DB::raw('COALESCE(unit_bisnis_profiles.jenis_usaha, \'Restoran\') as type'),
                    'users.email as Email',
                    'users.alamat',
                    'users.foto_profil',
                    DB::raw('COALESCE(unit_bisnis_profiles.status_verifikasi, \'terverifikasi\') as status_verifikasi'),
                    DB::raw('COALESCE(unit_bisnis_profiles.created_at, users.created_at) as created_at')
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
        
        $request->validate([
            'password' => 'nullable|string|min:8',
            'foto_profil' => 'nullable|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
        ]);

        $dataToUpdate = [];

        if ($request->filled('password')) {
            $dataToUpdate['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        if ($request->hasFile('foto_profil')) {
            $path = $request->file('foto_profil')->store('profiles', 'public');
            $dataToUpdate['foto_profil'] = $path;
        }

        if (!empty($dataToUpdate)) {
            $user->update($dataToUpdate);
        }

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