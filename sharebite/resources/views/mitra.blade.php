<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mitra Kami - ShareBite</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fbf9;
        }

        .text-dark-green {
            color: #0a5c36;
        }

        .bg-dark-green {
            background-color: #0a5c36;
        }

        .text-primary-green {
            color: #1cb764;
        }

        .bg-primary-green {
            background-color: #1cb764;
        }

        .text-gold {
            color: #9b621e;
        }

        .bg-gold {
            background-color: #9b621e;
        }

        .hero-gradient {
            background: radial-gradient(circle at top right, rgba(28, 183, 100, 0.08) 0%, rgba(248, 251, 249, 0) 50%);
        }

        /* Float animation for decorative blobs */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .animate-float {
            animation: float 4s ease-in-out infinite;
        }
    </style>
</head>

<body class="text-gray-800 antialiased relative overflow-x-hidden min-h-screen flex flex-col justify-between">

    <!-- Decorative background elements -->
    <div class="absolute top-24 -left-20 w-80 h-80 bg-[#1cb764]/5 rounded-full blur-3xl pointer-events-none animate-float"></div>
    <div class="absolute top-80 right-0 w-96 h-96 bg-gold/5 rounded-full blur-3xl pointer-events-none animate-float" style="animation-delay: 2s;"></div>

    <div>
        <!-- Navbar -->
        <nav class="fixed w-full z-50 bg-[#f8fbf9]/95 backdrop-blur-md border-b border-gray-100 transition-all duration-300">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="flex items-center justify-between h-24">
                    <!-- Logo -->
                    <a href="/" class="flex items-center group">
                        <img src="{{ asset('images/ShareBite.png') }}" alt="ShareBite Logo"
                            class="h-9 transform group-hover:scale-105 transition-transform duration-300">
                    </a>

                    <!-- Desktop Menu -->
                    <div class="hidden md:flex items-center space-x-12">
                        <a href="/"
                            class="text-[13px] font-black text-gray-400 hover:text-[#0a5c36] transition-colors uppercase tracking-widest">Home</a>
                        <a href="/mitra"
                            class="text-[13px] font-black text-[#0a5c36] border-b-2 border-[#0a5c36] pb-1 uppercase tracking-widest">Mitra Kami</a>
                        <a href="/tentang-kami"
                            class="text-[13px] font-black text-gray-400 hover:text-[#0a5c36] transition-colors uppercase tracking-widest">Tentang Kami</a>
                    </div>

                    <!-- CTA Auth -->
                    <div class="hidden md:flex items-center">
                        @auth
                            @if (auth()->user()->role == 'unit_bisnis')
                                <a href="/unit/dashboard"
                                    class="bg-dark-green hover:bg-[#064225] text-white px-8 py-3.5 rounded-full text-xs uppercase tracking-widest font-black transition-all shadow-xl shadow-[#0a5c36]/20 hover:-translate-y-0.5">
                                    Dashboard
                                </a>
                            @elseif (auth()->user()->role == 'admin')
                                <a href="/admin/dashboard"
                                    class="bg-dark-green hover:bg-[#064225] text-white px-8 py-3.5 rounded-full text-xs uppercase tracking-widest font-black transition-all shadow-xl shadow-[#0a5c36]/20 hover:-translate-y-0.5">
                                    Dashboard
                                </a>
                            @else
                                <a href="/user/dashboard"
                                    class="bg-dark-green hover:bg-[#064225] text-white px-8 py-3.5 rounded-full text-xs uppercase tracking-widest font-black transition-all shadow-xl shadow-[#0a5c36]/20 hover:-translate-y-0.5">
                                    Dashboard
                                </a>
                            @endif
                        @else
                            <a href="/login"
                                class="bg-dark-green hover:bg-[#064225] text-white px-8 py-3.5 rounded-full text-xs uppercase tracking-widest font-black transition-all shadow-xl shadow-[#0a5c36]/20 hover:-translate-y-0.5">
                                Masuk
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Header Section -->
        <section class="relative pt-40 pb-16 overflow-hidden hero-gradient">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10 text-center">
                <h1 class="text-4xl lg:text-6xl font-black text-dark-green leading-[1.1] mb-6 tracking-tight">
                    MITRA <span class="text-gold">PENYELAMAT</span> MAKANAN
                </h1>
                <p class="text-[16px] text-gray-500 font-bold max-w-xl mx-auto leading-relaxed">
                    Apresiasi bagi para pebisnis kuliner yang berkomitmen menyelamatkan makanan berlebih demi bumi dan kemanusiaan.
                </p>
            </div>
        </section>

        <!-- Filter & Search Section -->
        <section class="pb-16 bg-transparent relative z-30">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <form action="{{ route('mitra') }}" method="GET" x-data="{ open: false, selected: '{{ $jenis_usaha ? ucfirst($jenis_usaha) : 'Semua Kategori Usaha' }}', value: '{{ $jenis_usaha }}' }" x-ref="form" 
                    class="bg-white/80 backdrop-blur-md p-6 rounded-[2.5rem] shadow-xl shadow-gray-100/50 border border-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                        <!-- Search Input -->
                        <div class="md:col-span-6 relative">
                            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama usaha atau lokasi..."
                                class="w-full pl-14 pr-5 py-4 rounded-2xl border border-gray-100 focus:outline-none focus:border-[#1cb764] focus:ring-2 focus:ring-[#1cb764]/10 text-sm font-semibold transition-all">
                            <svg class="w-5 h-5 text-gray-400 absolute left-5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>

                        <!-- Custom Category Dropdown (AlpineJS) -->
                        <div class="md:col-span-4 relative">
                            <button type="button" id="category-dropdown-btn" @click="open = !open" 
                                class="w-full flex items-center justify-between pl-5 pr-4 py-4 rounded-2xl border border-gray-100 hover:border-[#1cb764] focus:outline-none text-sm font-semibold text-gray-700 bg-white transition-all shadow-sm">
                                <span x-text="selected"></span>
                                <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <input type="hidden" name="jenis_usaha" :value="value">
                            
                            <!-- Dropdown List -->
                            <div x-show="open" @click.outside="open = false" 
                                x-transition:enter="transition ease-out duration-100" 
                                x-transition:enter-start="opacity-0 scale-95" 
                                x-transition:enter-end="opacity-100 scale-100" 
                                x-transition:leave="transition ease-in duration-75" 
                                x-transition:leave-start="opacity-100 scale-100" 
                                x-transition:leave-end="opacity-0 scale-95" 
                                class="absolute z-50 w-full mt-2 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 max-h-60 overflow-y-auto"
                                style="display: none;">
                                <button type="button" dusk="category-option-all" @click="selected = 'Semua Kategori Usaha'; value = ''; open = false; $nextTick(() => $refs.form.submit())" 
                                    class="w-full text-left px-5 py-3.5 text-sm font-semibold text-gray-600 hover:bg-[#eefcf4] hover:text-dark-green transition-colors flex items-center justify-between">
                                    <span>Semua Kategori Usaha</span>
                                    <svg x-show="value === ''" class="w-4 h-4 text-[#1cb764]" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                                @foreach ($jenisUsahaList as $ju)
                                    <button type="button" dusk="category-option-{{ str_replace(' ', '-', strtolower($ju)) }}" @click="selected = '{{ ucfirst($ju) }}'; value = '{{ $ju }}'; open = false; $nextTick(() => $refs.form.submit())" 
                                        class="w-full text-left px-5 py-3.5 text-sm font-semibold text-gray-600 hover:bg-[#eefcf4] hover:text-dark-green transition-colors flex items-center justify-between"
                                        :class="value === '{{ $ju }}' ? 'bg-[#eefcf4] text-dark-green font-bold' : ''">
                                        <span>{{ ucfirst($ju) }}</span>
                                        <svg x-show="value === '{{ $ju }}'" class="w-4 h-4 text-[#1cb764]" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="md:col-span-2">
                            <button type="submit" dusk="search-submit-btn"
                                class="w-full bg-dark-green hover:bg-[#064225] text-white py-4 rounded-2xl text-xs uppercase tracking-widest font-black transition-all shadow-lg shadow-[#0a5c36]/20 hover:-translate-y-0.5">
                                Cari
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </section>

        <!-- Mitra Cards Section -->
        <section class="pb-24 bg-transparent">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-10">
                    @forelse ($mitras as $mitra)
                        @php
                            $profile = $mitra->unitBisnisProfile;
                            $namaUsaha = $profile->nama_usaha ?? $mitra->name;
                            $jenisUsaha = $profile->jenis_usaha ?? 'Usaha Makanan';
                            
                            // High quality fallbacks from Unsplash
                            $fallbackImgUrl = 'https://images.unsplash.com/photo-1498837167922-ddd27525d352?auto=format&fit=crop&q=80&w=600';
                            if (stripos($jenisUsaha, 'roti') !== false || stripos($jenisUsaha, 'bakery') !== false) {
                                $fallbackImgUrl = 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&q=80&w=600';
                            } elseif (stripos($jenisUsaha, 'restoran') !== false || stripos($jenisUsaha, 'cafe') !== false || stripos($jenisUsaha, 'kafe') !== false) {
                                $fallbackImgUrl = 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&q=80&w=600';
                            } elseif (stripos($jenisUsaha, 'swalayan') !== false || stripos($jenisUsaha, 'supermarket') !== false || stripos($jenisUsaha, 'toko') !== false) {
                                $fallbackImgUrl = 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&q=80&w=600';
                            } elseif (stripos($jenisUsaha, 'katering') !== false || stripos($jenisUsaha, 'catering') !== false) {
                                $fallbackImgUrl = 'https://images.unsplash.com/photo-1555244162-803834f70033?auto=format&fit=crop&q=80&w=600';
                            }
                            
                            // Check for custom profile photo first
                            $imgUrl = ($mitra->foto_profil) 
                                ? asset('storage/' . $mitra->foto_profil) 
                                : $fallbackImgUrl;
                        @endphp
                        <!-- Card -->
                        <div class="bg-white rounded-[2.5rem] overflow-hidden border border-gray-100 shadow-xl shadow-gray-100/50 hover:shadow-2xl hover:shadow-[#1cb764]/10 hover:-translate-y-3 transition-all duration-500 flex flex-col group">
                            <!-- Image Container -->
                            <div class="relative overflow-hidden h-56 rounded-t-[2.5rem]">
                                <img src="{{ $imgUrl }}" alt="{{ $namaUsaha }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                <!-- Subtle Gradient overlay -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                                <!-- Category Badge -->
                                <span class="absolute top-6 left-6 bg-[#eefcf4]/90 backdrop-blur-md text-[#0a2e1f] font-bold text-xs uppercase tracking-wider px-4 py-2.5 rounded-full border border-[#1cb764]/20 shadow-sm">
                                    {{ $jenisUsaha }}
                                </span>
                            </div>

                            <!-- Card Content -->
                            <div class="p-8 flex-grow flex flex-col justify-between">
                                <div>
                                    <h3 class="text-2xl font-black text-dark-green mb-3 group-hover:text-primary-green transition-colors leading-tight">
                                        {{ $namaUsaha }}
                                    </h3>
                                    <p class="text-[13px] font-bold text-gray-400 leading-relaxed uppercase tracking-wider mb-6 flex items-start gap-2.5">
                                        <svg class="w-5 h-5 text-primary-green shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <span>{{ $mitra->alamat ?? 'Lokasi tidak dicantumkan' }}</span>
                                    </p>
                                </div>

                                <a href="/login"
                                    class="w-full text-center bg-dark-green hover:bg-[#064225] text-white py-4 rounded-full text-xs uppercase tracking-widest font-black transition-all hover:scale-[1.02] shadow-md shadow-[#0a5c36]/15 hover:shadow-[#0a5c36]/35">
                                    Lihat Menu Aktif
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 text-center py-24 bg-white rounded-[3rem] border border-gray-100 shadow-xl shadow-gray-100/50">
                            <svg class="w-20 h-20 text-gray-300 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 9.172V5L8 4z"></path>
                            </svg>
                            <p class="text-gray-400 font-bold text-lg">Tidak ada mitra terdaftar yang cocok dengan pencarian Anda.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination Links -->
                <div class="mt-16">
                    {{ $mitras->links() }}
                </div>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer class="bg-white pt-24 pb-10 border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-12 lg:gap-8 mb-16">
                <!-- Branding -->
                <div class="col-span-1 md:col-span-5">
                    <img src="{{ asset('images/ShareBite.png') }}" alt="ShareBite"
                        class="h-10 mb-8 opacity-90 hover:opacity-100 transition-opacity">
                    <p class="text-[15px] text-gray-500 font-bold leading-relaxed max-w-sm">
                        Langkah nyata untuk memutus rantai sisa pangan, memberdayakan komunitas, dan menciptakan dampak
                        positif bagi bumi.
                    </p>
                </div>

                <!-- Platform Links -->
                <div class="col-span-1 md:col-span-3">
                    <h4 class="text-[11px] font-black text-[#1cb764] uppercase tracking-widest mb-8">Platform</h4>
                    <ul class="space-y-4 text-sm font-bold text-gray-500">
                        <li><a href="/tentang-kami" class="hover:text-dark-green transition-colors">Tentang Kami</a></li>
                        <li><a href="/register/unit-bisnis" class="hover:text-dark-green transition-colors">Donasi Makanan</a></li>
                        <li><a href="/register/individu" class="hover:text-dark-green transition-colors">Daftar Relawan</a></li>
                    </ul>
                </div>

                <!-- Hubungi Kami & Socials -->
                <div class="col-span-1 md:col-span-4">
                    <h4 class="text-[11px] font-black text-[#1cb764] uppercase tracking-widest mb-8">Hubungi Kami</h4>
                    <ul class="space-y-4 text-sm font-bold text-gray-500 mb-8">
                        <li><a href="https://mail.google.com/mail/?view=cm&fs=1&to=hello@sharebite.id" class="hover:text-dark-green transition-colors" target="_blank">Pusat Bantuan</a></li>
                        <li><a href="https://mail.google.com/mail/?view=cm&fs=1&to=hello@sharebite.id" class="hover:text-dark-green transition-colors" target="_blank">hello@sharebite.id</a></li>
                    </ul>
                </div>
            </div>

            <div class="text-center pt-10 border-t border-gray-100 text-xs font-bold text-gray-400">
                &copy; {{ date('Y') }} ShareBite. Seluruh Hak Cipta Dilindungi Undang-Undang.
            </div>
        </div>
    </footer>

</body>

</html>
