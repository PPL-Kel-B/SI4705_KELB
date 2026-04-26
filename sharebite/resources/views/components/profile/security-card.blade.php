@props(['security'])

<article class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-100 sm:p-6">
    {{-- Header Keamanan --}}
    <h4 class="flex items-center gap-3 text-lg font-bold text-slate-900">
        {{-- Ikon Tameng (Shield) dengan background bulat hijau muda --}}
        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#EAF7F0]">
            <svg class="h-7 w-6 text-[#22A06B]" viewBox="0 0 24 24" fill="currentColor">
                <path fill="#22A06B"
                    d="M11.582 2.377a.75.75 0 0 1 .836 0l.961.646a11.44 11.44 0 0 0 5.238 1.885A1.26 1.26 0 0 1 19.75 6.16V11c0 3.004-1.929 5.688-3.709 7.544a24 24 0 0 1-3.54 3.022q-.041.03-.064.044l-.017.012l-.005.003l-.002.001L12 21l-.412.627l-.002-.002l-.006-.003l-.017-.012l-.065-.044l-.234-.163a24 24 0 0 1-3.305-2.859C6.179 16.688 4.25 14.004 4.25 11V6.16c0-.647.49-1.188 1.133-1.252a11.44 11.44 0 0 0 5.238-1.885zM12 21l-.412.627a.75.75 0 0 0 .824 0zm-.75-1.488V11.75H5.8c.28 2.125 1.701 4.15 3.241 5.756a23 23 0 0 0 2.209 2.006m7-9.262V6.377a12.94 12.94 0 0 1-5.5-1.973v5.846z" />
            </svg>
        </span>
        Keamanan
    </h4>

    {{-- Daftar Menu --}}
    <div class="mt-6 space-y-4">

        {{-- Tombol 1: Ubah Kata Sandi --}}
        <button type="button"
            class="group flex w-full items-center justify-between rounded-[1.25rem] bg-[#FAFAFA] p-3 text-left transition-all hover:bg-slate-100">
            <div class="flex items-center gap-4">
                {{-- Lingkaran tipis (stroke) warna hijau --}}
                <div
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full ring-1 ring-[#22A06B]/20 bg-white group-hover:bg-[#22A06B]/5">
                    {{-- Ikon Reset/Password Hijau --}}
                    <svg class="h-5 w-5 text-[#22A06B]" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        <rect x="10" y="11" width="4" height="5" rx="1" stroke-width="1.5" />
                        <path d="M10 11v-1a2 2 0 114 0v1" stroke-width="1.5" />
                    </svg>
                </div>
                <span class="text-[15px] font-semibold text-slate-800">
                    {{ $security['change_password'] ?? 'Ubah Kata Sandi' }}
                </span>
            </div>
            {{-- Panah Kanan --}}
            <svg class="mr-2 h-4 w-4 text-slate-400 group-hover:text-slate-600" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 18l6-6-6-6" />
            </svg>
        </button>

        {{-- Tombol 2: Notifikasi --}}
        <button type="button"
            class="group flex w-full items-center justify-between rounded-[1.25rem] bg-[#FAFAFA] p-3 text-left transition-all hover:bg-slate-100">
            <div class="flex items-center gap-4">
                {{-- Lingkaran tipis (stroke) warna oranye --}}
                <div
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full ring-1 ring-[#F59E0B]/20 bg-white group-hover:bg-[#F59E0B]/5">
                    {{-- Ikon Lonceng (Bell) Oranye --}}
                    <svg class="h-5 w-5 text-[#F59E0B]" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                        <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                    </svg>
                </div>
                <span class="text-[15px] font-semibold text-slate-800">
                    {{ $security['notifications'] ?? 'Notifikasi' }}
                </span>
            </div>
            {{-- Panah Kanan --}}
            <svg class="mr-2 h-4 w-4 text-slate-400 group-hover:text-slate-600" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 18l6-6-6-6" />
            </svg>
        </button>

    </div>
</article>
