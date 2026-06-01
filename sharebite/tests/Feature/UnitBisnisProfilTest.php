<?php

use App\Models\User;
use App\Models\UnitBisnisProfile;

test('business unit profile page can be rendered', function () {
    // Create a user with role 'unit_bisnis'
    $user = User::factory()->create([
        'role' => 'unit_bisnis',
        'no_hp' => '08123456789',
        'alamat' => 'Alamat Awal',
        'latitude' => '-6.900000',
        'longitude' => '107.600000',
    ]);

    // Create a unit bisnis profile
    $profile = UnitBisnisProfile::create([
        'user_id' => $user->id,
        'nama_usaha' => $user->name,
        'nama_bisnis' => $user->name,
        'jenis_usaha' => 'Restoran',
        'tipe_bisnis' => 'Restoran',
        'email_bisnis' => $user->email,
        'no_telepon' => '08123456789',
        'lokasi_lat' => '-6.900000',
        'lokasi_lng' => '107.600000',
        'verified' => true,
        'tahun_bergabung' => 2023,
    ]);

    $response = $this
        ->actingAs($user)
        ->get('/unit/profil');

    $response->assertOk();
    $response->assertSee($user->name);
    $response->assertSee('Alamat Awal');
});

test('business unit profile can be updated and synchronized to users table', function () {
    // Create a user with role 'unit_bisnis'
    $user = User::factory()->create([
        'role' => 'unit_bisnis',
        'no_hp' => '08123456789',
        'alamat' => 'Alamat Lama',
        'latitude' => '-6.900000',
        'longitude' => '107.600000',
    ]);

    // Create a unit bisnis profile
    $profile = UnitBisnisProfile::create([
        'user_id' => $user->id,
        'nama_usaha' => $user->name,
        'nama_bisnis' => $user->name,
        'jenis_usaha' => 'Restoran',
        'tipe_bisnis' => 'Restoran',
        'email_bisnis' => $user->email,
        'no_telepon' => '08123456789',
        'lokasi_lat' => '-6.900000',
        'lokasi_lng' => '107.600000',
        'verified' => true,
        'tahun_bergabung' => 2023,
    ]);

    $response = $this
        ->actingAs($user)
        ->post('/unit/profil/update', [
            'nama_bisnis' => 'Restoran Baru Enak',
            'tipe_bisnis' => 'Kafe',
            'email_bisnis' => 'baru@restoran.com',
            'no_telepon' => '08999999999',
            'alamat' => 'Alamat Baru Gress',
            'lokasi_lat' => '-6.912345',
            'lokasi_lng' => '107.654321',
        ]);

    $response->assertRedirect('/unit/profil');
    $response->assertSessionHasNoErrors();

    // Refresh model data
    $user->refresh();
    $profile->refresh();

    // Verify User table updates
    expect($user->name)->toBe('Restoran Baru Enak');
    expect($user->alamat)->toBe('Alamat Baru Gress');
    expect($user->no_hp)->toBe('08999999999');
    expect((float)$user->latitude)->toBe(-6.912345);
    expect((float)$user->longitude)->toBe(107.654321);

    // Verify UnitBisnisProfile table updates
    expect($profile->nama_bisnis)->toBe('Restoran Baru Enak');
    expect($profile->tipe_bisnis)->toBe('Kafe');
    expect($profile->email_bisnis)->toBe('baru@restoran.com');
    expect($profile->no_telepon)->toBe('08999999999');
    expect((float)$profile->lokasi_lat)->toBe(-6.912345);
    expect((float)$profile->lokasi_lng)->toBe(107.654321);
});
