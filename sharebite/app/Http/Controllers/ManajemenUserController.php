<?php

namespace App\Http\Controllers;

use App\Models\User; // Asumsi menggunakan satu tabel User dengan kolom 'role' dan 'type'
use Illuminate\Http\Request;

class ManajemenUserController extends Controller
{
    public function index(Request $request)
    {
        // Ambil parameter tab dari URL, default ke 'unit_bisnis'
        $tab = $request->query('tab', 'unit_bisnis');

        // Statistik (Card Atas) - Mengambil langsung dari tabel masing-masing
        // Kita pakai DB facade agar lebih mudah karena tabelnya terpisah
        $stats = [
            'total_bisnis' => \DB::table('unit_bisnis')->count(),
            'pending_verifikasi' => 42, 
            // Jumlahkan komunitas + individu agar hasilnya 2
            'aktif_komunitas' => \DB::table('komunitas')->count() + \DB::table('individus')->count(),
        ];

        // Logika Filter Tabel berdasarkan Tab
        if ($tab == 'komunitas') {
            // Menggabungkan data Komunitas dan Individu
            $komunitas = \DB::table('komunitas')->select('KomunitasID as id', 'Nama as name', \DB::raw("'Komunitas' as type"), 'Email', 'created_at')->get();
            $individu = \DB::table('individus')->select('IndividuID as id', 'Nama as name', \DB::raw("'Individu' as type"), 'Email', 'created_at')->get();
            
            $users = $komunitas->merge($individu);
        } else {
            // Default: Unit Bisnis
            $users = \DB::table('unit_bisnis')->select('UnitBisnisID as id', 'Nama_Usaha as name', 'Jenis_Usaha as type', 'Email', 'created_at')->get();
        }

        return view('manajemen_user', compact('users', 'stats', 'tab'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('manajemen_user_edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->only(['name', 'type', 'status'])); // Admin hanya edit info dasar & status

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