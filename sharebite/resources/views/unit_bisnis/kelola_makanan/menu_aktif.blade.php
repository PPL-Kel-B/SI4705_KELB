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
        <div class="relative flex items-center bg-[#e9eeeb] rounded-full px-4 py-3">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari pesanan atau menu..."
                   onchange="this.form.submit()"
                   class="bg-transparent border-none outline-none w-full ml-3 text-sm text-gray-700 font-medium placeholder-gray-500 focus:ring-0">
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

    {{-- Total Menu Habis Hari Ini --}}
    <div class="bg-white rounded-3xl p-6 flex items-center gap-5 shadow-sm border border-gray-50">
        <div class="w-14 h-14 rounded-2xl bg-[#fee2e2] flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-[#ef4444]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        </div>
        <div>
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Menu Habis Hari Ini</p>
            <div class="flex items-end gap-2">
                <span class="text-3xl font-extrabold text-gray-900 leading-none">{{ $totalMenuHabisHariIni }}</span>
                <span class="text-sm font-semibold text-gray-500 mb-0.5">Kategori</span>
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

{{-- ===== FILTERS & SORT ===== --}}
<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div id="filters-container" class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-hide">
        <a href="{{ route('unit.kelola_makanan', array_merge(request()->query(), ['filter' => 'semua'])) }}" 
           class="ajax-link px-5 py-2.5 rounded-xl text-sm font-bold whitespace-nowrap transition-colors {{ request('filter', 'semua') === 'semua' ? 'bg-[#0a2e1f] text-white' : 'bg-[#e9eeeb] text-gray-600 hover:bg-[#dcfce7] hover:text-[#1cb764]' }}">
            Semua
        </a>
        <a href="{{ route('unit.kelola_makanan', array_merge(request()->query(), ['filter' => 'tersedia'])) }}" 
           class="ajax-link px-5 py-2.5 rounded-xl text-sm font-bold whitespace-nowrap transition-colors {{ request('filter') === 'tersedia' ? 'bg-[#1cb764] text-white' : 'bg-[#e9eeeb] text-gray-600 hover:bg-[#dcfce7] hover:text-[#1cb764]' }}">
            Tersedia
        </a>
        <a href="{{ route('unit.kelola_makanan', array_merge(request()->query(), ['filter' => 'segera_habis'])) }}" 
           class="ajax-link px-5 py-2.5 rounded-xl text-sm font-bold whitespace-nowrap transition-colors {{ request('filter') === 'segera_habis' ? 'bg-[#cf8129] text-white' : 'bg-[#e9eeeb] text-gray-600 hover:bg-[#fdf4e9] hover:text-[#cf8129]' }}">
            Segera Habis
        </a>
    </div>
    
    <div id="sort-container" class="relative group">
        <div class="flex items-center gap-2 text-sm font-semibold text-gray-500 cursor-pointer hover:text-gray-800 transition-colors bg-white px-4 py-2.5 rounded-xl shadow-sm border border-gray-50">
            <span>Urutkan: 
                @if(request('sort') === 'terlama') Terlama
                @elseif(request('sort') === 'stok_terbanyak') Stok Terbanyak
                @elseif(request('sort') === 'stok_terdikit') Stok Terdikit
                @else Terbaru
                @endif
            </span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
        </div>
        <div class="absolute right-0 top-full mt-2 w-48 bg-white border border-gray-100 shadow-xl rounded-xl overflow-hidden opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50">
            <a href="{{ route('unit.kelola_makanan', array_merge(request()->query(), ['sort' => 'terbaru'])) }}" class="ajax-link block px-4 py-3 text-sm font-medium hover:bg-gray-50 {{ request('sort', 'terbaru') === 'terbaru' ? 'text-[#1cb764]' : 'text-gray-700' }}">Terbaru</a>
            <a href="{{ route('unit.kelola_makanan', array_merge(request()->query(), ['sort' => 'terlama'])) }}" class="ajax-link block px-4 py-3 text-sm font-medium hover:bg-gray-50 {{ request('sort') === 'terlama' ? 'text-[#1cb764]' : 'text-gray-700' }}">Terlama</a>
            <a href="{{ route('unit.kelola_makanan', array_merge(request()->query(), ['sort' => 'stok_terbanyak'])) }}" class="ajax-link block px-4 py-3 text-sm font-medium hover:bg-gray-50 {{ request('sort') === 'stok_terbanyak' ? 'text-[#1cb764]' : 'text-gray-700' }}">Stok Terbanyak</a>
            <a href="{{ route('unit.kelola_makanan', array_merge(request()->query(), ['sort' => 'stok_terdikit'])) }}" class="ajax-link block px-4 py-3 text-sm font-medium hover:bg-gray-50 {{ request('sort') === 'stok_terdikit' ? 'text-[#1cb764]' : 'text-gray-700' }}">Stok Terdikit</a>
        </div>
    </div>
</div>

{{-- ===== MENU LIST ===== --}}
<div id="menu-container">
@if($menuAktifs->isEmpty())
    <div class="bg-white rounded-3xl p-12 shadow-sm border border-gray-50 flex flex-col items-center justify-center text-center mt-6">
        @if(request('search'))
            <div class="bg-blue-50 w-24 h-24 rounded-full flex items-center justify-center text-blue-500 mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <h2 class="text-2xl font-extrabold text-gray-800 mb-2">Pencarian Tidak Ditemukan</h2>
            <p class="text-gray-500 max-w-md mb-8">Kami tidak menemukan makanan dengan kata kunci "<b>{{ request('search') }}</b>" di menu aktif.</p>
            <a href="{{ route('unit.kelola_makanan', request()->except('search')) }}" class="ajax-link inline-flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold px-8 py-4 rounded-xl transition-all shadow-sm hover:shadow-md">
                Reset Pencarian
            </a>
        @elseif(request('filter') && request('filter') !== 'semua')
            <div class="bg-orange-50 w-24 h-24 rounded-full flex items-center justify-center text-orange-500 mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
            </div>
            <h2 class="text-2xl font-extrabold text-gray-800 mb-2">Filter Kosong</h2>
            <p class="text-gray-500 max-w-md mb-8">Tidak ada menu aktif yang cocok dengan filter yang Anda pilih saat ini.</p>
            <a href="{{ route('unit.kelola_makanan', request()->except('filter')) }}" class="ajax-link inline-flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold px-8 py-4 rounded-xl transition-all shadow-sm hover:shadow-md">
                Hapus Filter
            </a>
        @else
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
        @endif
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
                    @if($menu->stok_porsi > 5)
                        <span class="bg-[#0a2e1f] text-white text-[10px] font-extrabold px-3 py-1.5 rounded-lg uppercase tracking-widest shadow-md">
                            Tersedia
                        </span>
                    @else
                        <span class="bg-[#cf8129] text-white text-[10px] font-extrabold px-3 py-1.5 rounded-lg uppercase tracking-widest shadow-md">
                            Segera Habis
                        </span>
                    @endif
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

                {{-- Actions --}}
                <div class="flex items-center gap-3">
                    <a href="{{ route('unit.menu_aktif.edit', $menu->id) }}" class="flex-1 py-3 bg-white border border-gray-200 text-gray-600 font-bold text-xs rounded-xl flex items-center justify-center gap-1.5 transition-colors hover:bg-gray-50 hover:border-gray-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        Edit
                    </a>
                    <button type="button" onclick="confirmTutup('{{ $menu->id }}', '{{ addslashes($menu->masterMakanan->nama_makanan) }}')" class="flex-1 py-3 bg-white border border-red-200 text-red-500 font-bold text-xs rounded-xl flex items-center justify-center gap-1.5 transition-colors hover:bg-red-50 hover:border-red-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                        Tutup
                    </button>
                    <form id="tutup-form-{{ $menu->id }}" action="{{ route('unit.menu_aktif.destroy', $menu->id) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmTutup(id, name) {
        if(typeof Swal === 'undefined') return alert('Library SweetAlert tidak ditemukan!');
        
        Swal.fire({
            title: 'Tutup Menu Aktif?',
            text: `Apakah Anda yakin ingin menutup dan menghapus "${name}" dari daftar menu yang sedang dijual hari ini?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#9ca3af',
            confirmButtonText: 'Ya, Tutup!',
            cancelButtonText: 'Batal',
            customClass: {
                confirmButton: 'rounded-xl shadow-md',
                cancelButton: 'rounded-xl shadow-md'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('tutup-form-' + id).submit();
            }
        });
    }

    // ===== AJAX FILTER & SORT =====
    document.addEventListener('click', async (e) => {
        const link = e.target.closest('.ajax-link');
        if (!link) return;
        
        e.preventDefault();
        const url = link.href;
        const menuContainer = document.getElementById('menu-container');
        
        // Animasi Loading
        menuContainer.innerHTML = `
            <div class="flex flex-col justify-center items-center h-64 w-full">
                <svg class="animate-spin h-10 w-10 text-[#1cb764] mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-sm font-bold text-gray-500">Memuat data...</p>
            </div>
        `;
        
        // Update URL Address Bar (tanpa refresh)
        window.history.pushState({}, '', url);

        try {
            const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const html = await response.text();
            
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            // Replace konten grid/list
            if (doc.getElementById('menu-container')) {
                menuContainer.innerHTML = doc.getElementById('menu-container').innerHTML;
            }
            
            // Replace tab filter (untuk update state aktif)
            if (doc.getElementById('filters-container')) {
                document.getElementById('filters-container').innerHTML = doc.getElementById('filters-container').innerHTML;
            }

            // Replace dropdown sort
            if (doc.getElementById('sort-container')) {
                document.getElementById('sort-container').innerHTML = doc.getElementById('sort-container').innerHTML;
            }
        } catch (err) {
            // Fallback jika fetch gagal (misal koneksi terputus)
            window.location.href = url;
        }
    });

    // Support browser back/forward buttons
    window.addEventListener('popstate', () => {
        window.location.reload();
    });
</script>
@endpush
