<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShareBite - Selamatkan Makanan, Selamatkan Bumi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fbf9; }
        .text-dark-green { color: #0a5c36; }
        .bg-dark-green { background-color: #0a5c36; }
        .text-primary-green { color: #1cb764; }
        .bg-primary-green { background-color: #1cb764; }
        .text-gold { color: #9b621e; }
        .bg-gold { background-color: #9b621e; }
        
        .hero-gradient {
            background: radial-gradient(circle at top right, rgba(28, 183, 100, 0.08) 0%, rgba(248, 251, 249, 0) 50%);
        }
    </style>
</head>
<body class="text-gray-800 antialiased relative">

    <!-- Navbar -->
    <nav class="fixed w-full z-50 bg-[#f8fbf9]/95 backdrop-blur-md border-b border-gray-100 transition-all duration-300" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex items-center justify-between h-24">
                <!-- Logo -->
                <a href="/" class="flex items-center group">
                    <img src="{{ asset('images/ShareBite.png') }}" alt="ShareBite Logo" class="h-9 transform group-hover:scale-105 transition-transform duration-300">
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-12">
                    <a href="/" class="text-[13px] font-black text-[#0a5c36] border-b-2 border-[#0a5c36] pb-1 uppercase tracking-widest">Home</a>
                    <a href="#" class="text-[13px] font-black text-gray-400 hover:text-[#0a5c36] transition-colors uppercase tracking-widest">Promo</a>
                    <a href="#" class="text-[13px] font-black text-gray-400 hover:text-[#0a5c36] transition-colors uppercase tracking-widest">Komunitas</a>
                    <a href="#" class="text-[13px] font-black text-gray-400 hover:text-[#0a5c36] transition-colors uppercase tracking-widest">Tentang Kami</a>
                </div>

                <!-- CTA Auth -->
                <div class="hidden md:flex items-center">
                    @auth
                        <a href="/login" class="bg-dark-green hover:bg-[#064225] text-white px-8 py-3.5 rounded-full text-xs uppercase tracking-widest font-black transition-all shadow-xl shadow-[#0a5c36]/20 hover:-translate-y-0.5">
                            Dashboard
                        </a>
                    @else
                        <a href="/login" class="bg-dark-green hover:bg-[#064225] text-white px-8 py-3.5 rounded-full text-xs uppercase tracking-widest font-black transition-all shadow-xl shadow-[#0a5c36]/20 hover:-translate-y-0.5">
                            Daftar / Masuk
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-36 pb-20 lg:pt-48 lg:pb-32 overflow-hidden hero-gradient">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <!-- Text Content -->
                <div class="max-w-2xl">
                    <h1 class="text-5xl lg:text-[4.5rem] font-black text-dark-green leading-[1.05] mb-6 tracking-tight">
                        SELAMATKAN<br>MAKANAN,<br>SELAMATKAN<br><span class="text-gold">BUMI.</span>
                    </h1>
                    <p class="text-[17px] text-gray-600 mb-10 leading-relaxed font-bold max-w-lg">
                        Bergabunglah bersama kami di ShareBite untuk menghentikan limbah makanan menjadi solusi bagi mereka yang membutuhkan. Langkah kecil Anda, harapan besar mereka.
                    </p>
                    <div class="flex flex-wrap items-center gap-4">
                        <a href="/register/unit-bisnis" class="bg-dark-green hover:bg-[#064225] text-white px-8 py-4 rounded-full text-sm font-black transition-all flex items-center gap-2 shadow-xl shadow-[#0a5c36]/20 hover:-translate-y-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            Donasi Makanan
                        </a>
                        <a href="/login" class="bg-gold hover:bg-[#855217] text-white px-8 py-4 rounded-full text-sm font-black transition-all flex items-center gap-2 shadow-xl shadow-[#9b621e]/20 hover:-translate-y-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            Cari Makanan
                        </a>
                    </div>
                </div>

                <!-- Hero Image (Real Food) -->
                <div class="relative lg:ml-auto">
                    <!-- Decor element -->
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-gold/10 rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-[#1cb764]/20 rounded-full blur-3xl"></div>
                    
                    <div class="relative rounded-[2.5rem] overflow-hidden shadow-2xl border-8 border-white/50 transform hover:scale-[1.02] transition-transform duration-500">
                        <!-- Badge -->
                        <div class="absolute top-6 right-6 bg-gold text-white w-20 h-20 rounded-full flex flex-col items-center justify-center font-black text-center shadow-lg transform rotate-12 z-10 border-4 border-white">
                            <span class="text-xs leading-tight">Fresh</span>
                            <span class="text-xs leading-tight">& Safe</span>
                        </div>
                        <img src="https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&q=80&w=800" alt="Delicious Food" class="w-full max-w-[500px] object-cover aspect-square">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-24 bg-white relative">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
                <h2 class="text-3xl lg:text-4xl font-black text-dark-green max-w-sm leading-tight">
                    Dampak Nyata Dari Langkah Kecil Kita.
                </h2>
                <a href="#" class="text-gold font-bold flex items-center gap-2 hover:gap-3 transition-all text-sm uppercase tracking-widest">
                    Lihat Semua <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <!-- Stat 1 -->
                <div class="bg-[#eef5f1] p-10 rounded-[2.5rem] relative overflow-hidden group hover:-translate-y-1 transition-all border border-[#1cb764]/10">
                    <div class="relative z-10">
                        <h3 class="text-5xl font-black text-dark-green mb-3">{{ number_format($totalPorsiTerselamatkan, 0, ',', '.') }}+</h3>
                        <p class="text-sm font-bold text-gray-500 tracking-wide">Makanan terselamatkan</p>
                    </div>
                    <svg class="w-32 h-32 text-[#1cb764]/10 absolute -bottom-6 -right-6 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M11 2v9a3 3 0 003 3v8h2v-8a3 3 0 003-3V2h-2v7a1 1 0 01-1 1h-2a1 1 0 01-1-1V2h-2zM5 2v10a3 3 0 003 3v7h2v-7a3 3 0 003-3V2H5zm2 2h4v8a1 1 0 01-1 1H8a1 1 0 01-1-1V4z"></path>
                    </svg>
                </div>
                
                <!-- Stat 2 -->
                <div class="bg-dark-green p-10 rounded-[2.5rem] relative overflow-hidden text-white group hover:-translate-y-1 transition-all shadow-2xl shadow-[#0a5c36]/20">
                    <div class="relative z-10">
                        <h3 class="text-5xl font-black mb-3">{{ $totalBeratKg }}<span class="text-3xl">kg</span></h3>
                        <p class="text-sm font-bold text-white/80 tracking-wide">Beban Makanan Terselamatkan</p>
                    </div>
                    <svg class="w-32 h-32 text-white/10 absolute -bottom-6 -right-6 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17 3H7c-1.1 0-2 .9-2 2v16l7-3 7 3V5c0-1.1-.9-2-2-2z"></path>
                    </svg>
                </div>

                <!-- Stat 3 -->
                <div class="bg-gold p-10 rounded-[2.5rem] relative overflow-hidden text-white group hover:-translate-y-1 transition-all shadow-2xl shadow-gold/20">
                    <div class="relative z-10">
                        <h3 class="text-5xl font-black mb-3">{{ number_format($totalPahlawan, 0, ',', '.') }}+</h3>
                        <p class="text-sm font-bold text-white/80 mb-8 tracking-wide">Pahlawan Bergabung</p>
                        <div class="flex -space-x-3">
                            @foreach($pahlawanFotos as $foto)
                                <img class="w-12 h-12 rounded-full border-2 border-gold object-cover shadow-sm bg-white" src="{{ asset('storage/' . $foto) }}" alt="Avatar Pahlawan">
                            @endforeach
                            <!-- Filler if < 4 -->
                            @for($i = count($pahlawanFotos); $i < 3; $i++)
                                <div class="w-12 h-12 rounded-full border-2 border-gold bg-white text-gold flex items-center justify-center shadow-sm">
                                    <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path></svg>
                                </div>
                            @endfor
                            <div class="w-12 h-12 rounded-full border-2 border-gold bg-white text-gold flex items-center justify-center text-xs font-black shadow-sm">+</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section (Fixed Timeline Layout) -->
    <section class="py-32 relative bg-[#f8fbf9]">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-24">
                <span class="bg-[#1cb764]/10 text-dark-green text-[11px] font-black tracking-widest uppercase px-5 py-2 rounded-full mb-6 inline-block">Alur Proses</span>
                <h2 class="text-3xl lg:text-[2.5rem] font-black text-dark-green tracking-tight">Bagaimana ShareBite Bekerja?</h2>
            </div>

            <div class="relative max-w-5xl mx-auto">
                <!-- Dotted Vertical Line -->
                <div class="hidden md:block absolute left-1/2 top-4 bottom-4 w-0 border-l-[3px] border-dashed border-[#1cb764]/30 transform -translate-x-1/2 z-0"></div>

                <!-- Step 1 -->
                <div class="flex flex-col md:flex-row items-center justify-center mb-24 relative z-10 w-full group">
                    <!-- Text Left -->
                    <div class="md:w-5/12 md:pr-16 text-center md:text-right mb-8 md:mb-0 order-2 md:order-1">
                        <h3 class="text-2xl font-black text-dark-green mb-4">Lacak & Kumpul</h3>
                        <p class="text-sm font-bold text-gray-500 leading-relaxed">Aktifkan notifikasi untuk mendapatkan update makanan berlebih dari gerai pangan di sekitar Anda. Semua dijamin masih layak & aman dikonsumsi.</p>
                    </div>
                    <!-- Center Icon -->
                    <div class="md:w-2/12 flex justify-center shrink-0 mb-6 md:mb-0 relative order-1 md:order-2">
                        <div class="w-16 h-16 bg-[#e2f1e8] rounded-full flex items-center justify-center text-lg font-black text-[#0a5c36] border-[6px] border-[#f8fbf9] shadow-md relative z-10 group-hover:scale-110 group-hover:bg-[#1cb764] group-hover:text-white transition-all duration-300">
                            01
                        </div>
                    </div>
                    <!-- Image Right -->
                    <div class="md:w-5/12 md:pl-16 flex justify-center md:justify-start order-3">
                        <div class="rounded-[2rem] w-full max-w-[280px] aspect-video bg-gray-200 overflow-hidden shadow-2xl border-4 border-white transform group-hover:rotate-2 transition-transform duration-500">
                            <img src="https://images.unsplash.com/photo-1490645935967-10de6ba17061?auto=format&fit=crop&q=80&w=600" alt="Lacak Makanan" class="w-full h-full object-cover">
                        </div>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="flex flex-col md:flex-row items-center justify-center mb-24 relative z-10 w-full group">
                    <!-- Image Left -->
                    <div class="md:w-5/12 md:pr-16 flex justify-center md:justify-end order-3 md:order-1">
                        <div class="rounded-[2rem] w-full max-w-[280px] aspect-video bg-gray-200 overflow-hidden shadow-2xl border-4 border-white transform group-hover:-rotate-2 transition-transform duration-500">
                            <img src="https://images.unsplash.com/photo-1464226184884-fa280b87c399?auto=format&fit=crop&q=80&w=600" alt="Verifikasi" class="w-full h-full object-cover">
                        </div>
                    </div>
                    <!-- Center Icon -->
                    <div class="md:w-2/12 flex justify-center shrink-0 mb-6 md:mb-0 relative order-1 md:order-2">
                        <div class="w-16 h-16 bg-[#e2f1e8] rounded-full flex items-center justify-center text-lg font-black text-[#0a5c36] border-[6px] border-[#f8fbf9] shadow-md relative z-10 group-hover:scale-110 group-hover:bg-[#1cb764] group-hover:text-white transition-all duration-300">
                            02
                        </div>
                    </div>
                    <!-- Text Right -->
                    <div class="md:w-5/12 md:pl-16 text-center md:text-left mb-8 md:mb-0 order-2 md:order-3">
                        <h3 class="text-2xl font-black text-dark-green mb-4">Verifikasi & Klaim</h3>
                        <p class="text-sm font-bold text-gray-500 leading-relaxed">Relawan kami akan memverifikasi keamanan makanan dengan saksama. Segera klaim pesanan dan dapatkan pickup PIN untuk mengambil makanan.</p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="flex flex-col md:flex-row items-center justify-center relative z-10 w-full group">
                    <!-- Text Left -->
                    <div class="md:w-5/12 md:pr-16 text-center md:text-right mb-8 md:mb-0 order-2 md:order-1">
                        <h3 class="text-2xl font-black text-dark-green mb-4">Distribusi & Senyuman</h3>
                        <p class="text-sm font-bold text-gray-500 leading-relaxed">Berikan PIN pengambilan ke mitra bisnis kami, dan selamat menikmati! Anda turut mengurangi emisi karbon sekaligus menghemat uang belanja.</p>
                    </div>
                    <!-- Center Icon -->
                    <div class="md:w-2/12 flex justify-center shrink-0 mb-6 md:mb-0 relative order-1 md:order-2">
                        <div class="w-16 h-16 bg-[#e2f1e8] rounded-full flex items-center justify-center text-lg font-black text-[#0a5c36] border-[6px] border-[#f8fbf9] shadow-md relative z-10 group-hover:scale-110 group-hover:bg-[#1cb764] group-hover:text-white transition-all duration-300">
                            03
                        </div>
                    </div>
                    <!-- Image Right -->
                    <div class="md:w-5/12 md:pl-16 flex justify-center md:justify-start order-3">
                        <div class="rounded-[2rem] w-full max-w-[280px] aspect-video bg-gray-200 overflow-hidden shadow-2xl border-4 border-white transform group-hover:rotate-2 transition-transform duration-500">
                            <img src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?auto=format&fit=crop&q=80&w=600" alt="Distribusi" class="w-full h-full object-cover">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Role Section -->
    <section class="bg-[#0a5c36] py-24 my-10">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-8">
                <!-- Bisnis Pangan -->
                <div class="bg-[#78a586] p-12 rounded-[2rem] text-white relative overflow-hidden group shadow-lg hover:-translate-y-1 transition-transform duration-300">
                    <svg class="absolute -right-10 -bottom-10 w-72 h-72 text-white/10 group-hover:scale-110 group-hover:-rotate-12 transition-transform duration-700" fill="currentColor" viewBox="0 0 24 24"><path d="M4 6h16v2H4zm2 4h12v12H6z"></path></svg>
                    
                    <div class="relative z-10">
                        <h3 class="text-[32px] font-black mb-5 leading-tight">Untuk Bisnis Pangan</h3>
                        <p class="text-[15px] text-white font-medium mb-10 leading-relaxed max-w-sm">Kurangi kerugian finansial dan tingkatkan profil ESG perusahaan Anda dengan mendonasikan atau menjual makanan sisa layak konsumsi.</p>
                        
                        <ul class="space-y-4 mb-14 text-[15px] font-bold text-white">
                            <li class="flex items-center gap-4"><svg class="w-6 h-6 text-[#0a5c36]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Sertifikat Keberlanjutan</li>
                            <li class="flex items-center gap-4"><svg class="w-6 h-6 text-[#0a5c36]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Minimalisir Kerugian & Optimalisasi Stok</li>
                        </ul>

                        <a href="/register/unit-bisnis" class="inline-block bg-white text-[#0a5c36] px-8 py-3.5 rounded-2xl text-[15px] font-black hover:bg-gray-50 transition-all shadow-md hover:shadow-lg">Gabung Sebagai Mitra</a>
                    </div>
                </div>

                <!-- Relawan / Individu -->
                <div class="bg-[#78a586] p-12 rounded-[2rem] text-white relative overflow-hidden group shadow-lg hover:-translate-y-1 transition-transform duration-300">
                    <svg class="absolute -right-10 -bottom-10 w-72 h-72 text-white/10 group-hover:scale-110 group-hover:rotate-12 transition-transform duration-700" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"></path></svg>
                    
                    <div class="relative z-10">
                        <h3 class="text-[32px] font-black mb-5 leading-tight">Pahlawan ShareBite</h3>
                        <p class="text-[15px] text-white font-medium mb-10 leading-relaxed max-w-sm">Gunakan waktu luang Anda untuk menjemput dan mengantar donasi. Jadilah bagian dari solusi nyata kelaparan.</p>
                        
                        <ul class="space-y-4 mb-14 text-[15px] font-bold text-white">
                            <li class="flex items-center gap-4"><svg class="w-6 h-6 text-[#9b621e]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Fleksibilitas Waktu</li>
                            <li class="flex items-center gap-4"><svg class="w-6 h-6 text-[#9b621e]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Terbuka untuk Individu & Komunitas</li>
                        </ul>

                        <a href="/register/individu" class="inline-block bg-[#9b621e] text-white px-10 py-3.5 rounded-2xl text-[15px] font-black hover:bg-[#855217] transition-all shadow-md hover:shadow-lg">Daftar Relawan</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Available Donations -->
    <section class="bg-white py-24 border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-6">
                <h2 class="text-3xl lg:text-[2.5rem] font-black text-dark-green tracking-tight">Donasi Tersedia Hari Ini</h2>
                <a href="/login" class="text-[#1cb764] font-bold flex items-center gap-2 hover:gap-3 transition-all text-sm uppercase tracking-widest">
                    Lihat Semua Menu <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                @forelse($menus as $menu)
                <div class="bg-white border-2 border-gray-50 rounded-[2rem] p-4 shadow-sm hover:shadow-2xl transition-all group duration-300">
                    <div class="relative w-full h-56 rounded-3xl overflow-hidden mb-6 bg-gray-100">
                        <!-- Label -->
                        <div class="absolute top-4 left-4 bg-[#1cb764] text-white text-[10px] font-black uppercase tracking-widest px-4 py-2 rounded-full z-10 shadow-lg">
                            Tersedia
                        </div>
                        <img src="{{ asset('storage/' . $menu->masterMakanan->foto) }}" alt="{{ $menu->masterMakanan->nama_makanan }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    </div>
                    <div class="px-3 pb-3">
                        <h3 class="text-xl font-black text-gray-800 mb-1.5 truncate">{{ $menu->masterMakanan->nama_makanan }}</h3>
                        <p class="text-sm font-bold text-gray-400 mb-6 flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"></path></svg>
                            {{ $menu->unitBisnis->nama_usaha }}
                        </p>
                        
                        <div class="flex items-center justify-between border-t-2 border-gray-50 pt-5">
                            <span class="text-xs font-black text-gold bg-gold/10 px-3 py-1.5 rounded-full">{{ $menu->stok_porsi }} Porsi Tersisa</span>
                            <div class="w-10 h-10 rounded-full bg-gray-50 border-2 border-gray-100 flex items-center justify-center text-gray-400 group-hover:bg-[#1cb764] group-hover:border-[#1cb764] group-hover:text-white transition-all cursor-pointer shadow-sm group-hover:shadow-[#1cb764]/30">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center py-20 bg-gray-50 rounded-[3rem] border-2 border-dashed border-gray-200">
                    <svg class="w-20 h-20 text-gray-300 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    <p class="text-gray-400 font-bold text-lg">Belum ada makanan yang tersedia hari ini.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Footer (Matched Mockup) -->
    <footer class="bg-white pt-24 pb-10 border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-12 lg:gap-8 mb-16">
                <!-- Branding -->
                <div class="col-span-1 md:col-span-5">
                    <img src="{{ asset('images/ShareBite.png') }}" alt="ShareBite" class="h-10 mb-8 opacity-90 hover:opacity-100 transition-opacity">
                    <p class="text-[15px] text-gray-500 font-bold leading-relaxed max-w-sm">
                        Langkah nyata untuk memutus rantai sisa pangan, memberdayakan komunitas, dan menciptakan dampak positif bagi bumi.
                    </p>
                </div>

                <!-- Platform Links -->
                <div class="col-span-1 md:col-span-3">
                    <h4 class="text-[11px] font-black text-[#1cb764] uppercase tracking-widest mb-8">Platform</h4>
                    <ul class="space-y-4 text-sm font-bold text-gray-500">
                        <li><a href="#" class="hover:text-dark-green transition-colors">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-dark-green transition-colors">Donasi Makanan</a></li>
                        <li><a href="#" class="hover:text-dark-green transition-colors">Daftar Relawan</a></li>
                    </ul>
                </div>

                <!-- Hubungi Kami & Socials -->
                <div class="col-span-1 md:col-span-4">
                    <h4 class="text-[11px] font-black text-[#1cb764] uppercase tracking-widest mb-8">Hubungi Kami</h4>
                    <ul class="space-y-4 text-sm font-bold text-gray-500 mb-8">
                        <li><a href="#" class="hover:text-dark-green transition-colors">Pusat Bantuan</a></li>
                        <li><a href="mailto:hello@sharebite.id" class="hover:text-dark-green transition-colors">hello@sharebite.id</a></li>
                    </ul>
                    <div class="flex items-center gap-4">
                        <a href="#" class="w-12 h-12 rounded-full bg-[#f8fbf9] flex items-center justify-center text-dark-green hover:bg-[#1cb764] hover:text-white transition-all shadow-sm">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c5.05-.5 9-4.76 9-9.95z"></path></svg>
                        </a>
                        <a href="#" class="w-12 h-12 rounded-full bg-[#f8fbf9] flex items-center justify-center text-dark-green hover:bg-[#1cb764] hover:text-white transition-all shadow-sm">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.05c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.52 8.52 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"></path></svg>
                        </a>
                        <a href="#" class="w-12 h-12 rounded-full bg-[#f8fbf9] flex items-center justify-center text-dark-green hover:bg-[#1cb764] hover:text-white transition-all shadow-sm">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="text-center pt-10 border-t border-gray-100 text-xs font-bold text-gray-400">
                &copy; {{ date('Y') }} ShareBite. Seluruh Hak Cipta Dilindungi Undang-Undang.
            </div>
        </div>
    </footer>

</body>
</html>
