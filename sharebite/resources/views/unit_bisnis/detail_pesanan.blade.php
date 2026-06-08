@extends('layouts.unit_bisnis')
@section('content')
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-[16px] relative mb-6 font-bold shadow-sm flex items-center gap-2" role="alert">
            <svg class="w-5 h-5 text-green-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @php
        $orderIdPad = str_pad($pesanan->id, 3, '0', STR_PAD_LEFT);
        $orderSuffix = ($pesanan->user->role === 'individu') ? 'A' : 'B';
        $formattedOrderId = "SB-{$orderIdPad}-{$orderSuffix}";
    @endphp

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 pt-2 gap-4">
        <div class="flex flex-col gap-1">
            <h1 class="text-3xl font-extrabold text-gray-800">Order <span class="text-[#189347]">{{ $formattedOrderId }}</span></h1>
            <div class="flex items-center gap-2 text-xs font-bold text-gray-400 mt-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>{{ \Carbon\Carbon::parse($pesanan->waktu_pesan)->translatedFormat('d M Y') }}</span>
                <span class="mx-1">•</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ \Carbon\Carbon::parse($pesanan->waktu_pesan)->format('H:i') }} WIB</span>
            </div>
        </div>
        <div class="flex items-center gap-3">
            @if($pesanan->status === 'selesai')
                <div class="bg-[#E4F2E8] text-[#189347] px-5 py-3 rounded-[16px] flex items-center gap-3 border border-[#189347]/10 shadow-sm">
                    <div class="bg-[#C5E8D2] p-1.5 rounded-lg text-[#189347] flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-[#189347]/60 uppercase tracking-wider leading-none mb-1">Status</p>
                        <p class="text-[14px] font-black leading-none">Selesai</p>
                    </div>
                </div>
            @elseif($pesanan->isTidakDiambil())
                <div class="bg-red-50 text-red-700 px-5 py-3 rounded-[16px] flex items-center gap-3 border border-red-200 shadow-sm">
                    <div class="bg-red-100 p-1.5 rounded-lg text-red-700 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-red-700/60 uppercase tracking-wider leading-none mb-1">Status</p>
                        <p class="text-[14px] font-black leading-none">Tidak Diambil</p>
                    </div>
                </div>
                <div class="bg-red-600 text-white px-5 py-3 rounded-[16px] flex items-center gap-3 shadow-sm">
                    <div class="bg-white/20 p-1.5 rounded-lg text-white flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-white/70 uppercase tracking-wider leading-none mb-1">Batas Waktu Pengambilan</p>
                        <p class="text-[14px] font-black leading-none">{{ \Carbon\Carbon::parse($pesanan->menuAktif->batas_pengambilan)->format('H:i') }} WIB (Kadaluarsa)</p>
                    </div>
                </div>
            @else
                <div class="bg-[#E4F2E8] text-[#189347] px-5 py-3 rounded-[16px] flex items-center gap-3 border border-[#189347]/10 shadow-sm">
                    <div class="bg-[#C5E8D2] p-1.5 rounded-lg text-[#189347] flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-[#189347]/60 uppercase tracking-wider leading-none mb-1">Status</p>
                        <p class="text-[14px] font-black leading-none">Menunggu Diambil</p>
                    </div>
                </div>
                <div class="bg-[#B3261E] text-white px-5 py-3 rounded-[16px] flex items-center gap-3 shadow-sm">
                    <div class="bg-white/20 p-1.5 rounded-lg text-white flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-white/70 uppercase tracking-wider leading-none mb-1">Batas Waktu Pengambilan</p>
                        <p class="text-[14px] font-black leading-none">{{ \Carbon\Carbon::parse($pesanan->menuAktif->batas_pengambilan)->format('H:i') }} WIB</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
 
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div class="lg:col-span-8">
            <div class="relative w-full h-[320px] rounded-[32px] overflow-hidden shadow-sm mb-6">
                <img src="{{ asset('storage/' . ($pesanan->menuAktif->masterMakanan->foto ?? '')) }}" onerror="this.src='https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=800&q=80'" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent flex flex-col justify-end p-8">
                    <span class="bg-[#189347] text-white text-[10px] font-extrabold uppercase px-3 py-1 rounded-full w-fit mb-3 tracking-widest">
                        {{ $pesanan->menuAktif->masterMakanan->kategori ?? 'Kategori' }}
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
                    @if($pesanan->menuAktif->is_gratis) GRATIS @else Rp {{ number_format($pesanan->jumlah_porsi * $pesanan->menuAktif->harga_jual, 0, ',', '.') }} @endif
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

                <div class="grid grid-cols-3 gap-2 w-full">
                    <div class="bg-[#F8FAFC] p-3 rounded-2xl text-center">
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider mb-1">ID Relawan</p>
                        <p class="text-[13px] font-black text-gray-800 truncate" title="{{ $volId }}">{{ $volId }}</p>
                    </div>
                    <div class="bg-[#F8FAFC] p-3 rounded-2xl text-center">
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider mb-1">RATING</p>
                        <p class="text-[13px] font-black text-gray-800 flex items-center justify-center gap-0.5">
                            {{ number_format($averageRating, 1) }}
                            <span class="text-amber-500 text-xs">★</span>
                        </p>
                    </div>
                    <div class="bg-[#F8FAFC] p-3 rounded-2xl text-center">
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider mb-1">TOTAL DONASI</p>
                        <p class="text-[13px] font-black text-gray-800">{{ $totalPesananBuyer }}</p>
                    </div>
                </div>

                <!-- Box Pengingat Penyerahan untuk mengisi ruang kosong -->
                <div class="mt-auto w-full pt-8">
                    <div class="bg-gradient-to-br from-[#E4F2E8] to-[#F0F7F2] p-5 rounded-[24px] border border-[#189347]/10 flex flex-col items-center text-center shadow-sm">
                        <div class="w-10 h-10 rounded-full bg-[#189347] flex items-center justify-center text-white mb-3 shadow-md shadow-[#189347]/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h4 class="text-sm font-extrabold text-[#189347] mb-1.5">Penyerahan Higienis</h4>
                        <p class="text-[11px] text-gray-500 font-bold leading-relaxed">
                            Pastikan kemasan makanan dalam kondisi tertutup rapat, bersih, dan higienis sebelum diserahkan demi menjaga keselamatan penerima manfaat.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection