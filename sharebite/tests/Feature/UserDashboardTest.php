<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserActivity;
use App\Models\MenuAktif;
use App\Models\MasterMakanan;
use App\Models\Pesanan;
use App\Models\UnitBisnisProfile;
use App\Models\Pembayaran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_page_is_displayed_with_correct_computations()
    {
        $user = User::factory()->create([
            'role' => 'individu',
            'latitude' => '-6.9271',
            'longitude' => '107.6186',
            'alamat' => 'Bandung, Jawa Barat'
        ]);

        // Create Unit Bisnis
        $unitUser = User::factory()->create([
            'role' => 'unit_bisnis',
            'latitude' => '-6.9300',
            'longitude' => '107.6200',
        ]);
        $unitProfile = UnitBisnisProfile::create([
            'user_id' => $unitUser->id,
            'nama_usaha' => 'Toko Roti Wangi',
            'jenis_usaha' => 'Kuliner',
            'radius_penjemputan' => 5,
        ]);

        // Create Food
        $master = MasterMakanan::create([
            'unit_bisnis_id' => $unitProfile->id,
            'nama_makanan' => 'Roti Manis',
            'kategori' => 'Cemilan / Makanan Ringan',
            'harga' => 12000,
            'berat' => 0.25, // 0.25 kg
        ]);
        $menu = MenuAktif::create([
            'master_makanan_id' => $master->id,
            'unit_bisnis_id' => $unitProfile->id,
            'is_gratis' => false,
            'harga_jual' => 10000,
            'stok_porsi' => 10,
            'batas_pengambilan' => now()->addHours(3),
            'status' => 'aktif',
        ]);

        // Create paid order
        $pesanan = Pesanan::create([
            'user_id' => $user->id,
            'menu_aktif_id' => $menu->id,
            'unit_bisnis_id' => $unitProfile->id,
            'jumlah_porsi' => 4,
            'total_harga' => 40000,
            'status' => 'dibayar',
            'kode_unik' => 'SB-1234-XYZ',
            'waktu_pesan' => now(),
        ]);

        // Log one activity
        UserActivity::log($user->id, 'pemesanan', 'Pesanan Dibuat', 'Membeli roti manis.');

        $response = $this
            ->actingAs($user)
            ->get(route('user.dashboard'));

        $response->assertOk();
        $response->assertSee('Toko Roti Wangi');
        $response->assertSee('Roti Manis');
        // Porsi diambil: 4
        $response->assertSee('4');
        // Makanan terselamatkan: 4 * 0.25 = 1.0 kg
        $response->assertSee('1,0');
        // CO2 dihemat: 1.0 * 2.5 = 2.5 kg
        $response->assertSee('2,5');
        // Activity logged
        $response->assertSee('Pesanan Dibuat');
    }

    public function test_activities_page_is_displayed()
    {
        $user = User::factory()->create(['role' => 'individu']);

        $response = $this
            ->actingAs($user)
            ->get(route('user.activities'));

        $response->assertOk();
    }

    public function test_nearby_page_is_displayed_and_filters_correctly()
    {
        $user = User::factory()->create([
            'role' => 'individu',
            'latitude' => '-6.9271',
            'longitude' => '107.6186'
        ]);

        // Create Unit Bisnis
        $unitUser = User::factory()->create([
            'role' => 'unit_bisnis',
            'latitude' => '-6.9300',
            'longitude' => '107.6200',
        ]);
        $unitProfile = UnitBisnisProfile::create([
            'user_id' => $unitUser->id,
            'nama_usaha' => 'Toko Roti Wangi',
            'jenis_usaha' => 'Kuliner',
            'radius_penjemputan' => 5,
        ]);

        // Create Food
        $master = MasterMakanan::create([
            'unit_bisnis_id' => $unitProfile->id,
            'nama_makanan' => 'Spaghetti',
            'kategori' => 'Makanan Berat',
            'harga' => 20000,
            'berat' => 0.3,
        ]);
        $menu = MenuAktif::create([
            'master_makanan_id' => $master->id,
            'unit_bisnis_id' => $unitProfile->id,
            'is_gratis' => false,
            'harga_jual' => 15000,
            'stok_porsi' => 5,
            'batas_pengambilan' => now()->addHours(2),
            'status' => 'aktif',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('user.nearby', ['search' => 'Spaghetti']));

        $response->assertOk();
        $response->assertSee('Spaghetti');
        $response->assertSee('Toko Roti Wangi');
    }

    public function test_dashboard_displays_warning_when_location_is_not_set()
    {
        $user = User::factory()->create([
            'role' => 'individu',
            'latitude' => null,
            'longitude' => null,
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('user.dashboard'));

        $response->assertOk();
        $response->assertSee('Tentukan lokasi untuk melihat makanan terdekat anda.');
        $response->assertSee('Titik Lokasi Belum Ditentukan');
    }

    public function test_nearby_displays_warning_when_location_is_not_set()
    {
        $user = User::factory()->create([
            'role' => 'individu',
            'latitude' => null,
            'longitude' => null,
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('user.nearby'));

        $response->assertOk();
        $response->assertSee('Tidak bisa menampilkan lokasi terdekat, harap tentukan lokasi terlebih dahulu');
        $response->assertSee('Lokasi Belum Ditentukan');
    }
}
