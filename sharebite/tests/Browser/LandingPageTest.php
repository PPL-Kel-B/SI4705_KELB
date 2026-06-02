<?php

use Laravel\Dusk\Browser;
use App\Models\User;
use App\Models\UnitBisnisProfile;
use Illuminate\Foundation\Testing\DatabaseTruncation;

// Use database truncation to ensure a clean state for Dusk tests.
uses(DatabaseTruncation::class);

test('user can visit landing page and navigate to Mitra Kami (TC-LP-01)', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/')
            ->waitForText('SELAMATKAN')
            ->script("document.querySelectorAll('.fade-up').forEach(el => el.classList.add('visible'));");

        $browser->assertSee('SELAMATKAN')
            ->assertSee('MAKANAN')
            ->clickLink('Mitra Kami')
            ->waitForLocation('/mitra')
            ->assertPathIs('/mitra')
            ->assertSee('MITRA PENYELAMAT MAKANAN');
    });
});

test('user can visit landing page and navigate to Tentang Kami (TC-LP-02)', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/')
            ->script("document.querySelectorAll('.fade-up').forEach(el => el.classList.add('visible'));");

        $browser->clickLink('Tentang Kami')
            ->waitForLocation('/tentang-kami')
            ->assertPathIs('/tentang-kami')
            ->script("document.querySelectorAll('.fade-up').forEach(el => el.classList.add('visible'));");

        $browser->assertSee('Ubah Sisa Pangan')
            ->assertSee('Jadi Senyuman');
    });
});

test('scroll reveal animation on landing page (TC-LP-03)', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/')
            ->assertPresent('.fade-up');
    });
});

test('user can see all verified mitras on initial load (TC-MIT-01)', function () {
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
            ->assertDontSee('Katering Rahasia');
    });
});

test('user can search mitras by keyword (TC-MIT-02)', function () {
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
            ->type('search', 'Lestari')
            ->click('@search-submit-btn')
            ->waitForLocation('/mitra')
            ->assertQueryStringHas('search', 'Lestari')
            ->assertSee('Lestari Bakery')
            ->assertDontSee('Sari Cafe');
    });
});

test('user gets no results when searching for non-existent mitras (TC-MIT-03)', function () {
    $user1 = User::factory()->create(['role' => 'unit_bisnis', 'name' => 'Lestari Bakery']);
    UnitBisnisProfile::create([
        'user_id' => $user1->id,
        'nama_usaha' => 'Lestari Bakery',
        'jenis_usaha' => 'Bakery',
        'status_verifikasi' => 'terverifikasi',
    ]);

    $this->browse(function (Browser $browser) {
        $browser->visit('/mitra')
            ->type('search', 'Xyz Bakery')
            ->click('@search-submit-btn')
            ->waitForLocation('/mitra')
            ->assertQueryStringHas('search', 'Xyz Bakery')
            ->assertDontSee('Lestari Bakery');
    });
});

test('user can filter mitras by category (TC-MIT-04)', function () {
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
            ->click('@category-option-restoran')
            ->waitForLocation('/mitra')
            ->assertQueryStringHas('jenis_usaha', 'Restoran')
            ->assertDontSee('Lestari Bakery')
            ->assertSee('Sari Cafe');
    });
});

test('user can reset category filter to Semua Kategori (TC-MIT-05)', function () {
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
            ->click('#category-dropdown-btn')
            ->waitForText('Semua Kategori')
            ->click('@category-option-all')
            ->waitForLocation('/mitra')
            ->assertSee('Lestari Bakery')
            ->assertSee('Sari Cafe');
    });
});

test('user can visit landing page and navigate to Login page (TC-LP-04)', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/')
            ->clickLink('Masuk')
            ->waitForLocation('/login')
            ->assertPathIs('/login')
            ->assertSee('Selamat Datang');
    });
});
