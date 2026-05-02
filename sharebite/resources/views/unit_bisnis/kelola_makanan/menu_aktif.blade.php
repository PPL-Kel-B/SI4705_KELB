@extends('layouts.unit_bisnis')

@section('title', 'Menu Aktif')

@section('content')

{{-- ===== PAGE HEADER & TABS ===== --}}
<div class="mb-8">
    <h1 class="text-3xl font-extrabold text-[#0a2e1f] mb-4">Kelola Menu Makanan</h1>
    <div class="flex items-center space-x-6 border-b border-gray-200">
        <a href="{{ route('unit.kelola_makanan') }}" class="pb-2 text-sm font-extrabold text-[#1cb764] border-b-2 border-[#1cb764]">Menu Aktif</a>
        <a href="{{ route('unit.master_data.index') }}" class="pb-2 text-sm font-semibold text-gray-500 hover:text-[#1cb764] transition">Master Data</a>
    </div>
</div>

{{-- ===== ACTION BAR ===== --}}
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    {{-- Search --}}
    <form action="{{ route('unit.kelola_makanan') }}" method="GET" class="w-full md:w-96 relative">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pesanan atau menu..." 
               class="w-full bg-[#f4f6f5] border-none rounded-xl pl-10 pr-4 py-3.5 text-sm font-medium text-gray-700 placeholder-gray-400 focus:ring-2 focus:ring-[#1cb764] shadow-sm">
        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
    </form>

    {{-- Buka Menu Button --}}
    <a href="{{ route('unit.menu_aktif.create') }}" class="flex items-center justify-center gap-2 bg-[#1cb764] hover:bg-[#19a55a] text-white font-bold px-6 py-3.5 rounded-xl transition-all shadow-sm">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
        <span>Buka Menu</span>
    </a>
</div>

{{-- ===== SUCCESS POP-UP ANIMATION ===== --}}
@if(session('success'))
<div id="success-popup" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-3xl p-8 shadow-2xl max-w-sm w-full text-center transform transition-all animate-bounce-in">
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-green-500 animate-checkmark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h3 class="text-2xl font-extrabold text-gray-900 mb-2">Berhasil!</h3>
        <p class="text-gray-500 font-medium mb-6">{{ session('success') }}</p>
        <button onclick="document.getElementById('success-popup').remove()" 
                class="w-full bg-[#1cb764] hover:bg-[#19a55a] text-white font-bold py-3.5 rounded-2xl shadow-lg transition-all active:scale-95">
            Oke, Mengerti
        </button>
    </div>
</div>

<style>
    @keyframes bounce-in {
        0% { transform: scale(0.3); opacity: 0; }
        50% { transform: scale(1.05); opacity: 1; }
        70% { transform: scale(0.9); }
        100% { transform: scale(1); }
    }
    .animate-bounce-in {
        animation: bounce-in 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    }
    @keyframes checkmark {
        0% { stroke-dashoffset: 100; opacity: 0; }
        100% { stroke-dashoffset: 0; opacity: 1; }
    }
    .animate-checkmark {
        stroke-dasharray: 100;
        stroke-dashoffset: 100;
        animation: checkmark 0.8s ease-in-out 0.3s forwards;
    }
</style>

<script>
    // Auto remove after 5 seconds
    setTimeout(() => {
        const popup = document.getElementById('success-popup');
        if (popup) {
            popup.style.opacity = '0';
            popup.style.transition = 'opacity 0.5s';
            setTimeout(() => popup.remove(), 500);
        }
    }, 5000);
</script>
@endif

{{-- ===== STATS CARDS ===== --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    {{-- Total Menu Aktif --}}
    <div class="bg-white rounded-3xl p-6 flex items-center gap-5 shadow-sm border border-gray-50">
        <div class="w-14 h-14 rounded-2xl bg-[#eefcf4] flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-[#1cb764]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
        </div>
        <div>
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Total Menu Aktif</p>
            <div class="flex items-end gap-2">
                <span class="text-3xl font-extrabold text-gray-900 leading-none">{{ $totalMenuAktif }}</span>
                <span class="text-sm font-semibold text-gray-500 mb-0.5">Kategori</span>
            </div>
        </div>
    </div>

    {{-- Total Menu Habis --}}
    <div class="bg-white rounded-3xl p-6 flex items-center gap-5 shadow-sm border border-gray-50">
        <div class="w-14 h-14 rounded-2xl bg-[#fee2e2] flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-[#ef4444]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        </div>
        <div>
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Total Menu Habis</p>
            <div class="flex items-end gap-2">
                <span class="text-3xl font-extrabold text-gray-900 leading-none">{{ $totalMenuHabis }}</span>
                <span class="text-sm font-semibold text-gray-500 mb-0.5">Menu</span>
            </div>
        </div>
    </div>

    {{-- Porsi Terjual --}}
    <div class="bg-white rounded-3xl p-6 flex items-center gap-5 shadow-sm border border-gray-50">
        <div class="w-14 h-14 rounded-2xl bg-[#fef3c7] flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-[#f59e0b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Porsi Terjual Hari Ini</p>
            <div class="flex items-end gap-2">
                <span class="text-3xl font-extrabold text-gray-900 leading-none">{{ $porsiTerjualHariIni }}</span>
                <span class="text-sm font-semibold text-gray-500 mb-0.5">Porsi</span>
            </div>
        </div>
    </div>
</div>

{{-- ===== FILTERS (DUMMY) ===== --}}
<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-hide">
        <button class="px-5 py-2.5 rounded-xl bg-[#0a2e1f] text-white text-sm font-bold whitespace-nowrap">Semua</button>
        <button class="px-5 py-2.5 rounded-xl bg-[#e9eeeb] text-gray-600 hover:bg-[#dcfce7] hover:text-[#1cb764] text-sm font-bold whitespace-nowrap transition-colors">Tersedia</button>
        <button class="px-5 py-2.5 rounded-xl bg-[#e9eeeb] text-gray-600 hover:bg-[#fee2e2] hover:text-[#ef4444] text-sm font-bold whitespace-nowrap transition-colors">Segera Habis</button>
        <button class="px-5 py-2.5 rounded-xl bg-[#e9eeeb] text-gray-600 hover:bg-gray-200 text-sm font-bold whitespace-nowrap transition-colors">Habis</button>
    </div>
    
    <div class="flex items-center gap-2 text-sm font-semibold text-gray-500 cursor-pointer hover:text-gray-800 transition-colors bg-white px-4 py-2.5 rounded-xl shadow-sm border border-gray-50">
        <span>Urutkan: Terbaru</span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
    </div>
</div>

{{-- ===== MENU LIST ===== --}}
@if($menuAktifs->isEmpty())
    <div class="bg-white rounded-3xl p-12 shadow-sm border border-gray-50 flex flex-col items-center justify-center text-center mt-6">
        <div class="bg-[#eefcf4] w-24 h-24 rounded-full flex items-center justify-center text-[#1cb764] mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
            </svg>
        </div>
        <h2 class="text-2xl font-extrabold text-gray-800 mb-2">Belum ada makanan aktif</h2>
        <p class="text-gray-500 max-w-md mb-8">Anda belum memiliki menu yang sedang aktif dijual hari ini. Buka menu sekarang untuk mulai berjualan.</p>
        <a href="{{ route('unit.menu_aktif.create') }}" class="inline-flex items-center justify-center gap-2 bg-[#1cb764] hover:bg-[#19a55a] text-white font-bold px-8 py-4 rounded-xl transition-all shadow-md hover:shadow-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
            <span>Buka Menu Pertama</span>
        </a>
    </div>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($menuAktifs as $menu)
        <div class="bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            {{-- Image & Badge --}}
            <div class="relative bg-gray-100 w-full overflow-hidden" style="aspect-ratio: 4/3;">
                @if($menu->masterMakanan->foto)
                    <img src="{{ asset('storage/' . $menu->masterMakanan->foto) }}"
                         alt="{{ $menu->masterMakanan->nama_makanan }}"
                         class="w-full h-full object-cover">
                @else
                    <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&q=80"
                         alt="{{ $menu->masterMakanan->nama_makanan }}"
                         class="w-full h-full object-cover">
                @endif
                
                <div class="absolute top-4 left-4">
                    <span class="bg-[#0a2e1f] text-white text-[10px] font-extrabold px-3 py-1.5 rounded-lg uppercase tracking-widest shadow-md">
                        Tersedia
                    </span>
                </div>
            </div>

            {{-- Card Body --}}
            <div class="p-5">
                <h3 class="text-[17px] font-extrabold text-gray-900 leading-snug mb-2 line-clamp-2">
                    {{ $menu->masterMakanan->nama_makanan }}
                </h3>
                <p class="text-xs text-gray-500 leading-relaxed mb-4 line-clamp-2 min-h-[32px]">
                    {{ $menu->masterMakanan->deskripsi ?? 'Tidak ada deskripsi' }}
                </p>

                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-4 h-4 text-[#1cb764]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    <span class="text-sm font-bold text-gray-800">{{ $menu->stok_porsi }} Porsi</span>
                </div>

                <div class="mb-5">
                    @if($menu->is_gratis)
                        <span class="text-xl font-extrabold text-[#1cb764]">Gratis</span>
                    @else
                        <span class="text-xl font-extrabold text-[#cf8129]">Rp{{ number_format($menu->harga_jual, 0, ',', '.') }}</span>
                    @endif
                </div>

                {{-- Dummy Actions --}}
                <div class="flex items-center gap-3">
                    <button type="button" disabled class="flex-1 py-3 bg-white border border-gray-200 text-gray-600 font-bold text-xs rounded-xl flex items-center justify-center gap-1.5 cursor-not-allowed hover:bg-gray-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        Edit
                    </button>
                    <button type="button" disabled class="flex-1 py-3 bg-white border border-red-200 text-red-500 font-bold text-xs rounded-xl flex items-center justify-center gap-1.5 cursor-not-allowed hover:bg-red-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                        Tutup
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif

@endsection

@push('scripts')
{{-- Additional scripts can go here --}}
@endpush
