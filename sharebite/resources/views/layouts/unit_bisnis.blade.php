<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <title>@yield('title', 'Dashboard Unit Bisnis') - ShareBite</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F4F8F6;
            /* Warna bg luar */
        }

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>

<body class="text-gray-800 antialiased" x-data="{ sidebarOpen: false, sidebarMinimized: false, showLogoutModal: false }">

    <!-- Mobile sidebar backdrop -->
    <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-20 bg-black bg-opacity-50 lg:hidden"
        @click="sidebarOpen = false"></div>

    <!-- Sidebar -->
    <aside
        :class="{
            'translate-x-0': sidebarOpen,
            '-translate-x-full': !sidebarOpen,
            'w-64': !sidebarMinimized,
            'w-20': sidebarMinimized
        }"
        class="fixed inset-y-0 left-0 z-30 flex flex-col bg-white shadow-[4px_0_24px_rgba(0,0,0,0.02)] transition-all duration-300 lg:translate-x-0">

        <!-- Logo Area -->
        <div class="flex items-center justify-center h-24 px-6 mb-4">
            <div class="flex items-center justify-center overflow-hidden w-full">
                <img src="{{ asset('images/logo.png') }}" alt="ShareBite" :class="sidebarMinimized ? 'h-99' : 'h-99'"
                    class="shrink-0 transition-all duration-300">
            </div>
            <!-- Minimize Button (Desktop) -->
            <button @click="sidebarMinimized = !sidebarMinimized"
                class="hidden lg:block text-gray-400 hover:text-gray-600 bg-gray-50 p-1.5 rounded-lg">
                <svg x-show="!sidebarMinimized" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                </svg>
                <svg x-show="sidebarMinimized" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                </svg>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 overflow-y-auto px-4 space-y-2">
            <!-- Dashboard -->
            <a href="{{ route('unit.dashboard') }}"
                class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all {{ request()->routeIs('unit.dashboard') ? 'bg-[#dcfce7] text-[#1cb764] font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700 font-medium' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                <span x-show="!sidebarMinimized" class="whitespace-nowrap">Dashboard</span>
            </a>

            <!-- Kelola Makanan -->
            <a href="{{ route('unit.kelola_makanan') }}"
                class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all {{ request()->routeIs('unit.kelola_makanan', 'unit.menu_aktif.*', 'unit.master_data.*') ? 'bg-[#dcfce7] text-[#1cb764] font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700 font-medium' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span x-show="!sidebarMinimized" class="whitespace-nowrap">Kelola Makanan</span>
            </a>

            <!-- Pesanan -->
            <a href="{{ route('unit.pesanan') }}"
                class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all {{ request()->routeIs('unit.pesanan') ? 'bg-[#dcfce7] text-[#1cb764] font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700 font-medium' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <span x-show="!sidebarMinimized" class="whitespace-nowrap">Pesanan</span>
            </a>

            <!-- Riwayat -->
            <a href="{{ route('unit.riwayat') }}"
                class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all {{ request()->routeIs('unit.riwayat') ? 'bg-[#dcfce7] text-[#1cb764] font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700 font-medium' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span x-show="!sidebarMinimized" class="whitespace-nowrap">Riwayat</span>
            </a>

            <!-- Profil -->
            <a href="{{ route('unit.profil') }}"
                class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all {{ request()->routeIs('unit.profil') ? 'bg-[#dcfce7] text-[#1cb764] font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700 font-medium' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span x-show="!sidebarMinimized" class="whitespace-nowrap">Profil</span>
            </a>

            <!-- Pengaturan -->
            <a href="{{ route('unit.pengaturan') }}"
                class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all {{ request()->routeIs('unit.pengaturan') ? 'bg-[#dcfce7] text-[#1cb764] font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700 font-medium' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span x-show="!sidebarMinimized" class="whitespace-nowrap">Pengaturan</span>
            </a>
        </nav>

        <!-- Footer / Logout -->
        <div class="p-4 border-t border-gray-50 mb-4 mt-2">
            <button @click="showLogoutModal = true"
                class="flex items-center gap-4 px-4 py-3.5 w-full rounded-xl text-red-600 hover:bg-red-50 transition-colors font-semibold text-left">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span x-show="!sidebarMinimized" class="whitespace-nowrap">Logout</span>
            </button>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div :class="{
        'lg:ml-64': !sidebarMinimized,
        'lg:ml-20': sidebarMinimized
    }"
        class="transition-all duration-300 min-h-screen flex flex-col">

        <!-- Header -->
        <header class="h-28 flex items-center justify-between px-6 lg:px-10">
            <!-- Left Side (Mobile Toggle) -->
            <div class="flex items-center gap-4">
                <!-- Hamburger for mobile -->
                <button @click="sidebarOpen = true"
                    class="lg:hidden p-2 rounded-xl bg-white shadow-sm text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- Right Side (Notifications & Profile) -->
            <div class="flex items-center gap-6">
                <!-- Notification Bell -->
                <button class="relative text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span
                        class="absolute top-0 right-0 w-2.5 h-2.5 bg-[#f7b055] rounded-full border-2 border-[#F4F8F6]"></span>
                </button>

                <!-- Profile Info -->
                <div class="hidden sm:flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-[11px] text-gray-500 font-medium uppercase tracking-wider">Unit Bisnis</p>
                        <p class="text-sm font-extrabold text-[#0a2e1f]">
                            {{ Auth::check() ? Auth::user()->name : 'Arcamanik Hotel' }}
                        </p>
                    </div>
                    <img src="https://ui-avatars.com/api/?name={{ Auth::check() ? urlencode(Auth::user()->name) : 'Arcamanik' }}&background=0a2e1f&color=fff&bold=true"
                        alt="Avatar" class="w-11 h-11 rounded-full object-cover shadow-sm ring-2 ring-white">
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1 px-6 lg:px-10 pb-10 mt-2">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
    <!-- Logout Confirmation Modal -->
    <div x-show="showLogoutModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <!-- Backdrop -->
        <div x-show="showLogoutModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            @click="showLogoutModal = false" class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

        <!-- Modal Content -->
        <div x-show="showLogoutModal" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative bg-white rounded-3xl shadow-2xl max-w-md w-full p-8 overflow-hidden z-10">

            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 bg-[#eefcf4] rounded-full blur-3xl opacity-50">
            </div>

            <div class="text-center">
                <div
                    class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-red-50 text-red-500 mb-6">
                    <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 mb-2">Konfirmasi Logout</h3>
                <p class="text-gray-500 font-medium mb-8 leading-relaxed">Apakah Anda yakin ingin keluar dari akun
                    ShareBite Anda sekarang?</p>

                <div class="flex flex-col sm:flex-row gap-3">
                    <button @click="showLogoutModal = false"
                        class="flex-1 px-6 py-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-2xl transition-all">
                        Batal
                    </button>
                    <form action="{{ route('logout') }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit"
                            class="w-full px-6 py-4 bg-red-500 hover:bg-red-600 text-white font-bold rounded-2xl transition-all shadow-lg shadow-red-100 hover:shadow-red-200">
                            Ya, Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
