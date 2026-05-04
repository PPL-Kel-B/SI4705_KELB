@extends('layouts.unit_bisnis')

@section('title', 'Edit Menu Master')

@section('content')

<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <div class="flex items-center text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-3">
            <a href="{{ route('unit.master_data.index') }}" class="hover:text-gray-600 transition">Kelola Makanan</a>
            <span class="mx-2">›</span>
            <span class="text-[#0a2e1f]">Edit Menu Master</span>
        </div>
        <h1 class="text-3xl font-extrabold text-[#0a2e1f] mb-2">Edit Menu Master</h1>
        <p class="text-gray-500 font-medium text-sm">Ubah detail data makanan master Anda.</p>
    </div>

    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-50">
        <form action="{{ route('unit.master_data.update', $master_datum->id) }}" method="POST" enctype="multipart/form-data" id="form-edit-menu">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                {{-- Nama Produk --}}
                <div>
                    <label for="Nama_Makanan" class="block text-sm font-extrabold text-gray-900 mb-2">Nama Produk</label>
                    <input type="text" id="Nama_Makanan" name="Nama_Makanan" value="{{ old('Nama_Makanan', $master_datum->nama_makanan) }}" 
                           class="form-input w-full bg-[#f4f8f6] border-none rounded-xl px-4 py-3.5 text-sm text-gray-800 focus:ring-2 focus:ring-[#1cb764]">
                </div>

                {{-- Kategori Makanan --}}
                <div>
                    <label for="Kategori" class="block text-sm font-extrabold text-gray-900 mb-2">Kategori Makanan</label>
                    <select id="Kategori" name="Kategori"
                            class="form-input w-full bg-[#f4f8f6] border-none rounded-xl px-4 py-3.5 text-sm text-gray-800 focus:ring-2 focus:ring-[#1cb764] appearance-none cursor-pointer">
                        @foreach(['Makanan Berat', 'Snack / Cemilan', 'Minuman', 'Dessert', 'Makanan Sehat', 'Makanan Rumahan', 'Makanan Cepat Saji', 'Makanan Tradisional', 'Makanan Internasional'] as $kategori)
                            <option value="{{ $kategori }}" {{ old('Kategori', $master_datum->kategori) == $kategori ? 'selected' : '' }}>{{ $kategori }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Unggah Foto --}}
                <div>
                    <label class="block text-sm font-extrabold text-gray-900 mb-2">Unggah Foto (Opsional)</label>
                    <div class="relative border-2 border-dashed border-[#d1e6db] bg-[#f4f8f6] rounded-2xl p-8 hover:bg-[#ebf4ef] transition-colors cursor-pointer text-center" id="drop-zone">
                        <input type="file" id="Foto" name="Foto" accept="image/png,image/jpeg,image/webp" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer form-input" onchange="previewImage(event)">
                        
                        <div id="upload-placeholder" class="{{ $master_datum->foto ? 'hidden' : 'flex' }} flex-col items-center pointer-events-none">
                            <div class="w-12 h-12 bg-[#dcfce7] rounded-full flex items-center justify-center text-[#1cb764] mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            </div>
                            <p class="text-sm font-bold text-gray-900 mb-1">Klik untuk unggah atau seret foto baru</p>
                            <p class="text-[11px] font-medium text-gray-500 uppercase tracking-widest">Biarkan kosong jika tidak ingin mengubah</p>
                        </div>

                        <div id="image-preview" class="{{ $master_datum->foto ? 'flex' : 'hidden' }} flex-col items-center pointer-events-none">
                            <img id="preview-img" src="{{ $master_datum->foto ? asset('storage/' . $master_datum->foto) : '' }}" alt="Preview" class="w-32 h-32 object-cover rounded-xl shadow-sm mb-2">
                            <p class="text-xs font-bold text-[#1cb764]">Ganti Foto</p>
                        </div>
                    </div>
                </div>

                {{-- Deskripsi Produk --}}
                <div>
                    <label for="deskripsi" class="block text-sm font-extrabold text-gray-900 mb-2">Deskripsi Produk</label>
                    <textarea id="deskripsi" name="deskripsi" rows="3"
                              class="form-input w-full bg-[#f4f8f6] border-none rounded-xl px-4 py-3.5 text-sm text-gray-800 focus:ring-2 focus:ring-[#1cb764] resize-none">{{ old('deskripsi', $master_datum->deskripsi) }}</textarea>
                </div>

                {{-- Harga & Berat --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="Harga" class="block text-sm font-extrabold text-gray-900 mb-2">Harga (Rp)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-800 font-bold text-sm pointer-events-none">Rp</span>
                            <input type="text" id="Harga" name="Harga" value="{{ old('Harga', number_format($master_datum->harga, 0, '', '.')) }}"
                                   class="form-input w-full bg-[#f4f8f6] border-none rounded-xl pl-10 pr-4 py-3.5 text-sm text-gray-800 font-bold focus:ring-2 focus:ring-[#1cb764]">
                        </div>
                    </div>
                    <div>
                        <label for="Berat" class="block text-sm font-extrabold text-gray-900 mb-2">Berat (kg)</label>
                        <div class="relative">
                            <input type="number" id="Berat" name="Berat" step="0.01" min="0" value="{{ old('Berat', $master_datum->berat) }}"
                                   class="form-input w-full bg-[#f4f8f6] border-none rounded-xl px-4 py-3.5 text-sm text-gray-800 font-bold focus:ring-2 focus:ring-[#1cb764]">
                            <span class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-800 font-bold text-sm pointer-events-none">kg</span>
                        </div>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" id="btn-simpan"
                            class="w-full flex items-center justify-center gap-2 bg-[#1cb764] text-white font-bold px-6 py-4 rounded-xl transition-all hover:bg-[#19a55a]">
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const hargaInput = document.getElementById('Harga');
    hargaInput.addEventListener('keyup', function(e) {
        let value = this.value.replace(/[^0-9]/g, '');
        this.value = value ? formatRupiah(value) : '';
    });
    function formatRupiah(number) { return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."); }

    function previewImage(event) {
        const file = event.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-img').src = e.target.result;
            if(document.getElementById('upload-placeholder')) document.getElementById('upload-placeholder').classList.add('hidden');
            document.getElementById('image-preview').classList.remove('hidden');
            document.getElementById('image-preview').classList.add('flex');
        };
        reader.readAsDataURL(file);
    }
</script>
@endpush