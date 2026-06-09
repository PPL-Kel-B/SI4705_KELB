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

test('TC-DASH-01: Menampilkan dashboard user relawan dengan kalkulasi statistik yang benar', function () {
    // 1. Setup User Individu
    $user = User::factory()->create([
        'name' => 'Individu Peduli',
        'email' => 'individu@sharebite.com',
        'password' => bcrypt('password'),
        'role' => 'individu',
        'latitude' => '-6.9271',
        'longitude' => '107.6186',
        'alamat' => 'Bandung, Jawa Barat'
    ]);

    // 2. Setup Unit Bisnis
    $unitUser = User::factory()->create([
        'name' => 'Toko Roti Wangi',
        'email' => 'unit@sharebite.com',
        'password' => bcrypt('password'),
        'role' => 'unit_bisnis',
        'latitude' => '-6.9300',
        'longitude' => '107.6200',
    ]);
    
    $unitProfile = UnitBisnisProfile::create([
        'user_id' => $unitUser->id,
        'nama_usaha' => 'Toko Roti Wangi',
        'jenis_usaha' => 'Kuliner',
        'radius_penjemputan' => 5,
        'lokasi_lat' => '-6.9300',
        'lokasi_lng' => '107.6200',
        'jam_buka' => '00:00',
        'jam_tutup' => '23:59',
        'status_verifikasi' => 'terverifikasi',
    ]);

    // 3. Setup Makanan
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

    // 4. Setup Pesanan
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

    // 5. Setup User Activity
    UserActivity::log($user->id, 'pemesanan', 'Pesanan Dibuat', 'Membeli roti manis.');

    $this->browse(function (Browser $browser) use ($user) {
        $browser->loginAs($user)
            ->visit('/user/dashboard')
            ->waitForText('Halo, Individu!')
            ->pause(duskDelay()) // Pause to see dashboard banner loaded
            ->assertSee('Halo, Individu!')
            ->assertSee('Porsi Diambil')
            ->assertSee('4')
            ->assertSee('Makanan Terselamatkan')
            ->assertSee('1,0')
            ->assertSee('CO2 Dihemat')
            ->assertSee('2,5')
            ->assertSee('Aktivitas Terakhir')
            ->assertSee('Pesanan Dibuat')
            ->assertSee('Membeli roti manis.')
            ->assertSee('Donasi Terdekat')
            ->assertSee('Roti Manis')
            ->assertSee('Toko Roti Wangi')
            ->pause(duskDelay()); // Final pause for display
    });
});

test('TC-DASH-02: Menampilkan daftar aktivitas terakhir di dashboard dan halaman aktivitas', function () {
    $user = User::factory()->create([
        'name' => 'Individu Peduli',
        'email' => 'individu@sharebite.com',
        'password' => bcrypt('password'),
        'role' => 'individu',
        'latitude' => '-6.9271',
        'longitude' => '107.6186'
    ]);

    UserActivity::log($user->id, 'pemesanan', 'Pemesanan Roti', 'Telah memesan 2 porsi.');
    UserActivity::log($user->id, 'profil', 'Update Profil', 'Mengubah foto profil.');
    UserActivity::log($user->id, 'pengaturan', 'Ganti Password', 'Memperbarui kata sandi.');

    $this->browse(function (Browser $browser) use ($user) {
        $browser->loginAs($user)
            ->visit('/user/dashboard')
            ->waitForText('Aktivitas Terakhir')
            ->pause(duskDelay()) // Pause to view dashboard activities list
            ->assertSee('Pemesanan Roti')
            ->assertSee('Update Profil')
            ->assertSee('Ganti Password')
            ->clickLink('Lihat Semua Aktivitas')
            ->waitForLocation('/user/aktivitas')
            ->pause(duskDelay()) // Pause to see redirection completed
            ->assertPathIs('/user/aktivitas')
            ->assertSee('Semua Aktivitas')
            ->assertSee('Pemesanan Roti')
            ->assertSee('Update Profil')
            ->assertSee('Ganti Password')
            ->pause(duskDelay()); // Final pause for display
    });
});

test('TC-DASH-03: Mencari makanan di halaman eksplorasi terdekat dengan filter yang sangat detail', function () {
    // 1. Setup User
    $user = User::factory()->create([
        'name' => 'Individu Peduli',
        'email' => 'individu@sharebite.com',
        'password' => bcrypt('password'),
        'role' => 'individu',
        'latitude' => '-6.9271',
        'longitude' => '107.6186'
    ]);

    // 2. Setup Unit Bisnis A (Toko Wangi - 0.3 km away)
    $unitUserA = User::factory()->create([
        'name' => 'Toko Wangi',
        'email' => 'unitA@sharebite.com',
        'password' => bcrypt('password'),
        'role' => 'unit_bisnis',
        'latitude' => '-6.9300',
        'longitude' => '107.6200',
    ]);
    $unitProfileA = UnitBisnisProfile::create([
        'user_id' => $unitUserA->id,
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
    $unitUserB = User::factory()->create([
        'name' => 'Bakery Lestari',
        'email' => 'unitB@sharebite.com',
        'password' => bcrypt('password'),
        'role' => 'unit_bisnis',
        'latitude' => '-6.9600',
        'longitude' => '107.6300',
    ]);
    $unitProfileB = UnitBisnisProfile::create([
        'user_id' => $unitUserB->id,
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
    $masterA = MasterMakanan::create([
        'unit_bisnis_id' => $unitProfileA->id,
        'nama_makanan' => 'Spaghetti Carbonara',
        'kategori' => 'Makanan Berat',
        'harga' => 25000,
        'berat' => 0.3,
    ]);
    $menuA = MenuAktif::create([
        'master_makanan_id' => $masterA->id,
        'unit_bisnis_id' => $unitProfileA->id,
        'is_gratis' => false,
        'harga_jual' => 15000,
        'stok_porsi' => 8,
        'batas_pengambilan' => now()->addHours(4),
        'status' => 'aktif',
    ]);

    // Setup Makanan B (Roti Coklat - Cemilan / Makanan Ringan, Gratis)
    $masterB = MasterMakanan::create([
        'unit_bisnis_id' => $unitProfileB->id,
        'nama_makanan' => 'Roti Coklat Keju',
        'kategori' => 'Cemilan / Makanan Ringan',
        'harga' => 10000,
        'berat' => 0.15,
    ]);
    $menuB = MenuAktif::create([
        'master_makanan_id' => $masterB->id,
        'unit_bisnis_id' => $unitProfileB->id,
        'is_gratis' => true,
        'harga_jual' => 0,
        'stok_porsi' => 5,
        'batas_pengambilan' => now()->addHours(6),
        'status' => 'aktif',
    ]);

    $this->browse(function (Browser $browser) use ($user) {
        $browser->loginAs($user)
            ->visit('/user/donasi-terdekat')
            ->waitForText('Semua Donasi Terdekat')
            ->pause(duskDelay()) // Initial load pause
            ->assertSee('Spaghetti Carbonara')
            ->assertSee('Roti Coklat Keju');

        // A. Filter Pencarian Nama Makanan (Spaghetti)
        $browser->type('input[placeholder="Cari makanan atau toko..."]', 'Spaghetti')
            ->pause(duskDelay()) // Pause to see user typing "Spaghetti" and matching results
            ->assertSee('Spaghetti Carbonara')
            ->assertDontSee('Roti Coklat Keju');

        // B. Filter Pencarian Nama Toko (Bakery)
        $browser->clear('input[placeholder="Cari makanan atau toko..."]')
            ->type('input[placeholder="Cari makanan atau toko..."]', 'Bakery')
            ->pause(duskDelay()) // Pause to see search term clear and change to "Bakery"
            ->assertSee('Roti Coklat Keju')
            ->assertDontSee('Spaghetti Carbonara');

        // C. Filter Pencarian Tidak Ditemukan (Empty State)
        $browser->clear('input[placeholder="Cari makanan atau toko..."]')
            ->type('input[placeholder="Cari makanan atau toko..."]', 'Soto Ayam Betawi')
            ->pause(duskDelay()) // Pause to see empty state message
            ->assertDontSee('Spaghetti Carbonara')
            ->assertDontSee('Roti Coklat Keju')
            ->assertSee('Tidak Ada Donasi Ditemukan')
            ->assertSee('Reset Filter');

        // D. Uji Tombol Reset Filter
        $browser->script("
            const btn = Array.from(document.querySelectorAll('button'))
                .find(el => el.textContent.trim() === 'Reset Filter');
            btn?.click();
        ");
        $browser->pause(duskDelay()) // Pause to see items restore
            ->assertSee('Spaghetti Carbonara')
            ->assertSee('Roti Coklat Keju');

        // E. Filter Kategori (Cemilan)
        $browser->script("
            const btn = Array.from(document.querySelectorAll('button'))
                .find(el => el.textContent.trim() === 'Cemilan');
            btn?.click();
        ");
        $browser->pause(duskDelay()) // Pause to see Cemilan pill active
            ->assertSee('Roti Coklat Keju')
            ->assertDontSee('Spaghetti Carbonara');

        // Balikkan Kategori ke Semua
        $browser->script("
            const btn = Array.from(document.querySelectorAll('button'))
                .find(el => el.textContent.trim() === 'Semua');
            btn?.click();
        ");
        $browser->pause(duskDelay());

        // F. Filter Jarak (< 1 km)
        $browser->select('select[x-model="selectedDistance"]', '1')
            ->pause(duskDelay()) // Pause to see distance filter filter-out Roti Coklat
            ->assertSee('Spaghetti Carbonara')
            ->assertDontSee('Roti Coklat Keju');

        // Balikkan Jarak ke Semua Jarak
        $browser->select('select[x-model="selectedDistance"]', 'all')
            ->pause(duskDelay());

        // G. Filter Harga Gratis vs Berbayar
        // Tipe: Gratis
        $browser->select('select[x-model="selectedPrice"]', 'gratis')
            ->pause(duskDelay()) // Pause to see only free food
            ->assertSee('Roti Coklat Keju')
            ->assertDontSee('Spaghetti Carbonara');

        // Tipe: Berbayar
        $browser->select('select[x-model="selectedPrice"]', 'berbayar')
            ->pause(duskDelay()) // Pause to see only paid food
            ->assertSee('Spaghetti Carbonara')
            ->assertDontSee('Roti Coklat Keju')
            ->pause(duskDelay()); // Final pause for display
    });
});

test('TC-DASH-04: Menyembunyikan makanan di dashboard jika lokasi user belum diset', function () {
    $user = User::factory()->create([
        'name' => 'Individu Tanpa Lokasi',
        'email' => 'noloc@sharebite.com',
        'password' => bcrypt('password'),
        'role' => 'individu',
        'latitude' => null,
        'longitude' => null,
    ]);

    $this->browse(function (Browser $browser) use ($user) {
        $browser->loginAs($user)
            ->visit('/user/dashboard')
            ->waitForText('Halo, Individu!')
            ->pause(duskDelay()) // Pause to see location warning on dashboard
            ->assertSee('Halo, Individu!')
            ->assertSee('Tentukan lokasi untuk melihat makanan terdekat anda.')
            ->assertDontSee('Ambil')
            ->assertSee('Titik Lokasi Belum Ditentukan')
            ->assertSee('Atur Lokasi Sekarang')
            ->clickLink('Atur Lokasi Sekarang')
            ->waitForLocation('/user/lokasi')
            ->pause(duskDelay()) // Pause to see redirection to Sabrina's feature
            ->assertPathIs('/user/lokasi')
            ->pause(duskDelay()); // Final pause
    });
});

test('TC-DASH-05: Menyembunyikan makanan di halaman eksplorasi jika lokasi user belum diset', function () {
    $user = User::factory()->create([
        'name' => 'Individu Tanpa Lokasi',
        'email' => 'noloc@sharebite.com',
        'password' => bcrypt('password'),
        'role' => 'individu',
        'latitude' => null,
        'longitude' => null,
    ]);

    $this->browse(function (Browser $browser) use ($user) {
        $browser->loginAs($user)
            ->visit('/user/donasi-terdekat')
            ->waitForText('Semua Donasi Terdekat')
            ->pause(duskDelay()) // Pause to see location warning on nearby page
            ->assertSee('Lokasi Belum Ditentukan')
            ->assertSee('Tidak bisa menampilkan lokasi terdekat, harap tentukan lokasi terlebih dahulu')
            ->assertSee('Atur Lokasi')
            ->clickLink('Atur Lokasi')
            ->waitForLocation('/user/lokasi')
            ->pause(duskDelay()) // Pause to see redirection completed
            ->assertPathIs('/user/lokasi')
            ->pause(duskDelay()); // Final pause
    });
});
