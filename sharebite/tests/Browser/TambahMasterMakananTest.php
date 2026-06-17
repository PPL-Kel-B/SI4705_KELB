<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\MasterMakanan;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class MasterMakananTest extends DuskTestCase
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

    public function test_tambah_lima_master_makanan()
    {
        $makanan = [
            [
                'nama' => 'AYAM PANGGANG',
                'kategori' => 'Makanan Berat',
                'gambar' => 'AYAM PANGGANG.jpg',
            ],
            [
                'nama' => 'BASO BADAK',
                'kategori' => 'Makanan Berat',
                'gambar' => 'BASO BADAK.jpg',
            ],
            [
                'nama' => 'MARTABAK',
                'kategori' => 'Makanan Ringan',
                'gambar' => 'MARTABAK.jpg',
            ],
            [
                'nama' => 'SUSHI',
                'kategori' => 'Makanan Berat',
                'gambar' => 'SUSHI.jpg',
            ],
            [
                'nama' => 'PAKET NASI LIWET',
                'kategori' => 'Makanan Berat',
                'gambar' => 'PAKET NASI LIWET.jpg',
            ],
        ];

        $this->browse(function (Browser $browser) use ($makanan) {

            $browser->loginAs($this->user);

            foreach ($makanan as $item) {

                // bersihkan data lama spesifik untuk unit bisnis ini
                MasterMakanan::where('nama_makanan', $item['nama'])
                             ->where('unit_bisnis_id', $this->unitBisnis->id)
                             ->forceDelete();

                $browser->visit('/unit/kelola-master-data/create')

                    ->type('Nama_Makanan', $item['nama'])

                    ->select('Kategori', $item['kategori'])

                    ->type(
                        'deskripsi',
                        'Testing Dusk '.$item['nama']
                    )

                    ->value('#Harga', '25000')

                    ->value('#Berat', '1')

                    ->attach(
                        'Foto',
                        base_path(
                            'tests/Browser/stubs/'.$item['gambar']
                        )
                    )

                    ->pause(1500);

                $browser->script("
                    document.getElementById('btn-simpan').disabled = false;
                ");

                $browser->click('#btn-simpan')
                        ->pause(3000);
            }
        });

        // Verifikasi database spesifik untuk unit bisnis ini
        foreach ($makanan as $item) {

            $this->assertDatabaseHas(
                'master_makanans',
                [
                    'nama_makanan' => $item['nama'],
                    'unit_bisnis_id' => $this->unitBisnis->id,
                ]
            );
        }
    }
}