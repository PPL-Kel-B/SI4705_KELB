@props(['profile'])

<section class="rounded-[2rem] bg-white p-5 shadow-sm ring-1 ring-emerald-100 sm:p-6">
    <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex flex-col items-center gap-5 text-center sm:flex-row sm:text-left">
            <div
                class="relative h-20 w-20 shrink-0 overflow-hidden rounded-full bg-emerald-100 ring-4 ring-white shadow-lg">
                <x-profile.avatar-generate :name="$profile['name']" class="h-full w-full mx-0" />
            </div>

            <div>
                <h2 class="text-2xl font-bold text-slate-900 sm:text-3xl">
                    {{ $profile['name'] }}
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Bergabung sejak {{ $profile['member_since'] }}
                </p>

                <button type="button"
                    class="mt-3 inline-flex items-center rounded-full border border-emerald-600 px-4 py-1.5 text-sm font-medium text-emerald-700 transition hover:bg-emerald-50">
                    Ganti Foto Profil
                </button>
            </div>
        </div>

        {{-- Tombol Simpan --}}
        <div class="flex justify-center sm:justify-end">
            <button type="submit" id="submit-button" disabled
                class="inline-flex items-center justify-center rounded-full bg-emerald-600 px-8 py-3 text-base font-semibold text-white shadow-lg shadow-emerald-200 transition 
                disabled:bg-slate-300 disabled:shadow-none disabled:cursor-not-allowed
                hover:bg-emerald-700 hover:shadow-none focus:ring-2 focus:ring-emerald-500 w-full sm:w-auto">
                Simpan Perubahan
            </button>
        </div>
    </div>
</section>
