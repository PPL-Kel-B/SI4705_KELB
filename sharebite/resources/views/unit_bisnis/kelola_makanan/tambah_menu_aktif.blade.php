@extends('layouts.unit_bisnis')

@section('title', 'Tambah Menu Aktif')

@section('content')

{{-- Breadcrumbs & Header --}}
<div class="mb-8">
    <div class="flex items-center text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-3">
        <a href="{{ route('unit.kelola_makanan') }}" class="hover:text-[#1cb764] transition">Kelola Makanan</a>
        <span class="mx-2">›</span>
        <span class="text-[#0a2e1f]">Tambah Menu Baru</span>
    </div>
</div>

<section x-data="menuAktifApp()" class="max-w-7xl mx-auto">
    <h1 class="text-3xl font-extrabold text-[#0a2e1f] mb-2">Tambah Menu Aktif</h1>
    <p class="text-gray-500 font-medium text-sm mb-8">Tambahkan menu untuk dijual berdasarkan makanan master data.</p>

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

    <form action="{{ route('unit.menu_aktif.store') }}" method="POST" id="form-tambah-menu">
        @csrf
        <input type="hidden" name="master_makanan_id" x-model="selectedId">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            {{-- FORM COLUMN --}}
            <div class="lg:col-span-7">
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-50 space-y-6">
                    
                    {{-- Search Master Data --}}
                    <div class="relative">
                        <span class="absolute left-4 top-3.5 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </span>
                        <input type="text" x-model="searchQuery" placeholder="Cari nama menu atau kategori..." 
                               class="w-full pl-12 pr-4 py-3.5 bg-[#f4f6f5] rounded-xl border-none shadow-sm focus:ring-2 focus:ring-[#1cb764] text-sm font-medium outline-none transition">
                    </div>

                    {{-- Master Data List --}}
                    <div class="bg-[#f9fafb] rounded-2xl p-4 border border-gray-100 h-72 overflow-y-auto space-y-3 scrollbar-hide">
                        @if($masterMakanans->isEmpty())
                            <div class="h-full flex flex-col items-center justify-center text-center">
                                <p class="text-sm text-gray-500 font-medium">Belum ada master data.</p>
                                <a href="{{ route('unit.master_data.create') }}" class="text-[#1cb764] text-xs font-bold mt-2 hover:underline">Tambah Master Data Baru</a>
                            </div>
                        @else
                            <template x-for="item in filteredMasterData" :key="item.id">
                                <div @click="selectMenu(item)" 
                                     :class="{'bg-[#eefcf4] border-[#1cb764] shadow-sm': selectedId === item.id, 'bg-white border-gray-100 hover:border-[#1cb764] hover:bg-gray-50': selectedId !== item.id}"
                                     class="flex items-center justify-between p-3 border rounded-xl cursor-pointer transition-all duration-200">
                                    <div class="flex items-center gap-4">
                                        <img :src="item.foto_url" class="w-12 h-12 rounded-lg object-cover bg-gray-100">
                                        <div>
                                            <h4 class="font-bold text-sm text-gray-900 line-clamp-1" x-text="item.nama_makanan"></h4>
                                            <p class="text-[10px] text-[#1cb764] font-extrabold uppercase tracking-wider mt-0.5" x-text="item.kategori"></p>
                                        </div>
                                    </div>
                                    <span x-show="selectedId === item.id" class="bg-[#1cb764] text-white text-[10px] px-3 py-1.5 rounded-lg font-bold uppercase tracking-wider shadow-sm">Terpilih</span>
                                </div>
                            </template>
                            <div x-show="filteredMasterData.length === 0" class="p-4 text-center text-sm text-gray-500 font-medium">
                                Tidak ada menu yang cocok dengan pencarian.
                            </div>
                        @endif
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
                            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Jumlah Stok</label>
                            <div class="flex items-center bg-[#f4f6f5] rounded-xl p-1">
                                <button type="button" @click="if(stock > 1) stock--" class="w-10 h-10 flex items-center justify-center bg-white rounded-lg shadow-sm text-xl font-bold text-[#1cb764] transition">-</button>
                                <input type="number" name="stok_porsi" x-model="stock" min="1" class="w-full text-center font-bold text-lg outline-none bg-transparent text-gray-900 border-none focus:ring-0">
                                <button type="button" @click="stock++" class="w-10 h-10 flex items-center justify-center bg-white rounded-lg shadow-sm text-xl font-bold text-[#1cb764] transition">+</button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Batas Pengambilan</label>
                            <div class="flex items-center bg-[#f4f6f5] rounded-xl h-[48px] overflow-hidden">
                                <input type="time" name="batas_pengambilan" value="21:00" class="w-full h-full text-center font-bold text-lg outline-none bg-transparent text-[#1cb764] border-none focus:ring-0 cursor-pointer">
                            </div>
                        </div>
                    </div>

                    <button type="submit" id="btn-submit" :disabled="!selectedId" :class="{'opacity-50 cursor-not-allowed': !selectedId}" class="w-full bg-[#1cb764] text-white py-4 rounded-xl font-bold hover:bg-[#19a55a] transition shadow-md mt-2">
                        Publikasikan Menu Aktif
                    </button>
                </div>
            </div>

            {{-- PREVIEW COLUMN --}}
            <div class="lg:col-span-5 space-y-6 sticky top-8">
                <div class="flex items-center gap-2 text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2">
                    <span class="w-2 h-2 bg-[#f7b055] rounded-full"></span> Pratinjau
                </div>
                
                {{-- Preview Card --}}
                <div class="bg-white rounded-3xl shadow-lg border border-gray-50 overflow-hidden" x-show="selectedMenu">
                    <div class="relative bg-gray-100 w-full h-48 overflow-hidden">
                        <img :src="selectedMenu ? selectedMenu.foto_url : ''" class="w-full h-full object-cover">
                        <span class="absolute top-4 left-4 bg-[#0a2e1f]/80 backdrop-blur-md text-white text-[10px] px-3 py-1.5 rounded-lg font-extrabold uppercase tracking-widest shadow-md">Tersedia</span>
                    </div>
                    <div class="p-6">
                        <h2 class="text-xl font-extrabold text-gray-900 leading-snug mb-3 line-clamp-1" x-text="selectedMenu ? selectedMenu.nama_makanan : ''"></h2>
                        <p class="text-sm text-gray-500 leading-relaxed mb-4 line-clamp-2 min-h-[40px]" x-text="selectedMenu ? selectedMenu.deskripsi : ''"></p>
                        
                        <div class="flex items-center gap-2 text-gray-600 mb-4">
                            <svg class="w-4 h-4 text-[#1cb764]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            <span class="font-bold text-sm text-gray-800" x-text="stock + ' Porsi'"></span>
                        </div>
                        
                        <div class="pt-4 border-t border-gray-50">
                            <div class="text-2xl font-extrabold" :class="isFree ? 'text-[#1cb764]' : 'text-[#cf8129]'" x-text="isFree ? 'GRATIS' : formatRupiah(selectedMenu ? selectedMenu.harga : 0)"></div>
                        </div>
                    </div>
                </div>

                {{-- Empty Preview State --}}
                <div class="bg-white rounded-3xl shadow-sm border border-gray-50 overflow-hidden flex flex-col items-center justify-center p-10 h-[480px] text-center" x-show="!selectedMenu">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center text-gray-300 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <p class="text-sm font-semibold text-gray-400">Pilih master data di samping<br>untuk melihat pratinjau.</p>
                </div>

                {{-- Tips --}}
                <div class="bg-[#eefcf4] p-5 rounded-2xl border-l-4 border-[#1cb764] flex gap-4">
                    <div class="text-2xl">💡</div>
                    <div>
                        <h5 class="font-bold text-sm text-[#0a2e1f] mb-1">Tips Kurasi</h5>
                        <p class="text-xs text-green-700 leading-relaxed font-medium">Pastikan stok yang diinput akurat dengan sistem inventori dapur Anda untuk menghindari pembatalan pesanan.</p>
                    </div>
                </div>
            </div>
            
        </div>
    </form>
</section>

@endsection

@push('scripts')
<script>
    // Use pre-mapped master data from controller
    const rawMasterData = @json($masterMakanans);

    document.addEventListener('alpine:init', () => {
        Alpine.data('menuAktifApp', () => ({
            searchQuery: '',
            masterData: rawMasterData,
            selectedId: null,
            selectedMenu: null,
            isFree: false,
            stock: 15,

            get filteredMasterData() {
                if (this.searchQuery === '') {
                    return this.masterData;
                }
                return this.masterData.filter(item => {
                    return item.nama_makanan.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                           item.kategori.toLowerCase().includes(this.searchQuery.toLowerCase());
                });
            },

            selectMenu(item) {
                this.selectedId = item.id;
                this.selectedMenu = item;
            },

            formatRupiah(number) {
                if(!number) return 'Rp0';
                return 'Rp' + parseFloat(number).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }
        }));
    });

    // Handle form submit loading state
    const form = document.getElementById('form-tambah-menu');
    const btnSimpan = document.getElementById('btn-submit');
    form.addEventListener('submit', function() {
        if (!btnSimpan.disabled) {
            btnSimpan.disabled = true;
            btnSimpan.innerHTML = '<svg class="animate-spin h-5 w-5 text-white inline mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> <span>Menyimpan...</span>';
        }
    });
</script>
@endpush