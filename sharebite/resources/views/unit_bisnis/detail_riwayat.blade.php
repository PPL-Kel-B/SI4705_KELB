@extends('layouts.unit_bisnis')

@section('title', 'Detail Pesanan')

@section('content')
@php
    $orderIdPad = str_pad($pesanan->id, 4, '0', STR_PAD_LEFT);
    $role = optional($pesanan->user)->role;
    $orderSuffix = ($role === 'individu') ? 'A' : 'B';
    $formattedOrderId = "SB-{$orderIdPad}-{$orderSuffix}";
    
    $makananFoto = $pesanan->menuAktif->masterMakanan->foto ? asset('storage/'.$pesanan->menuAktif->masterMakanan->foto) : 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=500&q=80';
    $waktuPesan = \Carbon\Carbon::parse($pesanan->created_at)->translatedFormat('d M Y, H:i') . ' WIB';
    $waktuDiambil = \Carbon\Carbon::parse($pesanan->updated_at)->translatedFormat('d M Y, H:i') . ' WIB';
@endphp

<div class="max-w-6xl mx-auto">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-[16px] relative mb-6 font-bold shadow-sm flex items-center gap-2" role="alert">
            <svg class="w-5 h-5 text-green-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <h1 class="text-[28px] font-extrabold text-[#189347] mb-6">Detail Pesanan</h1>

    <!-- Top Row: Main Card & Ulasan -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6 items-stretch">
        <!-- Main Card -->
        <div class="lg:col-span-2">
            <div class="bg-[#F8FFF9] rounded-[24px] p-8 shadow-sm border border-[#E6F4EA] h-full flex flex-col justify-between relative">
                <div>
                    <div class="flex justify-between items-start mb-8">
                        <div>
                            <p class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">ID PESANAN</p>
                            <h2 class="text-3xl font-extrabold text-gray-800">Order {{ $formattedOrderId }}</h2>
                            
                            <div class="mt-4 flex flex-col gap-1.5">
                                <div class="flex items-center text-[13px] font-semibold text-gray-500 gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    Donasi: {{ $waktuPesan }}
                                </div>
                                <div class="flex items-center text-[13px] font-bold text-[#189347] gap-2">
                                    <svg class="w-4 h-4 text-[#189347]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Diambil pada: {{ $waktuDiambil }}
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            @if($pesanan->status == 'selesai')
                                <div class="bg-[#E4F2E8] text-[#189347] px-4 py-2 rounded-full flex items-center gap-2 font-extrabold text-[12px] tracking-wide border border-[#189347]/10">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                    SELESAI
                                </div>
                            @elseif(method_exists($pesanan, 'isTidakDiambil') && $pesanan->isTidakDiambil())
                                <div class="bg-red-100 text-red-700 px-4 py-2 rounded-full flex items-center gap-2 font-extrabold text-[12px] tracking-wide border border-red-200">
                                    TIDAK DIAMBIL
                                </div>
                            @else
                                <div class="bg-gray-100 text-gray-700 px-4 py-2 rounded-full flex items-center gap-2 font-extrabold text-[12px] tracking-wide uppercase border border-gray-200">
                                    {{ str_replace('_', ' ', $pesanan->status) }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row gap-6 items-start">
                        <img src="{{ $makananFoto }}" class="w-48 h-48 object-cover rounded-[20px] shadow-sm">
                        <div class="flex-1 py-1">
                            <h3 class="text-[22px] font-extrabold text-gray-800 mb-2">{{ $pesanan->menuAktif->masterMakanan->nama_makanan ?? 'Nama Makanan' }}</h3>
                            <p class="text-[13px] font-medium text-gray-500 leading-relaxed mb-6">{{ $pesanan->menuAktif->masterMakanan->deskripsi ?? 'Dikemas dengan rapi untuk menjaga kualitas rasa dan kesegarannya. Dibuat dengan bahan-bahan terbaik.' }}</p>
                            
                            <div class="flex gap-12">
                                <div>
                                    <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">JUMLAH</p>
                                    <p class="text-lg font-black text-gray-800">{{ $pesanan->jumlah_porsi }} Porsi</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">HARGA</p>
                                    <p class="text-lg font-black text-gray-800">
                                        @if($pesanan->menuAktif->is_gratis) GRATIS @else Rp {{ number_format($pesanan->menuAktif->harga_jual, 0, ',', '.') }}/Porsi @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 pt-6 flex justify-end">
                    <div class="text-right">
                        <p class="text-[12px] font-bold text-gray-500 mb-1">Total Harga</p>
                        <p class="text-[28px] font-black text-[#189347]">
                            @if($pesanan->menuAktif->is_gratis) GRATIS @else Rp {{ number_format($pesanan->total_harga ?? ($pesanan->jumlah_porsi * $pesanan->menuAktif->harga_jual), 0, ',', '.') }} @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ulasan & Rating Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-[24px] p-8 shadow-sm border border-gray-50 h-full flex flex-col">
                <h3 class="text-lg font-extrabold text-gray-800 mb-6">Ulasan & Rating</h3>
                
                @if($pesanan->rating || ($pesanan->buktiDonasis && $pesanan->buktiDonasis->count() > 0))
                    <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar bg-[#F8FAFC] border border-gray-100 rounded-[20px] p-6">
                        <!-- Rating -->
                        @if($pesanan->rating)
                            <div class="mb-5 text-center flex flex-col items-center">
                                <div class="flex justify-center text-yellow-400 mb-3">
                                    @for($i=1; $i<=5; $i++)
                                        @if($i <= $pesanan->rating->nilai)
                                            <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        @else
                                            <svg class="w-6 h-6 text-gray-200 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        @endif
                                    @endfor
                                </div>
                                <p class="text-[13px] font-bold text-gray-800 italic">"{{ $pesanan->rating->catatan_pengalaman ?? $pesanan->rating->komentar ?? 'Tidak ada komentar.' }}"</p>
                            </div>
                        @else
                            <p class="text-[13px] text-gray-500 text-center italic mb-5">Belum ada rating & ulasan dari penerima.</p>
                        @endif

                        <!-- Bukti Donasi -->
                        @if($pesanan->buktiDonasis && $pesanan->buktiDonasis->count() > 0)
                            <div class="mt-6 pt-4 border-t border-gray-200 border-dashed">
                                <h4 class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest text-center mb-3">Bukti Pesanan</h4>
                                <div class="grid grid-cols-2 gap-2">
                                    @foreach($pesanan->buktiDonasis as $bukti)
                                        <div class="rounded-xl overflow-hidden border border-gray-100 aspect-square relative group">
                                            <img src="{{ asset('storage/' . $bukti->foto) }}" alt="Bukti Donasi" class="w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                <a href="{{ asset('storage/' . $bukti->foto) }}" target="_blank" class="p-2 bg-white text-gray-900 rounded-lg shadow-sm hover:bg-gray-50">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="bg-[#F8FAFC] border border-gray-100 border-dashed rounded-[20px] p-6 text-center flex-1 flex flex-col items-center justify-center">
                        <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-gray-300 shadow-sm mb-3">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        </div>
                        <h4 class="text-[13px] font-extrabold text-gray-700 mb-2">Pengguna belum melakukan rating</h4>
                        <p class="text-[11px] font-medium text-gray-400 leading-relaxed">
                            Penerima manfaat dapat memberikan rating maksimal 3 hari setelah status dinyatakan selesai oleh relawan.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Bottom Row: Relawan -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-[24px] p-8 shadow-sm border border-gray-50 h-full flex flex-col">
                <h3 class="text-[15px] font-extrabold text-gray-800 flex items-center gap-2 mb-6">
                    <svg class="w-5 h-5 text-[#D97706]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Relawan Pengambil & Distribusi
                </h3>
                
                <div class="flex gap-4 items-center">
                    <div class="w-14 h-14 rounded-full overflow-hidden bg-gray-100 shrink-0 shadow-sm">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($pesanan->user->name ?? 'Budi Santoso') }}&background=27272a&color=fff" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h4 class="text-[15px] font-extrabold text-gray-800">{{ $pesanan->user->name ?? 'Budi Santoso' }}</h4>
                        <p class="text-[12px] font-medium text-gray-500 mt-0.5 leading-relaxed">Relawan pengambil yang telah mendistribusikan pesanan ini kepada penerima manfaat.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom scrollbar for Ulasan section if content is too long */
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: #E2E8F0;
    border-radius: 10px;
}
</style>
@endsection
