@extends('layouts.unit_bisnis')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">

    <!-- Coming Soon Placeholder -->
    <div class="flex flex-col items-center justify-center min-h-[60vh] bg-white rounded-3xl shadow-sm border border-gray-50 p-10 text-center">
        <div class="w-24 h-24 bg-[#eefcf4] rounded-full flex items-center justify-center text-[#1cb764] mb-6">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg>
        </div>
        <h2 class="text-3xl font-black text-gray-800 mb-3">Halaman Sedang Dikembangkan</h2>
        <p class="text-gray-500 font-medium max-w-md mx-auto">
            Fitur Dashboard ini sedang dalam tahap pengembangan dan akan segera hadir. Terima kasih atas kesabaran Anda!
        </p>
    </div>

</div>
@endsection
