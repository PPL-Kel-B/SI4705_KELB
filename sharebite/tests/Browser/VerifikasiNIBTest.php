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

class VerifikasiNIBTest extends DuskTestCase
{
    /**
     * Helper to login as admin.
     */
    protected function loginAdmin(Browser $browser): void
    {
        $browser->visit('/login');
        
        if (str_contains($browser->driver->getCurrentURL(), '/admin/dashboard')) {
            return;
        }

        $browser->type('email', 'faridzaridzaridzarid@gmail.com')
            ->pause(duskDelay())
            ->type('password', 'Admin@2024!')
            ->pause(duskDelay())
            ->waitUntilEnabled('#loginBtn')
            ->press('#loginBtn')
            ->waitForLocation('/admin/dashboard')
            ->pause(duskDelay());
    }

    /**
     * Test case for Verifikasi NIB Unit Bisnis.
     * Diasumsikan test ini dijalankan setelah RegistrasiVerifikasiTest.php
     * sehingga data Jaki Munawaroh Bakery sudah ada di database.
     */
    public function testVerifikasiNIBUnitBisnis(): void
    {
        // Cari user yang diregistrasi pada test sebelumnya
        $user = User::where('email', 'jaki.munawaroh@bakery.com')->first();
        
        $this->assertNotNull($user, 'User Jaki Munawaroh Bakery tidak ditemukan. Pastikan RegistrasiVerifikasiTest berjalan sukses terlebih dahulu.');

        $this->browse(function (Browser $browser) use ($user) {
            // 1. Login admin
            $this->loginAdmin($browser);

            // 2. Go to Manajemen User & 3. Go to Verifikasi NIB section
            $browser->visit('/admin/manajemen-pengguna?tab=verifikasi_nib')
                ->pause(duskDelay())
                ->assertSee('Verifikasi NIB Terbaru')
                ->assertSee('Jaki Munawaroh Bakery');

            // 4. Melakukan verifikasi NIB
            // Klik tombol Review berdasarkan ID user
            $browser->click("a[href*='/admin/manajemen-pengguna/verifikasi/{$user->id}']")
                ->waitForLocation("/admin/manajemen-pengguna/verifikasi/{$user->id}")
                ->pause(duskDelay())
                ->assertSee('Review Dokumen NIB');

            // Mengisi catatan verifikasi
            $browser->type('textarea[x-model="notes"]', 'Dokumen NIB atas nama Jaki Munawaroh Bakery terlihat valid dan lengkap.')
                ->pause(duskDelay());

            // Klik Setujui & Verifikasi
            $browser->press('Setujui & Verifikasi')
                ->pause(duskDelay());

            // Klik Ya, Setujui di dalam Modal
            $browser->press('Ya, Setujui')
                ->waitForLocation('/admin/manajemen-pengguna')
                ->pause(duskDelay());

            // Memastikan status verifikasi di database sudah berubah
            $this->assertDatabaseHas('unit_bisnis_profiles', [
                'user_id' => $user->id,
                'status_verifikasi' => 'terverifikasi',
            ]);
        });
    }
}
