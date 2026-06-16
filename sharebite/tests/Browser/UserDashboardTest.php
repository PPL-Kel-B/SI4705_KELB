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
if (! function_exists('duskDelay')) {
    function duskDelay(): int {
        return (int) env('DUSK_PAUSE_MS', 3500);
    }
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

    // Setup Makanan B (Roti Coklat - Makanan Ringan, Gratis)
    $this->masterB = MasterMakanan::create([
        'unit_bisnis_id' => $this->unitProfileB->id,
        'nama_makanan' => 'Roti Coklat Keju',
        'kategori' => 'Makanan Ringan',
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

test('TC-DASH-04: Verifikasi Tampilan List Donasi Terdekat dengan Lokasi', function () {
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

test('TC-DASH-07: Eksplorasi Donasi Terdekat - Pencarian Real-time berdasarkan Nama Makanan dan Nama Unit Bisnis', function () {
    $this->browse(function (Browser $browser) {
        $browser->loginAs($this->user)
            ->visit('/user/dashboard')
            ->waitForText('Halo, Individu!')
            ->pause(duskDelay())
            ->clickLink('Lihat Semua')
            ->waitForLocation('/user/donasi-terdekat')
            ->waitForText('Semua Donasi Terdekat')
            // Search by Food Name
            ->typeSlowly('input[placeholder="Cari makanan atau toko..."]', 'Spaghetti', 100)
            ->pause(duskDelay())
            ->assertSee('Spaghetti Carbonara')
            ->assertDontSee('Roti Coklat Keju')
            // Search by Business Name (Clear search input first via Javascript)
            ->script("
                const input = document.querySelector('input[placeholder=\"Cari makanan atau toko...\"]');
                input.value = '';
                input.dispatchEvent(new Event('input'));
            ");
        $browser->pause(500)
            ->typeSlowly('input[placeholder="Cari makanan atau toko..."]', 'Bakery', 100)
            ->pause(duskDelay())
            ->assertSee('Roti Coklat Keju')
            ->assertDontSee('Spaghetti Carbonara')
            ->pause(duskDelay());
    });
});

test('TC-DASH-08: Eksplorasi Donasi Terdekat - Penanganan Pencarian Tidak Ditemukan dan Reset Filter', function () {
    $this->browse(function (Browser $browser) {
        $browser->loginAs($this->user)
            ->visit('/user/dashboard')
            ->waitForText('Halo, Individu!')
            ->pause(duskDelay())
            ->clickLink('Lihat Semua')
            ->waitForLocation('/user/donasi-terdekat')
            ->waitForText('Semua Donasi Terdekat')
            ->typeSlowly('input[placeholder="Cari makanan atau toko..."]', 'Soto Ayam Betawi', 100)
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

test('TC-DASH-09: Eksplorasi Donasi Terdekat - Penyaringan Daftar berdasarkan Kategori (Category Pills)', function () {
    $this->browse(function (Browser $browser) {
        $browser->loginAs($this->user)
            ->visit('/user/dashboard')
            ->waitForText('Halo, Individu!')
            ->pause(duskDelay())
            ->clickLink('Lihat Semua')
            ->waitForLocation('/user/donasi-terdekat')
            ->waitForText('Semua Donasi Terdekat')
            ->script("
                const btn = Array.from(document.querySelectorAll('button'))
                    .find(el => el.textContent.trim() === 'Makanan Ringan');
                btn?.click();
            ");
        $browser->pause(duskDelay())
            ->assertSee('Roti Coklat Keju')
            ->assertDontSee('Spaghetti Carbonara')
            ->pause(duskDelay());
    });
});

test('TC-DASH-10: Eksplorasi Donasi Terdekat - Penyaringan Daftar berdasarkan Radius Jarak (Distance Dropdown)', function () {
    $this->browse(function (Browser $browser) {
        $browser->loginAs($this->user)
            ->visit('/user/dashboard')
            ->waitForText('Halo, Individu!')
            ->pause(duskDelay())
            ->clickLink('Lihat Semua')
            ->waitForLocation('/user/donasi-terdekat')
            ->waitForText('Semua Donasi Terdekat')
            ->pause(1000)
            // Highlight the distance dropdown to show where the test is focusing
            ->script("
                const el = document.querySelector('select[x-model=\"selectedDistance\"]');
                el.style.border = '3px solid #1cb764';
                el.style.backgroundColor = '#eefcf4';
            ");
        $browser->pause(1500)
            ->click('select[x-model="selectedDistance"]')
            ->pause(1500)
            ->select('select[x-model="selectedDistance"]', '1')
            ->script("
                const el = document.querySelector('select[x-model=\"selectedDistance\"]');
                el.style.border = '';
                el.style.backgroundColor = '';
                el.blur();
            ");
        $browser->pause(500)
            ->click('h1') // Click page title to dismiss native dropdown popup
            ->pause(duskDelay())
            ->assertSee('Spaghetti Carbonara')
            ->assertDontSee('Roti Coklat Keju')
            ->pause(duskDelay());
    });
});

test('TC-DASH-11: Eksplorasi Donasi Terdekat - Penyaringan Daftar berdasarkan Status Harga dan Range Harga', function () {
    $this->browse(function (Browser $browser) {
        $browser->loginAs($this->user)
            ->visit('/user/dashboard')
            ->waitForText('Halo, Individu!')
            ->pause(duskDelay())
            ->clickLink('Lihat Semua')
            ->waitForLocation('/user/donasi-terdekat')
            ->waitForText('Semua Donasi Terdekat')
            ->pause(1000)

            // 1. Highlight Price Type Dropdown
            ->script("
                const el = document.querySelector('select[x-model=\"selectedPrice\"]');
                el.style.border = '3px solid #1cb764';
                el.style.backgroundColor = '#eefcf4';
            ");
        $browser->pause(1500)
            ->click('select[x-model="selectedPrice"]')
            ->pause(1500)
            // Filter: Gratis
            ->select('select[x-model="selectedPrice"]', 'gratis')
            ->script("
                document.querySelector('select[x-model=\"selectedPrice\"]').blur();
            ");
        $browser->pause(500)
            ->click('h1') // Click outside
            ->pause(duskDelay())
            ->assertSee('Roti Coklat Keju')
            ->assertDontSee('Spaghetti Carbonara')

            // 2. Highlight Price Type Dropdown for "Berbayar"
            ->script("
                const el = document.querySelector('select[x-model=\"selectedPrice\"]');
                el.style.border = '3px solid #1cb764';
                el.style.backgroundColor = '#eefcf4';
            ");
        $browser->pause(1500)
            ->click('select[x-model="selectedPrice"]')
            ->pause(1500)
            // Filter: Berbayar
            ->select('select[x-model="selectedPrice"]', 'berbayar')
            ->script("
                document.querySelector('select[x-model=\"selectedPrice\"]').blur();
            ");
        $browser->pause(500)
            ->click('h1') // Click outside
            ->pause(duskDelay())
            ->assertSee('Spaghetti Carbonara')
            ->assertDontSee('Roti Coklat Keju')

            // 3. Reset to "Semua Harga"
            ->script("
                const el = document.querySelector('select[x-model=\"selectedPrice\"]');
                el.style.border = '3px solid #1cb764';
                el.style.backgroundColor = '#eefcf4';
            ");
        $browser->pause(1500)
            ->click('select[x-model="selectedPrice"]')
            ->pause(1500)
            ->select('select[x-model="selectedPrice"]', 'all')
            ->script("
                const el = document.querySelector('select[x-model=\"selectedPrice\"]');
                el.style.border = '';
                el.style.backgroundColor = '';
                el.blur();
            ");
        $browser->pause(500)
            ->click('h1') // Click outside
            ->pause(duskDelay())
            ->assertSee('Roti Coklat Keju')
            ->assertSee('Spaghetti Carbonara')

            // 4. Highlight & Test Price Range Slider
            ->script("
                const slider = document.querySelector('input[type=\"range\"]');
                slider.style.outline = '3px solid #1cb764';
                slider.style.outlineOffset = '2px';
            ");
        $browser->pause(1500)
            ->script("
                const slider = document.querySelector('input[type=\"range\"]');
                slider.value = 10000;
                slider.dispatchEvent(new Event('input'));
            ");
        $browser->pause(duskDelay())
            ->script("
                const slider = document.querySelector('input[type=\"range\"]');
                slider.style.outline = '';
                slider.style.outlineOffset = '';
            ");
        $browser->pause(500)
            // Roti Coklat Keju is free (Rp 0 <= 10000) -> should see it
            ->assertSee('Roti Coklat Keju')
            // Spaghetti Carbonara is Rp 15000 (> 10000) -> should not see it
            ->assertDontSee('Spaghetti Carbonara')
            ->pause(duskDelay());
    });
});

test('TC-DASH-12: Dashboard menyembunyikan makanan dan menampilkan warning jika lokasi null', function () {
    $this->browse(function (Browser $browser) {
        $browser->loginAs($this->userNoLoc)
            ->visit('/user/dashboard')
            ->waitForText('Halo, Individu!')
            ->assertSee('Tentukan lokasi untuk melihat makanan terdekat anda.')
            ->assertDontSee('Ambil')
            ->pause(duskDelay());
    });
});

test('TC-DASH-13: Dashboard Radius Anda menampilkan tombol Atur Lokasi jika lokasi null', function () {
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

test('TC-DASH-14: Eksplorasi terdekat menampilkan warning dan tombol Atur Lokasi jika lokasi null', function () {
    $this->browse(function (Browser $browser) {
        $browser->loginAs($this->userNoLoc)
            ->visit('/user/dashboard')
            ->waitForText('Halo, Individu!')
            ->pause(duskDelay())
            ->clickLink('Lihat Semua')
            ->waitForLocation('/user/donasi-terdekat')
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

test('TC-DASH-15: Detail Makanan - Verifikasi Informasi Lengkap, Lokasi, Konten Jaminan Kualitas, dan Breadcrumbs', function () {
    $this->browse(function (Browser $browser) {
        $browser->loginAs($this->user)
            ->visit('/user/dashboard')
            ->waitForText('Halo, Individu!')
            ->pause(duskDelay())
            ->clickLink('Spaghetti Carbonara')
            ->waitForLocation('/user/makanan/' . $this->menuA->id)
            ->assertPathIs('/user/makanan/' . $this->menuA->id)
            ->waitForText('Spaghetti Carbonara')
            // 1. Assert Breadcrumbs
            ->assertSeeIn('nav.text-sm', 'Dashboard')
            ->assertSeeIn('nav.text-sm', 'Makanan')
            ->assertSeeIn('nav.text-sm', 'Spaghetti Carbonara')
            // 2. Assert Dynamic Remaining Time
            ->assertSee('jam lagi')
            // 3. Assert Distance tag
            ->assertSee('0,4 km')
            // 4. Assert portion and price
            ->assertSee('8 Porsi')
            ->assertSee('Rp 15.000')
            // 5. Assert vendor info
            ->assertSee('Toko Wangi')
            ->assertSee('Alamat belum diatur')
            ->assertSee('Kunjungi Profil')
            // 6. Assert maps link
            ->assertSourceHas('google.com/maps/search/?api=1')
            // 7. Assert quality assurance card
            ->assertSee('JAMINAN KUALITAS')
            ->assertSee('Mitra kami telah melewati verifikasi standar keamanan pangan')
            ->pause(duskDelay());
    });
});

test('TC-DASH-16: Detail Makanan - Verifikasi Counter Portion Adjuster, Limit Batas Stok (Min/Max), dan Redirect ke Pembayaran', function () {
    $this->browse(function (Browser $browser) {
        $browser->loginAs($this->user)
            ->visit('/user/dashboard')
            ->waitForText('Halo, Individu!')
            ->pause(duskDelay())
            ->clickLink('Spaghetti Carbonara')
            ->waitForLocation('/user/makanan/' . $this->menuA->id)
            ->waitForText('Spaghetti Carbonara')
            // Initial qty is 1, price Rp 15.000
            ->assertSee('Rp 15.000')
            // Try to click '-' when qty is 1 (min limit check)
            ->click('button[class*="bg-gray-100"]')
            ->pause(500)
            ->assertSee('Rp 15.000')
            // Click '+' button 7 times to reach max stock (8 porsi)
            ->click('button[class*="bg-[#1cb764]"][class*="hover:bg-[#159f54]"]')
            ->click('button[class*="bg-[#1cb764]"][class*="hover:bg-[#159f54]"]')
            ->click('button[class*="bg-[#1cb764]"][class*="hover:bg-[#159f54]"]')
            ->click('button[class*="bg-[#1cb764]"][class*="hover:bg-[#159f54]"]')
            ->click('button[class*="bg-[#1cb764]"][class*="hover:bg-[#159f54]"]')
            ->click('button[class*="bg-[#1cb764]"][class*="hover:bg-[#159f54]"]')
            ->click('button[class*="bg-[#1cb764]"][class*="hover:bg-[#159f54]"]')
            ->pause(duskDelay())
            ->assertSee('Rp 120.000') // 8 * 15.000
            // Try to click '+' again when qty is 8 (max limit check)
            ->click('button[class*="bg-[#1cb764]"][class*="hover:bg-[#159f54]"]')
            ->pause(500)
            ->assertSee('Rp 120.000')
            // Click 'Ambil Makanan' to redirect to payments page
            ->press('Ambil Makanan')
            ->waitForLocation('/user/dashboard/' . $this->menuA->id . '/pembayaran')
            ->assertPathIs('/user/dashboard/' . $this->menuA->id . '/pembayaran')
            ->assertQueryStringHas('qty', '8')
            ->pause(duskDelay());
    });
});

test('TC-DASH-17: Detail Makanan - Verifikasi Rekomendasi Daftar Makanan Serupa (Similar Items)', function () {
    // Setup another food item in the same category ("Makanan Berat")
    $masterC = MasterMakanan::create([
        'unit_bisnis_id' => $this->unitProfileA->id,
        'nama_makanan' => 'Nasi Goreng Spesial',
        'kategori' => 'Makanan Berat',
        'harga' => 20000,
        'berat' => 0.4,
    ]);
    $menuC = MenuAktif::create([
        'master_makanan_id' => $masterC->id,
        'unit_bisnis_id' => $this->unitProfileA->id,
        'is_gratis' => false,
        'harga_jual' => 12000,
        'stok_porsi' => 10,
        'batas_pengambilan' => now()->addHours(5),
        'status' => 'aktif',
    ]);

    $this->browse(function (Browser $browser) use ($menuC) {
        $browser->loginAs($this->user)
            ->visit('/user/dashboard')
            ->waitForText('Halo, Individu!')
            ->pause(duskDelay())
            ->clickLink('Spaghetti Carbonara')
            ->waitForLocation('/user/makanan/' . $this->menuA->id)
            ->waitForText('Spaghetti Carbonara')
            // Assert "Makanan Berat Serupa" section header is shown
            ->assertSee('Makanan Berat Serupa')
            // Assert that Nasi Goreng Spesial is listed as a similar food card
            ->assertSee('Nasi Goreng Spesial')
            ->assertSee('Rp 12.000')
            ->pause(duskDelay());
    });
});

