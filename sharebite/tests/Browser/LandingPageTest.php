<?php

use Laravel\Dusk\Browser;
use App\Models\User;
use App\Models\UnitBisnisProfile;
use Illuminate\Foundation\Testing\DatabaseTruncation;

// Use database truncation to ensure a clean state for Dusk tests.
uses(DatabaseTruncation::class);

test('user can visit landing page and navigate to Mitra Kami', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/')
            ->script("document.querySelectorAll('.fade-up').forEach(el => el.classList.add('visible'));");

        $browser->assertSee('SELAMATKAN')
            ->assertSee('MAKANAN')
            ->assertSee('TENTANG KAMI')
            ->clickLink('Mitra Kami')
            ->waitForLocation('/mitra')
            ->assertPathIs('/mitra')
            ->assertSee('MITRA PENYELAMAT MAKANAN');
    });
});

test('user can visit landing page and navigate to Tentang Kami', function () {
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

test('user can search and filter mitras on Mitra Kami page', function () {
    // Seed test data in the database
    $user1 = User::factory()->create([
        'role' => 'unit_bisnis',
        'name' => 'Lestari Bakery',
        'email' => 'lestari@bakery.com',
    ]);
    UnitBisnisProfile::create([
        'user_id' => $user1->id,
        'nama_usaha' => 'Lestari Bakery',
        'jenis_usaha' => 'Bakery',
        'status_verifikasi' => 'terverifikasi',
    ]);

    $user2 = User::factory()->create([
        'role' => 'unit_bisnis',
        'name' => 'Sari Cafe',
        'email' => 'sari@cafe.com',
    ]);
    UnitBisnisProfile::create([
        'user_id' => $user2->id,
        'nama_usaha' => 'Sari Cafe',
        'jenis_usaha' => 'Restoran',
        'status_verifikasi' => 'terverifikasi',
    ]);

    $this->browse(function (Browser $browser) {
        $browser->visit('/mitra')
            ->assertSee('Lestari Bakery')
            ->assertSee('Sari Cafe')
            // Test search field
            ->type('search', 'Lestari')
            ->click('@search-submit-btn')
            ->waitForLocation('/mitra')
            ->assertQueryStringHas('search', 'Lestari')
            ->assertSee('Lestari Bakery')
            ->assertDontSee('Sari Cafe')
            // Reset to /mitra
            ->visit('/mitra')
            // Test Custom Category Dropdown opening
            ->click('#category-dropdown-btn')
            ->waitForText('Restoran')
            // Click Restoran option using Dusk selector
            ->click('@category-option-restoran')
            ->waitForLocation('/mitra')
            ->assertQueryStringHas('jenis_usaha', 'Restoran')
            ->assertDontSee('Lestari Bakery')
            ->assertSee('Sari Cafe');
    });
});
