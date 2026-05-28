<?php

use App\Models\User;
use Laravel\Dusk\Browser;
use Illuminate\Support\Facades\Hash;

test('unit bisnis dapat login dan klik tambah menu baru menuju halaman kelola makanan', function () {
    // Pastikan user unit bisnis ada di database
    User::updateOrCreate(
        ['email' => 'unit@sharebite.com'],
        [
            'name'     => 'Lestari Food',
            'password' => Hash::make('password'),
            'role'     => 'unit_bisnis',
            'no_hp'    => '08123456780',
        ]
    );

    $this->browse(function (Browser $browser) {
        $browser
            // 1. Login sebagai unit bisnis
            ->visit('/login')
            ->assertSee('Selamat Datang')
            ->type('email', 'unit@sharebite.com')
            ->type('[name="password"]', 'password')
            ->click('#loginBtn')
            ->pause(3000)

            // 2. Pastikan sudah di dashboard
            ->assertPathIs('/unit/dashboard')
            ->assertSee('Dashboard')

            // 3. Klik tombol "Tambah Menu Baru" di bagian Kelola Menu Aktif
            ->clickLink('Tambah Menu Baru')
            ->pause(2000)

            // 4. Pastikan diarahkan ke halaman Kelola Makanan
            ->assertPathIs('/unit/kelola-makanan')
            ->assertSee('Kelola Menu Makanan');
    });
});
