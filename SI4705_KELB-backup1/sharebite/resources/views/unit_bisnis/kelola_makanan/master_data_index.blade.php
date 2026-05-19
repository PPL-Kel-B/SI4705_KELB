@extends('layouts.unit_bisnis')

@section('title', 'Master Data Makanan')

@section('content')

{{-- ===== PAGE HEADER & TABS ===== --}}
<div class="mb-8 -mt-6">
    <h1 class="text-3xl font-extrabold text-[#0a2e1f] mb-4">Kelola Menu Makanan</h1>
    <div class="flex items-center space-x-6 border-b border-gray-200">
        <a href="{{ route('unit.kelola_makanan') }}" class="pb-2 text-sm font-semibold text-gray-500 hover:text-[#1cb764] transition">Menu Aktif</a>
        <a href="{{ route('unit.master_data.index') }}" class="pb-2 text-sm font-extrabold text-[#1cb764] border-b-2 border-[#1cb764]">Master Data</a>
    </div>
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
            popup.style.transition = 'opacity 0.5s ease';
            setTimeout(() => popup.remove(), 500);
        }
    }, 5000);
</script>
@endif

{{-- ===== ALERT MESSAGES ===== --}}
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
    <form method="GET" action="{{ route('unit.master_data.index') }}" class="flex-1 max-w-md">
        <div class="relative flex items-center bg-[#e9eeeb] rounded-full px-4 py-3">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari master data makanan ..."
                   onchange="this.form.submit()"
                   class="bg-transparent border-none outline-none w-full ml-3 text-sm text-gray-700 font-medium placeholder-gray-500">
        </div>
    </form>

    {{-- Tambah Button --}}
    <a href="{{ route('unit.master_data.create') }}"
       class="flex items-center gap-2 bg-[#1cb764] hover:bg-[#19a55a] text-white font-bold px-6 py-3.5 rounded-2xl shadow-md transition-all duration-200">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
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
            <div class="flex items-center space-x-2 border-t border-gray-100 pt-4 mb-4">
                {{-- Edit Master --}}
                <a href="{{ route('unit.master_data.edit', $makanan->id) }}"
                   class="flex-1 flex items-center justify-center space-x-1.5 bg-[#fdf4e9] text-[#cf8129] hover:bg-[#faeedd] font-bold text-xs px-3 py-2.5 rounded-xl transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <span>Edit Master</span>
                </a>

                {{-- Delete Master --}}
                <button type="button" onclick="confirmDelete('{{ $makanan->id }}', '{{ addslashes($makanan->nama_makanan) }}')"
                        class="w-10 h-10 flex items-center justify-center border border-red-100 text-red-400 hover:text-red-600 bg-red-50 hover:bg-red-100 rounded-xl transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
                <form id="delete-form-{{ $makanan->id }}" action="{{ route('unit.master_data.destroy', $makanan->id) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
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

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
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

// Tambahkan Fungsi ini untuk Delete
function confirmDelete(id, name) {
    if(typeof Swal === 'undefined') return alert('Library SweetAlert tidak ditemukan!');
    
    Swal.fire({
        title: 'Hapus Master Data?',
        text: `Apakah Anda yakin ingin menghapus "${name}"? Data yang sudah dihapus tidak dapat dikembalikan.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#9ca3af',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        customClass: {
            confirmButton: 'rounded-xl shadow-md',
            cancelButton: 'rounded-xl shadow-md'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}
</script>
@endpush