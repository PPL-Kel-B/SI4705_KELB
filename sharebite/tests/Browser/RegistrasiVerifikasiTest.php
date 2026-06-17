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

class RegistrasiVerifikasiTest extends DuskTestCase
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
            $browser->visit('/register/unit-bisnis')
                ->assertSee('Informasi Bisnis')
                ->typeSlowly('Nama_Usaha', 'Jaki Munawaroh Bakery', 100)
                ->select('Jenis_Usaha', 'Restoran')
                ->typeSlowly('Alamat', 'Jl. Jenderal Sudirman No. 123, Bandung', 100)
                ->attach('NIB_File', __DIR__ . '/stubs/Format Yang Sesuai.jpeg')
                ->typeSlowly('Nomor_hp', '081234567899', 100)
                ->typeSlowly('Email', 'jaki.munawaroh@bakery.com', 100)
                ->typeSlowly('Password', 'Jaki123!', 100)
                ->pause(duskDelay());

            // Isi lokasi coordinates & centang persetujuan serta aktifkan tombol submit
            $browser->script("
                document.getElementById('Latitude').value = '-6.9271';
                document.getElementById('Longitude').value = '107.6186';
                document.getElementById('terms').checked = true;
                document.getElementById('Latitude').dispatchEvent(new Event('input'));
                document.getElementById('Longitude').dispatchEvent(new Event('input'));
                document.getElementById('terms').dispatchEvent(new Event('change'));
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

        // Verifikasi data masuk ke database
        $this->assertDatabaseHas('users', [
            'email' => 'jaki.munawaroh@bakery.com',
            'role' => 'unit_bisnis',
            'latitude' => '-6.9271',
            'longitude' => '107.6186',
        ]);

        $this->assertDatabaseHas('unit_bisnis_profiles', [
            'nama_usaha' => 'Jaki Munawaroh Bakery',
            'jenis_usaha' => 'Restoran',
            'lokasi_lat' => '-6.9271',
            'lokasi_lng' => '107.6186',
            'status_verifikasi' => 'pending',
        ]);
    }
}
