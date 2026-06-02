<?php

namespace Tests\Browser;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UnitBisnisPengaturanTest extends DuskTestCase
{
    public function test_unit_bisnis_dapat_melihat_halaman_pengaturan()
    {
        $user = User::where('email', 'unit@sharebite.com')->first();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/unit/pengaturan')
                ->pause(1500)
                ->assertSee('Pengaturan Operasional')
                ->assertSee('JAM OPERASIONAL')
                ->assertSee('RADIUS PENJEMPUTAN')
                ->assertSee('Notifikasi');
        });
    }

    public function test_unit_bisnis_dapat_mengubah_jam_operasional()
    {
        $user = User::where('email', 'unit@sharebite.com')->first();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/unit/pengaturan')
                ->pause(800)
                ->type('jam_buka', '08:00')
                ->type('jam_tutup', '20:00')
                ->press('Simpan Pengaturan Operasional')
                ->pause(1500)
                ->assertSee('berhasil');
        });
    }

    public function test_unit_bisnis_jam_tutup_tidak_boleh_lebih_kecil_dari_jam_buka()
    {
        $user = User::where('email', 'unit@sharebite.com')->first();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/unit/pengaturan')
                ->pause(800)
                ->type('jam_buka', '20:00')
                ->type('jam_tutup', '08:00')
                ->press('Simpan Pengaturan Operasional')
                ->pause(1500)
                ->assertSee('lebih besar dari jam buka');
        });
    }

    public function test_unit_bisnis_dapat_mengubah_radius_penjemputan()
    {
        $user = User::where('email', 'unit@sharebite.com')->first();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/unit/pengaturan')
                ->pause(800);

            $browser->script("document.querySelector('[name=radius_penjemputan]').value = 10");

            $browser->type('jam_buka', '08:00')
                ->type('jam_tutup', '20:00')
                ->press('Simpan Pengaturan Operasional')
                ->pause(1500)
                ->assertSee('berhasil');
        });
    }

    public function test_unit_bisnis_dapat_mengubah_notifikasi()
    {
        $user = User::where('email', 'unit@sharebite.com')->first();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/unit/pengaturan')
                ->pause(800);

            // Toggle checkbox sr-only tidak bisa diklik langsung, pakai JS
            $browser->script("document.querySelector('input[type=checkbox]').click()");

            $browser->type('jam_buka', '08:00')
                ->type('jam_tutup', '20:00')
                ->press('Simpan Pengaturan Operasional')
                ->pause(1500)
                ->assertSee('berhasil');
        });
    }

    public function test_unit_bisnis_melihat_informasi_identitas_di_pengaturan()
    {
        $user = User::where('email', 'unit@sharebite.com')->first();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/unit/pengaturan')
                ->pause(1000)
                ->assertSee('Informasi Identitas Bisnis')
                ->assertSee('Perbarui Profil Bisnis');
        });
    }
}
