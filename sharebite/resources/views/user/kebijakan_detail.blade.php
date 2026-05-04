@extends('layouts.user')

@section('title', $policy->title)

@section('content')
<div class="max-w-4xl mx-auto px-4 py-12">
    <!-- Navigation & Header -->
    <div class="mb-12 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-5">
            <a href="{{ route('user.pengaturan') }}" class="p-4 bg-white rounded-2xl shadow-sm text-gray-400 hover:text-[#1cb764] transition-colors border border-gray-50">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div>
                <h1 class="text-3xl font-black text-[#0a2e1f] tracking-tight">{{ $policy->title }}</h1>
                <p class="text-gray-400 font-bold text-sm uppercase tracking-widest mt-1">Kebijakan Komunitas ShareBite</p>
            </div>
        </div>
        <div class="px-6 py-3 bg-[#eefcf4] text-[#1cb764] rounded-2xl font-black text-sm border border-[#1cb764]/10 shadow-sm">
            Terakhir Diperbarui: Mei 2024
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="bg-white rounded-[3rem] p-10 md:p-16 shadow-sm border border-gray-50 relative overflow-hidden">
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 bg-[#eefcf4] rounded-full blur-3xl opacity-30"></div>
        
        <div class="relative z-10">
            <div class="text-6xl mb-8 transform -rotate-12 inline-block">{{ $policy->icon }}</div>
            
            <div class="prose prose-lg max-w-none">
                <p class="text-xl font-bold text-[#0a2e1f] leading-relaxed mb-10">
                    {{ $policy->description }}
                </p>

                <div class="space-y-10">
                    <section>
                        <h3 class="text-2xl font-black text-[#0a2e1f] mb-4 flex items-center gap-3">
                            <span class="w-8 h-8 bg-[#1cb764] text-white rounded-lg flex items-center justify-center text-sm font-black">01</span>
                            Pendahuluan
                        </h3>
                        <p class="text-gray-600 font-medium leading-relaxed">
                            ShareBite berkomitmen untuk menciptakan ekosistem berbagi yang aman dan terpercaya. Kebijakan ini dibuat untuk memastikan setiap interaksi dalam aplikasi memberikan manfaat maksimal bagi pemberi maupun penerima makanan.
                        </p>
                    </section>

                    <section>
                        <h3 class="text-2xl font-black text-[#0a2e1f] mb-4 flex items-center gap-3">
                            <span class="w-8 h-8 bg-[#1cb764] text-white rounded-lg flex items-center justify-center text-sm font-black">02</span>
                            Aturan Utama
                        </h3>
                        <ul class="space-y-4">
                            <li class="flex items-start gap-4">
                                <div class="mt-1.5 w-2 h-2 rounded-full bg-[#1cb764] shrink-0"></div>
                                <p class="text-gray-600 font-medium"><span class="font-black text-[#0a2e1f]">Kejujuran:</span> Pengguna wajib memberikan informasi yang akurat mengenai kondisi makanan atau identitas diri.</p>
                            </li>
                            <li class="flex items-start gap-4">
                                <div class="mt-1.5 w-2 h-2 rounded-full bg-[#1cb764] shrink-0"></div>
                                <p class="text-gray-600 font-medium"><span class="font-black text-[#0a2e1f]">Ketepatan Waktu:</span> Menghargai waktu pengambilan donasi yang telah disepakati bersama.</p>
                            </li>
                            <li class="flex items-start gap-4">
                                <div class="mt-1.5 w-2 h-2 rounded-full bg-[#1cb764] shrink-0"></div>
                                <p class="text-gray-600 font-medium"><span class="font-black text-[#0a2e1f]">Keamanan:</span> Selalu memprioritaskan protokol kesehatan saat proses serah terima makanan.</p>
                            </li>
                        </ul>
                    </section>

                    <section class="bg-[#f6fbf8] p-8 rounded-[2rem] border-2 border-dashed border-[#1cb764]/20">
                        <h3 class="text-xl font-black text-[#0a2e1f] mb-3 uppercase tracking-wider">Konsekuensi Pelanggaran</h3>
                        <p class="text-gray-600 font-medium leading-relaxed">
                            Setiap bentuk pelanggaran terhadap kebijakan ini akan ditinjau secara berkala oleh tim ShareBite dan dapat berakibat pada penangguhan akun secara sementara maupun permanen demi keamanan komunitas.
                        </p>
                    </section>
                </div>
            </div>

            <div class="mt-16 pt-10 border-t border-gray-100 flex flex-col md:flex-row items-center justify-between gap-6">
                <p class="text-gray-400 font-bold text-sm italic">"Terima kasih telah membantu mengurangi food waste bersama kami."</p>
                <a href="{{ route('user.pengaturan') }}" class="px-8 py-4 bg-[#0a2e1f] text-white font-black rounded-2xl shadow-xl hover:shadow-[#0a2e1f]/20 hover:-translate-y-1 transition-all">Selesai Membaca</a>
            </div>
        </div>
    </div>
</div>
@endsection
