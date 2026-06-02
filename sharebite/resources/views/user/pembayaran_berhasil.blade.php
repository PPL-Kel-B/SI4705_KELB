@extends('layouts.user')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    .font-jakarta { font-family: 'Plus Jakarta Sans', sans-serif; }

    .custom-leaflet-marker { background: transparent; border: none; }
    #map-berhasil { width: 100%; height: 100%; z-index: 0; }

    .pulse-circle {
        display: flex; align-items: center; justify-content: center;
        width: 100px; height: 100px; background: #189347;
        border-radius: 50%; color: white; position: relative;
        box-shadow: 0 0 0 0 rgba(24, 147, 71, 0.7);
        animation: pulse-green 2s infinite;
    }
    @keyframes pulse-green {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(24, 147, 71, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 20px rgba(24, 147, 71, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(24, 147, 71, 0); }
    }
</style>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<div class="min-h-screen bg-[#F0F7F2] p-6 lg:p-10 font-jakarta w-full">
    
    <div class="flex flex-col items-center justify-center text-center mb-10 mt-2">
        <div class="pulse-circle mb-5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <h1 class="text-[32px] font-extrabold text-gray-800 tracking-tight">Pembayaran Berhasil!</h1>
        <p class="text-gray-500 font-medium mt-2 text-[15px]">Pesanan Anda telah dikonfirmasi. Tunjukkan kode di bawah ke staf resto.</p>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 w-full max-w-full mx-auto mb-10 items-stretch">

        {{-- KOLOM KIRI --}}
        <div class="xl:col-span-8 flex flex-col gap-6 h-full">
            
            <div class="bg-white p-8 lg:p-10 rounded-[32px] shadow-sm border border-green-50 flex flex-col items-center text-center">
                <span class="text-[12px] font-extrabold text-gray-400 tracking-[0.15em] uppercase mb-4">KODE VERIFIKASI PENGAMBILAN</span>
                
                <div class="relative bg-[#F0F7F2] border-[2px] border-dashed border-[#189347] px-12 py-6 rounded-[24px] mb-6 overflow-hidden min-w-[320px]">
                    <h2 id="teks-kode" class="flex items-center justify-center gap-3 text-[42px] font-black text-[#189347] tracking-[0.1em] tabular-nums blur-[8px] select-none transition-all duration-300">
                        {{ $kode_verifikasi }}
                    </h2>
                    
                    <button id="btn-lihat-kode" onclick="toggleKode()" class="absolute inset-0 flex items-center justify-center w-full h-full bg-[#F0F7F2]/70 backdrop-blur-sm z-10 text-[#189347] font-extrabold text-[15px] gap-2 transition-all duration-300 hover:bg-[#F0F7F2]/50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        Lihat Kode Verifikasi
                    </button>
                    <button id="btn-tutup-kode" onclick="toggleKode()" class="absolute top-3 right-4 text-[#189347] bg-[#E4F2E8] p-2 rounded-full hidden hover:bg-[#D1E6D8] transition-colors shadow-sm"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg></button>
                </div>
                <div class="flex items-center gap-2 text-white bg-[#B3261E] px-5 py-2.5 rounded-full text-[13px] font-bold shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Batas Pengambilan: {{ \Carbon\Carbon::parse($makanan->batas_pengambilan)->format('d M Y, H:i') }}
                </div>
            </div>

            {{-- LOKASI PENGAMBILAN (DESAIN BARU) --}}
            <div class="bg-white p-6 lg:p-8 rounded-[32px] shadow-sm border border-gray-50 flex-1 flex flex-col">
                {{-- Header Lokasi & Petunjuk Arah --}}
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-[22px] w-[22px] text-[#189347]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-8-4.5-8-11.8A8 8 0 0112 1.2a8 8 0 018 8c0 7.3-8 11.8-8 11.8z" />
                            <circle cx="12" cy="9.2" r="2.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <h3 class="text-[17px] font-extrabold text-gray-900">Lokasi Pengambilan Pesanan</h3>
                    </div>
                    
                    <a href="https://www.google.com/maps?q={{ $makanan->unitBisnis->user->latitude ?? -6.193125 }},{{ $makanan->unitBisnis->user->longitude ?? 106.76483 }}" target="_blank" class="text-[#189347] text-[13px] font-bold flex items-center gap-1.5 hover:underline">
                        Petunjuk Arah
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                </div>
                
                {{-- Nama Resto & Alamat Singkat --}}
                <p class="text-gray-500 font-medium text-[14px] mb-6 pl-8">
                    {{ $makanan->unitBisnis->user->name ?? 'Resto ShareBite' }}, {{ $makanan->unitBisnis->user->alamat ?? 'Alamat resto belum diatur' }}
                </p>
                
                {{-- MAP WRAPPER (min-h DIKURANGI AGAR MAP YANG MENYESUAIKAN) --}}
                <div onclick="window.open('https://www.google.com/maps?q={{ $makanan->unitBisnis->user->latitude ?? -6.193125 }},{{ $makanan->unitBisnis->user->longitude ?? 106.76483 }}', '_blank')" class="relative w-full flex-1 min-h-[120px] bg-gray-100 rounded-[20px] overflow-hidden border border-gray-100 shadow-inner z-0 cursor-pointer group">
                    
                    <div id="map-berhasil" class="absolute inset-0 z-0"></div>
                    
                    {{-- Overlay Buka Map saat di-hover --}}
                    <div class="absolute inset-0 z-10 bg-black/5 group-hover:bg-black/10 transition-colors flex items-center justify-center pointer-events-none">
                        <div class="bg-white/90 backdrop-blur-sm px-5 py-2.5 rounded-full text-[13px] font-extrabold text-[#189347] shadow-md flex items-center gap-2 transform scale-95 opacity-0 group-hover:scale-100 group-hover:opacity-100 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                            Buka Map
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- KOLOM KANAN --}}
        <div class="xl:col-span-4 flex flex-col gap-6 h-full">
            <div class="bg-white p-8 rounded-[32px] shadow-sm border border-gray-50">
                
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-[17px] font-extrabold text-gray-800 tracking-tight">Ringkasan Pesanan</h3>
                    <span class="bg-[#eefcf4] border border-[#b3dfc3] text-[#189347] text-[11px] font-extrabold px-4 py-1.5 rounded-full tracking-widest uppercase shadow-sm">PAID</span>
                </div>
                <div class="flex gap-4 items-center mb-6">
                    <img src="{{ asset('storage/' . ($makanan->masterMakanan->foto ?? '')) }}" onerror="this.src='https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=300&q=80'" class="w-16 h-16 object-cover rounded-xl shadow-sm">
                    <div>
                        <h4 class="font-extrabold text-gray-800 text-[16px]">{{ $makanan->masterMakanan->nama_makanan ?? 'Nama Makanan' }}</h4>
                        <p class="text-gray-400 text-[13px] font-bold">{{ $qty }} Porsi</p>
                    </div>
                </div>
                <div class="pt-5 border-t border-gray-140 flex justify-between items-center">
                    <span class="text-[14px] text-gray-500 font-bold">Total Pembayaran</span>
                    <span class="text-[22px] font-extrabold text-[#189347]">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- KOTAK LANGKAH PENGAMBILAN (DIJADIKAN PATOKAN UTAMA / h-fit) --}}
            <div class="bg-white p-8 rounded-[32px] shadow-sm border border-gray-50 h-fit flex flex-col z-10 relative">
                
                {{-- Judul dengan Aksen Garis Vertikal --}}
                <div class="flex items-center gap-3 mb-5 z-10 relative">
                    <div class="w-1.5 h-6 bg-[#0B6A38] rounded-full"></div>
                    <h3 class="text-[18px] font-extrabold text-gray-900 tracking-tight">Langkah Pengambilan</h3>
                </div>

                {{-- List Langkah --}}
                <div class="relative space-y-6 z-10 ml-5">
                    {{-- Garis tebal penghubung di belakang (Dimulai dari top-5 agar sembunyi di balik angka 1) --}}
                    <div class="absolute left-5 top-5 bottom-5 w-1 bg-[#F0F7F2] -ml-[2px] z-0 rounded-full"></div>

                    {{-- Langkah 1 --}}
                    <div class="flex items-start gap-5 relative z-10">
                        <div class="w-10 h-10 bg-[#0B6A38] text-white rounded-full flex items-center justify-center font-bold text-[16px] shadow-[0_6px_16px_rgba(11,106,56,0.3)] shrink-0 ring-4 ring-white z-10">
                            1
                        </div>
                        <p class="text-gray-900 font-bold text-[15px] mt-2 leading-relaxed">
                            Datang ke resto sebelum batas waktu pengambilan berakhir.
                        </p>
                    </div>

                    {{-- Langkah 2 --}}
                    <div class="flex items-start gap-5 relative z-10">
                        <div class="w-10 h-10 bg-[#0B6A38] text-white rounded-full flex items-center justify-center font-bold text-[16px] shadow-[0_6px_16px_rgba(11,106,56,0.3)] shrink-0 ring-4 ring-white z-10">
                            2
                        </div>
                        <p class="text-gray-900 font-bold text-[15px] mt-1 leading-relaxed">
                            Klik Lihat Kode Verifikasi, lalu tunjukkan kode tersebut kepada staf resto.
                        </p>
                    </div>

                    {{-- Langkah 3 --}}
                    <div class="flex items-start gap-5 relative z-10">
                        <div class="w-10 h-10 bg-[#0B6A38] text-white rounded-full flex items-center justify-center font-bold text-[16px] shadow-[0_6px_16px_rgba(11,106,56,0.3)] shrink-0 ring-4 ring-white z-10">
                            3
                        </div>
                        <p class="text-gray-900 font-bold text-[15px] mt-2 leading-relaxed">
                            Makanan siap diambil.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-center pb-10">
        <a href="{{ route('user.riwayat') }}" class="bg-white text-gray-800 border border-gray-100 hover:border-[#189347] hover:bg-[#189347] hover:text-white px-10 py-4 rounded-[20px] font-extrabold text-[15px] transition-all duration-300 shadow-sm">
            Lihat Riwayat Pesanan
        </a>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
function toggleKode() {
    const teksKode = document.getElementById('teks-kode');
    const btnLihat = document.getElementById('btn-lihat-kode');
    const btnTutup = document.getElementById('btn-tutup-kode');
    if (teksKode.classList.contains('blur-[8px]')) {
        teksKode.classList.remove('blur-[8px]');
        btnLihat.classList.add('opacity-0', 'pointer-events-none');
        btnTutup.classList.remove('hidden');
    } else {
        teksKode.classList.add('blur-[8px]');
        btnLihat.classList.remove('opacity-0', 'pointer-events-none');
        btnTutup.classList.add('hidden');
    }
}
document.addEventListener('DOMContentLoaded', () => {
    const lat = {{ $makanan->unitBisnis->user->latitude ?? -6.193125 }};
    const lng = {{ $makanan->unitBisnis->user->longitude ?? 106.76483 }};
    
    // Zoom control di-false agar UI map terlihat lebih bersih seperti desain
    const map = L.map('map-berhasil', { zoomControl: false, dragging: true, scrollWheelZoom: false }).setView([lat, lng], 16);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    
    // Desain Custom Marker Sendok & Garpu dengan efek pulse hijau
    const customIcon = L.divIcon({ 
        className: 'custom-leaflet-marker', 
        html: `<div class="relative flex items-center justify-center w-24 h-24">
                   <div class="absolute w-16 h-16 bg-[#189347]/30 rounded-full animate-pulse"></div>
                   <div class="relative w-10 h-10 bg-[#189347] rounded-full shadow-lg flex items-center justify-center border-[3px] border-white">
                       <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                           <path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"></path>
                           <path d="M7 2v20"></path>
                           <path d="M21 15V2v0a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3zm0 0v7"></path>
                       </svg>
                   </div>
               </div>`, 
        iconSize: [96, 96], 
        iconAnchor: [48, 48] 
    });
    
    L.marker([lat, lng], { icon: customIcon }).addTo(map);
});
</script>
@endsection