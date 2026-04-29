<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="bg-gradient-to-b from-emerald-50 via-slate-50 to-amber-50 font-sans antialiased text-slate-900 min-h-screen">

    <div class="mx-auto w-full max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
        {{-- Header Area --}}
        @isset($header)
            <header class="mb-6 flex h-14 items-center gap-4">
                {{-- Tombol Back --}}
                @if (isset($backUrl))
                    <a href="{{ $backUrl }}"
                        class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white text-emerald-700 shadow-sm ring-1 ring-emerald-100 transition hover:bg-emerald-50 hover:text-emerald-800">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6" />
                        </svg>
                    </a>
                @endif

                {{-- Slot Header --}}
                <div class="flex-1">
                    {{ $header }}
                </div>
            </header>
        @endisset

        {{-- Main Content Area --}}
        <main>
            {{ $slot }}
        </main>
    </div>

</body>

</html>
