@extends('layouts.unit_bisnis')

@section('title', 'Pengaturan Unit Bisnis')

@php
    $hideSearch = true;
    $lastPasswordChange = auth()->user()->password_updated_at ?? auth()->user()->updated_at;
    $passwordAgo = $lastPasswordChange ? $lastPasswordChange->diffForHumans() : 'Belum pernah diubah';
@endphp

@section('content')
<div x-data="{
    isSaving: false,
    radiusValue: {{ $unitBisnis->radius_penjemputan ?? 15 }},
    successMessage: '{{ session('success') ?? '' }}',
    showSuccess: {{ session('success') ? 'true' : 'false' }},
    showError: {{ (session('error') || $errors->any()) ? 'true' : 'false' }},
    errorMessage: '{{ session('error') ?? ($errors->any() ? 'Terdapat kesalahan pada pengaturan. Silakan periksa kembali.' : '') }}',
    notifAktif: {{ ($unitBisnis->notifikasi_aktif ?? true) ? 'true' : 'false' }}
}"
x-init="
    if (showSuccess) { setTimeout(() => showSuccess = false, 4000); }
    if (showError) { setTimeout(() => showError = false, 5000); }
"
class="space-y-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Header Section -->
    <div class="mb-6">
        <h1 class="text-4xl font-black text-[#1cb764] tracking-tight">Pengaturan</h1>
        <p class="text-gray-500 font-medium mt-2 text-lg">Kelola preferensi akun dan aplikasi Anda untuk pengalaman berbagi terbaik.</p>
    </div>

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

    {{-- ================================================== --}}
    {{-- TOP SECTION: Two Columns --}}
    {{-- ================================================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        {{-- LEFT COLUMN: Pengaturan Operasional --}}
        <div class="lg:col-span-7">
            <form method="POST" action="{{ route('unit.pengaturan.update') }}" @submit="isSaving = true" id="settings-form">
                @csrf
                <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-[0_10px_30px_rgba(0,0,0,0.03)] space-y-8">

                    {{-- Section Header --}}
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-[#eefcf4] flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#1cb764]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-extrabold text-[#0a2e1f]">Pengaturan Operasional</h2>
                    </div>

                    {{-- Sub-Card: Jam Operasional --}}
                    <div class="bg-[#f7faf8] rounded-2xl p-6 border border-gray-50 space-y-5">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-lg bg-[#eefcf4] flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[#1cb764]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span class="text-[10px] font-extrabold text-[#1cb764] uppercase tracking-widest">Jam Operasional Penjemputan</span>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            {{-- Jam Buka --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-2">Jam Buka</label>
                                <input type="time" name="jam_buka" value="{{ old('jam_buka', $unitBisnis->jam_buka ? substr($unitBisnis->jam_buka, 0, 5) : '08:00') }}"
                                    class="w-full px-4 py-3.5 bg-white border border-gray-200 rounded-2xl text-base font-extrabold text-[#0a2e1f] focus:border-[#1cb764] focus:ring-2 focus:ring-green-100 outline-none transition-all"
                                    required>
                                @error('jam_buka')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Jam Tutup --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-2">Jam Tutup</label>
                                <input type="time" name="jam_tutup" value="{{ old('jam_tutup', $unitBisnis->jam_tutup ? substr($unitBisnis->jam_tutup, 0, 5) : '21:00') }}"
                                    class="w-full px-4 py-3.5 bg-white border border-gray-200 rounded-2xl text-base font-extrabold text-[#0a2e1f] focus:border-[#1cb764] focus:ring-2 focus:ring-green-100 outline-none transition-all"
                                    required>
                                @error('jam_tutup')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <p class="text-xs text-gray-400 font-medium leading-relaxed">
                            Relawan hanya dapat memesan penjemputan dalam rentang waktu ini.
                        </p>
                    </div>

                    {{-- Sub-Card: Radius Penjemputan --}}
                    <div class="bg-[#f7faf8] rounded-2xl p-6 border border-gray-50 space-y-5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-lg bg-[#eefcf4] flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[#1cb764]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <span class="text-[10px] font-extrabold text-[#1cb764] uppercase tracking-widest">Radius Penjemputan Maksimal</span>
                            </div>
                            <span class="text-2xl font-black text-[#0a2e1f]" x-text="radiusValue + ' KM'"></span>
                        </div>

                        {{-- Slider --}}
                        <div class="relative pt-2">
                            <input type="range" name="radius_penjemputan" min="1" max="50"
                                x-model="radiusValue"
                                class="radius-slider w-full h-2 rounded-lg appearance-none cursor-pointer"
                                style="background: linear-gradient(to right, #1cb764, #16a34a);">
                        </div>

                        {{-- Scale Labels --}}
                        <div class="flex items-center justify-between text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                            <span>1 KM</span>
                            <span>25 KM</span>
                            <span>50 KM</span>
                        </div>
                        @error('radius_penjemputan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Save Button --}}
                    <button type="submit" :disabled="isSaving"
                        :class="isSaving ? 'opacity-75 cursor-not-allowed' : 'hover:bg-[#16a34a] hover:shadow-green-200/30'"
                        class="w-full sm:w-auto px-8 py-3.5 bg-[#1cb764] text-white font-extrabold text-sm rounded-2xl shadow-lg shadow-green-100/15 transition-all flex items-center justify-center gap-2">
                        <span x-show="!isSaving" class="flex items-center gap-2">
                            Simpan Pengaturan Operasional
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                        <span x-show="isSaving" class="flex items-center gap-2" x-cloak>
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Menyimpan...
                        </span>
                    </button>

                    {{-- Hidden Notification Fields (synced via Alpine) --}}
                    <input type="hidden" name="notifikasi_aktif" :value="notifAktif ? 1 : 0">
                    <input type="hidden" name="notifikasi_pesanan" :value="notifAktif ? 1 : 0">
                    <input type="hidden" name="notifikasi_penjemputan" :value="notifAktif ? 1 : 0">
                </div>
            </form>
        </div>

        {{-- RIGHT COLUMN: Notifications + Support --}}
        <div class="lg:col-span-5 space-y-6">

            {{-- Kontrol Notifikasi Card --}}
            <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-[0_10px_30px_rgba(0,0,0,0.03)]">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-2xl bg-[#eefcf4] flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#1cb764]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-extrabold text-[#0a2e1f]">Kontrol Notifikasi</h3>
                </div>

                <div class="flex items-center justify-between p-4 bg-[#f7faf8] rounded-2xl border border-gray-50">
                    <div class="flex-1">
                        <p class="text-sm font-bold text-[#0a2e1f] mb-0.5">Aktifkan Notifikasi</p>
                        <p class="text-[11px] text-gray-400 font-medium leading-relaxed">Terima semua notifikasi pesanan dan operasional.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer ml-4 shrink-0">
                        <input type="checkbox" x-model="notifAktif" class="sr-only peer">
                        <div class="w-12 h-7 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all after:shadow-sm peer-checked:bg-[#1cb764]"></div>
                    </label>
                </div>
            </div>

            {{-- Pusat Bantuan Card (Orange Gradient) --}}
            <div class="rounded-3xl p-8 border border-orange-100 shadow-[0_10px_30px_rgba(0,0,0,0.03)] relative overflow-hidden"
                 style="background: linear-gradient(135deg, #fb923c 0%, #f97316 50%, #ea580c 100%);">
                {{-- Decorative circles --}}
                <div class="absolute -top-8 -right-8 w-32 h-32 rounded-full bg-white/10"></div>
                <div class="absolute -bottom-6 -left-6 w-24 h-24 rounded-full bg-white/5"></div>

                <div class="relative z-10 space-y-5">
                    {{-- Icon --}}
                    <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>

                    <div>
                        <h3 class="text-2xl font-black text-white leading-tight mb-2">Pusat Bantuan</h3>
                        <p class="text-sm text-white/85 font-medium leading-relaxed">
                            Tim support kami tersedia 24/7 untuk membantu tantangan operasional Anda.
                        </p>
                    </div>

                    <a href="{{ route('unit.chat') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-white hover:bg-gray-50 text-orange-600 font-extrabold text-sm rounded-2xl shadow-lg transition-all hover:scale-[1.02] active:scale-[0.98]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        Hubungi Support
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ================================================== --}}
    {{-- BOTTOM SECTION: Full Width Cards --}}
    {{-- ================================================== --}}

    {{-- Informasi Identitas Bisnis --}}
    <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-[0_10px_30px_rgba(0,0,0,0.03)]">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-10 h-10 rounded-2xl bg-[#eefcf4] flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#1cb764]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-lg font-extrabold text-[#0a2e1f]">Informasi Identitas Bisnis</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            {{-- Nama Entitas --}}
            <div>
                <label class="text-[10px] font-extrabold text-[#7c9a8d] uppercase tracking-wider block mb-2">Nama Entitas</label>
                <div class="px-4 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl text-sm {{ $unitBisnis->nama_usaha ? 'font-bold text-[#0a2e1f]' : 'text-gray-400 italic' }}">
                    {{ $unitBisnis->nama_usaha ?: 'Belum diisi' }}
                </div>
            </div>

            {{-- Email Bisnis --}}
            <div>
                <label class="text-[10px] font-extrabold text-[#7c9a8d] uppercase tracking-wider block mb-2">Email Bisnis</label>
                <div class="px-4 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl text-sm break-all {{ $user->email ? 'font-bold text-[#0a2e1f]' : 'text-gray-400 italic' }}">
                    {{ $user->email ?: 'Belum diisi' }}
                </div>
            </div>

            {{-- Nomor Hotline --}}
            <div>
                <label class="text-[10px] font-extrabold text-[#7c9a8d] uppercase tracking-wider block mb-2">Nomor Hotline</label>
                <div class="px-4 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl text-sm {{ $user->no_hp ? 'font-bold text-[#0a2e1f]' : 'text-gray-400 italic' }}">
                    {{ $user->no_hp ?: 'Belum diisi' }}
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <a href="{{ route('unit.profil') }}" class="flex items-center gap-1.5 text-sm font-bold text-[#1cb764] hover:text-[#16a34a] transition-all">
                Perbarui Profil Bisnis
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>

    {{-- Keamanan Akun --}}
    <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-[0_10px_30px_rgba(0,0,0,0.03)]">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-red-50 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.952 11.952 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-extrabold text-[#0a2e1f]">Keamanan Akun</h3>
                    <p class="text-xs text-gray-400 font-medium mt-0.5">Terakhir diganti {{ $passwordAgo }}</p>
                </div>
            </div>

            <a href="{{ route('unit.profil', ['changePassword' => 'true']) }}"
                class="px-5 py-2.5 bg-white border-2 border-red-200 text-red-500 hover:bg-red-50 hover:border-red-300 font-bold text-sm rounded-2xl transition-all">
                Ganti Kata Sandi
            </a>
        </div>
    </div>
</div>

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

    /* Custom Range Slider */
    .radius-slider {
        -webkit-appearance: none;
        appearance: none;
        height: 8px;
        border-radius: 999px;
        outline: none;
    }
    .radius-slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #ffffff;
        border: 3px solid #1cb764;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(28, 183, 100, 0.3);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .radius-slider::-webkit-slider-thumb:hover {
        transform: scale(1.15);
        box-shadow: 0 4px 12px rgba(28, 183, 100, 0.4);
    }
    .radius-slider::-moz-range-thumb {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #ffffff;
        border: 3px solid #1cb764;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(28, 183, 100, 0.3);
    }
</style>
@endsection
