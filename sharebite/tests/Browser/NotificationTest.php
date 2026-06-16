<?php

use Laravel\Dusk\Browser;
use App\Models\User;
use App\Models\UnitBisnisProfile;
use App\Models\MasterMakanan;
use App\Models\MenuAktif;
use App\Models\Pesanan;
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

test('TC-REG-01: Verifikasi pendaftaran unit bisnis baru memicu notifikasi admin', function () {
    // Create an admin user so the system has an admin to notify
    $admin = User::create([
        'name' => 'Admin ShareBite',
        'email' => 'faridmunadhil12@gmail.com',
        'password' => bcrypt('Password123!'),
        'no_hp' => '081234567890',
        'role' => 'admin',
    ]);

    $this->browse(function (Browser $browser) {
        $browser->visit('/register/unit-bisnis')
            ->assertSee('Informasi Bisnis')
            ->pause(duskDelay())
            ->typeSlowly('Nama_Usaha', 'Lestari Bakery', 100)
            ->select('Jenis_Usaha', 'Restoran')
            ->typeSlowly('Alamat', 'Jalan Merdeka No. 10', 100)
            ->typeSlowly('Nomor_hp', '081298765432', 100)
            ->typeSlowly('Email', 'lestari@bakery.com', 100)
            ->typeSlowly('Password', 'Password123!', 100)
            ->pause(duskDelay());

        // Check the terms checkbox and enable the submit button via JS to bypass validation requirements
        $browser->script("
            document.getElementById('terms').checked = true;
            const btn = document.getElementById('submitBtn');
            btn.disabled = false;
            btn.classList.remove('opacity-50', 'cursor-not-allowed');
        ");

        $browser->pause(duskDelay())
            ->click('#submitBtn')
            ->waitForLocation('/login')
            ->assertPathIs('/login')
            ->pause(duskDelay());
    });

    // Assert that the unit bisnis user was created in the database
    $this->assertDatabaseHas('users', [
        'email' => 'lestari@bakery.com',
        'role' => 'unit_bisnis',
    ]);

    // Assert that the unit bisnis profile was created with pending status
    $this->assertDatabaseHas('unit_bisnis_profiles', [
        'nama_usaha' => 'Lestari Bakery',
        'jenis_usaha' => 'Restoran',
        'status_verifikasi' => 'pending',
    ]);

    // Assert that a database notification record was created for the admin
    $this->assertDatabaseHas('notifications', [
        'type' => 'App\Notifications\UnitBisnisMendaftarNotification',
    ]);
});

test('TC-REG-02: Verifikasi preview dan isi email notifikasi admin', function () {
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

test('TC-SET-01: Verifikasi toggle pengaturan notifikasi pada halaman pengaturan umum', function () {
    $user = User::factory()->create([
        'role' => 'individu',
        'notif_donasi' => true,
    ]);

    $this->browse(function (Browser $browser) use ($user) {
        $browser->loginAs($user)
            ->visit('/user/dashboard')
            ->waitForText('Halo,')
            ->pause(duskDelay())
            ->clickLink('Pengaturan')
            ->waitForLocation('/user/pengaturan')
            ->waitForText('Pengaturan')
            ->pause(duskDelay())
            ->assertSee('Donasi Baru')
            ->click('button[class*="relative inline-flex h-8 w-14"]')
            ->pause(duskDelay())
            ->waitForText('Pengaturan notifikasi berhasil diperbarui!')
            ->assertSee('Pengaturan notifikasi berhasil diperbarui!')
            ->pause(duskDelay());
    });

    $user->refresh();
    expect((bool)$user->notif_donasi)->toBeFalse();
});

test('TC-SET-02: Verifikasi toggle pengaturan notifikasi pada halaman pengaturan bisnis', function () {
    $user = User::factory()->create([
        'role' => 'unit_bisnis',
    ]);

    $profile = UnitBisnisProfile::create([
        'user_id' => $user->id,
        'nama_usaha' => $user->name,
        'jenis_usaha' => 'Restoran',
        'lokasi_lat' => '-6.900000',
        'lokasi_lng' => '107.600000',
        'verified' => true,
        'tahun_bergabung' => 2023,
        'notifikasi_aktif' => true,
        'notifikasi_pesanan' => true,
        'notifikasi_penjemputan' => true,
    ]);

    $this->browse(function (Browser $browser) use ($user) {
        $browser->loginAs($user)
            ->visit('/unit/dashboard')
            ->waitForText('Dashboard')
            ->pause(duskDelay())
            ->clickLink('Pengaturan')
            ->waitForLocation('/unit/pengaturan')
            ->waitForText('Pengaturan Operasional')
            ->pause(duskDelay())
            ->assertSee('Aktifkan Notifikasi')
            ->click('label[class*="relative inline-flex items-center cursor-pointer"]')
            ->pause(duskDelay())
            ->click('#settings-form button[type="submit"]')
            ->waitForLocation('/unit/pengaturan')
            ->assertSee('Pengaturan berhasil diperbarui!')
            ->pause(duskDelay());
    });

    $profile->refresh();
    expect((bool)$profile->notifikasi_aktif)->toBeFalse();
});

test('TC-SET-03: Verifikasi MakananDekatNotification mematuhi preferensi notif donasi user', function () {
    $unitBisnisUser = User::factory()->create(['role' => 'unit_bisnis']);
    $profile = UnitBisnisProfile::create([
        'user_id' => $unitBisnisUser->id,
        'nama_usaha' => 'Test Cafe',
        'jenis_usaha' => 'Restoran',
    ]);
    $masterMakanan = MasterMakanan::create([
        'unit_bisnis_id' => $profile->id,
        'nama_makanan' => 'Test Food',
        'kategori' => 'Makanan Utama',
        'harga' => 10000,
        'berat' => 100,
    ]);
    $menuAktif = MenuAktif::create([
        'master_makanan_id' => $masterMakanan->id,
        'unit_bisnis_id' => $profile->id,
        'stok_porsi' => 10,
        'batas_pengambilan' => now()->addHours(2),
        'status' => 'aktif',
    ]);

    // User with notif_donasi = true
    $userOn = User::factory()->create([
        'role' => 'individu',
        'notif_donasi' => true,
    ]);
    $userOn->notify(new \App\Notifications\MakananDekatNotification($menuAktif));
    $this->assertDatabaseHas('notifications', [
        'notifiable_id' => $userOn->id,
        'type' => \App\Notifications\MakananDekatNotification::class,
    ]);

    // User with notif_donasi = false
    $userOff = User::factory()->create([
        'role' => 'individu',
        'notif_donasi' => false,
    ]);
    $userOff->notify(new \App\Notifications\MakananDekatNotification($menuAktif));
    $this->assertDatabaseMissing('notifications', [
        'notifiable_id' => $userOff->id,
        'type' => \App\Notifications\MakananDekatNotification::class,
    ]);
});

test('TC-SET-04: Verifikasi PesananMasukNotification mematuhi preferensi notifikasi unit bisnis', function () {
    // 1. Setup Unit Bisnis with notifications ON
    $userOn = User::factory()->create(['role' => 'unit_bisnis']);
    $profileOn = UnitBisnisProfile::create([
        'user_id' => $userOn->id,
        'nama_usaha' => 'Cafe On',
        'jenis_usaha' => 'Restoran',
        'notifikasi_aktif' => true,
        'notifikasi_pesanan' => true,
    ]);
    $masterMakananOn = MasterMakanan::create([
        'unit_bisnis_id' => $profileOn->id,
        'nama_makanan' => 'Food On',
        'kategori' => 'Makanan Utama',
        'harga' => 10000,
        'berat' => 100,
    ]);
    $menuAktifOn = MenuAktif::create([
        'master_makanan_id' => $masterMakananOn->id,
        'unit_bisnis_id' => $profileOn->id,
        'stok_porsi' => 10,
        'batas_pengambilan' => now()->addHours(2),
        'status' => 'aktif',
    ]);

    $customer = User::factory()->create(['role' => 'individu']);

    // Create Pesanan for Cafe On -> should trigger notification
    Pesanan::create([
        'menu_aktif_id' => $menuAktifOn->id,
        'unit_bisnis_id' => $profileOn->id,
        'user_id' => $customer->id,
        'jumlah_porsi' => 2,
        'total_harga' => 20000,
        'status' => 'dibayar',
        'kode_unik' => 'CODE-ON-' . time() . rand(100, 999),
    ]);

    $this->assertDatabaseHas('notifications', [
        'notifiable_id' => $userOn->id,
        'type' => \App\Notifications\PesananMasukNotification::class,
    ]);

    // 2. Setup Unit Bisnis with notifications OFF
    $userOff = User::factory()->create(['role' => 'unit_bisnis']);
    $profileOff = UnitBisnisProfile::create([
        'user_id' => $userOff->id,
        'nama_usaha' => 'Cafe Off',
        'jenis_usaha' => 'Restoran',
        'notifikasi_aktif' => false,
        'notifikasi_pesanan' => true,
    ]);
    $masterMakananOff = MasterMakanan::create([
        'unit_bisnis_id' => $profileOff->id,
        'nama_makanan' => 'Food Off',
        'kategori' => 'Makanan Utama',
        'harga' => 10000,
        'berat' => 100,
    ]);
    $menuAktifOff = MenuAktif::create([
        'master_makanan_id' => $masterMakananOff->id,
        'unit_bisnis_id' => $profileOff->id,
        'stok_porsi' => 10,
        'batas_pengambilan' => now()->addHours(2),
        'status' => 'aktif',
    ]);

    // Create Pesanan for Cafe Off -> should NOT trigger notification
    Pesanan::create([
        'menu_aktif_id' => $menuAktifOff->id,
        'unit_bisnis_id' => $profileOff->id,
        'user_id' => $customer->id,
        'jumlah_porsi' => 2,
        'total_harga' => 20000,
        'status' => 'dibayar',
        'kode_unik' => 'CODE-OFF-' . time() . rand(100, 999),
    ]);

    $this->assertDatabaseMissing('notifications', [
        'notifiable_id' => $userOff->id,
        'type' => \App\Notifications\PesananMasukNotification::class,
    ]);
});
