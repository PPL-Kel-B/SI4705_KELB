@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-6">

    <!-- Header & Action -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900">Admin Dashboard</h1>
            <p class="text-gray-500 mt-1 font-medium">Ringkasan aktivitas ekosistem <span class="text-[#1cb764] font-bold">ShareBite</span> hari ini.</p>
        </div>
        <button class="bg-[#0a2e1f] text-white px-5 py-3 rounded-xl font-bold flex items-center justify-center gap-2 hover:bg-[#062015] shadow-md transition-all shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Generate Laporan
        </button>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white rounded-3xl p-3 shadow-sm border border-gray-100 flex flex-col lg:flex-row gap-3 items-end">
        <div class="flex-1 w-full bg-gray-50 rounded-2xl p-3 flex flex-col justify-center">
            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1 px-1">Pencarian Global</label>
            <div class="flex items-center gap-2 px-1">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" placeholder="Cari ID, Nama, atau Lokasi..." class="bg-transparent border-none outline-none text-sm w-full text-gray-700 font-medium placeholder-gray-400">
            </div>
        </div>
        
        <div class="w-full lg:w-48 bg-gray-50 rounded-2xl p-3 flex flex-col justify-center">
            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1 px-1">Rentang Waktu</label>
            <div class="flex items-center justify-between px-1">
                <div class="flex items-center gap-2 text-sm font-bold text-gray-700">
                    <svg class="w-4 h-4 text-[#1cb764]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Semua Waktu
                </div>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </div>
        </div>

        <div class="w-full lg:w-56 bg-gray-50 rounded-2xl p-3 flex flex-col justify-center">
            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1 px-1">Kategori Entitas</label>
            <div class="flex items-center justify-between px-1">
                <div class="flex items-center gap-2 text-sm font-bold text-gray-700">
                    <svg class="w-4 h-4 text-[#e89b42]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                    Semua Kategori
                </div>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </div>
        </div>

        <div class="flex gap-2 w-full lg:w-auto h-full pb-1 pr-1 lg:ml-2 shrink-0">
            <button class="px-5 py-3 text-sm font-bold text-gray-500 hover:text-gray-800 transition rounded-xl">Reset</button>
            <button class="bg-[#0a2e1f] text-white px-6 py-3 rounded-2xl text-sm font-bold shadow-md hover:bg-[#062015] transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                Filter Data
            </button>
        </div>
    </div>

    <!-- 4 Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
        <!-- Card 1 -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 relative overflow-hidden">
            <div class="flex justify-between items-start mb-6">
                <div class="w-10 h-10 rounded-xl bg-[#eefcf4] flex items-center justify-center text-[#1cb764]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <span class="bg-[#eefcf4] text-[#1cb764] text-[10px] font-extrabold px-2 py-1 rounded-md flex items-center gap-1">+12% <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg></span>
            </div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Unit Bisnis</p>
            <p class="text-3xl font-black text-gray-900">245</p>
        </div>

        <!-- Card 2 -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 relative overflow-hidden">
            <div class="flex justify-between items-start mb-6">
                <div class="w-10 h-10 rounded-xl bg-[#fcf3e8] flex items-center justify-center text-[#f7b055]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <span class="bg-[#fcf3e8] text-[#f7b055] text-[10px] font-extrabold px-2 py-1 rounded-md flex items-center gap-1">+5% <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg></span>
            </div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Komunitas</p>
            <p class="text-3xl font-black text-gray-900">120</p>
        </div>

        <!-- Card 3 -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 relative overflow-hidden">
            <div class="flex justify-between items-start mb-6">
                <div class="w-10 h-10 rounded-xl bg-[#f0f2f9] flex items-center justify-center text-[#4c54a4]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <span class="bg-[#f0f2f9] text-[#4c54a4] text-[10px] font-extrabold px-2 py-1 rounded-md flex items-center gap-1">+2.4k <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path></svg></span>
            </div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Makanan (Porsi)</p>
            <p class="text-3xl font-black text-gray-900">12,450</p>
        </div>

        <!-- Card 4 -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 relative overflow-hidden">
            <div class="flex justify-between items-start mb-6">
                <div class="w-10 h-10 rounded-xl bg-[#eefcf4] flex items-center justify-center text-[#1cb764]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <span class="bg-[#eefcf4] text-[#1cb764] text-[10px] font-extrabold px-2 py-1 rounded-md flex items-center gap-1">+15% <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg></span>
            </div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Transaksi</p>
            <p class="text-3xl font-black text-gray-900">8,920</p>
        </div>
    </div>

    <!-- 3 Line Charts Placeholder -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <!-- Chart 1: Aktivitas User -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-50 flex flex-col justify-between min-h-[250px]">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="font-extrabold text-gray-900 text-lg">Aktivitas User</h3>
                    <p class="text-[11px] text-gray-400 font-medium">Tren pengguna harian (Jan - Jun)</p>
                </div>
                <span class="bg-[#eefcf4] text-[#1cb764] text-[10px] font-bold px-2 py-1 rounded-full uppercase">Active</span>
            </div>
            <!-- Mock Chart -->
            <div class="relative w-full h-28 mt-6">
                <!-- SVG Line placeholder for Green -->
                <svg viewBox="0 0 100 50" class="w-full h-full overflow-visible" preserveAspectRatio="none">
                    <path d="M0,40 Q15,30 30,45 T60,20 T80,0 T100,20" fill="none" stroke="#1cb764" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                    <circle cx="30" cy="45" r="3" fill="#1cb764" />
                    <circle cx="80" cy="0" r="3" fill="#1cb764" />
                </svg>
            </div>
            <div class="flex justify-between text-[10px] font-bold text-gray-300 uppercase mt-4">
                <span>Jan</span><span>Feb</span><span>Mar</span><span>Apr</span><span>Mei</span><span>Jun</span>
            </div>
        </div>

        <!-- Chart 2: Jumlah Makanan -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-50 flex flex-col justify-between min-h-[250px]">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="font-extrabold text-gray-900 text-lg">Jumlah Makanan</h3>
                    <p class="text-[11px] text-gray-400 font-medium">Distribusi porsi (Jan - Jun)</p>
                </div>
                <span class="bg-[#fcf3e8] text-[#f7b055] text-[10px] font-bold px-2 py-1 rounded-full uppercase">Growth</span>
            </div>
            <!-- Mock Chart -->
            <div class="relative w-full h-28 mt-6">
                <!-- SVG Line placeholder for Brown/Orange -->
                <svg viewBox="0 0 100 50" class="w-full h-full overflow-visible" preserveAspectRatio="none">
                    <path d="M0,45 Q20,35 40,25 T70,40 T100,10" fill="none" stroke="#9a5b15" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                    <circle cx="40" cy="25" r="3" fill="#9a5b15" />
                    <circle cx="100" cy="10" r="3" fill="#9a5b15" />
                </svg>
            </div>
            <div class="flex justify-between text-[10px] font-bold text-gray-300 uppercase mt-4">
                <span>Jan</span><span>Feb</span><span>Mar</span><span>Apr</span><span>Mei</span><span>Jun</span>
            </div>
        </div>

        <!-- Chart 3: Jumlah Transaksi -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-50 flex flex-col justify-between min-h-[250px]">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="font-extrabold text-gray-900 text-lg">Jumlah Transaksi</h3>
                    <p class="text-[11px] text-gray-400 font-medium">Volume distribusi (Jan - Jun)</p>
                </div>
                <span class="bg-[#f0f2f9] text-[#4c54a4] text-[10px] font-bold px-2 py-1 rounded-full uppercase">Reports</span>
            </div>
            <!-- Mock Chart -->
            <div class="relative w-full h-28 mt-6">
                <!-- SVG Line placeholder for Blue -->
                <svg viewBox="0 0 100 50" class="w-full h-full overflow-visible" preserveAspectRatio="none">
                    <path d="M0,40 Q25,40 40,25 T60,5 T70,40 T90,5 T100,10" fill="none" stroke="#4c54a4" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                    <circle cx="40" cy="25" r="3" fill="#4c54a4" />
                    <circle cx="90" cy="5" r="3" fill="#4c54a4" />
                </svg>
            </div>
            <div class="flex justify-between text-[10px] font-bold text-gray-300 uppercase mt-4">
                <span>Jan</span><span>Feb</span><span>Mar</span><span>Apr</span><span>Mei</span><span>Jun</span>
            </div>
        </div>
    </div>

    <!-- Bottom Section: Table & Donut Chart -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        
        <!-- Table: Transaksi Terbaru -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-6 shadow-sm border border-gray-50 overflow-hidden flex flex-col">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-extrabold text-gray-900 text-lg">Transaksi Terbaru</h3>
                <a href="#" class="text-[#1cb764] text-[11px] font-bold uppercase tracking-widest hover:underline flex items-center gap-1">
                    Lihat Semua
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </a>
            </div>
            
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left border-collapse min-w-[600px]">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest w-1/4">Mitra Penyalur</th>
                            <th class="py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest w-1/4">Penerima Manfaat</th>
                            <th class="py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest w-1/4">Item Makanan</th>
                            <th class="py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Status</th>
                            <th class="py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-50">
                        <!-- Row 1 -->
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 pr-4 font-bold text-gray-800">Bakery Lestari</td>
                            <td class="py-4 pr-4 font-medium text-gray-600">Panti Asuhan Kasih</td>
                            <td class="py-4 pr-4 font-medium text-gray-600">50 Paket Roti Segar</td>
                            <td class="py-4 px-2 text-center">
                                <span class="bg-[#eefcf4] text-[#1cb764] px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest">Selesai</span>
                            </td>
                            <td class="py-4 text-right text-gray-400 font-medium text-xs">2 mnt lalu</td>
                        </tr>
                        <!-- Row 2 -->
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 pr-4 font-bold text-gray-800">Hotel Nusantara</td>
                            <td class="py-4 pr-4 font-medium text-gray-600">Relawan Jakarta Pusat</td>
                            <td class="py-4 pr-4 font-medium text-gray-600">120 Box Nasi Bento</td>
                            <td class="py-4 px-2 text-center">
                                <span class="bg-[#fcf3e8] text-[#f7b055] px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest">Proses</span>
                            </td>
                            <td class="py-4 text-right text-gray-400 font-medium text-xs">15 mnt lalu</td>
                        </tr>
                        <!-- Row 3 -->
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 pr-4 font-bold text-gray-800">Warung Ijo</td>
                            <td class="py-4 pr-4 font-medium text-gray-600">Komunitas Marjinal</td>
                            <td class="py-4 pr-4 font-medium text-gray-600">25 Bungkus Nasi Rames</td>
                            <td class="py-4 px-2 text-center">
                                <span class="bg-[#eefcf4] text-[#1cb764] px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest">Selesai</span>
                            </td>
                            <td class="py-4 text-right text-gray-400 font-medium text-xs">45 mnt lalu</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Donut Chart: Status Distribusi -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-50 flex flex-col">
            <h3 class="font-extrabold text-gray-900 text-lg">Status Distribusi</h3>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-8">Real-time Stats</p>
            
            <div class="flex-1 flex flex-col items-center justify-center relative">
                <!-- Simple Donut using SVG -->
                <svg viewBox="0 0 36 36" class="w-48 h-48 drop-shadow-md">
                    <!-- Background Circle -->
                    <path class="text-gray-100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="4" />
                    <!-- Foreground Circle (82%) -->
                    <path class="text-[#0a2e1f]" stroke-dasharray="82, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" />
                </svg>
                
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none mt-2">
                    <span class="text-4xl font-black text-gray-900">82%</span>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Success</span>
                </div>
            </div>

            <div class="flex justify-between items-center mt-8 px-4 border-t border-gray-50 pt-6">
                <div class="text-center">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Done</p>
                    <p class="text-sm font-black text-gray-800">7.3k</p>
                </div>
                <div class="text-center border-l border-gray-100 pl-4">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Proc</p>
                    <p class="text-sm font-black text-gray-800">1.2k</p>
                </div>
                <div class="text-center border-l border-gray-100 pl-4">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Fail</p>
                    <p class="text-sm font-black text-gray-800">0.4k</p>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
