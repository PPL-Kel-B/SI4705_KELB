<?php

use Laravel\Dusk\Browser;
use App\Models\User;
use App\Models\UnitBisnisProfile;
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
