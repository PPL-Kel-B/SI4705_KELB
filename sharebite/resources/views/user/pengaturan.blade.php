@extends('layouts.user')

@section('title', 'Pengaturan')

@section('content')
<div class="max-w-7xl mx-auto px-4" x-data="{ 
    notifDonasi: true, 
    notifEmail: false,
    showAccountModal: false 
}">
    <!-- Header Section -->
    <div class="mb-6">
        <h1 class="text-4xl font-black text-[#1cb764] tracking-tight">Pengaturan</h1>
        <p class="text-gray-500 font-medium mt-2 text-lg">Kelola preferensi akun dan aplikasi Anda untuk pengalaman berbagi terbaik.</p>
    </div>

    @if(session('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition.opacity
            class="fixed top-6 right-6 bg-[#1cb764] text-white px-8 py-4 rounded-3xl shadow-2xl flex items-center gap-4 z-[100] font-bold border-4 border-white">
            <div class="bg-white/20 p-2 rounded-full">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
            </div>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        
        <!-- KOLOM KIRI: Informasi Akun & Kebijakan -->
        <div class="lg:col-span-7 space-y-10">
            
            <!-- Box 1: Informasi Akun -->
            <div class="bg-white rounded-[3rem] p-12 shadow-[0_20px_50px_rgba(0,0,0,0.02)] border border-gray-50 relative overflow-hidden">
                <div class="flex items-center gap-6 mb-12">
                    <div class="w-16 h-16 bg-[#eefcf4] text-[#1cb764] rounded-[1.5rem] flex items-center justify-center shadow-inner">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <h2 class="text-3xl font-black text-[#0a2e1f]">Informasi Akun</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                    <div class="space-y-3">
                        <label class="text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Nama Lengkap</label>
                        <div class="bg-gray-100 px-8 py-5 rounded-[1.5rem] text-[#111] font-bold text-lg shadow-inner">
                            {{ $user->name }}
                        </div>
                    </div>
                    <div class="space-y-3">
                        <label class="text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Email</label>
                        <div class="bg-gray-100 px-8 py-5 rounded-[1.5rem] text-[#111] font-bold text-lg shadow-inner">
                            {{ $user->email }}
                        </div>
                    </div>
                </div>

                <div class="mt-8 border-t border-gray-100 pt-8">
                    <a href="{{ route('user.profile.edit', ['source' => 'pengaturan']) }}" 
                       class="w-full flex items-center justify-center gap-3 bg-[#f8faf9] hover:bg-[#eefcf4] text-[#1cb764] px-8 py-4 rounded-2xl font-black transition-all group border border-transparent hover:border-[#1cb764]/20 shadow-sm hover:shadow-md">
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                        </svg>
                        Edit Profile
                    </a>
                </div>
            </div>

            <!-- Box 2: Kebijakan & Peraturan -->
            <div class="bg-white rounded-[3rem] p-12 shadow-[0_20px_50px_rgba(0,0,0,0.02)] border border-gray-50">
                <div class="flex items-center gap-6 mb-12">
                    <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-[1.5rem] flex items-center justify-center shadow-inner">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <h2 class="text-3xl font-black text-[#0a2e1f]">Kebijakan & Peraturan</h2>
                </div>

                <div class="space-y-6">
                    <a href="{{ route('user.pengaturan.policy', 'keamanan-pangan') }}" class="flex items-center gap-6 p-6 bg-white border-2 border-gray-50 rounded-3xl hover:border-[#1cb764] hover:bg-[#eefcf4]/10 transition-all group">
                        <div class="w-14 h-14 bg-green-50 text-green-500 rounded-2xl flex items-center justify-center group-hover:bg-[#1cb764] group-hover:text-white transition-colors">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-black text-[#0a2e1f] text-lg">Standar Keamanan Pangan</h4>
                            <p class="text-sm text-gray-400 font-bold">Panduan menjaga kualitas makanan</p>
                        </div>
                        <svg class="w-6 h-6 text-gray-200 transition-transform group-hover:translate-x-2 group-hover:text-[#1cb764]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                    </a>

                    <a href="{{ route('user.pengaturan.policy', 'etika-berbagi') }}" class="flex items-center gap-6 p-6 bg-white border-2 border-gray-50 rounded-3xl hover:border-purple-500 hover:bg-purple-50 transition-all group">
                        <div class="w-14 h-14 bg-purple-50 text-purple-500 rounded-2xl flex items-center justify-center group-hover:bg-purple-500 group-hover:text-white transition-colors">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-black text-[#0a2e1f] text-lg">Etika Berbagi</h4>
                            <p class="text-sm text-gray-400 font-bold">Cara berinteraksi yang baik di komunitas</p>
                        </div>
                        <svg class="w-6 h-6 text-gray-200 transition-transform group-hover:translate-x-2 group-hover:text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                    </a>

                    <a href="{{ route('user.pengaturan.policy', 'ketentuan-layanan') }}" class="flex items-center gap-6 p-6 bg-white border-2 border-gray-50 rounded-3xl hover:border-orange-500 hover:bg-orange-50 transition-all group">
                        <div class="w-14 h-14 bg-orange-50 text-orange-500 rounded-2xl flex items-center justify-center group-hover:bg-orange-500 group-hover:text-white transition-colors">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-black text-[#0a2e1f] text-lg">Ketentuan Layanan</h4>
                            <p class="text-sm text-gray-400 font-bold">Aspek hukum dan tanggung jawab</p>
                        </div>
                        <svg class="w-6 h-6 text-gray-200 transition-transform group-hover:translate-x-2 group-hover:text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: Notifikasi, Perangkat & Bantuan -->
        <div class="lg:col-span-5 space-y-10">
            
            <!-- Notifikasi -->
            <div class="bg-white rounded-[3rem] p-12 shadow-[0_20px_50px_rgba(0,0,0,0.02)] border border-gray-50">
                <div class="flex items-center gap-6 mb-12">
                    <div class="w-16 h-16 bg-[#eefcf4] text-[#1cb764] rounded-[1.5rem] flex items-center justify-center shadow-inner">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    </div>
                    <h2 class="text-3xl font-black text-[#0a2e1f]">Notifikasi</h2>
                </div>

                <div class="space-y-10">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-black text-[#0a2e1f] text-lg">Donasi Baru</h4>
                            <p class="text-sm text-gray-400 font-bold">Makanan tersedia di dekat Anda</p>
                        </div>
                        <button @click="notifDonasi = !notifDonasi" 
                                :class="notifDonasi ? 'bg-[#1cb764]' : 'bg-gray-200'"
                                class="relative inline-flex h-8 w-14 items-center rounded-full transition-colors duration-500 focus:outline-none shadow-sm">
                            <span :class="notifDonasi ? 'translate-x-7' : 'translate-x-1'"
                                  class="inline-block h-6 w-6 transform rounded-full bg-white transition-transform duration-500 shadow-md"></span>
                        </button>
                    </div>

                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-black text-[#0a2e1f] text-lg">Email Mingguan</h4>
                            <p class="text-sm text-gray-400 font-bold">Laporan dampak sosial Anda</p>
                        </div>
                        <button @click="notifEmail = !notifEmail" 
                                :class="notifEmail ? 'bg-[#1cb764]' : 'bg-gray-200'"
                                class="relative inline-flex h-8 w-14 items-center rounded-full transition-colors duration-500 focus:outline-none shadow-sm">
                            <span :class="notifEmail ? 'translate-x-7' : 'translate-x-1'"
                                  class="inline-block h-6 w-6 transform rounded-full bg-white transition-transform duration-500 shadow-md"></span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Manajemen Perangkat (FIXED LIST) -->
            <div class="bg-white rounded-[3rem] p-12 shadow-[0_20px_50px_rgba(0,0,0,0.02)] border border-gray-50">
                <div class="flex items-center gap-6 mb-12">
                    <div class="w-16 h-16 bg-indigo-50 text-indigo-500 rounded-[1.5rem] flex items-center justify-center shadow-inner">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    </div>
                    <h2 class="text-3xl font-black text-[#0a2e1f]">Manajemen Perangkat</h2>
                </div>

                <div class="space-y-8">
                    @forelse($sessions as $session)
                    <div class="flex items-center gap-6 group">
                        <div class="w-14 h-14 {{ $session->is_current_device ? 'bg-[#eefcf4] text-[#1cb764]' : 'bg-gray-50 text-gray-300' }} rounded-2xl flex items-center justify-center shadow-sm transition-all group-hover:scale-105">
                            @if($session->icon === 'mobile')
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            @else
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-3">
                                <h4 class="font-black text-[#111] text-lg">{{ $session->device_name }}</h4>
                                @if($session->is_current_device)
                                    <span class="text-[9px] bg-[#eefcf4] text-[#1cb764] px-2 py-0.5 rounded-full font-black uppercase border border-[#1cb764]/20 shadow-sm">Perangkat Ini</span>
                                @endif
                            </div>
                            <p class="text-[11px] text-gray-400 font-bold uppercase tracking-widest">{{ $session->location }} • {{ $session->is_current_device ? 'Aktif Sekarang' : $session->last_active }}</p>
                        </div>
                        @if(!$session->is_current_device)
                        <form action="{{ route('user.pengaturan.logout_session', $session->id) }}" method="POST" onsubmit="return confirm('Keluarkan perangkat ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs font-black text-red-500 hover:text-red-700 transition-colors uppercase tracking-widest">Log Out</button>
                        </form>
                        @endif
                    </div>
                    @empty
                    <p class="text-gray-400 font-bold text-center py-4 italic">Belum ada riwayat perangkat.</p>
                    @endforelse
                </div>
            </div>

            <!-- Box 4: Butuh Bantuan (Gradient) -->
            <div class="mt-auto bg-[#f89b29] rounded-[2.5rem] p-10 shadow-xl relative overflow-hidden flex flex-col justify-center">
                <!-- Efek cahaya belakang -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -mr-20 -mt-20 pointer-events-none"></div>
                
                <div class="relative z-10">
                    <!-- Ikon & Judul -->
                    <div class="flex items-center gap-6 mb-8">
                        <div class="w-20 h-20 bg-white/20 rounded-[1.5rem] flex items-center justify-center backdrop-blur-md border border-white/30 shadow-sm flex-shrink-0">
                            <!-- Custom SVG Ikon CS Headset persis seperti gambar -->
                            <svg viewBox="0 0 100 100" class="w-12 h-12 text-white" fill="currentColor">
                                <!-- Headband -->
                                <path d="M 50 20 C 30 20 16 34 16 54 h 10 C 26 40 36 30 50 30 C 64 30 74 40 74 54 h 10 C 84 34 70 20 50 20 z" />
                                <!-- Earpieces -->
                                <rect x="12" y="50" width="14" height="24" rx="6" />
                                <rect x="74" y="50" width="14" height="24" rx="6" />
                                <!-- Eyes -->
                                <circle cx="36" cy="54" r="4.5" />
                                <circle cx="64" cy="54" r="4.5" />
                                <!-- Bangs / Poni -->
                                <path d="M 46 31 C 35 34 26 52 26 52 C 35 44 43 41 46 41 Z" />
                                <path d="M 54 31 C 65 34 74 52 74 52 C 65 44 57 41 54 41 Z" />
                                <!-- Mic Arm -->
                                <path d="M 81 62 v 14 a 8 8 0 0 1 -8 8 H 40 v -9 h 33 v -13 z" />
                            </svg>
                        </div>
                        <h3 class="text-[32px] font-black text-white tracking-tight leading-tight">Butuh Bantuan?</h3>
                    </div>
                    
                    <!-- Tombol Hubungi CS -->
                    <a href="#" class="w-full bg-white text-[#f89b29] py-5 rounded-full font-black text-lg flex items-center justify-center gap-3 shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        Hubungi CS
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>
            </div>  
        </div>
    </div>

    <!-- MODAL EDIT INFORMASI AKUN (POPUPS ELEGAN) -->
    <div x-show="showAccountModal" 
         class="fixed inset-0 z-[100] flex items-center justify-center p-6" 
         x-cloak
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div class="absolute inset-0 bg-[#0a2e1f]/80 backdrop-blur-md" @click="showAccountModal = false"></div>
        
        <div class="relative bg-white rounded-[3.5rem] p-14 max-w-xl w-full shadow-2xl transform transition-all"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90 translate-y-10"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            <div class="flex justify-between items-center mb-12">
                <div>
                    <h3 class="text-4xl font-black text-[#0a2e1f] tracking-tight">Edit Informasi</h3>
                    <p class="text-gray-400 font-bold text-sm mt-1 uppercase tracking-widest">Perbarui data login Anda</p>
                </div>
                <button @click="showAccountModal = false" class="p-3 bg-gray-50 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-2xl transition-all">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>
    </div>

</div>

<style>
    /* Premium Font & Smooth Scrolling */
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
    [x-cloak] { display: none !important; }
</style>
@endsection