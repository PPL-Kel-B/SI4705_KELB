<?php

use Laravel\Dusk\Browser;
use App\Models\User;
use App\Models\UnitBisnisProfile;

beforeEach(function () {
    // Clear old test records before running to prevent duplicates, but keep them after the test run finishes.
    User::whereIn('name', ['Lestari Bakery', 'Sari Cafe', 'Katering Rahasia'])->delete();
});

/**
 * Helper to get user-defined pause duration for slow-motion demo.
 * Default is 1500ms so actions are clearly visible during presentation.
 */
if (! function_exists('duskDelay')) {
    function duskDelay(): int {
        return (int) env('DUSK_PAUSE_MS', 3500);
    }
}

test('TC-LP-01: Verifikasi Tampilan Utama dan Elemen Penting Halaman Landing Page (Home)', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/')
            ->waitForText('SELAMATKAN')
            // Force animations to render
            ->script("document.querySelectorAll('.fade-up').forEach(el => el.classList.add('visible'));");

        $browser->assertTitle('ShareBite - Selamatkan Makanan, Selamatkan Bumi')
            // Assert Navbar
            ->assertSee('Home')
            ->assertSee('Mitra Kami')
            ->assertSee('Tentang Kami')
            ->assertSee('Masuk')
            // Assert Hero Section
            ->assertSee('SELAMATKAN')
            ->assertSee('MAKANAN')
            ->assertSee('BUMI.')
            ->assertSee('Donasi Makanan')
            ->assertSee('Cari Makanan')
            // Assert Stats Section
            ->assertSee('Dampak Nyata Dari Langkah Kecil Kita.')
            ->assertSee('Makanan terselamatkan')
            ->assertSee('Beban Makanan Terselamatkan')
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
            ->assertSee('Platform')
            ->assertSee('Hubungi Kami')
            ->assertSee('hello@sharebite.id')
            ->pause(duskDelay());
    });
});

test('TC-LP-02: Verifikasi Fungsionalitas Tombol CTA (Call-to-Action) Navigasi Internal di Halaman Landing Page', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/')
            ->waitForText('SELAMATKAN')
            ->clickLink('Donasi Makanan')
            ->waitForLocation('/register/unit-bisnis')
            ->assertPathIs('/register/unit-bisnis')
            ->assertTitle('Pendaftaran Unit Bisnis - ShareBite')
            ->assertSee('Informasi Bisnis')
            ->pause(duskDelay());

        $browser->visit('/')
            ->waitForText('SELAMATKAN')
            ->clickLink('Cari Makanan')
            ->waitForLocation('/login')
            ->assertPathIs('/login')
            ->assertSee('Selamat Datang')
            ->pause(duskDelay());

        $browser->visit('/')
            ->waitForText('SELAMATKAN')
            ->clickLink('Gabung Sebagai Mitra')
            ->waitForLocation('/register/unit-bisnis')
            ->assertPathIs('/register/unit-bisnis')
            ->assertTitle('Pendaftaran Unit Bisnis - ShareBite')
            ->pause(duskDelay());

        $browser->visit('/')
            ->waitForText('SELAMATKAN')
            ->clickLink('Daftar Relawan')
            ->waitForLocation('/register/individu')
            ->assertPathIs('/register/individu')
            ->assertTitle('Pendaftaran Relawan (Individu) - ShareBite')
            ->assertSee('Identitas Individu')
            ->pause(duskDelay());
    });
});

test('TC-LP-03: Verifikasi Navigasi Menu Utama di Navbar Landing Page (ke Mitra, Tentang Kami, dan Login)', function () {
    $this->browse(function (Browser $browser) {
        // 1. Navigate to Mitra Kami page via Navbar
        $browser->visit('/')
            ->clickLink('Mitra Kami')
            ->waitForLocation('/mitra')
            ->assertPathIs('/mitra')
            ->assertSee('MITRA PENYELAMAT MAKANAN')
            ->pause(duskDelay());

        // 2. Navigate to Tentang Kami page via Navbar
        $browser->visit('/')
            ->clickLink('Tentang Kami')
            ->waitForLocation('/tentang-kami')
            ->assertPathIs('/tentang-kami')
            ->assertSee('Ubah Sisa Pangan')
            ->assertSee('Jadi Senyuman')
            ->pause(duskDelay());

        // 3. Navigate to Login page via Navbar button "Masuk"
        $browser->visit('/')
            ->clickLink('Masuk')
            ->waitForLocation('/login')
            ->assertPathIs('/login')
            ->assertSee('Selamat Datang')
            ->pause(duskDelay());
    });
});

test('TC-LP-04: Verifikasi Keberadaan Animasi Scroll Reveal (.fade-up) di Halaman Landing Page', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/')
            ->assertPresent('.fade-up')
            ->pause(duskDelay());
    });
});

test('TC-MIT-01: Verifikasi menampilkan semua mitra terverifikasi pada load awal halaman Mitra', function () {
    // Seed test data in the database
    $user1 = User::factory()->create(['role' => 'unit_bisnis', 'name' => 'Lestari Bakery']);
    UnitBisnisProfile::create([
        'user_id' => $user1->id,
        'nama_usaha' => 'Lestari Bakery',
        'jenis_usaha' => 'Bakery',
        'status_verifikasi' => 'terverifikasi',
    ]);

    $user2 = User::factory()->create(['role' => 'unit_bisnis', 'name' => 'Sari Cafe']);
    UnitBisnisProfile::create([
        'user_id' => $user2->id,
        'nama_usaha' => 'Sari Cafe',
        'jenis_usaha' => 'Restoran',
        'status_verifikasi' => 'terverifikasi',
    ]);

    // Seed pending data (should NOT be visible)
    $user3 = User::factory()->create(['role' => 'unit_bisnis', 'name' => 'Katering Rahasia']);
    UnitBisnisProfile::create([
        'user_id' => $user3->id,
        'nama_usaha' => 'Katering Rahasia',
        'jenis_usaha' => 'Katering',
        'status_verifikasi' => 'pending',
    ]);

    $this->browse(function (Browser $browser) {
        $browser->visit('/mitra')
            ->assertSee('Lestari Bakery')
            ->assertSee('Sari Cafe')
            ->assertDontSee('Katering Rahasia')
            ->pause(duskDelay());
    });
});

test('TC-MIT-02: Verifikasi pencarian mitra menggunakan kata kunci nama mitra', function () {
    $user1 = User::factory()->create(['role' => 'unit_bisnis', 'name' => 'Lestari Bakery']);
    UnitBisnisProfile::create([
        'user_id' => $user1->id,
        'nama_usaha' => 'Lestari Bakery',
        'jenis_usaha' => 'Bakery',
        'status_verifikasi' => 'terverifikasi',
    ]);

    $user2 = User::factory()->create(['role' => 'unit_bisnis', 'name' => 'Sari Cafe']);
    UnitBisnisProfile::create([
        'user_id' => $user2->id,
        'nama_usaha' => 'Sari Cafe',
        'jenis_usaha' => 'Restoran',
        'status_verifikasi' => 'terverifikasi',
    ]);

    $this->browse(function (Browser $browser) {
        $browser->visit('/mitra')
            ->typeSlowly('search', 'Lestari', 100)
            ->pause(duskDelay())
            ->click('@search-submit-btn')
            ->waitForLocation('/mitra')
            ->assertQueryStringHas('search', 'Lestari')
            ->assertSee('Lestari Bakery')
            ->assertDontSee('Sari Cafe')
            ->pause(duskDelay());
    });
});

test('TC-MIT-03: Verifikasi pencarian mitra yang tidak terdaftar menghasilkan hasil kosong', function () {
    $user1 = User::factory()->create(['role' => 'unit_bisnis', 'name' => 'Lestari Bakery']);
    UnitBisnisProfile::create([
        'user_id' => $user1->id,
        'nama_usaha' => 'Lestari Bakery',
        'jenis_usaha' => 'Bakery',
        'status_verifikasi' => 'terverifikasi',
    ]);

    $this->browse(function (Browser $browser) {
        $browser->visit('/mitra')
            ->typeSlowly('search', 'Xyz Bakery', 100)
            ->pause(duskDelay())
            ->click('@search-submit-btn')
            ->waitForLocation('/mitra')
            ->assertQueryStringHas('search', 'Xyz Bakery')
            ->assertDontSee('Lestari Bakery')
            ->pause(duskDelay());
    });
});

test('TC-MIT-04: Verifikasi penyaringan daftar mitra berdasarkan kategori jenis usaha', function () {
    $user1 = User::factory()->create(['role' => 'unit_bisnis', 'name' => 'Lestari Bakery']);
    UnitBisnisProfile::create([
        'user_id' => $user1->id,
        'nama_usaha' => 'Lestari Bakery',
        'jenis_usaha' => 'Bakery',
        'status_verifikasi' => 'terverifikasi',
    ]);

    $user2 = User::factory()->create(['role' => 'unit_bisnis', 'name' => 'Sari Cafe']);
    UnitBisnisProfile::create([
        'user_id' => $user2->id,
        'nama_usaha' => 'Sari Cafe',
        'jenis_usaha' => 'Restoran',
        'status_verifikasi' => 'terverifikasi',
    ]);

    $this->browse(function (Browser $browser) {
        $browser->visit('/mitra')
            ->click('#category-dropdown-btn')
            ->waitForText('Restoran')
            ->pause(duskDelay())
            ->click('@category-option-restoran')
            ->waitForLocation('/mitra')
            ->assertQueryStringHas('jenis_usaha', 'Restoran')
            ->assertDontSee('Lestari Bakery')
            ->assertSee('Sari Cafe')
            ->pause(duskDelay());
    });
});

test('TC-MIT-05: Verifikasi reset filter kategori kembali ke Semua Kategori', function () {
    $user1 = User::factory()->create(['role' => 'unit_bisnis', 'name' => 'Lestari Bakery']);
    UnitBisnisProfile::create([
        'user_id' => $user1->id,
        'nama_usaha' => 'Lestari Bakery',
        'jenis_usaha' => 'Bakery',
        'status_verifikasi' => 'terverifikasi',
    ]);

    $user2 = User::factory()->create(['role' => 'unit_bisnis', 'name' => 'Sari Cafe']);
    UnitBisnisProfile::create([
        'user_id' => $user2->id,
        'nama_usaha' => 'Sari Cafe',
        'jenis_usaha' => 'Restoran',
        'status_verifikasi' => 'terverifikasi',
    ]);

    $this->browse(function (Browser $browser) {
        // Visit directly with category filter active
        $browser->visit('/mitra?jenis_usaha=Restoran')
            ->assertDontSee('Lestari Bakery')
            ->assertSee('Sari Cafe')
            ->pause(duskDelay())
            ->click('#category-dropdown-btn')
            ->waitForText('Semua Kategori')
            ->click('@category-option-all')
            ->waitForLocation('/mitra')
            ->assertSee('Lestari Bakery')
            ->assertSee('Sari Cafe')
            ->pause(duskDelay());
    });
});
