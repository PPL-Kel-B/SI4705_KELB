@php
    $hideSearch = true; // Sembunyikan search bar di header
@endphp
@extends('layouts.user')

@section('title', 'Kumpulan Aktivitas')

@section('content')
<div class="space-y-6 animate-fade-in pb-12">
    <!-- Header Halaman -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <a href="{{ route('user.dashboard') }}" class="inline-flex items-center gap-2 text-xs font-bold text-gray-500 hover:text-[#1cb764] hover:border-[#1cb764] bg-white hover:bg-[#eefcf4] px-4 py-2.5 rounded-full border border-gray-100 shadow-sm transition-all duration-300 w-fit mb-4">
                <svg class="w-4 h-4 text-gray-400 transition-colors" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
                Kembali ke Dashboard
            </a>
            <h1 class="text-2xl font-black text-gray-800">Semua Aktivitas</h1>
            <p class="text-xs text-gray-400 font-medium">Kumpulan seluruh jejak kontribusi dan aktivitas Anda</p>
        </div>
    </div>

    <!-- Container Utama -->
    <div class="bg-white rounded-[2rem] p-6 sm:p-8 shadow-sm border border-gray-50">
        @if($activities->isEmpty())
            <div class="text-center py-12 text-gray-400 space-y-3">
                <div class="text-5xl">📋</div>
                <p class="font-bold text-sm">Belum ada aktivitas yang terekam.</p>
            </div>
        @else
            <!-- Timeline List -->
            <div class="space-y-8 relative before:absolute before:left-4 before:top-2 before:bottom-2 before:w-[2px] before:bg-gray-100">
                @foreach($activities as $act)
                    @php
                        $bgColor = 'bg-[#f0f4ff] text-[#4f46e5]';
                        if($act->tipe === 'profil') $bgColor = 'bg-[#fcf3e6] text-[#e09121]';
                        if($act->tipe === 'pengaturan') $bgColor = 'bg-[#f2fcf6] text-[#1cb764]';
                        if($act->tipe === 'ulasan') $bgColor = 'bg-pink-50 text-pink-500';
                    @endphp
                    <div class="flex gap-6 relative">
                        <div class="w-8 h-8 rounded-full shrink-0 flex items-center justify-center font-bold text-sm z-10 {{ $bgColor }} border-4 border-white shadow-sm">
                            ●
                        </div>
                        <div class="space-y-1 bg-gray-50/50 p-4 rounded-2xl border border-gray-50/80 flex-1 hover:bg-gray-50 transition duration-200">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                                <h4 class="text-sm font-extrabold text-gray-800">{{ $act->judul }}</h4>
                                <span class="text-[10px] text-gray-400 font-bold">
                                    {{ $act->created_at->isoFormat('D MMMM YYYY, HH:mm') }} WIB
                                </span>
                            </div>
                            <p class="text-xs text-gray-600 leading-relaxed">{{ $act->deskripsi }}</p>
                            <div class="text-[9px] text-[#1cb764] font-black uppercase tracking-wider mt-2">
                                {{ $act->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pt-6 mt-6 border-t border-gray-50">
                {{ $activities->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
