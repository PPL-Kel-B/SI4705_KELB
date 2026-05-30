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
        // 1. AMBIL DATA USER DAN PROFIL DARI SEEDER
        $userKomunitas = User::where('role', 'komunitas')->first() ?? User::where('role', 'individu')->first();
        $userUnitBisnis = User::where('role', 'unit_bisnis')->first();
        $profile = $userUnitBisnis->unitBisnisProfile;

        // 2. BUAT DATA MAKANAN TIRUAN DI DATABASE (Dipastikan Masuk ke DB sharebite_dusk)
        $namaMakananTest = 'Siomay Premium Dusk-' . time();
        
        $masterMakanan = MasterMakanan::create([
            'unit_bisnis_id' => $profile->id, 
            'nama_makanan' => $namaMakananTest,
            'kategori' => 'Makanan Ringan', 
            'harga' => 15000, 
            'berat' => 200
        ]);

        MenuAktif::create([
            'master_makanan_id' => $masterMakanan->id,
            'unit_bisnis_id' => $profile->id,
            'stok_porsi' => 15, 
            'batas_pengambilan' => '23:59', 
            'status' => 'aktif'
        ]);

        // 3. JALANKAN OTOMATISASI BROWSER
        $this->browse(function (Browser $browser) use ($userKomunitas, $profile) {
            
            $browser->loginAs($userKomunitas)
                    
                    // Tembak URL simulasi dengan prefix /user yang benar
                    ->visit('/user/tes-tombol-profil') 
                    
                    // Tunggu maksimal 10 detik sampai halaman termuat sempurna dan tombol terdeteksi
                    ->waitForText('Kunjungi Profil', 10)
                    
                    // KLIK TOMBOL "Kunjungi Profil"
                    ->clickLink('Kunjungi Profil') 
                    
                    // TUNGGU SAMPAI HALAMAN PROFIL UNIT BISNIS BERHASIL TERBUKA DENGAN PREFIX /user
                    ->waitForLocation('/user/unit-bisnis/' . $profile->id, 10)
                    ->assertPathIs('/user/unit-bisnis/' . $profile->id)
                    ->assertSee($profile->nama_usaha);
        });
    }
}