@extends('layouts.admin')

@section('title', 'Daftar Transaksi')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Daftar Transaksi</h1>
            <p class="text-gray-500 font-medium mt-1">Riwayat lengkap seluruh transaksi distribusi makanan di ekosistem ShareBite.</p>
        </div>
        <div>
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center justify-center gap-2 bg-white text-gray-700 px-5 py-3 rounded-2xl font-bold shadow-sm border border-gray-100 hover:bg-gray-50 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Dashboard
            </a>
        </div>
    </div>

    <!-- Filter & Search Card -->
    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
        <form method="GET" action="{{ route('admin.transaksi') }}" class="grid grid-cols-1 md:grid-cols-12 items-end gap-5">
            <!-- Search -->
            <div class="md:col-span-6 space-y-2">
                <label class="text-[10px] font-black text-gray-400 tracking-widest uppercase">Pencarian</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari Mitra, Penerima, atau Makanan..." 
                        class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                </div>
            </div>

            <!-- Status Filter -->
            <div class="md:col-span-4 space-y-2">
                <label class="text-[10px] font-black text-gray-400 tracking-widest uppercase">Status Transaksi</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </span>
                    <select name="status" 
                        class="w-full pl-11 pr-10 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold text-gray-700 appearance-none focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                        <option value="semua" {{ $status == 'semua' || !$status ? 'selected' : '' }}>Semua Status</option>
                        <option value="menunggu_pembayaran" {{ $status == 'menunggu_pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                        <option value="dibayar" {{ $status == 'dibayar' ? 'selected' : '' }}>Dibayar</option>
                        <option value="siap_diambil" {{ $status == 'siap_diambil' ? 'selected' : '' }}>Siap Diambil</option>
                        <option value="selesai" {{ $status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="dibatalkan" {{ $status == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                    <span class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </span>
                </div>
            </div>

            <!-- Buttons -->
            <div class="md:col-span-2 flex items-center justify-end gap-2 pb-0.5">
                <a href="{{ route('admin.transaksi') }}" class="text-sm font-bold text-gray-400 hover:text-gray-600 px-3 py-3 transition">Reset</a>
                <button type="submit" class="bg-[#00502b] hover:bg-[#003d20] text-white px-5 py-3 rounded-2xl font-bold flex items-center justify-center gap-2 shadow-sm transition w-full md:w-auto">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-50 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                        <th class="pb-3 pl-2">Waktu</th>
                        <th class="pb-3">Mitra Penyalur</th>
                        <th class="pb-3">Penerima Manfaat</th>
                        <th class="pb-3">Item Makanan</th>
                        <th class="pb-3">Total Harga</th>
                        <th class="pb-3 pr-2">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm font-semibold text-gray-700">
                    @forelse($transactions as $t)
                    <tr class="hover:bg-gray-50/50 transition duration-150">
                        <td class="py-4 pl-2 text-gray-400 text-xs">
                            {{ $t->waktu_pesan ? $t->waktu_pesan->translatedFormat('d M Y, H:i') : $t->created_at->translatedFormat('d M Y, H:i') }}
                        </td>
                        <td class="py-4 font-bold text-gray-900">
                            {{ $t->unitBisnis ? $t->unitBisnis->nama_usaha : 'Mitra Tidak Dikenal' }}
                        </td>
                        <td class="py-4 text-gray-500">
                            {{ $t->user ? $t->user->name : 'Penerima' }}
                        </td>
                        <td class="py-4 text-gray-500">
                            {{ $t->jumlah_porsi }} Porsi 
                            @if($t->menuAktif && $t->menuAktif->masterMakanan)
                                — {{ $t->menuAktif->masterMakanan->nama_makanan }}
                            @endif
                        </td>
                        <td class="py-4 text-gray-600 font-mono">
                            Rp {{ number_format($t->total_harga, 0, ',', '.') }}
                        </td>
                        <td class="py-4 pr-2">
                            @if($t->status == 'selesai')
                                <span class="inline-flex items-center px-3 py-1 bg-[#eefcf4] text-[#1cb764] border border-[#d2f6e2] rounded-full text-xs font-black tracking-wide uppercase">Selesai</span>
                            @elseif($t->status == 'dibatalkan')
                                <span class="inline-flex items-center px-3 py-1 bg-red-50 text-red-600 border border-red-100 rounded-full text-xs font-black tracking-wide uppercase">Batal</span>
                            @elseif($t->status == 'siap_diambil')
                                <span class="inline-flex items-center px-3 py-1 bg-blue-50 text-blue-600 border border-blue-100 rounded-full text-xs font-black tracking-wide uppercase">Siap Diambil</span>
                            @elseif($t->status == 'dibayar')
                                <span class="inline-flex items-center px-3 py-1 bg-indigo-50 text-indigo-600 border border-indigo-100 rounded-full text-xs font-black tracking-wide uppercase">Dibayar</span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 bg-[#fcf7ee] text-[#b97d10] border border-[#fbeed4] rounded-full text-xs font-black tracking-wide uppercase">Menunggu</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-400 font-medium">Tidak ada transaksi ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div class="mt-6">
            {{ $transactions->links() }}
        </div>
    </div>

</div>
@endsection
