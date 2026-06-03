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
        $userKomunitas = User::where('role', 'komunitas')->first();
        if (!$userKomunitas) {
            $userKomunitas = User::factory()->create(['role' => 'komunitas']);
        }

        $userUnitBisnis = User::where('role', 'unit_bisnis')->first();
        if (!$userUnitBisnis) {
            $userUnitBisnis = User::factory()->create(['role' => 'unit_bisnis']);
        }

        $profile = $userUnitBisnis->unitBisnisProfile;
        if (!$profile) {
            $profile = \App\Models\UnitBisnisProfile::create([
                'user_id' => $userUnitBisnis->id,
                'nama_usaha' => 'katsuna',
                'jenis_usaha' => 'Kafe',
                'jam_buka' => '08:00',
                'jam_tutup' => '21:00',
                'status_verifikasi' => 'terverifikasi'
            ]);
        }
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
                    ->clickLink('Kunjungi Profil')
                    ->waitForLocation('/user/unit-bisnis/' . $profile->id, 10)
                    ->assertPathIs('/user/unit-bisnis/' . $profile->id)
                    
                    // PM-01: Verifikasi Identitas & Kontak Mitra
                    ->assertSee($profile->nama_usaha)
                    ->assertSee('Verified')
                    ->assertSee('Jam Operasional')
                    ->assertSee('Hubungi Mitra')
                    ->assertSee('Email Mitra')
                    
                    // PM-02: Verifikasi "Tentang Mitra" & "Spesialisasi"
                    ->assertSee('Tentang Mitra')
                    ->assertSee('SPESIALISASI')
                    
                    // PM-03: Tampilan Galeri (Empty State)
                    ->assertSee('GALERI AKTIVITAS DONASI')
                    ->assertSee('Bukti Aktivitas Donasi Belum Tersedia')
                    
                    // PM-04: Tampilan Ulasan Komunitas (Empty State)
                    ->assertSee('ULASAN KOMUNITAS')
                    ->assertSee('Belum Ada Ulasan')
                    
                    // PM-05: Verifikasi Statistik Mitra
                    ->assertSee('TOTAL DONASI')
                    ->assertSee('REPUTASI / RATING')
                    
                    // PM-06: Interaksi Daftar "Makanan Tersedia"
                    ->assertSee('Makanan yang Tersedia Saat Ini')
                    ->assertSee($namaMakananTest)
                    ->clickLink('Ambil')
                    ->pause(1000)
                    ->assertPathBeginsWith('/user/tes-tombol-profil/');
        });
    }
}