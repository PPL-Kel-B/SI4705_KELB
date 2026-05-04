@extends('layouts.unit_bisnis')

@section('title', 'Edit Menu Aktif')

@section('content')

{{-- Breadcrumbs & Header --}}
<div class="mb-8">
    <div class="flex items-center text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-3">
        <a href="{{ route('unit.kelola_makanan') }}" class="hover:text-[#1cb764] transition">Kelola Makanan</a>
        <span class="mx-2">›</span>
        <span class="text-[#0a2e1f]">Edit Menu Aktif</span>
    </div>
</div>

<section x-data="editMenuApp()" class="max-w-7xl mx-auto">
    <h1 class="text-3xl font-extrabold text-[#0a2e1f] mb-2">Edit Menu Aktif</h1>
    <p class="text-gray-500 font-medium text-sm mb-8">Ubah stok, batas waktu pengambilan, atau status harga menu Anda.</p>

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <ul class="text-sm font-medium">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('unit.menu_aktif.update', $menuAktif->id) }}" method="POST" id="form-edit-menu">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            {{-- FORM COLUMN --}}
            <div class="lg:col-span-7">
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-50 space-y-6">
                    
                    {{-- Master Data Info (Read Only) --}}
                    <div class="bg-[#f9fafb] rounded-2xl p-4 border border-gray-100 flex items-center gap-4">
                        <img src="{{ $menuAktif->masterMakanan->foto ? asset('storage/' . $menuAktif->masterMakanan->foto) : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&q=80' }}" 
                             class="w-16 h-16 rounded-xl object-cover bg-gray-100">
                        <div>
                            <p class="text-[10px] text-[#1cb764] font-extrabold uppercase tracking-wider mb-1">{{ $menuAktif->masterMakanan->kategori }}</p>
                            <h4 class="font-bold text-base text-gray-900 leading-snug">{{ $menuAktif->masterMakanan->nama_makanan }}</h4>
                            <p class="text-xs text-gray-500 mt-1 line-clamp-1">{{ $menuAktif->masterMakanan->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                        </div>
                    </div>

                    {{-- Gratiskan Toggle --}}
                    <div class="bg-[#eefcf4] p-5 rounded-2xl flex justify-between items-center border border-[#d1e6db]">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center flex-shrink-0 text-xl">🎁</div>
                            <div>
                                <h4 class="font-bold text-sm text-[#0a2e1f]">Gratiskan makanan ini</h4>
                                <p class="text-[11px] text-green-700 mt-0.5 font-medium">Menu ini akan dibagikan secara cuma-cuma</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_gratis" value="1" x-model="isFree" class="sr-only peer">
                            <div class="w-12 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#1cb764]"></div>
                        </label>
                    </div>

                    {{-- Stok & Waktu --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Jumlah Stok Baru</label>
                            <div class="flex items-center bg-[#f4f6f5] rounded-xl p-1">
                                <button type="button" @click="if(stock > 0) stock--" class="w-10 h-10 flex items-center justify-center bg-white rounded-lg shadow-sm text-xl font-bold text-[#1cb764] transition">-</button>
                                <input type="number" name="stok_porsi" x-model="stock" min="0" class="w-full text-center font-bold text-lg outline-none bg-transparent text-gray-900 border-none focus:ring-0">
                                <button type="button" @click="stock++" class="w-10 h-10 flex items-center justify-center bg-white rounded-lg shadow-sm text-xl font-bold text-[#1cb764] transition">+</button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Ubah Batas Pengambilan</label>
                            <div class="flex items-center bg-[#f4f6f5] rounded-xl h-[48px] overflow-hidden">
                                <input type="time" name="batas_pengambilan" value="{{ \Carbon\Carbon::parse($menuAktif->batas_pengambilan)->format('H:i') }}" class="w-full h-full text-center font-bold text-lg outline-none bg-transparent text-[#1cb764] border-none focus:ring-0 cursor-pointer">
                            </div>
                        </div>
                    </div>

                    <button type="submit" id="btn-submit" class="w-full bg-[#1cb764] text-white py-4 rounded-xl font-bold hover:bg-[#19a55a] transition shadow-md mt-2">
                        Simpan Perubahan
                    </button>
                </div>
            </div>

            {{-- PREVIEW COLUMN --}}
            <div class="lg:col-span-5 space-y-6 sticky top-8">
                <div class="flex items-center gap-2 text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2">
                    <span class="w-2 h-2 bg-[#f7b055] rounded-full"></span> Pratinjau
                </div>
                
                {{-- Preview Card --}}
                <div class="bg-white rounded-3xl shadow-lg border border-gray-50 overflow-hidden">
                    <div class="relative bg-gray-100 w-full h-48 overflow-hidden">
                        <img src="{{ $menuAktif->masterMakanan->foto ? asset('storage/' . $menuAktif->masterMakanan->foto) : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&q=80' }}" class="w-full h-full object-cover">
                        <span class="absolute top-4 left-4 bg-[#0a2e1f]/80 backdrop-blur-md text-white text-[10px] px-3 py-1.5 rounded-lg font-extrabold uppercase tracking-widest shadow-md">Tersedia</span>
                    </div>
                    <div class="p-6">
                        <h2 class="text-xl font-extrabold text-gray-900 leading-snug mb-3 line-clamp-1">{{ $menuAktif->masterMakanan->nama_makanan }}</h2>
                        <p class="text-sm text-gray-500 leading-relaxed mb-4 line-clamp-2 min-h-[40px]">{{ $menuAktif->masterMakanan->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                        
                        <div class="flex items-center gap-2 text-gray-600 mb-4">
                            <svg class="w-4 h-4 text-[#1cb764]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            <span class="font-bold text-sm text-gray-800" x-text="stock + ' Porsi'"></span>
                        </div>
                        
                        <div class="pt-4 border-t border-gray-50">
                            <div class="text-2xl font-extrabold" :class="isFree ? 'text-[#1cb764]' : 'text-[#cf8129]'" x-text="isFree ? 'GRATIS' : 'Rp{{ number_format($menuAktif->masterMakanan->harga, 0, ',', '.') }}'"></div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </form>
</section>

@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('editMenuApp', () => ({
            isFree: {{ $menuAktif->is_gratis ? 'true' : 'false' }},
            stock: {{ $menuAktif->stok_porsi }}
        }));
    });

    // Handle form submit loading state
    const form = document.getElementById('form-edit-menu');
    const btnSimpan = document.getElementById('btn-submit');
    form.addEventListener('submit', function() {
        if (!btnSimpan.disabled) {
            btnSimpan.disabled = true;
            btnSimpan.innerHTML = '<svg class="animate-spin h-5 w-5 text-white inline mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> <span>Menyimpan...</span>';
        }
    });
</script>
@endpush
