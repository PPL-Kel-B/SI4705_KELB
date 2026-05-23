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
    </style>
</head>

<body class="text-gray-800 antialiased relative">

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
                        <a href="/login"
                            class="bg-dark-green hover:bg-[#064225] text-white px-8 py-3.5 rounded-full text-xs uppercase tracking-widest font-black transition-all shadow-xl shadow-[#0a5c36]/20 hover:-translate-y-0.5">
                            Dashboard
                        </a>
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
    <section class="relative pt-36 pb-12 overflow-hidden hero-gradient">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10 text-center">
            <h1 class="text-4xl lg:text-5xl font-black text-dark-green leading-tight mb-4 tracking-tight">
                MITRA <span class="text-gold">PENYELAMAT</span> MAKANAN
            </h1>
            <p class="text-[16px] text-gray-500 font-bold max-w-xl mx-auto leading-relaxed">
                Mereka yang berkomitmen memutus rantai limbah pangan dan mendukung kebaikan sosial bersama ShareBite.
            </p>
        </div>
    </section>

    <!-- Filter & Search Section -->
    <section class="pb-10 bg-transparent">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <form action="{{ route('mitra') }}" method="GET" class="bg-white p-6 rounded-[2rem] shadow-xl shadow-gray-100 border border-gray-50">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                    <!-- Search Input -->
                    <div class="md:col-span-6 relative">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama usaha atau lokasi..."
                            class="w-full pl-12 pr-4 py-4 rounded-2xl border border-gray-100 focus:outline-none focus:border-[#1cb764] focus:ring-1 focus:ring-[#1cb764] text-sm font-semibold transition-all">
                        <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>

                    <!-- Category Dropdown -->
                    <div class="md:col-span-4">
                        <select name="jenis_usaha"
                            class="w-full px-4 py-4 rounded-2xl border border-gray-100 focus:outline-none focus:border-[#1cb764] text-sm font-semibold transition-all appearance-none bg-no-repeat bg-[right_1rem_center] bg-[length:1.25em_1.25em] bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2020%2020%22%20fill%3D%22none%22%3E%3Cpath%20d%3D%22M7%209l3%203%203-3%22%20stroke%3D%22%236b7280%22%20stroke-width%3D%221.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%2F%3E%3C%2Fsvg%3E')]">
                            <option value="">Semua Kategori Usaha</option>
                            @foreach ($jenisUsahaList as $ju)
                                <option value="{{ $ju }}" {{ $jenis_sign = ($jenis_usaha == $ju) ? 'selected' : '' }}>
                                    {{ ucfirst($ju) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Submit Button -->
                    <div class="md:col-span-2">
                        <button type="submit"
                            class="w-full bg-dark-green hover:bg-[#064225] text-white py-4 rounded-2xl text-xs uppercase tracking-widest font-black transition-all shadow-md shadow-[#0a5c36]/10">
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
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse ($mitras as $mitra)
                    @php
                        $profile = $mitra->unitBisnisProfile;
                        $namaUsaha = $profile->nama_usaha ?? $mitra->name;
                        $jenisUsaha = $profile->jenis_usaha ?? 'Usaha Makanan';
                        
                        // Select high quality unsplash image depending on business type
                        $imgUrl = 'https://images.unsplash.com/photo-1498837167922-ddd27525d352?auto=format&fit=crop&q=80&w=600';
                        if (stripos($jenisUsaha, 'roti') !== false || stripos($jenisUsaha, 'bakery') !== false) {
                            $imgUrl = 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&q=80&w=600';
                        } elseif (stripos($jenisUsaha, 'restoran') !== false || stripos($jenisUsaha, 'cafe') !== false || stripos($jenisUsaha, 'kafe') !== false) {
                            $imgUrl = 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&q=80&w=600';
                        } elseif (stripos($jenisUsaha, 'swalayan') !== false || stripos($jenisUsaha, 'supermarket') !== false || stripos($jenisUsaha, 'toko') !== false) {
                            $imgUrl = 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&q=80&w=600';
                        } elseif (stripos($jenisUsaha, 'katering') !== false || stripos($jenisUsaha, 'catering') !== false) {
                            $imgUrl = 'https://images.unsplash.com/photo-1555244162-803834f70033?auto=format&fit=crop&q=80&w=600';
                        }
                    @endphp
                    <!-- Card -->
                    <div class="bg-white rounded-[2.5rem] overflow-hidden border border-gray-100 shadow-lg shadow-gray-100/50 hover:-translate-y-2 transition-all duration-300 flex flex-col group">
                        <!-- Image Container -->
                        <div class="relative overflow-hidden h-52">
                            <img src="{{ $imgUrl }}" alt="{{ $namaUsaha }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <!-- Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                            <!-- Category Badge -->
                            <span class="absolute top-6 left-6 bg-[#eefcf4] text-[#0a2e1f] font-bold text-xs uppercase tracking-wider px-4 py-2 rounded-full border border-[#1cb764]/20 shadow-sm">
                                {{ $jenisUsaha }}
                            </span>
                        </div>

                        <!-- Card Content -->
                        <div class="p-8 flex-grow flex flex-col justify-between">
                            <div>
                                <h3 class="text-xl font-bold text-dark-green mb-3 group-hover:text-[#1cb764] transition-colors">
                                    {{ $namaUsaha }}
                                </h3>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-[#1cb764]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ $mitra->alamat ?? 'Lokasi tidak dicantumkan' }}
                                </p>
                            </div>

                            <a href="/login"
                                class="mt-6 block text-center bg-dark-green hover:bg-[#064225] text-white py-3.5 rounded-full text-xs uppercase tracking-widest font-black transition-all shadow-md shadow-[#0a5c36]/10">
                                Lihat Menu Aktif
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-20 bg-white rounded-[3rem] border border-gray-100 shadow-sm">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        <li><a href="#" class="hover:text-dark-green transition-colors">Pusat Bantuan</a></li>
                        <li><a href="mailto:hello@sharebite.id" class="hover:text-dark-green transition-colors">hello@sharebite.id</a></li>
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
