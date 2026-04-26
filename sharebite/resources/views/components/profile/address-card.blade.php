@props(['profile'])

<article class="lg:col-span-2 rounded-3xl bg-white p-5 sm:p-6 ring-1 ring-slate-100 shadow-sm">
    <div class="flex items-center justify-between gap-3">
        <h4 class="flex items-center gap-2 text-lg font-semibold text-slate-800">
            <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-amber-100 text-amber-700">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-5.7 7-11a7 7 0 1 0-14 0c0 5.3 7 11 7 11z" />
                    <circle cx="12" cy="10" r="2.5" />
                </svg>
            </span>
            Lokasi dan Alamat Utama
        </h4>
        <button type="button" class="rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-700 hover:bg-amber-200 transition">
            Ubah Alamat
        </button>
    </div>
    <div class="mt-4 rounded-xl border-l-4 border-amber-400 bg-slate-50 px-4 py-3 text-sm text-slate-700">
        <p class="inline-flex items-start gap-2">
            <svg class="mt-0.5 h-4 w-4 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-5.7 7-11a7 7 0 1 0-14 0c0 5.3 7 11 7 11z" />
                <circle cx="12" cy="10" r="2.5" />
            </svg>
            <span>{{ $profile['address'] }}</span>
        </p>
    </div>
</article>
