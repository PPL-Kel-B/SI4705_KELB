@extends('layouts.unit_bisnis')

@section('title', 'Detail Riwayat Pesanan')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-[16px] relative mb-6 font-bold shadow-sm flex items-center gap-2" role="alert">
            <svg class="w-5 h-5 text-green-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Header -->
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('unit.riwayat') }}" class="w-10 h-10 bg-green-50 rounded-full flex items-center justify-center text-green-600 hover:bg-green-100 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Detail Riwayat Donasi</h1>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-3xl overflow-hidden shadow-sm border border-gray-100">
        <div class="flex flex-col md:flex-row">
            <!-- Image Section -->
            <div class="w-full md:w-1/2 h-64 md:h-auto relative">
                @php
                    $makananFoto = $pesanan->menuAktif->masterMakanan->foto ? asset('storage/'.$pesanan->menuAktif->masterMakanan->foto) : asset('images/default_food.png');
                @endphp
                <img src="{{ $makananFoto }}" alt="Food Image" class="w-full h-full object-cover">
                <div class="absolute top-4 left-4">
                    @if($pesanan->status == 'selesai')
                        <span class="px-3 py-1 bg-green-600 text-white text-xs font-bold rounded-full uppercase tracking-wider">SELESAI</span>
                    @elseif($pesanan->status == 'dibayar')
                        <span class="px-3 py-1 bg-blue-600 text-white text-xs font-bold rounded-full uppercase tracking-wider">DIBAYAR</span>
                    @elseif($pesanan->status == 'siap_diambil')
                        <span class="px-3 py-1 bg-orange-600 text-white text-xs font-bold rounded-full uppercase tracking-wider">SIAP DIAMBIL</span>
                    @else
                        <span class="px-3 py-1 bg-gray-600 text-white text-xs font-bold rounded-full uppercase tracking-wider">{{ strtoupper($pesanan->status) }}</span>
                    @endif
                </div>
            </div>

            <!-- Detail Section -->
            <div class="w-full md:w-1/2 p-8 flex flex-col justify-center bg-white relative">
                <div class="absolute top-8 right-8">
                    <p class="text-xl font-bold text-green-700">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</p>
                </div>
                
                <h2 class="text-3xl font-extrabold text-gray-900 mb-2 pr-24">{{ $pesanan->menuAktif->masterMakanan->nama_makanan ?? 'Tidak Diketahui' }}</h2>
                <div class="flex items-center text-gray-500 mb-8">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    <span class="font-medium">{{ $unitBisnis->nama_usaha ?? 'Unit Bisnis' }}</span>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-8">
                    <div class="bg-gray-50 p-4 rounded-2xl">
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Tanggal Pesanan</p>
                        <p class="font-semibold text-gray-900">{{ $pesanan->created_at->format('d F Y') }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-2xl">
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Porsi</p>
                        <p class="font-semibold text-gray-900">{{ $pesanan->jumlah_porsi }} Porsi</p>
                    </div>
                </div>

                <div class="flex items-center justify-between border-t border-gray-100 pt-6">
                    <div class="flex items-center text-green-600 font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        @if($pesanan->status == 'selesai')
                            Transaksi Selesai
                        @else
                            {{ ucfirst($pesanan->status) }}
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500">ID:</p>
                        <p class="text-sm font-medium text-gray-900">{{ $pesanan->kode_unik ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Sections -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        
        <!-- Bukti Berbagi Section -->
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
            <h3 class="text-lg font-bold text-gray-900 flex items-center mb-6">
                <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                Bukti Berbagi
            </h3>

            @if($pesanan->buktiDonasis && $pesanan->buktiDonasis->count() > 0)
                <div class="grid grid-cols-1 gap-4">
                    @foreach($pesanan->buktiDonasis as $bukti)
                        <div class="rounded-2xl overflow-hidden border border-gray-100 aspect-video relative group">
                            <img src="{{ asset('storage/' . $bukti->foto) }}" alt="Bukti Donasi" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <a href="{{ asset('storage/' . $bukti->foto) }}" target="_blank" class="px-4 py-2 bg-white text-gray-900 rounded-lg text-sm font-medium shadow-sm hover:bg-gray-50">
                                    Lihat Penuh
                                </a>
                            </div>
                        </div>
                        <p class="text-xs text-center text-gray-500 mt-2">Diunggah pada {{ $bukti->created_at->format('d M Y, H:i') }}</p>
                    @endforeach
                </div>
            @else
                <div class="border-2 border-dashed border-gray-200 rounded-3xl p-8 flex flex-col items-center justify-center text-center h-48 bg-gray-50/50">
                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <p class="text-sm font-medium text-gray-900 mb-1">Belum Ada Bukti</p>
                    <p class="text-xs text-gray-500">Penerima/relawan belum mengunggah foto bukti donasi untuk pesanan ini.</p>
                </div>
            @endif
        </div>

        <!-- Rating Section -->
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
            <h3 class="text-lg font-bold text-gray-900 flex items-center mb-6">
                <svg class="w-5 h-5 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                Ulasan & Rating
            </h3>

            @if($pesanan->rating)
                <div class="mb-4">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">RATING DARI PENERIMA</p>
                    <div class="flex text-yellow-400">
                        @for($i=1; $i<=5; $i++)
                            @if($i <= $pesanan->rating->nilai)
                                <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            @else
                                <svg class="w-6 h-6 text-gray-200 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            @endif
                        @endfor
                    </div>
                </div>

                <div class="mb-4">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">KOMENTAR</p>
                    <div class="bg-gray-50 rounded-2xl p-4">
                        <p class="text-gray-700 italic">"{{ $pesanan->rating->catatan_pengalaman ?? $pesanan->rating->komentar ?? 'Tidak ada komentar.' }}"</p>
                    </div>
                </div>
                
                <div class="mt-4 flex items-center gap-3">
                    @php
                        $penerimaName = $pesanan->user->name ?? 'User Tidak Diketahui';
                        $inisial = strtoupper(substr($penerimaName, 0, 2));
                        $colors = ['bg-orange-500', 'bg-blue-500', 'bg-green-500', 'bg-purple-500', 'bg-pink-500'];
                        $colorIndex = crc32($penerimaName) % count($colors);
                        $avatarColor = $colors[$colorIndex];
                    @endphp
                    <div class="w-8 h-8 rounded-full {{ $avatarColor }} text-white flex items-center justify-center text-xs font-bold flex-shrink-0">
                        {{ $inisial }}
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $penerimaName }}</p>
                        <p class="text-xs text-gray-500">{{ $pesanan->rating->created_at->format('d M Y') }}</p>
                    </div>
                </div>
            @else
                <div class="border-2 border-dashed border-gray-200 rounded-3xl p-8 flex flex-col items-center justify-center text-center h-48 bg-gray-50/50">
                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-3 text-gray-400">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    </div>
                    <p class="text-sm font-medium text-gray-900 mb-1">Belum Ada Ulasan</p>
                    <p class="text-xs text-gray-500">Penerima/relawan belum memberikan rating untuk pesanan ini.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
