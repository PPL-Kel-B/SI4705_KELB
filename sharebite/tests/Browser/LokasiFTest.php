<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;

if (! function_exists('duskDelay')) {
    function duskDelay(): int {
        return (int) env('DUSK_PAUSE_MS', 1500);
    }
}

class LokasiFTest extends DuskTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Force database connection to sharebite_dusk for the Dusk testing process
        \Illuminate\Support\Facades\Config::set('database.connections.mysql.database', 'sharebite_dusk');
        \Illuminate\Support\Facades\Config::set('database.connections.mysql.username', 'root');
        \Illuminate\Support\Facades\Config::set('database.connections.mysql.password', '');
        \Illuminate\Support\Facades\Config::set('database.default', 'mysql');
        \Illuminate\Support\Facades\DB::purge('mysql');
        \Illuminate\Support\Facades\DB::reconnect('mysql');
    }

    /**
     * TC-MAP-F01: Akses Halaman Peta & Atur Lokasi Relawan Farid
     * Browser akan otomatis menangkap lokasi GPS asli dari perangkat.
     * User klik tombol "Simpan Lokasi Saya" untuk menyimpan koordinat ke database.
     * Tidak ada manipulasi data atau hardcode koordinat.
     */
    public function test_TC_MAP_F01_AksesPetaDanAturLokasi(): void
    {
        $user = User::where('email', 'farid@gmail.com')->firstOrFail();

        // Bersihkan koordinat lama agar pengetesan benar-benar fresh
        $user->update([
            'latitude' => null,
            'longitude' => null,
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            // 1. Login dari halaman login
            $browser->visit('/login')
                ->assertSee('Selamat Datang')
                ->type('email', $user->email)
                ->type('password', 'Password123!')
                ->click('#loginBtn')
                ->waitForLocation('/user/dashboard')
                ->pause(duskDelay());

            // 2. Akses halaman lokasi — browser akan otomatis menangkap GPS asli
            $browser->visit('/user/lokasi')
                ->pause(duskDelay())
                ->assertPresent('#map'); // Pastikan elemen map utama dirender

            // 3. Tunggu GPS menangkap lokasi (status "Lokasi Ditemukan")
            $browser->waitForText('Lokasi Ditemukan', 15)
                ->pause(duskDelay())
                ->assertPresent('.user-marker'); // Marker biru lokasi saya muncul di peta

            // 4. Klik tombol "Simpan Lokasi Saya" untuk menyimpan koordinat ke database
            $browser->waitFor('#btn-simpan-lokasi', 5)
                ->click('#btn-simpan-lokasi')
                ->waitForLocation('/user/lokasi')
                ->pause(duskDelay());
        });

        // 5. Verifikasi lokasi tersimpan di database (koordinat asli dari GPS)
        $user->refresh();
        $this->assertNotNull($user->latitude, 'Latitude harus terisi dari GPS asli');
        $this->assertNotNull($user->longitude, 'Longitude harus terisi dari GPS asli');
    }
}
