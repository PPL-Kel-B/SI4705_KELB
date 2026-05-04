<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <title>@yield('title', 'ShareBite') – ShareBite</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/custom-style.css') }}">

    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* Sidebar active state */
        .nav-item { display:flex; align-items:center; gap:12px; padding:10px 16px; border-radius:12px; font-size:14px; font-weight:500; color:#6B7280; text-decoration:none; transition:all .2s; cursor:pointer; }
        .nav-item:hover { background:#F0F9F4; color:#1A6B3C; }
        .nav-item.active { background:#EBF7F0; color:#1A6B3C; font-weight:700; }
        .nav-item svg { flex-shrink:0; }

        /* Scrollbar */
        ::-webkit-scrollbar { width:5px; }
        ::-webkit-scrollbar-track { background:#F9FAFB; }
        ::-webkit-scrollbar-thumb { background:#D1FAE5; border-radius:99px; }
    </style>

    @stack('styles')
</head>
<body class="bg-[#F0F7F0]">

<div class="flex min-h-screen">

    {{-- ============================================================ --}}
    {{-- SIDEBAR --}}
    {{-- ============================================================ --}}
    <aside class="w-60 bg-white flex flex-col flex-shrink-0 shadow-sm" style="min-height:100vh;">

        {{-- Logo --}}
        <div class="px-6 py-6 border-b border-gray-50">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
                {{-- Leaf icon --}}
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:linear-gradient(135deg,#22c55e,#16a34a);">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17 8C8 10 5.9 16.17 3.82 21.34L5.71 22l1-2.3A4.49 4.49 0 008 20C19 20 22 3 22 3c-1 2-8 2-5 8z"/>
                    </svg>
                </div>
                <span class="text-lg font-extrabold text-gray-800">ShareBite</span>
            </a>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 py-5 space-y-1">

            {{-- Dashboard --}}
            <a href="{{ route('dashboard') }}"
               class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                    <rect x="14" y="3" width="7" height="7" rx="1.5"/>
                    <rect x="3" y="14" width="7" height="7" rx="1.5"/>
                    <rect x="14" y="14" width="7" height="7" rx="1.5"/>
                </svg>
                Dashboard
            </a>

            {{-- Kelola Makanan --}}
            <a href="{{ route('kelola-master-data.index') }}"
               class="nav-item {{ request()->routeIs('kelola-master-data.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Kelola Makanan
            </a>

            {{-- Pesanan --}}
            <a href="#" class="nav-item">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Pesanan
            </a>

            {{-- Riwayat --}}
            <a href="#" class="nav-item">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Riwayat
            </a>

            {{-- Profil --}}
            <a href="{{ route('profile.index') }}" class="nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Profil
            </a>

            {{-- Pengaturan --}}
            <a href="#" class="nav-item">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Pengaturan
            </a>
        </nav>

        {{-- Logout --}}
        <div class="px-3 py-5 border-t border-gray-50">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-item w-full text-left" style="color:#EF4444;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- ============================================================ --}}
    {{-- MAIN CONTENT --}}
    {{-- ============================================================ --}}
    <div class="flex-1 flex flex-col min-w-0">

        {{-- TOP HEADER --}}
        <header class="bg-white border-b border-gray-100 px-8 pt-6 pb-0">
            <div class="flex items-start justify-between">

                {{-- Page Title + Tabs --}}
                <div>
                    <h1 class="text-2xl font-extrabold text-gray-900">@yield('page-title', 'Dashboard')</h1>
                    @yield('header-tabs')
                </div>

                {{-- User Info --}}
                <div class="flex items-center gap-3 pb-4">
                    {{-- Bell --}}
                    <button class="w-9 h-9 flex items-center justify-center rounded-xl bg-gray-50 hover:bg-gray-100 transition text-gray-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </button>

                    {{-- User --}}
                    <div class="flex items-center gap-2.5">
                        <div class="text-right">
                            <p class="text-[11px] text-gray-400 font-medium leading-none mb-0.5">
                                {{ optional(Auth::user())->role ?? 'Unit Bisnis' }}
                            </p>
                            <p class="text-sm font-bold text-gray-800 leading-none">
                                {{ optional(Auth::user())->name ?? 'Guest' }}
                            </p>
                        </div>
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white font-bold text-sm overflow-hidden">
                            @if(Auth::check())
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            @else
                                G
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- PAGE CONTENT --}}
        <main class="flex-1 p-8">
            @yield('content')
        </main>

    </div>
</div>

@stack('scripts')
</body>
</html>
