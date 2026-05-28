<?php

use App\Models\User;
use Laravel\Dusk\Browser;
use Illuminate\Support\Facades\Hash;

test('unit bisnis dapat login dan melihat dashboard', function () {
    // Pastikan user unit bisnis ada di database
    $user = User::updateOrCreate(
        ['email' => 'unit@sharebite.com'],
        [
            'name'     => 'Lestari Food',
            'password' => Hash::make('password'),
            'role'     => 'unit_bisnis',
            'no_hp'    => '08123456780',
        ]
    );

    $this->browse(function (Browser $browser) {
        $browser->visit('/login')
                ->assertSee('Selamat Datang')              // Halaman login tampil
                ->type('email', 'unit@sharebite.com')
                ->type('[name="password"]', 'password')    // Pakai selector karena id tidak dipakai di type()
                ->click('#loginBtn')                       // Klik tombol "Masuk Sekarang" via ID
                ->pause(3000)                              // Tunggu redirect
                ->assertPathIs('/unit/dashboard')          // Pastikan redirect ke dashboard unit bisnis
                ->assertSee('Dashboard')                   // Konten dashboard tampil
                ->assertSee('Lestari Food');               // Nama user tampil di hero banner
    });
});