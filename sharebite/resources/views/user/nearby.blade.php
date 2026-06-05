@php
    $hideSearch = true; // Sembunyikan search bar di header utama agar tidak duplikat dengan search bar di halaman ini
@endphp
@extends('layouts.user')

@section('title', 'Kumpulan Donasi Terdekat')

@section('content')
<div class="space-y-4 animate-fade-in pb-12 -mt-2 lg:-mt-6">
    <!-- Header Halaman -->
    <div class="space-y-1">
        <a href="{{ route('user.dashboard') }}" class="inline-flex items-center gap-2 text-xs font-bold text-gray-500 hover:text-[#1cb764] hover:border-[#1cb764] bg-white hover:bg-[#eefcf4] px-4 py-2.5 rounded-full border border-gray-100 shadow-sm transition-all duration-300 w-fit mb-2">
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
        </div>
    </div>

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
    @else
        <!-- Filter and Content wrapper using AlpineJS -->
        <div x-data="{
            search: '{{ request('search', '') }}',
            selectedCategory: 'Semua',
            selectedDistance: 'all',
            selectedPrice: 'all',
            maxPrice: 1000000,
            items: [
                @foreach($nearby_donations as $menu)
                {
                    nama: '{{ addslashes(strtolower($menu->masterMakanan->nama_makanan)) }}',
                    usaha: '{{ addslashes(strtolower($menu->unitBisnis->nama_usaha ?? '')) }}',
                    kategori: '{{ $menu->masterMakanan->kategori ?? 'Umum' }}',
                    distance: {{ $menu->computed_distance }},
                    is_gratis: {{ $menu->is_gratis ? 'true' : 'false' }},
                    harga: {{ $menu->harga_jual ?? 0 }}
                }{{ !$loop->last ? ',' : '' }}
                @endforeach
            ],
            matches(item) {
                if (!item) return false;
                const matchesSearch = !this.search || item.nama.includes(this.search.toLowerCase()) || item.usaha.includes(this.search.toLowerCase());
                const matchesCategory = this.selectedCategory === 'Semua' || item.kategori === this.selectedCategory;
                const matchesDistance = this.selectedDistance === 'all' || item.distance <= parseFloat(this.selectedDistance);
                const matchesPrice = this.selectedPrice === 'all' || 
                    (this.selectedPrice === 'gratis' && item.is_gratis) || 
                    (this.selectedPrice === 'berbayar' && !item.is_gratis);
                const itemPrice = item.is_gratis ? 0 : item.harga;
                const matchesPriceRange = itemPrice <= parseFloat(this.maxPrice);
                return matchesSearch && matchesCategory && matchesDistance && matchesPrice && matchesPriceRange;
            },
            get hasResults() {
                return this.items.some(item => this.matches(item));
            },
            resetFilters() {
                this.search = '';
                this.selectedCategory = 'Semua';
                this.selectedDistance = 'all';
                this.selectedPrice = 'all';
                this.maxPrice = 1000000;
            }
        }" class="space-y-6">

            <!-- Filter Panel -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100/80 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    <!-- Search Input -->
                    <div class="relative md:col-span-1">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        <input type="text" x-model="search" placeholder="Cari makanan atau toko..." class="w-full bg-gray-50 border-none rounded-full py-2.5 pl-11 pr-4 text-xs focus:outline-none focus:ring-2 focus:ring-[#1cb764] transition-all text-gray-700 font-bold placeholder-gray-400">
                    </div>

                    <!-- Distance Select -->
                    <div class="flex items-center gap-2 bg-gray-50 rounded-full px-4 py-1.5 border-none">
                        <svg class="w-4 h-4 text-gray-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25s-7.5-4.108-7.5-11.25a7.5 7.5 0 1115 0z" />
                        </svg>
                        <select x-model="selectedDistance" class="w-full bg-transparent border-none text-xs font-bold text-gray-700 focus:outline-none cursor-pointer">
                            <option value="all">Semua Jarak</option>
                            <option value="1">&lt; 1 km</option>
                            <option value="3">&lt; 3 km</option>
                            <option value="5">&lt; 5 km</option>
                        </select>
                    </div>

                    <!-- Price Type Select -->
                    <div class="flex items-center gap-2 bg-gray-50 rounded-full px-4 py-1.5 border-none">
                        <svg class="w-4 h-4 text-gray-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <select x-model="selectedPrice" class="w-full bg-transparent border-none text-xs font-bold text-gray-700 focus:outline-none cursor-pointer">
                            <option value="all">Semua Harga</option>
                            <option value="gratis">Gratis</option>
                            <option value="berbayar">Berbayar</option>
                        </select>
                    </div>

                    <!-- Price Range Slider -->
                    <div class="flex flex-col justify-center bg-gray-50 rounded-[1.25rem] px-4 py-1 border-none min-h-[38px]">
                        <div class="flex items-center justify-between text-[9px] font-bold text-gray-500 leading-none mb-0.5">
                            <span>Harga Maks:</span>
                            <span class="text-[#1cb764] font-extrabold" x-text="maxPrice == 1000000 ? 'Semua Harga' : (maxPrice == 0 ? 'Gratis' : 'Rp ' + Number(maxPrice).toLocaleString('id-ID'))"></span>
                        </div>
                        <input type="range" min="0" max="1000000" step="10000" x-model="maxPrice" class="w-full accent-[#1cb764] h-1 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                    </div>
                </div>

                <!-- Category Pills (Horizontal Scroll) -->
                <div class="flex gap-2 overflow-x-auto pb-1 no-scrollbar pt-2 border-t border-gray-50">
                    <template x-for="cat in ['Semua', 'Makanan Berat', 'Cemilan / Makanan Ringan', 'Minuman', 'Dessert']">
                        <button type="button" 
                                @click="selectedCategory = cat" 
                                :class="selectedCategory === cat ? 'bg-[#1cb764] text-white shadow-sm shadow-green-100' : 'bg-gray-50 hover:bg-gray-100 text-gray-600'" 
                                class="text-xs font-bold px-4 py-2 rounded-full transition shrink-0"
                                x-text="cat === 'Cemilan / Makanan Ringan' ? 'Cemilan' : cat">
                        </button>
                    </template>
                </div>
            </div>

            <!-- Grid Donasi -->
            <div x-show="hasResults" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-4">
                @foreach($nearby_donations as $menu)
                    @php
                        $batas = \Carbon\Carbon::parse($menu->batas_pengambilan);
                        $segera_habis = $batas->diffInMinutes(now(), false) > -60;
                        
                        $diffInMins = now()->diffInMinutes($batas, false);
                        if ($diffInMins > 0) {
                            if ($diffInMins < 60) {
                                $timeStr = round($diffInMins) . ' mnt lagi';
                            } else {
                                $timeStr = round($diffInMins / 60) . ' jam lagi';
                            }
                        } else {
                            $timeStr = 'Habis';
                        }
                    @endphp
                    <div x-show="matches(items[{{ $loop->index }}])" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="bg-white rounded-[2rem] border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] hover:shadow-[0_20px_40px_rgba(0,0,0,0.06)] hover:-translate-y-1.5 transition-all duration-300 flex flex-col overflow-hidden h-[275px] group max-w-[310px] w-full mx-auto">
                        
                        <!-- Gambar Makanan & Overlay -->
                        <div class="relative h-[125px] w-full overflow-hidden bg-gray-50 shrink-0">
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

            <!-- Empty State -->
            <div x-show="!hasResults" class="bg-white rounded-[2rem] p-16 text-center border border-gray-100 shadow-sm space-y-4 max-w-lg mx-auto">
                <div class="w-16 h-16 bg-[#f4f7f5] text-[#1cb764] rounded-full flex items-center justify-center mx-auto">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <h3 class="font-extrabold text-gray-800 text-base">Tidak Ada Donasi Ditemukan</h3>
                <p class="text-gray-400 font-semibold text-xs max-w-sm mx-auto leading-relaxed">
                    Maaf, tidak ada donasi aktif yang sesuai dengan kriteria pencarian atau di dalam filter radius jangkauan Anda saat ini.
                </p>
                <button type="button" @click="resetFilters()" class="inline-block bg-[#1cb764] hover:bg-[#148f4c] text-white text-xs font-bold px-6 py-2.5 rounded-full transition shadow-sm cursor-pointer">
                    Reset Filter
                </button>
            </div>

        </div>
    @endif
</div>
@endsection
