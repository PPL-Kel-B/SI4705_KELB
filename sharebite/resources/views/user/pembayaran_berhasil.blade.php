@extends('layouts.user')

@section('content')

{{-- ── Import Font & Leaflet CSS ── --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    .font-jakarta { font-family: 'Plus Jakarta Sans', sans-serif; }

    .custom-leaflet-marker { background: transparent; border: none; }
    #map-berhasil { width: 100%; height: 100%; z-index: 0; }

    /* EFEK BULATAN BERDEBAR (PULSING) ATAS */
    .pulse-circle {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100px;
        height: 100px;
        background: #189347;
        border-radius: 50%;
        color: white;
        position: relative;
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
    
    {{-- BAGIAN ATAS: ICON BERDEBAR & JUDUL --}}
    <div class="flex flex-col items-center justify-center text-center mb-10 mt-2">
        <div class="pulse-circle mb-5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <h1 class="text-[32px] font-extrabold text-gray-800 tracking-tight">Pembayaran Berhasil!</h1>
        <p class="text-gray-500 font-medium mt-2 text-[15px]">Pesanan Anda telah dikonfirmasi. Tunjukkan kode di bawah ke staf gerai.</p>
    </div>

    {{-- GRID UTAMA: FULL WIDTH (PORSI 8:4) --}}
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 w-full max-w-full mx-auto mb-10">

        {{-- KOLOM KIRI --}}
        <div class="xl:col-span-8 flex flex-col gap-6">
            
            {{-- BOX KODE VERIFIKASI (DESAIN BARU) --}}
            <div class="bg-white p-8 lg:p-10 rounded-[32px] shadow-sm border border-green-50 flex flex-col items-center text-center">
                
                {{-- Judul --}}
                <span class="text-[12px] font-extrabold text-gray-400 tracking-[0.15em] uppercase mb-4">KODE VERIFIKASI PENGAMBILAN</span>
                
                {{-- Kotak Blur Kode --}}
                <div class="relative bg-[#F0F7F2] border-[2px] border-dashed border-[#189347] px-12 py-6 rounded-[24px] mb-6 overflow-hidden group min-w-[320px]">
                    <h2 id="teks-kode" class="flex items-center justify-center gap-3 text-[42px] font-black text-[#189347] tracking-[0.1em] tabular-nums blur-[8px] select-none transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#189347]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        {{ $kode_verifikasi }}
                    </h2>
                    
                    <button id="btn-lihat-kode" onclick="toggleKode()" class="absolute inset-0 flex items-center justify-center w-full h-full bg-[#F0F7F2]/70 backdrop-blur-sm z-10 text-[#189347] font-extrabold text-[15px] gap-2 transition-all duration-300 hover:bg-[#F0F7F2]/50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Lihat Kode Verifikasi
                    </button>

                    <button id="btn-tutup-kode" onclick="toggleKode()" class="absolute top-3 right-4 text-[#189347] bg-[#E4F2E8] p-2 rounded-full hidden hover:bg-[#D1E6D8] transition-colors shadow-sm" title="Sembunyikan Kode">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
                    </button>
                </div>
                
                {{-- Kapsul Batas Waktu Merah di Bawah --}}
                <div class="flex items-center gap-2 text-white bg-[#B3261E] px-5 py-2.5 rounded-full text-[13px] font-bold shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Batas Pengambilan: {{ \Carbon\Carbon::parse($makanan->batas_pengambilan)->format('d M Y, H:i') }}
                </div>
            </div>

            {{-- LOKASI PENGAMBILAN --}}
            <div class="bg-white p-8 rounded-[32px] shadow-sm border border-gray-50 flex-1 flex flex-col justify-center">
                <div class="flex items-center gap-2.5 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-[26px] w-[26px] text-[#189347]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-8-4.5-8-11.8A8 8 0 0112 1.2a8 8 0 018 8c0 7.3-8 11.8-8 11.8z" />
                        <circle cx="12" cy="9.2" r="2.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <h3 class="text-[20px] font-extrabold text-gray-800">Lokasi Pengambilan</h3>
                </div>
                
                <div class="flex flex-col md:flex-row gap-8 items-center">
                    
                    {{-- MAP WRAPPER (KIRI) --}}
                    <div onclick="window.open('https://www.google.com/maps?q={{ $makanan->latitude ?? -6.193125 }},{{ $makanan->longitude ?? 106.76483 }}', '_blank')" class="relative w-full md:w-[320px] h-[200px] bg-gray-100 rounded-[24px] overflow-hidden border border-gray-100 flex-shrink-0 cursor-pointer group shadow-sm z-0">
                        <div id="map-berhasil" class="absolute inset-0 pointer-events-none z-0"></div>
                        <div class="absolute inset-0 z-10 bg-black/5 group-hover:bg-black/10 transition-colors flex items-center justify-center">
                            <div class="bg-white/90 backdrop-blur-sm px-5 py-2.5 rounded-full text-[13px] font-extrabold text-[#189347] shadow-md flex items-center gap-2 transform scale-95 opacity-0 group-hover:scale-100 group-hover:opacity-100 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                                Buka Map
                            </div>
                        </div>
                    </div>
                    
                    {{-- DETAIL ALAMAT (KANAN) --}}
                    <div class="flex-1 text-left">
                        <h4 class="text-2xl font-extrabold text-gray-800 mb-3">{{ $makanan->resto }}</h4>
                        <p class="text-gray-500 font-medium text-[16px] mb-5 leading-relaxed pr-4">{{ $makanan->alamat }}</p>
                        
                        <a href="https://www.google.com/maps?q={{ $makanan->latitude ?? -6.193125 }},{{ $makanan->longitude ?? 106.76483 }}" target="_blank" class="text-[#189347] text-[15px] font-bold flex items-center gap-1.5 hover:underline w-fit">
                            Buka di Google Maps
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN --}}
        <div class="xl:col-span-4 flex flex-col gap-6">
            
            {{-- RINGKASAN ITEM --}}
            <div class="bg-white p-8 rounded-[32px] shadow-sm border border-gray-50 h-fit">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-[17px] font-extrabold text-gray-800">Ringkasan Pesanan</h3>
                    <span class="bg-[#E4F2E8] text-[#189347] text-[9px] font-black px-3 py-1 rounded-full tracking-widest uppercase shadow-sm">PAID</span>
                </div>

                <div class="flex gap-4 items-center mb-8">
                    <img src="{{ asset('storage/' . $makanan->gambar) }}" onerror="this.src='https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=300&q=80'" class="w-16 h-16 object-cover rounded-xl shadow-sm border border-gray-50">
                    <div>
                        <h4 class="font-extrabold text-gray-800 text-[16px] leading-tight">{{ $makanan->nama }}</h4>
                        <p class="text-gray-400 text-[13px] font-bold mt-0.5">{{ $qty }} Porsi</p>
                    </div>
                </div>

                <div class="space-y-4 pt-5 border-t border-gray-100">
                    <div class="flex justify-between text-gray-500 font-medium items-center">
                        <span class="text-[14px]">Total Bayar</span>
                        <span class="text-[22px] font-extrabold text-[#189347]">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- LANGKAH PENGAMBILAN --}}
            <div class="bg-white p-8 rounded-[32px] shadow-sm border border-gray-50 flex-1">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-1.5 h-6 bg-[#189347] rounded-full"></div>
                    <h3 class="text-[18px] font-extrabold text-gray-800">Langkah Pengambilan</h3>
                </div>
                
                <div class="relative pl-1">
                    {{-- Garis Penghubung Vertikal --}}
                    <div class="absolute left-5 top-2 bottom-4 w-[2px] bg-green-100 z-0"></div>
                    
                    <div class="flex items-center gap-5 mb-8 relative z-10">
                        <div class="w-8 h-8 bg-[#189347] text-white rounded-full flex items-center justify-center font-bold text-[13px] shadow-sm ring-4 ring-white flex-shrink-0">1</div>
                        <p class="text-gray-800 font-extrabold text-[15px]">Datang ke gerai sebelum batas pengambilan berakhir.</p>
                    </div>
                    
                    <div class="flex items-center gap-5 mb-8 relative z-10">
                        <div class="w-8 h-8 bg-[#189347] text-white rounded-full flex items-center justify-center font-bold text-[13px] shadow-sm ring-4 ring-white flex-shrink-0">2</div>
                        <p class="text-gray-800 font-extrabold text-[15px]">Klik Lihat Kode Verifikasi, lalu tunjukkan kode tersebut kepada staf gerai.</p>
                    </div>
                    
                    <!-- <div class="flex items-center gap-5 relative z-10">
                        <div class="w-8 h-8 bg-[#189347] text-white rounded-full flex items-center justify-center font-bold text-[13px] shadow-sm ring-4 ring-white flex-shrink-0">3</div>
                        <p class="text-gray-800 font-extrabold text-[15px]">Konfirmasi Selesai</p> -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TOMBOL RIWAYAT PESANAN (Tengah Bawah) --}}
    <div class="flex justify-center pb-10">
        <a href="{{ route('user.riwayat') }}" class="bg-white text-gray-800 border border-gray-100 hover:border-[#189347] hover:bg-[#189347] hover:text-white px-10 py-4 rounded-[20px] font-extrabold text-[15px] transition-all duration-300 shadow-sm hover:shadow-md">
            Lihat Riwayat Pesanan
        </a>
    </div>

</div>

{{-- ── Script JS ── --}}
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
    // Koordinat Map
    const lat = {{ $makanan->latitude ?? -6.193125 }};
    const lng = {{ $makanan->longitude ?? 106.76483 }};

    const map = L.map('map-berhasil', {
        zoomControl: true,
        dragging: true,
        scrollWheelZoom: false,
    }).setView([lat, lng], 16);

    map.zoomControl.setPosition('topleft');

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    // Desain Marker Berdebar (Radar Style)
    const customIcon = L.divIcon({
        className: 'custom-leaflet-marker',
        html: `<div class="relative flex items-center justify-center w-16 h-16">
                   <div class="absolute w-full h-full bg-[#189347]/20 rounded-full animate-ping"></div>
                   <div class="absolute w-10 h-10 bg-[#189347]/40 rounded-full"></div>
                   <div class="relative w-8 h-8 bg-[#189347] rounded-full shadow-md flex items-center justify-center border-2 border-white">
                       <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" viewBox="0 0 20 20" fill="currentColor">
                           <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                       </svg>
                   </div>
               </div>`,
        iconSize: [64, 64],
        iconAnchor: [32, 32]
    });

    L.marker([lat, lng], { icon: customIcon }).addTo(map);
});
</script>

@endsection