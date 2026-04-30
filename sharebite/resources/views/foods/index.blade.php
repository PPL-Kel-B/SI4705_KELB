@extends('layouts.app')

@section('title', 'Kelola Menu Makanan')

@section('content')

<div class="space-y-8">
    {{-- ===== ALERT MESSAGES ===== --}}
    @if(session('success'))
    <div id="alert-success"
         class="alert-animate flex items-center space-x-3 bg-green-50 border border-green-200 text-green-800 px-5 py-4 rounded-xl shadow-sm">
        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <p class="text-sm font-medium">{{ session('success') }}</p>
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
         class="alert-animate flex items-center space-x-3 bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-xl shadow-sm">
        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                      d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-sm font-medium">{{ session('error') }}</p>
        <button onclick="document.getElementById('alert-error').remove()"
                class="ml-auto text-red-500 hover:text-red-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    @endif

    {{-- ===== HEADER WITH SEARCH & ADD BUTTON ===== --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            {{-- Search Bar --}}
            <form method="GET" action="{{ route('foods.index') }}" class="w-full sm:w-auto flex-1 max-w-md">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text"
                           id="search-input"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Cari master data makanan ..."
                           class="w-full bg-gray-50 border border-gray-200 rounded-lg pl-12 pr-4 py-3 text-sm text-gray-700 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition">
                </div>
            </form>

            {{-- Tambah Master Menu Button --}}
            <a href="{{ route('foods.create') }}"
               class="flex items-center justify-center gap-2 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold px-6 py-3 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg whitespace-nowrap">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Master Menu</span>
            </a>
        </div>
    </div>

    {{-- ===== EMPTY STATE ===== --}}
    @if($foods->isEmpty())
    <div class="bg-white rounded-xl shadow-sm p-12">
        <div class="flex flex-col items-center justify-center text-center">
            <div class="w-24 h-24 bg-gray-100 rounded-2xl flex items-center justify-center mb-6">
                <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-700 mb-2">
                {{ request('search') ? 'Makanan tidak ditemukan' : 'Belum ada data makanan' }}
            </h3>
            <p class="text-sm text-gray-500 mb-6">
                {{ request('search') ? 'Coba gunakan kata kunci pencarian lain.' : 'Mulai dengan menambahkan menu makanan pertama Anda.' }}
            </p>
            <a href="{{ route('foods.create') }}"
               class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2.5 rounded-lg transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Menu Sekarang
            </a>
        </div>
    </div>
    @else
    {{-- ===== FOOD CARDS GRID ===== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($foods as $food)
        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden group">
            {{-- Image Container --}}
            <div class="relative bg-gray-100 overflow-hidden" style="aspect-ratio: 4/3;">
                @if($food->foto)
                    <img src="{{ asset('storage/' . $food->foto) }}"
                         alt="{{ $food->nama_makanan }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                         onerror="this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&q=80'">
                @else
                    <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&q=80"
                         alt="{{ $food->nama_makanan }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                @endif
            </div>

            {{-- Card Content --}}
            <div class="p-5">
                {{-- Category Badge --}}
                <div class="inline-block bg-amber-100 text-amber-700 text-xs font-extrabold tracking-wider uppercase px-3 py-1 rounded-full mb-3">
                    {{ $food->kategori ?? 'Umum' }}
                </div>

                {{-- Food Name --}}
                <h3 class="text-base font-bold text-gray-900 leading-tight mb-3 line-clamp-2">
                    {{ $food->nama_makanan }}
                </h3>

                {{-- Weight Info --}}
                <div class="flex items-center gap-2 text-gray-600 text-sm mb-5">
                    <svg class="w-4 h-4 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                    </svg>
                    <span class="font-medium">
                        {{ $food->berat ? number_format($food->berat, 2) . ' kg' : '— kg' }}
                    </span>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center gap-3">
                    {{-- Edit Button --}}
                    <a href="{{ route('foods.edit', $food->id) }}"
                       class="flex-1 flex items-center justify-center gap-2 border-2 border-gray-200 text-gray-700 hover:border-green-500 hover:text-green-600 hover:bg-green-50 font-semibold text-xs px-4 py-2.5 rounded-lg transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        <span>Edit Master</span>
                    </a>

                    {{-- Delete Button --}}
                    <form action="{{ route('foods.destroy', $food->id) }}"
                          method="POST"
                          class="delete-form"
                          data-name="{{ $food->nama_makanan }}"
                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus {{ addslashes($food->nama_makanan) }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-11 h-11 flex items-center justify-center border-2 border-red-200 text-red-400 hover:bg-red-500 hover:text-white hover:border-red-500 rounded-lg transition-all duration-200">
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

    {{-- ===== PAGINATION ===== --}}
    @if($foods->hasPages())
    <div class="flex justify-center pt-8">
        <div class="flex items-center gap-2">
            {{-- Previous Button --}}
            @if($foods->onFirstPage())
                <span class="w-10 h-10 flex items-center justify-center bg-gray-100 border border-gray-200 rounded-lg text-gray-300 cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </span>
            @else
                <a href="{{ $foods->previousPageUrl() }}"
                   class="w-10 h-10 flex items-center justify-center bg-white border border-gray-200 rounded-lg text-gray-600 hover:bg-green-50 hover:text-green-600 hover:border-green-300 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
            @endif

            {{-- Page Numbers --}}
            @foreach($foods->getUrlRange(max(1, $foods->currentPage() - 2), min($foods->lastPage(), $foods->currentPage() + 2)) as $page => $url)
                @if($page == $foods->currentPage())
                    <span class="w-10 h-10 flex items-center justify-center bg-green-600 text-white font-semibold rounded-lg shadow-md">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $url }}"
                       class="w-10 h-10 flex items-center justify-center bg-white border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-green-600 hover:border-green-300 transition-all font-medium">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            {{-- Next Button --}}
            @if($foods->hasMorePages())
                <a href="{{ $foods->nextPageUrl() }}"
                   class="w-10 h-10 flex items-center justify-center bg-white border border-gray-200 rounded-lg text-gray-600 hover:bg-green-50 hover:text-green-600 hover:border-green-300 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            @else
                <span class="w-10 h-10 flex items-center justify-center bg-gray-100 border border-gray-200 rounded-lg text-gray-300 cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </span>
            @endif
        </div>
    </div>

    <p class="text-center text-xs text-gray-500 mt-4">
        Menampilkan <span class="font-semibold">{{ $foods->firstItem() }}–{{ $foods->lastItem() }}</span> dari <span class="font-semibold">{{ $foods->total() }}</span> menu makanan
    </p>
    @endif

    @endif
</div>

<style>
    .alert-animate {
        animation: slideInDown 0.3s ease-out;
    }

    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeOut {
        to {
            opacity: 0;
            transform: translateY(-10px);
        }
    }
</style>

@endsection
