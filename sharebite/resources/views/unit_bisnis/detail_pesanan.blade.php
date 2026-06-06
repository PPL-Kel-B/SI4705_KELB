@extends('layouts.unit_bisnis')
@section('content')
<div class="p-6 bg-[#F0F7F2] min-h-screen font-jakarta">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-3">
            <h1 class="text-3xl font-extrabold text-gray-800">Order <span class="text-[#189347]">{{ $pesanan->kode_unik }}</span></h1>
        </div>
        <div class="flex items-center gap-4">
            <span class="bg-[#FFF4E5] text-[#D97706] font-extrabold text-[13px] px-4 py-2 rounded-full uppercase tracking-wider flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                Menunggu Diambil
            </span>
            <span class="bg-[#B3261E] text-white font-bold text-[13px] px-4 py-2 rounded-full flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Batas Waktu: {{ \Carbon\Carbon::parse($pesanan->menuAktif->batas_pengambilan)->format('H:i') }} WIB
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div class="lg:col-span-8">
            <div class="relative w-full h-[320px] rounded-[32px] overflow-hidden shadow-sm mb-6">
                <img src="{{ asset('storage/' . ($pesanan->menuAktif->masterMakanan->foto ?? '')) }}" onerror="this.src='https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=800&q=80'" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent flex flex-col justify-end p-8">
                    <span class="bg-[#189347] text-white text-[10px] font-extrabold uppercase px-3 py-1 rounded-full w-fit mb-3 tracking-widest">
                        {{ $pesanan->menuAktif->masterMakanan->kategori->nama_kategori ?? 'Sayur dan Buah' }}
                    </span>
                    <h2 class="text-4xl font-extrabold text-white">{{ $pesanan->menuAktif->masterMakanan->nama_makanan ?? 'Nama Makanan' }}</h2>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6 mb-6">
                <div class="bg-white p-6 rounded-[24px] shadow-sm">
                    <p class="text-[12px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Jumlah Porsi</p>
                    <p class="text-3xl font-black text-gray-800">{{ $pesanan->jumlah_porsi }} <span class="text-lg font-bold text-gray-400">Porsi</span></p>
                </div>
                <div class="bg-white p-6 rounded-[24px] shadow-sm">
                    <p class="text-[12px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Harga Per Porsi</p>
                    <p class="text-3xl font-black text-[#189347]">@if($pesanan->menuAktif->is_gratis) GRATIS @else Rp {{ number_format($pesanan->menuAktif->harga_jual, 0, ',', '.') }} @endif</p>
                </div>
            </div>

            <div class="bg-white p-8 rounded-[24px] shadow-sm flex justify-between items-center border border-gray-50">
                <div>
                    <h3 class="text-xl font-extrabold text-gray-800">Total Kontribusi</h3>
                    <p class="text-[13px] font-medium text-gray-400 mt-1">Termasuk Biaya Admin & Layanan</p>
                </div>
                <div class="text-5xl font-black text-[#189347]">
                    @if($pesanan->menuAktif->is_gratis) GRATIS @else Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }} @endif
                </div>
            </div>
        </div>

        <div class="lg:col-span-4">
            <div class="bg-white p-8 rounded-[32px] shadow-sm flex flex-col items-center border border-gray-50 h-full">
                <p class="text-[11px] font-extrabold text-gray-400 uppercase tracking-[0.2em] w-full text-left mb-6">Informasi Pembeli / Relawan</p>
                
                <div class="w-28 h-28 bg-gray-200 rounded-[28px] mb-5 overflow-hidden ring-4 ring-[#E4F2E8]">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($pesanan->user->name) }}&background=189347&color=fff&size=150" class="w-full h-full object-cover">
                </div>
                
                <h3 class="text-2xl font-extrabold text-gray-800 mb-2">{{ $pesanan->user->name ?? 'Relawan' }}</h3>
                <span class="bg-[#E4F2E8] text-[#189347] text-[11px] font-extrabold px-4 py-1.5 rounded-full tracking-widest uppercase mb-8">Verified Volunteer</span>

                <div class="grid grid-cols-2 gap-4 w-full mb-8">
                    <div class="bg-[#F8FAFC] p-4 rounded-2xl text-center">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">ID Relawan</p>
                        <p class="text-lg font-black text-gray-800">{{ $volId }}</p>
                    </div>
                    <div class="bg-[#F8FAFC] p-4 rounded-2xl text-center">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Total Pesanan</p>
                        <p class="text-lg font-black text-gray-800">{{ $totalPesananBuyer }}</p>
                    </div>
                </div>

                <a href="#" class="w-full border-2 border-gray-200 hover:border-[#189347] text-gray-600 hover:text-[#189347] py-3.5 rounded-[16px] font-bold text-[14px] flex items-center justify-center gap-2 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg> Hubungi Relawan
                </a>
            </div>
        </div>
    </div>
</div>
@endsection