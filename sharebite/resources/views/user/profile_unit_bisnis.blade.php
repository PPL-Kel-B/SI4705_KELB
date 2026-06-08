@extends('layouts.user') {{-- Sesuaikan dengan nama layout utama di projectmu --}}

@section('content')
<div class="container mx-auto px-6 pt-2 pb-8">
    
    {{-- 1. BREADCRUMB (Navigasi Atas) --}}
    <nav class="text-sm text-gray-500 mb-4">
        <a href="{{ route('user.dashboard') }}" class="hover:underline cursor-pointer">Dashboard</a> / 
        <span class="hover:underline cursor-pointer">Makanan</span> / 
        <span class="text-[#1cb764] font-semibold">{{ $unitBisnis->nama }}</span>
    </nav>

    {{-- 2. GRID 2 KOLOM UTAMA --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-8 items-stretch">
        
        {{-- ==================== KOLOM KIRI: PROFIL & STATISTIK ==================== --}}
        <div class="lg:col-span-4 flex flex-col justify-between gap-6">
            
            {{-- Card Profil Unit Bisnis --}}
            <div class="bg-white rounded-3xl shadow-sm overflow-hidden border border-gray-100 flex flex-col w-full flex-1">
                {{-- Banner Latar Belakang --}}
                @if($unitBisnis->header_image)
                <div class="h-48 md:h-56 w-full relative shrink-0 bg-gray-900 overflow-hidden">
                    {{-- Efek Latar Belakang Blur --}}
                    <div class="absolute inset-0 bg-cover bg-center blur-xl opacity-60 scale-110" style="background-image: url('{{ $unitBisnis->header_image }}');"></div>
                    {{-- Gambar Asli (Tidak Terpotong) --}}
                    <img src="{{ $unitBisnis->header_image }}" class="absolute inset-0 w-full h-full object-contain drop-shadow-lg" alt="Header Profil">
                    {{-- Overlay Gradien agar teks/badge lebih terbaca --}}
                    <div class="absolute inset-0 bg-gradient-to-b from-black/20 via-transparent to-black/20"></div>
                @else
                <div class="h-40 md:h-48 bg-gradient-to-br from-[#1cb764] to-[#148f4c] relative shrink-0">
                    <div class="absolute inset-0 opacity-25 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
                @endif
                    
                    {{-- Glassmorphism Verified Lencana --}}
                    <span class="bg-white/20 backdrop-blur-md text-white text-[10px] font-black tracking-wider uppercase px-3 py-1 rounded-full border border-white/20 absolute top-4 right-4 z-10">
                        Verified Partner
                    </span>
                </div>
                
                {{-- Detail Profil Toko --}}
                <div class="px-6 pb-6 relative flex flex-col items-start -mt-14 flex-1 justify-center">
                    {{-- Foto Profil Unit Bisnis --}}
                    <div class="w-28 h-28 bg-white p-1 rounded-3xl shadow-lg border border-gray-50 z-10 shrink-0">
                        <img src="{{ $unitBisnis->foto_profile ?? 'https://ui-avatars.com/api/?name=' . urlencode($unitBisnis->nama) . '&background=eefcf4&color=1cb764&bold=true&size=128' }}"
                             alt="{{ $unitBisnis->nama }}"
                             class="w-full h-full rounded-2xl object-cover shadow-sm">
                    </div>
                    
                    {{-- Nama, Kategori & Alamat --}}
                    <div class="z-10 w-full mt-4">
                        <div class="flex flex-col gap-2 mb-2">
                            <h1 class="text-2xl font-black text-gray-800 tracking-tight leading-tight">{{ $unitBisnis->nama }}</h1>
                            <div>
                                <span class="inline-flex items-center gap-1.5 bg-[#eefcf4] text-[#1cb764] text-[10px] font-extrabold px-3 py-1 rounded-full border border-[#1cb764]/10">
                                    ✓ {{ $unitBisnis->kategori }}
                                </span>
                            </div>
                        </div>
                        <p class="text-gray-500 flex items-start gap-2 text-xs sm:text-sm mt-3 leading-relaxed">
                            <span class="text-[#1cb764] shrink-0 mt-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </span> 
                            <span>{{ $unitBisnis->alamat }}</span>
                        </p>
                    </div>

                    {{-- Jam Operasional & Kontak --}}
                    <div class="w-full border-t border-gray-100 my-4"></div>
                    <div class="w-full space-y-2.5">
                        <div class="flex items-center justify-between text-xs font-semibold text-gray-400">
                            <span class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#1cb764] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Jam Operasional
                            </span>
                            <span class="text-gray-800 font-extrabold">{{ $unitBisnis->jam_buka }} - {{ $unitBisnis->jam_tutup }} WIB</span>
                        </div>
                        <div class="flex items-center justify-between text-xs font-semibold text-gray-400">
                            <span class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#1cb764] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                Hubungi Mitra
                            </span>
                            <span class="text-gray-800 font-extrabold">{{ $unitBisnis->no_telepon }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs font-semibold text-gray-400">
                            <span class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#1cb764] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Email Mitra
                            </span>
                            <span class="text-gray-800 font-extrabold truncate max-w-[150px] lg:max-w-[120px] xl:max-w-[160px]">{{ $unitBisnis->email }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Dampak Statistik --}}
            <div class="grid grid-cols-2 gap-4 w-full">
                {{-- Total Donasi --}}
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between transition-all hover:shadow-md hover:-translate-y-0.5 duration-200">
                    <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center text-orange-500 shadow-sm border border-orange-100/30 mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-400 text-[9px] font-bold uppercase tracking-wider">Total Donasi</p>
                        <h3 class="text-base font-black text-gray-800 mt-0.5">{{ $unitBisnis->total_donasi ?? 0 }}</h3>
                    </div>
                </div>

                {{-- Rating --}}
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between transition-all hover:shadow-md hover:-translate-y-0.5 duration-200">
                    <div class="w-10 h-10 bg-yellow-50 rounded-xl flex items-center justify-center text-yellow-500 shadow-sm border border-yellow-100/30 mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-400 text-[9px] font-bold uppercase tracking-wider">Reputasi / Rating</p>
                        <h3 class="text-base font-black text-gray-800 mt-0.5">{{ $unitBisnis->rating ?? '0.0' }}</h3>
                    </div>
                </div>
            </div>

        </div>

        {{-- ==================== KOLOM KANAN: DETAIL KONTEN MITRA ==================== --}}
        <div class="lg:col-span-8 flex flex-col space-y-6 h-full">
            {{-- Deskripsi Toko (Tentang Mitra) --}}
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 space-y-6 flex-1">
                {{-- Tentang Mitra --}}
                <div>
                    <h2 class="text-xl font-black text-[#1cb764] tracking-tight mb-2">Tentang Mitra</h2>
                    <p class="text-gray-600 text-sm sm:text-base leading-relaxed font-medium">
                        {{ $unitBisnis->deskripsi }}
                    </p>
                </div>
                
                {{-- Spesialisasi --}}
                @php
                    $categories = collect($makananAktif)->pluck('kategori')->unique()->filter()->toArray();
                @endphp
                <div>
                    <h3 class="text-sm font-bold text-gray-800 tracking-wide uppercase mb-3">Spesialisasi</h3>
                    <div class="flex flex-wrap gap-2.5">
                        @if(empty($categories))
                            <span class="inline-flex items-center gap-1.5 bg-[#eefcf4] text-[#1cb764] text-[10px] font-extrabold px-3 py-1.5 rounded-full border border-[#1cb764]/10 shadow-sm transition hover:scale-[1.02] duration-200">
                                <svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                Makanan Nusantara
                            </span>
                            <span class="inline-flex items-center gap-1.5 bg-[#eefcf4] text-[#1cb764] text-[10px] font-extrabold px-3 py-1.5 rounded-full border border-[#1cb764]/10 shadow-sm transition hover:scale-[1.02] duration-200">
                                <svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                Sayur-Mayur & Buah Segar
                            </span>
                        @else
                            @foreach($categories as $cat)
                                @if(strpos(strtolower($cat), 'berat') !== false)
                                    <span class="inline-flex items-center gap-1.5 bg-[#eefcf4] text-[#1cb764] text-[10px] font-extrabold px-3 py-1.5 rounded-full border border-[#1cb764]/10 shadow-sm transition hover:scale-[1.02] duration-200">
                                        <svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Makanan Nusantara / Berat
                                    </span>
                                @elseif(strpos(strtolower($cat), 'ringan') !== false || strpos(strtolower($cat), 'cemilan') !== false)
                                    <span class="inline-flex items-center gap-1.5 bg-[#eefcf4] text-[#1cb764] text-[10px] font-extrabold px-3 py-1.5 rounded-full border border-[#1cb764]/10 shadow-sm transition hover:scale-[1.02] duration-200">
                                        <svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Sayur-Mayur & Cemilan Segar
                                    </span>
                                @elseif(strpos(strtolower($cat), 'minuman') !== false)
                                    <span class="inline-flex items-center gap-1.5 bg-[#eefcf4] text-[#1cb764] text-[10px] font-extrabold px-3 py-1.5 rounded-full border border-[#1cb764]/10 shadow-sm transition hover:scale-[1.02] duration-200">
                                        <svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Aneka Minuman Segar
                                    </span>
                                @elseif(strpos(strtolower($cat), 'dessert') !== false)
                                    <span class="inline-flex items-center gap-1.5 bg-[#eefcf4] text-[#1cb764] text-[10px] font-extrabold px-3 py-1.5 rounded-full border border-[#1cb764]/10 shadow-sm transition hover:scale-[1.02] duration-200">
                                        <svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Dessert & Makanan Manis
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 bg-[#eefcf4] text-[#1cb764] text-[10px] font-extrabold px-3 py-1.5 rounded-full border border-[#1cb764]/10 shadow-sm transition hover:scale-[1.02] duration-200">
                                        <svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                        {{ $cat }}
                                    </span>
                                @endif
                            @endforeach
                        @endif
                        <span class="inline-flex items-center gap-1.5 bg-[#eefcf4] text-[#1cb764] text-[10px] font-extrabold px-3 py-1.5 rounded-full border border-[#1cb764]/10 shadow-sm transition hover:scale-[1.02] duration-200">
                            <svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            Mendukung donasi komunitas lokal
                        </span>
                    </div>
                </div>

                {{-- Galeri --}}
                @php
                    $buktiDonasisCol = collect($buktiDonasis)->filter();
                @endphp
                <div x-data="{ openGalleryModal: false }">
                    <h3 class="text-sm font-bold text-gray-800 tracking-wide uppercase mb-3">Galeri Aktivitas Donasi</h3>
                    
                    @if($buktiDonasisCol->isNotEmpty())
                        <div class="grid grid-cols-4 gap-3">
                            @foreach($buktiDonasisCol->take(3) as $index => $foto)
                                <div class="h-16 rounded-xl overflow-hidden shadow-sm border border-gray-100 cursor-pointer hover:opacity-90 hover:scale-[1.02] active:scale-95 transition-all duration-200" @click="openGalleryModal = true">
                                    <img src="{{ $foto }}" alt="Bukti Donasi {{ $index + 1 }}" class="w-full h-full object-cover">
                                </div>
                            @endforeach
                            
                            @if($buktiDonasisCol->count() > 4)
                                <div class="h-16 rounded-xl overflow-hidden shadow-sm border border-gray-100 relative cursor-pointer group active:scale-95 transition-all duration-200" @click="openGalleryModal = true">
                                    <img src="{{ $buktiDonasisCol->get(3) }}" alt="Bukti Donasi 4" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    <div class="absolute inset-0 bg-black/60 backdrop-blur-[1px] flex flex-col items-center justify-center text-white font-extrabold text-xs transition-colors group-hover:bg-black/50">
                                        <span class="text-sm">+{{ $buktiDonasisCol->count() - 3 }}</span>
                                    </div>
                                </div>
                            @elseif($buktiDonasisCol->count() == 4)
                                <div class="h-16 rounded-xl overflow-hidden shadow-sm border border-gray-100 cursor-pointer hover:opacity-90 hover:scale-[1.02] active:scale-95 transition-all duration-200" @click="openGalleryModal = true">
                                    <img src="{{ $buktiDonasisCol->get(3) }}" alt="Bukti Donasi 4" class="w-full h-full object-cover">
                                </div>
                            @endif
                        </div>
                        <p class="text-[10px] text-gray-400 mt-2 font-semibold">Foto bukti penyaluran donasi terverifikasi kami.</p>
                    @else
                        <div class="flex flex-col items-center justify-center p-6 bg-[#f4f7f5]/40 rounded-2xl border border-dashed border-gray-200 text-center">
                            <div class="w-12 h-12 bg-[#eefcf4] rounded-2xl flex items-center justify-center text-[#1cb764] mb-3 border border-[#1cb764]/10 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <p class="text-xs font-bold text-gray-700">Bukti Aktivitas Donasi Belum Tersedia</p>
                            <p class="text-[10px] text-gray-400 mt-1 max-w-[240px] font-medium leading-relaxed">Mitra ini belum memiliki atau mengunggah foto penyaluran donasi saat ini.</p>
                        </div>
                    @endif

                    {{-- AlpineJS Modal --}}
                    <div x-show="openGalleryModal" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="fixed inset-0 z-50 flex items-center justify-center p-4" 
                         x-cloak>
                        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="openGalleryModal = false"></div>
                        <div class="relative bg-white rounded-3xl shadow-2xl max-w-2xl w-full max-h-[80vh] p-6 z-10 border border-gray-100 flex flex-col">
                            <div class="flex justify-between items-center mb-4 pb-2 border-b border-gray-100 shrink-0">
                                <h3 class="text-base font-extrabold text-gray-800 flex items-center gap-2">
                                    <svg class="h-5 w-5 text-gray-500 shrink-0 inline-block mr-1 align-text-bottom" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg> Galeri Aktivitas Donasi
                                </h3>
                                <button @click="openGalleryModal = false" class="text-gray-400 hover:text-gray-600 bg-gray-100 hover:bg-gray-200 p-1.5 rounded-full transition">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 overflow-y-auto pr-1">
                                @foreach($buktiDonasisCol as $index => $foto)
                                    <div class="aspect-square rounded-2xl overflow-hidden shadow-sm border border-gray-100 group cursor-pointer hover:shadow-md transition">
                                        <a href="{{ $foto }}" target="_blank" class="block w-full h-full">
                                            <img src="{{ $foto }}" alt="Bukti Donasi Lengkap {{ $index + 1 }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Divider line --}}
                <div class="border-t border-gray-100"></div>

                {{-- Ulasan Komunitas --}}
                <div>
                    <h3 class="text-sm font-bold text-gray-800 tracking-wide uppercase mb-3 flex items-center gap-2">
                        <svg class="h-5 w-5 text-[#1cb764] shrink-0 inline-block align-text-bottom" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg> Ulasan Komunitas
                    </h3>
                    @if(collect($ulasans)->filter()->isNotEmpty())
                        <div class="space-y-4 max-h-[320px] overflow-y-auto pr-1">
                            @foreach($ulasans as $ulasan)
                                <div class="bg-gray-50/50 p-4 rounded-2xl border border-gray-100 transition duration-200 hover:bg-gray-50 hover:shadow-sm">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-emerald-50 text-[#1cb764] flex items-center justify-center font-bold text-sm border border-[#1cb764]/10 shrink-0">
                                                {{ strtoupper(substr($ulasan->user->name ?? 'U', 0, 1)) }}
                                            </div>
                                            <div>
                                                <h4 class="text-sm font-black text-gray-800 leading-none">{{ $ulasan->user->name ?? 'Pengguna Anonim' }}</h4>
                                                <p class="text-[10px] text-gray-400 font-semibold mt-1">
                                                    Pelanggan ShareBite
                                                </p>
                                            </div>
                                        </div>
                                        <button class="text-gray-400 hover:text-gray-600 p-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                            </svg>
                                        </button>
                                    </div>
                                    
                                    <div class="flex items-center gap-2 mb-2">
                                        <div class="flex items-center gap-0.5 text-yellow-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-3.5 h-3.5" fill="{{ $i <= $ulasan->skor_rating ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.9 1.39-.9 1.69 0l1.286 3.97a1 1 0 00.95.69h4.17c.969 0 1.371 1.24.588 1.81l-3.374 2.455a1 1 0 00-.386 1.18l1.287 3.97c.3.9-.755 1.688-1.54 1.118l-3.374-2.455a1 1 0 00-1.175 0l-3.374 2.455c-.784.57-1.838-.218-1.539-1.118l1.288-3.97a1 1 0 00-.386-1.18L2.34 9.397c-.783-.57-.38-1.81.588-1.81h4.17a1 1 0 00.951-.69l1.286-3.97z" />
                                                </svg>
                                            @endfor
                                        </div>
                                        <span class="text-[10px] text-gray-400 font-medium">
                                            {{ $ulasan->created_at instanceof \Carbon\Carbon ? $ulasan->created_at->diffForHumans() : (\Carbon\Carbon::parse($ulasan->created_at)->diffForHumans()) }}
                                        </span>
                                    </div>

                                    <p class="text-xs text-gray-800 font-medium leading-relaxed">
                                        {{ $ulasan->catatan_pengalaman ?? 'Tidak ada komentar tertulis.' }}
                                    </p>

                                    @if(!empty($ulasan->foto_bukti_berbagi))
                                        <div class="mt-3 rounded-xl overflow-hidden border border-gray-100 shadow-sm cursor-pointer hover:opacity-90 transition-opacity" @click="openGalleryModal = true">
                                            <img src="{{ str_starts_with($ulasan->foto_bukti_berbagi, 'http') ? $ulasan->foto_bukti_berbagi : asset('storage/' . $ulasan->foto_bukti_berbagi) }}" alt="Foto Ulasan" class="w-full h-48 object-cover">
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center p-6 bg-[#f4f7f5]/40 rounded-2xl border border-dashed border-gray-200 text-center">
                            <div class="w-11 h-11 bg-emerald-50 rounded-2xl flex items-center justify-center text-[#1cb764] mb-2 border border-[#1cb764]/10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                            <p class="text-xs font-bold text-gray-700">Belum Ada Ulasan</p>
                            <p class="text-[10px] text-gray-400 mt-1 max-w-[240px] font-medium leading-relaxed">Jadilah yang pertama memberikan apresiasi positif untuk mitra donasi ini!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    {{-- 3. DAFTAR MAKANAN AKTIF --}}
    <div class="mt-10">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800">Makanan yang Tersedia Saat Ini</h2>
            <span class="text-xs bg-[#eefcf4] text-[#1cb764] px-3 py-1.5 rounded-lg font-bold border border-[#1cb764]/10">
                {{ collect($makananAktif)->count() }} Menu Aktif
            </span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($makananAktif as $makanan)
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100 flex flex-col group hover:shadow-md transition">
                {{-- Gambar Makanan --}}
                <div class="h-40 bg-gray-200 relative overflow-hidden">
                    <img src="{{ $makanan->foto }}" alt="{{ $makanan->nama }}" class="w-full h-full object-cover">
                    @if($makanan->porsi < 5)
                        <div class="absolute top-3 left-3 bg-red-500 text-white text-[10px] uppercase font-bold px-2 py-1 rounded-md shadow-sm">
                            Segera Habis
                        </div>
                    @else
                        <div class="absolute top-3 left-3 bg-[#1cb764] text-white text-[10px] uppercase font-bold px-2 py-1 rounded-md shadow-sm">
                            Tersedia
                        </div>
                    @endif
                    <div class="absolute bottom-3 right-3 bg-black/60 text-white text-xs px-2 py-1 rounded-lg backdrop-blur-sm flex items-center gap-1.5 font-bold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        {{ $makanan->jarak }}
                    </div>
                </div>

                {{-- Konten Card Makanan --}}
                <div class="p-4 flex-1 flex flex-col justify-between">
                    <div>
                        <h4 class="font-extrabold text-gray-800 group-hover:text-[#1cb764] transition text-base mb-1.5 line-clamp-1">
                            {{ $makanan->nama }}
                        </h4>
                        <p class="text-sm font-semibold text-gray-500 mb-2 flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#1cb764]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            Sisa {{ $makanan->porsi }} Porsi
                        </p>
                    </div>

                    <div class="flex items-center justify-between mt-4 pt-3 border-t border-gray-50">
                        <div>
                            <p class="text-[10px] text-gray-400 font-medium">Harga / Porsi</p>
                            <p class="text-[#1cb764] font-bold text-sm">Rp {{ number_format((int)$makanan->harga, 0, ',', '.') }}</p>
                        </div>
                        {{-- Tombol Ambil Makanan --}}
                        <a href="{{ route('user.makanan.detail', $makanan->id) }}" class="bg-[#1cb764] hover:bg-[#158f4e] text-white text-xs font-semibold px-4 py-2 rounded-xl transition text-center">
                            Ambil
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</div>
@endsection