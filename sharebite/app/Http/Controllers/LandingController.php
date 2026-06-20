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

        $totalBeratKgVal = DB::table('pesanans')
            ->join('menu_aktifs', 'pesanans.menu_aktif_id', '=', 'menu_aktifs.id')
            ->join('master_makanans', 'menu_aktifs.master_makanan_id', '=', 'master_makanans.id')
            ->whereIn('pesanans.status', ['selesai', 'siap_diambil', 'dibayar'])
            ->sum(DB::raw('pesanans.jumlah_porsi * master_makanans.berat')) ?? 0;
            
        $totalBeratKg = number_format($totalBeratKgVal, 1, ',', '.');

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

    public function mitra(Request $request)
    {
        $search = $request->input('search');
        $jenis_usaha = $request->input('jenis_usaha');

        $query = User::where('role', 'unit_bisnis')
            ->whereHas('unitBisnisProfile', function ($q) {
                $q->where('status_verifikasi', 'terverifikasi');
            })
            ->with('unitBisnisProfile');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%")
                  ->orWhereHas('unitBisnisProfile', function($subQ) use ($search) {
                      $subQ->where('nama_usaha', 'like', "%{$search}%");
                  });
            });
        }

        if ($jenis_usaha) {
            $query->whereHas('unitBisnisProfile', function($q) use ($jenis_usaha) {
                $q->where('jenis_usaha', $jenis_usaha);
            });
        }

        $mitras = $query->latest()->paginate(9)->withQueryString();

        // Ambil list jenis usaha unik untuk filter dropdown/button
        $jenisUsahaList = \App\Models\UnitBisnisProfile::where('status_verifikasi', 'terverifikasi')
            ->whereNotNull('jenis_usaha')
            ->distinct()
            ->pluck('jenis_usaha');

        return view('mitra', compact('mitras', 'jenisUsahaList', 'search', 'jenis_usaha'));
    }

    public function komunitas()
    {
        // Ambil data komunitas aktif
        $komunitases = User::where('role', 'komunitas')
            ->latest()
            ->take(6)
            ->get();

        return view('komunitas', compact('komunitases'));
    }
}
