@extends('layouts.user')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Hero Banner -->
    <div class="bg-[#1cb764] rounded-3xl p-8 lg:p-12 text-white relative overflow-hidden shadow-md">
        <!-- Decoration background (optional) -->
        <div class="absolute right-0 bottom-0 opacity-20 pointer-events-none">
            <svg width="400" height="400" viewBox="0 0 24 24" fill="currentColor">
                <circle cx="12" cy="12" r="10" />
            </svg>
        </div>
        
        <div class="relative z-10 max-w-2xl">
            <div class="inline-block bg-white/20 backdrop-blur-sm px-4 py-1.5 rounded-full text-sm font-bold tracking-wide uppercase mb-6">
                Dashboard Relawan
            </div>
            <h1 class="text-4xl lg:text-5xl font-extrabold mb-4">
                Halo, {{ Auth::check() ? explode(' ', Auth::user()->name)[0] : 'Munadhils' }}!
            </h1>
            <p class="text-lg lg:text-xl font-medium text-white/90">
                Siap menyelamatkan makanan dan berbagi<br class="hidden sm:block"> kebaikan hari ini?
            </p>
        </div>
    </div>

    <!-- Placeholder Content to match image's layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Content -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Stat Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-50 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-500 mb-1">Porsi Diambil</p>
                        <p class="text-3xl font-extrabold text-[#9a5b15]">124</p>
                    </div>
                    <div class="bg-[#fcf3e8] p-3 rounded-full text-[#e89b42]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
                
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-50 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-500 mb-1">Makanan Terselamatkan</p>
                        <p class="text-3xl font-extrabold text-[#1cb764]">12.5 <span class="text-lg text-gray-400">kg</span></p>
                    </div>
                    <div class="bg-[#eefcf4] p-3 rounded-full text-[#1cb764]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-50 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-500 mb-1">CO₂ Dihemat</p>
                        <p class="text-3xl font-extrabold text-[#4c54a4]">31.2 <span class="text-lg text-gray-400">kg</span></p>
                    </div>
                    <div class="bg-[#f0f2f9] p-3 rounded-full text-[#4c54a4]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Donasi Terdekat -->
            <div>
                <div class="flex items-end justify-between mb-4">
                    <div>
                        <h2 class="text-2xl font-extrabold text-gray-800">Donasi Terdekat</h2>
                        <p class="text-gray-500 font-medium mt-1">Tersedia untuk segera didistribusikan</p>
                    </div>
                    <a href="#" class="text-[#1cb764] font-bold text-sm hover:underline flex items-center gap-1">
                        Lihat Semua
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Placeholder Card 1 -->
                    <div class="bg-white rounded-3xl p-4 shadow-sm border border-gray-50">
                        <div class="bg-gray-100 h-40 rounded-2xl mb-4 flex items-center justify-center text-gray-400 font-medium">
                            [Gambar Makanan]
                        </div>
                        <h3 class="font-bold text-lg text-gray-800">Paket Salad Buah Segar</h3>
                        <p class="text-sm text-gray-500 flex items-center gap-1 mt-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                            Healthy Garden Bistro
                        </p>
                        <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
                            <div class="flex gap-3 text-xs font-semibold text-gray-500">
                                <span class="bg-orange-50 text-orange-600 px-2 py-1 rounded-md flex items-center gap-1">⏱ 20 mnt lagi</span>
                                <span class="bg-gray-50 text-gray-600 px-2 py-1 rounded-md flex items-center gap-1">📦 3 Porsi</span>
                            </div>
                            <button class="bg-[#1cb764] text-white px-5 py-2 rounded-xl font-bold text-sm hover:bg-[#19a55a] transition">Ambil</button>
                        </div>
                    </div>

                    <!-- Placeholder Card 2 -->
                    <div class="bg-white rounded-3xl p-4 shadow-sm border border-gray-50">
                        <div class="bg-gray-100 h-40 rounded-2xl mb-4 flex items-center justify-center text-gray-400 font-medium">
                            [Gambar Makanan]
                        </div>
                        <h3 class="font-bold text-lg text-gray-800">Nasi Kotak Ayam Bakar</h3>
                        <p class="text-sm text-gray-500 flex items-center gap-1 mt-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                            Warung Sedap Malam
                        </p>
                        <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
                            <div class="flex gap-3 text-xs font-semibold text-gray-500">
                                <span class="bg-orange-50 text-orange-600 px-2 py-1 rounded-md flex items-center gap-1">⏱ 3 Jam lagi</span>
                                <span class="bg-gray-50 text-gray-600 px-2 py-1 rounded-md flex items-center gap-1">📦 9 Porsi</span>
                            </div>
                            <button class="bg-[#1cb764] text-white px-5 py-2 rounded-xl font-bold text-sm hover:bg-[#19a55a] transition">Ambil</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Content -->
        <div class="space-y-6">
            <!-- Aktivitas Terakhir -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-50">
                <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#1cb764]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Aktivitas Terakhir
                </h3>
                
                <div class="space-y-6 relative before:absolute before:inset-0 before:ml-4 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gray-100">
                    <!-- Item 1 -->
                    <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                        <div class="flex items-center justify-center w-8 h-8 rounded-full bg-[#eefcf4] border-2 border-white shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 absolute left-0 md:left-1/2 -translate-x-1/2 z-10"></div>
                        <div class="w-[calc(100%-2.5rem)] md:w-[calc(50%-2.5rem)] ml-10 md:ml-0 p-4 rounded-xl bg-white border border-gray-50 shadow-sm">
                            <h4 class="font-bold text-gray-800 text-sm">Donasi Selesai</h4>
                            <p class="text-xs text-gray-500 mt-1">Berhasil mengantar 5 Nasi Kotak ke Panti Asuhan Kasih.</p>
                            <span class="text-[10px] font-bold text-gray-400 mt-2 block uppercase tracking-wider">2 Jam Yang Lalu</span>
                        </div>
                    </div>
                </div>
                
                <button class="w-full mt-6 bg-[#f4f7f5] text-gray-600 font-bold text-sm py-3 rounded-xl hover:bg-gray-100 transition">Lihat Semua Aktivitas</button>
            </div>

            <!-- Map Card -->
            <div class="bg-white rounded-3xl p-2 shadow-sm border border-gray-50 overflow-hidden relative h-48">
                <div class="absolute inset-0 bg-gray-200 flex items-center justify-center text-gray-400 font-medium">
                    [Peta Radius Anda]
                </div>
                <div class="absolute bottom-4 left-4 z-10 text-white drop-shadow-md">
                    <p class="text-xs font-semibold">Radius Anda</p>
                    <p class="text-lg font-bold">Sapan Majalaya</p>
                    <p class="text-[10px] flex items-center gap-1 mt-1">
                        <span class="w-2 h-2 rounded-full bg-[#1cb764]"></span>
                        12 Donatur Aktif Sekitar
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
