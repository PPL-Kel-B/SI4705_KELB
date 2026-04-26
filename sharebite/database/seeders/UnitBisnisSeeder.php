<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitBisnisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('unit_bisnis')->insert([
            [
                'Nama_Usaha' => 'Arcamanik Hotel',
                'Jenis_Usaha' => 'Restoran',
                'Alamat' => 'Jl. Raya Bandung',
                'Nomor_hp' => '0822123456',
                'Email' => 'arcamanik@hotel.com',
                'Password' => bcrypt('password123'),
                'AdminID' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Nama_Usaha' => 'Cafe Central',
                'Jenis_Usaha' => 'Kafe',
                'Alamat' => 'Jl. Merdeka No. 5',
                'Nomor_hp' => '0831987654',
                'Email' => 'central@cafe.com',
                'Password' => bcrypt('password123'),
                'AdminID' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
