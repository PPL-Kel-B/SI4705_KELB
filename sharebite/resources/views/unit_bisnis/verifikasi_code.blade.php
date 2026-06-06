@extends('layouts.unit_bisnis')
@section('content')
<div class="p-6 lg:p-10 bg-[#F0F7F2] min-h-screen font-jakarta w-full">
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white p-10 rounded-[32px] shadow-sm border border-green-50 mb-10 text-center max-w-3xl mx-auto">
        <h2 class="text-[13px] font-extrabold text-[#189347] tracking-[0.2em] uppercase mb-8">INPUT 6-DIGIT KODE VERIFIKASI</h2>
        
        <form action="{{ route('unit.pesanan.verifikasi') }}" method="GET" id="form-verifikasi" class="flex flex-col items-center">
            <div class="flex items-center justify-center gap-2 mb-8">
                <span class="text-3xl font-black text-[#189347] mr-2">SB -</span>
                
                <input type="text" maxlength="1" class="code-box w-14 h-16 text-3xl font-black text-center text-[#189347] bg-[#F0F7F2] border-2 border-transparent focus:border-[#189347] focus:ring-0 rounded-2xl transition outline-none">
                <input type="text" maxlength="1" class="code-box w-14 h-16 text-3xl font-black text-center text-[#189347] bg-[#F0F7F2] border-2 border-transparent focus:border-[#189347] focus:ring-0 rounded-2xl transition outline-none">
                <input type="text" maxlength="1" class="code-box w-14 h-16 text-3xl font-black text-center text-[#189347] bg-[#F0F7F2] border-2 border-transparent focus:border-[#189347] focus:ring-0 rounded-2xl transition outline-none">
                
                <span class="text-3xl font-black text-gray-300 mx-1">-</span>
                
                <input type="text" maxlength="1" class="code-box w-14 h-16 text-3xl font-black text-center text-[#189347] bg-[#F0F7F2] border-2 border-transparent focus:border-[#189347] focus:ring-0 rounded-2xl transition outline-none uppercase">
                <input type="text" maxlength="1" class="code-box w-14 h-16 text-3xl font-black text-center text-[#189347] bg-[#F0F7F2] border-2 border-transparent focus:border-[#189347] focus:ring-0 rounded-2xl transition outline-none uppercase">
                <input type="text" maxlength="1" class="code-box w-14 h-16 text-3xl font-black text-center text-[#189347] bg-[#F0F7F2] border-2 border-transparent focus:border-[#189347] focus:ring-0 rounded-2xl transition outline-none uppercase">
                
                <input type="hidden" name="code" id="hidden-code">
            </div>

            <button type="button" onclick="submitCode()" class="text-[#189347] font-bold text-[15px] flex items-center gap-2 hover:underline">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg> Cari makanan
            </button>
        </form>
    </div>

    @if($pesanan)
    <div class="max-w-4xl mx-auto">
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
                        <span class="text-[12px] text-gray-400 font-medium">Total Pesanan</span>
                        <span class="text-[12px] font-bold text-gray-800">{{ $totalPesananBuyer }} Pesanan</span>
                    </div>
                </div>

                <form action="{{ route('unit.pesanan.update', $pesanan->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="w-full bg-[#189347] hover:bg-[#147a3a] text-white py-3.5 rounded-xl font-bold text-[14px] flex items-center justify-center gap-2 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> Verifikasi & Selesaikan
                    </button>
                </form>
            </div>

            <div class="md:col-span-8 bg-white rounded-[24px] shadow-sm overflow-hidden flex flex-col justify-between p-2">
                <img src="{{ asset('storage/' . ($pesanan->menuAktif->masterMakanan->foto ?? '')) }}" onerror="this.src='https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=800&q=80'" class="w-full h-44 object-cover rounded-[16px]">
                <div class="p-6">
                    <h3 class="text-2xl font-extrabold text-[#189347] mb-1">
                        {{ $pesanan->jumlah_porsi }}x {{ $pesanan->menuAktif->masterMakanan->nama_makanan ?? 'Menu Makanan' }}
                    </h3>
                    
                    <div class="flex justify-end mt-4">
                        <div class="text-right">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Nilai Redistribusi</p>
                            <p class="text-2xl font-black text-[#189347]">@if($pesanan->menuAktif->is_gratis) GRATIS @else Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }} @endif</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
    const inputs = document.querySelectorAll('.code-box');
    inputs.forEach((input, index) => {
        input.addEventListener('input', (e) => {
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

    function submitCode() {
        let code = '';
        inputs.forEach((input, idx) => {
            code += input.value;
            if (idx === 2) code += '-'; 
        });
        document.getElementById('hidden-code').value = code;
        document.getElementById('form-verifikasi').submit();
    }
</script>
@endsection