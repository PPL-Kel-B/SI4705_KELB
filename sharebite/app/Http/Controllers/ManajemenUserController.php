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
                    'komunitas_profiles.id',
                    'komunitas_profiles.nama_komunitas as name',
                    DB::raw("'Komunitas' as type"),
                    'users.email as Email',
                    'komunitas_profiles.created_at'
                )->get();

            // Data individu dari individu_profiles join users
            $individu = DB::table('individu_profiles')
                ->join('users', 'individu_profiles.user_id', '=', 'users.id')
                ->select(
                    'individu_profiles.id',
                    'users.name',
                    DB::raw("'Individu' as type"),
                    'users.email as Email',
                    'individu_profiles.created_at'
                )->get();

            $users = $komunitas->merge($individu);
        } else {
            // Default: Unit Bisnis
            $users = DB::table('unit_bisnis_profiles')
                ->join('users', 'unit_bisnis_profiles.user_id', '=', 'users.id')
                ->select(
                    'unit_bisnis_profiles.id',
                    'unit_bisnis_profiles.nama_usaha as name',
                    'unit_bisnis_profiles.jenis_usaha as type',
                    'users.email as Email',
                    'unit_bisnis_profiles.status_verifikasi',
                    'unit_bisnis_profiles.created_at'
                )->get();
        }

        return view('manajemen_user', compact('users', 'stats', 'tab'));
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
        return view('manajemen_user_edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->only(['name', 'email']));

        return redirect()->route('manajemen-user.index', ['tab' => $request->tab])
                         ->with('success', 'Data berhasil diperbarui');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}