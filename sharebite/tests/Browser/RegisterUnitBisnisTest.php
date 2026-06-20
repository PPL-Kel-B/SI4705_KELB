<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\UnitBisnisProfile;

if (! function_exists('duskDelay')) {
    function duskDelay(): int {
        return (int) env('DUSK_PAUSE_MS', 3500);
    }
}

class RegisterUnitBisnisTest extends DuskTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Bersihkan data lama agar pengujian selalu bersih
        User::whereIn('email', [
            'jaki.munawaroh@bakery.com'
        ])->delete();
    }

    /**
     * TC-REG-UNIT-POS: Registrasi Unit Bisnis Baru (Positif)
     */
    public function testRegistUnitBisnis(): void
    {
        $this->browse(function (Browser $browser) {
            // 1. Mulai dari halaman login
            $browser->visit('/login')
                ->assertSee('Selamat Datang')
                // 2. Klik link Buat Akun Baru
                ->clickLink('Buat Akun Baru')
                ->waitForLocation('/registerkomunitas')
                // 3. Klik tab Unit Bisnis
                ->click('a[href*="unit-bisnis"]')
                ->waitForLocation('/register/unit-bisnis')
                ->assertSee('Informasi Bisnis')
                // 4. Isi form pendaftaran
                ->type('Nama_Usaha', 'Jaki Munawaroh Bakery')
                ->select('Jenis_Usaha', 'Restoran')
                ->type('Alamat', 'Jalan Rancawangi II, Tegalluar, Bojongsoang, Kabupaten Bandung, Jawa Barat, 40295, Indonesia')
                ->attach('NIB_File', __DIR__ . '/stubs/Format Yang Sesuai.jpeg')
                ->type('Nomor_hp', '081234567899')
                ->type('Email', 'jaki.munawaroh@bakery.com')
                ->type('Password', 'Jaki123!');

            // 5. Cari lokasi melalui search box (memanggil Nominatim API asli)
            $browser->type('#location-search', 'Jalan Rancawangi II, Tegalluar, Bojongsoang, Kabupaten Bandung, Jawa Barat, 40295, Indonesia')
                ->pause(500) // 0.5 detik jeda setelah mengetik
                ->click('#btn-search-loc')
                ->waitFor('#search-suggestions li', 10)
                ->click('#search-suggestions li')
                ->pause(1000);

            // Centang terms checkbox
            $browser->click('#terms');

            // 6. Klik tombol submit
            $browser->pause(duskDelay())
                ->click('#submitBtn')
                ->waitForLocation('/login')
                ->assertPathIs('/login')
                ->pause(duskDelay());
        });

        // Verifikasi data masuk ke database
        $this->assertDatabaseHas('users', [
            'email' => 'jaki.munawaroh@bakery.com',
            'role' => 'unit_bisnis',
        ]);

        // Pastikan latitude dan longitude terisi (dari Nominatim API asli)
        $user = User::where('email', 'jaki.munawaroh@bakery.com')->first();
        $this->assertNotNull($user->latitude, 'Latitude harus terisi dari pencarian lokasi');
        $this->assertNotNull($user->longitude, 'Longitude harus terisi dari pencarian lokasi');

        $this->assertDatabaseHas('unit_bisnis_profiles', [
            'nama_usaha' => 'Jaki Munawaroh Bakery',
            'jenis_usaha' => 'Restoran',
            'status_verifikasi' => 'pending',
        ]);

        // Pastikan lokasi_lat dan lokasi_lng terisi (dari Nominatim API asli)
        $profile = UnitBisnisProfile::where('nama_usaha', 'Jaki Munawaroh Bakery')->first();
        $this->assertNotNull($profile->lokasi_lat, 'Lokasi latitude harus terisi dari pencarian lokasi');
        $this->assertNotNull($profile->lokasi_lng, 'Lokasi longitude harus terisi dari pencarian lokasi');
    }
}
