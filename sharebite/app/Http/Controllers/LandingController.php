<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\User;
use App\Models\MenuAktif;
use Illuminate\Support\Facades\DB;

class LandingController extends Controller
{
    public function index()
    {
        // 1. Total Porsi Terselamatkan
        $totalPorsiTerselamatkan = Pesanan::whereIn('status', ['selesai', 'siap_diambil', 'dibayar'])->sum('jumlah_porsi');

        // 2. Total Berat Terselamatkan (asumsi berat di database dalam satuan gram, jadi dibagi 1000 untuk kg)
        $totalBeratGram = DB::table('pesanans')
            ->join('menu_aktifs', 'pesanans.menu_aktif_id', '=', 'menu_aktifs.id')
            ->join('master_makanans', 'menu_aktifs.master_makanan_id', '=', 'master_makanans.id')
            ->whereIn('pesanans.status', ['selesai', 'siap_diambil', 'dibayar'])
            ->select(DB::raw('SUM(pesanans.jumlah_porsi * master_makanans.berat) as total_berat'))
            ->value('total_berat') ?? 0;
            
        $totalBeratKg = number_format($totalBeratGram / 1000, 1, ',', '.'); // ubah ke kg

        // 3. Pahlawan Bergabung (Jumlah User Unit Bisnis dan Komunitas)
        $totalPahlawan = User::whereIn('role', ['unit_bisnis', 'komunitas', 'individu'])->count();
        $pahlawanFotos = User::whereIn('role', ['unit_bisnis', 'komunitas', 'individu'])
            ->whereNotNull('foto_profil')
            ->latest()
            ->take(4)
            ->pluck('foto_profil');

        // 4. Menu Aktif (Donasi Tersedia Hari Ini) - ambil 3 terbaru
        $menus = MenuAktif::with(['masterMakanan', 'unitBisnis'])
            ->where('status', 'aktif')
            ->where('stok_porsi', '>', 0)
            ->where('batas_pengambilan', '>', now())
            ->latest()
            ->take(3)
            ->get();

        return view('welcome', compact(
            'totalPorsiTerselamatkan',
            'totalBeratKg',
            'totalPahlawan',
            'pahlawanFotos',
            'menus'
        ));
    }
}
