@extends('layouts.user')

@php
    $hideSearch = true;
@endphp

@section('title', 'Edit Profile')

@section('content')
    <div class="max-w-6xl mx-auto space-y-4" x-data="{ showSuccess: {{ session('success') ? 'true' : 'false' }} }">

        {{-- Success Modal --}}
        <div x-show="showSuccess" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"
                @click="window.location.href='{{ session('profile_edit_back_url', route('user.profile')) }}'"></div>
            <div class="relative bg-white rounded-[2.5rem] p-10 shadow-2xl max-w-sm w-full text-center z-10 transform transition-all"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100">
                <div
                    class="w-20 h-20 bg-[#eefcf4] rounded-full flex items-center justify-center text-[#1cb764] mx-auto mb-6 shadow-sm">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 mb-2">Berhasil!</h3>
                <p class="text-gray-500 font-medium mb-8">{{ session('success') }}</p>
                <a href="{{ session('profile_edit_back_url', route('user.profile')) }}"
                    class="block w-full py-4 bg-[#1cb764] text-white font-black rounded-2xl shadow-lg shadow-[#1cb764]/20 hover:bg-[#19a55a] transition-all">Oke,
                    Mantap!</a>
            </div>
        </div>

        <!-- Header & Back Button -->
        <div class="flex items-center gap-4">
            <a href="{{ session('profile_edit_back_url', route('user.profile')) }}"
                class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-400 hover:text-[#1cb764] shadow-sm border border-gray-100 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-2xl font-black text-[#0a2e1f]">Edit Profile</h1>
        </div>

        <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            {{-- Hero Header Card --}}
            <div
                class="bg-white rounded-[2.5rem] p-8 lg:p-10 shadow-sm border border-gray-50 flex flex-col md:flex-row items-center gap-10 relative overflow-hidden">
                <div class="absolute -top-20 -right-20 w-64 h-64 bg-[#eefcf4] rounded-full blur-3xl opacity-40"></div>

                {{-- Avatar Section --}}
                <div class="relative group">
                    <div class="w-40 h-40 rounded-[2.5rem] overflow-hidden border-4 border-white shadow-xl bg-gray-50">
                        <img id="avatar-preview"
                            src="{{ $user->foto_profil ? asset('storage/' . $user->foto_profil) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=f7b055&color=fff&size=200' }}"
                            class="w-full h-full object-cover">
                    </div>
                    <label for="foto_profil"
                        class="absolute bottom-2 right-2 w-10 h-10 bg-[#0a2e1f] text-white rounded-2xl flex items-center justify-center cursor-pointer hover:bg-[#1cb764] transition-all shadow-lg border-4 border-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <input type="file" name="foto_profil" id="foto_profil" class="hidden" accept="image/*"
                            onchange="previewImage(event)">
                    </label>
                </div>

                <div class="flex-1 text-center md:text-left space-y-4">
                    <h2 class="text-4xl lg:text-5xl font-black text-gray-900 tracking-tight">{{ $user->name }}</h2>
                    <p class="text-gray-400 font-bold uppercase tracking-[0.2em] text-xs">Bergabung sejak
                        {{ $user->created_at->format('F Y') }}</p>
                    <button type="button" onclick="document.getElementById('foto_profil').click()"
                        class="px-6 py-2.5 border-2 border-[#1cb764] text-[#1cb764] font-black rounded-xl text-xs hover:bg-[#1cb764] hover:text-white transition-all uppercase tracking-widest">
                        Ganti Foto Profil
                    </button>
                </div>
            </div>

            {{-- Form Section --}}
            <div class="grid grid-cols-1 gap-8">
                {{-- Informasi Pribadi --}}
                <div class="bg-white rounded-[2.5rem] p-8 lg:p-10 shadow-sm border border-gray-50">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-10 h-10 bg-[#eefcf4] rounded-xl flex items-center justify-center text-[#1cb764]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-black text-[#0a2e1f]">Informasi Pribadi</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest ml-1">Nama
                                Komunitas</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                class="w-full bg-[#f4f7f5] px-6 py-4 rounded-2xl border-2 border-transparent focus:border-[#1cb764] focus:bg-white outline-none font-bold text-gray-700 transition-all">
                        </div>
                        <div class="space-y-3">
                            <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest ml-1">Email
                                (Non-aktif)</label>
                            <input type="email" value="{{ $user->email }}"
                                class="w-full bg-[#f4f7f5] px-6 py-4 rounded-2xl border-2 border-transparent opacity-60 cursor-not-allowed font-bold text-gray-400 outline-none"
                                readonly>
                        </div>
                        <div class="md:col-span-2 space-y-3">
                            <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest ml-1">
                                Nomor Telepon
                            </label>
                            <input type="text" name="no_hp" id="phone_input" value="{{ old('no_hp', $user->no_hp) }}"
                                onkeypress="return isNumberKey(event)" inputmode="numeric" maxlength="15"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                class="w-full bg-[#f4f7f5] px-6 py-4 rounded-2xl border-2 border-transparent focus:border-[#1cb764] focus:bg-white outline-none font-bold text-gray-700 transition-all"
                                placeholder="08xxxxxxxxxx">
                        </div>
                        <div class="md:col-span-2 space-y-3">
                            <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest ml-1">Alamat
                                Utama</label>
                            <textarea name="alamat" rows="3"
                                class="w-full bg-[#f4f7f5] px-6 py-4 rounded-2xl border-2 border-transparent focus:border-[#1cb764] focus:bg-white outline-none font-bold text-gray-700 transition-all resize-none"
                                placeholder="Masukkan alamat lengkap Anda...">{{ old('alamat', $user->alamat) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Keamanan (Disabled for now) --}}
                <div class="bg-white rounded-[2.5rem] p-8 lg:p-10 shadow-sm border border-gray-50">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-10 h-10 bg-[#eefcf4] rounded-xl flex items-center justify-center text-[#1cb764]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-black text-[#0a2e1f]">Keamanan</h3>
                    </div>

                    <a href="#"
                        class="flex items-center justify-between bg-[#f4f7f5] hover:bg-[#eefcf4] p-6 rounded-3xl transition-all group border border-transparent hover:border-[#1cb764]/20">
                        <div class="flex items-center gap-5">
                            <div
                                class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-[#1cb764] shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-extrabold text-[#0a2e1f]">Ubah Kata Sandi</h4>
                                <p class="text-xs text-gray-400 font-bold uppercase mt-0.5 tracking-tighter">
                                    @if ($user->password_updated_at)
                                        Terakhir diubah {{ $user->password_updated_at->diffForHumans() }}
                                    @else
                                        Belum pernah diubah
                                    @endif
                                </p>
                            </div>
                        </div>
                        <svg class="w-6 h-6 text-gray-300 group-hover:text-[#1cb764] transition" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </a>
                </div>

            </div>

            <div class="pt-4">
                <button type="submit"
                    class="w-full py-5 bg-[#1cb764] text-white font-black rounded-3xl shadow-xl shadow-[#1cb764]/20 hover:bg-[#19a55a] hover:-translate-y-1 transition-all active:scale-[0.98] text-lg tracking-wide">
                    Simpan Perubahan Profil
                </button>
            </div>
        </form>
    </div>

@section('scripts')
    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatar-preview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection
@endsection
