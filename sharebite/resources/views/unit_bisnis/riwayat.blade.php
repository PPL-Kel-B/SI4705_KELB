@extends('layouts.unit_bisnis')

@section('title', 'Riwayat Pesanan')

@section('content')
<div class="space-y-6">

    <!-- Top Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Card 1: Total Pendapatan -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex justify-between items-start">
            <div>
                <p class="text-sm text-gray-500 font-medium mb-1">Total Pendapatan</p>
                <h3 class="text-3xl font-bold text-gray-900 mb-2">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                <div class="flex items-center text-sm">
                    @if($salesGrowth > 0)
                        <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        <span class="text-green-600 font-medium">+{{ $salesGrowth }}% vs bulan lalu</span>
                    @elseif($salesGrowth < 0)
                        <svg class="w-4 h-4 text-red-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"></path></svg>
                        <span class="text-red-600 font-medium">{{ $salesGrowth }}% vs bulan lalu</span>
                    @else
                        <span class="text-gray-500 font-medium">0% vs bulan lalu</span>
                    @endif
                </div>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-green-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </div>

        <!-- Card 2: Makanan Terselamatkan -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex justify-between items-start">
            <div>
                <p class="text-sm text-gray-500 font-medium mb-1">Makanan Terselamatkan</p>
                <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ $makananTerselamatkan }} <span class="text-xl font-medium text-gray-600">Porsi</span></h3>
                <div class="flex items-center text-sm text-amber-600 font-medium">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    Impact Sosial Tinggi
                </div>
            </div>
            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center text-amber-700">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1.5-3.08a4.001 4.001 0 00-2-7.46V7a2 2 0 014 0v.46a4.001 4.001 0 00-2 7.46zm1-5.46a2 2 0 11-4 0 2 2 0 014 0z" clip-rule="evenodd"></path></svg>
            </div>
        </div>

        <!-- Card 3: Rating Kepuasan -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex justify-between items-start">
            <div>
                <p class="text-sm text-gray-500 font-medium mb-1">Rating Kepuasan</p>
                <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ $ratingKepuasan }}/5</h3>
                <div class="flex items-center text-sm">
                    <div class="flex text-yellow-400 mr-2">
                        @for($i=1; $i<=5; $i++)
                            @if($i <= round($ratingKepuasan))
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            @else
                                <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            @endif
                        @endfor
                    </div>
                    <span class="text-gray-500">({{ number_format($totalUlasan, 0, ',', '.') }} Ulasan)</span>
                </div>
            </div>
            <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Search and Filter Bar -->
    <div class="flex flex-col md:flex-row gap-4 mb-6 items-center">
        <div class="relative flex-grow w-full">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </div>
            <form action="{{ route('unit.riwayat') }}" method="GET" id="searchForm">
                <input type="text" name="search" value="{{ request('search') }}" class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:border-green-500 focus:ring-1 focus:ring-green-500 sm:text-sm" placeholder="Cari transaksi, nama makanan, atau relawan...">
            </form>
        </div>
        <div class="flex gap-2 w-full md:w-auto">
            <button class="flex items-center justify-center px-4 py-3 border border-gray-200 shadow-sm text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 w-full md:w-auto">
                <svg class="mr-2 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Rentang Waktu
                <svg class="ml-2 h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div class="relative w-full md:w-auto">
                <select name="status" form="searchForm" onchange="document.getElementById('searchForm').submit()" class="appearance-none flex items-center justify-center px-4 py-3 pl-10 pr-10 border border-gray-200 shadow-sm text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 w-full md:w-auto cursor-pointer">
                    <option value="">Filter Status</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    <option value="dibayar" {{ request('status') == 'dibayar' ? 'selected' : '' }}>Dibayar</option>
                    <option value="siap_diambil" {{ request('status') == 'siap_diambil' ? 'selected' : '' }}>Siap Diambil</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                </div>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                    <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-900">Detail Transaksi Terbaru</h3>
            <a href="#" class="text-green-600 hover:text-green-700 font-medium text-sm flex items-center">
                Unduh Laporan
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="p-4 pl-6 border-b border-gray-100">TANGGAL</th>
                        <th class="p-4 border-b border-gray-100">NAMA MAKANAN</th>
                        <th class="p-4 border-b border-gray-100">PENERIMA/RELAWAN</th>
                        <th class="p-4 border-b border-gray-100 text-center">PORSI</th>
                        <th class="p-4 border-b border-gray-100">TOTAL</th>
                        <th class="p-4 pr-6 border-b border-gray-100 text-center">STATUS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($transaksi as $item)
                        @php
                            $makananName = $item->menuAktif->masterMakanan->nama_makanan ?? 'Makanan tidak diketahui';
                            $kategori = $item->menuAktif->masterMakanan->kategori ?? 'Umum';
                            $makananFoto = $item->menuAktif->masterMakanan->foto ? asset('storage/'.$item->menuAktif->masterMakanan->foto) : asset('images/default_food.png');
                            
                            $penerimaName = $item->user->name ?? 'User Tidak Diketahui';
                            $inisial = strtoupper(substr($penerimaName, 0, 2));
                            
                            // Tentukan warna inisial (random tapi konsisten berdasarkan nama)
                            $colors = ['bg-orange-500', 'bg-blue-500', 'bg-green-500', 'bg-purple-500', 'bg-pink-500'];
                            $colorIndex = crc32($penerimaName) % count($colors);
                            $avatarColor = $colors[$colorIndex];
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-4 pl-6 align-top">
                                <div class="font-medium text-gray-900">{{ $item->created_at->format('d M Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $item->created_at->format('H:i') }} WIB</div>
                                <div class="text-[10px] text-gray-400 mt-1 uppercase">{{ $item->kode_unik }}</div>
                            </td>
                            <td class="p-4 align-top">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-lg bg-gray-200 overflow-hidden flex-shrink-0">
                                        <img src="{{ $makananFoto }}" alt="{{ $makananName }}" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $makananName }}</div>
                                        <div class="text-xs text-green-600 mt-0.5">{{ $kategori }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4 align-top">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full {{ $avatarColor }} text-white flex items-center justify-center text-xs font-bold flex-shrink-0">
                                        {{ $inisial }}
                                    </div>
                                    <div class="font-medium text-gray-900">{{ $penerimaName }}</div>
                                </div>
                            </td>
                            <td class="p-4 text-center align-top font-medium text-gray-900">
                                {{ $item->jumlah_porsi }}
                            </td>
                            <td class="p-4 align-top">
                                <div class="font-bold text-gray-900">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</div>
                            </td>
                            <td class="p-4 pr-6 text-center align-top">
                                @if($item->status == 'selesai')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-100">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span> Selesai
                                    </span>
                                @elseif($item->status == 'dibayar')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                        <span class="w-1.5 h-1.5 bg-blue-500 rounded-full mr-1.5"></span> Dibayar
                                    </span>
                                @elseif($item->status == 'siap_diambil')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-orange-50 text-orange-700 border border-orange-100">
                                        <span class="w-1.5 h-1.5 bg-orange-500 rounded-full mr-1.5"></span> Menunggu
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-50 text-gray-700 border border-gray-200">
                                        <span class="w-1.5 h-1.5 bg-gray-500 rounded-full mr-1.5"></span> {{ ucfirst($item->status) }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-gray-500">
                                Tidak ada transaksi yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($transaksi->hasPages())
        <div class="p-4 border-t border-gray-100 flex items-center justify-between">
            <div class="text-sm text-gray-500">
                Menampilkan <span class="font-medium text-gray-900">{{ $transaksi->firstItem() }}</span> - <span class="font-medium text-gray-900">{{ $transaksi->lastItem() }}</span> dari <span class="font-medium text-gray-900">{{ $transaksi->total() }}</span> transaksi
            </div>
            
            <div class="flex items-center gap-1">
                @if ($transaksi->onFirstPage())
                    <span class="w-8 h-8 flex items-center justify-center rounded border border-gray-200 text-gray-400 bg-gray-50 cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </span>
                @else
                    <a href="{{ $transaksi->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded border border-gray-200 text-gray-700 hover:bg-gray-50 hover:border-gray-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                @endif

                @foreach ($transaksi->getUrlRange(max(1, $transaksi->currentPage() - 2), min($transaksi->lastPage(), $transaksi->currentPage() + 2)) as $page => $url)
                    @if ($page == $transaksi->currentPage())
                        <span class="w-8 h-8 flex items-center justify-center rounded bg-green-600 text-white font-medium border border-green-600">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center rounded border border-gray-200 text-gray-700 hover:bg-gray-50 hover:border-gray-300">{{ $page }}</a>
                    @endif
                @endforeach

                @if ($transaksi->hasMorePages())
                    <a href="{{ $transaksi->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded border border-gray-200 text-gray-700 hover:bg-gray-50 hover:border-gray-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                @else
                    <span class="w-8 h-8 flex items-center justify-center rounded border border-gray-200 text-gray-400 bg-gray-50 cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </span>
                @endif
            </div>
        </div>
        @else
        <div class="p-4 border-t border-gray-100 flex justify-between items-center text-sm text-gray-500">
            Menampilkan {{ $transaksi->count() }} dari {{ $transaksi->total() }} transaksi
        </div>
        @endif
    </div>

</div>
@endsection
