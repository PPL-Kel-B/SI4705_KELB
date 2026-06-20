<?php

use Laravel\Dusk\Browser;

/**
 * Helper to get user-defined pause duration for slow-motion demo.
 * Default is 1500ms so actions are clearly visible during presentation.
 */
if (! function_exists('duskDelay')) {
    function duskDelay(): int {
        return (int) env('DUSK_PAUSE_MS', 3500);
    }
}

if (! function_exists('disableAnimations')) {
    function disableAnimations(Browser $browser) {
        $browser->script("
            const style = document.createElement('style');
            style.innerHTML = '*:not(html):not(body):not(#dusk-click-pointer):not(.dusk-highlighted) { transition: none !important; animation: none !important; } html { scroll-behavior: smooth !important; }';
            document.head.appendChild(style);
            document.querySelectorAll('.fade-up').forEach(el => el.classList.add('visible'));
        ");
    }
}

if (! function_exists('visitHome')) {
    function visitHome(Browser $browser) {
        $browser->visit('/');
        try {
            $browser->waitForText('SELAMATKAN', 5);
        } catch (\Exception $e) {
            $html = $browser->driver->getPageSource();
            file_put_contents(base_path('tests/Browser/debug_landing.html'), $html);
            $browser->screenshot('debug_landing');
            throw $e;
        }
        disableAnimations($browser);
    }
}

if (! function_exists('scrollAndHighlightClick')) {
    function scrollAndHighlightClick(Browser $browser, string $selector, string $color = '#1cb764') {
        $jsonSelector = json_encode($selector);
        $jsonColor = json_encode($color);
        $browser->script("
            (function() {
                const oldPointer = document.getElementById('dusk-click-pointer');
                if (oldPointer) oldPointer.remove();
                document.querySelectorAll('.dusk-highlighted').forEach(node => {
                    node.classList.remove('dusk-highlighted');
                    node.style.removeProperty('outline');
                    node.style.removeProperty('outline-offset');
                    node.style.removeProperty('box-shadow');
                    node.style.removeProperty('background-color');
                    node.style.removeProperty('color');
                    node.style.removeProperty('transition');
                });

                if (!document.getElementById('dusk-highlight-styles')) {
                    const style = document.createElement('style');
                    style.id = 'dusk-highlight-styles';
                    style.innerHTML = `
                        @keyframes duskPulse {
                            0% { transform: translateY(0) scale(1); }
                            50% { transform: translateY(-12px) scale(1.2); }
                            100% { transform: translateY(0) scale(1); }
                        }
                        #dusk-click-pointer {
                            animation: duskPulse 0.8s infinite ease-in-out !important;
                        }
                    `;
                    document.head.appendChild(style);
                }

                const selector = {$jsonSelector};
                const color = {$jsonColor};
                const el = document.querySelector(selector);
                if (el) {
                    const targetPosition = el.getBoundingClientRect().top + window.pageYOffset - (window.innerHeight / 2);
                    const startPosition = window.pageYOffset;
                    const distance = targetPosition - startPosition;
                    const duration = 1200;
                    let startTime = null;

                    function animateScroll(currentTime) {
                        if (startTime === null) startTime = currentTime;
                        const timeElapsed = currentTime - startTime;
                        
                        let t = timeElapsed / (duration / 2);
                        let run;
                        if (t < 1) {
                            run = distance / 2 * t * t + startPosition;
                        } else {
                            t--;
                            run = -distance / 2 * (t * (t - 2) - 1) + startPosition;
                        }

                        window.scrollTo(0, run);

                        if (timeElapsed < duration) {
                            requestAnimationFrame(animateScroll);
                        } else {
                            window.scrollTo(0, targetPosition);
                            
                            el.classList.add('dusk-highlighted');
                            el.style.setProperty('outline', '6px dashed ' + color, 'important');
                            el.style.setProperty('outline-offset', '4px', 'important');
                            el.style.setProperty('box-shadow', '0 0 30px ' + color, 'important');
                            el.style.setProperty('background-color', '#ffff99', 'important');
                            el.style.setProperty('color', '#000000', 'important');
                            el.style.setProperty('transition', 'all 0.3s ease', 'important');

                            const pointer = document.createElement('div');
                            pointer.id = 'dusk-click-pointer';
                            pointer.innerHTML = '👇';
                            pointer.style.position = 'absolute';
                            pointer.style.fontSize = '3.5rem';
                            pointer.style.zIndex = '999999';
                            pointer.style.pointerEvents = 'none';

                            const rect = el.getBoundingClientRect();
                            const topPos = rect.top + window.pageYOffset - 60;
                            const leftPos = rect.left + window.pageXOffset + (rect.width / 2) - 24;
                            pointer.style.top = topPos + 'px';
                            pointer.style.left = leftPos + 'px';

                            document.body.appendChild(pointer);

                            setTimeout(() => {
                                if (pointer) pointer.remove();
                                el.click();
                            }, 1300);
                        }
                    }

                    requestAnimationFrame(animateScroll);
                }
            })();
        ");
        $browser->pause(2800);
    }
}

if (! function_exists('scrollAndHighlightClickLink')) {
    function scrollAndHighlightClickLink(Browser $browser, string $linkText, string $color = '#1cb764') {
        $jsonLinkText = json_encode(strtolower(trim($linkText)));
        $jsonColor = json_encode($color);
        $browser->script("
            (function() {
                const oldPointer = document.getElementById('dusk-click-pointer');
                if (oldPointer) oldPointer.remove();
                document.querySelectorAll('.dusk-highlighted').forEach(node => {
                    node.classList.remove('dusk-highlighted');
                    node.style.removeProperty('outline');
                    node.style.removeProperty('outline-offset');
                    node.style.removeProperty('box-shadow');
                    node.style.removeProperty('background-color');
                    node.style.removeProperty('color');
                    node.style.removeProperty('transition');
                });

                if (!document.getElementById('dusk-highlight-styles')) {
                    const style = document.createElement('style');
                    style.id = 'dusk-highlight-styles';
                    style.innerHTML = `
                        @keyframes duskPulse {
                            0% { transform: translateY(0) scale(1); }
                            50% { transform: translateY(-12px) scale(1.2); }
                            100% { transform: translateY(0) scale(1); }
                        }
                        #dusk-click-pointer {
                            animation: duskPulse 0.8s infinite ease-in-out !important;
                        }
                    `;
                    document.head.appendChild(style);
                }

                const linkText = {$jsonLinkText};
                const color = {$jsonColor};
                const links = Array.from(document.querySelectorAll('a, button'));
                const el = links.find(el => el.textContent.trim().toLowerCase() === linkText);
                if (el) {
                    const targetPosition = el.getBoundingClientRect().top + window.pageYOffset - (window.innerHeight / 2);
                    const startPosition = window.pageYOffset;
                    const distance = targetPosition - startPosition;
                    const duration = 1200;
                    let startTime = null;

                    function animateScroll(currentTime) {
                        if (startTime === null) startTime = currentTime;
                        const timeElapsed = currentTime - startTime;
                        
                        let t = timeElapsed / (duration / 2);
                        let run;
                        if (t < 1) {
                            run = distance / 2 * t * t + startPosition;
                        } else {
                            t--;
                            run = -distance / 2 * (t * (t - 2) - 1) + startPosition;
                        }

                        window.scrollTo(0, run);

                        if (timeElapsed < duration) {
                            requestAnimationFrame(animateScroll);
                        } else {
                            window.scrollTo(0, targetPosition);
                            
                            el.classList.add('dusk-highlighted');
                            el.style.setProperty('outline', '6px dashed ' + color, 'important');
                            el.style.setProperty('outline-offset', '4px', 'important');
                            el.style.setProperty('box-shadow', '0 0 30px ' + color, 'important');
                            el.style.setProperty('background-color', '#ffff99', 'important');
                            el.style.setProperty('color', '#000000', 'important');
                            el.style.setProperty('transition', 'all 0.3s ease', 'important');

                            const pointer = document.createElement('div');
                            pointer.id = 'dusk-click-pointer';
                            pointer.innerHTML = '👇';
                            pointer.style.position = 'absolute';
                            pointer.style.fontSize = '3.5rem';
                            pointer.style.zIndex = '999999';
                            pointer.style.pointerEvents = 'none';

                            const rect = el.getBoundingClientRect();
                            const topPos = rect.top + window.pageYOffset - 60;
                            const leftPos = rect.left + window.pageXOffset + (rect.width / 2) - 24;
                            pointer.style.top = topPos + 'px';
                            pointer.style.left = leftPos + 'px';

                            document.body.appendChild(pointer);

                            setTimeout(() => {
                                if (pointer) pointer.remove();
                                el.click();
                            }, 1300);
                        }
                    }

                    requestAnimationFrame(animateScroll);
                }
            })();
        ");
        $browser->pause(2800);
    }
}

test('TC-LP-01: Verifikasi Tampilan Utama dan Elemen Penting Halaman Landing Page (Home)', function () {
    // 1. Makanan Terselamatkan
    $totalPorsiTerselamatkan = \App\Models\Pesanan::whereIn('status', ['selesai', 'siap_diambil', 'dibayar'])->sum('jumlah_porsi');
    $porsiText = number_format($totalPorsiTerselamatkan, 0, ',', '.') . '+';

    // 2. Beban Makanan Terselamatkan
    $totalBeratKgVal = \Illuminate\Support\Facades\DB::table('pesanans')
        ->join('menu_aktifs', 'pesanans.menu_aktif_id', '=', 'menu_aktifs.id')
        ->join('master_makanans', 'menu_aktifs.master_makanan_id', '=', 'master_makanans.id')
        ->whereIn('pesanans.status', ['selesai', 'siap_diambil', 'dibayar'])
        ->select(\Illuminate\Support\Facades\DB::raw('SUM(pesanans.jumlah_porsi * master_makanans.berat) as total_berat'))
        ->value('total_berat') ?? 0;
    $totalBeratKg = number_format($totalBeratKgVal, 1, ',', '.');
    $beratText = $totalBeratKg . 'kg';

    // 3. Pahlawan Bergabung
    $totalPahlawan = \App\Models\User::whereIn('role', ['unit_bisnis', 'komunitas', 'individu'])->count();
    $pahlawanText = number_format($totalPahlawan, 0, ',', '.') . '+';

    // Disconnect DB connection in test runner to prevent database lock for the web server
    \Illuminate\Support\Facades\DB::disconnect();

    $this->browse(function (Browser $browser) use ($porsiText, $beratText, $pahlawanText) {
        visitHome($browser);

        $browser->assertTitle('ShareBite - Selamatkan Makanan, Selamatkan Bumi')
            // Assert Navbar (using uppercase text as rendered on page)
            ->assertSee('HOME')
            ->assertSee('MITRA KAMI')
            ->assertSee('TENTANG KAMI')
            ->assertSee('MASUK')
            // Assert Hero Section
            ->assertSee('SELAMATKAN')
            ->assertSee('MAKANAN')
            ->assertSee('BUMI.')
            ->assertSee('Donasi Makanan')
            ->assertSee('Cari Makanan')
            // Assert Stats Section
            ->assertSee('Dampak Nyata Dari Langkah Kecil Kita.')
            ->assertSee($porsiText)
            ->assertSee('Makanan terselamatkan')
            ->assertSee($beratText)
            ->assertSee('Beban Makanan Terselamatkan')
            ->assertSee($pahlawanText)
            ->assertSee('Pahlawan Bergabung')
            // Assert How It Works Section
            ->assertSee('Bagaimana ShareBite Bekerja?')
            ->assertSee('Lacak & Kumpul')
            ->assertSee('Verifikasi & Klaim')
            ->assertSee('Distribusi & Senyuman')
            // Assert Role Section
            ->assertSee('Untuk Bisnis Pangan')
            ->assertSee('Pahlawan ShareBite')
            ->assertSee('Gabung Sebagai Mitra')
            ->assertSee('Daftar Relawan')
            // Assert Available Donations
            ->assertSee('Donasi Tersedia Hari Ini')
            // Assert Footer
            ->assertSee('PLATFORM')
            ->assertSee('HUBUNGI KAMI')
            ->assertSee('hello@sharebite.id')
            ->pause(duskDelay());
    });
});

test('TC-LP-02: Verifikasi Fungsionalitas Tombol CTA (Call-to-Action) Navigasi Internal di Halaman Landing Page', function () {
    $this->browse(function (Browser $browser) {
        // 1. Donasi Makanan link (Hero)
        visitHome($browser);
        scrollAndHighlightClickLink($browser, 'Donasi Makanan');
        $browser->waitForLocation('/register/unit-bisnis')
            ->assertPathIs('/register/unit-bisnis')
            ->assertTitle('Pendaftaran Unit Bisnis - ShareBite')
            ->assertSee('Informasi Bisnis')
            ->pause(duskDelay());

        // 2. Cari Makanan link (Hero)
        visitHome($browser);
        scrollAndHighlightClickLink($browser, 'Cari Makanan');
        $browser->waitForLocation('/login')
            ->assertPathIs('/login')
            ->assertSee('Selamat Datang')
            ->pause(duskDelay());

        // 3. Gabung Sebagai Mitra link (Roles)
        visitHome($browser);
        scrollAndHighlightClickLink($browser, 'Gabung Sebagai Mitra');
        $browser->waitForLocation('/register/unit-bisnis')
            ->assertPathIs('/register/unit-bisnis')
            ->assertTitle('Pendaftaran Unit Bisnis - ShareBite')
            ->pause(duskDelay());

        // 4. Daftar Relawan link (Roles)
        visitHome($browser);
        scrollAndHighlightClickLink($browser, 'Daftar Relawan');
        $browser->waitForLocation('/register/individu')
            ->assertPathIs('/register/individu')
            ->assertTitle('Pendaftaran Relawan (Individu) - ShareBite')
            ->assertSee('Identitas Individu')
            ->pause(duskDelay());

        // 5. Active Food Card green arrow button (redirects to login)
        visitHome($browser);
        $browser->assertSee('MARTABAK'); // verify active food card exists from TambahMenuAktifTest
        scrollAndHighlightClick($browser, '[dusk="active-food-card-arrow"]');
        $browser->waitForLocation('/login')
            ->assertPathIs('/login')
            ->assertSee('Selamat Datang')
            ->pause(duskDelay());

        // 6. Footer: Tentang Kami link
        visitHome($browser);
        scrollAndHighlightClick($browser, '[dusk="footer-about-link"]');
        $browser->waitForLocation('/tentang-kami')
            ->assertPathIs('/tentang-kami')
            ->pause(duskDelay());

        // 7. Footer: Donasi Makanan link
        visitHome($browser);
        scrollAndHighlightClick($browser, '[dusk="footer-donate-link"]');
        $browser->waitForLocation('/register/unit-bisnis')
            ->assertPathIs('/register/unit-bisnis')
            ->pause(duskDelay());

        // 8. Footer: Daftar Relawan link
        visitHome($browser);
        scrollAndHighlightClick($browser, '[dusk="footer-volunteer-link"]');
        $browser->waitForLocation('/register/individu')
            ->assertPathIs('/register/individu')
            ->pause(duskDelay());

        // 9. Footer: Pusat Bantuan & hello@sharebite.id (Assert Gmail redirect)
        visitHome($browser);
        $browser->assertAttribute('footer div.grid > div:nth-child(3) ul li:nth-child(1) a', 'href', 'https://mail.google.com/mail/?view=cm&fs=1&to=hello@sharebite.id')
            ->assertAttribute('footer div.grid > div:nth-child(3) ul li:nth-child(1) a', 'target', '_blank')
            ->assertAttribute('footer div.grid > div:nth-child(3) ul li:nth-child(2) a', 'href', 'https://mail.google.com/mail/?view=cm&fs=1&to=hello@sharebite.id')
            ->assertAttribute('footer div.grid > div:nth-child(3) ul li:nth-child(2) a', 'target', '_blank')
            ->pause(duskDelay());
    });
});

test('TC-LP-03: Verifikasi Halaman Mitra Kami dari Halaman Home (Navigasi, Pencarian, Filter, dan Tombol Detail)', function () {
    $this->browse(function (Browser $browser) {
        // 1. Navigasi dari Home ke Mitra Kami
        visitHome($browser);
        scrollAndHighlightClickLink($browser, 'Mitra Kami');
        $browser->waitForLocation('/mitra')
            ->assertPathIs('/mitra')
            ->assertSee('MITRA PENYELAMAT MAKANAN')
            ->pause(duskDelay());

        // 2. Verifikasi Mitra Terverifikasi Tampil, Pending Tidak Tampil
        $browser->assertSee('Jaki Munawaroh Bakery')
            ->assertDontSee('Lestari Food') // Lestari Food is in DB seeder but has no profile, so it must not be visible
            ->pause(duskDelay());

        // 3. Verifikasi Pencarian Mitra (Keyword Positif)
        $browser->typeSlowly('search', 'Jaki', 100)
            ->pause(duskDelay());
        scrollAndHighlightClick($browser, '[dusk="search-submit-btn"]');
        $browser->waitForLocation('/mitra')
            ->assertQueryStringHas('search', 'Jaki')
            ->assertSee('Jaki Munawaroh Bakery')
            ->pause(duskDelay());

        // 4. Verifikasi Pencarian Mitra (Keyword Negatif)
        scrollAndHighlightClickLink($browser, 'Mitra Kami');
        $browser->waitForLocation('/mitra')
            ->typeSlowly('search', 'Xyz Bakery', 100)
            ->pause(duskDelay());
        scrollAndHighlightClick($browser, '[dusk="search-submit-btn"]');
        $browser->waitForLocation('/mitra')
            ->assertQueryStringHas('search', 'Xyz Bakery')
            ->assertDontSee('Jaki Munawaroh Bakery')
            ->pause(duskDelay());

        // 5. Verifikasi Penyaringan Kategori Usaha (Dinamis berdasarkan database)
        $categories = \App\Models\UnitBisnisProfile::where('status_verifikasi', 'terverifikasi')
            ->whereNotNull('jenis_usaha')
            ->distinct()
            ->pluck('jenis_usaha');

        // Disconnect DB connection in test runner to prevent database lock for the web server
        \Illuminate\Support\Facades\DB::disconnect();

        scrollAndHighlightClickLink($browser, 'Mitra Kami');
        $browser->waitForLocation('/mitra');
        scrollAndHighlightClick($browser, '#category-dropdown-btn');
        $browser->waitForText('Semua Kategori Usaha');

        if ($categories->isNotEmpty()) {
            $firstCategory = $categories->first();
            $duskOption = 'category-option-' . str_replace(' ', '-', strtolower($firstCategory));

            $browser->waitForText(ucfirst($firstCategory))
                ->pause(duskDelay());
            scrollAndHighlightClick($browser, "[dusk=\"{$duskOption}\"]");
            $browser->waitForLocation('/mitra')
                ->assertQueryStringHas('jenis_usaha', $firstCategory)
                ->pause(duskDelay());

            // 6. Verifikasi Reset Filter Kategori Usaha
            scrollAndHighlightClick($browser, '#category-dropdown-btn');
            $browser->waitForText('Semua Kategori Usaha');
            scrollAndHighlightClick($browser, '[dusk="category-option-all"]');
            $browser->waitForLocation('/mitra')
                ->pause(duskDelay());
        }

        // 7. Verifikasi Klik Tombol "Lihat Menu Aktif" pada Card
        scrollAndHighlightClickLink($browser, 'Lihat Menu Aktif');
        $browser->waitForLocation('/login')
            ->assertPathIs('/login')
            ->assertSee('Selamat Datang')
            ->pause(duskDelay());
    });
});

test('TC-LP-04: Verifikasi Halaman Tentang Kami dari Halaman Home (Navigasi, Statistik, dan Tombol Register)', function () {
    // Hitung berat makanan terselamatkan secara dinamis dari database untuk asersi yang tepat
    $totalBeratKgVal = \Illuminate\Support\Facades\DB::table('pesanans')
        ->join('menu_aktifs', 'pesanans.menu_aktif_id', '=', 'menu_aktifs.id')
        ->join('master_makanans', 'menu_aktifs.master_makanan_id', '=', 'master_makanans.id')
        ->whereIn('pesanans.status', ['selesai', 'siap_diambil', 'dibayar'])
        ->select(\Illuminate\Support\Facades\DB::raw('SUM(pesanans.jumlah_porsi * master_makanans.berat) as total_berat'))
        ->value('total_berat') ?? 0;
    $totalBeratKg = number_format($totalBeratKgVal, 1, ',', '.') . 'kg';

    // Disconnect DB connection in test runner to prevent database lock for the web server
    \Illuminate\Support\Facades\DB::disconnect();

    $this->browse(function (Browser $browser) use ($totalBeratKg) {
        // 1. Navigasi dari Home ke Tentang Kami & Cek Teks & Statistik (Melalui Footer)
        visitHome($browser);
        scrollAndHighlightClick($browser, '[dusk="footer-about-link"]');
        $browser->waitForLocation('/tentang-kami')
            ->assertPathIs('/tentang-kami');

        disableAnimations($browser);

        $browser->assertSee('Ubah Sisa Pangan')
            ->assertSee('Jadi Senyuman')
            ->assertSee($totalBeratKg)
            ->pause(duskDelay());

        // 2. Verifikasi Tombol Donasi Makanan di halaman Tentang Kami
        scrollAndHighlightClickLink($browser, 'Donasi Makanan');
        $browser->waitForLocation('/register/unit-bisnis')
            ->assertPathIs('/register/unit-bisnis')
            ->assertTitle('Pendaftaran Unit Bisnis - ShareBite')
            ->assertSee('Informasi Bisnis')
            ->pause(duskDelay());

        // 3. Verifikasi Tombol Daftar Relawan di halaman Tentang Kami (Melalui Navbar)
        visitHome($browser);
        scrollAndHighlightClick($browser, '[dusk="nav-about-link"]');
        $browser->waitForLocation('/tentang-kami');

        disableAnimations($browser);

        scrollAndHighlightClickLink($browser, 'Daftar Relawan');
        $browser->waitForLocation('/register/individu')
            ->assertPathIs('/register/individu')
            ->assertTitle('Pendaftaran Relawan (Individu) - ShareBite')
            ->assertSee('Identitas Individu')
            ->pause(duskDelay());
    });
});
