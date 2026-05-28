@extends('layouts.unit_bisnis')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">

    <!-- Hero Banner -->
    <div class="relative bg-[#0a2e1f] rounded-3xl p-8 lg:p-12 text-white overflow-hidden shadow-sm" style="min-height: 200px;">
        <!-- Background Image dengan tag <img> supaya pasti tampil -->
        <img 
            src="https://i0.wp.com/www.emporioarchitect.com/upload/portofolio/desain-restoran-modern-1-lantai-89070123-461258133231223095257-4.jpg" 
            alt="" 
            class="absolute inset-0 w-full h-full object-cover opacity-40 pointer-events-none"
            style="z-index: 0;"
        >
        <!-- Gradient Overlay -->
        <div class="absolute inset-0" style="z-index: 1; background: linear-gradient(to right, #0a2e1f 40%, rgba(10,46,31,0.7) 70%, transparent 100%);"></div>
        
        <div class="relative max-w-2xl" style="z-index: 2;">
            <div class="inline-flex items-center bg-[#1cb764] px-3 py-1.5 rounded-full text-xs font-bold tracking-wide uppercase mb-6 shadow-sm">
                Unit Bisnis Aktif
            </div>
            <h1 class="text-4xl lg:text-5xl font-extrabold mb-4">
                Halo, {{ Auth::check() ? Auth::user()->name : 'Lestari Food' }}!
            </h1>
            <p class="text-lg font-medium text-white/80 max-w-lg">
                Selamat datang kembali. Mari kurangi limbah makanan dan bagikan kebaikan hari ini.
            </p>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1 -->
        <div class="bg-white p-5 rounded-3xl shadow-sm border-b-4 border-[#1cb764]">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-xl bg-[#eefcf4] text-[#1cb764] flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                </div>
                <span class="bg-[#eefcf4] text-[#1cb764] text-xs font-bold px-2 py-1 rounded-md">+12%</span>
            </div>
            <p class="text-2xl font-extrabold text-gray-800">{{ number_format($totalPesanan) }}</p>
        </div>

        <!-- Card 2 -->
        <div class="bg-white p-5 rounded-3xl shadow-sm border-b-4 border-[#f7b055]">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-xl bg-[#fcf3e8] text-[#f7b055] flex items-center justify-center">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                </div>
                <span class="bg-[#fcf3e8] text-[#f7b055] text-xs font-bold px-2 py-1 rounded-md">Teratas</span>
            </div>
            <p class="text-2xl font-extrabold text-gray-800">{{ number_format($ratingResto, 1) }}<span class="text-sm text-gray-400">/5.0</span></p>
        </div>

        <!-- Card 3 -->
        <div class="bg-white p-5 rounded-3xl shadow-sm border-b-4 border-[#1cb764]">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-xl bg-[#eefcf4] text-[#1cb764] flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <span class="bg-[#eefcf4] text-[#1cb764] text-xs font-bold px-2 py-1 rounded-md">Bulan Ini</span>
            </div>
            <p class="text-2xl font-extrabold text-gray-800">Rp {{ $totalPendapatan >= 1000000 ? number_format($totalPendapatan / 1000000, 1) . 'M' : number_format($totalPendapatan / 1000, 0) . 'K' }}</p>
        </div>

        <!-- Card 4 -->
        <div class="bg-white p-5 rounded-3xl shadow-sm border-b-4 border-[#1cb764]">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-xl bg-[#eefcf4] text-[#1cb764] flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
                <span class="bg-[#eefcf4] text-[#1cb764] text-xs font-bold px-2 py-1 rounded-md">Stabil</span>
            </div>
            <p class="text-2xl font-extrabold text-gray-800">{{ $salesGrowth }}%</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Graph Section (Full width) -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded-3xl p-6 shadow-sm">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-xl font-extrabold text-gray-800">Jumlah Penjualan Per Bulan</h2>
                        <p class="text-xs text-gray-500 mt-1">Volume porsi makanan yang berhasil diredistribusikan</p>
                    </div>
                    <div class="flex gap-2">
                        <button class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-bold">2023</button>
                        <button class="px-3 py-1 bg-[#0a2e1f] text-white rounded-full text-xs font-bold">2024</button>
                    </div>
                </div>

                @php
                    $salesData = $salesData ?? [];
                    
                    // Pastikan semua bulan ada
                    $defaultBulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
                    foreach ($defaultBulan as $b) {
                        if (!isset($salesData[$b])) $salesData[$b] = 0;
                    }

                    $maxValue  = max($salesData) > 0 ? max($salesData) : 1; // ← hindari pembagian 0
                    $maxHeight = 180;
                    $peakMonth = array_search(max($salesData), $salesData);
                @endphp
                <div class="flex items-end justify-between px-2 gap-2 sm:gap-4" style="height: 220px;">
                    @foreach($salesData as $bulan => $nilai)
                        @php
                            $barHeight  = $maxValue > 0 ? round($nilai / $maxValue * $maxHeight) : 0;
                            $isPeak     = ($bulan === $peakMonth) && $nilai > 0; // ← tidak highlight jika 0
                            $barColor   = $isPeak ? '#1cb764' : '#dde9e4';
                            $labelColor = $isPeak ? 'font-bold text-[#1cb764]' : 'text-gray-500 font-semibold';
                        @endphp
                        <div class="flex-1 flex flex-col items-center justify-end h-full">
                            @if($nilai > 0)
                                <span class="text-[10px] font-bold text-gray-800 mb-1">{{ number_format($nilai) }}</span>
                            @else
                                <span class="text-[10px] text-gray-300 mb-1">-</span>
                            @endif
                            <div
                                class="w-full rounded-t-lg hover:opacity-80 transition-opacity {{ $isPeak ? 'shadow-md' : '' }}"
                                style="height: {{ max($barHeight, 4) }}px; background-color: {{ $nilai > 0 ? $barColor : '#f0f4f2' }};"
                            ></div>
                            <span class="text-[10px] mt-2 {{ $labelColor }}">{{ $bulan }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Left: Pesanan Masuk (2/3 width) -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl p-6 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-extrabold text-gray-800">Pesanan Masuk</h2>
                    <a href="{{ route('unit.pesanan') }}" class="text-[#1cb764] text-xs font-bold hover:underline flex items-center gap-1">
                        Lihat Semua 
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </div>
                <div class="space-y-4">
                    @forelse($pesananMasuk as $pesanan)
                        @php
                            $namaMakanan = $pesanan->menuAktif?->masterMakanan?->nama ?? 'Menu tidak tersedia';
                            $foto        = $pesanan->menuAktif?->masterMakanan?->foto;
                            $fotoUrl     = $foto ? asset('storage/' . $foto) : null;
                            $porsi       = $pesanan->jumlah_porsi;
                            $waktu       = $pesanan->created_at->diffForHumans();

                            $statusConfig = match($pesanan->status) {
                                'siap_diambil' => ['label' => 'Menunggu Diambil', 'bg' => 'bg-[#fcf3e8]', 'text' => 'text-[#f7b055]'],
                                'selesai'      => ['label' => 'Sudah Diambil',    'bg' => 'bg-[#eefcf4]', 'text' => 'text-[#1cb764]'],
                                'dibayar'      => ['label' => 'Dibayar',          'bg' => 'bg-[#eefcf4]', 'text' => 'text-[#1cb764]'],
                                'dibatalkan'   => ['label' => 'Dibatalkan',       'bg' => 'bg-red-50',    'text' => 'text-red-500'],
                                default        => ['label' => ucfirst($pesanan->status), 'bg' => 'bg-gray-100', 'text' => 'text-gray-500'],
                            };
                        @endphp

                        <div class="flex items-center justify-between p-3 bg-[#F4F8F6] rounded-2xl border border-gray-50">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 bg-gray-200 rounded-xl shrink-0 overflow-hidden">
                                    @if($fotoUrl)
                                        <img src="{{ $fotoUrl }}" alt="{{ $namaMakanan }}" class="w-full h-full object-cover"
                                            onerror="this.style.display='none'; this.parentElement.style.background='#d1fae5';">
                                    @else
                                        <div class="w-full h-full bg-[#d1fae5] flex items-center justify-center">
                                            <svg class="w-6 h-6 text-[#1cb764]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="font-bold text-sm text-gray-800">{{ $namaMakanan }}</h3>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $porsi }} Porsi &bull; Dipesan {{ $waktu }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="{{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} text-[10px] font-bold px-3 py-1.5 rounded-full uppercase tracking-wider whitespace-nowrap">
                                    {{ $statusConfig['label'] }}
                                </span>
                                <a href="{{ route('unit.pesanan') }}" class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        {{-- Tampilkan jika belum ada pesanan --}}
                        <div class="flex flex-col items-center justify-center py-10 text-center">
                            <div class="w-14 h-14 rounded-full bg-[#eefcf4] flex items-center justify-center mb-3">
                                <svg class="w-7 h-7 text-[#1cb764]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <p class="text-sm font-semibold text-gray-500">Belum ada pesanan masuk</p>
                            <p class="text-xs text-gray-400 mt-1">Pesanan baru akan muncul di sini</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right: Kelola Menu Aktif (1/3 width) -->
        <div>
            <div class="bg-white rounded-3xl p-6 shadow-sm h-full flex flex-col">
                <h2 class="text-xl font-extrabold text-gray-800 mb-6">Kelola Menu Aktif</h2>
                
                <div class="space-y-4 flex-1">
                    <!-- Status 1: Tersedia — count dari controller -->
                    <div class="bg-[#F4F8F6] border border-gray-100 rounded-2xl p-4">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-[#1cb764]"></div>
                                <h3 class="font-bold text-sm text-[#0a2e1f] uppercase tracking-wide">Tersedia</h3>
                            </div>
                            <span class="text-xs font-medium text-gray-500">
                                <strong class="text-gray-800 text-sm">{{ $menuAktifCount ?? 0 }}</strong> Menu
                            </span>
                        </div>
                        <p class="text-[11px] text-gray-500 leading-relaxed">
                            Menu dalam kondisi stok aman. Pastikan label kedaluwarsa tetap terupdate.
                        </p>
                    </div>

                    <!-- Status 2: Segera Habis -->
                    <div class="bg-[#fcf3e8] border border-orange-100 rounded-2xl p-4">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-[#f7b055]"></div>
                                <h3 class="font-bold text-sm text-[#9a5b15] uppercase tracking-wide">Segera Habis</h3>
                            </div>
                            <span class="text-xs font-medium text-orange-600">
                                <strong class="text-orange-700 text-sm">{{ $menuHabisCount ?? 0 }}</strong> Menu
                            </span>
                        </div>
                        <p class="text-[11px] text-orange-600/80 leading-relaxed">
                            Persediaan menipis. Segera hapus atau update menu jika stok di dapur telah kosong.
                        </p>
                    </div>
                </div>

                {{-- Tombol → link ke halaman Kelola Makanan --}}
                <a 
                    href="{{ route('unit.kelola_makanan') }}"
                    class="w-full mt-6 bg-[#0a2e1f] text-white font-bold text-sm py-3.5 rounded-xl hover:bg-[#062015] transition shadow-md flex items-center justify-center gap-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Tambah Menu Baru
                </a>
            </div>
        </div>
</div>
@endsection