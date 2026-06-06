@extends('layouts.unit_bisnis')
@section('content')
<div class="p-6 bg-[#F0F7F2] min-h-screen font-jakarta">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-800">Pesanan Masuk</h1>
            <p class="text-gray-500 mt-1">Kelola distribusi makanan berlebih Anda hari ini secara efisien.</p>
        </div>
        <a href="{{ route('unit.pesanan.verifikasi') }}" class="bg-[#189347] hover:bg-[#147a3a] text-white px-6 py-3 rounded-[14px] font-bold shadow-sm flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
            Verifikasi Code
        </a>
    </div>

    {{-- Statistik --}}
    <div class="grid grid-cols-3 gap-6 mb-10">
        <div class="bg-white p-6 rounded-[20px] shadow-sm border border-gray-100 border-l-4 border-l-[#189347]">
            <p class="text-sm font-bold text-gray-400 mb-1">Total Pesanan</p>
            <h2 class="text-3xl font-black text-[#189347]">{{ $pesanans->count() }}</h2>
        </div>
        <div class="bg-white p-6 rounded-[20px] shadow-sm border border-gray-100 border-l-4 border-l-[#D97706]">
            <p class="text-sm font-bold text-gray-400 mb-1">Menunggu Diambil</p>
            <h2 class="text-3xl font-black text-[#D97706]">{{ $menunggu->count() }}</h2>
        </div>
        <div class="bg-white p-6 rounded-[20px] shadow-sm border border-gray-100 border-l-4 border-l-[#7E22CE]">
            <p class="text-sm font-bold text-gray-400 mb-1">Selesai Distribusi</p>
            <h2 class="text-3xl font-black text-[#7E22CE]">{{ $selesai->count() }}</h2>
        </div>
    </div>

    <h2 class="text-xl font-extrabold text-gray-800 mb-6 flex items-center gap-2">Daftar Pesanan Aktif <span class="w-2 h-2 bg-[#D97706] rounded-full"></span></h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($menunggu as $p)
        <div class="bg-white rounded-[24px] p-5 shadow-sm border border-gray-50 flex flex-col">
            <div class="relative mb-4">
                <img src="{{ asset('storage/' . ($p->menuAktif->masterMakanan->foto ?? '')) }}" onerror="this.src='https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=300&q=80'" class="w-full h-40 object-cover rounded-[16px]">
                <span class="absolute top-3 right-3 bg-[#D97706] text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">Menunggu Diambil</span>
            </div>
            <h3 class="text-[18px] font-extrabold text-[#189347] leading-tight mb-1">{{ $p->menuAktif->masterMakanan->nama_makanan ?? 'Nama Makanan' }}</h3>
            <p class="text-[14px] font-bold text-[#189347]/70 mb-5">{{ $p->jumlah_porsi }} Porsi</p>
            
            <div class="flex justify-between items-center mb-6 text-[13px]">
                <div>
                    <p class="text-gray-400 font-medium">PENERIMA</p>
                    <p class="font-bold text-gray-800">{{ $p->user->name ?? 'Relawan' }}</p>
                </div>
                <div class="text-right">
                    <p class="text-gray-400 font-medium">WAKTU PESAN</p>
                    <p class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($p->waktu_pesan)->format('H:i') }} WIB</p>
                </div>
            </div>
            <a href="{{ route('unit.pesanan.show', $p->id) }}" class="mt-auto w-full bg-[#189347] hover:bg-[#147a3a] text-white text-center py-3 rounded-xl font-bold text-[14px] transition">Detail Pesanan →</a>
        </div>
        @endforeach
    </div>
</div>
@endsection