<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\UnitBisnisProfile;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UnitBisnisPengaturanTest extends DuskTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Pastikan user unit bisnis dan profilnya ada di database dusk
        $user = User::firstOrCreate(
            ['email' => 'unit@sharebite.com'],
            [
                'name'     => 'Lestari Food',
                'password' => bcrypt('password'),
                'role'     => 'unit_bisnis',
                'no_hp'    => '+6282178830750',
            ]
        );

        UnitBisnisProfile::firstOrCreate(
            ['user_id' => $user->id],
            [
                'nama_usaha'           => 'Lestari Food',
                'jenis_usaha'          => 'Restoran',
                'email_bisnis'         => 'lestari@gmail.com',
                'no_telepon'           => '+6282178830750',
                'foto_bisnis'          => 'images/placeholder-bisnis.jpg',
                'lokasi_lat'           => '-6.9271',
                'lokasi_lng'           => '107.6411',
                'radius_penjemputan'   => 15,
                'jam_buka'             => '08:00',
                'jam_tutup'            => '21:00',
                'verified'             => true,
                'status_verifikasi'    => 'terverifikasi',
                'tahun_bergabung'      => 2023,
                'notifikasi_aktif'     => true,
                'notifikasi_pesanan'   => true,
                'notifikasi_penjemputan' => true,
            ]
        );
    }

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
                ->pause(800);

            // Gunakan JS untuk set nilai time input agar tidak bergantung pada keyboard Chrome
            $browser->script("
                document.querySelector('[name=jam_buka]').value = '08:00';
                document.querySelector('[name=jam_tutup]').value = '20:00';
            ");

            $browser->press('Simpan Pengaturan Operasional')
                ->pause(2000)
                ->assertSourceHas('berhasil');
        });
    }

    public function test_unit_bisnis_jam_tutup_harus_setelah_jam_buka()
    {
        $user = User::where('email', 'unit@sharebite.com')->first();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->driver->manage()->deleteAllCookies();

            $browser->loginAs($user)
                ->visit('/unit/pengaturan')
                ->pause(800);

            // Set jam tutup lebih awal dari jam buka → validasi harus gagal
            $browser->script("
                document.querySelector('[name=jam_buka]').value = '20:00';
                document.querySelector('[name=jam_tutup]').value = '08:00';
            ");

            $browser->press('Simpan Pengaturan Operasional')
                ->pause(2000)
                ->assertSee('setelah jam buka');
        });
    }

    public function test_unit_bisnis_dapat_mengubah_radius_penjemputan()
    {
        $user = User::where('email', 'unit@sharebite.com')->first();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->driver->manage()->deleteAllCookies();

            $browser->loginAs($user)
                ->visit('/unit/pengaturan')
                ->pause(800);

            // Hanya ubah radius — jam terisi dari database
            $browser->script("document.querySelector('[name=radius_penjemputan]').value = 25");

            $browser->press('Simpan Pengaturan Operasional')
                ->pause(2000)
                ->assertSourceHas('berhasil');
        });
    }

    public function test_unit_bisnis_dapat_mengubah_notifikasi()
    {
        $user = User::where('email', 'unit@sharebite.com')->first();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->driver->manage()->deleteAllCookies();

            $browser->loginAs($user)
                ->visit('/unit/pengaturan')
                ->pause(800);

            // Hanya ubah toggle notifikasi — jam & radius terisi dari database
            $browser->script("document.querySelector('input[type=checkbox]').click()");

            $browser->press('Simpan Pengaturan Operasional')
                ->pause(2000)
                ->assertSourceHas('berhasil');
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
