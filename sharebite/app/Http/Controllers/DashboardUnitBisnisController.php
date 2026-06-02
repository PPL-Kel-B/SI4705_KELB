<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MenuAktif;
use App\Models\Pesanan;
use App\Models\Rating;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardUnitBisnisController extends Controller
{
    public function index()
    {
        $unitBisnisProfile = Auth::user()->unitBisnisProfile;

        if (!$unitBisnisProfile) {
            return view('unit_bisnis.dashboard', [
                'menuAktifCount'  => 0,
                'menuHabisCount'  => 0,
                'totalPesanan'    => 0,
                'ratingResto'     => 0,
                'totalPendapatan' => 0,
                'salesGrowth'     => 0,
                'salesData'       => [],
                'pesananMasuk'    => collect(),
            ]);
        }

        $unitBisnisId = $unitBisnisProfile->id;
        $now          = Carbon::now();

        // ── Kelola Menu Aktif ──────────────────────
        $menuAktifCount = MenuAktif::where('unit_bisnis_id', $unitBisnisId)
            ->where('status', 'aktif')
            ->count();

        $menuHabisCount = MenuAktif::where('unit_bisnis_id', $unitBisnisId)
            ->where('status', 'aktif')
            ->where('stok_porsi', '<=', 3)
            ->count();
        // ── Total Pesanan ──────────────────────────
        $totalPesanan = Pesanan::where('unit_bisnis_id', $unitBisnisId)->count();

        // ── Rating Resto ───────────────────────────
        $ratingResto = Rating::where('unit_bisnis_id', $unitBisnisId)->avg('nilai') ?? 0;
        $ratingResto = round($ratingResto, 1);

        // ── Total Pendapatan bulan ini ─────────────
        $totalPendapatan = Pesanan::where('unit_bisnis_id', $unitBisnisId)
                            ->whereIn('status', ['selesai', 'siap_diambil', 'dibayar'])
                            ->whereMonth('created_at', $now->month)
                            ->whereYear('created_at', $now->year)
                            ->sum('total_harga');

        // ── Sales Growth ───────────────────────────
        $pendapatanBulanLalu = Pesanan::where('unit_bisnis_id', $unitBisnisId)
                            ->whereIn('status', ['selesai', 'siap_diambil', 'dibayar'])
                            ->whereMonth('created_at', $now->copy()->subMonth()->month)
                            ->whereYear('created_at', $now->copy()->subMonth()->year)
                            ->sum('total_harga');

        $salesGrowth = $pendapatanBulanLalu > 0
                        ? round((($totalPendapatan - $pendapatanBulanLalu) / $pendapatanBulanLalu) * 100, 1)
                        : ($totalPendapatan > 0 ? 100 : 0);

        // ── Chart Per Bulan ────────────────────────
        $bulanLabel = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

        $penjualanPerBulan = Pesanan::where('unit_bisnis_id', $unitBisnisId)
                            ->whereIn('status', ['selesai', 'siap_diambil', 'dibayar'])
                            ->whereYear('created_at', $now->year)
                            ->select(
                                DB::raw('MONTH(created_at) as bulan'),
                                DB::raw('SUM(jumlah_porsi) as total_porsi')
                            )
                            ->groupBy('bulan')
                            ->pluck('total_porsi', 'bulan')
                            ->toArray();

        $salesData = [];
        foreach ($bulanLabel as $index => $label) {
            $salesData[$label] = $penjualanPerBulan[$index + 1] ?? 0;
        }

        // ── Pesanan Masuk (5 terbaru) ──────────────
        $pesananMasuk = Pesanan::with(['menuAktif.masterMakanan'])
                            ->where('unit_bisnis_id', $unitBisnisId)
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();

        // ── Satu return di paling bawah ────────────
        return view('unit_bisnis.dashboard', compact(
            'menuAktifCount',
            'menuHabisCount',
            'totalPesanan',
            'ratingResto',
            'totalPendapatan',
            'salesGrowth',
            'salesData',
            'pesananMasuk'
        ));
    }
}