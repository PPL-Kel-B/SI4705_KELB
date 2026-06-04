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

test('user can register a unit bisnis and trigger a notification to the admin (TC-REG-01)', function () {
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

test('validation for empty nama usaha (TC-REG-02)', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/register/unit-bisnis')
            ->assertSee('Informasi Bisnis')
            // Keep Nama_Usaha empty, fill other fields
            ->select('Jenis_Usaha', 'Restoran')
            ->type('Alamat', 'Jalan Merdeka No. 10')
            ->type('Nomor_hp', '081298765432')
            ->type('Email', 'lestari@bakery.com')
            ->type('Password', 'Password123!');

        // Verify that the submit button remains disabled (cannot submit)
        $disabled = $browser->attribute('#submitBtn', 'disabled');
        expect($disabled)->toBe('true');
    });
});

test('validation for unsupported NIB file format (TC-REG-03)', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/register/unit-bisnis')
            ->attach('NIB_File', __FILE__) // Attach this PHP file (unsupported format)
            ->waitForText('Format file tidak didukung!')
            ->assertSee('Format file tidak didukung!');
    });
});

test('validation for NIB file size exceeding 5MB (TC-REG-04)', function () {
    // Generate a temporary 6MB dummy PDF file dynamically
    $tempFile = tempnam(sys_get_temp_dir(), 'nib_test_');
    $fp = fopen($tempFile, 'w');
    fseek($fp, 6 * 1024 * 1024); // Seek to 6MB
    fputs($fp, 'a');
    fclose($fp);
    
    $largePdf = $tempFile . '.pdf';
    rename($tempFile, $largePdf);

    try {
        $this->browse(function (Browser $browser) use ($largePdf) {
            $browser->visit('/register/unit-bisnis')
                ->attach('NIB_File', $largePdf)
                ->waitForText('Ukuran file melebihi 5MB!')
                ->assertSee('Ukuran file melebihi 5MB!');
        });
    } finally {
        // Ensure cleanup of the temporary file
        if (file_exists($largePdf)) {
            unlink($largePdf);
        }
    }
});

test('validation for duplicate email or phone (TC-REG-05)', function () {
    // Create an existing user with duplicate email/phone
    User::factory()->create([
        'email' => 'lestari@bakery.com',
        'no_hp' => '081298765432',
    ]);

    $this->browse(function (Browser $browser) {
        $browser->visit('/register/unit-bisnis')
            ->type('Nama_Usaha', 'Lestari Bakery')
            ->select('Jenis_Usaha', 'Restoran')
            ->type('Alamat', 'Jalan Merdeka No. 10')
            ->type('Nomor_hp', '081298765432') // Duplicate phone
            ->type('Email', 'lestari@bakery.com')   // Duplicate email
            ->type('Password', 'Password123!');

        // Check terms checkbox and enable the submit button via JS
        $browser->script("
            document.getElementById('terms').checked = true;
            const btn = document.getElementById('submitBtn');
            btn.disabled = false;
            btn.classList.remove('opacity-50', 'cursor-not-allowed');
        ");

        $browser->click('#submitBtn')
            ->waitForText('taken') // Laravel default "has already been taken" validation message
            ->assertSee('taken');
    });
});

test('UnitBisnisMendaftarNotification generates correct email preview and content (TC-REG-06)', function () {
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

test('user can toggle notification settings on general settings page (TC-SET-01)', function () {
    $user = User::factory()->create([
        'role' => 'individu',
        'notif_donasi' => true,
    ]);

    $this->browse(function (Browser $browser) use ($user) {
        $browser->loginAs($user)
            ->visit('/user/pengaturan')
            ->waitForText('Pengaturan')
            ->assertSee('Donasi Baru')
            ->click('button[class*="relative inline-flex h-8 w-14"]')
            ->waitForText('Pengaturan notifikasi berhasil diperbarui!')
            ->assertSee('Pengaturan notifikasi berhasil diperbarui!');
    });

    $user->refresh();
    expect((bool)$user->notif_donasi)->toBeFalse();
});

test('unit bisnis can toggle notification settings on business settings page (TC-SET-02)', function () {
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
            ->visit('/unit/pengaturan')
            ->waitForText('Pengaturan Operasional')
            ->assertSee('Aktifkan Notifikasi')
            ->click('label[class*="relative inline-flex items-center cursor-pointer"]')
            ->click('#settings-form button[type="submit"]')
            ->waitForLocation('/unit/pengaturan')
            ->assertSee('Pengaturan berhasil diperbarui!');
    });

    $profile->refresh();
    expect((bool)$profile->notifikasi_aktif)->toBeFalse();
});

test('MakananDekatNotification respects notif_donasi preference (TC-SET-03)', function () {
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

test('PesananMasukNotification respects notifikasi_aktif and notifikasi_pesanan settings (TC-SET-04)', function () {
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

