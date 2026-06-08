<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\UnitBisnisProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RiwayatUnitBisnisController extends Controller
{
    private function getUnitBisnisData()
    {
        $user = Auth::user();

        $unitBisnis = UnitBisnisProfile::where('user_id', $user->id)->first();

        if (!$unitBisnis) {
            $unitBisnis = new UnitBisnisProfile([
                'user_id' => $user->id,
                'nama_usaha' => $user->name,
                'jenis_usaha' => null,
                'lokasi_lat' => '-6.9271',
                'lokasi_lng' => '107.6411',
                'radius_penjemputan' => 15,
                'jam_buka' => '08:00',
                'jam_tutup' => '21:00',
                'foto_bisnis' => 'images/placeholder-bisnis.jpg',
                'verified' => true,
                'tahun_bergabung' => now()->year,
            ]);
        }

        $unitBisnis->alamat = $user->alamat ?? '';

        // Ambil semua pesanan selesai milik unit bisnis ini berdasarkan unit_bisnis_id
        $pesanans = $unitBisnis->id
            ? Pesanan::where('unit_bisnis_id', $unitBisnis->id)
                ->where('status', 'selesai')
                ->with('menuAktif.masterMakanan')
                ->get()
            : collect();

        $totalPorsi = $pesanans->sum('jumlah_porsi');

        $totalKg = $pesanans->sum(function ($pesanan) {
            $berat = $pesanan->menuAktif->masterMakanan->berat ?? 0;
            return $berat * $pesanan->jumlah_porsi;
        });

        return [
            'unitBisnis' => $unitBisnis,
            'user' => $user,
            'stats' => [
                'total_porsi' => $totalPorsi,
                'total_kg' => $totalKg,
                'jumlah_pesanan' => $pesanans->count(),
                'total_kontribusi' => $pesanans->count(),
            ],
            'isNew' => !UnitBisnisProfile::where('user_id', $user->id)->exists(),
        ];
    }

    public function index(Request $request)
    {
        $data = $this->getUnitBisnisData();
        $unitBisnis = $data['unitBisnis'];
        $user = $data['user'];

        if (!$unitBisnis || !$unitBisnis->id) {
            return redirect()->route('unit.dashboard')->with('error', 'Profil unit bisnis tidak ditemukan.');
        }

        $now = Carbon::now();
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Base Query untuk Agregat
        $pesananQuery = Pesanan::where('unit_bisnis_id', $unitBisnis->id);
        $ratingQuery = \App\Models\Rating::where('unit_bisnis_id', $unitBisnis->id);

        if ($startDate && $endDate) {
            $pesananQuery->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            $ratingQuery->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        // 1. Total Pendapatan (Filtered by Date if provided)
        $totalPendapatan = (clone $pesananQuery)
            ->whereIn('status', ['selesai', 'dibayar', 'siap_diambil'])
            ->sum('total_harga');

        // Sales Growth (Tetap bulanan sebagai perbandingan)
        $pendapatanBulanIni = Pesanan::where('unit_bisnis_id', $unitBisnis->id)
            ->whereIn('status', ['selesai', 'dibayar', 'siap_diambil'])
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->sum('total_harga');

        $pendapatanBulanLalu = Pesanan::where('unit_bisnis_id', $unitBisnis->id)
            ->whereIn('status', ['selesai', 'dibayar', 'siap_diambil'])
            ->whereMonth('created_at', $now->copy()->subMonth()->month)
            ->whereYear('created_at', $now->copy()->subMonth()->year)
            ->sum('total_harga');

        $salesGrowth = $pendapatanBulanLalu > 0
            ? round((($pendapatanBulanIni - $pendapatanBulanLalu) / $pendapatanBulanLalu) * 100, 1)
            : ($pendapatanBulanIni > 0 ? 100 : 0);

        // 2. Makanan Terselamatkan (Filtered by Date)
        $makananTerselamatkan = (clone $pesananQuery)
            ->where('status', 'selesai')
            ->sum('jumlah_porsi');

        // 3. Rating Kepuasan (Filtered by Date)
        $ratingKepuasan = (clone $ratingQuery)->avg('nilai') ?? 0;
        $ratingKepuasan = round($ratingKepuasan, 1);
        $totalUlasan = (clone $ratingQuery)->count();

        // 4. Query Transaksi (with search & filter)
        $query = Pesanan::with(['menuAktif.masterMakanan', 'user.individuProfile', 'user.komunitasProfile'])
            ->where('unit_bisnis_id', $unitBisnis->id);

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('menuAktif.masterMakanan', function($q2) use ($search) {
                    $q2->where('nama_makanan', 'like', "%{$search}%");
                })
                ->orWhereHas('user', function($q3) use ($search) {
                    $q3->where('name', 'like', "%{$search}%");
                })
                ->orWhere('kode_unik', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $transaksi = $query->orderBy('created_at', 'desc')->paginate(10);
        $transaksi->appends($request->all());

        return view('unit_bisnis.riwayat', array_merge($data, [
            'totalPendapatan' => $totalPendapatan,
            'salesGrowth' => $salesGrowth,
            'makananTerselamatkan' => $makananTerselamatkan,
            'ratingKepuasan' => $ratingKepuasan,
            'totalUlasan' => $totalUlasan,
            'transaksi' => $transaksi
        ]));
    }

    public function export(Request $request)
    {
        $user = Auth::user();
        $unitBisnis = UnitBisnisProfile::where('user_id', $user->id)->first();

        if (!$unitBisnis) {
            return redirect()->back()->with('error', 'Profil unit bisnis tidak ditemukan.');
        }

        $query = Pesanan::with(['menuAktif.masterMakanan', 'user'])
            ->where('unit_bisnis_id', $unitBisnis->id);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('menuAktif.masterMakanan', function($q2) use ($search) {
                    $q2->where('nama_makanan', 'like', "%{$search}%");
                })
                ->orWhereHas('user', function($q3) use ($search) {
                    $q3->where('name', 'like', "%{$search}%");
                })
                ->orWhere('kode_unik', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $transaksi = $query->orderBy('created_at', 'desc')->get();

        $fileName = 'Laporan_Riwayat_Pesanan_' . Carbon::now()->format('Ymd_His') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($transaksi) {
            $file = fopen('php://output', 'w');
            
            // Kolom CSV
            fputcsv($file, [
                'ID Pesanan', 
                'Tanggal', 
                'Waktu', 
                'Kode Unik', 
                'Nama Makanan', 
                'Penerima/Relawan', 
                'Porsi', 
                'Total Harga (Rp)', 
                'Status'
            ]);

            foreach ($transaksi as $item) {
                $makananName = $item->menuAktif->masterMakanan->nama_makanan ?? 'Tidak diketahui';
                $penerimaName = $item->user->name ?? 'Tidak diketahui';

                fputcsv($file, [
                    $item->id,
                    $item->created_at->format('Y-m-d'),
                    $item->created_at->format('H:i:s'),
                    $item->kode_unik,
                    $makananName,
                    $penerimaName,
                    $item->jumlah_porsi,
                    $item->total_harga,
                    ucfirst($item->status)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function show($id)
    {
        $user = Auth::user();
        $unitBisnis = UnitBisnisProfile::where('user_id', $user->id)->first();

        if (!$unitBisnis) {
            return redirect()->back()->with('error', 'Profil unit bisnis tidak ditemukan.');
        }

        $pesanan = Pesanan::with(['menuAktif.masterMakanan', 'user', 'rating', 'buktiDonasis'])
            ->where('id', $id)
            ->where('unit_bisnis_id', $unitBisnis->id)
            ->firstOrFail();

        return view('unit_bisnis.detail_riwayat', compact('pesanan', 'unitBisnis'));
    }
}