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

        // 2. Tentukan batasan tanggal berdasarkan filter
        $now = Carbon::now();
        $start = null;
        $end = (clone $now)->endOfDay();

        $startPrev = null;
        $endPrev = null;

        if ($rentangWaktu == 'bulan_ini') {
            $start = (clone $now)->startOfMonth();
            $end = (clone $now)->endOfMonth();
            
            $startPrev = (clone $now)->subMonth()->startOfMonth();
            $endPrev = (clone $now)->subMonth()->endOfMonth();
        } elseif ($rentangWaktu == 'bulan_lalu') {
            $start = (clone $now)->subMonth()->startOfMonth();
            $end = (clone $now)->subMonth()->endOfMonth();
            
            $startPrev = (clone $now)->subMonths(2)->startOfMonth();
            $endPrev = (clone $now)->subMonths(2)->endOfMonth();
        } elseif ($rentangWaktu == 'minggu_ini') {
            $start = (clone $now)->startOfWeek();
            $end = (clone $now)->endOfWeek();
            
            $startPrev = (clone $now)->subWeek()->startOfWeek();
            $endPrev = (clone $now)->subWeek()->endOfWeek();
        } else {
            // Semua Waktu
            $earliest = Pesanan::min('waktu_pesan') ?: (Pesanan::min('created_at') ?: (clone $now)->subYear());
            $start = Carbon::parse($earliest)->startOfDay();
            
            // Bandingkan dengan data sebelum awal bulan berjalan
            $endPrev = (clone $now)->startOfMonth();
        }

        // 3. Hitung statistik Unit Bisnis & Komunitas berdasarkan kategori_entitas
        $totalUnitBisnis = '-';
        $ubGrowth = '-';
        $totalKomunitas = '-';
        $komGrowth = '-';

        // Hitung Unit Bisnis jika kategori adalah semua_kategori atau unit_bisnis
        if ($kategoriEntitas == 'semua_kategori' || $kategoriEntitas == 'unit_bisnis') {
            $ubQuery = User::where('role', 'unit_bisnis')->where('created_at', '<=', $end);
            if ($search) {
                $ubQuery->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhereHas('unitBisnisProfile', function($qp) use ($search) {
                          $qp->where('nama_usaha', 'like', "%{$search}%");
                      });
                });
            }
            $totalUnitBisnis = $ubQuery->count();

            $ubQueryPrev = User::where('role', 'unit_bisnis');
            if ($rentangWaktu == 'semua_waktu') {
                $ubQueryPrev->where('created_at', '<', $endPrev);
            } else {
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
            $totalUnitBisnisPrev = $ubQueryPrev->count();
            $ubGrowth = $totalUnitBisnisPrev > 0 ? round((($totalUnitBisnis - $totalUnitBisnisPrev) / $totalUnitBisnisPrev) * 100) : ($totalUnitBisnis > 0 ? 100 : 0);
        }

        // Hitung Komunitas jika kategori adalah semua_kategori, komunitas, atau individu
        if ($kategoriEntitas == 'semua_kategori' || $kategoriEntitas == 'komunitas' || $kategoriEntitas == 'individu') {
            $rolesToCount = [];
            if ($kategoriEntitas == 'semua_kategori') {
                $rolesToCount = ['komunitas', 'individu'];
            } elseif ($kategoriEntitas == 'komunitas') {
                $rolesToCount = ['komunitas'];
            } elseif ($kategoriEntitas == 'individu') {
                $rolesToCount = ['individu'];
            }

            $komQuery = User::whereIn('role', $rolesToCount)->where('created_at', '<=', $end);
            if ($search) {
                $komQuery->where('name', 'like', "%{$search}%");
            }
            $totalKomunitas = $komQuery->count();

            $komQueryPrev = User::whereIn('role', $rolesToCount);
            if ($rentangWaktu == 'semua_waktu') {
                $komQueryPrev->where('created_at', '<', $endPrev);
            } else {
                $komQueryPrev->where('created_at', '<=', $endPrev);
            }
            if ($search) {
                $komQueryPrev->where('name', 'like', "%{$search}%");
            }
            $totalKomunitasPrev = $komQueryPrev->count();
            $komGrowth = $totalKomunitasPrev > 0 ? round((($totalKomunitas - $totalKomunitasPrev) / $totalKomunitasPrev) * 100) : ($totalKomunitas > 0 ? 100 : 0);
        }

        // 4. Query Pesanan (transaksi) terfilter
        $pesananQuery = Pesanan::query();
        $pesananQueryPrev = Pesanan::query();

        if ($start && $end) {
            $pesananQuery->whereBetween('waktu_pesan', [$start, $end]);
        }
        if ($rentangWaktu == 'semua_waktu') {
            if ($endPrev) {
                $pesananQueryPrev->where('waktu_pesan', '<', $endPrev);
            }
        } else {
            if ($startPrev && $endPrev) {
                $pesananQueryPrev->whereBetween('waktu_pesan', [$startPrev, $endPrev]);
            }
        }

        if ($kategoriEntitas == 'komunitas') {
            $pesananQuery->whereHas('user', function($q) {
                $q->where('role', 'komunitas');
            });
            $pesananQueryPrev->whereHas('user', function($q) {
                $q->where('role', 'komunitas');
            });
        } elseif ($kategoriEntitas == 'individu') {
            $pesananQuery->whereHas('user', function($q) {
                $q->where('role', 'individu');
            });
            $pesananQueryPrev->whereHas('user', function($q) {
                $q->where('role', 'individu');
            });
        }

        if ($search) {
            $searchFilter = function($q) use ($search) {
                $q->whereHas('unitBisnis', function($qb) use ($search) {
                    $qb->where('nama_usaha', 'like', "%{$search}%");
                })->orWhereHas('user', function($qu) use ($search) {
                    $qu->where('name', 'like', "%{$search}%");
                })->orWhereHas('menuAktif.masterMakanan', function($qm) use ($search) {
                    $qm->where('nama_makanan', 'like', "%{$search}%");
                });
            };
            $pesananQuery->where($searchFilter);
            $pesananQueryPrev->where($searchFilter);
        }

        // 5. Hitung metrik makanan (porsi) & total transaksi (berhasil = selesai)
        $totalMakanan = (clone $pesananQuery)->where('status', 'selesai')->sum('jumlah_porsi') ?: 0;
        $totalMakananPrev = (clone $pesananQueryPrev)->where('status', 'selesai')->sum('jumlah_porsi') ?: 0;
        $makananGrowth = $totalMakananPrev > 0 ? round((($totalMakanan - $totalMakananPrev) / $totalMakananPrev) * 100) : ($totalMakanan > 0 ? 100 : 0);

        $totalTransaksi = (clone $pesananQuery)->where('status', 'selesai')->count();
        $totalTransaksiPrev = (clone $pesananQueryPrev)->where('status', 'selesai')->count();
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

        // 6. Query Transaksi Terbaru terfilter (limit 5 untuk dashboard)
        $pesanans = (clone $pesananQuery)->with(['unitBisnis', 'user', 'menuAktif.masterMakanan'])
            ->latest('waktu_pesan')
            ->limit(5)
            ->get();

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

        // 7. Hitung Status Distribusi terfilter (Doughnut Chart)
        $doneCount = (clone $pesananQuery)->where('status', 'selesai')->count();
        $procCount = (clone $pesananQuery)->whereIn('status', ['menunggu_pembayaran', 'dibayar', 'siap_diambil'])->count();
        $failCount = (clone $pesananQuery)->where('status', 'dibatalkan')->count();
        $totalDist = $doneCount + $procCount + $failCount;
        $successRate = $totalDist > 0 ? round(($doneCount / $totalDist) * 100) : 0;

        // 8. Query Chart Bulanan secara riil untuk tahun 2026 (Jan - Jun) terfilter
        $months = [1, 2, 3, 4, 5, 6];
        $chartAktivitasUser = [];
        $chartJumlahMakanan = [];
        $chartJumlahTransaksi = [];

        foreach ($months as $m) {
            $monthQuery = Pesanan::whereMonth('waktu_pesan', $m)
                ->whereYear('waktu_pesan', 2026);

            if ($kategoriEntitas == 'komunitas') {
                $monthQuery->whereHas('user', function($q) {
                    $q->where('role', 'komunitas');
                });
            } elseif ($kategoriEntitas == 'individu') {
                $monthQuery->whereHas('user', function($q) {
                    $q->where('role', 'individu');
                });
            }

            if ($search) {
                $monthQuery->where(function($q) use ($search) {
                    $q->whereHas('unitBisnis', function($qb) use ($search) {
                        $qb->where('nama_usaha', 'like', "%{$search}%");
                    })->orWhereHas('user', function($qu) use ($search) {
                        $qu->where('name', 'like', "%{$search}%");
                    })->orWhereHas('menuAktif.masterMakanan', function($qm) use ($search) {
                        $qm->where('nama_makanan', 'like', "%{$search}%");
                    });
                });
            }

            $chartAktivitasUser[] = (clone $monthQuery)->distinct('user_id')->count('user_id');
            $chartJumlahMakanan[] = (int) (clone $monthQuery)->where('status', 'selesai')->sum('jumlah_porsi');
            $chartJumlahTransaksi[] = (clone $monthQuery)->where('status', 'selesai')->count();
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

        // 2. Tentukan batasan tanggal berdasarkan filter
        $now = Carbon::now();
        $start = null;
        $end = (clone $now)->endOfDay();

        $startPrev = null;
        $endPrev = null;

        if ($rentangWaktu == 'bulan_ini') {
            $start = (clone $now)->startOfMonth();
            $end = (clone $now)->endOfMonth();
            
            $startPrev = (clone $now)->subMonth()->startOfMonth();
            $endPrev = (clone $now)->subMonth()->endOfMonth();
        } elseif ($rentangWaktu == 'bulan_lalu') {
            $start = (clone $now)->subMonth()->startOfMonth();
            $end = (clone $now)->subMonth()->endOfMonth();
            
            $startPrev = (clone $now)->subMonths(2)->startOfMonth();
            $endPrev = (clone $now)->subMonths(2)->endOfMonth();
        } elseif ($rentangWaktu == 'minggu_ini') {
            $start = (clone $now)->startOfWeek();
            $end = (clone $now)->endOfWeek();
            
            $startPrev = (clone $now)->subWeek()->startOfWeek();
            $endPrev = (clone $now)->subWeek()->endOfWeek();
        } else {
            // Semua Waktu
            $earliest = Pesanan::min('waktu_pesan') ?: (Pesanan::min('created_at') ?: (clone $now)->subYear());
            $start = Carbon::parse($earliest)->startOfDay();
            
            // Bandingkan dengan data sebelum awal bulan berjalan
            $endPrev = (clone $now)->startOfMonth();
        }

        $periodeStart = $start->translatedFormat('d M Y');
        $periodeEnd = $end->translatedFormat('d M Y');
        $dibuatPada = Carbon::now()->translatedFormat('d M Y');

        // 3. Hitung statistik Unit Bisnis & Komunitas berdasarkan kategori_entitas
        $totalUnitBisnis = '-';
        $ubGrowth = '-';
        $totalKomunitas = '-';
        $komGrowth = '-';

        // Hitung Unit Bisnis jika kategori adalah semua_kategori atau unit_bisnis
        if ($kategoriEntitas == 'semua_kategori' || $kategoriEntitas == 'unit_bisnis') {
            $ubQuery = User::where('role', 'unit_bisnis')->where('created_at', '<=', $end);
            if ($search) {
                $ubQuery->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhereHas('unitBisnisProfile', function($qp) use ($search) {
                          $qp->where('nama_usaha', 'like', "%{$search}%");
                      });
                });
            }
            $totalUnitBisnis = $ubQuery->count();

            $ubQueryPrev = User::where('role', 'unit_bisnis');
            if ($rentangWaktu == 'semua_waktu') {
                $ubQueryPrev->where('created_at', '<', $endPrev);
            } else {
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
            $totalUnitBisnisPrev = $ubQueryPrev->count();
            $ubGrowth = $totalUnitBisnisPrev > 0 ? round((($totalUnitBisnis - $totalUnitBisnisPrev) / $totalUnitBisnisPrev) * 100) : ($totalUnitBisnis > 0 ? 100 : 0);
        }

        // Hitung Komunitas jika kategori adalah semua_kategori, komunitas, atau individu
        if ($kategoriEntitas == 'semua_kategori' || $kategoriEntitas == 'komunitas' || $kategoriEntitas == 'individu') {
            $rolesToCount = [];
            if ($kategoriEntitas == 'semua_kategori') {
                $rolesToCount = ['komunitas', 'individu'];
            } elseif ($kategoriEntitas == 'komunitas') {
                $rolesToCount = ['komunitas'];
            } elseif ($kategoriEntitas == 'individu') {
                $rolesToCount = ['individu'];
            }

            $komQuery = User::whereIn('role', $rolesToCount)->where('created_at', '<=', $end);
            if ($search) {
                $komQuery->where('name', 'like', "%{$search}%");
            }
            $totalKomunitas = $komQuery->count();

            $komQueryPrev = User::whereIn('role', $rolesToCount);
            if ($rentangWaktu == 'semua_waktu') {
                $komQueryPrev->where('created_at', '<', $endPrev);
            } else {
                $komQueryPrev->where('created_at', '<=', $endPrev);
            }
            if ($search) {
                $komQueryPrev->where('name', 'like', "%{$search}%");
            }
            $totalKomunitasPrev = $komQueryPrev->count();
            $komGrowth = $totalKomunitasPrev > 0 ? round((($totalKomunitas - $totalKomunitasPrev) / $totalKomunitasPrev) * 100) : ($totalKomunitas > 0 ? 100 : 0);
        }

        // 4. Query Pesanan terfilter
        $pesananQuery = Pesanan::query();
        $pesananQueryPrev = Pesanan::query();

        if ($start && $end) {
            $pesananQuery->whereBetween('waktu_pesan', [$start, $end]);
        }
        if ($rentangWaktu == 'semua_waktu') {
            if ($endPrev) {
                $pesananQueryPrev->where('waktu_pesan', '<', $endPrev);
            }
        } else {
            if ($startPrev && $endPrev) {
                $pesananQueryPrev->whereBetween('waktu_pesan', [$startPrev, $endPrev]);
            }
        }

        if ($kategoriEntitas == 'komunitas') {
            $pesananQuery->whereHas('user', function($q) {
                $q->where('role', 'komunitas');
            });
            $pesananQueryPrev->whereHas('user', function($q) {
                $q->where('role', 'komunitas');
            });
        } elseif ($kategoriEntitas == 'individu') {
            $pesananQuery->whereHas('user', function($q) {
                $q->where('role', 'individu');
            });
            $pesananQueryPrev->whereHas('user', function($q) {
                $q->where('role', 'individu');
            });
        }

        if ($search) {
            $searchFilter = function($q) use ($search) {
                $q->whereHas('unitBisnis', function($qb) use ($search) {
                    $qb->where('nama_usaha', 'like', "%{$search}%");
                })->orWhereHas('user', function($qu) use ($search) {
                    $qu->where('name', 'like', "%{$search}%");
                })->orWhereHas('menuAktif.masterMakanan', function($qm) use ($search) {
                    $qm->where('nama_makanan', 'like', "%{$search}%");
                });
            };
            $pesananQuery->where($searchFilter);
            $pesananQueryPrev->where($searchFilter);
        }

        // 5. Hitung metrik makanan (KG) & total transaksi (berhasil = selesai)
        $totalMakanan = (clone $pesananQuery)->where('status', 'selesai')->sum('jumlah_porsi') ?: 0;
        $makananKg = $totalMakanan * 0.2;
        
        $totalMakananPrev = (clone $pesananQueryPrev)->where('status', 'selesai')->sum('jumlah_porsi') ?: 0;
        $makananGrowth = $totalMakananPrev > 0 ? round((($totalMakanan - $totalMakananPrev) / $totalMakananPrev) * 100) : ($totalMakanan > 0 ? 100 : 0);

        $totalTransaksiCurrent = (clone $pesananQuery)->where('status', 'selesai')->count();
        $totalTransaksiPrev = (clone $pesananQueryPrev)->where('status', 'selesai')->count();
        $transaksiGrowth = $totalTransaksiPrev > 0 ? round((($totalTransaksiCurrent - $totalTransaksiPrev) / $totalTransaksiPrev) * 100) : ($totalTransaksiCurrent > 0 ? 100 : 0);

        $reportStats = [
            'unit_bisnis' => $totalUnitBisnis,
            'komunitas' => $totalKomunitas,
            'makanan_kg' => number_format($makananKg, 1, ',', '.'),
            'total_transaksi' => number_format($totalTransaksiCurrent, 0, ',', '.'),
            'unit_bisnis_change' => $ubGrowth === '-' ? '-' : (($ubGrowth >= 0 ? '+' : '') . $ubGrowth . '%'),
            'komunitas_change' => $komGrowth === '-' ? '-' : (($komGrowth >= 0 ? '+' : '') . $komGrowth . '%'),
            'unit_bisnis_change_raw' => $ubGrowth,
            'komunitas_change_raw' => $komGrowth,
            'makanan_growth' => $makananGrowth,
            'transaksi_growth' => $transaksiGrowth,
        ];

        // 6. Query Transaksi log terfilter (limit 50 item terbaru untuk mendukung cetak multi-halaman jika data banyak)
        $currentMonthPesanans = (clone $pesananQuery)->with(['unitBisnis', 'menuAktif.masterMakanan'])
            ->latest('waktu_pesan')
            ->limit(50)
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

        // 7. Hitung pertumbuhan donasi mingguan/periodik di rentang berjalan terfilter
        $mingguData = [];
        $totalDuration = $start->diffInSeconds($end);
        $interval = $totalDuration / 4;

        for ($w = 1; $w <= 4; $w++) {
            $subStart = $start->copy()->addSeconds($interval * ($w - 1));
            $subEnd = $start->copy()->addSeconds($interval * $w);
            
            $porsiSub = Pesanan::where('status', 'selesai')
                ->whereBetween('waktu_pesan', [$subStart, $subEnd]);

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
            ]
        ]);
    }
}
