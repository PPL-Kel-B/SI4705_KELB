<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Ringkasan Aktivitas - ShareBite</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;950&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #E9EFF4;
        }

        /* Print styles */
        @media print {
            body {
                background-color: #ffffff !important;
            }
            .no-print {
                display: none !important;
            }
            .print-card {
                box-shadow: none !important;
                border: none !important;
                margin: 0 !important;
                padding: 10mm 15mm !important;
                width: 100% !important;
                max-width: 100% !important;
                background-color: white !important;
            }
            .break-inside-avoid {
                break-inside: avoid !important;
                page-break-inside: avoid !important;
            }
            /* Force printing backgrounds */
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            /* Dynamic page numbering */
            .page-num::after {
                content: counter(page, decimal-leading-zero) !important;
            }
        }

        /* Screen-specific page numbering */
        @media screen {
            .page-num::after {
                content: "01";
            }
        }

        /* Fixed dimensions for PDF/A4 preview in browser screen */
        @media screen and (min-width: 1024px) {
            .print-card {
                width: 210mm;
                min-height: 297mm;
            }
        }
    </style>
</head>
<body class="text-gray-800 antialiased min-h-screen py-8 px-4 flex flex-col items-center justify-start">

    <!-- Top Action Bar (no-print) -->
    <div class="w-full max-w-[210mm] flex justify-between items-center mb-6 no-print">
        <a href="{{ route('admin.dashboard', request()->all()) }}" class="inline-flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-gray-700 bg-white px-4 py-2.5 rounded-xl shadow-sm border border-gray-100 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Dashboard
        </a>
        <button onclick="window.print()" class="inline-flex items-center gap-2 bg-[#00502b] hover:bg-[#003d20] text-white px-5 py-2.5 rounded-xl font-bold shadow-md hover:shadow-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            CETAK LAPORAN
        </button>
    </div>

    <!-- Paper Container -->
    <div class="print-card bg-white rounded-3xl p-12 shadow-2xl border border-gray-100 flex flex-col justify-between">
        
        <!-- Printable Table Layout for Repeating Headers/Footers on Multi-Page -->
        <table class="w-full border-none">
            <thead>
                <tr>
                    <th class="font-normal text-left pb-4">
                        <!-- Header Section -->
                        <div class="flex justify-between items-start">
                            <div class="space-y-1">
                                <h1 class="text-[#00502b] text-3xl font-black tracking-tight leading-none">Laporan Ringkasan Aktivitas</h1>
                                <p class="text-xs font-bold text-gray-500 tracking-wider">PERIODE: {{ strtoupper($periode_start) }} — {{ strtoupper($periode_end) }}</p>
                                @if($filters['search'] || $filters['rentang_waktu'] !== 'semua_waktu' || $filters['kategori_entitas'] !== 'semua_kategori')
                                    <p class="text-[9px] font-extrabold text-[#1cb764] tracking-wide uppercase mt-1 leading-none">
                                        Filter Aktif: 
                                        @if($filters['kategori_entitas'] !== 'semua_kategori')
                                            Entitas: {{ ucwords(str_replace('_', ' ', $filters['kategori_entitas'])) }}
                                        @endif
                                        @if($filters['rentang_waktu'] !== 'semua_waktu')
                                            {{ $filters['kategori_entitas'] !== 'semua_kategori' ? ' | ' : '' }} Waktu: {{ ucwords(str_replace('_', ' ', $filters['rentang_waktu'])) }}
                                        @endif
                                        @if($filters['search'])
                                            {{ ($filters['kategori_entitas'] !== 'semua_kategori' || $filters['rentang_waktu'] !== 'semua_waktu') ? ' | ' : '' }} Cari: "{{ $filters['search'] }}"
                                        @endif
                                    </p>
                                @endif
                            </div>
                            <div class="text-right space-y-1 pt-1">
                                <div class="flex items-center justify-end">
                                    <!-- Only ShareBite text, no additional leaf icon next to it -->
                                    <span class="text-2xl font-black text-gray-800 tracking-tight">Share<span class="text-[#1cb764]">Bite</span></span>
                                </div>
                                <p class="text-[8px] font-black text-gray-400 tracking-widest leading-none uppercase">Impact & Operations Division</p>
                                <p class="text-[10px] font-semibold text-gray-400 mt-1">Dibuat pada: {{ $dibuat_pada }}</p>
                            </div>
                        </div>

                        <!-- Solid dividing line -->
                        <hr class="border-t border-gray-250 my-6">
                    </th>
                </tr>
            </thead>
            
            <tbody>
                <tr>
                    <td class="pb-6">
                        <div class="space-y-8">
                            <!-- Stats Boxes Grid (Avoid breaks inside page) -->
                            <div class="grid grid-cols-4 gap-4 break-inside-avoid">
                                <!-- Unit Bisnis -->
                                <div class="border border-gray-150 rounded-xl p-4 bg-gray-50/50 flex flex-col justify-between">
                                    <span class="text-[8px] font-black text-gray-400 tracking-wider uppercase">Unit Bisnis</span>
                                    <div class="flex items-baseline gap-2 mt-1">
                                        <span class="text-2xl font-black text-gray-800">{{ $stats['unit_bisnis'] }}</span>
                                        <span class="text-[10px] font-bold {{ str_contains($stats['unit_bisnis_change'], '-') ? 'text-red-500' : 'text-[#1cb764]' }}">{{ $stats['unit_bisnis_change'] }}</span>
                                    </div>
                                </div>

                                <!-- Komunitas -->
                                <div class="border border-gray-150 rounded-xl p-4 bg-gray-50/50 flex flex-col justify-between">
                                    <span class="text-[8px] font-black text-gray-400 tracking-wider uppercase font-semibold">Komunitas</span>
                                    <div class="flex items-baseline gap-2 mt-1">
                                        <span class="text-2xl font-black text-gray-800">{{ $stats['komunitas'] }}</span>
                                        <span class="text-[10px] font-bold {{ str_contains($stats['komunitas_change'], '-') ? 'text-red-500' : 'text-[#1cb764]' }}">{{ $stats['komunitas_change'] }}</span>
                                    </div>
                                </div>

                                <!-- Makanan (KG) -->
                                <div class="border border-gray-150 rounded-xl p-4 bg-gray-50/50 flex flex-col justify-between">
                                    <span class="text-[8px] font-black text-gray-400 tracking-wider uppercase">Makanan (KG)</span>
                                    <div class="mt-1">
                                        <span class="text-2xl font-black text-[#b97d10]">{{ $stats['makanan_kg'] }}</span>
                                    </div>
                                </div>

                                <!-- Total Transaksi -->
                                <div class="border border-gray-150 rounded-xl p-4 bg-gray-50/50 flex flex-col justify-between">
                                    <span class="text-[8px] font-black text-gray-400 tracking-wider uppercase font-semibold">Total Transaksi</span>
                                    <div class="flex items-center justify-between mt-1">
                                        <span class="text-2xl font-black text-gray-800">{{ $stats['total_transaksi'] }}</span>
                                        <svg class="w-4 h-4 text-[#1cb764]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Pertumbuhan Donasi Makanan Section (Avoid breaks inside page) -->
                            <div class="space-y-4 break-inside-avoid">
                                <div class="flex items-center gap-3">
                                    <div class="w-6 h-1.5 bg-[#00502b]"></div>
                                    <h2 class="text-[#00502b] text-sm font-black tracking-wide uppercase">Pertumbuhan Donasi Makanan</h2>
                                </div>
                                
                                <!-- Chart Area -->
                                <div class="h-64 border border-gray-100 rounded-xl p-4 relative">
                                    <canvas id="reportChart"></canvas>
                                </div>

                                <!-- Executive Summary (Dynamic Values parsed from database) -->
                                <div class="border border-gray-100 rounded-xl p-4 bg-gray-50/30 text-xs text-gray-600 leading-relaxed font-medium">
                                    <strong class="text-[#00502b] font-bold">Executive Summary:</strong> Analisis operasional menunjukkan lonjakan signifikan sebesar {{ $summary['weekly_growth'] }}% pada volume donasi di minggu keempat. Tren ini berkorelasi positif dengan onboarding {{ $summary['new_partners'] }} mitra retail baru. Efisiensi distribusi tercatat stabil pada angka {{ $summary['efficiency'] }}%.
                                </div>
                            </div>

                            <!-- Log Transaksi Terbaru Section (Avoid breaks inside page) -->
                            <div class="space-y-4 break-inside-avoid">
                                <div class="flex items-center gap-3">
                                    <div class="w-6 h-1.5 bg-[#00502b]"></div>
                                    <h2 class="text-[#00502b] text-sm font-black tracking-wide uppercase">Log Transaksi Terbaru</h2>
                                </div>

                                <!-- Table -->
                                <div class="border border-gray-150 rounded-xl overflow-hidden">
                                    <table class="w-full text-left border-collapse text-xs">
                                        <thead>
                                            <tr class="bg-gray-50 border-b border-gray-150 text-[9px] font-black text-gray-400 uppercase tracking-widest">
                                                <th class="py-3 px-4">Waktu</th>
                                                <th class="py-3 px-4">Unit Bisnis</th>
                                                <th class="py-3 px-4">Kategori</th>
                                                <th class="py-3 px-4 text-center">Porsi</th>
                                                <th class="py-3 px-4 text-right">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100 text-gray-700 font-semibold">
                                            @forelse($transactions as $t)
                                            <tr class="hover:bg-gray-50/30">
                                                <td class="py-3 px-4 text-gray-400">{{ $t['waktu'] }}</td>
                                                <td class="py-3 px-4 text-gray-900 font-bold">{{ $t['mitra'] }}</td>
                                                <td class="py-3 px-4 text-gray-500">{{ $t['kategori'] }}</td>
                                                <td class="py-3 px-4 text-center text-gray-500 font-mono">{{ $t['porsi'] }}</td>
                                                <td class="py-3 px-4 text-right">
                                                    @if($t['status'] == 'SELESAI')
                                                        <span class="inline-flex items-center px-2 py-0.5 bg-[#eefcf4] text-[#1cb764] border border-[#d2f6e2] rounded text-[9px] font-bold tracking-wide uppercase">Selesai</span>
                                                    @elseif($t['status'] == 'PROSES')
                                                        <span class="inline-flex items-center px-2 py-0.5 bg-[#fcf7ee] text-[#b97d10] border border-[#fbeed4] rounded text-[9px] font-bold tracking-wide uppercase">Proses</span>
                                                    @else
                                                        <span class="inline-flex items-center px-2 py-0.5 bg-red-50 text-red-600 border border-red-100 rounded text-[9px] font-bold tracking-wide uppercase">Batal</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="5" class="py-8 text-center text-gray-400 font-medium">Belum ada transaksi di periode ini.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
            
            <tfoot>
                <tr>
                    <td class="pt-4">
                        <hr class="border-t border-gray-150 mb-4">
                        <!-- Footer Section -->
                        <div class="flex justify-between items-center text-[9px] font-bold text-gray-400 tracking-wider">
                            <div class="space-y-1">
                                <p>© 2026 SHAREBITE INDONESIA</p>
                                <p class="text-[8px] font-medium text-gray-300">DOKUMEN RAHASIA — HANYA UNTUK PENGGUNAAN INTERNAL</p>
                            </div>
                            <div class="text-right space-y-1">
                                <p class="uppercase">Generated System</p>
                                <p class="text-[8px] font-medium text-gray-300">ShareBite Core Engine v4.2</p>
                            </div>
                            <!-- Green block page indicator showing 01/02/03 dynamically in print -->
                            <div class="w-8 h-8 bg-[#00502b] text-white flex items-center justify-center font-black rounded-lg text-xs">
                                <span class="page-num"></span>
                            </div>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>

    </div>

    <!-- Chart Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const ctx = document.getElementById('reportChart').getContext('2d');
            
            // Create nice light green gradient for report area fill
            const gradient = ctx.createLinearGradient(0, 0, 0, 200);
            gradient.addColorStop(0, '#1cb76433'); // 20% alpha
            gradient.addColorStop(1, '#1cb76400'); // 0% alpha

            const mingguData = {!! $minggu_data !!};
            const chartValues = mingguData;

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'],
                    datasets: [{
                        data: chartValues,
                        borderColor: '#1cb764',
                        borderWidth: 3.5,
                        fill: true,
                        backgroundColor: gradient,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#1cb764',
                        pointBorderWidth: 2.5,
                        pointRadius: 4.5,
                        tension: 0.25
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#64748b',
                                font: {
                                    size: 10,
                                    weight: 'bold',
                                    family: "'Plus Jakarta Sans', sans-serif"
                                }
                            },
                            border: {
                                display: false
                            }
                        },
                        y: {
                            min: 0,
                            max: 100,
                            grid: {
                                color: '#e2e8f0',
                                borderDash: [5, 5],
                                drawTicks: false
                            },
                            ticks: {
                                stepSize: 25,
                                color: '#94a3b8',
                                font: {
                                    size: 10,
                                    weight: 'bold',
                                    family: "'Plus Jakarta Sans', sans-serif"
                                },
                                callback: function(value) {
                                    return value + '%';
                                }
                            },
                            border: {
                                display: false
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
