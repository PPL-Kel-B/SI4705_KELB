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
            style.innerHTML = '* { transition: none !important; animation: none !important; }';
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
        $browser->click('a[href="/register/unit-bisnis"].bg-dark-green')
            ->waitForLocation('/register/unit-bisnis')
            ->assertPathIs('/register/unit-bisnis')
            ->assertTitle('Pendaftaran Unit Bisnis - ShareBite')
            ->assertSee('Informasi Bisnis')
            ->pause(duskDelay());

        // 2. Cari Makanan link (Hero)
        visitHome($browser);
        $browser->click('a[href="/login"].bg-gold')
            ->waitForLocation('/login')
            ->assertPathIs('/login')
            ->assertSee('Selamat Datang')
            ->pause(duskDelay());

        // 3. Gabung Sebagai Mitra link (Roles)
        visitHome($browser);
        $browser->click('a[href="/register/unit-bisnis"].bg-white')
            ->waitForLocation('/register/unit-bisnis')
            ->assertPathIs('/register/unit-bisnis')
            ->assertTitle('Pendaftaran Unit Bisnis - ShareBite')
            ->pause(duskDelay());

        // 4. Daftar Relawan link (Roles)
        visitHome($browser);
        $browser->click('a[href="/register/individu"]')
            ->waitForLocation('/register/individu')
            ->assertPathIs('/register/individu')
            ->assertTitle('Pendaftaran Relawan (Individu) - ShareBite')
            ->assertSee('Identitas Individu')
            ->pause(duskDelay());

        // 5. Active Food Card green arrow button (redirects to login)
        visitHome($browser);
        $browser->assertSee('MARTABAK') // verify active food card exists from TambahMenuAktifTest
            ->click('.grid.md\:grid-cols-3 a[href="/login"]')
            ->waitForLocation('/login')
            ->assertPathIs('/login')
            ->assertSee('Selamat Datang')
            ->pause(duskDelay());

        // 6. Footer: Tentang Kami link
        visitHome($browser);
        $browser->click('footer a[href="/tentang-kami"]')
            ->waitForLocation('/tentang-kami')
            ->assertPathIs('/tentang-kami')
            ->pause(duskDelay());

        // 7. Footer: Donasi Makanan link
        visitHome($browser);
        $browser->click('footer a[href="/register/unit-bisnis"]')
            ->waitForLocation('/register/unit-bisnis')
            ->assertPathIs('/register/unit-bisnis')
            ->pause(duskDelay());

        // 8. Footer: Daftar Relawan link
        visitHome($browser);
        $browser->click('footer a[href="/register/individu"]')
            ->waitForLocation('/register/individu')
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
        $browser->clickLink('Mitra Kami')
            ->waitForLocation('/mitra')
            ->assertPathIs('/mitra')
            ->assertSee('MITRA PENYELAMAT MAKANAN')
            ->pause(duskDelay());

        // 2. Verifikasi Mitra Terverifikasi Tampil, Pending Tidak Tampil
        $browser->assertSee('Jaki Munawaroh Bakery')
            ->assertDontSee('Lestari Food') // Lestari Food is in DB seeder but has no profile, so it must not be visible
            ->pause(duskDelay());

        // 3. Verifikasi Pencarian Mitra (Keyword Positif)
        $browser->typeSlowly('search', 'Jaki', 100)
            ->pause(duskDelay())
            ->click('@search-submit-btn')
            ->waitForLocation('/mitra')
            ->assertQueryStringHas('search', 'Jaki')
            ->assertSee('Jaki Munawaroh Bakery')
            ->pause(duskDelay());

        // 4. Verifikasi Pencarian Mitra (Keyword Negatif)
        $browser->visit('/mitra')
            ->typeSlowly('search', 'Xyz Bakery', 100)
            ->pause(duskDelay())
            ->click('@search-submit-btn')
            ->waitForLocation('/mitra')
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

        $browser->visit('/mitra')
            ->click('#category-dropdown-btn')
            ->waitForText('Semua Kategori Usaha');

        if ($categories->isNotEmpty()) {
            $firstCategory = $categories->first();
            $duskOption = 'category-option-' . str_replace(' ', '-', strtolower($firstCategory));

            $browser->waitForText(ucfirst($firstCategory))
                ->pause(duskDelay())
                ->click("@{$duskOption}")
                ->waitForLocation('/mitra')
                ->assertQueryStringHas('jenis_usaha', $firstCategory)
                ->pause(duskDelay());

            // 6. Verifikasi Reset Filter Kategori Usaha
            $browser->click('#category-dropdown-btn')
                ->waitForText('Semua Kategori Usaha')
                ->click('@category-option-all')
                ->waitForLocation('/mitra')
                ->pause(duskDelay());
        }

        // 7. Verifikasi Klik Tombol "Lihat Menu Aktif" pada Card
        $browser->clickLink('Lihat Menu Aktif')
            ->waitForLocation('/login')
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
        // 1. Navigasi dari Home ke Tentang Kami & Cek Teks & Statistik
        visitHome($browser);
        $browser->clickLink('Tentang Kami')
            ->waitForLocation('/tentang-kami')
            ->assertPathIs('/tentang-kami');

        disableAnimations($browser);

        $browser->assertSee('Ubah Sisa Pangan')
            ->assertSee('Jadi Senyuman')
            ->assertSee($totalBeratKg)
            ->pause(duskDelay());

        // 2. Verifikasi Tombol Donasi Makanan di halaman Tentang Kami
        $browser->click('a[href="/register/unit-bisnis"]')
            ->waitForLocation('/register/unit-bisnis')
            ->assertPathIs('/register/unit-bisnis')
            ->assertTitle('Pendaftaran Unit Bisnis - ShareBite')
            ->assertSee('Informasi Bisnis')
            ->pause(duskDelay());

        // 3. Verifikasi Tombol Daftar Relawan di halaman Tentang Kami
        $browser->visit('/tentang-kami')
            ->waitForLocation('/tentang-kami');

        disableAnimations($browser);

        $browser->click('a[href="/register/individu"]')
            ->waitForLocation('/register/individu')
            ->assertPathIs('/register/individu')
            ->assertTitle('Pendaftaran Relawan (Individu) - ShareBite')
            ->assertSee('Identitas Individu')
            ->pause(duskDelay());
    });
});
