<?php

use App\Models\User;
use Laravel\Dusk\Browser;
use Illuminate\Support\Facades\Hash;

test('unit bisnis dapat login dan klik lihat semua pesanan menuju halaman pesanan masuk', function () {
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
            ->assertSee('Pesanan Masuk')

            // 3. Klik link "Lihat Semua" di dekat judul "Pesanan Masuk"
            ->clickLink('Lihat Semua')
            ->pause(2000)

            // 4. Pastikan diarahkan ke halaman Pesanan
            ->assertPathIs('/unit/pesanan')
            ->assertSee('Pesanan Masuk');
    });
});
