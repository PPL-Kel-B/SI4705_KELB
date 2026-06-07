@extends('layouts.unit_bisnis')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@php
    $reqCode = request('code', '');
    $reqCode = str_replace('SB-', '', strtoupper($reqCode));
    $parts = explode('-', $reqCode);
    $numPart = $parts[0] ?? '';
    $alphaPart = $parts[1] ?? '';
    
    $n0 = $numPart[0] ?? '';
    $n1 = $numPart[1] ?? '';
    $n2 = $numPart[2] ?? '';
    
    $a0 = $alphaPart[0] ?? '';
    $a1 = $alphaPart[1] ?? '';
    $a2 = $alphaPart[2] ?? '';
@endphp

    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Verifikasi Gagal',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#189347',
                    customClass: {
                        popup: 'rounded-[24px]',
                        confirmButton: 'rounded-[12px] px-6 py-2.5 font-bold'
                    }
                });
            });
        </script>
    @endif

    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('unit.pesanan.index') }}" class="w-10 h-10 rounded-full bg-[#E4F2E8] hover:bg-[#d4edd9] text-[#10703B] transition flex items-center justify-center shadow-none">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3.2" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h1 class="text-2xl font-black text-gray-800 tracking-tight">Verifikasi Code</h1>
    </div>

    <div class="bg-white p-10 rounded-[32px] shadow-sm border border-green-50 mb-10 text-center max-w-2xl mx-auto">
        <h2 class="text-[13px] font-extrabold text-[#189347] tracking-[0.2em] uppercase mb-8">INPUT 6-DIGIT KODE VERIFIKASI</h2>
        
        <form action="{{ route('unit.pesanan.verifikasi') }}" method="GET" id="form-verifikasi" class="flex flex-col items-center">
            <div class="flex items-center justify-center gap-2 mb-8">
                <span class="text-3xl font-black text-[#189347] mr-2">SB -</span>
                
                <input type="text" maxlength="1" value="{{ $n0 }}" class="code-box num-box w-14 h-16 text-3xl font-black text-center text-[#189347] bg-[#F0F7F2] border-2 border-transparent focus:border-[#189347] focus:ring-0 rounded-2xl transition outline-none">
                <input type="text" maxlength="1" value="{{ $n1 }}" class="code-box num-box w-14 h-16 text-3xl font-black text-center text-[#189347] bg-[#F0F7F2] border-2 border-transparent focus:border-[#189347] focus:ring-0 rounded-2xl transition outline-none">
                <input type="text" maxlength="1" value="{{ $n2 }}" class="code-box num-box w-14 h-16 text-3xl font-black text-center text-[#189347] bg-[#F0F7F2] border-2 border-transparent focus:border-[#189347] focus:ring-0 rounded-2xl transition outline-none">
                
                <span class="text-3xl font-black text-gray-300 mx-1">-</span>
                
                <input type="text" maxlength="1" value="{{ $a0 }}" class="code-box alpha-box w-14 h-16 text-3xl font-black text-center text-[#189347] bg-[#F0F7F2] border-2 border-transparent focus:border-[#189347] focus:ring-0 rounded-2xl transition outline-none uppercase">
                <input type="text" maxlength="1" value="{{ $a1 }}" class="code-box alpha-box w-14 h-16 text-3xl font-black text-center text-[#189347] bg-[#F0F7F2] border-2 border-transparent focus:border-[#189347] focus:ring-0 rounded-2xl transition outline-none uppercase">
                <input type="text" maxlength="1" value="{{ $a2 }}" class="code-box alpha-box w-14 h-16 text-3xl font-black text-center text-[#189347] bg-[#F0F7F2] border-2 border-transparent focus:border-[#189347] focus:ring-0 rounded-2xl transition outline-none uppercase">
                
                <input type="hidden" name="code" id="hidden-code">
            </div>

            <button type="button" onclick="submitCode()" class="text-[#189347] font-bold text-[15px] flex items-center gap-2 hover:underline">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg> Cari makanan
            </button>
        </form>
    </div>

    @if($pesanan)
    <div class="w-full">
        <div class="flex items-center gap-2 mb-4">
            <div class="w-2.5 h-2.5 bg-[#189347] rounded-full"></div>
            <h3 class="text-lg font-extrabold text-gray-800">Pesanan Ditemukan</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
            <div class="md:col-span-4 bg-white p-6 rounded-[24px] shadow-sm flex flex-col justify-between">
                <div class="flex items-center gap-4 mb-6">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($pesanan->user->name) }}&background=E4F2E8&color=189347" class="w-16 h-16 rounded-xl object-cover">
                    <h4 class="text-lg font-extrabold text-[#189347]">{{ $pesanan->user->name }}</h4>
                </div>
                
                <div class="space-y-4 mb-8">
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="text-[12px] text-gray-400 font-medium">ID Volunteer</span>
                        <span class="text-[12px] font-bold text-gray-800">{{ $volId }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="text-[12px] text-gray-400 font-medium">Total Donasi</span>
                        <span class="text-[12px] font-bold text-gray-800">{{ $totalPesananBuyer }} Donasi</span>
                    </div>
                </div>

                <form action="{{ route('unit.pesanan.update', $pesanan->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="w-full bg-gradient-to-r from-[#007037] to-[#1CB764] hover:from-[#005c2d] hover:to-[#179f57] text-white py-4 rounded-[20px] font-bold text-[15px] flex items-center justify-center gap-3 transition shadow-sm active:scale-[0.98] duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4" />
                            <circle cx="12" cy="12" r="9" />
                        </svg>
                        Verifikasi & Selesaikan Pesanan
                    </button>
                </form>
            </div>

            <div class="md:col-span-8 bg-white rounded-[24px] shadow-sm overflow-hidden flex flex-col">
                <img src="{{ asset('storage/' . ($pesanan->menuAktif->masterMakanan->foto ?? '')) }}" onerror="this.src='https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=800&q=80'" class="w-full h-56 sm:h-64 object-cover">
                <div class="p-6 flex-1 flex flex-col justify-center">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <h3 class="text-2xl font-extrabold text-[#189347] mb-1">
                                {{ $pesanan->jumlah_porsi }}x {{ $pesanan->menuAktif->masterMakanan->nama_makanan ?? 'Menu Makanan' }}
                            </h3>
                            <p class="text-xs text-gray-500">{{ $pesanan->menuAktif->masterMakanan->kategori ?? 'Makanan' }}</p>
                        </div>
                        <div class="text-left sm:text-right">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Nilai Redistribusi</p>
                            <p class="text-2xl font-black text-[#189347]">@if($pesanan->menuAktif->is_gratis) GRATIS @else Rp {{ number_format($pesanan->jumlah_porsi * $pesanan->menuAktif->harga_jual, 0, ',', '.') }} @endif</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

<script>
    const inputs = document.querySelectorAll('.code-box');
    inputs.forEach((input, index) => {
        // Automatically capitalize text inputs
        input.addEventListener('input', (e) => {
            if (input.classList.contains('alpha-box')) {
                input.value = input.value.toUpperCase();
            }
            if (e.target.value.length === 1 && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
        });
        
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && e.target.value === '' && index > 0) {
                inputs[index - 1].focus();
            }
        });
    });

    // Enforce digit constraints (0-9) for num-box
    const numBoxes = document.querySelectorAll('.num-box');
    numBoxes.forEach(input => {
        input.addEventListener('keypress', (e) => {
            if (!/[0-9]/.test(e.key)) {
                e.preventDefault();
            }
        });
    });

    // Enforce letters constraints (a-z, A-Z) for alpha-box
    const alphaBoxes = document.querySelectorAll('.alpha-box');
    alphaBoxes.forEach(input => {
        input.addEventListener('keypress', (e) => {
            if (!/[a-zA-Z]/.test(e.key)) {
                e.preventDefault();
            }
        });
    });

    function submitCode() {
        let code = '';
        let valid = true;
        inputs.forEach((input, idx) => {
            if (input.value === '') {
                valid = false;
            }
            code += input.value;
            if (idx === 2) code += '-'; 
        });

        if (!valid) {
            Swal.fire({
                icon: 'warning',
                title: 'Input Tidak Lengkap',
                text: 'Harap masukkan semua 6 digit kode verifikasi.',
                confirmButtonColor: '#189347',
                customClass: {
                    popup: 'rounded-[24px]',
                    confirmButton: 'rounded-[12px] px-6 py-2.5 font-bold'
                }
            });
            return;
        }

        document.getElementById('hidden-code').value = code;
        document.getElementById('form-verifikasi').submit();
    }
</script>
@endsection