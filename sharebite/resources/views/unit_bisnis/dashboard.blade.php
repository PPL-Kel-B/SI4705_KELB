@extends('layouts.unit_bisnis')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">

    <!-- Hero Banner -->
    <div class="relative bg-[#0a2e1f] rounded-3xl p-8 lg:p-12 text-white overflow-hidden shadow-sm">
        <!-- Background Image with Overlay -->
        <div class="absolute inset-0 z-0">
            <!-- Simulated background image with CSS gradient overlay -->
            <div class="absolute inset-0 bg-gradient-to-r from-[#0a2e1f] via-[#0a2e1f]/90 to-transparent z-10"></div>
            <!-- If there was a real image, it would be an img tag here. For now using a placeholder background style -->
            <div class="absolute inset-0 opacity-40 bg-[url('https://images.unsplash.com/photo-1556910103-1c02745a872e?q=80&w=2070&auto=format&fit=crop')] bg-cover bg-center"></div>
        </div>
        
        <div class="relative z-10 max-w-2xl">
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
            <p class="text-xs font-semibold text-gray-500 mb-1">Total Pesanan</p>
            <p class="text-2xl font-extrabold text-gray-800">1.284</p>
        </div>

        <!-- Card 2 -->
        <div class="bg-white p-5 rounded-3xl shadow-sm border-b-4 border-[#f7b055]">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-xl bg-[#fcf3e8] text-[#f7b055] flex items-center justify-center">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                </div>
                <span class="bg-[#fcf3e8] text-[#f7b055] text-xs font-bold px-2 py-1 rounded-md">Teratas</span>
            </div>
            <p class="text-xs font-semibold text-gray-500 mb-1">Rating Resto</p>
            <p class="text-2xl font-extrabold text-gray-800">4.9<span class="text-sm text-gray-400">/5.0</span></p>
        </div>

        <!-- Card 3 -->
        <div class="bg-white p-5 rounded-3xl shadow-sm border-b-4 border-[#1cb764]">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-xl bg-[#eefcf4] text-[#1cb764] flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <span class="bg-[#eefcf4] text-[#1cb764] text-xs font-bold px-2 py-1 rounded-md">Bulan Ini</span>
            </div>
            <p class="text-xs font-semibold text-gray-500 mb-1">Total Pendapatan</p>
            <p class="text-2xl font-extrabold text-gray-800">Rp 4.2M</p>
        </div>

        <!-- Card 4 -->
        <div class="bg-white p-5 rounded-3xl shadow-sm border-b-4 border-[#1cb764]">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-xl bg-[#eefcf4] text-[#1cb764] flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
                <span class="bg-[#eefcf4] text-[#1cb764] text-xs font-bold px-2 py-1 rounded-md">Stabil</span>
            </div>
            <p class="text-xs font-semibold text-gray-500 mb-1">Sales Growth</p>
            <p class="text-2xl font-extrabold text-gray-800">24.5%</p>
        </div>
    </div>

    <!-- Main Content 2 Columns -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Graph Section (Spans 2 cols on large screens, though in image it's full width before the split? Ah, looking at the image, Graph is full width, then under it is Pesanan Masuk (left 2/3) and Kelola Menu (right 1/3) -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded-3xl p-6 shadow-sm">
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <h2 class="text-xl font-extrabold text-gray-800">Jumlah Penjualan Per Bulan</h2>
                        <p class="text-xs text-gray-500 mt-1">Volume porsi makanan yang berhasil diredistribusikan</p>
                    </div>
                    <div class="flex gap-2">
                        <button class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-bold">2023</button>
                        <button class="px-3 py-1 bg-[#0a2e1f] text-white rounded-full text-xs font-bold">2024</button>
                    </div>
                </div>

                <!-- Custom Bar Chart HTML representation -->
                <div class="flex items-end justify-between h-48 px-2 mt-4 gap-2 sm:gap-4">
                    <!-- Jan -->
                    <div class="w-full flex flex-col items-center">
                        <span class="text-[10px] font-bold text-gray-800 mb-2">90</span>
                        <div class="w-full bg-[#e9eeeb] rounded-t-sm h-[20%] transition-all hover:opacity-80"></div>
                        <span class="text-[10px] text-gray-500 mt-3 font-semibold">Jan</span>
                    </div>
                    <!-- Feb -->
                    <div class="w-full flex flex-col items-center">
                        <span class="text-[10px] font-bold text-gray-800 mb-2">120</span>
                        <div class="w-full bg-[#e9eeeb] rounded-t-sm h-[30%] transition-all hover:opacity-80"></div>
                        <span class="text-[10px] text-gray-500 mt-3 font-semibold">Feb</span>
                    </div>
                    <!-- Mar -->
                    <div class="w-full flex flex-col items-center">
                        <span class="text-[10px] font-bold text-gray-800 mb-2">376</span>
                        <div class="w-full bg-[#0a2e1f] rounded-t-sm h-[90%] transition-all hover:opacity-80 shadow-md"></div>
                        <span class="text-[10px] font-bold text-[#1cb764] mt-3">Mar</span>
                    </div>
                    <!-- Apr -->
                    <div class="w-full flex flex-col items-center">
                        <span class="text-[10px] font-bold text-gray-800 mb-2">239</span>
                        <div class="w-full bg-[#e9eeeb] rounded-t-sm h-[60%] transition-all hover:opacity-80"></div>
                        <span class="text-[10px] text-gray-500 mt-3 font-semibold">Apr</span>
                    </div>
                    <!-- Mei -->
                    <div class="w-full flex flex-col items-center">
                        <span class="text-[10px] font-bold text-gray-800 mb-2">109</span>
                        <div class="w-full bg-[#e9eeeb] rounded-t-sm h-[25%] transition-all hover:opacity-80"></div>
                        <span class="text-[10px] text-gray-500 mt-3 font-semibold">Mei</span>
                    </div>
                    <!-- Jun -->
                    <div class="w-full flex flex-col items-center">
                        <span class="text-[10px] font-bold text-gray-800 mb-2">187</span>
                        <div class="w-full bg-[#e9eeeb] rounded-t-sm h-[45%] transition-all hover:opacity-80"></div>
                        <span class="text-[10px] text-gray-500 mt-3 font-semibold">Jun</span>
                    </div>
                    <!-- Jul -->
                    <div class="w-full flex flex-col items-center">
                        <span class="text-[10px] font-bold text-gray-800 mb-2">250</span>
                        <div class="w-full bg-[#e9eeeb] rounded-t-sm h-[65%] transition-all hover:opacity-80"></div>
                        <span class="text-[10px] text-gray-500 mt-3 font-semibold">Jul</span>
                    </div>
                    <!-- Agu -->
                    <div class="w-full flex flex-col items-center">
                        <span class="text-[10px] font-bold text-gray-800 mb-2">199</span>
                        <div class="w-full bg-[#e9eeeb] rounded-t-sm h-[50%] transition-all hover:opacity-80"></div>
                        <span class="text-[10px] text-gray-500 mt-3 font-semibold">Agu</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Left List (Pesanan Masuk) -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl p-6 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-extrabold text-gray-800">Pesanan Masuk</h2>
                    <a href="#" class="text-[#1cb764] text-xs font-bold hover:underline flex items-center gap-1">
                        Lihat Semua 
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </div>

                <div class="space-y-4">
                    <!-- Item 1 -->
                    <div class="flex items-center justify-between p-3 bg-[#F4F8F6] rounded-2xl border border-gray-50">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-gray-200 rounded-xl shrink-0 overflow-hidden">
                                <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=150&auto=format&fit=crop" alt="Food" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h3 class="font-bold text-sm text-gray-800">Nasi Kotak Ayam Bakar</h3>
                                <p class="text-xs text-gray-500 mt-0.5">5 Porsi &bull; Dipesan 10 menit lalu</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="bg-[#fcf3e8] text-[#f7b055] text-[10px] font-bold px-3 py-1.5 rounded-full uppercase tracking-wider">Menunggu Diambil</span>
                            <button class="text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"></path></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Item 2 -->
                    <div class="flex items-center justify-between p-3 bg-[#F4F8F6] rounded-2xl border border-gray-50">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-gray-200 rounded-xl shrink-0 overflow-hidden">
                                <img src="https://images.unsplash.com/photo-1484723091791-c0e7e53a3988?q=80&w=150&auto=format&fit=crop" alt="Food" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h3 class="font-bold text-sm text-gray-800">Aneka Kue Basah (Mix)</h3>
                                <p class="text-xs text-gray-500 mt-0.5">12 Porsi &bull; Dipesan 25 menit lalu</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="bg-[#eefcf4] text-[#1cb764] text-[10px] font-bold px-3 py-1.5 rounded-full uppercase tracking-wider">Sudah Diambil</span>
                            <button class="text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"></path></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Item 3 -->
                    <div class="flex items-center justify-between p-3 bg-[#F4F8F6] rounded-2xl border border-gray-50">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-gray-200 rounded-xl shrink-0 overflow-hidden">
                                <img src="https://images.unsplash.com/photo-1540420773420-3366772f4999?q=80&w=150&auto=format&fit=crop" alt="Food" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h3 class="font-bold text-sm text-gray-800">Soto Betawi Spesial</h3>
                                <p class="text-xs text-gray-500 mt-0.5">3 Porsi &bull; Dipesan 1 jam lalu</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="bg-[#fcf3e8] text-[#f7b055] text-[10px] font-bold px-3 py-1.5 rounded-full uppercase tracking-wider">Menunggu Diambil</span>
                            <button class="text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right List (Kelola Menu Aktif) -->
        <div>
            <div class="bg-white rounded-3xl p-6 shadow-sm h-full flex flex-col">
                <h2 class="text-xl font-extrabold text-gray-800 mb-6">Kelola Menu Aktif</h2>
                
                <div class="space-y-4 flex-1">
                    <!-- Status 1 -->
                    <div class="bg-[#F4F8F6] border border-gray-100 rounded-2xl p-4">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-[#1cb764]"></div>
                                <h3 class="font-bold text-sm text-[#0a2e1f] uppercase tracking-wide">Tersedia</h3>
                            </div>
                            <span class="text-xs font-medium text-gray-500"><strong class="text-gray-800 text-sm">8</strong> Menu</span>
                        </div>
                        <p class="text-[11px] text-gray-500 leading-relaxed">Menu dalam kondisi stok aman. Pastikan label kedaluwarsa tetap terupdate.</p>
                    </div>

                    <!-- Status 2 -->
                    <div class="bg-[#fcf3e8] border border-orange-100 rounded-2xl p-4">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-[#f7b055]"></div>
                                <h3 class="font-bold text-sm text-[#9a5b15] uppercase tracking-wide">Segera Habis</h3>
                            </div>
                            <span class="text-xs font-medium text-orange-600"><strong class="text-orange-700 text-sm">2</strong> Menu</span>
                        </div>
                        <p class="text-[11px] text-orange-600/80 leading-relaxed">Persediaan menipis. Segera hapus atau update menu jika stok di dapur telah kosong.</p>
                    </div>
                </div>

                <button class="w-full mt-6 bg-[#0a2e1f] text-white font-bold text-sm py-3.5 rounded-xl hover:bg-[#062015] transition shadow-md flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Tambah Menu Baru
                </button>
            </div>
        </div>

    </div>

</div>
@endsection
