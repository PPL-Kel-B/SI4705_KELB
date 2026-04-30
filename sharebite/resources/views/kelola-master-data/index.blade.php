@extends('layouts.app')

@section('title', 'Master Data Makanan')

@section('page-title', 'Kelola Menu Makanan')

@section('header-tabs')
<div class="flex items-center space-x-1 mt-3">
    <a href="#"
       class="px-4 py-1.5 text-sm font-medium text-gray-500 hover:text-brand transition-colors rounded-lg hover:bg-brand-light">
        Menu Aktif
    </a>
    <a href="{{ route('kelola-master-data.index') }}"
       class="relative px-4 py-1.5 text-sm font-semibold text-brand transition-colors">
        Master Data
        <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-brand rounded-full"></span>
    </a>
</div>
@endsection

@section('content')

{{-- ===== ALERT MESSAGES ===== --}}
@if(session('success'))
<div id="alert-success"
     class="alert-animate mb-6 flex items-center space-x-3 bg-green-50 border border-green-200 text-green-800 px-5 py-4 rounded-2xl shadow-sm">
    <div class="w-8 h-8 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
        </svg>
    </div>
    <p class="text-sm font-semibold">{{ session('success') }}</p>
    <button onclick="document.getElementById('alert-success').remove()"
            class="ml-auto text-green-500 hover:text-green-700 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>
@endif

@if(session('error'))
<div id="alert-error"
     class="alert-animate mb-6 flex items-center space-x-3 bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-2xl shadow-sm">
    <div class="w-8 h-8 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0">
        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                  d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </div>
    <p class="text-sm font-semibold">{{ session('error') }}</p>
    <button onclick="document.getElementById('alert-error').remove()"
            class="ml-auto text-red-500 hover:text-red-700 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>
@endif

{{-- ===== SEARCH & ADD BUTTON ===== --}}
<div class="flex items-center justify-between gap-4 mb-8">

    {{-- Search pill --}}
    <form method="GET" action="{{ route('kelola-master-data.index') }}" class="flex-1 max-w-xs">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <input type="text"
                   id="search-input"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari master data makanan ..."
                   onchange="this.form.submit()"
                   class="w-full bg-white border border-gray-200 rounded-full pl-11 pr-4 py-3 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-400 shadow-sm transition">
        </div>
    </form>

    {{-- Tambah Button --}}
    <a href="{{ route('kelola-master-data.create') }}"
       id="btn-tambah-master"
       class="flex items-center gap-2 text-white font-semibold px-6 py-3 rounded-full whitespace-nowrap transition-all duration-200"
       style="background:#1A6B3C; box-shadow: 0 4px 18px rgba(26,107,60,0.35);">
        <span class="w-5 h-5 flex items-center justify-center bg-white/20 rounded-full text-white font-bold text-base leading-none">+</span>
        <span>Tambah Master Menu</span>
    </a>
</div>

{{-- ===== FOOD GRID ===== --}}
@if($makanans->isEmpty())
<div class="flex flex-col items-center justify-center py-24 text-center">
    <div class="w-20 h-20 bg-gray-100 rounded-3xl flex items-center justify-center mb-4">
        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
    </div>
    <h3 class="text-lg font-bold text-gray-700 mb-1">
        {{ request('search') ? 'Makanan tidak ditemukan' : 'Belum ada data makanan' }}
    </h3>
    <p class="text-sm text-gray-400">
        {{ request('search') ? 'Coba kata kunci lain.' : 'Tambahkan master menu pertama Anda.' }}
    </p>
</div>
@else
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @foreach($makanans as $makanan)
    <div class="food-card bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-shadow duration-200 cursor-pointer">

        {{-- Food Image --}}
        <div class="relative bg-gray-100 w-full overflow-hidden" style="aspect-ratio: 4/3;">
            @if($makanan->foto)
                <img src="{{ asset('storage/' . $makanan->foto) }}"
                     alt="{{ $makanan->nama_makanan }}"
                     class="w-full h-full object-cover"
                     onerror="this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&q=80'">
            @else
                <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&q=80"
                     alt="{{ $makanan->nama_makanan }}"
                     class="w-full h-full object-cover">
            @endif
        </div>

        {{-- Card Body --}}
        <div class="p-5">
            {{-- Category --}}
            <p class="text-[10px] font-extrabold tracking-[0.12em] uppercase text-amber-500 mb-1">
                {{ $makanan->kategori ?? 'Umum' }}
            </p>

            {{-- Name --}}
            <h3 class="text-base font-bold text-gray-900 leading-snug mb-2 line-clamp-2">
                {{ $makanan->nama_makanan }}
            </h3>

            {{-- Weight --}}
            <div class="flex items-center space-x-1.5 text-gray-400 text-sm mb-4">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                </svg>
                <span class="font-medium">
                    {{ $makanan->berat ? number_format($makanan->berat, 2) . ' kg' : '— kg' }}
                </span>
            </div>

            {{-- Actions --}}
            <div class="flex items-center space-x-2">
                {{-- Edit --}}
                <a href="{{ route('kelola-master-data.edit', $makanan->id) }}"
                   class="flex-1 flex items-center justify-center space-x-1.5 border border-gray-200 text-gray-600 hover:border-brand hover:text-brand hover:bg-brand-light font-semibold text-xs px-3 py-2.5 rounded-xl transition-all duration-200">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <span>Edit Master</span>
                </a>

                {{-- Delete --}}
                <form action="{{ route('kelola-master-data.destroy', $makanan) }}"
                      method="POST"
                      class="delete-form"
                      data-name="{{ $makanan->nama_makanan }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-9 h-9 flex items-center justify-center border border-red-200 text-red-400 hover:bg-red-500 hover:text-white hover:border-red-500 rounded-xl transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Pagination --}}
@if($makanans->hasPages())
<div class="mt-10 flex justify-center">
    <div class="flex items-center space-x-2">
        {{-- Previous --}}
        @if($makanans->onFirstPage())
            <span class="w-10 h-10 flex items-center justify-center bg-white border border-gray-200 rounded-xl text-gray-300 cursor-not-allowed">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </span>
        @else
            <a href="{{ $makanans->previousPageUrl() }}"
               class="w-10 h-10 flex items-center justify-center bg-white border border-gray-200 rounded-xl text-gray-600 hover:bg-brand hover:text-white hover:border-brand transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
        @endif

        {{-- Pages --}}
        @foreach($makanans->getUrlRange(max(1, $makanans->currentPage() - 2), min($makanans->lastPage(), $makanans->currentPage() + 2)) as $page => $url)
            @if($page == $makanans->currentPage())
                <span class="w-10 h-10 flex items-center justify-center bg-brand text-white font-bold rounded-xl shadow-md" style="box-shadow: 0 4px 12px rgba(28,183,100,0.3);">
                    {{ $page }}
                </span>
            @else
                <a href="{{ $url }}"
                   class="w-10 h-10 flex items-center justify-center bg-white border border-gray-200 rounded-xl text-gray-600 hover:bg-brand-light hover:text-brand hover:border-brand transition-all font-medium">
                    {{ $page }}
                </a>
            @endif
        @endforeach

        {{-- Next --}}
        @if($makanans->hasMorePages())
            <a href="{{ $makanans->nextPageUrl() }}"
               class="w-10 h-10 flex items-center justify-center bg-white border border-gray-200 rounded-xl text-gray-600 hover:bg-brand hover:text-white hover:border-brand transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        @else
            <span class="w-10 h-10 flex items-center justify-center bg-white border border-gray-200 rounded-xl text-gray-300 cursor-not-allowed">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </span>
        @endif
    </div>
</div>
<p class="text-center text-xs text-gray-400 mt-3">
    Menampilkan {{ $makanans->firstItem() }}–{{ $makanans->lastItem() }} dari {{ $makanans->total() }} makanan
</p>
@endif

@endif

{{-- ===== DELETE CONFIRM MODAL ===== --}}
<div id="delete-modal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-3xl shadow-2xl p-8 max-w-sm w-full mx-4 animate-bounce-in">
        <div class="w-16 h-16 bg-red-100 rounded-2xl flex items-center justify-center mx-auto mb-5">
            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
        </div>
        <h3 class="text-xl font-extrabold text-gray-900 text-center mb-2">Hapus Makanan?</h3>
        <p class="text-sm text-gray-500 text-center mb-7">
            Anda akan menghapus <span id="delete-food-name" class="font-bold text-gray-800"></span>.
            Tindakan ini tidak dapat dibatalkan.
        </p>
        <div class="flex space-x-3">
            <button id="cancel-delete"
                    class="flex-1 py-3 border border-gray-200 rounded-2xl text-gray-600 font-semibold hover:bg-gray-50 transition">
                Batal
            </button>
            <button id="confirm-delete"
                    class="flex-1 py-3 bg-red-500 hover:bg-red-600 text-white rounded-2xl font-semibold transition shadow-lg">
                Ya, Hapus
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ===== DELETE CONFIRM MODAL =====
    const modal        = document.getElementById('delete-modal');
    const foodNameEl   = document.getElementById('delete-food-name');
    const cancelBtn    = document.getElementById('cancel-delete');
    const confirmBtn   = document.getElementById('confirm-delete');
    let   pendingForm  = null;

    document.querySelectorAll('.delete-form').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            pendingForm  = form;
            foodNameEl.textContent = '"' + form.dataset.name + '"';
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    });

    cancelBtn.addEventListener('click', function () {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        pendingForm = null;
    });

    confirmBtn.addEventListener('click', function () {
        if (pendingForm) {
            pendingForm.submit();
        }
    });

    // Close on backdrop click
    modal.addEventListener('click', function (e) {
        if (e.target === modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            pendingForm = null;
        }
    });

    // ===== AUTO DISMISS ALERTS =====
    setTimeout(function () {
        ['alert-success', 'alert-error'].forEach(function (id) {
            const el = document.getElementById(id);
            if (el) {
                el.style.transition = 'opacity 0.5s';
                el.style.opacity    = '0';
                setTimeout(() => el.remove(), 500);
            }
        });
    }, 4000);

});
</script>
@endpush