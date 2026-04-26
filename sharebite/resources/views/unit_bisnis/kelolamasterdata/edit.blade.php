@extends('layouts.unit_bisnis')

@section('title', 'Edit Master Data — ' . $makanan->Nama_Makanan)

@section('page-title', 'Edit Master Data Makanan')

@section('header-tabs')
<div class="flex items-center space-x-1 mt-3">
    <a href="{{ route('kelolamasterdata.index') }}"
       class="px-4 py-1.5 text-sm font-medium text-gray-500 hover:text-brand transition-colors rounded-lg hover:bg-brand-light">
        ← Kembali ke Master Data
    </a>
</div>
@endsection

@section('content')

<div class="max-w-3xl mx-auto">

    {{-- ===== VALIDATION ERRORS ===== --}}
    @if($errors->any())
    <div class="alert-animate mb-6 bg-red-50 border border-red-200 rounded-2xl p-5">
        <div class="flex items-start space-x-3">
            <div class="w-8 h-8 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-bold text-red-800 mb-2">Terdapat kesalahan input:</p>
                <ul class="space-y-1">
                    @foreach($errors->all() as $error)
                    <li class="text-sm text-red-700 flex items-center space-x-1.5">
                        <span class="w-1.5 h-1.5 bg-red-400 rounded-full inline-block flex-shrink-0"></span>
                        <span>{{ $error }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="alert-animate mb-6 flex items-center space-x-3 bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-2xl">
        <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-sm font-semibold">{{ session('error') }}</p>
    </div>
    @endif

    {{-- ===== EDIT CARD ===== --}}
    <div class="bg-white rounded-3xl shadow-sm overflow-hidden" style="border: 1px solid #e8f0ed;">

        {{-- Card Header --}}
        <div class="bg-gradient-to-r from-brand to-brand-dark px-8 py-6">
            <div class="flex items-center space-x-4">
                {{-- Food Image Preview --}}
                <div class="w-16 h-16 rounded-2xl overflow-hidden bg-white/20 flex-shrink-0">
                    @if($makanan->Foto)
                        <img src="{{ Storage::url($makanan->Foto) }}"
                             alt="{{ $makanan->Nama_Makanan }}"
                             class="w-full h-full object-cover"
                             id="current-food-img"
                             onerror="this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=200&q=80'">
                    @else
                        <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=200&q=80"
                             alt="{{ $makanan->Nama_Makanan }}"
                             class="w-full h-full object-cover"
                             id="current-food-img">
                    @endif
                </div>
                <div>
                    <p class="text-white/70 text-xs font-semibold uppercase tracking-widest mb-0.5">
                        {{ $makanan->Kategori ?? 'Makanan' }}
                    </p>
                    <h2 class="text-white text-xl font-extrabold">{{ $makanan->Nama_Makanan }}</h2>
                    <p class="text-white/60 text-xs mt-0.5">ID: {{ $makanan->MakananID }}</p>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('kelolamasterdata.update', $makanan->MakananID) }}"
              method="POST"
              enctype="multipart/form-data"
              class="p-8 space-y-6">
            @csrf
            @method('PUT')

            {{-- Row 1: Nama & Kategori --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Nama Makanan --}}
                <div>
                    <label for="Nama_Makanan"
                           class="block text-sm font-semibold text-gray-700 mb-2">
                        Nama Makanan <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="Nama_Makanan"
                           name="Nama_Makanan"
                           value="{{ old('Nama_Makanan', $makanan->Nama_Makanan) }}"
                           placeholder="Contoh: Nasi Campur Bali"
                           class="w-full bg-[#f0f7f4] border-2 border-transparent rounded-2xl px-4 py-3.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:border-brand focus:bg-white transition @error('Nama_Makanan') border-red-400 bg-red-50 @enderror">
                    @error('Nama_Makanan')
                    <p class="text-xs text-red-500 mt-1.5 flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                {{-- Kategori --}}
                <div>
                    <label for="Kategori"
                           class="block text-sm font-semibold text-gray-700 mb-2">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select id="Kategori"
                                name="Kategori"
                                class="w-full bg-[#f0f7f4] border-2 border-transparent rounded-2xl px-4 py-3.5 text-sm text-gray-800 focus:outline-none focus:border-brand focus:bg-white appearance-none transition @error('Kategori') border-red-400 bg-red-50 @enderror">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach(['Nasi Kotak','Pasta','Salad','Western','Asian','Soup','Dessert','Minuman','Snack','Lainnya'] as $kat)
                            <option value="{{ $kat }}"
                                {{ old('Kategori', $makanan->Kategori) === $kat ? 'selected' : '' }}>
                                {{ $kat }}
                            </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                    @error('Kategori')
                    <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Row 2: Berat, Jumlah Porsi, Harga --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Berat --}}
                <div>
                    <label for="Berat"
                           class="block text-sm font-semibold text-gray-700 mb-2">
                        Berat (kg) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="number"
                               id="Berat"
                               name="Berat"
                               step="0.01"
                               min="0"
                               value="{{ old('Berat', $makanan->Berat) }}"
                               placeholder="0.45"
                               class="w-full bg-[#f0f7f4] border-2 border-transparent rounded-2xl pl-4 pr-12 py-3.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:border-brand focus:bg-white transition @error('Berat') border-red-400 bg-red-50 @enderror">
                        <span class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 text-sm font-medium">kg</span>
                    </div>
                    @error('Berat')
                    <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Jumlah Porsi --}}
                <div>
                    <label for="Jumlah_porsi"
                           class="block text-sm font-semibold text-gray-700 mb-2">
                        Jumlah Porsi <span class="text-red-500">*</span>
                    </label>
                    <input type="number"
                           id="Jumlah_porsi"
                           name="Jumlah_porsi"
                           min="0"
                           value="{{ old('Jumlah_porsi', $makanan->Jumlah_porsi) }}"
                           placeholder="10"
                           class="w-full bg-[#f0f7f4] border-2 border-transparent rounded-2xl px-4 py-3.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:border-brand focus:bg-white transition @error('Jumlah_porsi') border-red-400 bg-red-50 @enderror">
                    @error('Jumlah_porsi')
                    <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Harga --}}
                <div>
                    <label for="Harga"
                           class="block text-sm font-semibold text-gray-700 mb-2">
                        Harga (Rp) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400 text-sm font-medium">Rp</span>
                        <input type="number"
                               id="Harga"
                               name="Harga"
                               step="100"
                               min="0"
                               value="{{ old('Harga', $makanan->Harga) }}"
                               placeholder="25000"
                               class="w-full bg-[#f0f7f4] border-2 border-transparent rounded-2xl pl-10 pr-4 py-3.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:border-brand focus:bg-white transition @error('Harga') border-red-400 bg-red-50 @enderror">
                    </div>
                    @error('Harga')
                    <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Row 3: Status & Batas Waktu --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Status --}}
                <div>
                    <label for="Status"
                           class="block text-sm font-semibold text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <div class="flex space-x-3">
                        @foreach(['Tersedia' => 'bg-green-100 text-green-700 border-green-300', 'Habis' => 'bg-red-100 text-red-700 border-red-300', 'Nonaktif' => 'bg-gray-100 text-gray-600 border-gray-300'] as $status => $classes)
                        <label class="flex-1 cursor-pointer">
                            <input type="radio"
                                   name="Status"
                                   value="{{ $status }}"
                                   class="sr-only peer"
                                   {{ old('Status', $makanan->Status) === $status ? 'checked' : '' }}>
                            <div class="text-center py-2.5 rounded-xl border-2 text-xs font-bold transition peer-checked:ring-2 peer-checked:ring-offset-1 peer-checked:ring-brand {{ $classes }} peer-checked:border-brand">
                                {{ $status }}
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @error('Status')
                    <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Batas Waktu --}}
                <div>
                    <label for="Batas_waktu_pengambilan"
                           class="block text-sm font-semibold text-gray-700 mb-2">
                        Batas Waktu Pengambilan <span class="text-red-500">*</span>
                    </label>
                    <input type="date"
                           id="Batas_waktu_pengambilan"
                           name="Batas_waktu_pengambilan"
                           value="{{ old('Batas_waktu_pengambilan', $makanan->Batas_waktu_pengambilan ? $makanan->Batas_waktu_pengambilan->format('Y-m-d') : '') }}"
                           class="w-full bg-[#f0f7f4] border-2 border-transparent rounded-2xl px-4 py-3.5 text-sm text-gray-800 focus:outline-none focus:border-brand focus:bg-white transition @error('Batas_waktu_pengambilan') border-red-400 @enderror">
                    @error('Batas_waktu_pengambilan')
                    <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Row 4: Foto Upload --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Foto Makanan
                    <span class="text-gray-400 font-normal">(opsional — maks. 2MB)</span>
                </label>

                {{-- Drop zone --}}
                <div id="drop-zone"
                     class="relative border-2 border-dashed border-gray-200 bg-[#f0f7f4] rounded-2xl p-6 hover:border-brand hover:bg-brand-light transition-all cursor-pointer">
                    <input type="file"
                           id="Foto"
                           name="Foto"
                           accept="image/jpg,image/jpeg,image/png,image/webp"
                           class="absolute inset-0 opacity-0 cursor-pointer w-full h-full"
                           onchange="previewImage(event)">
                    <div id="upload-placeholder" class="flex flex-col items-center text-center">
                        <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-sm mb-3">
                            <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-gray-700">Klik atau seret gambar ke sini</p>
                        <p class="text-xs text-gray-400 mt-1">JPG, PNG, WebP • Maks. 2MB</p>
                    </div>
                    <div id="image-preview" class="hidden flex-col items-center">
                        <img id="preview-img" src="" alt="Preview" class="w-32 h-32 object-cover rounded-2xl shadow-md mb-3">
                        <p id="preview-name" class="text-xs font-semibold text-gray-700"></p>
                        <p class="text-xs text-brand mt-1">Gambar baru dipilih — klik untuk ganti</p>
                    </div>
                </div>
                @error('Foto')
                <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- ===== FORM ACTIONS ===== --}}
            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <a href="{{ route('kelolamasterdata.index') }}"
                   class="flex items-center space-x-2 px-6 py-3 border border-gray-200 rounded-2xl text-gray-600 font-semibold hover:bg-gray-50 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Batal</span>
                </a>

                <button type="submit"
                        id="save-btn"
                        class="flex items-center space-x-2 bg-brand hover:bg-brand-dark text-white font-semibold px-8 py-3 rounded-2xl shadow-lg transition-all duration-200"
                        style="box-shadow: 0 4px 18px rgba(28,183,100,0.30);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Simpan Perubahan</span>
                </button>
            </div>

        </form>
    </div>

    {{-- Info card --}}
    <div class="mt-4 flex items-start space-x-2 bg-amber-50 border border-amber-200 rounded-2xl px-5 py-4">
        <svg class="w-4 h-4 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-xs text-amber-700">
            Field bertanda <span class="text-red-500 font-bold">*</span> wajib diisi. Perubahan akan langsung memperbarui data makanan di database.
        </p>
    </div>

</div>

@endsection

@push('scripts')
<script>
    function previewImage(event) {
        const file        = event.target.files[0];
        if (!file) return;

        const placeholder = document.getElementById('upload-placeholder');
        const previewDiv  = document.getElementById('image-preview');
        const previewImg  = document.getElementById('preview-img');
        const previewName = document.getElementById('preview-name');

        const reader = new FileReader();
        reader.onload = function (e) {
            previewImg.src     = e.target.result;
            previewName.textContent = file.name;
            placeholder.classList.add('hidden');
            previewDiv.classList.remove('hidden');
            previewDiv.classList.add('flex');

            // Also update header image
            const headerImg = document.getElementById('current-food-img');
            if (headerImg) headerImg.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }

    // Loading state on submit
    document.querySelector('form').addEventListener('submit', function () {
        const btn = document.getElementById('save-btn');
        btn.disabled = true;
        btn.innerHTML = `
            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            <span>Menyimpan...</span>
        `;
    });
</script>
@endpush
