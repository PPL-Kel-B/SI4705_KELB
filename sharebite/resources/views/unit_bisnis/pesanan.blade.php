@extends('layouts.unit_bisnis')
@section('content')
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-[16px] relative mb-6 font-bold shadow-sm flex items-center gap-2" role="alert">
            <svg class="w-5 h-5 text-green-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4 pt-2">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-800">Pesanan Masuk</h1>
            <p class="text-gray-500 mt-1 font-medium">Kelola distribusi makanan berlebih Anda hari ini secara efisien.</p>
            <div class="relative flex items-center bg-[#e9eeeb] rounded-full px-4 py-2.5 w-64 md:w-80 mt-4">
                <svg class="w-5 h-5 text-gray-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text"
                       id="search-pesanan"
                       placeholder="Cari pesanan atau menu..."
                       class="bg-transparent border-none outline-none w-full ml-3 text-sm text-gray-700 font-medium placeholder-gray-500 focus:ring-0 focus:outline-none">
                <button id="clear-search" class="hidden text-gray-400 hover:text-gray-600 transition ml-2 focus:outline-none shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
        <a href="{{ route('unit.pesanan.verifikasi') }}" class="bg-gradient-to-r from-[#10703B] to-[#1CB764] hover:from-[#0d5c30] hover:to-[#179f57] text-white px-6 py-3.5 rounded-2xl font-bold shadow-md hover:shadow-lg flex items-center gap-2.5 transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8V5a2 2 0 012-2h3M16 3h3a2 2 0 012 2v3M3 16v3a2 2 0 012 2h3M21 16v3a2 2 0 01-2 2h-3M12 11a3 3 0 100-6 3 3 0 000 6z M6 19v-1a4 4 0 014-4h4a4 4 0 014 4v1" />
            </svg>
            Verifikasi Code
        </a>
    </div>

    {{-- STATISTIK CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        {{-- Card 1: Total Pesanan --}}
        <div class="bg-white p-6 rounded-[24px] shadow-sm border border-gray-100 border-l-[6px] border-l-[#189347] relative overflow-hidden">
            <div class="flex justify-between items-start mb-2">
                <p class="text-[13px] font-bold text-[#189347] opacity-80">Total Pesanan</p>
                <div class="bg-[#E4F2E8] p-2 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#189347]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                </div>
            </div>
            <h2 class="text-4xl font-black text-[#189347] mb-2">{{ $pesanans->count() }}</h2>
            <p class="text-[11px] font-bold text-[#189347]/60">{{ $percentChangeText }}</p>
        </div>

        {{-- Card 2: Menunggu Diambil --}}
        <div class="bg-white p-6 rounded-[24px] shadow-sm border border-gray-100 border-l-[6px] border-l-[#B45309] relative overflow-hidden">
            <div class="flex justify-between items-start mb-2">
                <p class="text-[13px] font-bold text-[#B45309] opacity-80">Menunggu Diambil</p>
                <div class="bg-[#FFF4E5] p-2 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#B45309]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h4" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 10V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        <circle cx="17" cy="17" r="4" stroke="currentColor" stroke-width="1.5" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 15.5v1.5h1.5" />
                    </svg>
                </div>
            </div>
            <h2 class="text-4xl font-black text-[#78350F] mb-2">{{ $menunggu->count() }}</h2>
            <p class="text-[11px] font-bold text-[#B45309]/60">Prioritas utama hari ini</p>
        </div>

        {{-- Card 3: Selesai Distribusi --}}
        <div class="bg-white p-6 rounded-[24px] shadow-sm border border-gray-100 border-l-[6px] border-l-[#7E22CE] relative overflow-hidden">
            <div class="flex justify-between items-start mb-2">
                <p class="text-[13px] font-bold text-[#7E22CE] opacity-80">Selesai Distribusi</p>
                <div class="bg-[#F3E8FF] p-2 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#7E22CE]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>
                </div>
            </div>
            <h2 class="text-4xl font-black text-[#5B21B6] mb-2">{{ $selesai->count() }}</h2>
            <p class="text-[11px] font-bold text-[#7E22CE]/60">Telah diverifikasi sistem</p>
        </div>
    </div>

    {{-- FILTER TABS & JUDUL --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-6">
        <h2 class="text-xl font-extrabold text-gray-800 flex items-center gap-2 mb-4 md:mb-0">
            Daftar Pesanan Aktif
        </h2>
        
        <div class="flex bg-white rounded-full p-1.5 shadow-sm border border-gray-100">
            <button id="tab-terbaru" class="px-5 py-2 bg-[#E4F2E8] text-[#189347] font-extrabold text-[12px] rounded-full tracking-wide transition">Terbaru</button>
            <button id="tab-menunggu" class="px-5 py-2 text-gray-400 font-extrabold text-[12px] hover:text-gray-600 rounded-full tracking-wide transition">Menunggu</button>
            <button id="tab-selesai" class="px-5 py-2 text-gray-400 font-extrabold text-[12px] hover:text-gray-600 rounded-full tracking-wide transition">Selesai</button>
        </div>
    </div>

    {{-- GRID KARTU PESANAN --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12" id="orders-container">
        @forelse($pesanans as $p)
            @php
                $isSelesai = $p->status === 'selesai';
                $isTidakDiambil = $p->isTidakDiambil();
            @endphp
            <div class="order-card bg-white rounded-[28px] shadow-sm border border-gray-50 flex flex-col overflow-hidden hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300" data-status="{{ ($isSelesai || $isTidakDiambil) ? 'selesai' : 'menunggu' }}">
                {{-- Gambar Full Edge --}}
                <div class="relative h-48 w-full">
                    <img src="{{ asset('storage/' . ($p->menuAktif->masterMakanan->foto ?? '')) }}" onerror="this.src='https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=500&q=80'" class="w-full h-full object-cover {{ $isSelesai ? 'opacity-60 grayscale-[10%]' : ($isTidakDiambil ? 'opacity-40 grayscale-[30%]' : '') }}">
                    @if($isSelesai)
                        <span class="absolute top-4 right-4 bg-[#52C384] text-white text-[10px] font-extrabold px-3.5 py-1.5 rounded-full uppercase tracking-widest shadow-md">
                            Selesai
                        </span>
                    @elseif($isTidakDiambil)
                        <span class="absolute top-4 right-4 bg-[#EF4444] text-white text-[10px] font-extrabold px-3.5 py-1.5 rounded-full uppercase tracking-widest shadow-md">
                            Tidak Diambil
                        </span>
                    @else
                        <span class="absolute top-4 right-4 bg-[#D97706] text-white text-[10px] font-extrabold px-3.5 py-1.5 rounded-full uppercase tracking-widest shadow-md">
                            Menunggu Diambil
                        </span>
                    @endif
                </div>
                
                {{-- Konten Kartu --}}
                <div class="p-6 flex flex-col flex-1">
                    <h3 class="text-xl font-extrabold {{ $isSelesai ? 'text-[#189347]/50' : ($isTidakDiambil ? 'text-red-800/40' : 'text-[#189347]') }} leading-tight mb-1 truncate">{{ $p->menuAktif->masterMakanan->nama_makanan ?? 'Nama Makanan' }}</h3>
                    <p class="text-[14px] font-bold {{ $isSelesai ? 'text-[#189347]/40' : ($isTidakDiambil ? 'text-red-800/30' : 'text-[#189347]/70') }} mb-6">{{ $p->jumlah_porsi }} Porsi</p>
                    
                    <div class="flex justify-between items-start mb-8">
                        <div>
                            <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">PENERIMA</p>
                            <p class="text-[13px] font-black text-gray-800 truncate max-w-[120px]">{{ $p->user->name ?? 'Relawan' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1 text-right">WAKTU PESAN</p>
                            <p class="text-[13px] font-black text-gray-800 text-right">{{ \Carbon\Carbon::parse($p->waktu_pesan)->translatedFormat('d M Y') }}</p>
                            <p class="text-[11px] font-medium text-gray-500 text-right mt-0.5">{{ \Carbon\Carbon::parse($p->waktu_pesan)->format('H:i') }} WIB</p>
                        </div>
                    </div>
                    
                    @if($isSelesai || $isTidakDiambil)
                        <a href="{{ route('unit.riwayat.show', $p->id) }}" class="mt-auto w-full bg-white hover:bg-gray-50 border border-gray-200 text-gray-500 text-center py-3.5 rounded-xl font-bold text-[14px] flex justify-center items-center gap-2 transition">
                            Detail Riwayat
                        </a>
                    @else
                        <a href="{{ route('unit.pesanan.show', $p->id) }}" class="mt-auto w-full bg-[#189347] hover:bg-[#147a3a] text-white text-center py-3.5 rounded-xl font-bold text-[14px] flex justify-center items-center gap-2 transition">
                            Detail Pesanan 
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white p-12 rounded-[28px] text-center border border-gray-100 shadow-sm flex flex-col items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0V9a2 2 0 00-2-2H6a2 2 0 00-2 2v2m16 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <h4 class="text-lg font-extrabold text-gray-700 mb-1">Belum Ada Pesanan</h4>
                <p class="text-sm text-gray-400">Pesanan yang sudah dibayar oleh relawan akan muncul di sini.</p>
            </div>
        @endforelse
        
        {{-- Search Empty State --}}
        <div id="no-search-results" class="hidden col-span-full bg-white p-12 rounded-[28px] text-center border border-gray-100 shadow-sm flex flex-col items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            <h4 class="text-lg font-extrabold text-gray-700 mb-1">Pesanan Tidak Ditemukan</h4>
            <p class="text-sm text-gray-400">Tidak ada pesanan yang cocok dengan kata kunci pencarian Anda.</p>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabTerbaru = document.getElementById('tab-terbaru');
            const tabMenunggu = document.getElementById('tab-menunggu');
            const tabSelesai = document.getElementById('tab-selesai');
            const searchInput = document.getElementById('search-pesanan');
            const cards = document.querySelectorAll('.order-card');
            const noResults = document.getElementById('no-search-results');
            const tabs = [tabTerbaru, tabMenunggu, tabSelesai];
            
            const clearSearchBtn = document.getElementById('clear-search');
            
            let currentStatus = 'terbaru';

            function setActiveTab(activeTab) {
                tabs.forEach(tab => {
                    tab.classList.remove('bg-[#E4F2E8]', 'text-[#189347]');
                    tab.classList.add('text-gray-400', 'hover:text-gray-600');
                });
                activeTab.classList.remove('text-gray-400', 'hover:text-gray-600');
                activeTab.classList.add('bg-[#E4F2E8]', 'text-[#189347]');
            }

            function filterCards() {
                const query = searchInput ? searchInput.value.toLowerCase().trim() : '';
                let visibleCount = 0;

                if (clearSearchBtn) {
                    if (query.length > 0) {
                        clearSearchBtn.classList.remove('hidden');
                    } else {
                        clearSearchBtn.classList.add('hidden');
                    }
                }

                cards.forEach(card => {
                    const matchesStatus = (currentStatus === 'terbaru') || (card.dataset.status === currentStatus);
                    
                    const foodName = card.querySelector('h3') ? card.querySelector('h3').textContent.toLowerCase() : '';
                    const recipientName = card.querySelector('.font-black.text-gray-800') ? card.querySelector('.font-black.text-gray-800').textContent.toLowerCase() : '';
                    const matchesSearch = foodName.includes(query) || recipientName.includes(query);

                    if (matchesStatus && matchesSearch) {
                        card.style.display = 'flex';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                if (noResults) {
                    if (visibleCount === 0 && cards.length > 0) {
                        noResults.classList.remove('hidden');
                        noResults.classList.add('flex');
                    } else {
                        noResults.classList.remove('flex');
                        noResults.classList.add('hidden');
                    }
                }
            }

            tabTerbaru.addEventListener('click', function() {
                setActiveTab(tabTerbaru);
                currentStatus = 'terbaru';
                filterCards();
            });

            tabMenunggu.addEventListener('click', function() {
                setActiveTab(tabMenunggu);
                currentStatus = 'menunggu';
                filterCards();
            });

            tabSelesai.addEventListener('click', function() {
                setActiveTab(tabSelesai);
                currentStatus = 'selesai';
                filterCards();
            });

            if (searchInput) {
                searchInput.addEventListener('input', filterCards);
            }

            if (clearSearchBtn && searchInput) {
                clearSearchBtn.addEventListener('click', function() {
                    searchInput.value = '';
                    filterCards();
                    searchInput.focus();
                });
            }
        });
    </script>
    @endpush

    {{-- BANNER BAWAH (PANDUAN & BANTUAN) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Banner Panduan Pengambilan --}}
        <div class="bg-[#10703B] rounded-[28px] p-8 lg:p-10 relative overflow-hidden flex flex-col justify-center hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300">
            {{-- Watermark Ikon Tameng --}}
            <svg xmlns="http://www.w3.org/2000/svg" class="absolute -bottom-10 -right-10 w-64 h-64 text-white opacity-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
            
            <div class="relative z-10">
                <h3 class="text-2xl lg:text-3xl font-black text-white mb-3">Panduan Pengambilan</h3>
                <p class="text-white/80 text-[14px] leading-relaxed mb-8 max-w-sm">Pastikan relawan menunjukkan kode unik 6-digit yang valid sebelum menyerahkan makanan untuk menjamin keamanan distribusi.</p>
                <a href="{{ route('unit.pesanan.panduan') }}" class="text-white font-extrabold text-[13px] uppercase tracking-widest flex items-center gap-2 hover:underline w-fit">
                    BACA SELENGKAPNYA 
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                </a>
            </div>
        </div>

        {{-- Banner Butuh Bantuan --}}
        <div class="bg-[#FFEDD5] rounded-[28px] p-8 lg:p-10 flex flex-col justify-center hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300">
            <h3 class="text-2xl lg:text-3xl font-black text-gray-800 mb-3">Butuh Bantuan?</h3>
            <p class="text-gray-600 text-[14px] leading-relaxed mb-8 max-w-md">Tim dukungan ShareBite siap 24/7 membantu Anda.</p>
            
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('unit.chat') }}" class="bg-white hover:bg-gray-50 text-gray-800 font-extrabold px-6 py-3.5 rounded-[14px] shadow-sm text-[13px] tracking-wide transition">
                    Hubungi CS
                </a>
            </div>
        </div>
    </div>
@endsection