@props(['profile'])

<article class="lg:col-span-2 rounded-3xl bg-white p-5 sm:p-6 ring-1 ring-slate-100 shadow-sm">
    <h4 class="flex items-center gap-2 text-lg font-semibold text-slate-800">
        <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 12a5 5 0 1 0 0-10 5 5 0 0 0 0 10zm-7 9a7 7 0 0 1 14 0" />
            </svg>
        </span>
        Informasi Pribadi
    </h4>
    <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2">
        <div>
            <p class="text-[11px] uppercase tracking-wide text-slate-400">Nama Lengkap</p>
            <p class="mt-2 rounded-xl bg-slate-50 px-3 py-2 text-sm text-slate-700">{{ $profile['name'] }}</p>
        </div>
        <div>
            <p class="text-[11px] uppercase tracking-wide text-slate-400">Alamat Email</p>
            <p class="mt-2 rounded-xl bg-slate-50 px-3 py-2 text-sm text-slate-700">{{ $profile['email'] }}</p>
        </div>
        <div class="sm:col-span-2">
            <p class="text-[11px] uppercase tracking-wide text-slate-400">No Telepon</p>
            <p class="mt-2 rounded-xl bg-slate-50 px-3 py-2 text-sm text-slate-700">{{ $profile['phone'] }}</p>
        </div>
    </div>
</article>
