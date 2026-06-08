@extends('layouts.unit_bisnis')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6 font-jakarta">
    <!-- Navigation & Header -->
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-5">
            <a href="{{ route('unit.pesanan.index') }}" class="p-4 bg-white rounded-2xl shadow-sm text-gray-400 hover:text-[#189347] transition-colors border border-gray-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div>
                <h1 class="text-3xl font-black text-gray-800 tracking-tight">Panduan Pengambilan</h1>
                <p class="text-gray-400 font-bold text-sm uppercase tracking-widest mt-1">Kebijakan Komunitas ShareBite</p>
            </div>
        </div>
        <div class="px-6 py-3 bg-[#E4F2E8] text-[#189347] rounded-2xl font-black text-sm border border-[#189347]/10 shadow-sm w-fit">
            Terakhir Diperbarui: Juni 2026
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="bg-white rounded-[3rem] p-10 md:p-16 shadow-sm border border-gray-50 relative overflow-hidden">
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 bg-[#E4F2E8] rounded-full blur-3xl opacity-30"></div>
        
        <div class="relative z-10">
            <div class="text-6xl mb-8 transform -rotate-12 inline-block">🛡️</div>
            
            <div class="prose prose-lg max-w-none">
                <p class="text-xl font-bold text-gray-800 leading-relaxed mb-10">
                    Panduan resmi bagi Unit Bisnis mitra ShareBite dalam melakukan serah terima paket makanan kepada relawan/volunteer demi menjaga keamanan dan higienitas distribusi.
                </p>

                <div class="space-y-10">
                    <section>
                        <h3 class="text-2xl font-black text-gray-800 mb-4 flex items-center gap-3">
                            <span class="w-8 h-8 bg-[#189347] text-white rounded-lg flex items-center justify-center text-sm font-black">01</span>
                            Pendahuluan
                        </h3>
                        <p class="text-gray-600 font-medium leading-relaxed">
                            ShareBite berkomitmen untuk menciptakan ekosistem berbagi makanan berlebih yang aman, higienis, dan terpercaya bagi unit bisnis dan relawan. Panduan ini dibuat untuk memastikan kelancaran dan keamanan pada setiap proses serah terima makanan.
                        </p>
                    </section>

                    <section>
                        <h3 class="text-2xl font-black text-gray-800 mb-4 flex items-center gap-3">
                            <span class="w-8 h-8 bg-[#189347] text-white rounded-lg flex items-center justify-center text-sm font-black">02</span>
                            Aturan Utama
                        </h3>
                        <ul class="space-y-4">
                            <li class="flex items-start gap-4">
                                <div class="mt-1.5 w-2 h-2 rounded-full bg-[#189347] shrink-0"></div>
                                <p class="text-gray-600 font-medium"><span class="font-black text-gray-800">Verifikasi Kode 6-Digit:</span> Unit bisnis wajib memverifikasi kode unik 6-digit (format <span class="font-black text-[#189347]">SB-XXX-YYY</span>) yang dibawa oleh relawan sebelum menyerahkan paket makanan. Jangan menyerahkan makanan jika kode tidak sesuai atau tidak valid.</p>
                            </li>
                            <li class="flex items-start gap-4">
                                <div class="mt-1.5 w-2 h-2 rounded-full bg-[#189347] shrink-0"></div>
                                <p class="text-gray-600 font-medium"><span class="font-black text-gray-800">Pengecekan Identitas Relawan:</span> Pastikan ID Volunteer yang tertera di layar verifikasi cocok dengan profil relawan yang mengambil makanan.</p>
                            </li>
                            <li class="flex items-start gap-4">
                                <div class="mt-1.5 w-2 h-2 rounded-full bg-[#189347] shrink-0"></div>
                                <p class="text-gray-600 font-medium"><span class="font-black text-gray-800">Higienitas Paket Makanan:</span> Pastikan makanan dikemas dengan aman, bersih, dan sesuai standar kelayakan konsumsi untuk menjaga kualitas makanan yang didistribusikan.</p>
                            </li>
                        </ul>
                    </section>

                    <section class="bg-[#F0F7F2] p-8 rounded-[2rem] border-2 border-dashed border-[#189347]/20">
                        <h3 class="text-xl font-black text-gray-800 mb-3 uppercase tracking-wider">Konsekuensi Pelanggaran</h3>
                        <p class="text-gray-600 font-medium leading-relaxed">
                            Segala bentuk penyerahan paket makanan tanpa verifikasi kode unik yang valid atau ketidaksesuaian prosedur akan ditinjau oleh tim ShareBite dan dapat mempengaruhi status verifikasi unit bisnis Anda demi menjaga kepercayaan komunitas.
                        </p>
                    </section>
                </div>
            </div>

            <div class="mt-16 pt-10 border-t border-gray-100 flex flex-col md:flex-row items-center justify-between gap-6">
                <p class="text-gray-400 font-bold text-sm italic">"Terima kasih telah membantu mengurangi food waste bersama kami."</p>
                <a href="{{ route('unit.pesanan.index') }}" class="px-8 py-4 bg-[#189347] hover:bg-[#147a3a] text-white font-black rounded-2xl shadow-xl hover:shadow-[#189347]/20 hover:-translate-y-1 transition-all">Selesai Membaca</a>
            </div>
        </div>
    </div>
</div>
@endsection
