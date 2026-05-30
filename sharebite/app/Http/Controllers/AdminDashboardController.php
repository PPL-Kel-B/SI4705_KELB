<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UnitBisnisProfile;
use App\Models\KomunitasProfile;
use App\Models\IndividuProfile;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil input filter
        $search = $request->input('search');
        $rentangWaktu = $request->input('rentang_waktu', 'semua_waktu');
        $kategoriEntitas = $request->input('kategori_entitas', 'semua_kategori');

        // 2. Query data statistik utama dari database (dihitung dari tabel users agar sinkron dengan seeder)
        $totalUnitBisnis = User::where('role', 'unit_bisnis')->count();
        $totalKomunitas = User::whereIn('role', ['komunitas', 'individu'])->count();
        
        // Makanan dihitung dari total porsi pesanan yang sukses/selesai
        $totalMakanan = Pesanan::where('status', 'selesai')->sum('jumlah_porsi') ?: 0;
        
        $totalTransaksi = Pesanan::count();

        // Bandingkan dengan awal bulan ini untuk menghitung persen pertumbuhan (MoM)
        $startOfMonth = Carbon::now()->startOfMonth();

        $totalUnitBisnisPrev = User::where('role', 'unit_bisnis')->where('created_at', '<', $startOfMonth)->count();
        $totalKomunitasPrev = User::whereIn('role', ['komunitas', 'individu'])->where('created_at', '<', $startOfMonth)->count();
        $totalMakananPrev = Pesanan::where('status', 'selesai')->where('waktu_pesan', '<', $startOfMonth)->sum('jumlah_porsi') ?: 0;
        $totalTransaksiPrev = Pesanan::where('waktu_pesan', '<', $startOfMonth)->count();

        $ubGrowth = $totalUnitBisnisPrev > 0 ? round((($totalUnitBisnis - $totalUnitBisnisPrev) / $totalUnitBisnisPrev) * 100) : ($totalUnitBisnis > 0 ? 100 : 0);
        $komGrowth = $totalKomunitasPrev > 0 ? round((($totalKomunitas - $totalKomunitasPrev) / $totalKomunitasPrev) * 100) : ($totalKomunitas > 0 ? 100 : 0);
        $makananGrowth = $totalMakananPrev > 0 ? round((($totalMakanan - $totalMakananPrev) / $totalMakananPrev) * 100) : ($totalMakanan > 0 ? 100 : 0);
        $transaksiGrowth = $totalTransaksiPrev > 0 ? round((($totalTransaksi - $totalTransaksiPrev) / $totalTransaksiPrev) * 100) : ($totalTransaksi > 0 ? 100 : 0);

        $stats = [
            'total_unit_bisnis' => $totalUnitBisnis,
            'total_unit_bisnis_growth' => $ubGrowth,
            'total_komunitas' => $totalKomunitas,
            'total_komunitas_growth' => $komGrowth,
            'total_makanan' => $totalMakanan,
            'total_makanan_growth' => $makananGrowth,
            'total_transaksi' => $totalTransaksi,
            'total_transaksi_growth' => $transaksiGrowth,
        ];

        // 3. Query Transaksi Terbaru
        $query = Pesanan::with(['unitBisnis', 'user', 'menuAktif.masterMakanan'])
            ->latest('waktu_pesan');

        // Filter waktu
        if ($rentangWaktu == 'bulan_ini') {
            $query->whereMonth('waktu_pesan', Carbon::now()->month)
                  ->whereYear('waktu_pesan', Carbon::now()->year);
        } elseif ($rentangWaktu == 'bulan_lalu') {
            $query->whereMonth('waktu_pesan', Carbon::now()->subMonth()->month)
                  ->whereYear('waktu_pesan', Carbon::now()->subMonth()->year);
        } elseif ($rentangWaktu == 'minggu_ini') {
            $query->where('waktu_pesan', '>=', Carbon::now()->startOfWeek());
        }

        // Filter entitas pemesan
        if ($kategoriEntitas == 'komunitas') {
            $query->whereHas('user', function($q) {
                $q->where('role', 'komunitas');
            });
        } elseif ($kategoriEntitas == 'individu') {
            $query->whereHas('user', function($q) {
                $q->where('role', 'individu');
            });
        }

        // Filter pencarian global
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('unitBisnis', function($qb) use ($search) {
                    $qb->where('nama_usaha', 'like', "%{$search}%");
                })->orWhereHas('user', function($qu) use ($search) {
                    $qu->where('name', 'like', "%{$search}%");
                })->orWhereHas('menuAktif.masterMakanan', function($qm) use ($search) {
                    $qm->where('nama_makanan', 'like', "%{$search}%");
                });
            });
        }

        $pesanans = $query->limit(5)->get();

        $transactions = $pesanans->map(function ($pesanan) {
            $mitra = $pesanan->unitBisnis ? $pesanan->unitBisnis->nama_usaha : 'Mitra Tidak Dikenal';
            $penerima = $pesanan->user ? $pesanan->user->name : 'Penerima';
            
            $item = $pesanan->jumlah_porsi . ' Porsi';
            if ($pesanan->menuAktif && $pesanan->menuAktif->masterMakanan) {
                $item = $pesanan->jumlah_porsi . ' ' . $pesanan->menuAktif->masterMakanan->nama_makanan;
            }

            $status = 'PROSES';
            if ($pesanan->status == 'selesai') {
                $status = 'SELESAI';
            } elseif ($pesanan->status == 'dibatalkan') {
                $status = 'DIBATALKAN';
            }

            $waktu = 'N/A';
            if ($pesanan->waktu_pesan) {
                $waktu = $pesanan->waktu_pesan->diffForHumans();
            } elseif ($pesanan->created_at) {
                $waktu = $pesanan->created_at->diffForHumans();
            }

            return [
                'mitra' => $mitra,
                'penerima' => $penerima,
                'item' => $item,
                'status' => $status,
                'waktu' => $waktu,
            ];
        })->toArray();

        // 4. Hitung Status Distribusi (Doughnut Chart)
        $doneCount = Pesanan::where('status', 'selesai')->count();
        $procCount = Pesanan::whereIn('status', ['menunggu_pembayaran', 'dibayar', 'siap_diambil'])->count();
        $failCount = Pesanan::where('status', 'dibatalkan')->count();
        $totalDist = $doneCount + $procCount + $failCount;
        $successRate = $totalDist > 0 ? round(($doneCount / $totalDist) * 100) : 0;

        // 5. Query Chart Bulanan secara riil untuk tahun 2026 (Jan - Jun)
        $months = [1, 2, 3, 4, 5, 6];
        $chartAktivitasUser = [];
        $chartJumlahMakanan = [];
        $chartJumlahTransaksi = [];

        foreach ($months as $m) {
            $chartAktivitasUser[] = Pesanan::whereMonth('waktu_pesan', $m)
                ->whereYear('waktu_pesan', 2026)
                ->distinct('user_id')
                ->count();

            $chartJumlahMakanan[] = (int) Pesanan::whereMonth('waktu_pesan', $m)
                ->whereYear('waktu_pesan', 2026)
                ->where('status', 'selesai')
                ->sum('jumlah_porsi');

            $chartJumlahTransaksi[] = Pesanan::whereMonth('waktu_pesan', $m)
                ->whereYear('waktu_pesan', 2026)
                ->count();
        }

        return view('admin.dashboard', [
            'stats' => $stats,
            'transactions' => $transactions,
            'filters' => [
                'search' => $search,
                'rentang_waktu' => $rentangWaktu,
                'kategori_entitas' => $kategoriEntitas,
            ],
            'charts' => [
                'aktivitas_user' => json_encode($chartAktivitasUser),
                'jumlah_makanan' => json_encode($chartJumlahMakanan),
                'jumlah_transaksi' => json_encode($chartJumlahTransaksi),
            ],
            'distribution' => [
                'done' => $doneCount,
                'proc' => $procCount,
                'fail' => $failCount,
                'success_rate' => $successRate,
            ]
        ]);
    }

    public function allTransactions(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $query = Pesanan::with(['unitBisnis', 'user', 'menuAktif.masterMakanan'])
            ->latest('waktu_pesan');

        if ($status && $status != 'semua') {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('unitBisnis', function($qb) use ($search) {
                    $qb->where('nama_usaha', 'like', "%{$search}%");
                })->orWhereHas('user', function($qu) use ($search) {
                    $qu->where('name', 'like', "%{$search}%");
                })->orWhereHas('menuAktif.masterMakanan', function($qm) use ($search) {
                    $qm->where('nama_makanan', 'like', "%{$search}%");
                });
            });
        }

        $transactions = $query->paginate(10)->withQueryString();

        return view('admin.transaksi', compact('transactions', 'search', 'status'));
    }

    public function report(Request $request)
    {
        // 1. Ambil input filter
        $search = $request->input('search');
        $rentangWaktu = $request->input('rentang_waktu', 'semua_waktu');
        $kategoriEntitas = $request->input('kategori_entitas', 'semua_kategori');

        // Rentang Waktu filter
        $start = null;
        $end = Carbon::now()->endOfDay();

        if ($rentangWaktu == 'bulan_ini') {
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now()->endOfMonth();
        } elseif ($rentangWaktu == 'bulan_lalu') {
            $start = Carbon::now()->subMonth()->startOfMonth();
            $end = Carbon::now()->subMonth()->endOfMonth();
        } elseif ($rentangWaktu == 'minggu_ini') {
            $start = Carbon::now()->startOfWeek();
            $end = Carbon::now()->endOfWeek();
        }

        // Jika semua_waktu, set start ke waktu transaksi pertama di DB (agar periode di header terisi lengkap)
        if (!$start) {
            $earliest = Pesanan::min('waktu_pesan') ?: (Pesanan::min('created_at') ?: Carbon::now()->subYear());
            $start = Carbon::parse($earliest)->startOfDay();
        }
        
        $periodeStart = $start->translatedFormat('d M Y');
        $periodeEnd = $end->translatedFormat('d M Y');
        $dibuatPada = Carbon::now()->translatedFormat('d M Y'); // Hari ini

        // Query transaksi (Pesanan) terfilter
        $pesananQuery = Pesanan::query();

        if ($start && $end) {
            $pesananQuery->whereBetween('waktu_pesan', [$start, $end]);
        }

        if ($kategoriEntitas == 'komunitas') {
            $pesananQuery->whereHas('user', function($q) {
                $q->where('role', 'komunitas');
            });
        } elseif ($kategoriEntitas == 'individu') {
            $pesananQuery->whereHas('user', function($q) {
                $q->where('role', 'individu');
            });
        }

        if ($search) {
            $pesananQuery->where(function($q) use ($search) {
                $q->whereHas('unitBisnis', function($qb) use ($search) {
                    $qb->where('nama_usaha', 'like', "%{$search}%");
                })->orWhereHas('user', function($qu) use ($search) {
                    $qu->where('name', 'like', "%{$search}%");
                })->orWhereHas('menuAktif.masterMakanan', function($qm) use ($search) {
                    $qm->where('nama_makanan', 'like', "%{$search}%");
                });
            });
        }

        // Menghitung statistik terfilter secara dinamis
        // Untuk Unit Bisnis & Komunitas: jika kategori disaring ke komunitas/individu saja, maka unit bisnis yang tampil adalah unit bisnis yang bertransaksi dengan entitas tersebut.
        if ($kategoriEntitas == 'komunitas' || $kategoriEntitas == 'individu') {
            $unitBisnisCount = (clone $pesananQuery)->distinct('unit_bisnis_id')->count('unit_bisnis_id');
        } else {
            $ubQuery = User::where('role', 'unit_bisnis');
            if ($rentangWaktu != 'semua_waktu') {
                $ubQuery->where('created_at', '<=', $end);
            }
            if ($search) {
                $ubQuery->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhereHas('unitBisnisProfile', function($qp) use ($search) {
                          $qp->where('nama_usaha', 'like', "%{$search}%");
                      });
                });
            }
            $unitBisnisCount = $ubQuery->count();
        }

        if ($kategoriEntitas == 'unit_bisnis') {
            $komunitasCount = (clone $pesananQuery)->distinct('user_id')->count('user_id');
        } else {
            $komQuery = User::whereIn('role', ['komunitas', 'individu']);
            if ($kategoriEntitas == 'komunitas') {
                $komQuery->where('role', 'komunitas');
            } elseif ($kategoriEntitas == 'individu') {
                $komQuery->where('role', 'individu');
            }
            if ($rentangWaktu != 'semua_waktu') {
                $komQuery->where('created_at', '<=', $end);
            }
            if ($search) {
                $komQuery->where('name', 'like', "%{$search}%");
            }
            $komunitasCount = $komQuery->count();
        }

        $totalPorsiCurrent = (clone $pesananQuery)->where('status', 'selesai')->sum('jumlah_porsi') ?: 0;
        $makananKg = $totalPorsiCurrent * 0.2;
        $totalTransaksiCurrent = (clone $pesananQuery)->count();

        // Hitung perbandingan untuk persentase pertumbuhan dinamis laporan (MoM atau WoW)
        $pesananQueryPrev = Pesanan::query();
        if ($rentangWaktu == 'bulan_ini') {
            $startPrev = Carbon::now()->subMonth()->startOfMonth();
            $endPrev = Carbon::now()->subMonth()->endOfMonth();
            $pesananQueryPrev->whereBetween('waktu_pesan', [$startPrev, $endPrev]);
        } elseif ($rentangWaktu == 'bulan_lalu') {
            $startPrev = Carbon::now()->subMonths(2)->startOfMonth();
            $endPrev = Carbon::now()->subMonths(2)->endOfMonth();
            $pesananQueryPrev->whereBetween('waktu_pesan', [$startPrev, $endPrev]);
        } elseif ($rentangWaktu == 'minggu_ini') {
            $startPrev = Carbon::now()->subWeek()->startOfWeek();
            $endPrev = Carbon::now()->subWeek()->endOfWeek();
            $pesananQueryPrev->whereBetween('waktu_pesan', [$startPrev, $endPrev]);
        } else {
            // semua_waktu: bandingkan data sebelum awal bulan berjalan
            $endPrev = Carbon::now()->startOfMonth();
            $pesananQueryPrev->where('waktu_pesan', '<', $endPrev);
        }

        // Terapkan filter yang sama pada query prev
        if ($kategoriEntitas == 'komunitas') {
            $pesananQueryPrev->whereHas('user', function($q) {
                $q->where('role', 'komunitas');
            });
        } elseif ($kategoriEntitas == 'individu') {
            $pesananQueryPrev->whereHas('user', function($q) {
                $q->where('role', 'individu');
            });
        }
        if ($search) {
            $pesananQueryPrev->where(function($q) use ($search) {
                $q->whereHas('unitBisnis', function($qb) use ($search) {
                    $qb->where('nama_usaha', 'like', "%{$search}%");
                })->orWhereHas('user', function($qu) use ($search) {
                    $qu->where('name', 'like', "%{$search}%");
                })->orWhereHas('menuAktif.masterMakanan', function($qm) use ($search) {
                    $qm->where('nama_makanan', 'like', "%{$search}%");
                });
            });
        }

        // Hitung Unit Bisnis Prev
        if ($kategoriEntitas == 'komunitas' || $kategoriEntitas == 'individu') {
            $unitBisnisPrev = (clone $pesananQueryPrev)->distinct('unit_bisnis_id')->count('unit_bisnis_id');
        } else {
            $ubQueryPrev = User::where('role', 'unit_bisnis');
            if (isset($endPrev)) {
                $ubQueryPrev->where('created_at', '<=', $endPrev);
            }
            if ($search) {
                $ubQueryPrev->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhereHas('unitBisnisProfile', function($qp) use ($search) {
                          $qp->where('nama_usaha', 'like', "%{$search}%");
                      });
                });
            }
            $unitBisnisPrev = $ubQueryPrev->count();
        }

        // Hitung Komunitas Prev
        if ($kategoriEntitas == 'unit_bisnis') {
            $komunitasPrev = (clone $pesananQueryPrev)->distinct('user_id')->count('user_id');
        } else {
            $komQueryPrev = User::whereIn('role', ['komunitas', 'individu']);
            if ($kategoriEntitas == 'komunitas') {
                $komQueryPrev->where('role', 'komunitas');
            } elseif ($kategoriEntitas == 'individu') {
                $komQueryPrev->where('role', 'individu');
            }
            if (isset($endPrev)) {
                $komQueryPrev->where('created_at', '<=', $endPrev);
            }
            if ($search) {
                $komQueryPrev->where('name', 'like', "%{$search}%");
            }
            $komunitasPrev = $komQueryPrev->count();
        }

        $ubGrowth = $unitBisnisPrev > 0 ? round((($unitBisnisCount - $unitBisnisPrev) / $unitBisnisPrev) * 100) : ($unitBisnisCount > 0 ? 100 : 0);
        $komGrowth = $komunitasPrev > 0 ? round((($komunitasCount - $komunitasPrev) / $komunitasPrev) * 100) : ($komunitasCount > 0 ? 100 : 0);

        $reportStats = [
            'unit_bisnis' => $unitBisnisCount,
            'komunitas' => $komunitasCount,
            'makanan_kg' => number_format($makananKg, 1, ',', '.'),
            'total_transaksi' => number_format($totalTransaksiCurrent, 0, ',', '.'),
            'unit_bisnis_change' => ($ubGrowth >= 0 ? '+' : '') . $ubGrowth . '%',
            'komunitas_change' => ($komGrowth >= 0 ? '+' : '') . $komGrowth . '%',
        ];

        // 2. Query Transaksi log terfilter (maks. 15 item terbaru agar muat di cetak PDF)
        $currentMonthPesanans = (clone $pesananQuery)->with(['unitBisnis', 'menuAktif.masterMakanan'])
            ->latest('waktu_pesan')
            ->limit(15)
            ->get();

        $transactions = $currentMonthPesanans->map(function($pesanan) {
            $waktu = 'N/A';
            if ($pesanan->waktu_pesan) {
                $waktu = $pesanan->waktu_pesan->translatedFormat('d M, H:i');
            } elseif ($pesanan->created_at) {
                $waktu = $pesanan->created_at->translatedFormat('d M, H:i');
            }

            $mitra = $pesanan->unitBisnis ? $pesanan->unitBisnis->nama_usaha : 'Mitra Toko';
            
            $kategori = 'Makanan';
            if ($pesanan->menuAktif && $pesanan->menuAktif->masterMakanan) {
                $kategori = $pesanan->menuAktif->masterMakanan->kategori;
            }

            $status = 'SELESAI';
            if ($pesanan->status == 'dibatalkan') {
                $status = 'DIBATALKAN';
            } elseif ($pesanan->status != 'selesai') {
                $status = 'PROSES';
            }

            return [
                'waktu' => $waktu,
                'mitra' => $mitra,
                'kategori' => $kategori,
                'porsi' => $pesanan->jumlah_porsi,
                'status' => $status,
            ];
        })->toArray();

        // 3. Hitung pertumbuhan donasi mingguan/periodik di rentang berjalan
        $mingguData = [];
        $totalDuration = $start->diffInSeconds($end);
        $interval = $totalDuration / 4;

        for ($w = 1; $w <= 4; $w++) {
            $subStart = $start->copy()->addSeconds($interval * ($w - 1));
            $subEnd = $start->copy()->addSeconds($interval * $w);
            
            $porsiSub = Pesanan::where('status', 'selesai')
                ->whereBetween('waktu_pesan', [$subStart, $subEnd]);

            // Terapkan filter yang sama pada query chart
            if ($kategoriEntitas == 'komunitas') {
                $porsiSub->whereHas('user', function($q) {
                    $q->where('role', 'komunitas');
                });
            } elseif ($kategoriEntitas == 'individu') {
                $porsiSub->whereHas('user', function($q) {
                    $q->where('role', 'individu');
                });
            }
            if ($search) {
                $porsiSub->where(function($q) use ($search) {
                    $q->whereHas('unitBisnis', function($qb) use ($search) {
                        $qb->where('nama_usaha', 'like', "%{$search}%");
                    })->orWhereHas('user', function($qu) use ($search) {
                        $qu->where('name', 'like', "%{$search}%");
                    })->orWhereHas('menuAktif.masterMakanan', function($qm) use ($search) {
                        $qm->where('nama_makanan', 'like', "%{$search}%");
                    });
                });
            }

            $mingguData[] = $porsiSub->sum('jumlah_porsi') ?: 0;
        }

        // Akumulasikan ke persentase pertumbuhan kumulatif
        $runningSum = 0;
        $cumulativeData = [];
        $totalPeriodPortions = array_sum($mingguData);
        
        foreach ($mingguData as $portions) {
            $runningSum += $portions;
            if ($totalPeriodPortions > 0) {
                $cumulativeData[] = round(($runningSum / $totalPeriodPortions) * 100);
            } else {
                $cumulativeData[] = 0;
            }
        }

        // 4. Hitung variabel untuk template ringkasan eksekutif
        $p3 = $mingguData[2];
        $p4 = $mingguData[3];
        $weeklyGrowth = $p3 > 0 ? round((($p4 - $p3) / $p3) * 100) : ($p4 > 0 ? 100 : 0);
        
        $newPartnersQuery = UnitBisnisProfile::whereBetween('created_at', [$start, $end]);
        if ($search) {
            $newPartnersQuery->where('nama_usaha', 'like', "%{$search}%");
        }
        $newPartners = $newPartnersQuery->count();
        
        $totalOrdersCurrent = (clone $pesananQuery)->count();
        $doneOrdersCurrent = (clone $pesananQuery)->where('status', 'selesai')->count();
        $efficiency = $totalOrdersCurrent > 0 ? round(($doneOrdersCurrent / $totalOrdersCurrent) * 100, 1) : 0;

        return view('admin.laporan', [
            'periode_start' => $periodeStart,
            'periode_end' => $periodeEnd,
            'dibuat_pada' => $dibuatPada,
            'stats' => $reportStats,
            'transactions' => $transactions,
            'minggu_data' => json_encode($cumulativeData),
            'filters' => [
                'search' => $search,
                'rentang_waktu' => $rentangWaktu,
                'kategori_entitas' => $kategoriEntitas,
            ],
            'summary' => [
                'weekly_growth' => $weeklyGrowth,
                'new_partners' => $newPartners,
                'efficiency' => $efficiency,
            ]
        ]);
    }
}
