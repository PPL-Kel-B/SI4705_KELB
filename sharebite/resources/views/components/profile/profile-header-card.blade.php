@props(['profile'])

<section class="rounded-3xl bg-white/90 shadow-sm ring-1 ring-emerald-100 p-5 sm:p-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-4 sm:gap-5">
            <div class="h-20 w-20 overflow-hidden bg-emerald-100 ring-4 ring-white flex items-center justify-center rounded-full">
                <x-profile.avatar-generate :name="$profile['name']" class="h-full w-full mx-0" />
            </div>
            <div>
                <h3 class="text-2xl font-semibold text-slate-900 leading-tight">{{ $profile['name'] }}</h3>
                <p class="mt-1 text-sm text-emerald-700">Terdaftar {{ $profile['member_since'] }}</p>
            </div>
        </div>
        <a href="{{ route('profile.edit') }}" type="button" class="inline-flex items-center justify-center rounded-full bg-emerald-500 px-5 py-2 text-sm font-medium text-white transition hover:bg-emerald-600">
            <svg class="mr-2 h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 20h4l10-10a2.121 2.121 0 1 0-3-3L5 17v3z" />
            </svg>
            Edit Profile
        </a>
    </div>
</section>
