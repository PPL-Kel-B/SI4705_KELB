<?php

use App\Models\User;
use App\Models\UnitBisnisProfile;
use App\Models\MasterMakanan;
use App\Models\MenuAktif;
use App\Models\Pesanan;
use App\Models\UserActivity;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseTruncation;

// Use database truncation to ensure a clean state for Dusk tests.
uses(DatabaseTruncation::class);

/**
 * Helper to get user-defined pause duration for slow-motion demo.
 * Default is 1500ms so actions are clearly visible during presentation.
 */
function duskDelay(): int {
    return (int) env('DUSK_PAUSE_MS', 1500);
}

beforeEach(function () {
    // 1. Setup User Individu dengan Lokasi
    $this->user = User::factory()->create([
        'name' => 'Individu Peduli',
        'email' => 'individu@sharebite.com',
        'password' => bcrypt('password'),
        'role' => 'individu',
        'latitude' => '-6.9271',
        'longitude' => '107.6186',
        'alamat' => 'Bandung, Jawa Barat'
    ]);

    // 2. Setup Unit Bisnis A (Toko Wangi - 0.3 km away)
    $this->unitUserA = User::factory()->create([
        'name' => 'Toko Wangi',
        'email' => 'unitA@sharebite.com',
        'password' => bcrypt('password'),
        'role' => 'unit_bisnis',
        'latitude' => '-6.9300',
        'longitude' => '107.6200',
    ]);
    $this->unitProfileA = UnitBisnisProfile::create([
        'user_id' => $this->unitUserA->id,
        'nama_usaha' => 'Toko Wangi',
        'jenis_usaha' => 'Kuliner',
        'radius_penjemputan' => 5,
        'lokasi_lat' => '-6.9300',
        'lokasi_lng' => '107.6200',
        'jam_buka' => '00:00',
        'jam_tutup' => '23:59',
        'status_verifikasi' => 'terverifikasi',
    ]);

    // Setup Unit Bisnis B (Bakery Lestari - ~3.9 km away)
    $this->unitUserB = User::factory()->create([
        'name' => 'Bakery Lestari',
        'email' => 'unitB@sharebite.com',
        'password' => bcrypt('password'),
        'role' => 'unit_bisnis',
        'latitude' => '-6.9600',
        'longitude' => '107.6300',
    ]);
    $this->unitProfileB = UnitBisnisProfile::create([
        'user_id' => $this->unitUserB->id,
        'nama_usaha' => 'Bakery Lestari',
        'jenis_usaha' => 'Kuliner',
        'radius_penjemputan' => 5,
        'lokasi_lat' => '-6.9600',
        'lokasi_lng' => '107.6300',
        'jam_buka' => '00:00',
        'jam_tutup' => '23:59',
        'status_verifikasi' => 'terverifikasi',
    ]);

    // 3. Setup Makanan A (Spaghetti - Makanan Berat, Berbayar)
    $this->masterA = MasterMakanan::create([
        'unit_bisnis_id' => $this->unitProfileA->id,
        'nama_makanan' => 'Spaghetti Carbonara',
        'kategori' => 'Makanan Berat',
        'harga' => 25000,
        'berat' => 0.3,
    ]);
    $this->menuA = MenuAktif::create([
        'master_makanan_id' => $this->masterA->id,
        'unit_bisnis_id' => $this->unitProfileA->id,
        'is_gratis' => false,
        'harga_jual' => 15000,
        'stok_porsi' => 8,
        'batas_pengambilan' => now()->addHours(4),
        'status' => 'aktif',
    ]);

    // Setup Makanan B (Roti Coklat - Cemilan / Makanan Ringan, Gratis)
    $this->masterB = MasterMakanan::create([
        'unit_bisnis_id' => $this->unitProfileB->id,
        'nama_makanan' => 'Roti Coklat Keju',
        'kategori' => 'Cemilan / Makanan Ringan',
        'harga' => 10000,
        'berat' => 0.15,
    ]);
    $this->menuB = MenuAktif::create([
        'master_makanan_id' => $this->masterB->id,
        'unit_bisnis_id' => $this->unitProfileB->id,
        'is_gratis' => true,
        'harga_jual' => 0,
        'stok_porsi' => 5,
        'batas_pengambilan' => now()->addHours(6),
        'status' => 'aktif',
    ]);

    // 4. Setup User Tanpa Lokasi
    $this->userNoLoc = User::factory()->create([
        'name' => 'Individu Tanpa Lokasi',
        'email' => 'noloc@sharebite.com',
        'password' => bcrypt('password'),
        'role' => 'individu',
        'latitude' => null,
        'longitude' => null,
    ]);
});

test('TC-DASH-01: Verifikasi Banner Sapaan User Relawan pada Halaman Dashboard', function () {
    $this->browse(function (Browser $browser) {
        $browser->loginAs($this->user)
            ->visit('/user/dashboard')
            ->waitForText('Halo, Individu!')
            ->assertSee('Halo, Individu!')
            ->pause(duskDelay());
    });
});

test('TC-DASH-02: Verifikasi Kalkulasi Statistik Personal Porsi Diambil pada Dashboard', function () {
    // Setup 1 paid order
    Pesanan::create([
        'user_id' => $this->user->id,
        'menu_aktif_id' => $this->menuA->id,
        'unit_bisnis_id' => $this->unitProfileA->id,
        'jumlah_porsi' => 4,
        'total_harga' => 60000,
        'status' => 'dibayar',
        'kode_unik' => 'SB-1234-XYZ',
        'waktu_pesan' => now(),
    ]);

    $this->browse(function (Browser $browser) {
        $browser->loginAs($this->user)
            ->visit('/user/dashboard')
            ->waitForText('Porsi Diambil')
            ->assertSee('4')
            ->pause(duskDelay());
    });
});

test('TC-DASH-03: Verifikasi Kalkulasi Statistik Personal Makanan Terselamatkan & CO2 Dihemat pada Dashboard', function () {
    // Setup 1 paid order
    Pesanan::create([
        'user_id' => $this->user->id,
        'menu_aktif_id' => $this->menuA->id,
        'unit_bisnis_id' => $this->unitProfileA->id,
        'jumlah_porsi' => 4,
        'total_harga' => 60000,
        'status' => 'dibayar',
        'kode_unik' => 'SB-1234-XYZ',
        'waktu_pesan' => now(),
    ]);

    $this->browse(function (Browser $browser) {
        $browser->loginAs($this->user)
            ->visit('/user/dashboard')
            ->waitForText('Makanan Terselamatkan')
            // Makanan terselamatkan: 4 * 0.3 = 1.2 kg
            ->assertSee('1,2')
            // CO2 dihemat: 1.2 * 2.5 = 3.0 kg
            ->assertSee('3,0')
            ->pause(duskDelay());
    });
});

test('TC-DASH-04: Verifikasi Tampilan List Donasi Terdekat dengan Lokasi GPS Aktif', function () {
    $this->browse(function (Browser $browser) {
        $browser->loginAs($this->user)
            ->visit('/user/dashboard')
            ->waitForText('Donasi Terdekat')
            ->assertSee('Spaghetti Carbonara')
            ->assertSee('Toko Wangi')
            ->pause(duskDelay());
    });
});

test('TC-DASH-05: Verifikasi Menampilkan Daftar Aktivitas Terakhir di Dashboard', function () {
    UserActivity::log($this->user->id, 'pemesanan', 'Pesanan Dibuat', 'Membeli roti manis.');

    $this->browse(function (Browser $browser) {
        $browser->loginAs($this->user)
            ->visit('/user/dashboard')
            ->waitForText('Aktivitas Terakhir')
            ->assertSee('Pesanan Dibuat')
            ->assertSee('Membeli roti manis.')
            ->pause(duskDelay());
    });
});

test('TC-DASH-06: Verifikasi Halaman Riwayat Aktivitas Lengkap', function () {
    UserActivity::log($this->user->id, 'pemesanan', 'Pemesanan Roti', 'Telah memesan 2 porsi.');
    UserActivity::log($this->user->id, 'profil', 'Update Profil', 'Mengubah foto profil.');

    $this->browse(function (Browser $browser) {
        $browser->loginAs($this->user)
            ->visit('/user/dashboard')
            ->waitForText('Aktivitas Terakhir')
            ->clickLink('Lihat Semua Aktivitas')
            ->waitForLocation('/user/aktivitas')
            ->assertPathIs('/user/aktivitas')
            ->assertSee('Semua Aktivitas')
            ->assertSee('Pemesanan Roti')
            ->assertSee('Update Profil')
            ->pause(duskDelay());
    });
});

test('TC-DASH-07: Eksplorasi Donasi Terdekat - Pencarian Real-time berdasarkan Nama Makanan', function () {
    $this->browse(function (Browser $browser) {
        $browser->loginAs($this->user)
            ->visit('/user/donasi-terdekat')
            ->waitForText('Semua Donasi Terdekat')
            ->type('input[placeholder="Cari makanan atau toko..."]', 'Spaghetti')
            ->pause(duskDelay())
            ->assertSee('Spaghetti Carbonara')
            ->assertDontSee('Roti Coklat Keju')
            ->pause(duskDelay());
    });
});

test('TC-DASH-08: Eksplorasi Donasi Terdekat - Pencarian Real-time berdasarkan Nama Unit Bisnis', function () {
    $this->browse(function (Browser $browser) {
        $browser->loginAs($this->user)
            ->visit('/user/donasi-terdekat')
            ->waitForText('Semua Donasi Terdekat')
            ->type('input[placeholder="Cari makanan atau toko..."]', 'Bakery')
            ->pause(duskDelay())
            ->assertSee('Roti Coklat Keju')
            ->assertDontSee('Spaghetti Carbonara')
            ->pause(duskDelay());
    });
});

test('TC-DASH-09: Eksplorasi Donasi Terdekat - Penanganan Pencarian Tidak Ditemukan dan Reset Filter', function () {
    $this->browse(function (Browser $browser) {
        $browser->loginAs($this->user)
            ->visit('/user/donasi-terdekat')
            ->waitForText('Semua Donasi Terdekat')
            ->type('input[placeholder="Cari makanan atau toko..."]', 'Soto Ayam Betawi')
            ->pause(duskDelay())
            ->assertDontSee('Spaghetti Carbonara')
            ->assertDontSee('Roti Coklat Keju')
            ->assertSee('Tidak Ada Donasi Ditemukan')
            ->script("
                const btn = Array.from(document.querySelectorAll('button'))
                    .find(el => el.textContent.trim() === 'Reset Filter');
                btn?.click();
            ");
        $browser->pause(duskDelay())
            ->assertSee('Spaghetti Carbonara')
            ->assertSee('Roti Coklat Keju')
            ->pause(duskDelay());
    });
});

test('TC-DASH-10: Eksplorasi Donasi Terdekat - Penyaringan Daftar berdasarkan Kategori (Category Pills)', function () {
    $this->browse(function (Browser $browser) {
        $browser->loginAs($this->user)
            ->visit('/user/donasi-terdekat')
            ->waitForText('Semua Donasi Terdekat')
            ->script("
                const btn = Array.from(document.querySelectorAll('button'))
                    .find(el => el.textContent.trim() === 'Cemilan');
                btn?.click();
            ");
        $browser->pause(duskDelay())
            ->assertSee('Roti Coklat Keju')
            ->assertDontSee('Spaghetti Carbonara')
            ->pause(duskDelay());
    });
});

test('TC-DASH-11: Eksplorasi Donasi Terdekat - Penyaringan Daftar berdasarkan Radius Jarak (Distance Dropdown)', function () {
    $this->browse(function (Browser $browser) {
        $browser->loginAs($this->user)
            ->visit('/user/donasi-terdekat')
            ->waitForText('Semua Donasi Terdekat')
            ->select('select[x-model="selectedDistance"]', '1')
            ->pause(duskDelay())
            ->assertSee('Spaghetti Carbonara')
            ->assertDontSee('Roti Coklat Keju')
            ->pause(duskDelay());
    });
});

test('TC-DASH-12: Eksplorasi Donasi Terdekat - Penyaringan Daftar berdasarkan Status Harga', function () {
    $this->browse(function (Browser $browser) {
        $browser->loginAs($this->user)
            ->visit('/user/donasi-terdekat')
            ->waitForText('Semua Donasi Terdekat')
            // Gratis
            ->select('select[x-model="selectedPrice"]', 'gratis')
            ->pause(duskDelay())
            ->assertSee('Roti Coklat Keju')
            ->assertDontSee('Spaghetti Carbonara')
            // Berbayar
            ->select('select[x-model="selectedPrice"]', 'berbayar')
            ->pause(duskDelay())
            ->assertSee('Spaghetti Carbonara')
            ->assertDontSee('Roti Coklat Keju')
            ->pause(duskDelay());
    });
});

test('TC-DASH-13: Dashboard menyembunyikan makanan dan menampilkan warning jika lokasi null', function () {
    $this->browse(function (Browser $browser) {
        $browser->loginAs($this->userNoLoc)
            ->visit('/user/dashboard')
            ->waitForText('Halo, Individu!')
            ->assertSee('Tentukan lokasi untuk melihat makanan terdekat anda.')
            ->assertDontSee('Ambil')
            ->pause(duskDelay());
    });
});

test('TC-DASH-14: Dashboard Radius Anda menampilkan tombol Atur Lokasi jika lokasi null', function () {
    $this->browse(function (Browser $browser) {
        $browser->loginAs($this->userNoLoc)
            ->visit('/user/dashboard')
            ->waitForText('Halo, Individu!')
            ->assertSee('Titik Lokasi Belum Ditentukan')
            ->assertSee('Atur Lokasi Sekarang')
            ->clickLink('Atur Lokasi Sekarang')
            ->waitForLocation('/user/lokasi')
            ->assertPathIs('/user/lokasi')
            ->pause(duskDelay());
    });
});

test('TC-DASH-15: Eksplorasi terdekat menampilkan warning dan tombol Atur Lokasi jika lokasi null', function () {
    $this->browse(function (Browser $browser) {
        $browser->loginAs($this->userNoLoc)
            ->visit('/user/donasi-terdekat')
            ->waitForText('Semua Donasi Terdekat')
            ->assertSee('Lokasi Belum Ditentukan')
            ->assertSee('Tidak bisa menampilkan lokasi terdekat, harap tentukan lokasi terlebih dahulu')
            ->assertSee('Atur Lokasi')
            ->clickLink('Atur Lokasi')
            ->waitForLocation('/user/lokasi')
            ->assertPathIs('/user/lokasi')
            ->pause(duskDelay());
    });
});
