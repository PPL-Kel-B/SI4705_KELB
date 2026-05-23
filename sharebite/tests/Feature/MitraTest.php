<?php

use App\Models\User;
use App\Models\UnitBisnisProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('mitra page can be rendered and displays verified partners', function () {
    // Create verified unit bisnis
    $user1 = User::factory()->create([
        'role' => 'unit_bisnis',
        'name' => 'Lestari Bakery',
    ]);
    UnitBisnisProfile::create([
        'user_id' => $user1->id,
        'nama_usaha' => 'Lestari Bakery',
        'jenis_usaha' => 'Bakery',
        'status_verifikasi' => 'terverifikasi',
    ]);

    // Create unverified unit bisnis
    $user2 = User::factory()->create([
        'role' => 'unit_bisnis',
        'name' => 'Unverified Cafe',
    ]);
    UnitBisnisProfile::create([
        'user_id' => $user2->id,
        'nama_usaha' => 'Unverified Cafe',
        'jenis_usaha' => 'Cafe',
        'status_verifikasi' => 'pending',
    ]);

    $response = $this->get('/mitra');

    $response->assertStatus(200);
    $response->assertSee('Lestari Bakery');
    $response->assertDontSee('Unverified Cafe');
});

test('mitra page can be filtered by jenis_usaha', function () {
    $user1 = User::factory()->create(['role' => 'unit_bisnis']);
    UnitBisnisProfile::create([
        'user_id' => $user1->id,
        'nama_usaha' => 'Lestari Bakery',
        'jenis_usaha' => 'Bakery',
        'status_verifikasi' => 'terverifikasi',
    ]);

    $user2 = User::factory()->create(['role' => 'unit_bisnis']);
    UnitBisnisProfile::create([
        'user_id' => $user2->id,
        'nama_usaha' => 'Sari Restaurant',
        'jenis_usaha' => 'Restoran',
        'status_verifikasi' => 'terverifikasi',
    ]);

    // Filter by Bakery
    $response = $this->get('/mitra?jenis_usaha=Bakery');
    $response->assertStatus(200);
    $response->assertSee('Lestari Bakery');
    $response->assertDontSee('Sari Restaurant');

    // Filter by Restoran
    $response = $this->get('/mitra?jenis_usaha=Restoran');
    $response->assertStatus(200);
    $response->assertDontSee('Lestari Bakery');
    $response->assertSee('Sari Restaurant');
});

test('mitra page can be searched by name or location', function () {
    $user1 = User::factory()->create([
        'role' => 'unit_bisnis',
        'alamat' => 'Jalan Merdeka No. 10',
    ]);
    UnitBisnisProfile::create([
        'user_id' => $user1->id,
        'nama_usaha' => 'Lestari Bakery',
        'jenis_usaha' => 'Bakery',
        'status_verifikasi' => 'terverifikasi',
    ]);

    $user2 = User::factory()->create([
        'role' => 'unit_bisnis',
        'alamat' => 'Jalan Sudirman No. 50',
    ]);
    UnitBisnisProfile::create([
        'user_id' => $user2->id,
        'nama_usaha' => 'Sari Restaurant',
        'jenis_usaha' => 'Restoran',
        'status_verifikasi' => 'terverifikasi',
    ]);

    // Search by name
    $response = $this->get('/mitra?search=Lestari');
    $response->assertStatus(200);
    $response->assertSee('Lestari Bakery');
    $response->assertDontSee('Sari Restaurant');

    // Search by location
    $response = $this->get('/mitra?search=Sudirman');
    $response->assertStatus(200);
    $response->assertDontSee('Lestari Bakery');
    $response->assertSee('Sari Restaurant');
});
