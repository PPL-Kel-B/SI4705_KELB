<?php

use Laravel\Dusk\Browser;
use App\Models\User;
use App\Models\UnitBisnisProfile;
use Illuminate\Foundation\Testing\DatabaseTruncation;

// Use database truncation to ensure a clean state for Dusk tests.
uses(DatabaseTruncation::class);

/**
 * Helper to get user-defined pause duration for slow-motion demo.
 * Default is 1500ms so actions are clearly visible during presentation.
 */
if (! function_exists('duskDelay')) {
    function duskDelay(): int {
        return (int) env('DUSK_PAUSE_MS', 3500);
    }
}

test('TC-LP-01: Verifikasi navigasi dari Landing Page ke halaman Mitra Kami', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/')
            ->waitForText('SELAMATKAN')
            ->script("document.querySelectorAll('.fade-up').forEach(el => el.classList.add('visible'));");

        $browser->assertSee('SELAMATKAN')
            ->assertSee('MAKANAN')
            ->pause(duskDelay())
            ->clickLink('Mitra Kami')
            ->waitForLocation('/mitra')
            ->assertPathIs('/mitra')
            ->assertSee('MITRA PENYELAMAT MAKANAN')
            ->pause(duskDelay());
    });
});

test('TC-LP-02: Verifikasi navigasi dari Landing Page ke halaman Tentang Kami', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/')
            ->script("document.querySelectorAll('.fade-up').forEach(el => el.classList.add('visible'));");

        $browser->pause(duskDelay())
            ->clickLink('Tentang Kami')
            ->waitForLocation('/tentang-kami')
            ->assertPathIs('/tentang-kami');

        $browser->script("document.querySelectorAll('.fade-up').forEach(el => el.classList.add('visible'));");

        $browser->assertSee('Ubah Sisa Pangan')
            ->assertSee('Jadi Senyuman')
            ->pause(duskDelay());
    });
});

test('TC-LP-03: Verifikasi keberadaan animasi scroll reveal (.fade-up) di Landing Page', function () {
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

test('TC-LP-04: Verifikasi navigasi dari Landing Page ke halaman Login', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/')
            ->pause(duskDelay())
            ->clickLink('Masuk')
            ->waitForLocation('/login')
            ->assertPathIs('/login')
            ->assertSee('Selamat Datang')
            ->pause(duskDelay());
    });
});
