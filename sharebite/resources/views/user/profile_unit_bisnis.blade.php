@extends('layouts.user') {{-- Sesuaikan dengan nama layout utama di projectmu --}}

@section('content')
<div class="container mx-auto px-6 pt-2 pb-8">
    
    {{-- 1. BREADCRUMB (Navigasi Atas) --}}
    <nav class="text-sm text-gray-500 mb-4">
        <span class="hover:underline cursor-pointer">Dashboard</span> / 
        <span class="hover:underline cursor-pointer">Makanan</span> / 
        <span class="text-[#1cb764] font-semibold">{{ $unitBisnis->nama }}</span>
    </nav>

    {{-- 2. GRID 2 KOLOM (PROFIL TOKO & DESKRIPSI) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-8 items-stretch">
        
        {{-- Kolom Kiri: Profil Unit Bisnis (Panjang Kebawah) --}}
        <div class="lg:col-span-5 flex">
            <div class="bg-white rounded-3xl shadow-sm overflow-hidden border border-gray-100 flex flex-col w-full">
                {{-- Banner Latar Belakang --}}
                <div class="h-40 bg-gradient-to-br from-[#1cb764] to-[#148f4c] relative shrink-0">
                    <div class="absolute inset-0 opacity-25 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
                    
                    {{-- Glassmorphism Verified Lencana --}}
                    <span class="bg-white/20 backdrop-blur-md text-white text-[10px] font-black tracking-wider uppercase px-3 py-1 rounded-full border border-white/20 absolute top-4 right-4">
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
                        <p class="text-gray-500 flex items-start gap-1.5 text-xs sm:text-sm mt-3 leading-relaxed">
                            <span class="text-base leading-none">📍</span> {{ $unitBisnis->alamat }}
                        </p>
                    </div>

                    {{-- Jam Operasional & Kontak --}}
                    <div class="w-full border-t border-gray-100 my-4"></div>
                    <div class="w-full space-y-2.5">
                        <div class="flex items-center justify-between text-xs font-semibold text-gray-400">
                            <span class="flex items-center gap-1.5">⏱️ Jam Operasional</span>
                            <span class="text-gray-800 font-extrabold">{{ $unitBisnis->jam_buka }} - {{ $unitBisnis->jam_tutup }} WIB</span>
                        </div>
                        <div class="flex items-center justify-between text-xs font-semibold text-gray-400">
                            <span class="flex items-center gap-1.5">📞 Hubungi Mitra</span>
                            <span class="text-gray-800 font-extrabold">{{ $unitBisnis->no_telepon }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs font-semibold text-gray-400">
                            <span class="flex items-center gap-1.5">✉️ Email Mitra</span>
                            <span class="text-gray-800 font-extrabold truncate max-w-[180px]">{{ $unitBisnis->email }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Deskripsi Toko & Statistik Dampak (Sejajar dengan Background Kotak Putih) --}}
        <div class="lg:col-span-7 flex flex-col justify-between space-y-6">
            {{-- Deskripsi Toko (Tentang Mitra) --}}
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex-1 space-y-6">
                {{-- Tentang Mitra --}}
                <div>
                    <h2 class="text-xl font-black text-[#1cb764] tracking-tight mb-2">Tentang Mitra</h2>
                    <p class="text-gray-600 text-sm sm:text-base leading-relaxed font-medium">
                        {{ $unitBisnis->deskripsi }}
                    </p>
                </div>

                {{-- Spesialisasi --}}
                @php
                    $categories = collect($makananAktif)->pluck('kategori')->unique()->toArray();
                @endphp
                <div>
                    <h3 class="text-sm font-bold text-gray-800 tracking-wide uppercase mb-3">Spesialisasi</h3>
                    <div class="space-y-3">
                        @if(empty($categories))
                            <div class="flex items-center gap-3">
                                <span class="text-lg">🍛</span>
                                <span class="text-xs sm:text-sm font-bold text-gray-700">Makanan Nusantara</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-lg">🥦</span>
                                <span class="text-xs sm:text-sm font-bold text-gray-700">Sayur-Mayur & Buah Segar</span>
                            </div>
                        @else
                            @foreach($categories as $cat)
                                @if(strpos(strtolower($cat), 'berat') !== false)
                                    <div class="flex items-center gap-3">
                                        <span class="text-lg">🍛</span>
                                        <span class="text-xs sm:text-sm font-bold text-gray-700">Makanan Nusantara / Berat</span>
                                    </div>
                                @elseif(strpos(strtolower($cat), 'ringan') !== false || strpos(strtolower($cat), 'cemilan') !== false)
                                    <div class="flex items-center gap-3">
                                        <span class="text-lg">🥦</span>
                                        <span class="text-xs sm:text-sm font-bold text-gray-700">Sayur-Mayur & Cemilan Segar</span>
                                    </div>
                                @elseif(strpos(strtolower($cat), 'minuman') !== false)
                                    <div class="flex items-center gap-3">
                                        <span class="text-lg">🥤</span>
                                        <span class="text-xs sm:text-sm font-bold text-gray-700">Aneka Minuman Segar</span>
                                    </div>
                                @elseif(strpos(strtolower($cat), 'dessert') !== false)
                                    <div class="flex items-center gap-3">
                                        <span class="text-lg">🍰</span>
                                        <span class="text-xs sm:text-sm font-bold text-gray-700">Dessert & Makanan Manis</span>
                                    </div>
                                @else
                                    <div class="flex items-center gap-3">
                                        <span class="text-lg">🍱</span>
                                        <span class="text-xs sm:text-sm font-bold text-gray-700">{{ $cat }}</span>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                        <div class="flex items-center gap-3">
                            <span class="text-lg">🚚</span>
                            <span class="text-xs sm:text-sm font-bold text-gray-700">Mendukung donasi komunitas lokal</span>
                        </div>
                    </div>
                </div>

                {{-- Galeri (Bukti Donasi Terhubung ke Database) --}}
                <div>
                    <h3 class="text-sm font-bold text-gray-800 tracking-wide uppercase mb-3">Galeri Aktivitas Donasi</h3>
                    <div class="grid grid-cols-4 gap-3">
                        @if(!empty($buktiDonasis))
                            @foreach($buktiDonasis as $index => $foto)
                                <div class="h-16 rounded-xl overflow-hidden shadow-sm border border-gray-50">
                                    <img src="{{ $foto }}" alt="Bukti Donasi {{ $index + 1 }}" class="w-full h-full object-cover">
                                </div>
                            @endforeach
                            {{-- Fill with fallbacks if less than 4 --}}
                            @for($i = count($buktiDonasis); $i < 4; $i++)
                                <div class="h-16 rounded-xl overflow-hidden shadow-sm border border-gray-50">
                                    <img src="{{ ['https://images.unsplash.com/photo-1577106263724-2c8e03bfe9cf?w=150&q=80', 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?w=150&q=80', 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=150&q=80', 'https://images.unsplash.com/photo-1559027615-cd4628902d4a?w=150&q=80'][$i] }}" alt="Galeri Fallback {{ $i + 1 }}" class="w-full h-full object-cover">
                                </div>
                            @endfor
                        @else
                            {{-- Full Fallback Unsplash Photos --}}
                            <div class="h-16 rounded-xl overflow-hidden shadow-sm border border-gray-50">
                                <img src="https://images.unsplash.com/photo-1577106263724-2c8e03bfe9cf?w=150&q=80" alt="Galeri 1" class="w-full h-full object-cover">
                            </div>
                            <div class="h-16 rounded-xl overflow-hidden shadow-sm border border-gray-50">
                                <img src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?w=150&q=80" alt="Galeri 2" class="w-full h-full object-cover">
                            </div>
                            <div class="h-16 rounded-xl overflow-hidden shadow-sm border border-gray-50">
                                <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=150&q=80" alt="Galeri 3" class="w-full h-full object-cover">
                            </div>
                            <div class="h-16 rounded-xl overflow-hidden shadow-sm border border-gray-50">
                                <img src="https://images.unsplash.com/photo-1559027615-cd4628902d4a?w=150&q=80" alt="Galeri 4" class="w-full h-full object-cover">
                            </div>
                        @endif
                    </div>
                    <p class="text-[10px] text-gray-400 mt-2 font-semibold">Foto bukti penyaluran donasi terverifikasi kami.</p>
                </div>
            </div>

            {{-- Dampak Statistik (Sejajar / Side-by-side dengan Background Kotak Putih) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 shrink-0 mt-6">
                {{-- Total Donasi --}}
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between transition-all hover:shadow-md hover:-translate-y-0.5 duration-200">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-orange-50 rounded-2xl flex items-center justify-center text-xl text-orange-500 shadow-sm border border-orange-100/30">
                            🍱
                        </div>
                        <div>
                            <p class="text-gray-400 text-[10px] font-bold uppercase tracking-wider">Total Donasi</p>
                            <h3 class="text-lg font-black text-gray-800">{{ $unitBisnis->total_donasi }}</h3>
                        </div>
                    </div>
                </div>

                {{-- Rating --}}
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between transition-all hover:shadow-md hover:-translate-y-0.5 duration-200">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-yellow-50 rounded-2xl flex items-center justify-center text-xl text-yellow-500 shadow-sm border border-yellow-100/30">
                            ⭐
                        </div>
                        <div>
                            <p class="text-gray-400 text-[10px] font-bold uppercase tracking-wider">Reputasi / Rating</p>
                            <h3 class="text-lg font-black text-gray-800">{{ $unitBisnis->rating }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- 3. DAFTAR MAKANAN AKTIF --}}
    <div class="mt-10">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800">Makanan yang Tersedia Saat Ini</h2>
            <span class="text-xs bg-[#eefcf4] text-[#1cb764] px-3 py-1.5 rounded-lg font-bold border border-[#1cb764]/10">
                {{ count($makananAktif) }} Menu Aktif
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
                    <div class="absolute bottom-3 right-3 bg-black/60 text-white text-xs px-2 py-1 rounded-lg backdrop-blur-sm flex items-center gap-1">
                        📍 {{ $makanan->jarak }}
                    </div>
                </div>

                {{-- Konten Card Makanan --}}
                <div class="p-4 flex-1 flex flex-col justify-between">
                    <div>
                        <h4 class="font-bold text-gray-800 group-hover:text-[#1cb764] transition text-sm mb-1 line-clamp-1">
                            {{ $makanan->nama }}
                        </h4>
                        <p class="text-xs text-gray-400 mb-2">🍱 Sisa {{ $makanan->porsi }} Porsi</p>
                    </div>

                    <div class="flex items-center justify-between mt-4 pt-3 border-t border-gray-50">
                        <div>
                            <p class="text-[10px] text-gray-400 font-medium">Harga / Porsi</p>
                            <p class="text-[#1cb764] font-bold text-sm">Rp {{ $makanan->harga }}</p>
                        </div>
                        {{-- Tombol Ambil Makanan --}}
                        <a href="{{ route('user.tes-tombol-profil', $makanan->id) }}" class="bg-[#1cb764] hover:bg-[#158f4e] text-white text-xs font-semibold px-4 py-2 rounded-xl transition text-center">
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