@php
    $hideSearch = true;
@endphp
@extends('layouts.user')

@section('title', 'Detail Makanan - Simulasi')

@section('content')
<div class="container mx-auto px-4 pt-2 pb-8">
    
    {{-- BREADCRUMB --}}
    <nav class="text-sm text-gray-500 mb-4 flex items-center gap-2">
        <span class="hover:underline cursor-pointer">Dashboard</span> 
        <span class="text-gray-400">/</span>
        <span class="hover:underline cursor-pointer">Makanan</span> 
        <span class="text-gray-400">/</span>
        <span class="text-[#1cb764] font-semibold">{{ $makanan->nama }}</span>
    </nav>

    {{-- BAR SELECTOR MENU REAL DARI DATABASE (POV UNIT BISNIS) --}}
    @if($allActiveMenus->isNotEmpty())
    <div class="bg-[#1cb764] text-white px-6 py-3.5 rounded-3xl mb-6 flex flex-col md:flex-row items-center justify-between gap-4 shadow-sm">
        <div class="flex items-center gap-2">
            <span class="text-lg text-white">
                <svg class="h-5 w-5 inline-block mr-1 align-text-bottom shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v3m-3-3v3m6-3v3M4 11h16a1 1 0 011 1v1a8 8 0 01-8 8 8 8 0 01-8-8v-1a1 1 0 011-1z" />
                </svg>
            </span>
            <span class="text-xs sm:text-sm font-bold text-white">
                Menu Terpilih: <strong class="underline decoration-green-200 decoration-2 underline-offset-2">{{ $makanan->nama }}</strong>
            </span>
        </div>
        <div class="flex items-center gap-2">
            <label for="select_menu" class="text-xs font-bold text-green-100 whitespace-nowrap">Pilih Menu Lain:</label>
            <select id="select_menu" onchange="window.location.href='/user/tes-tombol-profil/' + this.value" class="bg-white/10 hover:bg-white/20 border border-white/20 rounded-xl px-3 py-2 text-xs text-white font-bold focus:outline-none focus:ring-2 focus:ring-green-300 transition cursor-pointer">
                @foreach($allActiveMenus as $m)
                    <option value="{{ $m->id }}" {{ $makanan->id == $m->id ? 'selected' : '' }} class="text-gray-800">
                        {{ $m->masterMakanan->nama_makanan }} ({{ $m->unitBisnis->nama_usaha }})
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    @else
    {{-- WARNING BANNER UNTUK SIMULASI FALLBACK --}}
    <div class="bg-amber-50 border border-amber-200 text-amber-800 px-6 py-4 rounded-2xl mb-6 flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-3">
            <span class="text-2xl text-amber-500 shrink-0">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
            </span>
            <div>
                <p class="font-bold text-sm">Belum Ada Menu Aktif di Database (Menggunakan Data Fallback)</p>
                <p class="text-xs text-amber-700">Kamu sedang melihat data simulasi buah salad. Coba login sebagai <strong>Unit Bisnis</strong>, tambahkan menu baru di menu "Kelola Makanan", lalu kembali ke halaman ini untuk melihat menu real buatanmu!</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('user.unit-bisnis.show', $makanan->unit_bisnis_id) }}" class="bg-[#1cb764] hover:bg-[#158f4e] text-white font-bold text-xs px-4 py-2 rounded-xl shadow-md transition whitespace-nowrap">
                Buka Profil Langsung
            </a>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- KOLOM KIRI (KONTEN UTAMA) --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- CARD DETAIL MAKANAN UTAMA (GAMBAR MAKANAN DENGAN BADGES) --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                {{-- Gambar Makanan --}}
                <div class="h-96 relative bg-gray-100">
                    <img src="{{ $makanan->foto }}" alt="{{ $makanan->nama }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"></div>
                    
                    {{-- Badges --}}
                    <div class="absolute bottom-6 left-6 right-6 flex justify-between items-end">
                        <div>
                            <span class="bg-[#1cb764] text-white text-xs font-bold px-3 py-1.5 rounded-lg uppercase tracking-wider">
                                {{ $makanan->kategori }}
                            </span>
                            <h1 class="text-2xl sm:text-3xl font-black text-white mt-3 drop-shadow-sm">{{ $makanan->nama }}</h1>
                            <p class="text-green-300 text-xs font-semibold mt-1 flex items-center gap-1">
                                <svg class="h-3.5 w-3.5 text-green-300 inline-block mr-1 align-text-bottom" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg> Organic Curator Verified
                            </p>
                        </div>
                        <span class="bg-black/40 backdrop-blur-md text-white text-xs px-3 py-1.5 rounded-xl font-bold flex flex-col items-center justify-center border border-white/10 min-w-[70px]">
                            <span class="text-[9px] font-bold text-gray-300 uppercase tracking-wider leading-none mb-0.5">Jarak</span>
                            <span class="leading-tight">{{ $makanan->jarak }}</span>
                        </span>
                    </div>
                </div>
            </div>

            {{-- 3-COLUMN HORIZONTAL QUICK STATS BAR --}}
            <div class="bg-white rounded-3xl border border-gray-100 p-5 shadow-sm">
                <div class="grid grid-cols-3 divide-x divide-gray-100 text-center">
                    <div class="flex items-center justify-center gap-3">
                        <div class="w-10 h-10 bg-[#eef5f1] rounded-xl flex items-center justify-center text-[#047857] shrink-0">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 8v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8"/>
                                <path d="M21 3H3v5h18V3z"/>
                                <path d="M10 12h4"/>
                            </svg>
                        </div>
                        <div class="text-left">
                            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider leading-none">Tersedia</p>
                            <p class="text-sm font-extrabold text-gray-800 mt-0.5">{{ $makanan->stok_porsi }} Porsi</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-center gap-3">
                        <div class="w-10 h-10 bg-[#f9f3eb] rounded-xl flex items-center justify-center text-[#854d0e] shrink-0">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
                            </svg>
                        </div>
                        <div class="text-left">
                            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider leading-none">Kadaluarsa</p>
                            <p class="text-sm font-extrabold text-[#854d0e] mt-0.5">{{ $makanan->batas_pengambilan }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-center gap-3">
                        <div class="w-10 h-10 bg-[#eef5f1] rounded-xl flex items-center justify-center text-[#047857] shrink-0">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="6" width="20" height="12" rx="2"/>
                                <circle cx="12" cy="12" r="2"/>
                                <path d="M6 12h.01M18 12h.01"/>
                            </svg>
                        </div>
                        <div class="text-left">
                            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider leading-none">Harga / Porsi</p>
                            <p class="text-sm font-extrabold text-[#1cb764] mt-0.5">
                                {{ $makanan->is_gratis ? 'Gratis' : 'Rp ' . number_format($makanan->harga, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SEGMENT KUNJUNGI PROFIL & TENTANG MAKANAN --}}
            <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm space-y-5">
                {{-- Vendor Row --}}
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-gray-100 rounded-xl overflow-hidden shadow-sm shrink-0 border border-gray-100">
                            <img src="{{ $makanan->foto_profile ?? 'https://images.unsplash.com/photo-1552566626-52f8b828add9?w=120&q=80' }}" 
                                 alt="{{ $makanan->nama_usaha }}"
                                 class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h3 class="font-extrabold text-gray-800 text-base leading-tight">{{ $makanan->nama_usaha }}</h3>
                            <p class="text-xs text-gray-500 flex items-start gap-1 mt-1 leading-normal">
                                <svg class="h-4 w-4 text-[#1cb764] inline-block mr-1.5 align-text-bottom shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg> <span>{{ $makanan->alamat }}</span>
                            </p>
                        </div>
                    </div>
                    
                    {{-- TOMBOL KUNJUNGI PROFIL --}}
                    <a href="{{ route('user.unit-bisnis.show', $makanan->unit_bisnis_id) }}" 
                       class="bg-white border-2 border-[#0a6833] text-[#0a6833] hover:bg-[#0a6833] hover:text-white font-extrabold text-base px-8 py-3 rounded-[1.75rem] transition-all duration-300 shadow-sm shrink-0">
                        Kunjungi Profil
                    </a>
                </div>

                {{-- Divider line --}}
                <div class="border-t border-gray-100"></div>

                {{-- Tentang Makanan --}}
                <div class="space-y-3">
                    <h3 class="font-bold text-gray-800 text-sm">Tentang Makanan Ini</h3>
                    <p class="text-gray-600 text-xs sm:text-sm leading-relaxed">
                        {{ $makanan->deskripsi }}
                    </p>
                    
                    {{-- Tags --}}
                    <div class="flex flex-wrap gap-2 pt-2">
                        <span class="bg-[#eefcf4] text-[#1cb764] text-[10px] px-3 py-1 rounded-full font-bold border border-[#1cb764]/10">✓ Segar & Higienis</span>
                        <span class="bg-[#eefcf4] text-[#1cb764] text-[10px] px-3 py-1 rounded-full font-bold border border-[#1cb764]/10">✓ Kemasan Ramah Lingkungan</span>
                        <span class="bg-[#eefcf4] text-[#1cb764] text-[10px] px-3 py-1 rounded-full font-bold border border-[#1cb764]/10">✓ Sertifikasi Halal</span>
                    </div>
                </div>
            </div>

        </div>

        {{-- KOLOM KANAN (SIDEBAR DETAILS) --}}
        <div class="space-y-6" x-data="{ porsi: 1, harga: {{ $makanan->harga }}, maxStok: {{ $makanan->stok_porsi }} }">
            
            {{-- CARD ORDER/PORSI --}}
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 space-y-6">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest text-center">PILIH JUMLAH PORSI</h3>
                
                {{-- Counter --}}
                <div class="flex items-center justify-between bg-gray-50 rounded-2xl p-2 max-w-[200px] mx-auto border border-gray-100">
                    <button @click="if(porsi > 1) porsi--" class="w-10 h-10 bg-gray-200 hover:bg-gray-300 rounded-xl text-gray-700 font-bold transition flex items-center justify-center text-lg shadow-sm">
                        -
                    </button>
                    <span class="text-lg font-black text-gray-800" x-text="porsi">1</span>
                    <button @click="if(porsi < maxStok) porsi++" class="w-10 h-10 bg-[#1cb764] hover:bg-[#158f4e] text-white rounded-xl font-bold transition flex items-center justify-center text-lg shadow-md">
                        +
                    </button>
                </div>

                {{-- Estimasi Harga --}}
                <div class="text-center py-2 border-t border-b border-gray-50">
                    <p class="text-2xl font-black text-[#1cb764] mt-1">
                        Rp <span x-text="(porsi * harga).toLocaleString('id-ID')">{{ number_format($makanan->harga, 0, ',', '.') }}</span>
                    </p>
                </div>

                {{-- Action Button --}}
                <button @click="window.location.href = '{{ route('user.makanan.pembayaran', $makanan->id) }}?qty=' + porsi" class="w-full bg-[#1cb764] hover:bg-[#158f4e] text-white font-extrabold text-xs py-4 rounded-2xl transition-all shadow-md flex items-center justify-center gap-2 uppercase tracking-wider">
                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    Ambil Makanan
                </button>
            </div>

            {{-- CARD LOKASI & PETA MOCK --}}
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 space-y-4">
                <div class="flex justify-between items-center">
                    <h3 class="font-extrabold text-gray-800 text-xs tracking-wider">LOKASI</h3>
                    <a href="#" class="text-xs text-[#1cb764] font-extrabold hover:underline">Buka di Maps</a>
                </div>
                
                {{-- Mock Map Box --}}
                <div class="h-44 rounded-2xl relative overflow-hidden border border-gray-100">
                    <img src="https://images.unsplash.com/photo-1524661135-423995f22d0b?w=400&q=80" alt="mock map" class="w-full h-full object-cover opacity-60">
                    
                    {{-- Grid lines background simulation --}}
                    <div class="absolute inset-0 opacity-15 bg-[radial-gradient(#1cb764_1px,transparent_1px)] [background-size:16px_16px]"></div>

                    {{-- Pin --}}
                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 flex flex-col items-center">
                        <div class="w-8 h-8 bg-[#1cb764] text-white rounded-full flex items-center justify-center shadow-md animate-bounce ring-4 ring-[#eefcf4]">
                            <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <span class="mt-1 bg-white/95 backdrop-blur-sm text-[9px] font-black text-gray-800 px-2 py-0.5 rounded-md border border-gray-100 shadow-sm whitespace-nowrap">
                            Lokasi Disini
                        </span>
                    </div>
                </div>

                {{-- Panduan Penjemputan --}}
                <div class="space-y-1">
                    <p class="text-xs font-extrabold text-gray-800 leading-none">Instruksi Pengambilan:</p>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        Lapor ke staf kasir dan tunjukkan ID ShareBite Anda di konter depan.
                    </p>
                </div>
            </div>

            {{-- CARD JAMINAN KUALITAS --}}
            <div class="bg-[#f9f6e6] border border-[#f3ecc2] p-5 rounded-3xl flex gap-3 shadow-sm">
                <span class="text-xl text-amber-600 shrink-0">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </span>
                <div>
                    <h4 class="font-extrabold text-[#854d0e] text-xs uppercase tracking-wider">JAMINAN KUALITAS</h4>
                    <p class="text-[10px] text-gray-600 leading-relaxed mt-1">
                        Mitra kami telah melewati verifikasi standar keamanan pangan ShareBite untuk menjamin kualitas makanan yang diberikan.
                    </p>
                </div>
            </div>

        </div>

    </div>

    {{-- MAKANAN SERUPA --}}
    <div class="mt-12">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-xl font-black text-gray-800">Makanan Serupa</h2>
                <p class="text-xs text-gray-400 mt-1">Mungkin Anda juga tertarik dengan ini</p>
            </div>
            <a href="#" class="text-xs text-[#1cb764] font-extrabold hover:underline flex items-center gap-1">
                Lihat Semua <span class="text-sm">➔</span>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            {{-- Card 1 --}}
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100 flex flex-col group hover:shadow-md transition">
                <div class="h-36 bg-gray-200 relative overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=300&q=80" alt="Gado-Gado Spesial" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute top-3 right-3 bg-white/90 text-[#c2410c] text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm">
                        Exp 3 jam
                    </div>
                </div>
                <div class="p-4 flex-1 flex flex-col justify-between">
                    <div>
                        <h4 class="font-extrabold text-gray-800 text-sm group-hover:text-[#1cb764] transition leading-snug">Gado-Gado Spesial</h4>
                        <p class="text-[10px] text-gray-400 mt-1">Kitchen Table Bistro</p>
                    </div>
                    <div class="flex items-center justify-between mt-3 pt-2.5 border-t border-gray-50">
                        <span class="text-xs text-green-600 font-bold">5 Porsi</span>
                        <span class="text-[10px] text-gray-400">1,2 km</span>
                    </div>
                </div>
            </div>

            {{-- Card 2 --}}
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100 flex flex-col group hover:shadow-md transition">
                <div class="h-36 bg-gray-200 relative overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=300&q=80" alt="Smoothie Bowl Berry" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute top-3 right-3 bg-white/90 text-[#c2410c] text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm">
                        Exp 1 jam
                    </div>
                </div>
                <div class="p-4 flex-1 flex flex-col justify-between">
                    <div>
                        <h4 class="font-extrabold text-gray-800 text-sm group-hover:text-[#1cb764] transition leading-snug">Smoothie Bowl Berry</h4>
                        <p class="text-[10px] text-gray-400 mt-1">Green Roots Cafe</p>
                    </div>
                    <div class="flex items-center justify-between mt-3 pt-2.5 border-t border-gray-50">
                        <span class="text-xs text-green-600 font-bold">2 Porsi</span>
                        <span class="text-[10px] text-gray-400">0,5 km</span>
                    </div>
                </div>
            </div>

            {{-- Card 3 --}}
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100 flex flex-col group hover:shadow-md transition">
                <div class="h-36 bg-gray-200 relative overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=300&q=80" alt="Snack Box Sehat" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute top-3 right-3 bg-white/90 text-[#c2410c] text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm">
                        Exp 4 jam
                    </div>
                </div>
                <div class="p-4 flex-1 flex flex-col justify-between">
                    <div>
                        <h4 class="font-extrabold text-gray-800 text-sm group-hover:text-[#1cb764] transition leading-snug">Snack Box Sehat</h4>
                        <p class="text-[10px] text-gray-400 mt-1">Eco Delights</p>
                    </div>
                    <div class="flex items-center justify-between mt-3 pt-2.5 border-t border-gray-50">
                        <span class="text-xs text-green-600 font-bold">15 Porsi</span>
                        <span class="text-[10px] text-gray-400">2,1 km</span>
                    </div>
                </div>
            </div>

            {{-- Card 4 --}}
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100 flex flex-col group hover:shadow-md transition">
                <div class="h-36 bg-gray-200 relative overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=300&q=80" alt="Green Detox Salad" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute top-3 right-3 bg-white/90 text-[#c2410c] text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm">
                        Exp 6 jam
                    </div>
                </div>
                <div class="p-4 flex-1 flex flex-col justify-between">
                    <div>
                        <h4 class="font-extrabold text-gray-800 text-sm group-hover:text-[#1cb764] transition leading-snug">Green Detox Salad</h4>
                        <p class="text-[10px] text-gray-400 mt-1">Urban Veggie</p>
                    </div>
                    <div class="flex items-center justify-between mt-3 pt-2.5 border-t border-gray-50">
                        <span class="text-xs text-green-600 font-bold">8 Porsi</span>
                        <span class="text-[10px] text-gray-400">1,8 km</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
