<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pesanan;
use App\Models\MenuAktif;
use App\Models\UserActivity;
use App\Models\User;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 1. Porsi Diambil: jumlah porsi makanan yang sudah dibeli (dibayar, siap_diambil, selesai)
        $porsi_diambil = Pesanan::where('user_id', $user->id)
            ->whereIn('status', ['dibayar', 'siap_diambil', 'selesai'])
            ->sum('jumlah_porsi');

        // 2. Makanan Terselamatkan: SUM dari berat makanan yang dibeli
        $makanan_terselamatkan = DB::table('pesanans')
            ->join('menu_aktifs', 'pesanans.menu_aktif_id', '=', 'menu_aktifs.id')
            ->join('master_makanans', 'menu_aktifs.master_makanan_id', '=', 'master_makanans.id')
            ->where('pesanans.user_id', $user->id)
            ->whereIn('pesanans.status', ['dibayar', 'siap_diambil', 'selesai'])
            ->sum(DB::raw('pesanans.jumlah_porsi * master_makanans.berat'));

        $makanan_terselamatkan = (float) $makanan_terselamatkan;

        // 3. CO2 Dihemat: Makanan Terselamatkan * 2.5
        $co2_dihemat = $makanan_terselamatkan * 2.5;

        // 4. Aktivitas Terakhir: menampilkan 3 aktivitas utama terbaru
        $recent_activities = UserActivity::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // 5. Donasi Terdekat: makanan aktif dengan filter radius unit bisnis dan diurutkan terdekat
        $allMenuAktifs = MenuAktif::where('status', 'aktif')
            ->where('stok_porsi', '>', 0)
            ->where('batas_pengambilan', '>', now())
            ->with(['masterMakanan', 'unitBisnis.user'])
            ->get();

        $nearby_donations = $allMenuAktifs->map(function ($menu) use ($user) {
            $latBisnis = $menu->unitBisnis->lokasi_lat ?? $menu->unitBisnis->user->latitude ?? null;
            $lngBisnis = $menu->unitBisnis->lokasi_lng ?? $menu->unitBisnis->user->longitude ?? null;

            $distance = 0.8; // fallback
            if ($user && !is_null($user->latitude) && !is_null($user->longitude) && !is_null($latBisnis) && !is_null($lngBisnis)) {
                $distance = User::calculateDistance(
                    $user->latitude,
                    $user->longitude,
                    $latBisnis,
                    $lngBisnis
                );
            }
            $menu->computed_distance = $distance;
            return $menu;
        })->filter(function ($menu) {
            // Hanya tampilkan jika jarak user berada di dalam radius_penjemputan milik toko
            $radius = (float) ($menu->unitBisnis->radius_penjemputan ?? 5);
            return $menu->computed_distance <= $radius;
        })->sortBy('computed_distance')->values();

        // Ambil 4 donasi terdekat untuk ditampilkan di dashboard utama
        $limited_nearby_donations = $nearby_donations->take(4);

        // 6. Hitung jumlah donatur aktif sekitar (dalam radius unit bisnis masing-masing)
        $active_donors_count = User::where('role', 'unit_bisnis')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->filter(function ($donor) use ($user) {
                if (!$user || is_null($user->latitude) || is_null($user->longitude)) {
                    return false;
                }
                $distance = User::calculateDistance(
                    $user->latitude,
                    $user->longitude,
                    $donor->latitude,
                    $donor->longitude
                );
                $profile = $donor->unitBisnisProfile;
                $radius = (float) ($profile->radius_penjemputan ?? 5);
                return $distance <= $radius;
            })->count();

        // 7. Gamifikasi Level & Badges (Below the fold)
        // Level 1: < 5kg, Level 2: 5-10kg, Level 3: 10-20kg, Level 4: 20-50kg, Level 5: 50kg+
        $level = 1;
        $level_name = 'Penyelamat Pemula';
        $next_level_weight = 5.0;
        $prev_level_weight = 0.0;

        if ($makanan_terselamatkan >= 50.0) {
            $level = 5;
            $level_name = 'Pahlawan Legendaris';
            $next_level_weight = null;
            $prev_level_weight = 50.0;
        } elseif ($makanan_terselamatkan >= 20.0) {
            $level = 4;
            $level_name = 'Ksatria Hijau';
            $next_level_weight = 50.0;
            $prev_level_weight = 20.0;
        } elseif ($makanan_terselamatkan >= 10.0) {
            $level = 3;
            $level_name = 'Pejuang Iklim';
            $next_level_weight = 20.0;
            $prev_level_weight = 10.0;
        } elseif ($makanan_terselamatkan >= 5.0) {
            $level = 2;
            $level_name = 'Sahabat Bumi';
            $next_level_weight = 10.0;
            $prev_level_weight = 5.0;
        }

        $progress_percentage = 100;
        if ($next_level_weight !== null) {
            $progress_percentage = (($makanan_terselamatkan - $prev_level_weight) / ($next_level_weight - $prev_level_weight)) * 100;
            $progress_percentage = min(100, max(0, $progress_percentage));
        }

        // Badges list
        $badges = [
            [
                'name' => 'Donasi Pertama',
                'description' => 'Melakukan pemesanan makanan pertama kali.',
                'unlocked' => $porsi_diambil > 0,
                'icon' => '🌱'
            ],
            [
                'name' => 'Anti Mubazir',
                'description' => 'Menyelamatkan makanan lebih dari 5 kg.',
                'unlocked' => $makanan_terselamatkan >= 5.0,
                'icon' => '🍱'
            ],
            [
                'name' => 'Pahlawan Hijau',
                'description' => 'Menyelamatkan makanan lebih dari 20 kg.',
                'unlocked' => $makanan_terselamatkan >= 20.0,
                'icon' => '🛡️'
            ],
            [
                'name' => 'Pahlawan Iklim',
                'description' => 'Menghemat CO2 lebih dari 50 kg.',
                'unlocked' => $co2_dihemat >= 50.0,
                'icon' => '☁️'
            ]
        ];

        // 8. Tips Hari Ini
        $tips = [
            'Rencanakan porsi makan Anda agar tidak bersisa dan mubazir.',
            'Pahami label tanggal kadaluarsa: "Best Before" berbeda dengan "Expired Date".',
            'Simpan sisa makanan dengan wadah tertutup rapat di kulkas agar awet lebih lama.',
            'Donasikan sisa makanan yang masih layak konsumsi kepada yang membutuhkan via ShareBite!',
            'Manfaatkan buah yang hampir terlalu matang menjadi jus atau salad buah.'
        ];
        $tip_of_the_day = $tips[array_rand($tips)];

        return view('user.dashboard', compact(
            'porsi_diambil',
            'makanan_terselamatkan',
            'co2_dihemat',
            'recent_activities',
            'limited_nearby_donations',
            'active_donors_count',
            'level',
            'level_name',
            'next_level_weight',
            'prev_level_weight',
            'progress_percentage',
            'badges',
            'tip_of_the_day'
        ));
    }

    public function activities(Request $request)
    {
        $user = auth()->user();
        
        $activities = UserActivity::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('user.activities', compact('activities'));
    }

    public function nearby(Request $request)
    {
        $user = auth()->user();
        $search = $request->input('search');

        $allMenuAktifs = MenuAktif::where('status', 'aktif')
            ->where('stok_porsi', '>', 0)
            ->where('batas_pengambilan', '>', now())
            ->with(['masterMakanan', 'unitBisnis.user'])
            ->get();

        $nearby_donations = $allMenuAktifs->map(function ($menu) use ($user) {
            $latBisnis = $menu->unitBisnis->lokasi_lat ?? $menu->unitBisnis->user->latitude ?? null;
            $lngBisnis = $menu->unitBisnis->lokasi_lng ?? $menu->unitBisnis->user->longitude ?? null;

            $distance = 0.8;
            if ($user && !is_null($user->latitude) && !is_null($user->longitude) && !is_null($latBisnis) && !is_null($lngBisnis)) {
                $distance = User::calculateDistance(
                    $user->latitude,
                    $user->longitude,
                    $latBisnis,
                    $lngBisnis
                );
            }
            $menu->computed_distance = $distance;
            return $menu;
        })->filter(function ($menu) use ($search) {
            // Radius filter
            $radius = (float) ($menu->unitBisnis->radius_penjemputan ?? 5);
            $in_radius = $menu->computed_distance <= $radius;

            // Search filter
            if ($search) {
                $makanan_name = strtolower($menu->masterMakanan->nama_makanan ?? '');
                $usaha_name = strtolower($menu->unitBisnis->nama_usaha ?? '');
                $search_query = strtolower($search);
                $matches_search = str_contains($makanan_name, $search_query) || str_contains($usaha_name, $search_query);
                return $in_radius && $matches_search;
            }

            return $in_radius;
        })->sortBy('computed_distance')->values();

        return view('user.nearby', compact('nearby_donations'));
    }
}
