@extends('layouts.user')

@section('title', 'Riwayat')

@section('content')
<div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-50 min-h-[60vh] flex flex-col items-center justify-center text-center">
    <div class="bg-[#eefcf4] w-20 h-20 rounded-full flex items-center justify-center text-[#1cb764] mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    </div>
    <h2 class="text-2xl font-extrabold text-gray-800 mb-2">Riwayat Donasi</h2>
    <p class="text-gray-500 max-w-md">Ini adalah halaman riwayat. Nantinya daftar donasi yang telah Anda ambil dan distribusikan akan ditampilkan di sini.</p>
</div>
@endsection
