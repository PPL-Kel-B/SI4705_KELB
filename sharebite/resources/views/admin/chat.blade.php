@extends('layouts.admin')

@section('title', 'Chat')

@section('content')
<div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-50 min-h-[60vh] flex flex-col items-center justify-center text-center">
    <div class="bg-[#eefcf4] w-20 h-20 rounded-full flex items-center justify-center text-[#1cb764] mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
    </div>
    <h2 class="text-2xl font-extrabold text-gray-800 mb-2">Pusat Pesan (Chat)</h2>
    <p class="text-gray-500 max-w-md">Ini adalah halaman Chat. Nantinya Anda dapat berkomunikasi dengan berbagai pihak (Komunitas, Unit Bisnis) di sini untuk membantu menyelesaikan masalah teknis atau pertanyaan.</p>
</div>
@endsection
