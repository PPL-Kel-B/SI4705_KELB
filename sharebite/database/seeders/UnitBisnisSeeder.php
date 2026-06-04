<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UnitBisnisProfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UnitBisnisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a sample unit bisnis user if not exists
        $user = User::firstOrCreate(
            ['email' => 'mitra@sharebite.com'],
            [
                'name' => 'Arcamanik Hotel',
                'role' => 'unit_bisnis',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'no_hp' => '+62 812 3456 7890',
                'alamat' => 'Jl. Soekarno-Hatta No. 789, Arcamanik, Kec. Arcamanik, Kota Bandung, Jawa Barat 40293, Indonesia',
            ]
        );

        UnitBisnisProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nama_usaha' => 'Arcamanik Hotel',
                'jenis_usaha' => 'Hotel',
                'foto_bisnis' => 'images/placeholder-bisnis.jpg',
                'lokasi_lat' => '-6.9271',
                'lokasi_lng' => '107.6411',
                'radius_penjemputan' => 15,
                'jam_buka' => '08:00',
                'jam_tutup' => '21:00',
                'verified' => true,
                'tahun_bergabung' => 2023,
                'notifikasi_aktif' => true,
                'notifikasi_pesanan' => true,
                'notifikasi_penjemputan' => true,
                'status_verifikasi' => 'terverifikasi',
                'total_makanan_terjual' => 1420,
                'total_berat_terjual' => 580.5,
            ]
        );

        // Create additional test users
        for ($i = 2; $i <= 3; $i++) {
            $testUser = User::firstOrCreate(
                ['email' => 'mitra' . $i . '@sharebite.com'],
                [
                    'name' => 'Restoran Test ' . $i,
                    'role' => 'unit_bisnis',
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'),
                    'no_hp' => '+62 812 ' . rand(1000, 9999) . ' ' . rand(1000, 9999),
                    'alamat' => 'Jl. Test No. ' . ($i * 100) . ', Bandung, Jawa Barat',
                ]
            );

            UnitBisnisProfile::updateOrCreate(
                ['user_id' => $testUser->id],
                [
                    'nama_usaha' => 'Restoran Test ' . $i,
                    'jenis_usaha' => ['Restoran', 'Kafe', 'Bakery'][$i - 2],
                    'lokasi_lat' => -6.9 - (rand(10, 99) / 1000),
                    'lokasi_lng' => 107.6 + (rand(10, 99) / 1000),
                    'radius_penjemputan' => rand(10, 30),
                    'jam_buka' => '09:00',
                    'jam_tutup' => '22:00',
                    'verified' => true,
                    'tahun_bergabung' => 2024,
                    'notifikasi_aktif' => true,
                    'notifikasi_pesanan' => true,
                    'notifikasi_penjemputan' => true,
                    'status_verifikasi' => 'terverifikasi',
                    'total_makanan_terjual' => rand(100, 500),
                    'total_berat_terjual' => rand(50, 300),
                ]
            );
        }

        $this->command->info('Unit Bisnis profiles seeded successfully!');
    }
}
