<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin Account
        User::updateOrCreate(
            ['email' => 'admin@sharebite.com'],
            [
                'name' => 'Admin ShareBite',
                'password' => bcrypt('Admin@2024!'),
                'role' => 'admin',
                'no_hp' => '08123456789',
            ]
        );

        // Create example users for each role
        $unit = User::updateOrCreate(
            ['email' => 'unit@sharebite.com'],
            [
                'name' => 'Lestari Food',
                'password' => bcrypt('password'),
                'role' => 'unit_bisnis',
                'no_hp' => '08123456780',
            ]
        );

        \App\Models\UnitBisnisProfile::updateOrCreate(
            ['user_id' => $unit->id],
            [
                'nama_usaha' => 'Lestari Food',
                'jenis_usaha' => 'Restoran',
                'status_verifikasi' => 'terverifikasi'
            ]
        );

        // Rejected Unit Bisnis for testing
        $rejectedUnit = User::updateOrCreate(
            ['email' => 'rejected@sharebite.com'],
            [
                'name' => 'Warung Ditolak',
                'password' => bcrypt('password'),
                'role' => 'unit_bisnis',
                'no_hp' => '08123456782',
            ]
        );

        \App\Models\UnitBisnisProfile::updateOrCreate(
            ['user_id' => $rejectedUnit->id],
            [
                'nama_usaha' => 'Warung Ditolak',
                'jenis_usaha' => 'Warung Makan',
                'status_verifikasi' => 'ditolak',
                'reviewer_notes' => 'Dokumen NIB yang Anda unggah sudah kadaluarsa dan tidak terbaca dengan jelas. Silakan unggah dokumen yang baru.'
            ]
        );

        User::updateOrCreate(
            ['email' => 'komunitas@sharebite.com'],
            [
                'name' => 'Komunitas Berbagi',
                'password' => bcrypt('password'),
                'role' => 'komunitas',
                'no_hp' => '08123456781',
            ]
        );
    }
}
