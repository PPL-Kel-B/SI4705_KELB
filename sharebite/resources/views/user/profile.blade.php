@extends('layouts.user')

@section('title', 'Profile Saya')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <h1 class="text-3xl font-extrabold text-[#0a2e1f] mb-6">Profile Saya</h1>

    {{-- Profile Header Card --}}
    <div class="bg-white rounded-[2rem] p-6 lg:p-8 shadow-sm border border-gray-50 flex flex-col md:flex-row items-center gap-8 relative overflow-hidden">
        {{-- Decorative Background Glow --}}
        <div class="absolute -top-24 -left-24 w-64 h-64 bg-[#eefcf4] rounded-full blur-3xl opacity-60 pointer-events-none"></div>
        
        <div class="relative w-32 h-32 md:w-40 md:h-40 shrink-0 bg-[#f4f7f5] rounded-3xl p-2 border-4 border-white shadow-md z-10 flex items-center justify-center">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($profile['name']) }}&background=f7b055&color=fff&size=150" alt="{{ $profile['name'] }}" class="w-full h-full rounded-2xl object-cover">
        </div>
        
        <div class="flex-1 text-center md:text-left z-10">
            <h2 class="text-3xl lg:text-4xl font-black text-gray-900 tracking-tight mb-2">{{ $profile['name'] }}</h2>
            <div class="inline-flex items-center gap-2 bg-gray-50 px-4 py-2 rounded-xl text-sm font-medium text-gray-500 border border-gray-100">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Terdaftar {{ $profile['member_since'] }}
            </div>
        </div>

        <div class="z-10 w-full md:w-auto">
            <a href="#" class="w-full md:w-auto inline-flex justify-center items-center gap-2 bg-[#1cb764] hover:bg-[#19a55a] text-white px-6 py-3.5 rounded-full font-bold transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                </svg>
                Edit Profile
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Kontribusi Berbagi --}}
        <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-50 relative overflow-hidden group">
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-gradient-to-tl from-[#eefcf4] to-transparent rounded-full blur-2xl opacity-50 group-hover:opacity-100 transition duration-500"></div>
            <div class="w-12 h-12 bg-[#eefcf4] rounded-2xl flex items-center justify-center text-[#1cb764] mb-6">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                </svg>
            </div>
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1">Kontribusi Berbagi</p>
            <div class="flex items-baseline gap-2">
                <span class="text-5xl lg:text-6xl font-black text-[#1cb764] tracking-tighter">{{ number_format($stats['contribution_items'], 0, ',', '.') }}</span>
                <span class="text-sm font-bold text-gray-700">Item</span>
            </div>
        </div>

        {{-- Dampak Ekologis --}}
        <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-50 relative overflow-hidden group">
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-gradient-to-tl from-[#fdf6ec] to-transparent rounded-full blur-2xl opacity-50 group-hover:opacity-100 transition duration-500"></div>
            <div class="w-12 h-12 bg-[#fdf6ec] rounded-2xl flex items-center justify-center text-[#f7b055] mb-6">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.5 3C13.5 3 10.5 5 8.5 8c-2 3-2.5 6-1.5 8H6c-2.2 0-4 1.8-4 4s1.8 4 4 4h4c2.2 0 4-1.8 4-4v-1.5c3-2 5-5 5-8C19 5.5 18 4 17.5 3zM12 20c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3-6.5c-2.5 0-4.5-2-4.5-4.5S12.5 4.5 15 4.5 19.5 6.5 19.5 9s-2 4.5-4.5 4.5z"/>
                </svg>
            </div>
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1">Dampak Ekologis</p>
            <div class="flex items-baseline gap-2">
                <span class="text-5xl lg:text-6xl font-black text-[#f7b055] tracking-tighter">{{ number_format($stats['eco_impact_kg'], 1, ',', '.') }}</span>
                <span class="text-sm font-bold text-gray-700">Kg Sampah</span>
            </div>
        </div>
    </div>

    {{-- Bottom Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Left Column (Info Pribadi & Lokasi) --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Informasi Pribadi --}}
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-50">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 bg-[#eefcf4] rounded-2xl flex items-center justify-center text-[#1cb764]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-extrabold text-gray-900">Informasi Pribadi</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2">Nama Lengkap</label>
                        <div class="bg-[#f8faf9] px-5 py-4 rounded-xl text-sm font-bold text-gray-800 border border-gray-100">
                            {{ $profile['name'] }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2">Alamat Email</label>
                        <div class="bg-[#f8faf9] px-5 py-4 rounded-xl text-sm font-bold text-gray-800 border border-gray-100">
                            {{ $profile['email'] }}
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2">Nomor Telepon</label>
                    <div class="bg-[#f8faf9] px-5 py-4 rounded-xl text-sm font-bold text-gray-800 border border-gray-100">
                        {{ $profile['phone'] }}
                    </div>
                </div>
            </div>

            {{-- Lokasi & Alamat Utama --}}
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-50">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-[#fdf6ec] rounded-2xl flex items-center justify-center text-[#f7b055]">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-extrabold text-gray-900">Lokasi & Alamat Utama</h3>
                    </div>
                    <button class="bg-[#fdf6ec] text-[#f7b055] hover:bg-[#faeedb] px-4 py-2 rounded-xl text-xs font-bold transition">Ubah Alamat</button>
                </div>

                <div class="flex gap-4 bg-[#f8faf9] p-5 rounded-2xl border border-gray-100 relative overflow-hidden">
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-[#f7b055]"></div>
                    <div class="text-[#f7b055] shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-gray-800 leading-relaxed">{{ $profile['address'] }}</p>
                </div>
            </div>

        </div>

        {{-- Right Column (Keamanan & Bantuan) --}}
        <div class="space-y-6">
            
            {{-- Keamanan --}}
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-50">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 bg-[#eefcf4] rounded-2xl flex items-center justify-center text-[#1cb764]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-extrabold text-gray-900">Keamanan</h3>
                </div>

                <div class="space-y-3">
                    <button class="w-full flex items-center justify-between bg-[#f8faf9] hover:bg-gray-50 px-5 py-4 rounded-2xl border border-gray-100 transition group">
                        <div class="flex items-center gap-3">
                            <div class="text-[#1cb764] bg-white p-2 rounded-lg shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-bold text-gray-800">{{ $security['change_password'] }}</span>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>

                    <button class="w-full flex items-center justify-between bg-[#f8faf9] hover:bg-gray-50 px-5 py-4 rounded-2xl border border-gray-100 transition group">
                        <div class="flex items-center gap-3">
                            <div class="text-[#f7b055] bg-white p-2 rounded-lg shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-bold text-gray-800">{{ $security['notifications'] }}</span>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Bantuan --}}
            <div class="bg-gradient-to-br from-[#f7b055] to-[#ed8b1a] rounded-[2rem] p-8 shadow-md relative overflow-hidden text-white">
                <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white rounded-full opacity-10"></div>
                <div class="absolute right-10 -bottom-10 w-40 h-40 bg-white rounded-full opacity-10"></div>
                
                <h3 class="text-xl font-extrabold mb-3 relative z-10">Butuh Bantuan?</h3>
                <p class="text-sm text-orange-50 font-medium leading-relaxed mb-8 relative z-10">Tim ShareBite siap mendampingi perjalanan berbagi Anda 24/7</p>
                
                <button class="w-full bg-white text-[#e97d09] hover:bg-orange-50 font-extrabold px-6 py-4 rounded-full transition shadow-sm relative z-10">
                    Hubungi CS ShareBite
                </button>
            </div>
            
        </div>
    </div>
</div>
@endsection
