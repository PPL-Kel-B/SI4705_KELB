<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UnitBisnisProfile;
use App\Models\MenuAktif;

class ProfilUnitBisnisController extends Controller
{
    /**
     * Menampilkan halaman profil unit bisnis dengan data dari database (jika ada) atau data bayangan (fallback).
     */
    public function show($id)
    {
        // Coba cari unit bisnis di database beserta relasi user-nya
        $profile = UnitBisnisProfile::with('user')->find($id);

        if ($profile) {
            // Jika ada di database, gunakan data real
            $unitBisnis = (object) [
                'id' => $profile->id,
                'nama' => $profile->nama_usaha,
                'kategori' => $profile->jenis_usaha ? $profile->jenis_usaha . ' Verified' : 'Partner Verified',
                'alamat' => $profile->user->alamat ?? 'Alamat belum diisi',
                'deskripsi' => $profile->reviewer_notes ?? 'Unit bisnis ini berdedikasi meminimalisir food waste dengan membagikan makanan berkualitas.',
                'total_donasi' => ($profile->total_makanan_terjual ?? 0) . ' Porsi',
                'rating' => '5.0 (0 Ulasan)' // default rating
            ];

            // Ambil makanan aktif real dari database untuk unit bisnis ini
            $makananReal = MenuAktif::with('masterMakanan')
                ->where('unit_bisnis_id', $profile->id)
                ->where('status', 'aktif')
                ->get();

            $makananAktif = [];
            foreach ($makananReal as $item) {
                $makananAktif[] = (object) [
                    'id' => $item->id,
                    'nama' => $item->masterMakanan->nama_makanan,
                    'harga' => $item->is_gratis ? '0 (Donasi)' : number_format($item->harga_jual, 0, ',', '.'),
                    'porsi' => $item->stok_porsi,
                    'jarak' => '0.8 km', // mock jarak karena tidak ada koordinat di db
                    'foto' => $item->masterMakanan->foto ? asset('storage/' . $item->masterMakanan->foto) : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&q=80',
                ];
            }

            // Jika unit bisnis tidak punya makanan aktif di database, beri data mock agar tidak kosong
            if (empty($makananAktif)) {
                $makananAktif = [
                    (object) [
                        'id' => 999,
                        'nama' => 'Menu Khusus Toko',
                        'harga' => '0 (Donasi)',
                        'porsi' => 5,
                        'jarak' => '1.0 km',
                        'foto' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&q=80',
                    ]
                ];
            }
        } else {
            // FALLBACK: MOCKING DATA UNIT BISNIS jika id tidak ada di DB
            $unitBisnis = (object) [
                'id' => $id,
                'nama' => 'Healthy Garden Bistro',
                'kategori' => 'Organic Curator Verified',
                'alamat' => 'Jl. Kebon Jeruk No. 45, Jakarta Barat',
                'deskripsi' => 'Paket salad buah premium yang terdiri dari potongan melon, anggur, semangka, dan stroberi segar. Disiapkan pagi ini untuk buffet makan siang dan tidak habis terjual demi meminimalisir food waste.',
                'total_donasi' => '520 Porsi',
                'rating' => '4.9 (120 Ulasan)'
            ];

            // MOCKING DATA MAKANAN AKTIF
            $makananAktif = [
                (object) [
                    'id' => 1, 
                    'nama' => 'Paket Salad Buah Segar', 
                    'harga' => '7.500', 
                    'porsi' => 12, 
                    'jarak' => '0.8 km',
                    'foto' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=400&q=80',
                ],
                (object) [
                    'id' => 2, 
                    'nama' => 'Smoothie Bowl Berry', 
                    'harga' => '12.000', 
                    'porsi' => 5, 
                    'jarak' => '1.2 km',
                    'foto' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&q=80',
                ],
                (object) [
                    'id' => 3, 
                    'nama' => 'Nasi Kotak Ayam Bakar', 
                    'harga' => '0 (Donasi)', 
                    'porsi' => 3, 
                    'jarak' => '1.5 km',
                    'foto' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=400&q=80',
                ],
                (object) [
                    'id' => 4, 
                    'nama' => 'Gado-Gado Spesial Toko', 
                    'harga' => '8.000', 
                    'porsi' => 8, 
                    'jarak' => '0.5 km',
                    'foto' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=400&q=80',
                ],
            ];
        }

        return view('user.profile_unit_bisnis', compact('unitBisnis', 'makananAktif'));
    }

    /**
     * Menampilkan halaman simulasi detail makanan dari database (POV Unit Bisnis)
     * atau fallback mock data jika database kosong.
     */
    public function simulasiDetail($menu_aktif_id = null)
    {
        // Ambil semua menu aktif dari DB beserta relasi
        $allActiveMenus = MenuAktif::with('masterMakanan', 'unitBisnis.user')
            ->where('status', 'aktif')
            ->latest()
            ->get();

        $activeMenu = null;

        if ($menu_aktif_id) {
            $activeMenu = MenuAktif::with('masterMakanan', 'unitBisnis.user')->find($menu_aktif_id);
        }

        // Jika tidak ada ID spesifik tapi ada menu aktif di DB, ambil yang paling baru
        if (!$activeMenu && $allActiveMenus->isNotEmpty()) {
            $activeMenu = $allActiveMenus->first();
        }

        if ($activeMenu && $activeMenu->masterMakanan && $activeMenu->unitBisnis) {
            // Gunakan data REAL dari database (POV Unit Bisnis)
            $makanan = (object) [
                'id' => $activeMenu->id,
                'nama' => $activeMenu->masterMakanan->nama_makanan,
                'kategori' => $activeMenu->masterMakanan->kategori ?? 'Umum',
                'deskripsi' => $activeMenu->masterMakanan->deskripsi ?? 'Tidak ada deskripsi makanan.',
                'harga' => $activeMenu->is_gratis ? 0 : (float) $activeMenu->harga_jual,
                'is_gratis' => $activeMenu->is_gratis,
                'stok_porsi' => $activeMenu->stok_porsi,
                'jarak' => '0.8 km', // mock jarak
                'batas_pengambilan' => $activeMenu->batas_pengambilan ? $activeMenu->batas_pengambilan->format('H:i') . ' WIB' : 'Hari Ini',
                'foto' => $activeMenu->masterMakanan->foto ? asset('storage/' . $activeMenu->masterMakanan->foto) : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=600&q=80',
                'unit_bisnis_id' => $activeMenu->unit_bisnis_id,
                'nama_usaha' => $activeMenu->unitBisnis->nama_usaha,
                'alamat' => $activeMenu->unitBisnis->user->alamat ?? 'Alamat belum diatur',
            ];
        } else {
            // FALLBACK: Gunakan data mockup buah salad jika kosong
            $makanan = (object) [
                'id' => null,
                'nama' => 'Paket Salad Buah Segar',
                'kategori' => 'Sayur & Buah',
                'deskripsi' => 'Paket salad buah premium yang terdiri dari potongan melon, anggur, semangka, dan stroberi segar. Disiapkan pagi ini untuk buffet makan siang dan tidak habis terjual.',
                'harga' => 7500,
                'is_gratis' => false,
                'stok_porsi' => 12,
                'jarak' => '0.8 km',
                'batas_pengambilan' => '2 Jam Lagi',
                'foto' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=1200&q=80',
                'unit_bisnis_id' => 1,
                'nama_usaha' => 'Healthy Garden Bistro',
                'alamat' => 'Jl. Kebon Jeruk No. 45, Jakarta Barat',
            ];
        }

        return view('user.simulasi_detail_makanan', compact('makanan', 'allActiveMenus'));
    }
}