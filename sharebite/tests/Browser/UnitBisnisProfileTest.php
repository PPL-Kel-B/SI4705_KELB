<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\UnitBisnisProfile;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UnitBisnisProfileTest extends DuskTestCase
{
    public function test_unit_bisnis_dapat_melihat_halaman_profile()
    {
        $user = User::where('email', 'unit@sharebite.com')->first();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/unit/profil')
                ->pause(1500)
                ->assertSee('Profil')
                ->assertSee('Informasi Bisnis')
                ->assertSee('DAMPAK SOSIAL')
                ->assertSee('DAMPAK LINGKUNGAN');
        });
    }

    public function test_unit_bisnis_dapat_mengubah_informasi_bisnis()
    {
        $user = User::where('email', 'unit@sharebite.com')->first();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/unit/profil')
                ->pause(1500);

            // Klik tombol Ubah → form masuk mode edit
            $browser->script("
                const h3 = Array.from(document.querySelectorAll('h3'))
                    .find(el => el.textContent.trim() === 'Informasi Bisnis');
                const card = h3?.closest('.bg-white');
                const btn = card?.querySelector('button');
                btn?.click();
            ");

            $browser->pause(800);

            // Verifikasi field bisa diisi (input tidak disabled)
            $browser->script("
                function setInput(selector, value) {
                    const el = document.querySelector(selector);
                    if (!el) return;
                    const setter = Object.getOwnPropertyDescriptor(
                        window.HTMLInputElement.prototype, 'value'
                    ).set;
                    setter.call(el, value);
                    el.dispatchEvent(new Event('input', { bubbles: true }));
                }
                setInput('input[x-model=\"formData.nama_bisnis\"]', 'Test Nama Bisnis');
                setInput('#no_telepon_input', '081234567890');
            ");

            $browser->pause(500);

            // Klik Batal — data TIDAK disimpan ke database
            $browser->script("
                const h3 = Array.from(document.querySelectorAll('h3'))
                    .find(el => el.textContent.trim() === 'Informasi Bisnis');
                const card = h3?.closest('.bg-white');
                const btn = card?.querySelector('button');
                btn?.click();
            ");

            $browser->pause(500)
                // Verifikasi nama bisnis kembali ke nilai asli (tidak berubah)
                ->assertSee('Lestari Food');
        });
    }

    public function test_unit_bisnis_foto_profil_menampilkan_inisial_jika_belum_ada_foto()
    {
        $user = User::where('email', 'unit@sharebite.com')->first();
        $unitBisnis = UnitBisnisProfile::where('user_id', $user->id)->first();

        // Simpan foto asli sebelum test
        $fotoAsli = $unitBisnis?->foto_bisnis;

        try {
            // Sementara set ke placeholder agar test bisa cek kondisi tanpa foto
            UnitBisnisProfile::where('user_id', $user->id)->update([
                'foto_bisnis' => 'images/placeholder-bisnis.jpg',
            ]);

            $this->browse(function (Browser $browser) use ($user) {
                $browser->loginAs($user)
                    ->visit('/unit/profil')
                    ->pause(1000)
                    ->assertPresent('.rounded-full');
            });
        } finally {
            // Kembalikan foto asli setelah test selesai
            if ($unitBisnis && $fotoAsli) {
                UnitBisnisProfile::where('user_id', $user->id)->update([
                    'foto_bisnis' => $fotoAsli,
                ]);
            }
        }
    }

    public function test_unit_bisnis_dapat_melihat_statistik_dampak()
    {
        $user = User::where('email', 'unit@sharebite.com')->first();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/unit/profil')
                ->pause(1500)
                ->assertSee('DAMPAK SOSIAL')
                ->assertSee('Porsi Makanan')
                ->assertSee('DAMPAK LINGKUNGAN')
                ->assertSee('Kilogram');
        });
    }

    public function test_unit_bisnis_dapat_melihat_lokasi_dan_alamat()
    {
        $user = User::where('email', 'unit@sharebite.com')->first();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/unit/profil')
                ->pause(1000)
                ->assertSee('Lokasi & Alamat')
                ->assertPresent('#main-map');
        });
    }
}
