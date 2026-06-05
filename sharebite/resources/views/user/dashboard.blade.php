@php
    $hideSearch = false; // Tampilkan search bar di header
@endphp
@extends('layouts.user')

@section('title', 'Dashboard')

@section('content')
<!-- Leaflet Map CSS/JS for Radius Anda -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<style>
    .custom-leaflet-marker { background: transparent; border: none; }
</style>

<div class="space-y-8 animate-fade-in pb-12">
    <!-- 1. Banner Sambutan -->
    <div class="relative overflow-hidden bg-gradient-to-r from-[#0b472e] via-[#093a23] to-[#1cb764] rounded-[2rem] text-white p-8 sm:p-12 shadow-md">
        <!-- Glowing abstract light blob -->
        <div class="absolute -right-10 -top-10 w-44 h-44 bg-green-400/10 rounded-full blur-2xl"></div>
        
        <!-- Subtle fresh produce overlay on the right (matches mockup) -->
        <div class="absolute right-0 top-0 bottom-0 w-1/2 hidden md:block select-none pointer-events-none" style="z-index: 1;">
            <img src="https://images.unsplash.com/photo-1576045057995-568f588f82fb?w=800&q=80" alt="" class="w-full h-full object-cover opacity-25 mix-blend-overlay">
            <!-- Fade overlay to blend left -->
            <div class="absolute inset-0 bg-gradient-to-r from-[#0b472e] via-[#0b472e]/40 to-transparent"></div>
        </div>
        
        <div class="relative z-10 max-w-xl">
            <span class="inline-flex bg-white/10 border border-white/20 text-white text-[10px] font-bold uppercase tracking-wider px-3.5 py-1.5 rounded-full mb-6">
                DASHBOARD RELAWAN
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight mb-4">
                Halo, {{ explode(' ', Auth::user()->name)[0] }}!
            </h1>
            <p class="text-white/80 text-sm sm:text-base font-medium leading-relaxed">
                Siap menyelamatkan makanan dan berbagi kebaikan hari ini?
            </p>
        </div>
    </div>

    <!-- 2. Baris Statistik (Stats Cards) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Porsi Diambil -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-50 flex items-center justify-between hover:shadow-md transition duration-300">
            <div class="space-y-1">
                <p class="text-xs text-gray-500 font-bold tracking-wide">Porsi Diambil</p>
                <p class="text-4xl font-black text-[#9a5b15]">{{ $porsi_diambil }}</p>
            </div>
            <div class="w-14 h-14 bg-[#fdf4e9] rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-[#e09121]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <!-- Fork -->
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 3v8a3 3 0 003 3h1m-1 0v7m-3-18v4m3-4v4m3-4v4" />
                    <!-- Knife -->
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 3v18M18 3c-2 0-3 3-3 8h3" />
                </svg>
            </div>
        </div>

        <!-- Makanan Terselamatkan -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-50 flex items-center justify-between hover:shadow-md transition duration-300">
            <div class="space-y-1">
                <p class="text-xs text-gray-500 font-bold tracking-wide">Makanan Terselamatkan</p>
                <p class="text-4xl font-black text-[#0b472e]">{{ number_format($makanan_terselamatkan, 1, ',', '.') }} <span class="text-lg font-bold text-gray-400">kg</span></p>
            </div>
            <div class="w-14 h-14 bg-[#eefcf4] rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-[#1cb764]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 3.58.1 8.2a7 7 0 0 1-8.1 9.8z" />
                    <path d="M19 2c-2.26 4.33-5.27 7.14-8 10" />
                </svg>
            </div>
        </div>

        <!-- CO2 Dihemat -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-50 flex items-center justify-between hover:shadow-md transition duration-300">
            <div class="space-y-1">
                <p class="text-xs text-gray-500 font-bold tracking-wide">CO2 Dihemat</p>
                <p class="text-4xl font-black text-[#1e3a8a]">{{ number_format($co2_dihemat, 1, ',', '.') }} <span class="text-lg font-bold text-gray-400">kg</span></p>
            </div>
            <div class="w-14 h-14 bg-[#f0f4ff] rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-[#3b82f6]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                </svg>
            </div>
        </div>
    </div>

    @php
        $chunk1 = $limited_nearby_donations->take(3);
        $chunk2 = $limited_nearby_donations->slice(3);
        $hasSecondRow = $limited_nearby_donations->count() > 3;
    @endphp

    @if(!$hasSecondRow)
        <!-- Standard Layout (0-3 cards) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-stretch animate-fade-in">
            <!-- Donasi Terdekat -->
            <div class="lg:col-span-2 flex flex-col">
                <div class="border-b border-gray-100 pb-2 mb-6">
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">Donasi Terdekat</h2>
                    <div class="flex items-center justify-between mt-1">
                        <p class="text-xs text-gray-400 font-bold">Tersedia untuk segera didistribusikan</p>
                        <a href="{{ route('user.nearby') }}" class="inline-flex items-center gap-1.5 text-sm font-extrabold text-[#1cb764] hover:text-[#148f4c] transition group">
                            Lihat Semua 
                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Grid Kartu Donasi -->
                @if(is_null(Auth::user()->latitude) || is_null(Auth::user()->longitude))
                    <div class="bg-amber-50 border border-amber-200 rounded-[2rem] p-10 text-center space-y-4 shadow-sm flex-1 flex flex-col justify-center items-center">
                        <div class="w-14 h-14 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-2">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25s-7.5-4.108-7.5-11.25a7.5 7.5 0 1115 0z" />
                            </svg>
                        </div>
                        <p class="text-amber-800 font-bold text-sm max-w-md mx-auto leading-relaxed mb-2">
                            Tidak bisa menampilkan lokasi terdekat, harap tentukan lokasi terlebih dahulu
                        </p>
                        <a href="{{ route('user.profile.edit') }}" class="inline-block bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold px-6 py-2.5 rounded-full transition shadow-sm">
                            Atur Lokasi
                        </a>
                    </div>
                @elseif($limited_nearby_donations->isEmpty())
                    <div class="bg-white rounded-[2rem] p-10 text-center border border-gray-100 shadow-sm flex-1 flex flex-col justify-center items-center">
                        <p class="text-gray-400 font-bold text-sm">Tidak ada donasi aktif di dekat Anda saat ini.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 flex-1">
                        @foreach($chunk1 as $menu)
                            @php
                                $batas = \Carbon\Carbon::parse($menu->batas_pengambilan);
                                $segera_habis = $batas->diffInMinutes(now(), false) > -60;
                                
                                $diffInMins = now()->diffInMinutes($batas, false);
                                if ($diffInMins > 0) {
                                    if ($diffInMins < 60) {
                                        $timeStr = $diffInMins . ' mnt lagi';
                                    } else {
                                        $timeStr = round($diffInMins / 60) . ' jam lagi';
                                    }
                                } else {
                                    $timeStr = 'Habis';
                                }
                            @endphp
                            <div class="relative bg-white border border-gray-100 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.02)] hover:shadow-[0_20px_40px_rgba(0,0,0,0.06)] hover:-translate-y-1.5 transition-all duration-300 flex flex-col overflow-hidden h-[275px] group">
                                <!-- Gambar Makanan & Overlay -->
                                <div class="relative h-[105px] w-full overflow-hidden bg-gray-50 shrink-0">
                                    <img src="{{ $menu->masterMakanan->foto ? asset('storage/' . $menu->masterMakanan->foto) : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=500&q=80' }}" 
                                         alt="{{ $menu->masterMakanan->nama_makanan }}" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">
                                    
                                    <!-- Status Tag -->
                                    @if($segera_habis)
                                        <span class="absolute top-3 left-3 bg-[#9a5b15] text-white text-[9px] font-black uppercase tracking-wider px-3 py-1.5 rounded-full shadow-sm">
                                            SEGERA HABIS
                                        </span>
                                    @else
                                        <span class="absolute top-3 left-3 bg-[#1cb764] text-white text-[9px] font-black uppercase tracking-wider px-3 py-1.5 rounded-full shadow-sm">
                                            TERSEDIA
                                        </span>
                                    @endif
     
                                    <!-- Jarak Tag -->
                                    <span class="absolute bottom-3 right-3 bg-white text-gray-800 text-[10px] font-bold px-3 py-1.5 rounded-full shadow-md flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-[#e09121]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25s-7.5-4.108-7.5-11.25a7.5 7.5 0 1115 0z" />
                                        </svg>
                                        {{ number_format($menu->computed_distance, 1, ',', '.') }} km
                                    </span>
                                </div>
     
                                <!-- Informasi Makanan -->
                                <div class="px-5 pb-3.5 pt-2.5 flex-1 flex flex-col justify-between">
                                    <div class="space-y-1">
                                        <!-- Kategori & Harga -->
                                        <div class="flex items-center justify-between gap-1.5 mb-1">
                                            <span class="inline-block bg-[#eefcf4] text-[#1cb764] text-[9px] font-black uppercase tracking-wider px-2.5 py-1 rounded-md border border-[#d2f4e1]">
                                                {{ $menu->masterMakanan->kategori ?? 'Umum' }}
                                            </span>
                                            <span class="text-[10px] font-black {{ $menu->is_gratis ? 'text-[#1cb764] bg-[#eefcf4] px-2 py-0.5 rounded-md border border-[#d2f4e1]' : 'text-gray-900 bg-gray-50 px-2 py-0.5 rounded-md border border-gray-100' }}">
                                                {{ $menu->is_gratis ? 'Gratis' : 'Rp ' . number_format($menu->harga_jual, 0, ',', '.') }}
                                            </span>
                                        </div>
                                        
                                        <h3 class="font-extrabold text-gray-900 text-sm leading-snug line-clamp-1 group-hover:text-[#1cb764] transition-colors">
                                            {{ $menu->masterMakanan->nama_makanan }}
                                        </h3>
                                        
                                        <p class="text-[10px] text-gray-400 font-semibold flex items-center gap-1.5 truncate">
                                            <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                                <path d="M3 9h18M3 9v12a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1V9M3 9L5 3h14l2 6M9 9v4M15 9v4" />
                                            </svg>
                                            {{ $menu->unitBisnis->nama_usaha ?? 'Mitra ShareBite' }}
                                        </p>
                                    </div>
     
                                    <!-- Garis Pembatas -->
                                    <hr class="border-t border-gray-100 my-2">
     
                                    <!-- Baris Bawah (Detail & Aksi) -->
                                    <div class="flex items-center justify-between mt-auto">
                                        <div class="flex items-center gap-2">
                                            <!-- Limit Waktu -->
                                            <div class="inline-flex items-center gap-1 bg-[#fdf4e9] text-[#9a5b15] text-[10px] font-extrabold px-2.5 py-1.5 rounded-full">
                                                <svg class="w-3.5 h-3.5 text-[#e09121]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span>{{ $timeStr }}</span>
                                            </div>
                                            <!-- Porsi -->
                                            <div class="flex items-center gap-1 text-[11px] text-gray-500 font-extrabold">
                                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                                </svg>
                                                <span>{{ $menu->stok_porsi }} Porsi</span>
                                            </div>
                                        </div>
                                        <a href="{{ route('user.makanan.pembayaran', $menu->id) }}" 
                                           class="bg-gradient-to-r from-[#0b472e] to-[#1cb764] hover:from-[#093522] hover:to-[#159a54] text-white text-xs font-bold px-4 py-2.5 rounded-2xl transition shadow-sm transform active:scale-95 cursor-pointer">
                                            Ambil
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Kolom Kanan (Aktivitas Terakhir & Radius) stacked -->
            <div class="flex flex-col justify-between">
                <!-- Aktivitas Terakhir -->
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-50 flex-1 flex flex-col justify-between mb-6">
                    <div class="space-y-6 p-1">
                        <div class="flex items-start gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-[#eefcf4] text-[#1cb764] flex items-center justify-center shrink-0 shadow-sm border border-green-50">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-base font-black text-gray-800">Aktivitas Terakhir</h2>
                                <p class="text-[10px] text-gray-400 font-medium">Aktivitas utama yang telah dilakukan</p>
                            </div>
                        </div>

                        @if($recent_activities->isEmpty())
                            <div class="text-center py-6 text-gray-400 text-xs font-bold">
                                Belum ada aktivitas tercatat.
                            </div>
                        @else
                            <!-- Timeline -->
                            <div class="space-y-6 relative before:absolute before:left-4 before:top-2 before:bottom-2 before:w-[2px] before:bg-gray-100">
                                @foreach($recent_activities as $act)
                                    @php
                                        $bgColor = 'bg-[#f0f4ff] text-[#4f46e5]';
                                        if($act->tipe === 'profil') $bgColor = 'bg-[#fcf3e6] text-[#e09121]';
                                        if($act->tipe === 'pengaturan') $bgColor = 'bg-[#f2fcf6] text-[#1cb764]';
                                        if($act->tipe === 'ulasan') $bgColor = 'bg-pink-50 text-pink-500';
                                    @endphp
                                    <div class="flex gap-4 relative">
                                        <div class="w-8 h-8 rounded-full shrink-0 flex items-center justify-center z-10 {{ $bgColor }} border-2 border-white shadow-sm">
                                            @if($act->tipe === 'pemesanan')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                                </svg>
                                            @elseif($act->tipe === 'ulasan')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.907c.961 0 1.357 1.23.582 1.796l-3.97 2.883a1 1 0 00-.364 1.118l1.52 4.674c.3.922-.755 1.688-1.538 1.118l-3.971-2.883a1 1 0 00-1.175 0l-3.97 2.883c-.783.57-1.838-.197-1.539-1.118l1.52-4.674a1 1 0 00-.364-1.118L2.25 10.087c-.775-.566-.379-1.796.582-1.796h4.906a1 1 0 00.95-.69l1.519-4.674z" />
                                                </svg>
                                            @elseif($act->tipe === 'profil')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            @elseif($act->tipe === 'pengaturan')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                </svg>
                                            @else
                                                ●
                                            @endif
                                        </div>
                                        <div class="space-y-1">
                                            <h4 class="text-xs font-extrabold text-gray-800">{{ $act->judul }}</h4>
                                            <p class="text-[11px] text-gray-500 leading-relaxed">{{ $act->deskripsi }}</p>
                                            <span class="inline-block text-[9px] text-gray-400 font-bold tracking-wider uppercase">
                                                {{ $act->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <a href="{{ route('user.activities') }}" class="w-full bg-[#f4f7f5] hover:bg-gray-200 text-gray-700 text-xs font-extrabold py-3 text-center rounded-2xl block transition shadow-sm mt-4">
                        Lihat Semua Aktivitas
                    </a>
                </div>

                <!-- Radius Anda -->
                @if(is_null(Auth::user()->latitude) || is_null(Auth::user()->longitude))
                    <div class="bg-amber-50 border border-amber-200 rounded-3xl p-6 text-gray-800 space-y-4">
                        <span class="inline-block bg-amber-500 text-white text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full">
                            LOKASI BELUM SET
                        </span>
                        <h3 class="text-base font-extrabold text-amber-800 leading-tight">
                            Titik Lokasi Belum Ditentukan
                        </h3>
                        <p class="text-xs text-amber-700 leading-relaxed font-medium">
                            Harap tentukan koordinat lokasi Anda terlebih dahulu agar sistem dapat mendeteksi donatur di sekitar Anda.
                        </p>
                        <a href="{{ route('user.profile.edit') }}" class="inline-block w-full bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold py-3 text-center rounded-2xl transition shadow-sm">
                            Atur Lokasi Sekarang
                        </a>
                    </div>
                @else
                    <div class="relative overflow-hidden rounded-[2rem] text-white shadow-lg shadow-green-900/10 flex-1 flex flex-col justify-between border border-gray-100 bg-white min-h-[160px] h-[340px]">
                        <div id="map-radius" class="absolute inset-0 z-0"></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0b472e] via-[#0b472e]/60 to-[#0b472e]/15 z-10 pointer-events-none"></div>
                        <div class="relative z-20 p-6 flex flex-col justify-between h-full min-h-[160px] pointer-events-none">
                            <span class="inline-block bg-[#1cb764] text-white text-[9px] font-black uppercase tracking-wider px-2.5 py-1 rounded-full self-start pointer-events-auto">
                                RADIUS ANDA
                            </span>
                            <div class="space-y-1 mt-auto">
                                <h3 class="text-base font-black leading-tight text-white drop-shadow-md">
                                    {{ Auth::user()->alamat ?? 'Bandung, Indonesia' }}
                                </h3>
                                <p class="text-[10px] text-green-300 font-bold flex items-center gap-1 drop-shadow-sm">
                                    <span class="w-2 h-2 rounded-full bg-green-400 inline-block animate-ping"></span>
                                    {{ $active_donors_count }} Donatur Aktif Sekitar
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @else
        <!-- Two-row Layout (4-6 cards) -->
        <!-- Row 1: Title & Cards 1-3 on Left, Aktivitas Terakhir on Right -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-stretch animate-fade-in">
            <div class="lg:col-span-2 flex flex-col">
                <div class="border-b border-gray-100 pb-2 mb-6">
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">Donasi Terdekat</h2>
                    <div class="flex items-center justify-between mt-1">
                        <p class="text-xs text-gray-400 font-bold">Tersedia untuk segera didistribusikan</p>
                        <a href="{{ route('user.nearby') }}" class="inline-flex items-center gap-1.5 text-sm font-extrabold text-[#1cb764] hover:text-[#148f4c] transition group">
                            Lihat Semua 
                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 flex-1">
                    @foreach($chunk1 as $menu)
                        @php
                            $batas = \Carbon\Carbon::parse($menu->batas_pengambilan);
                            $segera_habis = $batas->diffInMinutes(now(), false) > -60;
                            
                            $diffInMins = now()->diffInMinutes($batas, false);
                            if ($diffInMins > 0) {
                                if ($diffInMins < 60) {
                                    $timeStr = $diffInMins . ' mnt lagi';
                                } else {
                                    $timeStr = round($diffInMins / 60) . ' jam lagi';
                                }
                            } else {
                                $timeStr = 'Habis';
                            }
                        @endphp
                        <div class="relative bg-white border border-gray-100 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.02)] hover:shadow-[0_20px_40px_rgba(0,0,0,0.06)] hover:-translate-y-1.5 transition-all duration-300 flex flex-col overflow-hidden h-[275px] group">
                            <!-- Gambar Makanan & Overlay -->
                            <div class="relative h-[105px] w-full overflow-hidden bg-gray-50 shrink-0">
                                <img src="{{ $menu->masterMakanan->foto ? asset('storage/' . $menu->masterMakanan->foto) : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=500&q=80' }}" 
                                     alt="{{ $menu->masterMakanan->nama_makanan }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">
                                
                                <!-- Status Tag -->
                                @if($segera_habis)
                                    <span class="absolute top-3 left-3 bg-[#9a5b15] text-white text-[9px] font-black uppercase tracking-wider px-3 py-1.5 rounded-full shadow-sm">
                                        SEGERA HABIS
                                    </span>
                                @else
                                    <span class="absolute top-3 left-3 bg-[#1cb764] text-white text-[9px] font-black uppercase tracking-wider px-3 py-1.5 rounded-full shadow-sm">
                                        TERSEDIA
                                    </span>
                                @endif

                                <!-- Jarak Tag -->
                                <span class="absolute bottom-3 right-3 bg-white text-gray-800 text-[10px] font-bold px-3 py-1.5 rounded-full shadow-md flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-[#e09121]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25s-7.5-4.108-7.5-11.25a7.5 7.5 0 1115 0z" />
                                    </svg>
                                    {{ number_format($menu->computed_distance, 1, ',', '.') }} km
                                </span>
                            </div>

                            <!-- Informasi Makanan -->
                            <div class="px-5 pb-3.5 pt-2.5 flex-1 flex flex-col justify-between">
                                <div class="space-y-1">
                                    <!-- Kategori & Harga -->
                                    <div class="flex items-center justify-between gap-1.5 mb-1">
                                        <span class="inline-block bg-[#eefcf4] text-[#1cb764] text-[9px] font-black uppercase tracking-wider px-2.5 py-1 rounded-md border border-[#d2f4e1]">
                                            {{ $menu->masterMakanan->kategori ?? 'Umum' }}
                                        </span>
                                        <span class="text-[10px] font-black {{ $menu->is_gratis ? 'text-[#1cb764] bg-[#eefcf4] px-2 py-0.5 rounded-md border border-[#d2f4e1]' : 'text-gray-900 bg-gray-50 px-2 py-0.5 rounded-md border border-gray-100' }}">
                                            {{ $menu->is_gratis ? 'Gratis' : 'Rp ' . number_format($menu->harga_jual, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    
                                    <h3 class="font-extrabold text-gray-900 text-sm leading-snug line-clamp-1 group-hover:text-[#1cb764] transition-colors">
                                        {{ $menu->masterMakanan->nama_makanan }}
                                    </h3>
                                    
                                    <p class="text-[10px] text-gray-400 font-semibold flex items-center gap-1.5 truncate">
                                        <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                            <path d="M3 9h18M3 9v12a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1V9M3 9L5 3h14l2 6M9 9v4M15 9v4" />
                                        </svg>
                                        {{ $menu->unitBisnis->nama_usaha ?? 'Mitra ShareBite' }}
                                    </p>
                                </div>

                                <!-- Garis Pembatas -->
                                <hr class="border-t border-gray-100 my-2">

                                <!-- Baris Bawah (Detail & Aksi) -->
                                <div class="flex items-center justify-between mt-auto">
                                    <div class="flex items-center gap-2">
                                        <!-- Limit Waktu -->
                                        <div class="inline-flex items-center gap-1 bg-[#fdf4e9] text-[#9a5b15] text-[10px] font-extrabold px-2.5 py-1.5 rounded-full">
                                            <svg class="w-3.5 h-3.5 text-[#e09121]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span>{{ $timeStr }}</span>
                                        </div>
                                        <!-- Porsi -->
                                        <div class="flex items-center gap-1 text-[11px] text-gray-500 font-extrabold">
                                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                            </svg>
                                            <span>{{ $menu->stok_porsi }} Porsi</span>
                                        </div>
                                    </div>
                                    <a href="{{ route('user.makanan.pembayaran', $menu->id) }}" 
                                       class="bg-gradient-to-r from-[#0b472e] to-[#1cb764] hover:from-[#093522] hover:to-[#159a54] text-white text-xs font-bold px-4 py-2.5 rounded-2xl transition shadow-sm transform active:scale-95 cursor-pointer">
                                        Ambil
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <!-- Right Column: Aktivitas Terakhir -->
            <div class="flex flex-col">
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-50 flex-1 flex flex-col justify-between">
                    <div class="space-y-6 p-1">
                        <div class="flex items-start gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-[#eefcf4] text-[#1cb764] flex items-center justify-center shrink-0 shadow-sm border border-green-50">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-base font-black text-gray-800">Aktivitas Terakhir</h2>
                                <p class="text-[10px] text-gray-400 font-medium">Aktivitas utama yang telah dilakukan</p>
                            </div>
                        </div>

                        @if($recent_activities->isEmpty())
                            <div class="text-center py-6 text-gray-400 text-xs font-bold">
                                Belum ada aktivitas tercatat.
                            </div>
                        @else
                            <div class="space-y-6 relative before:absolute before:left-4 before:top-2 before:bottom-2 before:w-[2px] before:bg-gray-100">
                                @foreach($recent_activities as $act)
                                    @php
                                        $bgColor = 'bg-[#f0f4ff] text-[#4f46e5]';
                                        if($act->tipe === 'profil') $bgColor = 'bg-[#fcf3e6] text-[#e09121]';
                                        if($act->tipe === 'pengaturan') $bgColor = 'bg-[#f2fcf6] text-[#1cb764]';
                                        if($act->tipe === 'ulasan') $bgColor = 'bg-pink-50 text-pink-500';
                                    @endphp
                                    <div class="flex gap-4 relative">
                                        <div class="w-8 h-8 rounded-full shrink-0 flex items-center justify-center z-10 {{ $bgColor }} border-2 border-white shadow-sm">
                                            @if($act->tipe === 'pemesanan')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                                </svg>
                                            @elseif($act->tipe === 'ulasan')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.907c.961 0 1.357 1.23.582 1.796l-3.97 2.883a1 1 0 00-.364 1.118l1.52 4.674c.3.922-.755 1.688-1.538 1.118l-3.971-2.883a1 1 0 00-1.175 0l-3.97 2.883c-.783.57-1.838-.197-1.539-1.118l1.52-4.674a1 1 0 00-.364-1.118L2.25 10.087c-.775-.566-.379-1.796.582-1.796h4.906a1 1 0 00.95-.69l1.519-4.674z" />
                                                </svg>
                                            @elseif($act->tipe === 'profil')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            @elseif($act->tipe === 'pengaturan')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                </svg>
                                            @else
                                                ●
                                            @endif
                                        </div>
                                        <div class="space-y-1">
                                            <h4 class="text-xs font-extrabold text-gray-800">{{ $act->judul }}</h4>
                                            <p class="text-[11px] text-gray-500 leading-relaxed">{{ $act->deskripsi }}</p>
                                            <span class="inline-block text-[9px] text-gray-400 font-bold tracking-wider uppercase">
                                                {{ $act->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <a href="{{ route('user.activities') }}" class="w-full bg-[#f4f7f5] hover:bg-gray-200 text-gray-700 text-xs font-extrabold py-3 text-center rounded-2xl block transition shadow-sm mt-4">
                        Lihat Semua Aktivitas
                    </a>
                </div>
            </div>
        </div>

        <!-- Row 2: Cards 4-6 on Left, Radius Anda on Right -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-stretch mt-6 animate-fade-in">
            <!-- Left: Cards 4-6 -->
            <div class="lg:col-span-2">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($chunk2 as $menu)
                        @php
                            $batas = \Carbon\Carbon::parse($menu->batas_pengambilan);
                            $segera_habis = $batas->diffInMinutes(now(), false) > -60;
                            
                            $diffInMins = now()->diffInMinutes($batas, false);
                            if ($diffInMins > 0) {
                                if ($diffInMins < 60) {
                                    $timeStr = $diffInMins . ' mnt lagi';
                                } else {
                                    $timeStr = round($diffInMins / 60) . ' jam lagi';
                                }
                            } else {
                                $timeStr = 'Habis';
                            }
                        @endphp
                        <div class="relative bg-white border border-gray-100 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.02)] hover:shadow-[0_20px_40px_rgba(0,0,0,0.06)] hover:-translate-y-1.5 transition-all duration-300 flex flex-col overflow-hidden h-[275px] group">
                            <!-- Gambar Makanan & Overlay -->
                            <div class="relative h-[105px] w-full overflow-hidden bg-gray-50 shrink-0">
                                <img src="{{ $menu->masterMakanan->foto ? asset('storage/' . $menu->masterMakanan->foto) : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=500&q=80' }}" 
                                     alt="{{ $menu->masterMakanan->nama_makanan }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">
                                
                                <!-- Status Tag -->
                                @if($segera_habis)
                                    <span class="absolute top-3 left-3 bg-[#9a5b15] text-white text-[9px] font-black uppercase tracking-wider px-3 py-1.5 rounded-full shadow-sm">
                                        SEGERA HABIS
                                    </span>
                                @else
                                    <span class="absolute top-3 left-3 bg-[#1cb764] text-white text-[9px] font-black uppercase tracking-wider px-3 py-1.5 rounded-full shadow-sm">
                                        TERSEDIA
                                    </span>
                                @endif

                                <!-- Jarak Tag -->
                                <span class="absolute bottom-3 right-3 bg-white text-gray-800 text-[10px] font-bold px-3 py-1.5 rounded-full shadow-md flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-[#e09121]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25s-7.5-4.108-7.5-11.25a7.5 7.5 0 1115 0z" />
                                    </svg>
                                    {{ number_format($menu->computed_distance, 1, ',', '.') }} km
                                </span>
                            </div>

                            <!-- Informasi Makanan -->
                            <div class="px-5 pb-3.5 pt-2.5 flex-1 flex flex-col justify-between">
                                <div class="space-y-1">
                                    <!-- Kategori & Harga -->
                                    <div class="flex items-center justify-between gap-1.5 mb-1">
                                        <span class="inline-block bg-[#eefcf4] text-[#1cb764] text-[9px] font-black uppercase tracking-wider px-2.5 py-1 rounded-md border border-[#d2f4e1]">
                                            {{ $menu->masterMakanan->kategori ?? 'Umum' }}
                                        </span>
                                        <span class="text-[10px] font-black {{ $menu->is_gratis ? 'text-[#1cb764] bg-[#eefcf4] px-2 py-0.5 rounded-md border border-[#d2f4e1]' : 'text-gray-900 bg-gray-50 px-2 py-0.5 rounded-md border border-gray-100' }}">
                                            {{ $menu->is_gratis ? 'Gratis' : 'Rp ' . number_format($menu->harga_jual, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    
                                    <h3 class="font-extrabold text-gray-900 text-sm leading-snug line-clamp-1 group-hover:text-[#1cb764] transition-colors">
                                        {{ $menu->masterMakanan->nama_makanan }}
                                    </h3>
                                    
                                    <p class="text-[10px] text-gray-400 font-semibold flex items-center gap-1.5 truncate">
                                        <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                            <path d="M3 9h18M3 9v12a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1V9M3 9L5 3h14l2 6M9 9v4M15 9v4" />
                                        </svg>
                                        {{ $menu->unitBisnis->nama_usaha ?? 'Mitra ShareBite' }}
                                    </p>
                                </div>

                                <!-- Garis Pembatas -->
                                <hr class="border-t border-gray-100 my-2">

                                <!-- Baris Bawah (Detail & Aksi) -->
                                <div class="flex items-center justify-between mt-auto">
                                    <div class="flex items-center gap-2">
                                        <!-- Limit Waktu -->
                                        <div class="inline-flex items-center gap-1 bg-[#fdf4e9] text-[#9a5b15] text-[10px] font-extrabold px-2.5 py-1.5 rounded-full">
                                            <svg class="w-3.5 h-3.5 text-[#e09121]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span>{{ $timeStr }}</span>
                                        </div>
                                        <!-- Porsi -->
                                        <div class="flex items-center gap-1 text-[11px] text-gray-500 font-extrabold">
                                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                            </svg>
                                            <span>{{ $menu->stok_porsi }} Porsi</span>
                                        </div>
                                    </div>
                                    <a href="{{ route('user.makanan.pembayaran', $menu->id) }}" 
                                       class="bg-gradient-to-r from-[#0b472e] to-[#1cb764] hover:from-[#093522] hover:to-[#159a54] text-white text-xs font-bold px-4 py-2.5 rounded-2xl transition shadow-sm transform active:scale-95 cursor-pointer">
                                        Ambil
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @for($i = $chunk2->count(); $i < 3; $i++)
                        <div class="invisible h-[275px]"></div>
                    @endfor
                </div>
            </div>
            <!-- Right: Radius Anda -->
            <div class="flex flex-col">
                @if(is_null(Auth::user()->latitude) || is_null(Auth::user()->longitude))
                    <div class="bg-amber-50 border border-amber-200 rounded-3xl p-6 text-gray-800 space-y-4 flex-1 flex flex-col justify-center">
                        <span class="inline-block bg-amber-500 text-white text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full self-start">
                            LOKASI BELUM SET
                        </span>
                        <h3 class="text-base font-extrabold text-amber-800 leading-tight">
                            Titik Lokasi Belum Ditentukan
                        </h3>
                        <p class="text-xs text-amber-700 leading-relaxed font-medium">
                            Harap tentukan koordinat lokasi Anda terlebih dahulu agar sistem dapat mendeteksi donatur di sekitar Anda.
                        </p>
                        <a href="{{ route('user.profile.edit') }}" class="inline-block w-full bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold py-3 text-center rounded-2xl transition shadow-sm">
                            Atur Lokasi Sekarang
                        </a>
                    </div>
                @else
                    <div class="relative overflow-hidden rounded-[2rem] text-white shadow-lg shadow-green-900/10 flex-1 flex flex-col justify-between border border-gray-100 bg-white min-h-[160px] h-[275px]">
                        <div id="map-radius" class="absolute inset-0 z-0"></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0b472e] via-[#0b472e]/60 to-[#0b472e]/15 z-10 pointer-events-none"></div>
                        <div class="relative z-20 p-6 flex flex-col justify-between h-full min-h-[160px] pointer-events-none">
                            <span class="inline-block bg-[#1cb764] text-white text-[9px] font-black uppercase tracking-wider px-2.5 py-1 rounded-full self-start pointer-events-auto">
                                RADIUS ANDA
                            </span>
                            <div class="space-y-1 mt-auto">
                                <h3 class="text-base font-black leading-tight text-white drop-shadow-md">
                                    {{ Auth::user()->alamat ?? 'Bandung, Indonesia' }}
                                </h3>
                                <p class="text-[10px] text-green-300 font-bold flex items-center gap-1 drop-shadow-sm">
                                    <span class="w-2 h-2 rounded-full bg-green-400 inline-block animate-ping"></span>
                                    {{ $active_donors_count }} Donatur Aktif Sekitar
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Render Radius Map if map-radius element exists
    const mapElement = document.getElementById('map-radius');
    if (mapElement) {
        const lat = {{ Auth::user()->latitude ?? -6.917464 }};
        const lng = {{ Auth::user()->longitude ?? 107.619123 }};
        
        const map = L.map('map-radius', { 
            zoomControl: false, 
            dragging: false, 
            scrollWheelZoom: false,
            doubleClickZoom: false,
            boxZoom: false,
            keyboard: false
        }).setView([lat, lng], 13);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        
        // Custom Marker (Pulse green dot)
        const customIcon = L.divIcon({ 
            className: 'custom-leaflet-marker', 
            html: `<div class="relative flex items-center justify-center w-12 h-12">
                       <div class="absolute w-8 h-8 bg-[#1cb764]/30 rounded-full animate-pulse"></div>
                       <div class="relative w-3.5 h-3.5 bg-[#1cb764] rounded-full border-2 border-white shadow-md"></div>
                   </div>`, 
            iconSize: [48, 48], 
            iconAnchor: [24, 24] 
        });
        
        L.marker([lat, lng], { icon: customIcon }).addTo(map);

        // Fix Leaflet map sizing/alignment issue in grid/flex layouts
        const resizeObserver = new ResizeObserver(() => {
            map.invalidateSize();
        });
        resizeObserver.observe(mapElement);
    }
});
</script>
@endsection
