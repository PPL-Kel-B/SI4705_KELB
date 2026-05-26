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
     data-slug="{{ $makanan->slug ?? 'nasi-goreng' }}"
     data-nama="{{ $makanan->nama ?? 'Pesanan' }}"
     data-harga="{{ $makanan->harga ?? 0 }}"
     data-qty="{{ $qty ?? 1 }}"
     data-lat="{{ $makanan->latitude ?? -6.193125 }}" 
     data-lng="{{ $makanan->longitude ?? 106.76483 }}"
     style="display:none;">
</div>

{{-- Pembungkus utama dibuat full lebar layar dengan padding standar --}}
<div class="min-h-screen bg-[#F0F7F2] p-6 lg:p-10 font-jakarta w-full relative">
    
    {{-- Header dibuat full-width tanpa max-width constraint --}}
    <div class="flex items-center gap-3 mb-8 w-full max-w-full mx-auto">
        <button onclick="window.history.back()" class="w-9 h-9 bg-[#E3EFE7] text-[#189347] hover:bg-[#D1E6D8] rounded-full flex items-center justify-center transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </button>
        <h1 class="text-[22px] font-extrabold text-gray-800">Pembayaran</h1>
    </div>

    {{-- GRID UTAMA: Diubah menjadi porsi 8 banding 4 agar lebih proporsional saat dilebarkan --}}
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 w-full max-w-full mx-auto pb-20">

        {{-- KOLOM KIRI (Sekarang pakai col-span-8 supaya lebih lega ke samping) --}}
        <div class="xl:col-span-8 flex flex-col gap-6">

            {{-- Card 1: Item Makanan --}}
            <div class="bg-white p-6 lg:p-7 rounded-[28px] shadow-sm">
                <div class="flex gap-6 items-center">
                    <img src="{{ asset('storage/' . ($makanan->gambar ?? '')) }}" onerror="this.src='https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=300&q=80'" alt="Menu" class="w-[110px] h-[110px] object-cover rounded-[20px] flex-shrink-0">
                    
                    <div class="flex-1 flex flex-col justify-between py-1">
                        <div>
                            <div class="flex justify-between items-center mb-1.5">
                                <span class="bg-[#E4F2E8] text-[#189347] text-[10px] font-extrabold px-3 py-1.5 rounded-[10px] tracking-widest uppercase">TERSEDIA</span>
                                <span class="text-[#189347] font-extrabold text-xl">Rp {{ number_format($makanan->harga ?? 0, 0, ',', '.') }}</span>
                            </div>
                            <h2 class="font-extrabold text-gray-800 text-[20px] leading-tight">{{ $makanan->nama ?? 'Nama Makanan' }}</h2>
                            <p class="text-gray-400 text-sm mt-1 font-medium">Sisa stok dari {{ $makanan->resto ?? 'Restoran' }}</p>
                        </div>
                        <div class="flex items-center gap-6 mt-5">
                            <div class="flex items-center bg-[#EAF4ED] rounded-xl px-4 py-1.5 border border-green-50">
                                <span class="font-extrabold text-[14px] text-gray-800">{{ $qty ?? 1 }} Porsi</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 2: Rincian Pembayaran --}}
            <div class="bg-white p-7 rounded-[28px] shadow-sm">
                <h3 class="text-[12px] font-extrabold text-gray-400 mb-6 tracking-widest uppercase">RINCIAN PEMBAYARAN</h3>
                
                <div class="flex justify-between items-center mb-4 text-gray-500 font-medium text-[15px]">
                    <span>Subtotal ({{ $qty ?? 1 }} Porsi)</span>
                    <span class="font-extrabold text-gray-800">Rp {{ number_format($subtotal ?? 0, 0, ',', '.') }}</span>
                </div>
                
                <div class="flex justify-between items-center mb-6 text-gray-500 font-medium text-[15px]">
                    <span>Biaya Layanan</span>
                    <span class="font-extrabold text-[#189347]">Rp 0</span>
                </div>
                
                <hr class="border-gray-100 mb-6">
                
                <div class="flex justify-between items-center">
                    <span class="font-extrabold text-gray-800 text-[19px]">Total Pembayaran</span>
                    <span class="font-extrabold text-[28px] text-[#189347] tracking-tight">Rp {{ number_format($subtotal ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Card 3: Lokasi Penjemputan --}}
            <div class="bg-white p-7 rounded-[28px] shadow-sm">
                <h3 class="text-[12px] font-extrabold text-gray-400 mb-6 tracking-widest uppercase flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#189347]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    LOKASI PENJEMPUTAN
                </h3>
                
                <div class="flex flex-col md:flex-row gap-6 items-center">
                    
                    {{-- BUNGKUSAN MAP --}}
                    <div onclick="window.open('https://www.google.com/maps?q={{ $makanan->latitude ?? -6.193125 }},{{ $makanan->longitude ?? 106.76483 }}', '_blank')" class="relative w-full md:w-[220px] h-36 bg-[#E5EAE5] rounded-[20px] overflow-hidden border border-gray-200 cursor-pointer group shadow-sm">
                        <div id="map-pembayaran" class="absolute inset-0 pointer-events-none"></div>
                        <div class="absolute inset-0 z-10 bg-black/5 group-hover:bg-black/10 transition-colors flex items-center justify-center">
                            <div class="bg-white/90 backdrop-blur-sm px-4 py-2 rounded-full text-[13px] font-extrabold text-[#189347] shadow-md flex items-center gap-2 transform scale-95 opacity-90 group-hover:scale-100 group-hover:opacity-100 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                                Buka Map
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex-1 pr-4">
                        <h4 class="font-extrabold text-gray-800 text-[19px] mb-2">{{ $makanan->resto ?? 'Restoran' }}</h4>
                        <p class="text-[14px] text-gray-500 mb-4 leading-relaxed font-medium">{{ $makanan->alamat ?? 'Alamat belum diatur' }}</p>
                        <a href="https://www.google.com/maps?q={{ $makanan->latitude ?? -6.193125 }},{{ $makanan->longitude ?? 106.76483 }}" target="_blank" class="text-[#189347] text-[13px] font-extrabold flex items-center gap-1.5 hover:underline w-fit">
                            Petunjuk Arah
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN (QRIS Box - Porsi lebih seimbang col-span-4) --}}
        <div class="xl:col-span-4">
            <div class="bg-white p-8 lg:p-9 rounded-[32px] shadow-sm flex flex-col">
                <h3 class="font-extrabold text-gray-800 text-[22px] text-left mb-2">Pembayaran QRIS</h3>
                <p class="text-[14px] text-gray-500 text-left mb-8 font-medium leading-relaxed pr-6">Scan QR code berikut dengan aplikasi pembayaran Anda.</p>
                
                {{-- Box QR Code --}}
                <div class="bg-[#EAF4ED] p-5 rounded-[28px] inline-block mx-auto mb-6 w-full max-w-[290px]">
                    <div class="bg-white p-4 rounded-[20px] shadow-sm flex items-center justify-center overflow-hidden aspect-square w-full mx-auto">
                        <canvas id="qr-canvas"></canvas>
                    </div>
                </div>
                
                <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_QRIS.svg" alt="QRIS Logo" class="h-6 mx-auto mb-6">

                <div class="w-full">
                    <div class="flex items-center justify-between border border-[#F9E2E2] bg-[#FCF4F4] rounded-[18px] p-4 mb-5">
                        <div class="flex items-center text-[#B3261E] text-[13px] font-extrabold gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Batas Waktu Pembayaran
                        </div>
                        <div id="payment-timer" class="bg-[#B3261E] text-white px-3.5 py-1.5 rounded-[10px] font-extrabold text-sm tracking-widest tabular-nums">
                            --:--
                        </div>
                    </div>

                    <button type="button" onclick="downloadQR()" class="w-full bg-[#189347] hover:bg-[#147a3a] text-white font-extrabold text-[15px] py-4 rounded-[18px] transition-all flex items-center justify-center gap-2 mb-6">
                        Unduh QR Code
                    </button>
                </div>
            </div>

            {{-- TOMBOL SIMULASI (DISEMBUNYIKAN pakai class 'hidden' tapi tetap dipakai JS) --}}
            <div class="hidden mt-6 gap-3 z-10 relative">
                <form action="{{ route('user.pembayaran.proses', $makanan->slug ?? 'nasi-goreng') }}" method="POST" class="w-1/2">
                    @csrf
                    <input type="hidden" name="status" value="Berhasil">
                    <input type="hidden" name="qty" value="{{ $qty ?? 1 }}">
                    <button type="submit" class="w-full bg-blue-50 text-blue-600 font-bold py-3 rounded-[16px]">
                        Simulasi Sukses
                    </button>
                </form>
                <form id="form-batal-otomatis" action="{{ route('user.pembayaran.proses', $makanan->slug ?? 'nasi-goreng') }}" method="POST" class="w-1/2">
                    @csrf
                    <input type="hidden" name="status" value="Gagal">
                    <input type="hidden" name="qty" value="{{ $qty ?? 1 }}">
                    <button type="submit" class="w-full bg-red-50 text-red-600 font-bold py-3 rounded-[16px]">
                        Simulasi Gagal
                    </button>
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
    const slugMenu = meta.slug;

    // ==========================================
    // 1. RENDER QR CODE (BISA DI-SCAN HP -> LAPTOP PINDAH)
    // ==========================================
    try {
        const canvas = document.getElementById('qr-canvas');
        if(canvas) {
            // MENGGUNAKAN ROUTE PUBLIK BARU (Tanpa Auth)
            let rawUrl = "{{ route('pembayaran.scan.public', ['slug' => $makanan->slug ?? 'nasi-goreng']) }}";
            
            const ipWifiKamu = "192.168.100.101:8000"; 
            let sandboxUrl = rawUrl.replace('127.0.0.1:8000', ipWifiKamu).replace('localhost:8000', ipWifiKamu);
            
            QRCode.toCanvas(canvas, sandboxUrl, { 
                width: 220, margin: 1, color: { dark: '#000000', light: '#ffffff' }
            });
        }
    } catch (e) { console.error("QR Error", e); }

    // ==========================================
    // 2. RENDER MAP LEAFLET
    // ==========================================
    try {
        const lat = parseFloat(meta.lat);
        const lng = parseFloat(meta.lng);
        const mapContainer = document.getElementById('map-pembayaran');
        
        if (mapContainer && !isNaN(lat) && !isNaN(lng)) {
            const map = L.map('map-pembayaran', { zoomControl: false, dragging: false, scrollWheelZoom: false, doubleClickZoom: false }).setView([lat, lng], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

            const customIcon = L.divIcon({
                className: 'custom-leaflet-marker',
                html: `<div class="w-14 h-14 bg-[#0F7635]/30 rounded-full flex items-center justify-center">
                           <div class="w-9 h-9 bg-[#0F7635] rounded-full shadow-md flex items-center justify-center">
                               <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                   <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                               </svg>
                           </div>
                       </div>`,
                iconSize: [56, 56], iconAnchor: [28, 28] 
            });
            L.marker([lat, lng], {icon: customIcon}).addTo(map);
        }
    } catch (e) {}

    // ==========================================
    // 3. RENDER TIMER (Tahan Refresh)
    // ==========================================
    try {
        const timerEl = document.getElementById('payment-timer');
        if (timerEl) {
            const cleanName = slugMenu.replace(/[^a-zA-Z0-9]/g, '_').toLowerCase();
            const storageKey = 'timer_bayar_' + cleanName;
            
            let expireTime = localStorage.getItem(storageKey);

            if (expireTime) {
                expireTime = parseInt(expireTime, 10);
            }

            if (!expireTime || isNaN(expireTime) || expireTime < Date.now()) {
                expireTime = Date.now() + (15 * 60 * 1000); 
                localStorage.setItem(storageKey, expireTime.toString());
            }

            const interval = setInterval(() => {
                const now = Date.now();
                const timeLeft = Math.floor((expireTime - now) / 1000); 

                if (timeLeft <= 0) {
                    clearInterval(interval);
                    timerEl.textContent = '00:00';
                    localStorage.removeItem(storageKey); 
                    
                    const formBatal = document.getElementById('form-batal-otomatis');
                    if(formBatal) formBatal.submit();
                    return;
                }

                const m = Math.floor(timeLeft / 60);
                const s = timeLeft % 60;
                timerEl.textContent = `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
            }, 1000);
        }
    } catch (e) {}

    // ==========================================
    // 4. AJAX POLLING: MATA-MATA CEK STATUS SCAN
    // ==========================================
    setInterval(() => {
        fetch("{{ route('user.pembayaran.check', ['slug' => $makanan->slug ?? 'nasi-goreng']) }}")
        .then(response => response.json())
        .then(data => {
            if (data.status === 'sukses') {
                const formSukses = document.querySelector('form input[value="Berhasil"]').parentElement;
                if(formSukses) {
                    formSukses.submit();
                }
            }
        })
        .catch(error => {});
    }, 2000);

});

function downloadQR() {
    const src = document.getElementById('qr-canvas');
    if(!src) return;
    const a = document.createElement('a');
    a.download = 'qris-sharebite.png';
    a.href = src.toDataURL('image/png');
    a.click();
}
</script>
@endsection