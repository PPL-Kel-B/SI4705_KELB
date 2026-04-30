@extends('layouts.app')

@section('title', 'Edit Menu Makanan')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm p-8">
        {{-- Header --}}
        <div class="mb-8">
            <a href="{{ route('foods.index') }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-2 mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Daftar Menu
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Edit Menu Makanan</h1>
            <p class="text-gray-500 mt-2">Perbarui informasi menu makanan: <span class="font-semibold">{{ $food->nama_makanan }}</span></p>
        </div>

        {{-- Form --}}
        <form action="{{ route('foods.update', $food->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Form Row 1: Nama & Kategori --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nama Makanan --}}
                <div>
                    <label for="nama_makanan" class="block text-sm font-semibold text-gray-700 mb-2">
                        Nama Makanan <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="nama_makanan"
                           name="nama_makanan"
                           value="{{ old('nama_makanan', $food->nama_makanan) }}"
                           placeholder="Contoh: Nasi Campur Bali Organik"
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-green-500 transition @error('nama_makanan') border-red-500 @enderror"
                           required>
                    @error('nama_makanan')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kategori --}}
                <div>
                    <label for="kategori" class="block text-sm font-semibold text-gray-700 mb-2">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select id="kategori"
                            name="kategori"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-green-500 transition @error('kategori') border-red-500 @enderror"
                            required>
                        <option value="">Pilih Kategori</option>
                        <option value="NASI KOTAK" {{ old('kategori', $food->kategori) == 'NASI KOTAK' ? 'selected' : '' }}>Nasi Kotak</option>
                        <option value="PASTA" {{ old('kategori', $food->kategori) == 'PASTA' ? 'selected' : '' }}>Pasta</option>
                        <option value="SALAD" {{ old('kategori', $food->kategori) == 'SALAD' ? 'selected' : '' }}>Salad</option>
                        <option value="WESTERN" {{ old('kategori', $food->kategori) == 'WESTERN' ? 'selected' : '' }}>Western</option>
                        <option value="ASIAN" {{ old('kategori', $food->kategori) == 'ASIAN' ? 'selected' : '' }}>Asian</option>
                        <option value="SOUP" {{ old('kategori', $food->kategori) == 'SOUP' ? 'selected' : '' }}>Soup</option>
                        <option value="DESSERT" {{ old('kategori', $food->kategori) == 'DESSERT' ? 'selected' : '' }}>Dessert</option>
                    </select>
                    @error('kategori')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Form Row 2: Harga & Berat --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Harga --}}
                <div>
                    <label for="harga" class="block text-sm font-semibold text-gray-700 mb-2">
                        Harga (Rp) <span class="text-red-500">*</span>
                    </label>
                    <input type="number"
                           id="harga"
                           name="harga"
                           value="{{ old('harga', $food->harga) }}"
                           placeholder="Contoh: 45000"
                           step="1000"
                           min="0"
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-green-500 transition @error('harga') border-red-500 @enderror"
                           required>
                    @error('harga')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Berat --}}
                <div>
                    <label for="berat" class="block text-sm font-semibold text-gray-700 mb-2">
                        Berat (kg) <span class="text-red-500">*</span>
                    </label>
                    <input type="number"
                           id="berat"
                           name="berat"
                           value="{{ old('berat', $food->berat) }}"
                           placeholder="Contoh: 0.45"
                           step="0.01"
                           min="0"
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-green-500 transition @error('berat') border-red-500 @enderror"
                           required>
                    @error('berat')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Deskripsi --}}
            <div>
                <label for="deskripsi" class="block text-sm font-semibold text-gray-700 mb-2">
                    Deskripsi
                </label>
                <textarea id="deskripsi"
                          name="deskripsi"
                          rows="4"
                          placeholder="Jelaskan detail menu makanan, bahan, alergen, dll..."
                          class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-green-500 transition resize-none @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi', $food->deskripsi) }}</textarea>
                @error('deskripsi')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Foto Upload --}}
            <div>
                <label for="foto" class="block text-sm font-semibold text-gray-700 mb-2">
                    Foto Menu
                </label>

                {{-- Current Photo (if exists) --}}
                @if($food->foto)
                <div class="mb-4 p-4 bg-gray-50 rounded-lg flex items-center gap-4">
                    <img src="{{ asset('storage/' . $food->foto) }}" 
                         alt="{{ $food->nama_makanan }}"
                         class="w-20 h-20 object-cover rounded-lg">
                    <div class="flex-1">
                        <p class="text-sm text-gray-600">Foto saat ini</p>
                        <button type="button" 
                                onclick="document.getElementById('foto').click()"
                                class="text-sm text-green-600 hover:text-green-700 font-medium mt-1">
                            Ubah Foto
                        </button>
                    </div>
                </div>
                @endif

                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-green-400 transition" onclick="document.getElementById('foto').click()">
                    <input type="file"
                           id="foto"
                           name="foto"
                           accept="image/*"
                           class="hidden"
                           onchange="handleFileSelect(event)">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-gray-600 font-medium">Klik untuk upload foto</p>
                    <p class="text-gray-400 text-sm mt-1">atau drag & drop file di sini</p>
                    <p class="text-gray-400 text-xs mt-2">Format: JPG, PNG, WebP (Max: 2MB)</p>
                </div>
                <div id="file-preview" class="mt-4 hidden">
                    <img id="preview-img" src="" alt="Preview" class="w-32 h-32 object-cover rounded-lg">
                    <button type="button" onclick="clearFile()" class="mt-2 text-sm text-red-500 hover:text-red-700">Hapus Foto Baru</button>
                </div>
                @error('foto')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Form Actions --}}
            <div class="flex gap-4 pt-6 border-t border-gray-200">
                <a href="{{ route('foods.index') }}"
                   class="flex-1 px-6 py-3 border-2 border-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition text-center">
                    Batal
                </a>
                <button type="submit"
                        class="flex-1 px-6 py-3 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                    Perbarui Menu Makanan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function handleFileSelect(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('file-preview').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
}

function clearFile() {
    document.getElementById('foto').value = '';
    document.getElementById('file-preview').classList.add('hidden');
}
</script>

@endsection
