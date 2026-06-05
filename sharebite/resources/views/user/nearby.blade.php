@php
    $hideSearch = true; // Sembunyikan search bar di header utama agar tidak duplikat dengan search bar di halaman ini
@endphp
@extends('layouts.user')

@section('title', 'Kumpulan Donasi Terdekat')

@section('content')
<div class="space-y-6 animate-fade-in pb-12">
    <!-- Header Halaman -->
    <div class="space-y-2">
        <a href="{{ route('user.dashboard') }}" class="inline-flex items-center gap-2 text-xs font-bold text-gray-500 hover:text-[#1cb764] hover:border-[#1cb764] bg-white hover:bg-[#eefcf4] px-4 py-2.5 rounded-full border border-gray-100 shadow-sm transition-all duration-300 w-fit mb-4">
            <svg class="w-4 h-4 text-gray-400 transition-colors" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
            </svg>
            Kembali ke Dashboard
        </a>
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-gray-800">Semua Donasi Terdekat</h1>
                <p class="text-xs text-gray-400 font-medium">Menampilkan makanan yang tersedia di sekitar radius Anda</p>
            </div>
            
            <!-- Pencarian Lokal Halaman -->
            <form action="{{ route('user.nearby') }}" method="GET" class="w-full md:w-80">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari makanan atau toko..." class="w-full bg-white border border-gray-100 rounded-full py-2.5 pl-11 pr-4 text-xs focus:outline-none focus:ring-2 focus:ring-[#1cb764] focus:border-transparent transition-all text-gray-700 font-bold shadow-sm placeholder-gray-400">
                </div>
            </form>
        </div>
    </div>

    <!-- Grid Donasi -->
    @if(is_null(Auth::user()->latitude) || is_null(Auth::user()->longitude))
        <div class="bg-amber-50 border border-amber-200 rounded-[2rem] p-16 text-center space-y-4 shadow-sm">
            <div class="w-16 h-16 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mx-auto">
                <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25s-7.5-4.108-7.5-11.25a7.5 7.5 0 1115 0z" />
                </svg>
            </div>
            <h3 class="font-extrabold text-amber-800 text-base">Lokasi Belum Ditentukan</h3>
            <p class="text-amber-700 font-bold text-sm max-w-sm mx-auto leading-relaxed">
                Tidak bisa menampilkan lokasi terdekat, harap tentukan lokasi terlebih dahulu
            </p>
            <a href="{{ route('user.profile.edit') }}" class="inline-block bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold px-6 py-2.5 rounded-full transition shadow-sm">
                Atur Lokasi
            </a>
        </div>
    @elseif($nearby_donations->isEmpty())
        <div class="bg-white rounded-[2rem] p-16 text-center border border-gray-50 shadow-sm space-y-4">
            <div class="text-6xl">🍲</div>
            <h3 class="font-extrabold text-gray-800 text-base">Tidak Ada Donasi Ditemukan</h3>
            <p class="text-gray-400 font-semibold text-xs max-w-sm mx-auto leading-relaxed">
                Maaf, tidak ada donasi aktif yang sesuai dengan kriteria pencarian atau di dalam radius jangkauan Anda saat ini.
            </p>
            <a href="{{ route('user.nearby') }}" class="inline-block bg-[#1cb764] hover:bg-[#148f4c] text-white text-xs font-bold px-6 py-2.5 rounded-full transition shadow-sm">
                Reset Pencarian
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($nearby_donations as $menu)
                @php
                    $batas = \Carbon\Carbon::parse($menu->batas_pengambilan);
                    $segera_habis = $batas->diffInMinutes(now(), false) > -60;
                @endphp
                <div class="bg-white rounded-[2rem] border border-gray-50 overflow-hidden shadow-sm hover:shadow-md transition duration-300 flex flex-col justify-between">
                    <!-- Gambar Makanan -->
                    <div class="relative h-48 w-full bg-gray-100 overflow-hidden">
                        <img src="{{ $menu->masterMakanan->foto ? asset('storage/' . $menu->masterMakanan->foto) : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=500&q=80' }}" 
                             alt="{{ $menu->masterMakanan->nama_makanan }}" 
                             class="w-full h-full object-cover">
                        
                        <!-- Status Tag -->
                        <span class="absolute top-4 left-4 text-[9px] font-black uppercase tracking-wider px-2.5 py-1 rounded-full text-white {{ $segera_habis ? 'bg-[#e09121]' : 'bg-[#1cb764]' }}">
                            {{ $segera_habis ? 'SEGERA HABIS' : 'TERSEDIA' }}
                        </span>

                        <!-- Jarak Tag -->
                        <span class="absolute bottom-4 right-4 bg-white/90 backdrop-blur-md text-gray-800 text-[10px] font-bold px-2.5 py-1 rounded-full shadow-sm flex items-center gap-1">
                            <svg class="w-3 h-3 text-[#1cb764]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25s-7.5-4.108-7.5-11.25a7.5 7.5 0 1115 0z" />
                            </svg>
                            {{ number_format($menu->computed_distance, 1, ',', '.') }} km
                        </span>
                    </div>

                    <!-- Detail Makanan -->
                    <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                        <div class="space-y-2">
                            <span class="inline-block bg-[#eefcf4] text-[#1cb764] text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded-md">
                                {{ $menu->masterMakanan->kategori ?? 'Umum' }}
                            </span>
                            <h3 class="font-extrabold text-gray-800 text-sm leading-snug line-clamp-2">
                                {{ $menu->masterMakanan->nama_makanan }}
                            </h3>
                            <p class="text-xs text-gray-400 font-semibold flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.33L12 5.5l-7.5 4.83V21h15z" />
                                </svg>
                                {{ $menu->unitBisnis->nama_usaha ?? 'Mitra ShareBite' }}
                            </p>
                        </div>

                        <!-- Baris Bawah -->
                        <div class="flex items-center justify-between pt-3 border-t border-gray-50">
                            <div class="space-y-1 text-left">
                                <div class="text-[10px] text-gray-400 font-bold flex items-center gap-1.5">
                                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $menu->batas_pengambilan->diffForHumans() }}
                                </div>
                                <div class="text-[10px] text-gray-800 font-black flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                    </svg>
                                    {{ $menu->stok_porsi }} Porsi Tersisa
                                </div>
                            </div>
                            <a href="{{ route('user.makanan.pembayaran', $menu->id) }}" 
                               class="bg-[#1cb764] hover:bg-[#148f4c] text-white text-xs font-bold px-5 py-2.5 rounded-xl shadow-sm transition">
                                Ambil
                             </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
