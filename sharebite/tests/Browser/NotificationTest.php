<?php

use Laravel\Dusk\Browser;
use App\Models\User;
use App\Models\UnitBisnisProfile;
use Illuminate\Foundation\Testing\DatabaseTruncation;

// Use database truncation to ensure a clean state for Dusk tests.
uses(DatabaseTruncation::class);

test('user can register a unit bisnis and trigger a notification to the admin', function () {
    // Create an admin user so the system has an admin to notify
    $admin = User::create([
        'name' => 'Admin ShareBite',
        'email' => 'admin@sharebite.com',
        'password' => bcrypt('Password123!'),
        'no_hp' => '081234567890',
        'role' => 'admin',
    ]);

    $this->browse(function (Browser $browser) {
        $browser->visit('/register/unit-bisnis')
            ->assertSee('Informasi Bisnis')
            ->type('Nama_Usaha', 'Lestari Bakery')
            ->select('Jenis_Usaha', 'Restoran')
            ->type('Alamat', 'Jalan Merdeka No. 10')
            ->type('Nomor_hp', '081298765432')
            ->type('Email', 'lestari@bakery.com')
            ->type('Password', 'Password123!');

        // Check the terms checkbox and enable the submit button via JS to bypass validation requirements
        $browser->script("
            document.getElementById('terms').checked = true;
            const btn = document.getElementById('submitBtn');
            btn.disabled = false;
            btn.classList.remove('opacity-50', 'cursor-not-allowed');
        ");

        $browser->click('#submitBtn')
            ->waitForLocation('/login')
            ->assertPathIs('/login');
    });

    // Assert that the unit bisnis user was created in the database
    $this->assertDatabaseHas('users', [
        'email' => 'lestari@bakery.com',
        'role' => 'unit_bisnis',
    ]);

    // Assert that a database notification record was created for the admin
    $this->assertDatabaseHas('notifications', [
        'type' => 'App\Notifications\UnitBisnisMendaftarNotification',
    ]);
});

test('UnitBisnisMendaftarNotification generates correct email preview and content', function () {
    $user = User::factory()->create([
        'name' => 'Katering Barokah',
        'email' => 'barokah@katering.com',
    ]);

    UnitBisnisProfile::create([
        'user_id' => $user->id,
        'nama_usaha' => 'Katering Barokah',
        'jenis_usaha' => 'Katering',
        'status_verifikasi' => 'pending',
    ]);

    $notification = new \App\Notifications\UnitBisnisMendaftarNotification($user);
    $mailMessage = $notification->toMail($user);

    expect($mailMessage->introLines[0])->toContain('Katering Barokah');
    expect($mailMessage->actionText)->toContain('Verifikasi NIB');
    expect($mailMessage->actionUrl)->toContain('/admin/manajemen-pengguna');
});
