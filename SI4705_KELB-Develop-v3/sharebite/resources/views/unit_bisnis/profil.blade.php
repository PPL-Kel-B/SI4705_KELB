@extends('layouts.unit_bisnis')

@section('title', 'Profil Unit Bisnis')

@php
    $hideSearch = true;
@endphp

@section('content')
<div x-data="{
    showSuccess: false,
    showError: false,
    successMessage: '',
    errorMessage: '',
    photoPreview: '{{ ($unitBisnis->foto_bisnis && $unitBisnis->foto_bisnis !== "images/placeholder-bisnis.jpg") ? asset($unitBisnis->foto_bisnis) : "" }}',
    isSaving: false,
    isEditMode: false,
    formData: {
        nama_bisnis: @js(old('nama_bisnis', $unitBisnis->nama_bisnis ?? '')),
        tipe_bisnis: @js(old('tipe_bisnis', $unitBisnis->tipe_bisnis ?? '')),
        email_bisnis: @js(old('email_bisnis', $unitBisnis->email_bisnis ?? '')),
        no_telepon: @js(old('no_telepon', $unitBisnis->no_telepon ?? '')),
    },
    origData: {
        nama_bisnis: @js(old('nama_bisnis', $unitBisnis->nama_bisnis ?? '')),
        tipe_bisnis: @js(old('tipe_bisnis', $unitBisnis->tipe_bisnis ?? '')),
        email_bisnis: @js(old('email_bisnis', $unitBisnis->email_bisnis ?? '')),
        no_telepon: @js(old('no_telepon', $unitBisnis->no_telepon ?? '')),
    },
    cancelEdit() {
        this.formData = { ...this.origData };
        this.isEditMode = false;
    },
    init() {
        @if(session('success'))
            this.showSuccess = true;
            this.successMessage = @js(session('success'));
            setTimeout(() => { this.showSuccess = false; }, 4000);
        @endif
        @if(session('error'))
            this.showError = true;
            this.errorMessage = @js(session('error'));
            setTimeout(() => { this.showError = false; }, 4000);
        @endif
        @if($errors->any())
            this.showError = true;
            this.errorMessage = 'Terdapat kesalahan pada data yang dimasukkan. Silakan periksa kembali.';
            setTimeout(() => { this.showError = false; }, 5000);
        @endif
        @if(request('changePassword') === 'true')
            this.$nextTick(() => {
                document.getElementById('change-password-modal').showModal();
                history.replaceState(null, '', '{{ route('unit.profil') }}');
            });
        @endif
    }
}" class="space-y-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    {{-- Success Notification --}}
    <div x-show="showSuccess" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-[-8px]" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed top-4 right-4 z-50 bg-[#eefcf4] border border-green-200 rounded-2xl p-4 shadow-xl max-w-sm" x-cloak>
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#16a34a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm text-gray-800 font-bold" x-text="successMessage"></p>
            </div>
            <button @click="showSuccess = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Error Notification --}}
    <div x-show="showError" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-[-8px]" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed top-4 right-4 z-50 bg-red-50 border border-red-200 rounded-2xl p-4 shadow-xl max-w-sm" x-cloak>
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm text-red-800 font-bold" x-text="errorMessage"></p>
            </div>
            <button @click="showError = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Main Profile Layout Form --}}
    <form method="POST" action="{{ route('unit.profil.update') }}" enctype="multipart/form-data" 
        @submit="isSaving = true" class="space-y-6" id="profile-form">
        @csrf

        {{-- Hidden Coordinates & Photo inputs --}}
        <input type="hidden" id="lokasi_lat" name="lokasi_lat" value="{{ old('lokasi_lat', $unitBisnis->lokasi_lat ?? '-6.9271') }}">
        <input type="hidden" id="lokasi_lng" name="lokasi_lng" value="{{ old('lokasi_lng', $unitBisnis->lokasi_lng ?? '107.6411') }}">
        <input type="hidden" id="delete_photo" name="delete_photo" value="0">

        {{-- Hidden inputs informasi bisnis — selalu terkirim meski field disabled --}}
        <input type="hidden" name="nama_bisnis" :value="formData.nama_bisnis">
        <input type="hidden" name="tipe_bisnis" :value="formData.tipe_bisnis">
        <input type="hidden" name="email_bisnis" :value="formData.email_bisnis">
        <input type="hidden" name="no_telepon" :value="formData.no_telepon">
        <input type="file" id="foto_input" name="foto_bisnis" accept="image/*" class="hidden"
            @change="
                const file = $el.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        photoPreview = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            ">

        {{-- Two Columns Grid Layout --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            {{-- LEFT COLUMN: Profile Header, Business Info, Security (5 cols) --}}
            <div class="lg:col-span-5 space-y-6">
                
                {{-- CARD 1: Basic Info (Avatar & Badge) --}}
                <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-[0_10px_30px_rgba(0,0,0,0.03)]">
                    <div class="flex items-center gap-6">
                       {{-- Profile Photo Container --}}
                        <div class="relative shrink-0">

                            {{-- Jika Belum Ada Foto --}}
                            <div x-show="!photoPreview"
                                x-cloak
                                @click="document.getElementById('foto_input').click()"
                                class="w-24 h-24 rounded-full border-2 border-dashed border-[#1cb764] bg-[#eefcf4] flex flex-col items-center justify-center cursor-pointer hover:bg-[#dcfce7] transition-all shadow-sm">

                                <div class="w-9 h-9 rounded-full bg-[#1cb764] text-white flex items-center justify-center text-2xl font-bold leading-none shadow-md">
                                    +
                                </div>

                                <span class="text-[10px] font-extrabold text-[#16a34a] mt-2">
                                    Tambah Foto
                                </span>
                            </div>

                            {{-- Jika Sudah Ada Foto / Setelah Pilih Foto --}}
                            <div x-show="photoPreview" x-cloak class="relative group">

                                <img
                                    :src="photoPreview"
                                    alt="Foto Bisnis"
                                    class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-md ring-1 ring-gray-100">

                                @if($unitBisnis->verified || $unitBisnis->status_verifikasi === 'terverifikasi')
                                <div class="absolute bottom-0.5 right-0.5 w-6 h-6 bg-[#009b4d] rounded-full border-2 border-white flex items-center justify-center text-white shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                @endif

                                <div class="absolute inset-0 rounded-full bg-black/45 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center cursor-pointer"
                                    @click="document.getElementById('foto_input').click()">

                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>

                                <button type="button"
                                    onclick="document.getElementById('delete-photo-modal').showModal()"
                                    class="absolute -top-2 -right-2 w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full shadow-lg flex items-center justify-center transition-all z-20"
                                    title="Hapus Foto">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Business Name, Status & Joined --}}
                        <div class="space-y-2">
                            <h2 class="text-3xl font-black text-[#0a2e1f] tracking-tight leading-tight">
                                {{ $unitBisnis->nama_bisnis ?: Auth::user()->name }}
                            </h2>
                            @if($unitBisnis->verified || $unitBisnis->status_verifikasi === 'terverifikasi')
                            <div class="inline-flex items-center gap-1.5 bg-[#eefcf4] text-[#16a34a] px-3 py-1.5 rounded-lg border border-green-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-[10px] font-extrabold tracking-widest uppercase leading-none">Verified Business Partner</span>
                            </div>
                            @endif


<p class="text-xs text-gray-500 font-medium">Bekerja sama sejak {{ $unitBisnis->tahun_bergabung ? 'Januari ' . $unitBisnis->tahun_bergabung : 'Januari 2023' }}</p>
                        </div>
                    </div>
                </div>

                {{-- CARD 2: Informasi Bisnis --}}
                <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-[0_10px_30px_rgba(0,0,0,0.03)]">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-[#eefcf4] flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#1cb764]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">Informasi Bisnis</h3>
                        </div>
                        <button type="button" @click="isEditMode ? cancelEdit() : isEditMode = true"
                            :class="isEditMode ? 'text-red-500 hover:text-red-600' : 'text-[#1cb764] hover:text-[#16a34a]'"
                            class="flex items-center gap-1.5 text-xs font-semibold transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                            <span x-show="!isEditMode">Ubah</span>
                            <span x-show="isEditMode" x-cloak>Batal</span>
                        </button>
                    </div>

                    <div class="space-y-6">
                        {{-- Nama Bisnis --}}
                        <div class="space-y-1">
                            <label class="text-[10px] font-extrabold text-[#7c9a8d] uppercase tracking-wider block">
                                Nama Bisnis / Usaha
                            </label>

                            <input type="text"
                                x-model="formData.nama_bisnis"
                                :disabled="!isEditMode"
                                :class="isEditMode ? 'bg-white text-[#0a2e1f] border-2 border-[#1cb764]' : 'bg-gray-100 text-gray-500 border border-gray-200'"
                                class="w-full text-base font-bold rounded-xl px-4 py-3 focus:border-[#1cb764] focus:ring-0 transition-all placeholder-gray-300 disabled:cursor-not-allowed"
                                placeholder="Masukkan nama bisnis">

                            @error('nama_bisnis')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tipe Bisnis --}}
                        <div class="space-y-1 relative">
                            <label class="text-[10px] font-extrabold text-[#7c9a8d] uppercase tracking-wider block">
                                Tipe Bisnis
                            </label>

                            <div class="relative">
                                <select x-model="formData.tipe_bisnis"
                                    :disabled="!isEditMode"
                                    :class="[
                                        isEditMode ? 'bg-white text-[#0a2e1f] border-2 border-[#1cb764]' : 'bg-gray-100 text-gray-500 border border-gray-200',
                                        formData.tipe_bisnis === '' ? 'text-gray-300' : ''
                                    ]"
                                    class="w-full text-base font-bold rounded-xl px-4 py-3 pr-8 focus:border-[#1cb764] focus:ring-0 transition-all appearance-none disabled:cursor-not-allowed">

                                    <option value="" hidden>— Pilih Tipe Bisnis —</option>
                                    <option value="Tidak Ada">Tidak Ada</option>
                                    <option value="Restoran">Restoran</option>
                                    <option value="Kafe">Kafe</option>
                                    <option value="Bakery">Bakery</option>
                                    <option value="Catering">Catering</option>
                                    <option value="Hotel">Hotel</option>
                                    <option value="Toko Makanan">Toko Makanan</option>
                                </select>

                                {{-- Icon panah kanan seperti gambar --}}
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="absolute right-0 top-1/2 -translate-y-1/2 pointer-events-none h-5 w-5 text-[#7c9a8d]"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2.3">

                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </div>

                            @error('tipe_bisnis')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email Kontak --}}
                        <div class="space-y-1">
                            <label class="text-[10px] font-extrabold text-[#7c9a8d] uppercase tracking-wider block">Email Kontak</label>
                            <input type="email"
                                x-model="formData.email_bisnis"
                                :disabled="!isEditMode"
                                :class="isEditMode ? 'bg-white text-[#0a2e1f] border-2 border-[#1cb764]' : 'bg-gray-100 text-gray-500 border border-gray-200'"
                                class="w-full text-base font-bold rounded-xl px-4 py-3 focus:border-[#1cb764] focus:ring-0 transition-all placeholder-gray-300 disabled:cursor-not-allowed"
                                placeholder="Masukkan email bisnis">
                            @error('email_bisnis')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Nomor Telepon --}}
                        <div class="space-y-1">
                            <label class="text-[10px] font-extrabold text-[#7c9a8d] uppercase tracking-wider block">Nomor Telepon</label>
                            <input type="text"
                                x-model="formData.no_telepon"
                                id="no_telepon_input"
                                :disabled="!isEditMode"
                                :class="isEditMode ? 'bg-white text-[#0a2e1f] border-2 border-[#1cb764]' : 'bg-gray-100 text-gray-500 border border-gray-200'"
                                class="w-full text-base font-bold rounded-xl px-4 py-3 focus:border-[#1cb764] focus:ring-0 transition-all placeholder-gray-300 disabled:cursor-not-allowed"
                                placeholder="Masukkan nomor telepon"
                                inputmode="numeric"
                                @input="handlePhoneInput($el)"
                                @blur="validatePhoneNumber($el)">
                            @error('no_telepon')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-[9px] text-gray-400 mt-1" id="phone_helper">*Hanya angka dan tanda (+, -) yang diperbolehkan</p>
                        </div>
                    </div>
                </div>

                {{-- CARD 3: Keamanan --}}
                <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-[0_10px_30px_rgba(0,0,0,0.03)]">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-2xl bg-amber-50 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.952 11.952 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Keamanan</h3>
                    </div>

                    <div class="space-y-4">
                        {{-- Password Management Button --}}
                        <div @click="document.getElementById('change-password-modal').showModal()" 
                             class="group cursor-pointer flex items-center justify-between p-4 bg-gray-50 hover:bg-[#eefcf4] rounded-2xl border border-gray-100 transition-all duration-300">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white group-hover:bg-green-100 flex items-center justify-center shadow-sm transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 group-hover:text-[#16a34a] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                    </svg>
                                </div>
                                <span class="text-sm font-bold text-gray-700 group-hover:text-[#0a2e1f] transition-colors">Manajemen Kata Sandi</span>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-hover:text-[#1cb764] group-hover:translate-x-0.5 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                        <p class="text-[10px] text-gray-400 font-medium leading-relaxed">
                            *Keamanan akun Anda adalah prioritas kami. Pastikan untuk memperbarui kata sandi secara berkala.
                        </p>
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN: Stats & Location (7 cols) --}}
            <div class="lg:col-span-7 space-y-6">
                
                {{-- TOP: Two Stats Cards Side-By-Side --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    
                    {{-- Stats A: Dampak Sosial --}}
                    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-[0_10px_30px_rgba(0,0,0,0.03)] flex flex-col justify-between min-h-[176px]">
                        <div class="flex items-start gap-3">
                            <div class="w-14 h-14 rounded-2xl bg-[#eefcf4] flex items-center justify-center shrink-0">
                            {{-- Icon Daun --}}
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-10 w-10 text-[#22c55e]"
                                viewBox="0 0 24 24"
                                fill="currentColor">
                                <path d="M20.5 3.5c-7.2-.2-12.7 2.2-15.4 6.8-1.9 3.2-1.4 6.8.7 8.9.2-2.2 1.1-5.2 3.6-8.1.3-.4.9-.4 1.3-.1.4.3.4.9.1 1.3-2.3 2.8-3.1 5.6-3.2 7.5 2.6 1.2 6.4.4 9.1-2.3 3.2-3.1 4.5-8.1 3.8-14z"/>
                            </svg>
                        </div>
                        <div class="text-left">
                                <span class="text-[9px] font-extrabold text-gray-400 uppercase tracking-widest block">Dampak Sosial</span>
                                <span class="text-3xl font-black text-gray-900 block leading-tight">{{ number_format($stats['total_porsi'], 0, ',', '.') }}</span>
                                <span class="text-xs font-extrabold text-[#16a34a] block leading-none">Porsi Makanan</span>
                            </div>
                        </div>
                        <div class="text-xs text-gray-400 italic text-left pt-3 border-t border-gray-50 mt-3">
                            "Total Makanan Dibagikan"
                        </div>
                    </div>

                    {{-- Stats B: Dampak Lingkungan --}}
                    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-[0_10px_30px_rgba(0,0,0,0.03)] flex flex-col justify-between min-h-[176px]">
                        <div class="flex items-start gap-3">
                            <div class="w-14 h-14 rounded-2xl bg-amber-50 flex items-center justify-center shrink-0">
                            {{-- Icon Timbangan --}}
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-10 w-10 text-[#8a5a00]"
                                viewBox="0 0 24 24"
                                fill="currentColor">
                                <path d="M12 2a1 1 0 0 1 1 1v1h7a1 1 0 1 1 0 2h-3.2l3 5.2c.1.2.2.4.2.6C20 14.7 18.2 17 16 17s-4-2.3-4-5.2c0-.2.1-.4.2-.6L15.2 6H13v10h2a1 1 0 1 1 0 2H9a1 1 0 1 1 0-2h2V6H8.8l3 5.2c.1.2.2.4.2.6C12 14.7 10.2 17 8 17s-4-2.3-4-5.2c0-.2.1-.4.2-.6L7.2 6H4a1 1 0 1 1 0-2h7V3a1 1 0 0 1 1-1ZM6.1 12h3.8L8 8.7 6.1 12Zm8 0h3.8L16 8.7 14.1 12Z"/>
                            </svg>
                        </div>
                        <div class="text-left">
                                <span class="text-[9px] font-extrabold text-gray-400 uppercase tracking-widest block">Dampak Lingkungan</span>
                                <span class="text-3xl font-black text-gray-900 block leading-tight">{{ number_format($stats['total_kg'], 0, ',', '.') }}</span>
                                <span class="text-xs font-extrabold text-amber-700 block leading-none">Kilogram</span>
                            </div>
                        </div>
                        <div class="text-xs text-gray-400 italic text-left pt-3 border-t border-gray-50 mt-3">
                            "Total Berat Terselamatkan"
                        </div>
                    </div>
                </div>

                {{-- CARD 4: Lokasi & Alamat --}}
                <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-[0_10px_30px_rgba(0,0,0,0.03)] space-y-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-[#eefcf4] flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#1cb764]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">Lokasi & Alamat</h3>
                        </div>
                        <button type="button" onclick="openEditLocationModal()"
                            class="flex items-center gap-1.5 text-xs font-semibold text-[#1cb764] hover:text-[#16a34a] transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                            Ubah
                        </button>
                    </div>

                    {{-- Hidden address input for form submit --}}
                    <input type="hidden" id="alamat_input" name="alamat" value="{{ old('alamat', $unitBisnis->alamat) }}">

                    {{-- Address Callout Box --}}
                    <div class="bg-[#f7faf8] rounded-2xl p-5 border border-gray-50 flex items-start gap-4">
                        <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 mt-1.5 shrink-0 animate-pulse"></div>
                        <p id="alamat_display" class="text-sm font-bold text-[#0a2e1f] leading-relaxed text-left whitespace-pre-line">
                            {{ $unitBisnis->alamat ?: 'Masukkan alamat anda. Klik "Ubah" untuk menambahkan.' }}
                        </p>
                    </div>

                    {{-- Peta Map Container --}}
                    <div class="relative rounded-3xl overflow-hidden shadow-inner border border-gray-100 group">
                        {{-- Main Map Div --}}
                        <div id="main-map" class="h-80 z-10"></div>
                        
                        {{-- Floating Location Label on Map --}}
                        <div class="absolute top-4 left-4 z-20 bg-white/95 backdrop-blur-sm p-4 rounded-2xl shadow-lg border border-gray-50 max-w-[260px] pointer-events-none transition-all">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                <span class="text-[10px] font-black text-gray-500 tracking-wider">LOKASI SAAT INI</span>
                            </div>
                            <p class="text-[11px] text-gray-600 font-semibold leading-relaxed">
                                Titik jemput makanan otomatis disesuaikan dengan koordinat ini.
                            </p>
                        </div>

                        {{-- Floating Action Button on Map --}}
                        <button type="button" onclick="openEditLocationModal()"
                            class="absolute bottom-4 right-4 z-20 w-12 h-12 rounded-full bg-[#009b4d] hover:bg-[#008240] text-white flex items-center justify-center shadow-lg transition-transform hover:scale-105 active:scale-95"
                            title="Sesuaikan Lokasi Peta">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Bottom Button Actions --}}
                <div class="flex items-center justify-end gap-4 pt-4">
                    <a href="{{ route('unit.profil') }}" class="px-6 py-3 text-sm font-bold text-gray-500 hover:text-gray-800 transition-colors">
                        Batal
                    </a>
                    <button type="submit" :disabled="isSaving"
                        :class="isSaving ? 'opacity-75 cursor-not-allowed' : 'hover:bg-[#16a34a] hover:shadow-green-200/30'"
                        class="px-8 py-3.5 bg-[#1cb764] text-white font-extrabold text-sm rounded-2xl shadow-lg shadow-green-100/15 transition-all flex items-center gap-2">
                        <span x-show="!isSaving">Simpan Perubahan</span>
                        <span x-show="isSaving" class="flex items-center gap-2" x-cloak>
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </form>


    {{-- Delete Photo Confirmation Modal --}}
    <dialog id="delete-photo-modal" class="modal">
        <div class="modal-box max-w-sm rounded-3xl p-8 border border-gray-100 bg-white shadow-2xl text-center">

            <div class="mx-auto mb-5 w-16 h-16 rounded-full bg-red-100 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>

            <h3 class="text-xl font-extrabold text-gray-900 mb-2">
                Hapus Foto Profil?
            </h3>

            <p class="text-sm text-gray-500 mb-7">
                Apakah Anda yakin ingin menghapus foto profile?
            </p>

            <div class="flex gap-3">
                <button type="button"
                    onclick="document.getElementById('delete-photo-modal').close()"
                    class="w-1/2 py-3 rounded-2xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold transition-all">
                    Tidak
                </button>

                <button type="button"
                    onclick="deleteProfilePhoto()"
                    class="w-1/2 py-3 rounded-2xl bg-red-500 hover:bg-red-600 text-white font-bold shadow-lg transition-all">
                    Iya
                </button>
            </div>
        </div>
    </dialog>

    {{-- Form khusus hapus foto (terpisah dari form profil utama) --}}
    <form id="hapus-foto-form" method="POST" action="{{ route('unit.profil.hapus-foto') }}" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    {{-- Change Password Modal --}}
    <dialog id="change-password-modal" class="modal">
        <form method="POST" action="{{ route('unit.pengaturan.update-password') }}" class="modal-box max-w-sm rounded-3xl p-8 border border-gray-100 bg-white shadow-2xl">
            @csrf
            
            <h3 class="font-extrabold text-lg text-gray-900 mb-6 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
                Ganti Kata Sandi
            </h3>
            
            <div class="space-y-4">
                {{-- Kata Sandi Saat Ini --}}
                <div x-data="{ show: false }">
                    <label class="block text-xs font-bold text-gray-600 mb-1.5">Kata Sandi Saat Ini</label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" name="current_password"
                            class="w-full px-4 py-3 pr-11 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-900 focus:bg-white focus:border-[#1cb764] focus:ring-2 focus:ring-green-100 outline-none transition-all"
                            placeholder="Masukkan kata sandi saat ini" required>
                        <button type="button" @click="show = !show"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                            <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="show" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                    @error('current_password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kata Sandi Baru --}}
                <div x-data="{ show: false }">
                    <label class="block text-xs font-bold text-gray-600 mb-1.5">Kata Sandi Baru</label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" name="password"
                            class="w-full px-4 py-3 pr-11 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-900 focus:bg-white focus:border-[#1cb764] focus:ring-2 focus:ring-green-100 outline-none transition-all"
                            placeholder="Minimal 8 karakter" required>
                        <button type="button" @click="show = !show"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                            <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="show" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Konfirmasi Kata Sandi Baru --}}
                <div x-data="{ show: false }">
                    <label class="block text-xs font-bold text-gray-600 mb-1.5">Konfirmasi Kata Sandi Baru</label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" name="password_confirmation"
                            class="w-full px-4 py-3 pr-11 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-900 focus:bg-white focus:border-[#1cb764] focus:ring-2 focus:ring-green-100 outline-none transition-all"
                            placeholder="Ulangi kata sandi baru" required>
                        <button type="button" @click="show = !show"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                            <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="show" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-2.5 mt-8">
                <button type="button" class="px-5 py-2.5 rounded-xl text-sm font-bold text-gray-500 hover:text-gray-700 transition-colors" onclick="document.getElementById('change-password-modal').close()">Batal</button>
                <button type="submit" class="px-6 py-2.5 bg-[#16a34a] hover:bg-[#15803d] text-white rounded-xl text-sm font-bold shadow-md transition-colors">Ganti Kata Sandi</button>
            </div>
        </form>
    </dialog>

    {{-- Modal Ubah Lokasi Map --}}
    <dialog id="edit-location-modal" class="modal">
        <div class="modal-box max-w-2xl rounded-3xl p-8 border border-gray-100 bg-white shadow-2xl space-y-6 max-h-[90vh] overflow-y-auto">
            <h3 class="font-extrabold text-lg text-gray-900 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#1cb764]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Ubah Lokasi & Alamat Bisnis
            </h3>

            <div class="space-y-4">

                {{-- Alamat utama --}}
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1.5">Alamat</label>
                    <textarea id="modal_alamat" rows="2" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-900 focus:bg-white focus:border-[#1cb764] focus:ring-2 focus:ring-green-100 outline-none transition-all leading-relaxed"
                              placeholder="Contoh: Jl. Sukapura, Sukapura, Kec. Dayeuhkolot, Kabupaten Bandung">{{ $unitBisnis->alamat }}</textarea>
                    <p class="text-xs text-gray-400 font-medium leading-relaxed mt-1">
                        *Ketik alamat atau klik peta untuk isi otomatis.
                    </p>
                </div>

                {{-- Detail Alamat: no rumah, blok, patokan --}}
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1.5">
                        Detail Alamat
                        <span class="text-gray-400 font-normal normal-case">(no. rumah, blok, patokan — opsional)</span>
                    </label>
                    <input id="modal_detail" type="text"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-900 focus:bg-white focus:border-[#1cb764] focus:ring-2 focus:ring-green-100 outline-none transition-all"
                        placeholder="Contoh: No. 15, Blok B3, dekat Alfamart">
                    <p class="text-xs text-gray-400 font-medium leading-relaxed mt-1">
                        *Akan ditambahkan ke alamat sebagai keterangan tambahan.
                    </p>
                </div>

                {{-- Peta --}}
                <p class="text-xs text-gray-400 font-medium leading-relaxed">
                    *Klik titik lokasi pada peta atau geser pin untuk mengambil alamat secara otomatis.
                </p>

                <div class="rounded-2xl overflow-hidden border border-gray-200 shadow-inner">
                    <div id="edit-map" class="h-64 w-full"></div>
                </div>
            </div>

            <div class="flex justify-end gap-2.5 mt-8">
                <button type="button" class="px-5 py-2.5 rounded-xl text-sm font-bold text-gray-500 hover:text-gray-700 transition-colors"
                        onclick="document.getElementById('edit-location-modal').close()">Batal</button>
                <button type="button" class="px-6 py-2.5 bg-[#1cb764] hover:bg-[#16a34a] text-white rounded-xl text-sm font-bold shadow-md transition-colors"
                        onclick="saveLocationFromModal()">Simpan Lokasi</button>
            </div>
        </div>
    </dialog>

</div>

{{-- Leaflet Map Setup --}}
@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    let mainMap, editMap;
    let mainMarker, editMarker;

    const initLat = parseFloat("{{ $unitBisnis->lokasi_lat ?? '-6.9271' }}");
    const initLng = parseFloat("{{ $unitBisnis->lokasi_lng ?? '107.6411' }}");
    const businessName = "{{ addslashes($unitBisnis->nama_bisnis ?? Auth::user()->name) }}";

    const greenIcon = L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
        iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
    });

    document.addEventListener('DOMContentLoaded', () => {
        mainMap = L.map('main-map', { zoomControl: true, dragging: true, scrollWheelZoom: false })
                   .setView([initLat, initLng], 15);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
            subdomains: 'abcd', maxZoom: 20
        }).addTo(mainMap);

        mainMarker = L.marker([initLat, initLng], { icon: greenIcon }).addTo(mainMap);
        mainMarker.bindPopup(`<b>${businessName}</b><br>Lokasi Penjemputan Makanan`).openPopup();
    });

    function geocodeAddress(address) {
        if (!address || !address.trim()) return;
        const url = `https://nominatim.openstreetmap.org/search?format=json&limit=1&countrycodes=id&accept-language=id&q=${encodeURIComponent(address)}`;
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data && data.length > 0) {
                    const lat = parseFloat(data[0].lat);
                    const lng = parseFloat(data[0].lon);
                    document.getElementById('lokasi_lat').value = lat;
                    document.getElementById('lokasi_lng').value = lng;
                    if (editMap && editMarker) {
                        editMap.setView([lat, lng], 18);
                        editMarker.setLatLng([lat, lng]);
                    }
                }
            })
            .catch(err => console.log('Geocoding error:', err));
    }

    function reverseGeocodeCoordinates(lat, lng) {
        const url = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}&zoom=19&addressdetails=1&namedetails=1&extratags=1&accept-language=id`;
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data && data.address) {
                    const a = data.address;
                    const jalan = a.road || a.pedestrian || a.footway || a.path || a.service || '';
                    const gang  = a.alley || a.neighbourhood || a.residential || a.quarter || a.hamlet || '';
                    const blok  = a.block || a.suburb || a.city_district || '';
                    const nomor = a.house_number || '';
                    const desa  = a.village || a.suburb || a.town || '';
                    const kecamatan = a.municipality || a.city_district || a.district || '';
                    const kota      = a.city || a.town || a.county || a.regency || '';
                    const provinsi  = a.state || '';
                    const kodepos   = a.postcode || '';
                    const negara    = a.country || '';

                    let parts = [];
                    if (jalan) parts.push(jalan + (nomor ? ` No. ${nomor}` : ''));
                    if (gang && gang !== jalan && gang !== desa) parts.push(gang);
                    if (blok && blok !== gang && blok !== desa && blok !== kecamatan) parts.push(blok);
                    if (desa && desa !== gang) parts.push(desa);
                    if (kecamatan && kecamatan !== desa && kecamatan !== kota) parts.push(kecamatan);
                    if (kota) parts.push(kota);
                    if (provinsi) parts.push(provinsi);
                    if (kodepos) parts.push(kodepos);
                    if (negara) parts.push(negara);

                    let alamat = parts.filter(Boolean).join(', ')
                        .replace(/,\s*,/g, ',').replace(/^,\s*|,\s*$/g, '');

                    if (!alamat || (!jalan && !nomor)) {
                        alamat = data.display_name || `Koordinat: ${lat}, ${lng}`;
                    }

                    document.getElementById('modal_alamat').value = alamat;
                    document.getElementById('lokasi_lat').value   = lat;
                    document.getElementById('lokasi_lng').value   = lng;
                    if (editMarker) editMarker.setLatLng([lat, lng]);
                }
            })
            .catch(err => console.log('Reverse geocoding error:', err));
    }

    function openEditLocationModal() {
        document.getElementById('edit-location-modal').showModal();

        if (!editMap) {
            setTimeout(() => {
                const lat = parseFloat(document.getElementById('lokasi_lat').value) || initLat;
                const lng = parseFloat(document.getElementById('lokasi_lng').value) || initLng;

                editMap = L.map('edit-map').setView([lat, lng], 18);
                L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                    attribution: '&copy; OpenStreetMap contributors &copy; CARTO',
                    subdomains: 'abcd', maxZoom: 20
                }).addTo(editMap);

                editMarker = L.marker([lat, lng], { icon: greenIcon, draggable: true }).addTo(editMap);

                const alamatInput = document.getElementById('modal_alamat');
                let geocodeTimeout;
                function scheduleGeocode(val) {
                    clearTimeout(geocodeTimeout);
                    geocodeTimeout = setTimeout(() => geocodeAddress(val), 500);
                }
                alamatInput.addEventListener('input', function() { scheduleGeocode(this.value); });
                alamatInput.addEventListener('paste', function() { setTimeout(() => scheduleGeocode(this.value), 200); });
                alamatInput.addEventListener('blur',  function() { scheduleGeocode(this.value); });

                editMarker.on('dragend', function() {
                    const pos = editMarker.getLatLng();
                    reverseGeocodeCoordinates(pos.lat, pos.lng);
                });

                editMap.on('click', function(e) {
                    editMarker.setLatLng(e.latlng);
                    reverseGeocodeCoordinates(e.latlng.lat, e.latlng.lng);
                });
            }, 250);
        } else {
            setTimeout(() => {
                editMap.invalidateSize();
                const lat = parseFloat(document.getElementById('lokasi_lat').value) || initLat;
                const lng = parseFloat(document.getElementById('lokasi_lng').value) || initLng;
                editMap.setView([lat, lng], 18);
                editMarker.setLatLng([lat, lng]);
                document.getElementById('modal_alamat').value = document.getElementById('alamat_input').value;
            }, 250);
        }
    }

    function saveLocationFromModal() {
        const lat    = parseFloat(document.getElementById('lokasi_lat').value) || initLat;
        const lng    = parseFloat(document.getElementById('lokasi_lng').value) || initLng;
        const alamat = document.getElementById('modal_alamat').value.trim();
        const detail = document.getElementById('modal_detail').value.trim();

        const alamatFinal = detail ? alamat + (alamat ? '\n' : '') + 'Detail: ' + detail : alamat;

        document.getElementById('alamat_input').value       = alamatFinal;
        document.getElementById('alamat_display').innerText = alamatFinal || 'Belum ada alamat';

        // Reset field detail setelah simpan
        document.getElementById('modal_detail').value = '';

        if (mainMap && mainMarker) {
            mainMap.setView([lat, lng], 18);
            mainMarker.setLatLng([lat, lng]);
            mainMarker.bindPopup(`<b>${businessName}</b><br>${alamat || 'Lokasi Penjemputan Makanan'}`).openPopup();
        }

        document.getElementById('edit-location-modal').close();
    }

    // Handle phone input - allow only numbers and +, -, (, ), space
    function handlePhoneInput(input) {
        input.value = input.value.replace(/[^0-9+\-() ]/g, '');
    }

    // Validate phone number
    function validatePhoneNumber(input) {
        const value = input.value.trim();
        const helper = document.getElementById('phone_helper');
        
        if (value && !/^[0-9+\-() ]+$/.test(value)) {
            input.classList.add('border-red-500');
            helper.textContent = '❌ Nomor telepon harus berupa angka (format: 08xx atau +62xxx)';
            helper.classList.add('text-red-500');
            return false;
        } else {
            input.classList.remove('border-red-500');
            helper.textContent = '*Hanya angka dan tanda (+, -) yang diperbolehkan';
            helper.classList.remove('text-red-500');
            return true;
        }
    }

    // Delete profile photo using custom confirmation modal
    function deleteProfilePhoto() {
        const modal = document.getElementById('delete-photo-modal');
        if (modal) {
            modal.close();
        }

        document.getElementById('hapus-foto-form').submit();
    }

</script>

@endpush

<style>
    .modal::backdrop {
        background-color: rgba(0, 0, 0, 0.45);
        backdrop-filter: blur(4px);
    }
    .modal {
        border: none;
        padding: 0;
        max-width: 100vw;
        max-height: 100vh;
    }
    .modal-box {
        margin: auto;
    }
</style>
@endsection
