@extends('layouts.user') {{-- Sesuaikan dengan nama layout utama di projectmu --}}

@section('content')
<div class="container mx-auto px-6 py-8" style="background-color: #F4FBF7; min-height: 100vh;">
    
    {{-- 1. BREADCRUMB (Navigasi Atas) --}}
    <nav class="text-sm text-gray-500 mb-6">
        <span class="hover:underline cursor-pointer">Dashboard</span> / 
        <span class="hover:underline cursor-pointer">Makanan</span> / 
        <span class="text-emerald-600 font-semibold">{{ $unitBisnis->nama }}</span>
    </nav>

    {{-- 2. HERO SECTION / BANNER & PROFIL --}}
    <div class="bg-white rounded-3xl shadow-sm overflow-hidden mb-8 border border-emerald-100/50">
        {{-- Banner Latar Belakang --}}
        <div class="h-48 bg-gradient-to-r from-emerald-500 to-teal-600 relative">
            <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        </div>
        
        {{-- Detail Profil Toko --}}
        <div class="px-8 pb-8 relative flex flex-col md:flex-row items-start md:items-end gap-6 -mt-16">
            {{-- Foto Profil Unit Bisnis --}}
            <div class="w-32 h-32 bg-white p-2 rounded-2xl shadow-md border border-gray-100 z-10">
                <div class="w-full h-full bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 font-bold text-2xl">
                    {{ substr($unitBisnis->nama, 0, 2) }}
                </div>
            </div>
            
            {{-- Nama & Kategori --}}
            <div class="flex-1 z-10 pt-16 md:pt-0">
                <div class="flex items-center gap-2 mb-1">
                    <h1 class="text-2xl font-bold text-gray-800">{{ $unitBisnis->nama }}</h1>
                    <span class="bg-emerald-100 text-emerald-700 text-xs px-2.5 py-1 rounded-full font-medium flex items-center gap-1">
                        ✓ {{ $unitBisnis->kategori }}
                    </span>
                </div>
                <p class="text-gray-500 flex items-center gap-1 text-sm">
                    📍 {{ $unitBisnis->alamat }}
                </p>
            </div>
        </div>
    </div>

    {{-- 3. BODY SECTION (INFO & STATISTIK) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        {{-- Kolom Kiri: Tentang Toko --}}
        <div class="lg:col-span-2 bg-white p-6 rounded-3xl shadow-sm border border-emerald-100/50">
            <h2 class="text-lg font-bold text-gray-800 mb-3">Tentang Unit Bisnis</h2>
            <p class="text-gray-600 text-sm leading-relaxed">
                {{ $unitBisnis->deskripsi }}
            </p>
        </div>

        {{-- Kolom Kanan: Ringkasan Dampak (Sama seperti style card di image_73af67.png) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-4">
            {{-- Total Donasi --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-emerald-100/50 flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-xs font-medium mb-1">Total Donasi Dibagikan</p>
                    <h3 class="text-xl font-bold text-gray-800">{{ $unitBisnis->total_donasi }}</h3>
                </div>
                <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center text-orange-500">
                    🍱
                </div>
            </div>

            {{-- Rating --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-emerald-100/50 flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-xs font-medium mb-1">Reputasi / Rating</p>
                    <h3 class="text-xl font-bold text-gray-800">{{ $unitBisnis->rating }}</h3>
                </div>
                <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center text-yellow-500">
                    ⭐
                </div>
            </div>
        </div>
    </div>

    {{-- 4. DAFTAR MAKANAN AKTIF (Sama seperti grid card di image_73afa5.jpg) --}}
    <div class="mt-12">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800">Makanan yang Tersedia Saat Ini</h2>
            <span class="text-xs bg-emerald-50 text-emerald-600 px-3 py-1.5 rounded-lg font-semibold">
                {{ count($makananAktif) }} Menu Aktif
            </span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($makananAktif as $makanan)
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100 flex flex-col group hover:shadow-md transition">
                {{-- Gambar Makanan --}}
                <div class="h-40 bg-gray-200 relative overflow-hidden">
                    <img src="{{ $makanan->foto }}" alt="{{ $makanan->nama }}" class="w-full h-full object-cover">
                    <div class="absolute top-3 left-3 bg-emerald-600 text-white text-[10px] uppercase font-bold px-2 py-1 rounded-md">
                        Tersedia
                    </div>
                    <div class="absolute bottom-3 right-3 bg-black/60 text-white text-xs px-2 py-1 rounded-lg backdrop-blur-sm flex items-center gap-1">
                        📍 {{ $makanan->jarak }}
                    </div>
                </div>

                {{-- Konten Card Makanan --}}
                <div class="p-4 flex-1 flex flex-col justify-between">
                    <div>
                        <h4 class="font-bold text-gray-800 group-hover:text-emerald-600 transition text-sm mb-1 line-clamp-1">
                            {{ $makanan->nama }}
                        </h4>
                        <p class="text-xs text-gray-400 mb-2">🍱 Sisa {{ $makanan->porsi }} Porsi</p>
                    </div>

                    <div class="flex items-center justify-between mt-4 pt-3 border-t border-gray-50">
                        <div>
                            <p class="text-[10px] text-gray-400 font-medium">Harga / Porsi</p>
                            <p class="text-emerald-600 font-bold text-sm">Rp {{ $makanan->harga }}</p>
                        </div>
                        {{-- Tombol Ambil Makanan --}}
                        <a href="#" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-4 py-2 rounded-xl transition text-center">
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