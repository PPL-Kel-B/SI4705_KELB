@php
    $hideSearch = true;
@endphp
@extends('layouts.user')

@section('title', $menu->masterMakanan->nama_makanan)

@section('content')
<!-- Leaflet Map CSS/JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<style>
    .custom-leaflet-marker { background: transparent; border: none; }
</style>

<div class="space-y-4 animate-fade-in pb-12 -mt-2 lg:-mt-6">
    {{-- BREADCRUMB --}}
    <nav class="text-xs font-semibold text-gray-400 mb-6 flex items-center gap-2">
        <a href="{{ route('user.dashboard') }}" class="hover:text-[#1cb764] transition">Dashboard</a> 
        <span>/</span>
        <a href="{{ route('user.nearby') }}" class="hover:text-[#1cb764] transition">Makanan</a> 
        <span>/</span>
        <span class="text-gray-600 font-extrabold">{{ $menu->masterMakanan->nama_makanan }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        {{-- KOLOM KIRI (KONTEN UTAMA) --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- CARD DETAIL MAKANAN UTAMA --}}
            <div class="bg-white rounded-[2rem] border border-gray-50 overflow-hidden relative shadow-sm">
                {{-- Gambar Makanan --}}
                <div class="h-96 relative bg-gray-100">
                    <img src="{{ $menu->masterMakanan->foto ? asset('storage/' . $menu->masterMakanan->foto) : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=1200&q=80' }}" 
                         alt="{{ $menu->masterMakanan->nama_makanan }}" 
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                    
                    {{-- Badges --}}
                    <div class="absolute bottom-6 left-6 right-6 flex justify-between items-end">
                        <div>
                            <span class="bg-[#1cb764] text-white text-[10px] font-black uppercase tracking-wider px-3.5 py-1.5 rounded-full shadow-sm">
                                {{ $menu->masterMakanan->kategori ?? 'Umum' }}
                            </span>
                            <h1 class="text-2xl sm:text-3xl font-black text-white mt-3 drop-shadow-sm">{{ $menu->masterMakanan->nama_makanan }}</h1>
                            <p class="text-green-300 text-xs font-semibold mt-1.5 flex items-center gap-1.5 drop-shadow-sm">
                                🍴 Organic Curator Verified
                            </p>
                        </div>
                        <span class="bg-black/30 backdrop-blur-md text-white text-xs px-4 py-2.5 rounded-2xl font-bold flex flex-col items-center justify-center border border-white/10 min-w-[80px] leading-tight">
                            <span class="text-[9px] font-bold text-gray-300 uppercase tracking-widest leading-none mb-1">Jarak</span>
                            <span>{{ number_format($menu->computed_distance, 1, ',', '.') }} km</span>
                        </span>
                    </div>
                </div>
            </div>

            {{-- 3-COLUMN HORIZONTAL QUICK STATS BAR --}}
            <div class="bg-white rounded-[2rem] border border-gray-50 p-6 shadow-sm">
                <div class="grid grid-cols-3 divide-x divide-gray-100 text-center">
                    <div class="flex items-center justify-center gap-3">
                        <div class="w-10 h-10 bg-[#eefcf4] rounded-xl flex items-center justify-center text-[#1cb764] shrink-0">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                            </svg>
                        </div>
                        <div class="text-left">
                            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider leading-none">Tersedia</p>
                            <p class="text-sm font-extrabold text-gray-800 mt-1 leading-none">{{ $menu->stok_porsi }} Porsi</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-center gap-3">
                        <div class="w-10 h-10 bg-[#fdf4e9] rounded-xl flex items-center justify-center text-[#9a5b15] shrink-0">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="text-left">
                            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider leading-none">Kadaluarsa</p>
                            <p class="text-sm font-extrabold text-[#9a5b15] mt-1 leading-none">{{ $menu->time_remaining }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-center gap-3">
                        <div class="w-10 h-10 bg-[#eefcf4] rounded-xl flex items-center justify-center text-[#1cb764] shrink-0">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5h16.5c.621 0 1.125.504 1.125 1.125v12.75c0 .621-.504 1.125-1.125 1.125H3.75A1.125 1.125 0 012.625 18V5.625c0-.621.504-1.125 1.125-1.125z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 12.75a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15h.008v.008H9V15zm0-6h.008v.008H9V9zm6 0h.008v.008H15V9zm0 6h.008v.008H15V15z" />
                            </svg>
                        </div>
                        <div class="text-left">
                            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider leading-none">Harga / Porsi</p>
                            <p class="text-sm font-extrabold text-[#1cb764] mt-1 leading-none">
                                {{ $menu->is_gratis ? 'Gratis' : 'Rp ' . number_format($menu->harga_jual, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SEGMENT KUNJUNGI PROFIL & TENTANG MAKANAN --}}
            <div class="bg-white rounded-[2rem] border border-gray-50 p-6 shadow-sm space-y-5">
                {{-- Vendor Row --}}
                <div class="flex items-center justify-between gap-4 flex-wrap sm:flex-nowrap">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-gray-50 rounded-2xl overflow-hidden shadow-sm shrink-0 border border-gray-100">
                            <img src="{{ $menu->unitBisnis->foto_bisnis ? asset('storage/' . $menu->unitBisnis->foto_bisnis) : 'https://images.unsplash.com/photo-1552566626-52f8b828add9?w=120&q=80' }}" 
                                 alt="{{ $menu->unitBisnis->nama_usaha }}"
                                 class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h3 class="font-extrabold text-gray-800 text-base leading-tight">{{ $menu->unitBisnis->nama_usaha }}</h3>
                            <p class="text-xs text-gray-500 flex items-start gap-1.5 mt-1 leading-normal">
                                <svg class="h-4 w-4 text-[#1cb764] inline-block mt-0.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg> 
                                <span>{{ $menu->unitBisnis->user->alamat ?? 'Alamat belum diatur' }}</span>
                            </p>
                        </div>
                    </div>
                    
                    {{-- TOMBOL KUNJUNGI PROFIL --}}
                    <a href="{{ route('user.unit-bisnis.show', $menu->unit_bisnis_id) }}" 
                       class="bg-white border-2 border-[#1cb764] text-[#1cb764] hover:bg-[#1cb764] hover:text-white font-extrabold text-xs px-6 py-2.5 rounded-full transition-all duration-300 shadow-sm shrink-0 whitespace-nowrap">
                        Kunjungi Profil
                    </a>
                </div>

                {{-- Divider line --}}
                <hr class="border-t border-gray-100">

                {{-- Tentang Makanan --}}
                <div class="space-y-3">
                    <h3 class="font-black text-gray-800 text-sm">Tentang Makanan Ini</h3>
                    <p class="text-gray-600 text-xs sm:text-sm leading-relaxed">
                        {{ $menu->masterMakanan->deskripsi ?? 'Tidak ada deskripsi makanan.' }}
                    </p>
                    
                    {{-- Tags --}}
                    <div class="flex flex-wrap gap-2 pt-2">
                        <span class="bg-[#eefcf4] text-[#1cb764] text-[10px] px-3.5 py-1.5 rounded-full font-black border border-[#d2f4e1] flex items-center gap-1">
                            <svg class="w-3 h-3 text-[#1cb764]" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            Segar & Higienis
                        </span>
                        <span class="bg-[#eefcf4] text-[#1cb764] text-[10px] px-3.5 py-1.5 rounded-full font-black border border-[#d2f4e1] flex items-center gap-1">
                            <svg class="w-3 h-3 text-[#1cb764]" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            Kemasan Ramah Lingkungan
                        </span>
                        <span class="bg-[#eefcf4] text-[#1cb764] text-[10px] px-3.5 py-1.5 rounded-full font-black border border-[#d2f4e1] flex items-center gap-1">
                            <svg class="w-3 h-3 text-[#1cb764]" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            Sertifikasi Halal
                        </span>
                    </div>
                </div>
            </div>

        </div>

        {{-- KOLOM KANAN (SIDEBAR DETAILS) --}}
        <div class="space-y-6">
            
            {{-- CARD ORDER/PORSI --}}
            <div class="bg-white p-6 rounded-[2rem] border border-gray-50 shadow-sm space-y-6" 
                 x-data="{ 
                     porsi: 1, 
                     harga: {{ $menu->is_gratis ? 0 : (float) $menu->harga_jual }}, 
                     maxStok: {{ $menu->stok_porsi }} 
                 }">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest text-center">PILIH JUMLAH PORSI</h3>
                
                {{-- Counter --}}
                <div class="flex items-center justify-center gap-6 mx-auto">
                    <button @click="if(porsi > 1) porsi--" 
                            class="w-12 h-12 bg-gray-100 hover:bg-gray-200 rounded-xl text-gray-600 font-black transition flex items-center justify-center text-xl shadow-sm cursor-pointer">
                        -
                    </button>
                    <span class="text-3xl font-black text-gray-800 w-12 text-center" x-text="porsi">1</span>
                    <button @click="if(porsi < maxStok) porsi++" 
                            class="w-12 h-12 bg-[#1cb764] hover:bg-[#159f54] text-white rounded-xl font-black transition flex items-center justify-center text-xl shadow-md cursor-pointer">
                        +
                    </button>
                </div>

                {{-- Estimasi Harga --}}
                <div class="text-center py-2 border-t border-b border-gray-50">
                    <p class="text-3xl font-black text-[#1cb764] mt-1">
                        <span x-show="harga > 0">Rp <span x-text="(porsi * harga).toLocaleString('id-ID')">{{ number_format($menu->harga_jual, 0, ',', '.') }}</span></span>
                        <span x-show="harga == 0">Gratis</span>
                    </p>
                </div>

                {{-- Action Button --}}
                <button @click="window.location.href = '{{ route('user.makanan.pembayaran', $menu->id) }}?qty=' + porsi" 
                        class="w-full bg-[#1cb764] hover:bg-[#159f54] text-white font-extrabold text-sm py-4 rounded-2xl transition shadow-md flex items-center justify-center gap-2.5 cursor-pointer">
                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    Ambil Makanan
                </button>
            </div>

            {{-- CARD LOKASI & PETA --}}
            <div class="bg-white p-6 rounded-[2rem] border border-gray-50 shadow-sm space-y-4">
                <div class="flex justify-between items-center">
                    <h3 class="font-black text-gray-800 text-xs tracking-wider uppercase">LOKASI</h3>
                    <a href="https://www.google.com/maps/search/?api=1&query={{ $menu->unitBisnis->lokasi_lat ?? $menu->unitBisnis->user->latitude ?? -6.917464 }},{{ $menu->unitBisnis->lokasi_lng ?? $menu->unitBisnis->user->longitude ?? 107.619123 }}" 
                       target="_blank" 
                       class="text-xs text-[#1cb764] font-extrabold hover:underline">Buka di Maps</a>
                </div>
                
                {{-- Map Box --}}
                <div id="detail-map" class="h-44 rounded-2xl border border-gray-100 z-0"></div>

                {{-- Panduan Penjemputan --}}
                <div class="space-y-1">
                    <p class="text-xs font-black text-gray-800 leading-none">Instruksi Pengambilan:</p>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        Lapor ke staf kasir dan tunjukkan ID ShareBite Anda di konter depan.
                    </p>
                </div>
            </div>

            {{-- CARD JAMINAN KUALITAS --}}
            <div class="bg-[#fcfae6] border border-[#f3ecc2] p-5 rounded-[2rem] flex gap-3 shadow-sm">
                <span class="text-xl text-amber-600 shrink-0">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </span>
                <div>
                    <h4 class="font-black text-[#854d0e] text-xs uppercase tracking-wider">JAMINAN KUALITAS</h4>
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
            <a href="{{ route('user.nearby') }}" class="text-xs text-[#1cb764] font-extrabold hover:underline flex items-center gap-1 transition-all">
                Lihat Semua <span class="text-sm">➔</span>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($similar_items as $item)
            <div class="relative bg-white border border-gray-100 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.02)] hover:shadow-[0_20px_40px_rgba(0,0,0,0.06)] hover:-translate-y-1.5 transition-all duration-300 flex flex-col overflow-hidden h-[275px] group max-w-[310px] w-full mx-auto">
                <!-- Gambar Makanan & Overlay -->
                <a href="{{ route('user.makanan.detail', $item->id) }}" class="relative h-[135px] w-full overflow-hidden bg-gray-50 shrink-0 block">
                    <img src="{{ $item->masterMakanan->foto ? asset('storage/' . $item->masterMakanan->foto) : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=500&q=80' }}" 
                         alt="{{ $item->masterMakanan->nama_makanan }}" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">
                    
                    <!-- Exp Badge (Sudut Kanan Atas) -->
                    <span class="absolute top-3 right-3 bg-white/95 text-[#c2410c] text-[10px] font-black px-3 py-1 rounded-full shadow-sm">
                        Exp {{ str_ireplace(' lagi', '', $item->time_remaining) }}
                    </span>
                </a>

                <!-- Informasi Makanan -->
                <div class="px-5 pb-4 pt-3 flex-1 flex flex-col justify-between">
                    <a href="{{ route('user.makanan.detail', $item->id) }}" class="space-y-1 block">
                        <h3 class="font-extrabold text-gray-900 text-sm leading-snug line-clamp-1 group-hover:text-[#1cb764] transition-colors">
                            {{ $item->masterMakanan->nama_makanan }}
                        </h3>
                    </a>
                    
                    <a href="{{ route('user.unit-bisnis.show', $item->unit_bisnis_id) }}" class="text-[10px] text-gray-400 font-semibold flex items-center gap-1.5 truncate hover:text-[#1cb764] transition-colors mt-0.5">
                        <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M3 9h18M3 9v12a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1V9M3 9L5 3h14l2 6M9 9v4M15 9v4" />
                        </svg>
                        {{ $item->unitBisnis->nama_usaha ?? 'Mitra ShareBite' }}
                    </a>

                    <!-- Baris Bawah (Detail Porsi & Jarak) -->
                    <div class="flex items-center justify-between mt-auto pt-2">
                        <span class="text-xs font-black text-[#1cb764]">
                            {{ $item->stok_porsi }} Porsi
                        </span>
                        <span class="text-xs text-gray-400 font-bold">
                            {{ number_format($item->computed_distance, 1, ',', '.') }} km
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const lat = {{ $menu->unitBisnis->lokasi_lat ?? $menu->unitBisnis->user->latitude ?? -6.917464 }};
    const lng = {{ $menu->unitBisnis->lokasi_lng ?? $menu->unitBisnis->user->longitude ?? 107.619123 }};
    
    const map = L.map('detail-map', { 
        zoomControl: false, 
        dragging: true, 
        scrollWheelZoom: false
    }).setView([lat, lng], 15);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    
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
});
</script>
@endsection
