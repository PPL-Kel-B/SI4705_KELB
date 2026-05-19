<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - ShareBite</title>
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

        /* Fade-in animation on scroll */
        .fade-up {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.7s ease-out, transform 0.7s ease-out;
        }
        .fade-up.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>

<body class="text-gray-800 antialiased relative">

    {{-- ========================================== --}}
    {{-- NAVBAR — Copy Persis dari Landing Page --}}
    {{-- ========================================== --}}
    <nav class="fixed w-full z-50 bg-[#f8fbf9]/95 backdrop-blur-md border-b border-gray-100 transition-all duration-300"
        x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">
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
                    <a href="#"
                        class="text-[13px] font-black text-gray-400 hover:text-[#0a5c36] transition-colors uppercase tracking-widest">Promo</a>
                    <a href="#"
                        class="text-[13px] font-black text-gray-400 hover:text-[#0a5c36] transition-colors uppercase tracking-widest">Komunitas</a>
                    <a href="/tentang-kami"
                        class="text-[13px] font-black text-[#0a5c36] border-b-2 border-[#0a5c36] pb-1 uppercase tracking-widest">Tentang
                        Kami</a>
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

    {{-- ========================================== --}}
    {{-- HERO SECTION --}}
    {{-- ========================================== --}}
    <section class="relative pt-44 pb-20 lg:pt-52 lg:pb-28 overflow-hidden bg-white">
        <div class="max-w-5xl mx-auto px-6 lg:px-8 relative z-10 text-center">
            <div class="fade-up">
                <h1 class="text-4xl md:text-6xl lg:text-7xl font-black text-dark-green leading-[1.1] tracking-tight mb-8">
                    Ubah Sisa Pangan,<br>Jadi <span class="text-primary-green">Senyuman</span>.
                </h1>
                <p class="text-base md:text-lg text-gray-500 font-medium max-w-2xl mx-auto leading-relaxed mb-14">
                    ShareBite adalah gerakan revolusioner yang memutus rantai kelaparan di tengah kemubaziran.
                    Kami menghubungkan gerai pangan berlebih dengan mereka yang membutuhkan.
                </p>
            </div>

            <div class="fade-up rounded-[2.5rem] overflow-hidden max-w-4xl mx-auto shadow-2xl border-4 border-white group">
                <img src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?auto=format&fit=crop&q=80&w=1400&h=500"
                    alt="ShareBite Community"
                    class="w-full h-[260px] md:h-[400px] object-cover transform group-hover:scale-105 transition-transform duration-700">
            </div>
        </div>
    </section>

    {{-- ========================================== --}}
    {{-- APA ITU SHAREBITE --}}
    {{-- ========================================== --}}
    <section class="py-24 lg:py-32 bg-[#f8fbf9]">
        <div class="max-w-6xl mx-auto px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-16 items-center">
                <!-- Gambar -->
                <div class="grid grid-cols-2 gap-5 fade-up">
                    <div class="rounded-[1.5rem] overflow-hidden shadow-xl group hover:-translate-y-2 transition-transform duration-500">
                        <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&q=80&w=500&h=600"
                            alt="Makanan"
                            class="w-full h-72 object-cover transform group-hover:scale-110 transition-transform duration-700">
                    </div>
                    <div class="rounded-[1.5rem] overflow-hidden shadow-xl mt-10 group hover:-translate-y-2 transition-transform duration-500">
                        <img src="https://images.unsplash.com/photo-1464226184884-fa280b87c399?auto=format&fit=crop&q=80&w=500&h=600"
                            alt="Fresh food"
                            class="w-full h-72 object-cover transform group-hover:scale-110 transition-transform duration-700">
                    </div>
                </div>

                <!-- Teks -->
                <div class="fade-up">
                    <h2 class="text-3xl md:text-[2.5rem] font-black text-dark-green mb-6 leading-tight tracking-tight">Apa Itu ShareBite?</h2>
                    <p class="text-gray-500 font-medium leading-relaxed mb-5">
                        ShareBite adalah platform digital inovatif yang menjembatani kesenjangan antara surplus makanan
                        di gerai pangan dengan mereka yang membutuhkannya di komunitas lokal.
                    </p>
                    <p class="text-gray-500 font-medium leading-relaxed mb-10">
                        Dengan teknologi, kami menyederhanakan proses donasi makanan. Bisnis pangan tidak perlu lagi
                        membuang sisa produk yang masih layak, sementara relawan lokal dapat mendistribusikannya
                        ke tangan yang tepat.
                    </p>
                    <div class="flex flex-col gap-5">
                        <div class="flex items-center gap-4 group cursor-pointer">
                            <div class="w-11 h-11 rounded-full bg-[#e2f1e8] flex items-center justify-center shrink-0 group-hover:bg-[#1cb764] transition-colors duration-300">
                                <svg class="w-5 h-5 text-[#1cb764] group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-sm font-black text-gray-700 group-hover:text-[#0a5c36] transition-colors">Donasi makanan tanpa proses rumit</span>
                        </div>
                        <div class="flex items-center gap-4 group cursor-pointer">
                            <div class="w-11 h-11 rounded-full bg-[#e2f1e8] flex items-center justify-center shrink-0 group-hover:bg-[#1cb764] transition-colors duration-300">
                                <svg class="w-5 h-5 text-[#1cb764] group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-sm font-black text-gray-700 group-hover:text-[#0a5c36] transition-colors">Verifikasi keamanan oleh relawan terlatih</span>
                        </div>
                        <div class="flex items-center gap-4 group cursor-pointer">
                            <div class="w-11 h-11 rounded-full bg-[#e2f1e8] flex items-center justify-center shrink-0 group-hover:bg-[#1cb764] transition-colors duration-300">
                                <svg class="w-5 h-5 text-[#1cb764] group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-sm font-black text-gray-700 group-hover:text-[#0a5c36] transition-colors">Distribusi cepat dan transparan</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ========================================== --}}
    {{-- MISI & VISI --}}
    {{-- ========================================== --}}
    <section class="py-24 lg:py-32 bg-white">
        <div class="max-w-6xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16 fade-up">
                <h2 class="text-3xl md:text-[2.5rem] font-black text-dark-green mb-5 tracking-tight">Misi & Visi Kami</h2>
                <p class="text-gray-500 font-medium max-w-xl mx-auto">Komitmen kami untuk dunia tanpa kelaparan dan tanpa kemubaziran makanan.</p>
            </div>
            <div class="grid md:grid-cols-2 gap-8">
                <!-- Misi -->
                <div class="fade-up bg-dark-green rounded-[2rem] p-10 md:p-12 text-white relative overflow-hidden group hover:-translate-y-2 transition-transform duration-500 shadow-xl shadow-[#0a5c36]/20">
                    <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                    <div class="relative z-10">
                        <div class="w-14 h-14 rounded-2xl bg-white/10 flex items-center justify-center mb-8 group-hover:bg-[#1cb764] transition-colors duration-300">
                            <svg class="w-7 h-7 text-[#1cb764] group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-black mb-5">Misi Kami</h3>
                        <p class="text-white/80 leading-relaxed">
                            Membangun ekosistem donasi makanan yang paling mudah, aman, dan transparan bagi semua pihak.
                            Menjadikan penyelamatan makanan sebagai standar operasional bisnis pangan dan memutus rantai
                            kelaparan di tingkat lokal.
                        </p>
                    </div>
                </div>

                <!-- Visi -->
                <div class="fade-up bg-gold rounded-[2rem] p-10 md:p-12 text-white relative overflow-hidden group hover:-translate-y-2 transition-transform duration-500 shadow-xl shadow-[#9b621e]/20">
                    <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                    <div class="relative z-10">
                        <div class="w-14 h-14 rounded-2xl bg-white/10 flex items-center justify-center mb-8 group-hover:bg-white/20 transition-colors duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-black mb-5">Visi Kami</h3>
                        <p class="text-white/80 leading-relaxed">
                            Menciptakan dunia Zero Food Waste di mana tidak ada makanan layak yang berakhir di tempat
                            sampah sementara manusia masih kelaparan. Setiap porsi berharga, setiap senyuman berarti.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ========================================== --}}
    {{-- DAMPAK NYATA --}}
    {{-- ========================================== --}}
    <section class="py-24 lg:py-32 bg-[#f8fbf9]">
        <div class="max-w-6xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16 fade-up">
                <h2 class="text-3xl md:text-[2.5rem] font-black text-dark-green mb-5 tracking-tight">Dampak Nyata Kami</h2>
                <p class="text-gray-500 font-medium max-w-xl mx-auto">Setiap angka merepresentasikan kehidupan yang tersentuh dan makanan yang terselamatkan.</p>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="fade-up bg-white rounded-[1.5rem] p-8 text-center shadow-sm border border-gray-100 group hover:-translate-y-2 hover:shadow-xl hover:border-[#1cb764]/30 transition-all duration-500">
                    <h3 class="text-4xl font-black text-primary-green mb-3 group-hover:scale-110 transition-transform duration-300">100%</h3>
                    <p class="text-sm font-bold text-gray-500">Untuk Kemanusiaan</p>
                </div>
                <div class="fade-up bg-white rounded-[1.5rem] p-8 text-center shadow-sm border border-gray-100 group hover:-translate-y-2 hover:shadow-xl hover:border-[#0a5c36]/30 transition-all duration-500">
                    <h3 class="text-4xl font-black text-dark-green mb-3 group-hover:scale-110 transition-transform duration-300">24/7</h3>
                    <p class="text-sm font-bold text-gray-500">Platform Aktif</p>
                </div>
                <div class="fade-up bg-white rounded-[1.5rem] p-8 text-center shadow-sm border border-gray-100 group hover:-translate-y-2 hover:shadow-xl hover:border-[#9b621e]/30 transition-all duration-500">
                    <h3 class="text-4xl font-black text-gold mb-3 group-hover:scale-110 transition-transform duration-300">0kg</h3>
                    <p class="text-sm font-bold text-gray-500">Target Limbah</p>
                </div>
                <div class="fade-up bg-white rounded-[1.5rem] p-8 text-center shadow-sm border border-gray-100 group hover:-translate-y-2 hover:shadow-xl hover:border-[#1cb764]/30 transition-all duration-500">
                    <h3 class="text-4xl font-black text-primary-green mb-3 group-hover:scale-110 transition-transform duration-300">∞</h3>
                    <p class="text-sm font-bold text-gray-500">Senyuman</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ========================================== --}}
    {{-- CTA --}}
    {{-- ========================================== --}}
    <section class="py-24 lg:py-32 bg-white">
        <div class="max-w-4xl mx-auto px-6 lg:px-8">
            <div class="fade-up bg-dark-green rounded-[2.5rem] p-12 md:p-20 text-center text-white relative overflow-hidden shadow-2xl shadow-[#0a5c36]/20 group">
                <!-- Animated Decor -->
                <div class="absolute top-0 right-0 w-72 h-72 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-125 transition-transform duration-700"></div>
                <div class="absolute bottom-0 left-0 w-56 h-56 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/2 group-hover:scale-125 transition-transform duration-700"></div>

                <div class="relative z-10">
                    <h2 class="text-3xl md:text-5xl font-black mb-6 leading-tight tracking-tight">Siap Membuat<br>Perbedaan Nyata?</h2>
                    <p class="text-lg text-white/80 font-medium mb-12 max-w-lg mx-auto leading-relaxed">
                        Pilih peran Anda sekarang dan jadilah bagian dari revolusi pangan yang berkelanjutan bersama ShareBite.
                    </p>
                    <div class="flex flex-col sm:flex-row justify-center items-center gap-5">
                        <a href="/register/unit-bisnis"
                            class="w-full sm:w-auto bg-white text-dark-green px-10 py-4 rounded-full text-xs uppercase tracking-widest font-black hover:bg-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            Donasi Makanan
                        </a>
                        <a href="/register/individu"
                            class="w-full sm:w-auto border-2 border-white text-white px-10 py-4 rounded-full text-xs uppercase tracking-widest font-black hover:bg-white/15 hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2.5">
                            Daftar Relawan
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ========================================== --}}
    {{-- FOOTER — Copy Persis dari Landing Page --}}
    {{-- ========================================== --}}
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
                    <h4 class="text-[11px] font-black text-primary-green uppercase tracking-widest mb-8">Platform</h4>
                    <ul class="space-y-4 text-sm font-bold text-gray-500">
                        <li><a href="/tentang-kami" class="hover:text-dark-green transition-colors">Tentang Kami</a></li>
                        <li><a href="/register/unit-bisnis" class="hover:text-dark-green transition-colors">Donasi Makanan</a></li>
                        <li><a href="/register/individu" class="hover:text-dark-green transition-colors">Daftar Relawan</a></li>
                    </ul>
                </div>

                <!-- Hubungi Kami -->
                <div class="col-span-1 md:col-span-4">
                    <h4 class="text-[11px] font-black text-primary-green uppercase tracking-widest mb-8">Hubungi Kami</h4>
                    <ul class="space-y-4 text-sm font-bold text-gray-500">
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

    {{-- Scroll Reveal Animation Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry, index) => {
                    if (entry.isIntersecting) {
                        // Stagger animation for items in same section
                        setTimeout(() => {
                            entry.target.classList.add('visible');
                        }, index * 100);
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.15 });

            document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));
        });
    </script>

</body>

</html>
