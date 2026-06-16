@extends('layouts.user')

@section('content')

{{-- ── Import Font & Leaflet CSS ── --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    .font-jakarta { font-family: 'Plus Jakarta Sans', sans-serif; }
    
    .custom-leaflet-marker { background: transparent; border: none; }
    #map-pembayaran { width: 100%; height: 100%; z-index: 0; } 
</style>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

{{-- ── Data Metadata untuk JavaScript ── --}}
<div id="qr-meta"
     data-slug="{{ $id }}"
     data-nama="{{ $makanan->masterMakanan->nama_makanan ?? 'Pesanan' }}"
     data-harga="{{ $makanan->is_gratis ? 0 : ($makanan->harga_jual ?? 0) }}"
     data-qty="{{ $qty ?? 1 }}"
     data-lat="{{ $makanan->unitBisnis->user->latitude ?? -6.193125 }}" 
     data-lng="{{ $makanan->unitBisnis->user->longitude ?? 106.76483 }}"
     data-ref="{{ $ref ?? 'SB-00000000' }}"
     data-seconds="{{ $remainingSeconds ?? 900 }}"
     data-total="{{ $makanan->is_gratis ? 'GRATIS' : 'Rp ' . number_format($subtotal ?? 0, 0, ',', '.') }}"
     style="display:none;">
</div>

<div class="min-h-screen bg-[#F0F7F2] px-6 pb-6 pt-3 lg:px-10 lg:pb-10 lg:pt-3 font-jakarta w-full relative">
    
    {{-- Header --}}
    <div class="flex items-center gap-3 mb-8 w-full max-w-full mx-auto">
        <a href="{{ route('user.riwayat') }}" class="w-9 h-9 bg-[#E3EFE7] text-[#189347] hover:bg-[#D1E6D8] rounded-full flex items-center justify-center transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h1 class="text-[30px] font-extrabold text-gray-800">Pembayaran</h1>
    </div>

    {{-- GRID UTAMA --}}
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 w-full max-w-full mx-auto pb-20 items-stretch">

        {{-- KOLOM KIRI --}}
        <div class="xl:col-span-8 flex flex-col gap-6 h-full">

            {{-- Card 1: Item Makanan --}}
            <div class="bg-white p-6 lg:p-7 rounded-[28px] shadow-sm">
                <div class="flex gap-7 items-center">
                    {{-- Foto Makanan --}}
                    <img src="{{ asset('storage/' . ($makanan->masterMakanan->foto ?? '')) }}" 
                         onerror="this.src='https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=300&q=80'" 
                         alt="Menu" 
                         class="w-[110px] h-[110px] object-cover rounded-[22px] flex-shrink-0 shadow-sm">
                    
                    {{-- Konten Teks --}}
                    <div class="flex-1 flex justify-between items-center">
                        <div class="flex flex-col justify-center">
                            {{-- Label Status Dinamis --}}
                            <div class="mb-2">
                                @php
                                    if ($makanan->stok_porsi <= 5) {
                                        $statusTeks = 'SEGERA HABIS';
                                        $statusWarna = 'bg-[#FFF4E5] text-[#D97706]';
                                    } else {
                                        $statusTeks = 'TERSEDIA';
                                        $statusWarna = 'bg-[#E4F2E8] text-[#189347]';
                                    }
                                @endphp
                                <span class="{{ $statusWarna }} text-[10px] font-extrabold px-3 py-1 rounded-[8px] tracking-[0.1em] uppercase inline-block">
                                    {{ $statusTeks }}
                                </span>
                            </div>
                            <h2 class="font-extrabold text-gray-900 text-[22px] leading-tight mb-1">
                                {{ $makanan->masterMakanan->nama_makanan ?? 'Nama Makanan' }}
                            </h2>
                            <p class="text-gray-500 font-semibold text-[14px]">
                                {{ $qty ?? 1 }} Porsi
                            </p>
                        </div>

                        {{-- Bagian Harga --}}
                        <div class="text-right">
                            <span class="text-[#189347] font-extrabold text-[22px]">
                                @if($makanan->is_gratis)
                                    GRATIS
                                @else
                                    Rp {{ number_format($makanan->harga_jual ?? 0, 0, ',', '.') }}
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 2: Rincian Pembayaran --}}
            <div class="bg-white p-6 rounded-[28px] shadow-sm">
                <h3 class="text-[11px] font-extrabold text-gray-400 mb-5 tracking-widest uppercase">RINCIAN PEMBAYARAN</h3>
                <div class="flex justify-between items-center mb-3 text-gray-500 font-medium text-[14px]">
                    <span>Subtotal ({{ $qty ?? 1 }} Porsi)</span>
                    <span class="font-extrabold text-gray-800">
                        @if($makanan->is_gratis) GRATIS @else Rp {{ number_format($subtotal ?? 0, 0, ',', '.') }} @endif
                    </span>
                </div>
                <div class="flex justify-between items-center mb-5 text-gray-500 font-medium text-[14px]">
                    <span>Biaya Layanan</span>
                    <span class="font-extrabold text-[#189347]">
                        @if($makanan->is_gratis) GRATIS @else Rp 0 @endif
                    </span>
                </div>
                <hr class="border-gray-70 mb-5">
                <div class="flex justify-between items-center">
                    <span class="font-extrabold text-gray-800 text-[17px]">Total Pembayaran</span>
                    <span class="font-extrabold text-[24px] text-[#189347] tracking-tight">
                        @if($makanan->is_gratis) GRATIS @else Rp {{ number_format($subtotal ?? 0, 0, ',', '.') }} @endif
                    </span>
                </div>
            </div>

            {{-- Card 3: Lokasi Penjemputan --}}
            <div class="bg-white p-6 lg:p-8 rounded-[32px] shadow-sm flex-1 flex flex-col">
                <div class="flex items-center gap-2.5 mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-[24px] w-[24px] text-[#189347]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-8-4.5-8-11.8A8 8 0 0112 1.2a8 8 0 018 8c0 7.3-8 11.8-8 11.8z" />
                        <circle cx="12" cy="9.2" r="2.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <h3 class="text-[14px] font-bold text-gray-500 tracking-[0.2em] uppercase">LOKASI RESTO</h3>
                </div>
                
                <div class="flex flex-col md:flex-row gap-6 flex-1 items-stretch">
                    <div id="map-pembayaran-wrapper" onclick="window.open('https://www.google.com/maps?q={{ $makanan->unitBisnis->user->latitude ?? -6.193125 }},{{ $makanan->unitBisnis->user->longitude ?? 106.76483 }}', '_blank')" class="relative w-full md:w-[280px] min-h-[160px] h-full bg-gray-100 rounded-[24px] overflow-hidden border border-gray-100 flex-shrink-0 cursor-pointer group shadow-sm z-0 hover:border-[#189347] hover:ring-4 hover:ring-[#189347]/20 transition-all duration-300">
                        <div id="map-pembayaran" class="absolute inset-0 z-0"></div>
                        <div class="absolute inset-0 z-10 bg-black/5 group-hover:bg-black/10 transition-colors flex items-center justify-center">
                            <div class="bg-white/90 backdrop-blur-sm px-5 py-2.5 rounded-full text-[13px] font-extrabold text-[#189347] shadow-md flex items-center gap-2 transform scale-95 opacity-0 group-hover:scale-100 group-hover:opacity-100 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                                Buka Map
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex-1 text-left flex flex-col justify-center">
                        <h4 class="text-[20px] font-extrabold text-gray-800 mb-2">{{ $makanan->unitBisnis->user->name ?? 'Gerai ShareBite' }}</h4>
                        <p class="text-gray-500 font-medium text-[15px] mb-5 leading-relaxed">{{ $makanan->unitBisnis->user->alamat ?? 'Alamat gerai belum diatur' }}</p>
                        <a href="https://www.google.com/maps?q={{ $makanan->unitBisnis->user->latitude ?? -6.193125 }},{{ $makanan->unitBisnis->user->longitude ?? 106.76483 }}" target="_blank" class="text-[#189347] text-[14px] font-bold flex items-center gap-1.5 hover:underline hover:scale-105 hover:bg-[#E4F2E8] hover:px-2.5 hover:py-1 hover:-mx-2.5 hover:-my-1 hover:rounded-lg transition-all duration-200 w-fit">
                            Buka di Google Maps
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN (QRIS Box) --}}
        <div class="xl:col-span-4">
            <div class="bg-white p-8 lg:p-9 rounded-[32px] shadow-sm flex flex-col h-fit relative z-20">
                <h3 class="font-extrabold text-gray-800 text-[22px] text-left mb-2">Pembayaran QRIS</h3>
                <p class="text-[14px] text-gray-500 text-left mb-8 font-medium leading-relaxed pr-6">Scan QR code berikut dengan aplikasi pembayaran Anda.</p>
                
                <div class="bg-[#EAF4ED] p-5 rounded-[28px] inline-block mx-auto mb-6 w-full max-w-[290px]">
                    <div class="bg-white p-4 rounded-[20px] shadow-sm flex items-center justify-center overflow-hidden aspect-square w-full mx-auto">
                        <canvas id="qr-canvas"></canvas>
                    </div>
                </div>
                
                <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_QRIS.svg" alt="QRIS Logo" class="h-6 mx-auto mb-8">

                <div class="w-full">
                    <div class="flex items-center justify-between border border-[#F9E2E2] bg-[#FCF4F4] rounded-[18px] p-4 mb-5">
                        <div class="flex items-center text-[#B3261E] text-[13px] font-extrabold gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Batas Waktu Pembayaran
                        </div>
                        <div id="payment-timer" class="bg-[#B3261E] text-white px-3.5 py-1.5 rounded-[10px] font-extrabold text-sm tracking-widest tabular-nums">
                            --:--
                        </div>
                    </div>

                    <button type="button" onclick="downloadQR()" class="w-full bg-gradient-to-r from-[#10703B] to-[#1CB764] hover:from-[#0d5c30] hover:to-[#179f57] active:scale-[0.98] text-white font-extrabold text-[15px] py-4 rounded-[18px] transition-all flex items-center justify-center gap-2 mb-4 shadow-sm hover:shadow-md">
                        Unduh QR Code
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                    </button>

                    <div class="flex items-center justify-center gap-1.5 text-gray-400 text-[10px] font-extrabold tracking-widest mt-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        PEMBAYARAN AMAN & TERENKRIPSI
                    </div>
                </div>
            </div>

            <div class="hidden">
                <form action="{{ route('user.pembayaran.proses', $id) }}" method="POST">
                    @csrf <input type="hidden" name="status" value="Berhasil"> <input type="hidden" name="qty" value="{{ $qty ?? 1 }}">
                </form>
                <form id="form-batal-otomatis" action="{{ route('user.pembayaran.proses', $id) }}" method="POST">
                    @csrf <input type="hidden" name="status" value="Gagal"> <input type="hidden" name="qty" value="{{ $qty ?? 1 }}">
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const meta = document.getElementById('qr-meta').dataset;
    const idPesanan = meta.id;
    const slugMenu = meta.slug;

    try {
        const canvas = document.getElementById('qr-canvas');
        if(canvas) {
            let pathUrl = "{{ route('pembayaran.scan.public', ['id' => $id], false) }}";
            let finalUrl = window.location.origin + pathUrl;
            
            QRCode.toCanvas(canvas, finalUrl, { width: 300, margin: 1, color: { dark: '#000000', light: '#ffffff' } });
            canvas.style.width = '100%';
            canvas.style.height = 'auto';
        }
    } catch (e) {}

    try {
        const lat = parseFloat(meta.lat);
        const lng = parseFloat(meta.lng);
        const mapContainer = document.getElementById('map-pembayaran');
        if (mapContainer && !isNaN(lat) && !isNaN(lng)) {
            const map = L.map('map-pembayaran', { zoomControl: false, dragging: true, scrollWheelZoom: false }).setView([lat, lng], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
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
            L.marker([lat, lng], {icon: customIcon}).addTo(map);
        }
    } catch (e) {}

    try {
        const timerEl = document.getElementById('payment-timer');
        if (timerEl) {
            let timeLeft = (meta.seconds !== undefined && meta.seconds !== '') ? parseInt(meta.seconds) : 900;
            
            const interval = setInterval(() => {
                if (timeLeft <= 0) {
                    clearInterval(interval);
                    timerEl.textContent = '00:00';
                    const formBatal = document.getElementById('form-batal-otomatis');
                    if(formBatal) formBatal.submit();
                    return;
                }
                const m = Math.floor(timeLeft / 60);
                const s = timeLeft % 60;
                timerEl.textContent = `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
                timeLeft--;
            }, 1000);
        }
    } catch (e) {}

    setInterval(() => {
        fetch("{{ route('user.pembayaran.check', ['id' => $id]) }}")
        .then(response => response.json())
        .then(data => {
            if (data.status === 'sukses') {
                const formSukses = document.querySelector('form input[value="Berhasil"]').parentElement;
                if(formSukses) formSukses.submit();
            }
        })
        .catch(error => {});
    }, 2000);
});

function downloadQR() {
    const qrCanvas = document.getElementById('qr-canvas');
    if(!qrCanvas) return;
    const meta = document.getElementById('qr-meta').dataset;
    const refCode = meta.ref || 'SB-00000000';
    const totalAmount = meta.total || 'Rp 0';
    const newCanvas = document.createElement('canvas');
    const ctx = newCanvas.getContext('2d');
    const qrSize = qrCanvas.width;
    const extraHeight = 110; 
    newCanvas.width = qrSize;
    newCanvas.height = qrSize + extraHeight;
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, newCanvas.width, newCanvas.height);
    ctx.drawImage(qrCanvas, 0, 0);
    const centerX = newCanvas.width / 2;
    let startY = qrSize + 15;
    ctx.font = 'bold 18px "Segoe UI", Arial, sans-serif'; 
    ctx.fillStyle = '#111827'; 
    ctx.textAlign = 'center';
    ctx.fillText('QRIS · ShareBite', centerX, startY);
    startY += 24;
    ctx.font = '15px "Segoe UI", Arial, sans-serif';
    ctx.fillStyle = '#9CA3AF'; 
    ctx.fillText('Ref: ' + refCode, centerX, startY);
    startY += 30;
    ctx.font = 'bold 20px "Segoe UI", Arial, sans-serif';
    ctx.fillStyle = '#189347'; 
    ctx.fillText('Total: ' + totalAmount, centerX, startY);
    const a = document.createElement('a');
    a.download = 'qris-sharebite-' + refCode + '.png';
    a.href = newCanvas.toDataURL('image/png');
    a.click();
}
</script>
@endsection