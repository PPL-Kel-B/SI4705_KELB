<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UnitBisnisProfile;
use App\Models\MenuAktif;
use App\Models\BuktiDonasi;
use App\Models\Rating;

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
            // Hitung rata-rata rating
            $ratingsQuery = Rating::where('unit_bisnis_id', $profile->id);
            $totalRatings = $ratingsQuery->count();
            $averageRating = $totalRatings > 0 ? ($ratingsQuery->avg('skor_rating') ?? 5.0) : 5.0;
            $ratingString = number_format($averageRating, 1) . ' (' . $totalRatings . ' Ulasan)';

            // Jika ada di database, gunakan data real
            $unitBisnis = (object) [
                'id' => $profile->id,
                'nama' => $profile->nama_usaha,
                'kategori' => $profile->jenis_usaha ? $profile->jenis_usaha . ' Verified' : 'Partner Verified',
                'alamat' => $profile->user->alamat ?? 'Alamat belum diisi',
                'deskripsi' => $profile->reviewer_notes ?? 'Unit bisnis ini berdedikasi meminimalisir food waste dengan membagikan makanan berkualitas.',
                'total_donasi' => ($profile->total_makanan_terjual ?? 0) . ' Porsi',
                'rating' => $ratingString,
                'foto_profile' => $profile->foto_profile ? asset('storage/' . $profile->foto_profile) : null,
                'jam_buka' => $profile->jam_buka ? date('H:i', strtotime($profile->jam_buka)) : '08:00',
                'jam_tutup' => $profile->jam_tutup ? date('H:i', strtotime($profile->jam_tutup)) : '20:00',
                'no_telepon' => $profile->no_telepon ?? $profile->user->no_hp ?? '-',
                'email' => $profile->email_bisnis ?? $profile->user->email ?? '-'
            ];

            // Ambil makanan aktif real dari database untuk unit bisnis ini
            $makananReal = MenuAktif::with('masterMakanan')
                ->where('unit_bisnis_id', $profile->id)
                ->where('status', 'aktif')
                ->where('batas_pengambilan', '>=', now())
                ->get();

            $makananAktif = [];
            foreach ($makananReal as $item) {
                $distanceStr = '0.8 km';
                $user = auth()->user();
                if ($user && !is_null($user->latitude) && !is_null($user->longitude)) {
                    $latBisnis = $profile->lokasi_lat ?? $profile->user->latitude ?? null;
                    $lngBisnis = $profile->lokasi_lng ?? $profile->user->longitude ?? null;
                    if (!is_null($latBisnis) && !is_null($lngBisnis)) {
                        $distance = \App\Models\User::calculateDistance(
                            $user->latitude,
                            $user->longitude,
                            $latBisnis,
                            $lngBisnis
                        );
                        $distanceStr = number_format($distance, 1, ',', '.') . ' km';
                    }
                }

                $makananAktif[] = (object) [
                    'id' => $item->id,
                    'nama' => $item->masterMakanan->nama_makanan,
                    'harga' => $item->is_gratis ? '0 (Donasi)' : number_format($item->harga_jual, 0, ',', '.'),
                    'porsi' => $item->stok_porsi,
                    'jarak' => $distanceStr,
                    'foto' => $item->masterMakanan->foto ? asset('storage/' . $item->masterMakanan->foto) : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&q=80',
                    'kategori' => $item->masterMakanan->kategori ?? 'Umum',
                ];
            }

            $buktiDonasisReal = BuktiDonasi::whereHas('pesanan', function($q) use ($profile) {
                $q->where('unit_bisnis_id', $profile->id);
            })->latest()->take(20)->get();

            $buktiDonasis = [];
            foreach ($buktiDonasisReal as $bukti) {
                if ($bukti->foto) {
                    $buktiDonasis[] = asset('storage/' . $bukti->foto);
                }
            }

            // Gabungkan foto dari rating (foto_bukti_berbagi) jika jumlah gambar di galeri kurang dari 20
            if (count($buktiDonasis) < 20) {
                $ratingsReal = Rating::where('unit_bisnis_id', $profile->id)
                    ->whereNotNull('foto_bukti_berbagi')
                    ->where('foto_bukti_berbagi', '!=', '')
                    ->latest()
                    ->take(20 - count($buktiDonasis))
                    ->get();

                foreach ($ratingsReal as $rating) {
                    $buktiDonasis[] = asset('storage/' . $rating->foto_bukti_berbagi);
                }
            }

            // Jika unit bisnis tidak punya makanan aktif di database, beri data mock agar tidak kosong
            if (empty($makananAktif)) {
                $distanceStrFallback = '1.0 km';
                $user = auth()->user();
                if ($user && !is_null($user->latitude) && !is_null($user->longitude)) {
                    $latBisnis = $profile->lokasi_lat ?? $profile->user->latitude ?? null;
                    $lngBisnis = $profile->lokasi_lng ?? $profile->user->longitude ?? null;
                    if (!is_null($latBisnis) && !is_null($lngBisnis)) {
                        $distance = \App\Models\User::calculateDistance(
                            $user->latitude,
                            $user->longitude,
                            $latBisnis,
                            $lngBisnis
                        );
                        $distanceStrFallback = number_format($distance, 1, ',', '.') . ' km';
                    }
                }

                $makananAktif = [
                    (object) [
                        'id' => 999,
                        'nama' => 'Menu Khusus Toko',
                        'harga' => '0 (Donasi)',
                        'porsi' => 5,
                        'jarak' => $distanceStrFallback,
                        'foto' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&q=80',
                        'kategori' => 'Cemilan / Makanan Ringan',
                    ]
                ];
            }

            // Ambil semua data ulasan / rating real-time dari database beserta relasi user
            $ulasans = Rating::with('user')
                ->where('unit_bisnis_id', $profile->id)
                ->latest()
                ->get();
        } else {
            // FALLBACK: MOCKING DATA UNIT BISNIS jika id tidak ada di DB
            $unitBisnis = (object) [
                'id' => $id,
                'nama' => 'Healthy Garden Bistro',
                'kategori' => 'Organic Curator Verified',
                'alamat' => 'Jl. Kebon Jeruk No. 45, Jakarta Barat',
                'deskripsi' => 'Paket salad buah premium yang terdiri dari potongan melon, anggur, semangka, dan stroberi segar. Disiapkan pagi ini untuk buffet makan siang dan tidak habis terjual demi meminimalisir food waste.',
                'total_donasi' => '520 Porsi',
                'rating' => '4.9 (120 Ulasan)',
                'foto_profile' => null,
                'jam_buka' => '08:00',
                'jam_tutup' => '21:00',
                'no_telepon' => '0812-3456-7890',
                'email' => 'contact@healthygarden.com'
            ];

            // MOCKING DATA MAKANAN AKTIF
            $latBisnisFallback = -6.9271;
            $lngBisnisFallback = 107.6411;

            $distanceStr1 = '0.8 km';
            $distanceStr2 = '1.2 km';
            $distanceStr3 = '1.5 km';
            $distanceStr4 = '0.5 km';

            $user = auth()->user();
            if ($user && !is_null($user->latitude) && !is_null($user->longitude)) {
                $distance1 = \App\Models\User::calculateDistance($user->latitude, $user->longitude, $latBisnisFallback, $lngBisnisFallback);
                $distanceStr1 = number_format($distance1, 1, ',', '.') . ' km';

                $distance2 = \App\Models\User::calculateDistance($user->latitude, $user->longitude, $latBisnisFallback + 0.005, $lngBisnisFallback + 0.005);
                $distanceStr2 = number_format($distance2, 1, ',', '.') . ' km';

                $distance3 = \App\Models\User::calculateDistance($user->latitude, $user->longitude, $latBisnisFallback - 0.007, $lngBisnisFallback + 0.003);
                $distanceStr3 = number_format($distance3, 1, ',', '.') . ' km';

                $distance4 = \App\Models\User::calculateDistance($user->latitude, $user->longitude, $latBisnisFallback + 0.002, $lngBisnisFallback - 0.004);
                $distanceStr4 = number_format($distance4, 1, ',', '.') . ' km';
            }

            $makananAktif = [
                (object) [
                    'id' => 1, 
                    'nama' => 'Paket Salad Buah Segar', 
                    'harga' => '7.500', 
                    'porsi' => 12, 
                    'jarak' => $distanceStr1,
                    'foto' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=400&q=80',
                    'kategori' => 'Cemilan / Makanan Ringan',
                ],
                (object) [
                    'id' => 2, 
                    'nama' => 'Smoothie Bowl Berry', 
                    'harga' => '12.000', 
                    'porsi' => 5, 
                    'jarak' => $distanceStr2,
                    'foto' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&q=80',
                    'kategori' => 'Dessert',
                ],
                (object) [
                    'id' => 3, 
                    'nama' => 'Nasi Kotak Ayam Bakar', 
                    'harga' => '0 (Donasi)', 
                    'porsi' => 3, 
                    'jarak' => $distanceStr3,
                    'foto' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=400&q=80',
                    'kategori' => 'Makanan Berat',
                ],
                (object) [
                    'id' => 4, 
                    'nama' => 'Gado-Gado Spesial Toko', 
                    'harga' => '8.000', 
                    'porsi' => 8, 
                    'jarak' => $distanceStr4,
                    'foto' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=400&q=80',
                    'kategori' => 'Makanan Berat',
                ],
            ];
            $buktiDonasis = [];

            // MOCKING DATA ULASAN untuk fallback
            $ulasans = [
                (object) [
                    'skor_rating' => 5,
                    'catatan_pengalaman' => 'Makanan sangat lezat dan bersih! Porsinya pas banget untuk makan siang. Penjual sangat ramah dan proses penjemputan sangat cepat.',
                    'created_at' => now()->subDays(1),
                    'user' => (object) [
                        'name' => 'Budi Santoso'
                    ]
                ],
                (object) [
                    'skor_rating' => 4,
                    'catatan_pengalaman' => 'Sangat mengapresiasi kebersihan kemasannya. Sangat membantu masyarakat sekitar dalam mengurangi sampah makanan.',
                    'created_at' => now()->subDays(3),
                    'user' => (object) [
                        'name' => 'Siti Aminah'
                    ]
                ]
            ];
        }

        $hideSearch = true;
        return view('user.profile_unit_bisnis', compact('unitBisnis', 'makananAktif', 'buktiDonasis', 'hideSearch', 'ulasans'));
    }

    /**
     * Menampilkan halaman simulasi detail makanan dari database (POV Unit Bisnis)
     * atau fallback mock data jika database kosong.
     */
    public function simulasiDetail($menu_aktif_id = null)
    {
        // Ambil semua menu aktif dari DB beserta relasi yang belum kadaluarsa
        $allActiveMenus = MenuAktif::with('masterMakanan', 'unitBisnis.user')
            ->where('status', 'aktif')
            ->where('batas_pengambilan', '>=', now())
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
            $distanceStr = '0.8 km';
            $user = auth()->user();
            if ($user && !is_null($user->latitude) && !is_null($user->longitude)) {
                $latBisnis = $activeMenu->unitBisnis->lokasi_lat ?? $activeMenu->unitBisnis->user->latitude ?? null;
                $lngBisnis = $activeMenu->unitBisnis->lokasi_lng ?? $activeMenu->unitBisnis->user->longitude ?? null;
                if (!is_null($latBisnis) && !is_null($lngBisnis)) {
                    $distance = \App\Models\User::calculateDistance(
                        $user->latitude,
                        $user->longitude,
                        $latBisnis,
                        $lngBisnis
                    );
                    $distanceStr = number_format($distance, 1, ',', '.') . ' km';
                }
            }

            $makanan = (object) [
                'id' => $activeMenu->id,
                'nama' => $activeMenu->masterMakanan->nama_makanan,
                'kategori' => $activeMenu->masterMakanan->kategori ?? 'Umum',
                'deskripsi' => $activeMenu->masterMakanan->deskripsi ?? 'Tidak ada deskripsi makanan.',
                'harga' => $activeMenu->is_gratis ? 0 : (float) $activeMenu->harga_jual,
                'is_gratis' => $activeMenu->is_gratis,
                'stok_porsi' => $activeMenu->stok_porsi,
                'jarak' => $distanceStr,
                'batas_pengambilan' => $activeMenu->batas_pengambilan ? $activeMenu->batas_pengambilan->format('H:i') . ' WIB' : 'Hari Ini',
                'foto' => $activeMenu->masterMakanan->foto ? asset('storage/' . $activeMenu->masterMakanan->foto) : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=600&q=80',
                'unit_bisnis_id' => $activeMenu->unit_bisnis_id,
                'nama_usaha' => $activeMenu->unitBisnis->nama_usaha,
                'alamat' => $activeMenu->unitBisnis->user->alamat ?? 'Alamat belum diatur',
                'foto_profile' => $activeMenu->unitBisnis->foto_profile ? asset('storage/' . $activeMenu->unitBisnis->foto_profile) : null,
            ];
        } else {
            // FALLBACK: Gunakan data mockup buah salad jika kosong
            $distanceStrFallback = '0.8 km';
            $user = auth()->user();
            if ($user && !is_null($user->latitude) && !is_null($user->longitude)) {
                $distance = \App\Models\User::calculateDistance(
                    $user->latitude,
                    $user->longitude,
                    -6.9271,
                    107.6411
                );
                $distanceStrFallback = number_format($distance, 1, ',', '.') . ' km';
            }

            $makanan = (object) [
                'id' => null,
                'nama' => 'Paket Salad Buah Segar',
                'kategori' => 'Sayur & Buah',
                'deskripsi' => 'Paket salad buah premium yang terdiri dari potongan melon, anggur, semangka, dan stroberi segar. Disiapkan pagi ini untuk buffet makan siang dan tidak habis terjual.',
                'harga' => 7500,
                'is_gratis' => false,
                'stok_porsi' => 12,
                'jarak' => $distanceStrFallback,
                'batas_pengambilan' => '2 Jam Lagi',
                'foto' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=1200&q=80',
                'unit_bisnis_id' => 1,
                'nama_usaha' => 'Healthy Garden Bistro',
                'alamat' => 'Jl. Kebon Jeruk No. 45, Jakarta Barat',
                'foto_profile' => null,
            ];
        }

        return view('user.simulasi_detail_makanan', compact('makanan', 'allActiveMenus'));
    }
}