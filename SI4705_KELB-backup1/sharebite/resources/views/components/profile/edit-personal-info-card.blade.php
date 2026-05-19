@props(['profile'])

<section class="mt-6 rounded-[2rem] bg-white p-6 shadow-sm ring-1 ring-slate-100 sm:p-8">
    <h3 class="flex items-center gap-2 text-xl font-semibold text-slate-800">
        <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 12a5 5 0 1 0 0-10 5 5 0 0 0 0 10zm-7 9a7 7 0 0 1 14 0" />
            </svg>
        </span>
        Informasi Pribadi
    </h3>

    <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2">
        <div>
            <label for="name" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Nama
                Lengkap</label>
            <input id="name" name="name" type="text" value="{{ old('name', $profile['name']) }}"
                class="form-input w-full rounded-2xl border-0 bg-slate-50 px-4 py-3 text-sm text-slate-700 ring-1 ring-inset ring-slate-100 focus:ring-2 focus:ring-emerald-500 form-input-watch" />
        </div>

        <div>
            <label for="email"
                class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email', $profile['email']) }}"
                class="form-input w-full rounded-2xl border-0 bg-slate-50 px-4 py-3 text-sm text-slate-700 ring-1 ring-inset ring-slate-100 focus:ring-2 focus:ring-emerald-500 form-input-watch" />
        </div>

        <div class="md:col-span-2">
            <label for="phone" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Nomor
                Telepon</label>
            <input id="phone" name="phone" type="text" value="{{ old('phone', $profile['phone']) }}"
                class="form-input w-full rounded-2xl border-0 bg-slate-50 px-4 py-3 text-sm text-slate-700 ring-1 ring-inset ring-slate-100 focus:ring-2 focus:ring-emerald-500 form-input-watch" />
        </div>
    </div>
</section>
