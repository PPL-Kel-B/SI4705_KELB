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
        User::updateOrCreate(
            ['email' => 'unit@sharebite.com'],
            [
                'name' => 'Lestari Food',
                'password' => bcrypt('password'),
                'role' => 'unit_bisnis',
                'no_hp' => '08123456780',
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
