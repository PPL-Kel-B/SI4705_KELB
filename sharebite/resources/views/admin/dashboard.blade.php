@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="space-y-6">

    <!-- Top Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Admin Dashboard</h1>
            <p class="text-gray-500 font-medium mt-1">Ringkasan aktivitas ekosistem <span class="text-[#1cb764] font-semibold">ShareBite</span> hari ini.</p>
        </div>
        <div>
            <a href="{{ route('admin.laporan', request()->all()) }}" class="inline-flex items-center justify-center gap-2 bg-[#00502b] hover:bg-[#003d20] text-white px-5 py-3 rounded-2xl font-bold shadow-lg shadow-emerald-950/10 hover:shadow-emerald-950/20 hover:-translate-y-0.5 transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Generate Laporan
            </a>
        </div>
    </div>

    <!-- Filter Bar Card -->
    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
        <form method="GET" action="{{ route('admin.dashboard') }}" class="grid grid-cols-1 md:grid-cols-12 items-end gap-5">
            <!-- Pencarian Global -->
            <div class="md:col-span-4 space-y-2">
                <label class="text-[10px] font-black text-gray-400 tracking-widest uppercase">Pencarian Global</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ $filters['search'] }}" placeholder="Cari ID, Nama, atau Lokasi..." 
                        class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                </div>
            </div>

            <!-- Rentang Waktu -->
            <div class="md:col-span-3 space-y-2">
                <label class="text-[10px] font-black text-gray-400 tracking-widest uppercase">Rentang Waktu</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </span>
                    <select name="rentang_waktu" 
                        class="w-full pl-11 pr-10 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold text-gray-700 appearance-none focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                        <option value="semua_waktu" {{ $filters['rentang_waktu'] == 'semua_waktu' ? 'selected' : '' }}>Semua Waktu</option>
                        <option value="bulan_ini" {{ $filters['rentang_waktu'] == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="bulan_lalu" {{ $filters['rentang_waktu'] == 'bulan_lalu' ? 'selected' : '' }}>Bulan Lalu</option>
                        <option value="minggu_ini" {{ $filters['rentang_waktu'] == 'minggu_ini' ? 'selected' : '' }}>Minggu Ini</option>
                    </select>
                    <span class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </span>
                </div>
            </div>

            <!-- Kategori Entitas -->
            <div class="md:col-span-3 space-y-2">
                <label class="text-[10px] font-black text-gray-400 tracking-widest uppercase">Kategori Entitas</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <!-- Icon Kategori (flower-like / options) -->
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </span>
                    <select name="kategori_entitas" 
                        class="w-full pl-11 pr-10 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold text-gray-700 appearance-none focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                        <option value="semua_kategori" {{ $filters['kategori_entitas'] == 'semua_kategori' ? 'selected' : '' }}>Semua Kategori</option>
                        <option value="unit_bisnis" {{ $filters['kategori_entitas'] == 'unit_bisnis' ? 'selected' : '' }}>Unit Bisnis</option>
                        <option value="komunitas" {{ $filters['kategori_entitas'] == 'komunitas' ? 'selected' : '' }}>Komunitas</option>
                        <option value="individu" {{ $filters['kategori_entitas'] == 'individu' ? 'selected' : '' }}>Individu</option>
                    </select>
                    <span class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </span>
                </div>
            </div>

            <!-- Buttons -->
            <div class="md:col-span-2 flex items-center justify-end gap-3 pb-0.5">
                <a href="{{ route('admin.dashboard') }}" class="text-sm font-bold text-gray-400 hover:text-gray-600 px-3 py-3 transition">Reset</a>
                <button type="submit" class="bg-[#00502b] hover:bg-[#003d20] text-white px-5 py-3 rounded-2xl font-bold flex items-center justify-center gap-2 shadow-sm transition w-full md:w-auto">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    Filter Data
                </button>
            </div>
        </form>
    </div>

    <!-- Stats Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1: Total Unit Bisnis -->
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between relative overflow-hidden group hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between">
                <div class="p-3 bg-[#eefcf4] rounded-2xl text-[#1cb764]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div class="flex items-center gap-1 {{ $stats['total_unit_bisnis_growth'] >= 0 ? 'bg-[#eefcf4] text-[#1cb764]' : 'bg-red-50 text-red-600' }} px-2 py-1 rounded-full text-[10px] font-bold">
                    <span>{{ ($stats['total_unit_bisnis_growth'] >= 0 ? '+' : '') . $stats['total_unit_bisnis_growth'] }}%</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($stats['total_unit_bisnis_growth'] >= 0)
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                        @endif
                    </svg>
                </div>
            </div>
            <div class="mt-6">
                <p class="text-[10px] font-black text-gray-400 tracking-wider uppercase">Total Unit Bisnis</p>
                <h3 class="text-3xl font-black text-gray-800 mt-1">{{ $stats['total_unit_bisnis'] }}</h3>
            </div>
        </div>

        <!-- Card 2: Total Komunitas -->
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between relative overflow-hidden group hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between">
                <div class="p-3 bg-[#fdf5ee] rounded-2xl text-[#d97706]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="flex items-center gap-1 {{ $stats['total_komunitas_growth'] >= 0 ? 'bg-[#fdf5ee] text-[#d97706]' : 'bg-red-50 text-red-600' }} px-2 py-1 rounded-full text-[10px] font-bold">
                    <span>{{ ($stats['total_komunitas_growth'] >= 0 ? '+' : '') . $stats['total_komunitas_growth'] }}%</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($stats['total_komunitas_growth'] >= 0)
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                        @endif
                    </svg>
                </div>
            </div>
            <div class="mt-6">
                <p class="text-[10px] font-black text-gray-400 tracking-wider uppercase">Total Komunitas</p>
                <h3 class="text-3xl font-black text-gray-800 mt-1">{{ $stats['total_komunitas'] }}</h3>
            </div>
        </div>

        <!-- Card 3: Total Makanan (Porsi) -->
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between relative overflow-hidden group hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between">
                <div class="p-3 bg-[#eef6fc] rounded-2xl text-[#2563eb]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/>
                        <path d="M7 2v20"/>
                        <path d="M21 15V2v0a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/>
                    </svg>
                </div>
                <div class="flex items-center gap-1 {{ $stats['total_makanan_growth'] >= 0 ? 'bg-[#eef6fc] text-[#2563eb]' : 'bg-red-50 text-red-600' }} px-2 py-1 rounded-full text-[10px] font-bold">
                    <span>{{ ($stats['total_makanan_growth'] >= 0 ? '+' : '') . $stats['total_makanan_growth'] }}%</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($stats['total_makanan_growth'] >= 0)
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                        @endif
                    </svg>
                </div>
            </div>
            <div class="mt-6">
                <p class="text-[10px] font-black text-gray-400 tracking-wider uppercase">Total Makanan (Porsi)</p>
                <h3 class="text-3xl font-black text-gray-800 mt-1">{{ number_format($stats['total_makanan'], 0, ',', '.') }}</h3>
            </div>
        </div>

        <!-- Card 4: Total Transaksi -->
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between relative overflow-hidden group hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between">
                <div class="p-3 bg-[#eefcf4] rounded-2xl text-[#10b981]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="flex items-center gap-1 {{ $stats['total_transaksi_growth'] >= 0 ? 'bg-[#eefcf4] text-[#10b981]' : 'bg-red-50 text-red-600' }} px-2 py-1 rounded-full text-[10px] font-bold">
                    <span>{{ ($stats['total_transaksi_growth'] >= 0 ? '+' : '') . $stats['total_transaksi_growth'] }}%</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($stats['total_transaksi_growth'] >= 0)
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                        @endif
                    </svg>
                </div>
            </div>
            <div class="mt-6">
                <p class="text-[10px] font-black text-gray-400 tracking-wider uppercase">Total Transaksi</p>
                <h3 class="text-3xl font-black text-gray-800 mt-1">{{ number_format($stats['total_transaksi'], 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    <!-- Charts Grid Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chart 1: Aktivitas User -->
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <div class="flex items-center gap-2">
                        <h4 class="font-black text-gray-800 text-lg">Aktivitas User</h4>
                        <span class="bg-[#eefcf4] text-[#1cb764] px-2 py-0.5 rounded-full text-[9px] font-black tracking-wide uppercase">Active</span>
                    </div>
                    <p class="text-xs text-gray-400 font-medium mt-0.5">Tren pengguna harian (Jan - Jun)</p>
                </div>
            </div>
            <div class="h-64 relative">
                <canvas id="chartAktivitas"></canvas>
            </div>
        </div>

        <!-- Chart 2: Jumlah Makanan -->
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <div class="flex items-center gap-2">
                        <h4 class="font-black text-gray-800 text-lg">Jumlah Makanan</h4>
                        <span class="bg-[#fcf7ee] text-[#b97d10] px-2 py-0.5 rounded-full text-[9px] font-black tracking-wide uppercase">Growth</span>
                    </div>
                    <p class="text-xs text-gray-400 font-medium mt-0.5">Distribusi porsi (Jan - Jun)</p>
                </div>
            </div>
            <div class="h-64 relative">
                <canvas id="chartMakanan"></canvas>
            </div>
        </div>

        <!-- Chart 3: Jumlah Transaksi -->
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <div class="flex items-center gap-2">
                        <h4 class="font-black text-gray-800 text-lg">Jumlah Transaksi</h4>
                        <span class="bg-[#eeeefc] text-[#4f46e5] px-2 py-0.5 rounded-full text-[9px] font-black tracking-wide uppercase">Reports</span>
                    </div>
                    <p class="text-xs text-gray-400 font-medium mt-0.5">Volume distribusi (Jan - Jun)</p>
                </div>
            </div>
            <div class="h-64 relative">
                <canvas id="chartTransaksi"></canvas>
            </div>
        </div>
    </div>

    <!-- Table and Circular Status Row -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Recent Transactions Table (Takes 3 columns on large screens) -->
        <div class="lg:col-span-3 bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between mb-6">
                <h4 class="font-black text-gray-800 text-xl">Transaksi Terbaru</h4>
                <a href="{{ route('admin.transaksi') }}" class="text-sm font-bold text-[#1cb764] hover:text-[#128a49] flex items-center gap-1 transition">
                    Lihat Semua
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-50 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                            <th class="pb-3 pl-2">Mitra Penyalur</th>
                            <th class="pb-3">Penerima Manfaat</th>
                            <th class="pb-3">Item Makanan</th>
                            <th class="pb-3">Status</th>
                            <th class="pb-3 text-right pr-2">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-sm font-semibold text-gray-700">
                        @forelse($transactions as $t)
                        <tr class="hover:bg-gray-50/50 transition duration-150">
                            <td class="py-4 pl-2 font-bold text-gray-900">{{ $t['mitra'] }}</td>
                            <td class="py-4 text-gray-500">{{ $t['penerima'] }}</td>
                            <td class="py-4 text-gray-500">{{ $t['item'] }}</td>
                            <td class="py-4">
                                @if($t['status'] == 'SELESAI')
                                    <span class="inline-flex items-center px-3 py-1 bg-[#eefcf4] text-[#1cb764] border border-[#d2f6e2] rounded-full text-xs font-black tracking-wide uppercase">Selesai</span>
                                @elseif($t['status'] == 'PROSES')
                                    <span class="inline-flex items-center px-3 py-1 bg-[#fcf7ee] text-[#b97d10] border border-[#fbeed4] rounded-full text-xs font-black tracking-wide uppercase">Proses</span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 bg-red-50 text-red-600 border border-red-100 rounded-full text-xs font-black tracking-wide uppercase">Batal</span>
                                @endif
                            </td>
                            <td class="py-4 text-right text-gray-400 text-xs pr-2 font-medium">{{ $t['waktu'] }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-400 font-medium">Tidak ada transaksi ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Status Distribusi Card (Takes 1 column) -->
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between items-center text-center">
            <div class="w-full text-left mb-4">
                <h4 class="font-black text-gray-800 text-lg">Status Distribusi</h4>
                <p class="text-[10px] font-black text-gray-400 tracking-wider uppercase mt-0.5">Real-time Stats</p>
            </div>
            
            <!-- Circular Progress Chart -->
            <div class="relative w-44 h-44 flex items-center justify-center my-2">
                <canvas id="chartDistribution" class="absolute inset-0 w-full h-full"></canvas>
                <div class="flex flex-col items-center justify-center z-10">
                    <span class="text-3xl font-black text-gray-800">{{ $distribution['success_rate'] }}%</span>
                    <span class="text-[9px] font-black tracking-widest text-[#1cb764] uppercase mt-0.5">Success</span>
                </div>
            </div>

            <!-- Stats Breakdowns -->
            <div class="grid grid-cols-3 gap-2 w-full mt-4 pt-4 border-t border-gray-50 text-xs">
                <div>
                    <span class="text-[9px] font-bold text-gray-400 block uppercase">Done</span>
                    <span class="font-black text-gray-800 text-sm mt-0.5 block">{{ $distribution['done'] >= 1000 ? number_format($distribution['done']/1000, 1) . 'k' : $distribution['done'] }}</span>
                </div>
                <div>
                    <span class="text-[9px] font-bold text-gray-400 block uppercase">Proc</span>
                    <span class="font-black text-gray-800 text-sm mt-0.5 block">{{ $distribution['proc'] >= 1000 ? number_format($distribution['proc']/1000, 1) . 'k' : $distribution['proc'] }}</span>
                </div>
                <div>
                    <span class="text-[9px] font-bold text-gray-400 block uppercase">Fail</span>
                    <span class="font-black text-gray-800 text-sm mt-0.5 block font-mono">{{ $distribution['fail'] >= 1000 ? number_format($distribution['fail']/1000, 1) . 'k' : $distribution['fail'] }}</span>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Chart Scripts -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Data passed from controller
        const dataAktivitas = {!! $charts['aktivitas_user'] !!};
        const dataMakanan = {!! $charts['jumlah_makanan'] !!};
        const dataTransaksi = {!! $charts['jumlah_transaksi'] !!};

        const months = ['JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUN'];

        // Helper function for building line charts
        function createLineChart(ctxId, label, data, color, fillGrad) {
            const ctx = document.getElementById(ctxId).getContext('2d');
            
            // Create gradient
            const gradient = ctx.createLinearGradient(0, 0, 0, 240);
            gradient.addColorStop(0, fillGrad + '33'); // Alpha 20%
            gradient.addColorStop(1, fillGrad + '00'); // Alpha 0%

            return new Chart(ctx, {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [{
                        label: label,
                        data: data,
                        borderColor: color,
                        borderWidth: 4,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: color,
                        pointBorderWidth: 3,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        tension: 0.45,
                        fill: true,
                        backgroundColor: gradient,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y >= 1000 ? (context.parsed.y / 1000).toFixed(1) + 'k' : context.parsed.y;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#94a3b8',
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
                            display: false, // Hides Y-axis lines & labels like the image
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // Initialize Line Charts
        createLineChart('chartAktivitas', 'Aktivitas User', dataAktivitas, '#1cb764', '#1cb764');
        createLineChart('chartMakanan', 'Jumlah Makanan', dataMakanan, '#8b5a2b', '#8b5a2b');
        createLineChart('chartTransaksi', 'Jumlah Transaksi', dataTransaksi, '#312e81', '#4f46e5');

        // Circular Progress Doughnut Chart
        const distCtx = document.getElementById('chartDistribution').getContext('2d');
        new Chart(distCtx, {
            type: 'doughnut',
            data: {
                labels: ['Success', 'Processing', 'Failed'],
                datasets: [{
                    data: [{{ $distribution['done'] }}, {{ $distribution['proc'] }}, {{ $distribution['fail'] }}],
                    backgroundColor: [
                        '#1cb764', // Green
                        '#b97d10', // Orange
                        '#ef4444'  // Red
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '80%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: true
                    }
                }
            }
        });
    });
</script>
@endsection
