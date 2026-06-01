@extends('layouts.user')

@section('title', 'Detail Makanan - Simulasi')

@section('content')
<div class="container mx-auto px-4 py-6" style="background-color: #F4FBF7; min-height: 100vh;">
    
    {{-- BREADCRUMB --}}
    <nav class="text-sm text-gray-500 mb-6 flex items-center gap-2">
        <span class="hover:underline cursor-pointer">Dashboard</span> 
        <span class="text-gray-400">/</span>
        <span class="hover:underline cursor-pointer">Makanan</span> 
        <span class="text-gray-400">/</span>
        <span class="text-emerald-600 font-semibold">{{ $makanan->nama }}</span>
    </nav>

    {{-- BAR SELECTOR MENU REAL DARI DATABASE (POV UNIT BISNIS) --}}
    @if($allActiveMenus->isNotEmpty())
    <div class="bg-[#1cb764] text-white px-6 py-3.5 rounded-3xl mb-6 flex flex-col md:flex-row items-center justify-between gap-4 shadow-sm">
        <div class="flex items-center gap-2">
            <span class="text-lg">🍱</span>
            <span class="text-xs sm:text-sm font-bold text-white">
                Menu Terpilih: <strong class="underline decoration-emerald-200 decoration-2 underline-offset-2">{{ $makanan->nama }}</strong>
            </span>
        </div>
        <div class="flex items-center gap-2">
            <label for="select_menu" class="text-xs font-bold text-emerald-100 whitespace-nowrap">Pilih Menu Lain dari DB:</label>
            <select id="select_menu" onchange="window.location.href='/user/tes-tombol-profil/' + this.value" class="bg-white/10 hover:bg-white/20 border border-white/20 rounded-xl px-3 py-2 text-xs text-white font-bold focus:outline-none focus:ring-2 focus:ring-emerald-300 transition cursor-pointer">
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
            <span class="text-2xl">💡</span>
            <div>
                <p class="font-bold text-sm">Belum Ada Menu Aktif di Database (Menggunakan Data Fallback)</p>
                <p class="text-xs text-amber-700">Kamu sedang melihat data simulasi buah salad. Coba login sebagai <strong>Unit Bisnis</strong>, tambahkan menu baru di menu "Kelola Makanan", lalu kembali ke halaman ini untuk melihat menu real buatanmu!</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('user.unit-bisnis.show', $makanan->unit_bisnis_id) }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-4 py-2 rounded-xl shadow-md transition whitespace-nowrap">
                Buka Profil Langsung
            </a>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- KOLOM KIRI (KONTEN UTAMA) --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- CARD DETAIL MAKANAN UTAMA --}}
            <div class="bg-white rounded-3xl shadow-sm border border-emerald-100/50 overflow-hidden">
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
                            <h1 class="text-3xl font-black text-white mt-3 drop-shadow-sm">{{ $makanan->nama }}</h1>
                            <p class="text-emerald-300 text-xs font-semibold mt-1 flex items-center gap-1">
                                🍳 Organic Curator Verified
                            </p>
                        </div>
                        <span class="bg-black/40 backdrop-blur-md text-white text-xs px-3 py-1.5 rounded-xl font-bold flex items-center gap-1 border border-white/10">
                            📍 {{ $makanan->jarak }}
                        </span>
                    </div>
                </div>

                {{-- Bar Informasi Cepat --}}
                <div class="grid grid-cols-3 divide-x divide-emerald-50 border-b border-emerald-50 bg-emerald-50/20 p-5 text-center">
                    <div class="flex flex-col items-center justify-center p-2">
                        <span class="text-xl mb-1">📦</span>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Tersedia</p>
                        <p class="text-sm font-extrabold text-gray-800">{{ $makanan->stok_porsi }} Porsi</p>
                    </div>
                    <div class="flex flex-col items-center justify-center p-2">
                        <span class="text-xl mb-1">⏱️</span>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Kadaluarsa</p>
                        <p class="text-sm font-extrabold text-amber-600">{{ $makanan->batas_pengambilan }}</p>
                    </div>
                    <div class="flex flex-col items-center justify-center p-2">
                        <span class="text-xl mb-1">💵</span>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Harga / Porsi</p>
                        <p class="text-sm font-extrabold text-emerald-600">
                            {{ $makanan->is_gratis ? 'Gratis' : 'Rp ' . number_format($makanan->harga, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                {{-- SEGMENT KUNJUNGI PROFIL UNIT BISNIS (DIFOKUSKAN DI SINI) --}}
                <div class="p-6 bg-gradient-to-r from-emerald-50/40 via-white to-white border-b border-emerald-50 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center text-emerald-600 font-extrabold text-lg shadow-sm border border-emerald-200/50 uppercase">
                            {{ substr($makanan->nama_usaha, 0, 2) }}
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="font-extrabold text-gray-800 text-base">{{ $makanan->nama_usaha }}</h3>
                                <span class="bg-blue-50 text-blue-600 text-[10px] font-bold px-2 py-0.5 rounded-full">Mitra Terverifikasi</span>
                            </div>
                            <p class="text-xs text-gray-500 flex items-center gap-1 mt-0.5">
                                📍 {{ $makanan->alamat }}
                            </p>
                        </div>
                    </div>
                    
                    {{-- TOMBOL KUNJUNGI PROFIL --}}
                    <a href="{{ route('user.unit-bisnis.show', $makanan->unit_bisnis_id) }}" 
                       class="w-full sm:w-auto bg-white border-2 border-emerald-500 text-emerald-600 hover:bg-emerald-600 hover:text-white font-extrabold text-xs px-6 py-3 rounded-2xl transition-all shadow-sm text-center transform hover:-translate-y-0.5 duration-200">
                        Kunjungi Profil
                    </a>
                </div>

                {{-- TENTANG MAKANAN --}}
                <div class="p-6 space-y-4">
                    <h3 class="font-bold text-gray-800 text-base">Tentang Makanan Ini</h3>
                    <p class="text-gray-600 text-xs sm:text-sm leading-relaxed">
                        {{ $makanan->deskripsi }}
                    </p>
                    
                    {{-- Tags --}}
                    <div class="flex flex-wrap gap-2 pt-2">
                        <span class="bg-emerald-50 text-emerald-700 text-xs px-3 py-1.5 rounded-xl font-medium">✓ Segar & Higienis</span>
                        <span class="bg-emerald-50 text-emerald-700 text-xs px-3 py-1.5 rounded-xl font-medium">✓ Kemasan Ramah Lingkungan</span>
                        <span class="bg-emerald-50 text-emerald-700 text-xs px-3 py-1.5 rounded-xl font-medium">✓ Sertifikasi Halal</span>
                    </div>
                </div>

            </div>

            {{-- MAKANAN SERUPA --}}
            <div>
                <h3 class="font-bold text-gray-800 text-lg mb-4">Makanan Serupa</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-white rounded-2xl p-4 border border-gray-100 flex gap-4">
                        <div class="w-20 h-20 bg-gray-100 rounded-xl overflow-hidden shrink-0">
                            <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=150&q=80" class="w-full h-full object-cover">
                        </div>
                        <div class="flex flex-col justify-between">
                            <div>
                                <h4 class="font-bold text-sm text-gray-800">Smoothie Bowl Berry</h4>
                                <p class="text-[10px] text-gray-400">Sisa 5 Porsi • 1.2 km</p>
                            </div>
                            <p class="text-emerald-600 font-extrabold text-sm">Rp 12.000</p>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl p-4 border border-gray-100 flex gap-4">
                        <div class="w-20 h-20 bg-gray-100 rounded-xl overflow-hidden shrink-0">
                            <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=150&q=80" class="w-full h-full object-cover">
                        </div>
                        <div class="flex flex-col justify-between">
                            <div>
                                <h4 class="font-bold text-sm text-gray-800">Nasi Ayam Bakar</h4>
                                <p class="text-[10px] text-gray-400">Sisa 3 Porsi • 1.5 km</p>
                            </div>
                            <p class="text-emerald-600 font-extrabold text-sm">Donasi (Rp 0)</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- KOLOM KANAN (SIDEBAR DETAILS) --}}
        <div class="space-y-6" x-data="{ porsi: 1, harga: {{ $makanan->harga }}, maxStok: {{ $makanan->stok_porsi }} }">
            
            {{-- CARD ORDER/PORSI --}}
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-emerald-100/50 space-y-6">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider text-center">Pilih Jumlah Porsi</h3>
                
                {{-- Counter --}}
                <div class="flex items-center justify-between bg-gray-50 rounded-2xl p-2 max-w-[200px] mx-auto border border-gray-100">
                    <button @click="if(porsi > 1) porsi--" class="w-10 h-10 bg-white hover:bg-emerald-50 rounded-xl text-gray-600 hover:text-emerald-600 font-bold transition flex items-center justify-center text-lg shadow-sm">
                        -
                    </button>
                    <span class="text-lg font-black text-gray-800" x-text="porsi">1</span>
                    <button @click="if(porsi < maxStok) porsi++" class="w-10 h-10 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold transition flex items-center justify-center text-lg shadow-md">
                        +
                    </button>
                </div>

                {{-- Estimasi Harga --}}
                <div class="text-center py-2 border-t border-b border-gray-50">
                    <p class="text-[11px] text-gray-400 font-semibold">Total Pembayaran</p>
                    <p class="text-2xl font-black text-emerald-600 mt-1">
                        Rp <span x-text="(porsi * harga).toLocaleString('id-ID')">15.000</span>
                    </p>
                </div>

                {{-- Action Button --}}
                <button class="w-full bg-[#1cb764] hover:bg-[#158f4e] text-white font-extrabold text-sm py-4 rounded-2xl transition-all shadow-lg shadow-emerald-100 hover:shadow-emerald-200 flex items-center justify-center gap-2">
                    🛍️ Ambil Makanan
                </button>
            </div>

            {{-- CARD LOKASI & PETA MOCK --}}
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-emerald-100/50 space-y-4">
                <div class="flex justify-between items-center">
                    <h3 class="font-bold text-gray-800 text-sm">Lokasi Penjemputan</h3>
                    <a href="#" class="text-xs text-emerald-600 font-extrabold hover:underline">Buka di Maps</a>
                </div>
                
                {{-- Mock Map Box --}}
                <div class="h-44 bg-emerald-50 rounded-2xl relative overflow-hidden border border-emerald-100/30 flex items-center justify-center">
                    {{-- Grid lines background simulation --}}
                    <div class="absolute inset-0 opacity-15 bg-[radial-gradient(#10b981_1px,transparent_1px)] [background-size:16px_16px]"></div>
                    
                    {{-- Simulated Route Line --}}
                    <svg class="absolute inset-0 w-full h-full opacity-30" xmlns="http://www.w3.org/2000/svg">
                        <path d="M 30,120 Q 80,60 150,110 T 250,50" fill="none" stroke="#10b981" stroke-width="4" stroke-linecap="round" stroke-dasharray="8"/>
                    </svg>

                    {{-- Pin --}}
                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 flex flex-col items-center">
                        <div class="w-8 h-8 bg-emerald-600 text-white rounded-full flex items-center justify-center shadow-md animate-bounce ring-4 ring-emerald-100">
                            📍
                        </div>
                        <span class="mt-1 bg-white/95 backdrop-blur-sm text-[9px] font-black text-gray-800 px-2 py-0.5 rounded-md border border-gray-100 shadow-sm whitespace-nowrap">
                            Lokasi Disini
                        </span>
                    </div>
                </div>

                {{-- Panduan Penjemputan --}}
                <div class="space-y-1">
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Instruksi Pengambilan</p>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        Lapor ke staf kasir dan tunjukkan ID ShareBite Anda di konter depan {{ $makanan->nama_usaha }}.
                    </p>
                </div>
            </div>

            {{-- CARD JAMINAN KUALITAS --}}
            <div class="bg-gradient-to-br from-orange-50/50 to-orange-100/20 p-5 rounded-2xl border border-orange-100/50 flex gap-3">
                <span class="text-xl">🛡️</span>
                <div>
                    <h4 class="font-extrabold text-gray-800 text-xs">JAMINAN KUALITAS</h4>
                    <p class="text-[10px] text-gray-500 leading-relaxed mt-1">
                        Mitra kami telah melewati verifikasi standar keamanan pangan ShareBite untuk menjamin kualitas makanan yang diberikan.
                    </p>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
