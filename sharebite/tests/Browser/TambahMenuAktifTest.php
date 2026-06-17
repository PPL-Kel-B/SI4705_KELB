<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\MasterMakanan;
use App\Models\MenuAktif;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TambahMenuAktifTest extends DuskTestCase
{
    protected User $user;
    protected \App\Models\UnitBisnisProfile $unitBisnis;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::where(
            'email',
            'jaki.munawaroh@bakery.com'
        )->firstOrFail();

        $this->unitBisnis = \App\Models\UnitBisnisProfile::where('user_id', $this->user->id)->firstOrFail();
    }

    /**
     * TC-MENU-AKTIF: Menambahkan menu aktif dari master data
     */
    public function test_tambah_menu_aktif_dari_master_data()
    {
        $makananList = [
            'AYAM PANGGANG',
            'BASO BADAK',
            'MARTABAK',
            'SUSHI',
            'PAKET NASI LIWET',
        ];

        $this->browse(function (Browser $browser) use ($makananList) {
            $browser->loginAs($this->user);

            foreach ($makananList as $namaMakanan) {
                // Cari master makanan id untuk dibersihkan jika sudah pernah dibuat menu aktifnya
                $master = MasterMakanan::where('nama_makanan', $namaMakanan)
                                       ->where('unit_bisnis_id', $this->unitBisnis->id)
                                       ->first();
                if ($master) {
                    MenuAktif::where('master_makanan_id', $master->id)->forceDelete();
                }

                $browser->visit('/unit/kelola-makanan/tambah')
                        ->waitForText('Tambah Menu Aktif', 10)
                        ->pause(1000);

                // Cari menu di kolom pencarian dengan mengetik satu persatu agar pelan
                $chars = str_split($namaMakanan);
                $browser->clear('input[x-model="searchQuery"]')->pause(500);
                foreach ($chars as $char) {
                    $browser->append('input[x-model="searchQuery"]', $char)->pause(150);
                }

                $browser->pause(1500)
                        // Klik h4 yang muncul setelah proses filter pencarian
                        ->click('h4.line-clamp-1')
                        ->pause(1500);

                // Atur Gratis khusus untuk SUSHI
                if ($namaMakanan === 'SUSHI') {
                    $browser->script("
                        let checkbox = document.querySelector('input[name=\"is_gratis\"]');
                        checkbox.checked = true;
                        checkbox.dispatchEvent(new Event('change'));
                    ");
                    $browser->pause(1000);
                } else {
                    $browser->script("
                        let checkbox = document.querySelector('input[name=\"is_gratis\"]');
                        checkbox.checked = false;
                        checkbox.dispatchEvent(new Event('change'));
                    ");
                    $browser->pause(1000);
                }

                // Set Jumlah Stok menjadi 50
                $browser->script("
                    let stok = document.querySelector('input[name=\"stok_porsi\"]');
                    stok.value = '50';
                    stok.dispatchEvent(new Event('input'));
                ");
                $browser->pause(1000);

                // Set Batas Pengambilan menjadi jam 11.00 PM (23:00)
                $browser->script("
                    let batas = document.querySelector('input[name=\"batas_pengambilan\"]');
                    batas.value = '23:00';
                    batas.dispatchEvent(new Event('input'));
                ");
                $browser->pause(1000);

                $browser->pause(1000)
                        // Klik tombol Publikasikan Menu Aktif
                        ->click('#btn-submit')
                        ->pause(3000);
            }
        });

        // Verifikasi apakah data menu aktif sudah berhasil masuk ke database
        foreach ($makananList as $namaMakanan) {
            $master = MasterMakanan::where('nama_makanan', $namaMakanan)
                                   ->where('unit_bisnis_id', $this->unitBisnis->id)
                                   ->first();
            
            if ($master) {
                $isGratis = ($namaMakanan === 'SUSHI') ? 1 : 0;
                
                $this->assertDatabaseHas('menu_aktifs', [
                    'master_makanan_id' => $master->id,
                    'stok_porsi' => 50,
                    'is_gratis' => $isGratis,
                    'batas_pengambilan' => date('Y-m-d') . ' 23:00:00'
                ]);
            }
        }
    }
}
