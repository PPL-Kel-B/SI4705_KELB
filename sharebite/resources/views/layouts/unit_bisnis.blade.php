<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ShareBite Admin') — ShareBite</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind via CDN (project sudah ada Vite, tapi ini fallback aman) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: {
                            DEFAULT: '#1cb764',
                            dark:    '#18a357',
                            light:   '#e8f8ef',
                            muted:   '#b2dfcc',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        /* Scrollbar styling */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 6px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Sidebar active glow */
        .nav-active {
            background: linear-gradient(135deg, #1cb764 0%, #18a357 100%);
            box-shadow: 0 4px 15px rgba(28,183,100,0.35);
        }

        /* Card hover lift */
        .food-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.12);
        }

        /* Smooth transitions */
        .food-card { transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); }

        /* Alert animation */
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-12px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .alert-animate { animation: slideDown 0.35s ease-out; }

        /* Image aspect ratio */
        .food-img-wrap { aspect-ratio: 4/3; overflow: hidden; }
    </style>

    @stack('styles')
</head>
<body class="font-sans antialiased bg-[#f0f7f4] min-h-screen">

<div class="flex min-h-screen">

    {{-- ===================== SIDEBAR ===================== --}}
    <aside class="w-60 min-h-screen bg-white flex flex-col fixed left-0 top-0 bottom-0 shadow-xl z-30" style="border-right:1px solid #e8f0ed;">

        {{-- Logo --}}
        <div class="px-6 py-6 flex items-center space-x-3 border-b border-gray-100">
            <div class="w-9 h-9 bg-brand rounded-xl flex items-center justify-center shadow-md">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                          d="M12 3c-4.97 0-9 2.686-9 6v2c0 3.314 4.03 6 9 6s9-2.686 9-6V9c0-3.314-4.03-6-9-6z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                          d="M3 9c0 3.314 4.03 6 9 6s9-2.686 9-6"/>
                </svg>
            </div>
            <span class="text-xl font-extrabold text-gray-900 tracking-tight">Share<span class="text-brand">Bite</span></span>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">

            {{-- Dashboard --}}
            <a href="{{ url('/dashboard') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-500 font-medium hover:bg-brand-light hover:text-brand transition-all duration-200 group">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Dashboard</span>
            </a>

            {{-- Kelola Makanan (active) --}}
            <a href="{{ route('kelolamasterdata.index') }}"
               class="nav-active flex items-center space-x-3 px-4 py-3 rounded-xl text-white font-semibold">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span>Kelola Makanan</span>
            </a>

            {{-- Pesanan --}}
            <a href="#"
               class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-500 font-medium hover:bg-brand-light hover:text-brand transition-all duration-200">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <span>Pesanan</span>
            </a>

            {{-- Riwayat --}}
            <a href="#"
               class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-500 font-medium hover:bg-brand-light hover:text-brand transition-all duration-200">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Riwayat</span>
            </a>

            {{-- Profil --}}
            <a href="#"
               class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-500 font-medium hover:bg-brand-light hover:text-brand transition-all duration-200">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span>Profil</span>
            </a>

            {{-- Pengaturan --}}
            <a href="#"
               class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-500 font-medium hover:bg-brand-light hover:text-brand transition-all duration-200">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>Pengaturan</span>
            </a>

        </nav>

        {{-- Logout --}}
        <div class="px-4 py-5 border-t border-gray-100">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="flex items-center space-x-3 px-4 py-3 w-full rounded-xl text-red-500 font-semibold hover:bg-red-50 transition-all duration-200">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>

    </aside>
    {{-- ===================== END SIDEBAR ===================== --}}

    {{-- ===================== MAIN CONTENT ===================== --}}
    <div class="flex-1 ml-60 flex flex-col min-h-screen">

        {{-- Top Header --}}
        <header class="bg-white sticky top-0 z-20 px-8 py-5 flex items-center justify-between"
                style="border-bottom:1px solid #e8f0ed; box-shadow: 0 1px 10px rgba(0,0,0,0.05);">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">@yield('page-title', 'Kelola Menu Makanan')</h1>
                {{-- Tabs --}}
                @yield('header-tabs')
            </div>

            {{-- User Profile --}}
            <div class="flex items-center space-x-3">
                {{-- Bell --}}
                <button class="relative w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center hover:bg-brand-light transition-colors">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </button>
                {{-- User Info --}}
                <div class="flex items-center space-x-2 bg-gray-50 rounded-xl px-3 py-2">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs text-gray-400 font-medium">Unit Bisnis</p>
                        <p class="text-sm font-bold text-gray-800 leading-tight">
                            {{ Auth::check() ? (Auth::user()->name ?? 'Unit Bisnis') : 'Unit Bisnis' }}
                        </p>
                    </div>
                    <div class="w-9 h-9 rounded-xl bg-brand flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                        {{ Auth::check() ? strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) : 'U' }}
                    </div>
                </div>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="flex-1 p-8">
            @yield('content')
        </main>

    </div>
    {{-- ===================== END MAIN CONTENT ===================== --}}

</div>

@stack('scripts')
</body>
</html>
