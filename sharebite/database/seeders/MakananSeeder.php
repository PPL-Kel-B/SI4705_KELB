<?php

namespace Database\Seeders;

use App\Models\Makanan;
use Illuminate\Database\Seeder;

class MakananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            [
                'Nama_Makanan' => 'Paket Nasi Kotak Nusantara',
                'Jumlah_porsi' => 15,
                'Batas_waktu_pengambilan' => now()->addDay(),
                'Harga' => 12000,
                'Foto' => 'https://images.unsplash.com/photo-1604908176997-4317c4fcb1c4?w=400',
                'Status' => 'aktif',
                'UnitBisnisID' => 1,
            ],
            [
                'Nama_Makanan' => 'Rendang Daging Spesial',
                'Jumlah_porsi' => 20,
                'Batas_waktu_pengambilan' => now()->addDay(),
                'Harga' => 15000,
                'Foto' => 'https://images.unsplash.com/photo-1604908176997-4317c4fcb1c4?w=400',
                'Status' => 'aktif',
                'UnitBisnisID' => 1,
            ],
            [
                'Nama_Makanan' => 'Gado-Gado Jakarta',
                'Jumlah_porsi' => 25,
                'Batas_waktu_pengambilan' => now()->addDay(),
                'Harga' => 10000,
                'Foto' => 'https://images.unsplash.com/photo-1604908176997-4317c4fcb1c4?w=400',
                'Status' => 'aktif',
                'UnitBisnisID' => 1,
            ],
            [
                'Nama_Makanan' => 'Lumpia Goreng Premium',
                'Jumlah_porsi' => 30,
                'Batas_waktu_pengambilan' => now()->addDay(),
                'Harga' => 8000,
                'Foto' => 'https://images.unsplash.com/photo-1604908176997-4317c4fcb1c4?w=400',
                'Status' => 'aktif',
                'UnitBisnisID' => 1,
            ],
            [
                'Nama_Makanan' => 'Soto Ayam Kuning',
                'Jumlah_porsi' => 18,
                'Batas_waktu_pengambilan' => now()->addDay(),
                'Harga' => 11000,
                'Foto' => 'https://images.unsplash.com/photo-1604908176997-4317c4fcb1c4?w=400',
                'Status' => 'aktif',
                'UnitBisnisID' => 1,
            ],
            [
                'Nama_Makanan' => 'Bakso Berkuah',
                'Jumlah_porsi' => 22,
                'Batas_waktu_pengambilan' => now()->addDay(),
                'Harga' => 12500,
                'Foto' => 'https://images.unsplash.com/photo-1604908176997-4317c4fcb1c4?w=400',
                'Status' => 'aktif',
                'UnitBisnisID' => 1,
            ],
            [
                'Nama_Makanan' => 'Satay Ayam Bumbu Kacang',
                'Jumlah_porsi' => 20,
                'Batas_waktu_pengambilan' => now()->addDay(),
                'Harga' => 14000,
                'Foto' => 'https://images.unsplash.com/photo-1604908176997-4317c4fcb1c4?w=400',
                'Status' => 'aktif',
                'UnitBisnisID' => 1,
            ],
            [
                'Nama_Makanan' => 'Perkedel Goreng Renyah',
                'Jumlah_porsi' => 35,
                'Batas_waktu_pengambilan' => now()->addDay(),
                'Harga' => 7000,
                'Foto' => 'https://images.unsplash.com/photo-1604908176997-4317c4fcb1c4?w=400',
                'Status' => 'aktif',
                'UnitBisnisID' => 1,
            ],
        ];

        foreach ($menus as $menu) {
            Makanan::create($menu);
        }
    }
}
