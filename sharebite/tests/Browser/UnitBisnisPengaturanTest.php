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

    public function test_unit_bisnis_jam_tutup_harus_setelah_jam_buka()
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
                ->assertSee('setelah jam buka');
        });
    }

    public function test_unit_bisnis_dapat_mengubah_radius_penjemputan()
    {
        $user = User::where('email', 'unit@sharebite.com')->first();

        $this->browse(function (Browser $browser) use ($user) {
            // Reset session agar tidak ada old() dari test sebelumnya
            $browser->driver->manage()->deleteAllCookies();

            $browser->loginAs($user)
                ->visit('/unit/pengaturan')
                ->pause(800);

            // Hanya ubah radius — jam terisi dari database, bukan dari old() session
            $browser->script("document.querySelector('[name=radius_penjemputan]').value = 25");

            $browser->press('Simpan Pengaturan Operasional')
                ->pause(1500)
                ->assertSee('berhasil');
        });
    }

    public function test_unit_bisnis_dapat_mengubah_notifikasi()
    {
        $user = User::where('email', 'unit@sharebite.com')->first();

        $this->browse(function (Browser $browser) use ($user) {
            // Reset session agar tidak ada old() dari test sebelumnya
            $browser->driver->manage()->deleteAllCookies();

            $browser->loginAs($user)
                ->visit('/unit/pengaturan')
                ->pause(800);

            // Hanya ubah toggle notifikasi — jam & radius terisi dari database
            $browser->script("document.querySelector('input[type=checkbox]').click()");

            $browser->press('Simpan Pengaturan Operasional')
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

    public function test_unit_bisnis_klik_ganti_kata_sandi_masuk_ke_halaman_profil()
    {
        $user = User::where('email', 'unit@sharebite.com')->first();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/unit/pengaturan')
                ->pause(1000)
                ->assertSee('Keamanan Akun')
                ->clickLink('Ganti Kata Sandi')
                ->pause(1500)
                // Setelah klik, diarahkan ke halaman profil
                ->assertPathIs('/unit/profil')
                ->assertSee('Profil');
        });
    }

    public function test_unit_bisnis_modal_ganti_kata_sandi_terbuka_di_profil()
    {
        $user = User::where('email', 'unit@sharebite.com')->first();

        $this->browse(function (Browser $browser) use ($user) {
            // Kunjungi profil langsung dengan parameter changePassword=true
            $browser->loginAs($user)
                ->visit('/unit/profil?changePassword=true')
                ->pause(2000)
                // Modal harus terbuka otomatis
                ->assertSee('Ganti Kata Sandi')
                ->assertPresent('#change-password-modal');
        });
    }

    public function test_unit_bisnis_ganti_kata_sandi_gagal_jika_password_lama_salah()
    {
        $user = User::where('email', 'unit@sharebite.com')->first();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/unit/profil?changePassword=true')
                ->pause(2000);

            // Isi form dengan password lama yang salah
            $browser->type('current_password', 'passwordsalah123')
                ->type('password', 'passwordbaru123')
                ->type('password_confirmation', 'passwordbaru123')
                ->press('Ganti Kata Sandi')
                ->pause(1500)
                // Harus muncul pesan error password salah
                ->assertSee('tidak sesuai');
        });
    }
}
