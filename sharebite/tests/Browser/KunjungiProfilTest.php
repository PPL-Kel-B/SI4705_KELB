<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\MasterMakanan;
use App\Models\MenuAktif;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class KunjungiProfilTest extends DuskTestCase
{
    /**
     * Menguji alur E2E Kunjungi Profil dari POV Komunitas/Individu.
     */
    public function test_kunjungi_profil_flow_e2e(): void
    {
        $userKomunitas = User::where('role', 'komunitas')->first() ?? User::where('role', 'individu')->first();
        $userUnitBisnis = User::where('role', 'unit_bisnis')->first();
        $profile = $userUnitBisnis->unitBisnisProfile;
        $namaMakananTest = 'Rawon';
        $masterMakanan = MasterMakanan::updateOrCreate(
            [
                'unit_bisnis_id' => $profile->id, 
                'nama_makanan' => $namaMakananTest
            ],
            [
                'kategori' => 'Makanan Berat', 
                'harga' => 20000, 
                'berat' => 400
            ]
        );

        $menuAktif = MenuAktif::updateOrCreate(
            [
                'master_makanan_id' => $masterMakanan->id,
                'unit_bisnis_id' => $profile->id,
            ],
            [
                'stok_porsi' => 15, 
                'batas_pengambilan' => '23:59', 
                'status' => 'aktif'
            ]
        );

        $this->browse(function (Browser $browser) use ($userKomunitas, $profile, $menuAktif, $namaMakananTest) {
            
            $browser->loginAs($userKomunitas)
                    ->visit('/user/tes-tombol-profil/' . $menuAktif->id)
                    ->waitForText('Kunjungi Profil', 10)
                    ->assertSee($namaMakananTest)
                    ->assertSee('15 Porsi')
                    ->assertSee('23:59')
                    ->clickLink('Kunjungi Profil')
                    ->waitForLocation('/user/unit-bisnis/' . $profile->id, 10)
                    ->assertPathIs('/user/unit-bisnis/' . $profile->id)
                    ->assertSee($profile->nama_usaha)
                    ->assertSee('Jam Operasional')
                    ->assertSee('Hubungi Mitra')
                    ->assertSee('Email Mitra')
                    ->assertSee('TOTAL DONASI')
                    ->assertSee('REPUTASI / RATING')
                    ->assertSee('GALERI AKTIVITAS DONASI')
                    ->assertSee('ULASAN KOMUNITAS')
                    ->assertSee($namaMakananTest) 
                    ->clickLink('Ambil')
                    ->pause(1000)
                    ->assertPathBeginsWith('/user/tes-tombol-profil/');
        });
    }
}