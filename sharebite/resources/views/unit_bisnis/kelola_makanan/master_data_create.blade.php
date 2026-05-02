@extends('layouts.unit_bisnis')

@section('title', 'Tambah Menu Baru')

@section('content')

<div class="max-w-4xl mx-auto">
    {{-- Breadcrumbs & Header --}}
    <div class="mb-6">
        <div class="flex items-center text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-3">
            <a href="{{ route('unit.master_data.index') }}" class="hover:text-gray-600 transition">Kelola Makanan</a>
            <span class="mx-2">›</span>
            <span class="text-[#0a2e1f]">Tambah Menu Baru</span>
        </div>
        <h1 class="text-3xl font-extrabold text-[#0a2e1f] mb-2">Tambah Menu Baru</h1>
        <p class="text-gray-500 font-medium text-sm">Bagikan kelebihan stok makanan Anda untuk mengurangi limbah.</p>
    </div>

    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-50">
        <form action="{{ route('unit.master_data.store') }}" method="POST" enctype="multipart/form-data" id="form-tambah-menu">
            @csrf
            <div class="space-y-6">
                {{-- Nama Produk --}}
                <div>
                    <label for="Nama_Makanan" class="block text-sm font-extrabold text-gray-900 mb-2">Nama Produk</label>
                    <input type="text" id="Nama_Makanan" name="Nama_Makanan" placeholder="Contoh: Nasi Goreng Spesial Organik"
                           class="form-input w-full bg-[#f4f8f6] border-none rounded-xl px-4 py-3.5 text-sm text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-[#1cb764]">
                </div>

                {{-- Kategori Makanan --}}
                <div>
                    <label for="Kategori" class="block text-sm font-extrabold text-gray-900 mb-2">Kategori Makanan</label>
                    <select id="Kategori" name="Kategori"
                            class="form-input w-full bg-[#f4f8f6] border-none rounded-xl px-4 py-3.5 text-sm text-gray-800 focus:ring-2 focus:ring-[#1cb764] appearance-none cursor-pointer">
                        <option value="" disabled selected class="text-gray-400">Pilih kategori...</option>
                        @foreach(['Makanan Berat', 'Snack / Cemilan', 'Minuman', 'Dessert', 'Makanan Sehat', 'Makanan Rumahan', 'Makanan Cepat Saji', 'Makanan Tradisional', 'Makanan Internasional'] as $kategori)
                            <option value="{{ $kategori }}">{{ $kategori }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Unggah Foto --}}
                <div>
                    <label class="block text-sm font-extrabold text-gray-900 mb-2">Unggah Foto</label>
                    <div class="relative border-2 border-dashed border-[#d1e6db] bg-[#f4f8f6] rounded-2xl p-8 hover:bg-[#ebf4ef] transition-colors cursor-pointer text-center" id="drop-zone">
                        <input type="file" id="Foto" name="Foto" accept="image/png,image/jpeg,image/webp" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer form-input" onchange="previewImage(event)">
                        
                        <div id="upload-placeholder" class="flex flex-col items-center pointer-events-none">
                            <div class="w-12 h-12 bg-[#dcfce7] rounded-full flex items-center justify-center text-[#1cb764] mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            </div>
                            <p class="text-sm font-bold text-gray-900 mb-1">Klik untuk unggah atau seret foto</p>
                            <p class="text-[11px] font-medium text-gray-500 uppercase tracking-widest">PNG, JPG atau WEBP (Maks. 5MB)</p>
                        </div>

                        <div id="image-preview" class="hidden flex-col items-center pointer-events-none">
                            <img id="preview-img" src="" alt="Preview" class="w-32 h-32 object-cover rounded-xl shadow-sm mb-2">
                            <p class="text-xs font-bold text-[#1cb764]">Ganti Foto</p>
                        </div>
                    </div>
                </div>

                {{-- Deskripsi Produk --}}
                <div>
                    <label for="deskripsi" class="block text-sm font-extrabold text-gray-900 mb-2">Deskripsi Produk</label>
                    <textarea id="deskripsi" name="deskripsi" rows="3" placeholder="Ceritakan detail makanan Anda, seperti bahan utama atau waktu pembuatan..."
                              class="form-input w-full bg-[#f4f8f6] border-none rounded-xl px-4 py-3.5 text-sm text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-[#1cb764] resize-none"></textarea>
                </div>

                {{-- Harga & Berat --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="Harga" class="block text-sm font-extrabold text-gray-900 mb-2">Harga (Rp)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-800 font-bold text-sm pointer-events-none">Rp</span>
                            <input type="text" id="Harga" name="Harga" placeholder="0"
                                   class="form-input w-full bg-[#f4f8f6] border-none rounded-xl pl-10 pr-4 py-3.5 text-sm text-gray-800 font-bold focus:ring-2 focus:ring-[#1cb764]">
                        </div>
                    </div>
                    <div>
                        <label for="Berat" class="block text-sm font-extrabold text-gray-900 mb-2">Berat (kg)</label>
                        <div class="relative">
                            <input type="number" id="Berat" name="Berat" step="0.01" min="0" placeholder="0.5"
                                   class="form-input w-full bg-[#f4f8f6] border-none rounded-xl px-4 py-3.5 text-sm text-gray-800 font-bold focus:ring-2 focus:ring-[#1cb764]">
                            <span class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-800 font-bold text-sm pointer-events-none">kg</span>
                        </div>
                    </div>
                </div>

                {{-- Hidden default status --}}
                <input type="hidden" name="Status" value="Tersedia">
                <input type="hidden" name="Batas_waktu_pengambilan" value="{{ \Carbon\Carbon::now()->addDays(7)->format('Y-m-d') }}">
                <input type="hidden" name="Jumlah_porsi" value="1">

                {{-- Simpan Button --}}
                <div class="pt-4">
                    <button type="submit" id="btn-simpan" disabled
                            class="w-full flex items-center justify-center gap-2 bg-[#1cb764] text-white font-bold px-6 py-4 rounded-xl transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-[#19a55a]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        <span>Simpan Master Menu</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Price Formatting Logic
    const hargaInput = document.getElementById('Harga');
    hargaInput.addEventListener('keyup', function(e) {
        let value = this.value.replace(/[^0-9]/g, '');
        if (value) {
            this.value = formatRupiah(value);
        } else {
            this.value = '';
        }
        checkFormValidity();
    });

    function formatRupiah(number) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Preview Image Logic
    function previewImage(event) {
        const file = event.target.files[0];
        if (!file) return;

        const placeholder = document.getElementById('upload-placeholder');
        const previewDiv = document.getElementById('image-preview');
        const previewImg = document.getElementById('preview-img');

        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            placeholder.classList.add('hidden');
            previewDiv.classList.remove('hidden');
            previewDiv.classList.add('flex');
            checkFormValidity();
        };
        reader.readAsDataURL(file);
    }

    // Disable Save Button Logic
    const form = document.getElementById('form-tambah-menu');
    const inputs = document.querySelectorAll('.form-input');
    const btnSimpan = document.getElementById('btn-simpan');

    function checkFormValidity() {
        let isValid = true;
        
        // Required fields
        const nama = document.getElementById('Nama_Makanan').value.trim();
        const kategori = document.getElementById('Kategori').value;
        const harga = document.getElementById('Harga').value;
        const berat = document.getElementById('Berat').value;
        
        if (!nama || !kategori || !harga || !berat) {
            isValid = false;
        }

        btnSimpan.disabled = !isValid;
    }

    inputs.forEach(input => {
        input.addEventListener('input', checkFormValidity);
        input.addEventListener('change', checkFormValidity);
    });

    // Handle form submit with loading state
    form.addEventListener('submit', function() {
        btnSimpan.disabled = true;
        btnSimpan.innerHTML = '<svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> <span>Menyimpan...</span>';
    });
</script>
@endpush