@extends('layouts.unit_bisnis')

@section('title', 'Pesanan')

@section('content')
<div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-50 min-h-[60vh] flex flex-col items-center justify-center text-center">
    <div class="bg-[#eefcf4] w-20 h-20 rounded-full flex items-center justify-center text-[#1cb764] mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
        </svg>
    </div>
    <h2 class="text-2xl font-extrabold text-gray-800 mb-2">Pesanan Masuk</h2>
    <p class="text-gray-500 max-w-md">Ini adalah halaman Pesanan. Nantinya daftar reservasi/pesanan yang masuk dari pengguna akan ditampilkan di sini.</p>
</div>
@endsection
