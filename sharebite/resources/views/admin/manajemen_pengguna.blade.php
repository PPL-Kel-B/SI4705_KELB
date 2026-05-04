@extends('layouts.admin')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-50 min-h-[60vh] flex flex-col items-center justify-center text-center">
    <div class="bg-[#eefcf4] w-20 h-20 rounded-full flex items-center justify-center text-[#1cb764] mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
    </div>
    <h2 class="text-2xl font-extrabold text-gray-800 mb-2">Manajemen Pengguna</h2>
    <p class="text-gray-500 max-w-md">Ini adalah halaman Manajemen Pengguna. Nantinya daftar akun Unit Bisnis, Komunitas, dan Individu akan dikelola di sini beserta status verifikasinya.</p>
</div>
@endsection
