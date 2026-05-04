@extends('layouts.admin')

@section('title', 'Review Dokumen NIB - ' . $profile->nama_usaha)

@section('content')
<div class="max-w-7xl mx-auto -mt-4" x-data="{ showApproveModal: false, showRejectModal: false, notes: '' }">

    {{-- Breadcrumbs --}}
    <div class="flex items-center text-xs font-bold text-gray-400 uppercase tracking-widest mb-6 gap-2">
        <a href="{{ route('admin.manajemen_pengguna') }}" class="hover:text-[#1cb764] transition-colors">Manajemen Pengguna</a>
        <span>/</span>
        <a href="{{ route('admin.manajemen_pengguna', ['tab' => 'verifikasi_nib']) }}" class="hover:text-[#1cb764] transition-colors">Verifikasi NIB</a>
        <span>/</span>
        <span class="text-gray-600">Review</span>
    </div>

    {{-- Page Title --}}
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900">Review Dokumen NIB</h1>
    </div>

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        {{-- LEFT: Document Preview --}}
        <div class="lg:col-span-7 bg-white rounded-3xl p-8 shadow-sm border border-gray-50 flex flex-col">
            {{-- File Header --}}
            <div class="flex flex-col items-center mb-6">
                <div class="w-16 h-16 bg-[#eefcf4] rounded-2xl flex items-center justify-center text-[#1cb764] mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-900">
                    {{ $profile->nib_file ? 'NIB_' . Str::slug($profile->nama_usaha) . '.pdf' : 'Dokumen NIB Belum Diunggah' }}
                </h2>
                <p class="text-sm text-gray-400 mt-1">
                    Diunggah pada {{ date('d M Y', strtotime($profile->updated_at)) }}
                </p>
            </div>

            {{-- Document Preview (iframe / image / placeholder) --}}
            <div class="flex-1 bg-gray-50 rounded-2xl overflow-hidden border border-gray-100 min-h-[500px] flex flex-col">
                @if($profile->nib_file)
                    @php $ext = strtolower(pathinfo($profile->nib_file, PATHINFO_EXTENSION)); @endphp
                    @if(in_array($ext, ['pdf']))
                        <iframe
                            src="{{ asset('storage/' . $profile->nib_file) }}"
                            class="w-full flex-1 border-0 min-h-[500px]">
                        </iframe>
                    @else
                        <img
                            src="{{ asset('storage/' . $profile->nib_file) }}"
                            alt="Dokumen NIB"
                            class="w-full h-auto object-contain max-h-[650px]">
                    @endif
                @else
                    <div class="flex-1 flex flex-col items-center justify-center text-center p-10">
                        <div class="w-20 h-20 bg-gray-100 rounded-2xl flex items-center justify-center text-gray-300 mb-4">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <p class="text-gray-400 font-bold text-sm uppercase tracking-widest">File NIB belum diunggah</p>
                        <p class="text-gray-400 text-xs mt-2">Pengguna belum mengunggah dokumen NIB.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- RIGHT: Business Info + Notes + Actions --}}
        <div class="lg:col-span-5 flex flex-col gap-6">

            {{-- Business Info Card --}}
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-50 flex-1">
                <div class="flex items-center gap-3 mb-8 pb-6 border-b border-gray-50">
                    <div class="w-10 h-10 bg-[#eefcf4] rounded-xl flex items-center justify-center text-[#1cb764]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Informasi Bisnis</h3>
                </div>

                <div class="space-y-6">
                    {{-- Nama Usaha --}}
                    <div>
                        <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Nama Usaha</p>
                        <p class="text-2xl font-extrabold text-gray-900">{{ $profile->nama_usaha }}</p>
                    </div>

                    {{-- Jenis Usaha --}}
                    <div>
                        <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2">Jenis Usaha</p>
                        <span class="inline-flex items-center px-3 py-1 bg-[#eefcf4] text-[#1cb764] rounded-full text-xs font-bold uppercase tracking-wider">
                            {{ $profile->jenis_usaha ?? '-' }}
                        </span>
                    </div>

                    {{-- Alamat --}}
                    <div>
                        <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2">Alamat Lengkap</p>
                        <div class="flex gap-2">
                            <svg class="w-4 h-4 text-[#1cb764] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <p class="text-sm text-gray-600 leading-relaxed">{{ $profile->user->alamat ?? 'Alamat belum diperbarui' }}</p>
                        </div>
                    </div>

                    {{-- Kontak --}}
                    <div>
                        <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-3">Kontak</p>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                {{ $profile->user->no_hp ?? '-' }}
                            </div>
                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                {{ $profile->user->email }}
                            </div>
                        </div>
                    </div>

                    {{-- Reviewer Notes --}}
                    <div class="pt-6 border-t border-gray-50">
                        <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-3">Reviewer Notes</p>
                        <textarea
                            x-model="notes"
                            rows="4"
                            placeholder="Berikan catatan verifikasi jika diperlukan..."
                            class="w-full bg-gray-50 border border-gray-100 rounded-2xl p-4 text-sm text-gray-600 italic focus:outline-none focus:ring-2 focus:ring-[#1cb764]/30 focus:border-[#1cb764] transition-all resize-none">
                        </textarea>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center gap-3">
                <button
                    @click="showRejectModal = true"
                    class="flex-1 py-4 px-6 bg-white border-2 border-red-100 text-red-500 font-bold rounded-2xl hover:bg-red-50 hover:border-red-200 transition-all duration-200 flex items-center justify-center gap-2 group">
                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Tolak Verifikasi
                </button>
                <button
                    @click="showApproveModal = true"
                    class="flex-[1.5] py-4 px-6 bg-[#1cb764] text-white font-bold rounded-2xl hover:bg-[#0a2e1f] transition-all duration-200 shadow-lg shadow-[#1cb764]/20 flex items-center justify-center gap-2 group">
                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    Setujui &amp; Verifikasi
                </button>
            </div>
        </div>
    </div>

    {{-- ========= MODALS ========= --}}

    {{-- REJECT MODAL --}}
    <div x-show="showRejectModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showRejectModal = false"></div>
        <div class="relative bg-white rounded-3xl p-10 max-w-sm w-full shadow-2xl text-center"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center text-red-500 mx-auto mb-6">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <h3 class="text-xl font-extrabold text-gray-900 mb-2">Tolak Verifikasi NIB?</h3>
            <p class="text-sm text-gray-500 mb-8 leading-relaxed">
                Aksi ini akan menolak pengajuan NIB. Pastikan Anda sudah mengisi Reviewer Notes sebagai alasan penolakan.
            </p>
            <form action="{{ route('admin.manajemen_pengguna.process_nib', $profile->user_id) }}" method="POST" class="flex gap-3">
                @csrf
                <input type="hidden" name="status" value="ditolak">
                <input type="hidden" name="reviewer_notes" :value="notes">
                <button type="button" @click="showRejectModal = false"
                    class="flex-1 py-3 bg-gray-100 text-gray-600 font-bold rounded-xl hover:bg-gray-200 transition-all">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 py-3 bg-red-500 text-white font-bold rounded-xl hover:bg-red-600 transition-all shadow-lg shadow-red-500/25">
                    Ya, Tolak
                </button>
            </form>
        </div>
    </div>

    {{-- APPROVE MODAL --}}
    <div x-show="showApproveModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showApproveModal = false"></div>
        <div class="relative bg-white rounded-3xl p-10 max-w-sm w-full shadow-2xl text-center"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            <div class="w-16 h-16 bg-[#eefcf4] rounded-full flex items-center justify-center text-[#1cb764] mx-auto mb-6">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h3 class="text-xl font-extrabold text-gray-900 mb-2">Setujui Verifikasi NIB?</h3>
            <p class="text-sm text-gray-500 mb-8 leading-relaxed">
                Akun Unit Bisnis ini akan langsung aktif dan dapat menggunakan seluruh fitur platform ShareBite.
            </p>
            <form action="{{ route('admin.manajemen_pengguna.process_nib', $profile->user_id) }}" method="POST" class="flex gap-3">
                @csrf
                <input type="hidden" name="status" value="terverifikasi">
                <input type="hidden" name="reviewer_notes" :value="notes">
                <button type="button" @click="showApproveModal = false"
                    class="flex-1 py-3 bg-gray-100 text-gray-600 font-bold rounded-xl hover:bg-gray-200 transition-all">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 py-3 bg-[#1cb764] text-white font-bold rounded-xl hover:bg-[#0a2e1f] transition-all shadow-lg shadow-[#1cb764]/25">
                    Ya, Setujui
                </button>
            </form>
        </div>
    </div>

</div>

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection
