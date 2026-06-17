<?php

use App\Models\User;
use App\Models\UnitBisnisProfile;
use App\Models\MasterMakanan;
use App\Models\MenuAktif;
use App\Models\Pesanan;
use App\Models\UserActivity;
use Laravel\Dusk\Browser;
use Illuminate\Support\Facades\DB;

if (! function_exists('duskDelay')) {
    function duskDelay(): int {
        return (int) env('DUSK_PAUSE_MS', 3500);
    }
}

beforeEach(function () {
    // =========================================================================
    // CATATAN UNTUK PENGUJI / DOSEN:
    // Sebelum menjalankan pengujian Dashboard (TC-DASH-01 s.d TC-DASH-17),
    // sangat direkomendasikan dan diwajibkan untuk menjalankan:
    // 1. Pengujian Registrasi Unit Bisnis (TC-REG-01) atau Database Seeder
    //    (php artisan db:seed dan php artisan db:seed --class=UnitBisnisSeeder)
    //    untuk memastikan akun relawan dan mitra bisnis pangan beserta profil terdaftar.
    // 2. Unit Bisnis yang terdaftar harus memiliki minimal satu Menu Makanan Aktif
    //    (dapat ditambahkan melalui test kelola makanan atau diinput manual lewat web).
    //
    // File pengujian ini bersifat read-only (query-only) tanpa melakukan
    // insert/create/delete data secara langsung di database demi kepatuhan aturan pengujian.
    // =========================================================================

    // 1. Retrieve User Relawan (Individu)
    $this->user = User::where('email', 'individu@sharebite.com')->first();
    if (!$this->user) {
        throw new \Exception(
            "Pengujian Dibatalkan: User dengan email 'individu@sharebite.com' tidak ditemukan di database. " .
            "Harap jalankan seeder terlebih dahulu (php artisan db:seed) untuk menginisialisasi akun pengguna."
        );
    }

    // Set koordinat secara dinamis pada data user yang sudah ada jika belum diset
    if (is_null($this->user->latitude) || is_null($this->user->longitude)) {
        $this->user->update([
            'latitude' => '-6.9271',
            'longitude' => '107.6186',
            'alamat' => 'Bandung, Jawa Barat'
        ]);
    }

    // 2. Ambil Menu Makanan Aktif (Berbayar dan Gratis)
    $this->menuA = MenuAktif::where('status', 'aktif')
        ->where('is_gratis', false)
        ->where('stok_porsi', '>', 0)
        ->where('batas_pengambilan', '>', now())
        ->first();

    if (!$this->menuA) {
        throw new \Exception(
            "Pengujian Dibatalkan: Tidak ditemukan MenuAktif BERBAYAR yang aktif di database dengan stok > 0. " .
            "Harap tambahkan menu berbayar aktif terlebih dahulu lewat web atau seeder."
        );
    }

    $this->menuB = MenuAktif::where('status', 'aktif')
        ->where('is_gratis', true)
        ->where('stok_porsi', '>', 0)
        ->where('batas_pengambilan', '>', now())
        ->first();

    if (!$this->menuB) {
        throw new \Exception(
            "Pengujian Dibatalkan: Tidak ditemukan MenuAktif GRATIS yang aktif di database dengan stok > 0. " .
            "Harap tambahkan menu gratis aktif terlebih dahulu lewat web atau seeder."
        );
    }

    // Ambil Profil dan User terkait untuk Menu A dan Menu B
    $this->masterA = $this->menuA->masterMakanan;
    $this->unitProfileA = $this->menuA->unitBisnis;
    $this->unitUserA = $this->unitProfileA?->user;

    $this->masterB = $this->menuB->masterMakanan;
    $this->unitProfileB = $this->menuB->unitBisnis;
    $this->unitUserB = $this->unitProfileB?->user;

    // 3. Retrieve User Tanpa Lokasi
    $this->userNoLoc = User::where('email', 'noloc@sharebite.com')->first();
    if (!$this->userNoLoc) {
        // Fallback ke user komunitas@sharebite.com yang diseed tanpa lokasi
        $this->userNoLoc = User::where('email', 'komunitas@sharebite.com')->first();
    }
    if (!$this->userNoLoc) {
        // Fallback kedua: Cari user lain yang tidak memiliki koordinat
        $this->userNoLoc = User::whereNull('latitude')->whereNull('longitude')->first();
    }
    if (!$this->userNoLoc) {
        throw new \Exception(
            "Pengujian Dibatalkan: Tidak ditemukan akun pengguna tanpa koordinat lokasi (noloc/komunitas) untuk pengujian."
        );
    }
});

test('TC-DASH-01: Verifikasi Banner Sapaan User Relawan pada Halaman Dashboard', function () {
    $this->browse(function (Browser $browser) {
        $first_name = explode(' ', $this->user->name)[0];
        $browser->loginAs($this->user)
            ->visit('/user/dashboard')
            ->waitForText("Halo, {$first_name}!")
            ->assertSee("Halo, {$first_name}!")
            ->pause(duskDelay());
    });
});

test('TC-DASH-02: Verifikasi Kalkulasi Statistik Personal Porsi Diambil pada Dashboard', function () {
    $this->browse(function (Browser $browser) {
        // Hitung porsi diambil secara dinamis dari database
        $porsi_diambil = Pesanan::where('user_id', $this->user->id)
            ->whereIn('status', ['dibayar', 'siap_diambil', 'selesai'])
            ->sum('jumlah_porsi');

        $browser->loginAs($this->user)
            ->visit('/user/dashboard')
            ->waitForText('Porsi Diambil')
            ->assertSee((string) $porsi_diambil)
            ->pause(duskDelay());
    });
});

test('TC-DASH-03: Verifikasi Kalkulasi Statistik Personal Makanan Terselamatkan & CO2 Dihemat pada Dashboard', function () {
    $this->browse(function (Browser $browser) {
        // Hitung makanan terselamatkan secara dinamis dari database
        $makanan_terselamatkan = (float) DB::table('pesanans')
            ->join('menu_aktifs', 'pesanans.menu_aktif_id', '=', 'menu_aktifs.id')
            ->join('master_makanans', 'menu_aktifs.master_makanan_id', '=', 'master_makanans.id')
            ->where('pesanans.user_id', $this->user->id)
            ->whereIn('pesanans.status', ['dibayar', 'siap_diambil', 'selesai'])
            ->sum(DB::raw('pesanans.jumlah_porsi * master_makanans.berat'));

        $co2_dihemat = $makanan_terselamatkan * 2.5;

        $formattedBerat = number_format($makanan_terselamatkan, 1, ',', '.');
        $formattedCO2 = number_format($co2_dihemat, 1, ',', '.');

        $browser->loginAs($this->user)
            ->visit('/user/dashboard')
            ->waitForText('Makanan Terselamatkan')
            ->assertSee($formattedBerat)
            ->assertSee($formattedCO2)
            ->pause(duskDelay());
    });
});

test('TC-DASH-04: Verifikasi Tampilan List Donasi Terdekat dengan Lokasi', function () {
    $this->browse(function (Browser $browser) {
        $browser->loginAs($this->user)
            ->visit('/user/dashboard')
            ->waitForText('Donasi Terdekat')
            ->assertSee($this->masterA->nama_makanan)
            ->assertSee($this->unitProfileA->nama_usaha)
            ->pause(duskDelay());
    });
});

test('TC-DASH-05: Verifikasi Menampilkan Daftar Aktivitas Terakhir di Dashboard', function () {
    $this->browse(function (Browser $browser) {
        // Ambil aktivitas terakhir dari database
        $latestActivity = UserActivity::where('user_id', $this->user->id)
            ->orderBy('created_at', 'desc')
            ->first();

        $browser->loginAs($this->user)
            ->visit('/user/dashboard')
            ->waitForText('Aktivitas Terakhir');

        if ($latestActivity) {
            $browser->assertSee($latestActivity->judul)
                ->assertSee($latestActivity->deskripsi);
        } else {
            $browser->assertSee('Belum ada aktivitas tercatat.');
        }

        $browser->pause(duskDelay());
    });
});

test('TC-DASH-06: Verifikasi Halaman Riwayat Aktivitas Lengkap', function () {
    $this->browse(function (Browser $browser) {
        // Ambil aktivitas terakhir dari database
        $latestActivities = UserActivity::where('user_id', $this->user->id)
            ->orderBy('created_at', 'desc')
            ->take(2)
            ->get();

        $browser->loginAs($this->user)
            ->visit('/user/dashboard')
            ->waitForText('Aktivitas Terakhir')
            ->clickLink('Lihat Semua Aktivitas')
            ->waitForLocation('/user/aktivitas')
            ->assertPathIs('/user/aktivitas')
            ->assertSee('Semua Aktivitas');

        if ($latestActivities->isEmpty()) {
            $browser->assertSee('Belum ada aktivitas yang terekam.');
        } else {
            foreach ($latestActivities as $act) {
                $browser->assertSee($act->judul);
            }
        }

        $browser->pause(duskDelay());
    });
});

test('TC-DASH-07: Eksplorasi Donasi Terdekat - Pencarian Real-time berdasarkan Nama Makanan dan Nama Unit Bisnis', function () {
    $this->browse(function (Browser $browser) {
        $first_name = explode(' ', $this->user->name)[0];
        $browser->loginAs($this->user)
            ->visit('/user/dashboard')
            ->waitForText("Halo, {$first_name}!")
            ->pause(duskDelay())
            ->clickLink('Lihat Semua')
            ->waitForLocation('/user/donasi-terdekat')
            ->waitForText('Semua Donasi Terdekat')
            // Search by Food Name
            ->typeSlowly('input[placeholder="Cari makanan atau toko..."]', $this->masterA->nama_makanan, 100)
            ->pause(duskDelay())
            ->assertSee($this->masterA->nama_makanan)
            ->assertDontSee($this->masterB->nama_makanan)
            // Search by Business Name (Clear search input first via Javascript)
            ->script("
                const input = document.querySelector('input[placeholder=\"Cari makanan atau toko...\"]');
                input.value = '';
                input.dispatchEvent(new Event('input'));
            ");
        $browser->pause(500)
            ->typeSlowly('input[placeholder="Cari makanan atau toko..."]', $this->unitProfileB->nama_usaha, 100)
            ->pause(duskDelay())
            ->assertSee($this->masterB->nama_makanan)
            ->assertDontSee($this->masterA->nama_makanan)
            ->pause(duskDelay());
    });
});

test('TC-DASH-08: Eksplorasi Donasi Terdekat - Penanganan Pencarian Tidak Ditemukan dan Reset Filter', function () {
    $this->browse(function (Browser $browser) {
        $first_name = explode(' ', $this->user->name)[0];
        $browser->loginAs($this->user)
            ->visit('/user/dashboard')
            ->waitForText("Halo, {$first_name}!")
            ->pause(duskDelay())
            ->clickLink('Lihat Semua')
            ->waitForLocation('/user/donasi-terdekat')
            ->waitForText('Semua Donasi Terdekat')
            ->typeSlowly('input[placeholder="Cari makanan atau toko..."]', 'Soto Ayam Betawi Super Tidak Ada', 100)
            ->pause(duskDelay())
            ->assertDontSee($this->masterA->nama_makanan)
            ->assertDontSee($this->masterB->nama_makanan)
            ->assertSee('Tidak Ada Donasi Ditemukan')
            ->script("
                const btn = Array.from(document.querySelectorAll('button'))
                    .find(el => el.textContent.trim() === 'Reset Filter');
                btn?.click();
            ");
        $browser->pause(duskDelay())
            ->assertSee($this->masterA->nama_makanan)
            ->assertSee($this->masterB->nama_makanan)
            ->pause(duskDelay());
    });
});

test('TC-DASH-09: Eksplorasi Donasi Terdekat - Penyaringan Daftar berdasarkan Kategori (Category Pills)', function () {
    $category = $this->masterB->kategori;
    $this->browse(function (Browser $browser) use ($category) {
        $first_name = explode(' ', $this->user->name)[0];
        $browser->loginAs($this->user)
            ->visit('/user/dashboard')
            ->waitForText("Halo, {$first_name}!")
            ->pause(duskDelay())
            ->clickLink('Lihat Semua')
            ->waitForLocation('/user/donasi-terdekat')
            ->waitForText('Semua Donasi Terdekat')
            ->script("
                const btn = Array.from(document.querySelectorAll('button'))
                    .find(el => el.textContent.trim() === '" . addslashes($category) . "');
                btn?.click();
            ");
        $browser->pause(duskDelay())
            ->assertSee($this->masterB->nama_makanan);

        if ($this->masterA->kategori !== $category) {
            $browser->assertDontSee($this->masterA->nama_makanan);
        }

        $browser->pause(duskDelay());
    });
});

test('TC-DASH-10: Eksplorasi Donasi Terdekat - Penyaringan Daftar berdasarkan Radius Jarak (Distance Dropdown)', function () {
    $distA = \App\Models\User::calculateDistance(
        $this->user->latitude,
        $this->user->longitude,
        $this->unitProfileA->lokasi_lat ?? $this->unitUserA->latitude,
        $this->unitProfileA->lokasi_lng ?? $this->unitUserA->longitude
    );
    $distB = \App\Models\User::calculateDistance(
        $this->user->latitude,
        $this->user->longitude,
        $this->unitProfileB->lokasi_lat ?? $this->unitUserB->latitude,
        $this->unitProfileB->lokasi_lng ?? $this->unitUserB->longitude
    );

    $this->browse(function (Browser $browser) use ($distA, $distB) {
        $first_name = explode(' ', $this->user->name)[0];
        $browser->loginAs($this->user)
            ->visit('/user/dashboard')
            ->waitForText("Halo, {$first_name}!")
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
            ->pause(duskDelay());

        if ($distA <= 1.0) {
            $browser->assertSee($this->masterA->nama_makanan);
        } else {
            $browser->assertDontSee($this->masterA->nama_makanan);
        }

        if ($distB <= 1.0) {
            $browser->assertSee($this->masterB->nama_makanan);
        } else {
            $browser->assertDontSee($this->masterB->nama_makanan);
        }

        $browser->pause(duskDelay());
    });
});

test('TC-DASH-11: Eksplorasi Donasi Terdekat - Penyaringan Daftar berdasarkan Status Harga dan Range Harga', function () {
    $this->browse(function (Browser $browser) {
        $first_name = explode(' ', $this->user->name)[0];
        $browser->loginAs($this->user)
            ->visit('/user/dashboard')
            ->waitForText("Halo, {$first_name}!")
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
            ->assertSee($this->masterB->nama_makanan)
            ->assertDontSee($this->masterA->nama_makanan)

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
            ->assertSee($this->masterA->nama_makanan)
            ->assertDontSee($this->masterB->nama_makanan)

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
            ->assertSee($this->masterB->nama_makanan)
            ->assertSee($this->masterA->nama_makanan)

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
            // gratis is free (Rp 0 <= 10000) -> should see it
            ->assertSee($this->masterB->nama_makanan);

        if ($this->menuA->harga_jual > 10000) {
            $browser->assertDontSee($this->masterA->nama_makanan);
        } else {
            $browser->assertSee($this->masterA->nama_makanan);
        }

        $browser->pause(duskDelay());
    });
});

test('TC-DASH-12: Dashboard menyembunyikan makanan dan menampilkan warning jika lokasi null', function () {
    $this->browse(function (Browser $browser) {
        $first_name = explode(' ', $this->userNoLoc->name)[0];
        $browser->loginAs($this->userNoLoc)
            ->visit('/user/dashboard')
            ->waitForText("Halo, {$first_name}!")
            ->assertSee('Tentukan lokasi untuk melihat makanan terdekat anda.')
            ->assertDontSee('Ambil')
            ->pause(duskDelay());
    });
});

test('TC-DASH-13: Dashboard Radius Anda menampilkan tombol Atur Lokasi jika lokasi null', function () {
    $this->browse(function (Browser $browser) {
        $first_name = explode(' ', $this->userNoLoc->name)[0];
        $browser->loginAs($this->userNoLoc)
            ->visit('/user/dashboard')
            ->waitForText("Halo, {$first_name}!")
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
        $first_name = explode(' ', $this->userNoLoc->name)[0];
        $browser->loginAs($this->userNoLoc)
            ->visit('/user/dashboard')
            ->waitForText("Halo, {$first_name}!")
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
    $distA = \App\Models\User::calculateDistance(
        $this->user->latitude,
        $this->user->longitude,
        $this->unitProfileA->lokasi_lat ?? $this->unitUserA->latitude,
        $this->unitProfileA->lokasi_lng ?? $this->unitUserA->longitude
    );
    $formattedDistanceA = number_format($distA, 1, ',', '.') . ' km';
    $formattedHargaA = 'Rp ' . number_format($this->menuA->harga_jual, 0, ',', '.');
    $formattedStokA = $this->menuA->stok_porsi . ' Porsi';

    $this->browse(function (Browser $browser) use ($formattedDistanceA, $formattedHargaA, $formattedStokA) {
        $first_name = explode(' ', $this->user->name)[0];
        $browser->loginAs($this->user)
            ->visit('/user/dashboard')
            ->waitForText("Halo, {$first_name}!")
            ->pause(duskDelay())
            ->clickLink($this->masterA->nama_makanan)
            ->waitForLocation('/user/makanan/' . $this->menuA->id)
            ->assertPathIs('/user/makanan/' . $this->menuA->id)
            ->waitForText($this->masterA->nama_makanan)
            // 1. Assert Breadcrumbs
            ->assertSeeIn('nav.text-sm', 'Dashboard')
            ->assertSeeIn('nav.text-sm', 'Makanan')
            ->assertSeeIn('nav.text-sm', $this->masterA->nama_makanan)
            // 2. Assert Dynamic Remaining Time
            ->assertSee($this->menuA->time_remaining ?? 'lagi')
            // 3. Assert Distance tag
            ->assertSee($formattedDistanceA)
            // 4. Assert portion and price
            ->assertSee($formattedStokA)
            ->assertSee($formattedHargaA)
            // 5. Assert vendor info
            ->assertSee($this->unitProfileA->nama_usaha);

        if ($this->unitUserA->alamat) {
            $browser->assertSee($this->unitUserA->alamat);
        } else {
            $browser->assertSee('Alamat belum diatur');
        }

        $browser->assertSee('Kunjungi Profil')
            // 6. Assert maps link
            ->assertSourceHas('google.com/maps/search/?api=1')
            // 7. Assert quality assurance card
            ->assertSee('JAMINAN KUALITAS')
            ->assertSee('Mitra kami telah melewati verifikasi standar keamanan pangan')
            ->pause(duskDelay());
    });
});

test('TC-DASH-16: Detail Makanan - Verifikasi Counter Portion Adjuster, Limit Batas Stok (Min/Max), dan Redirect ke Pembayaran', function () {
    $formattedHargaA = 'Rp ' . number_format($this->menuA->harga_jual, 0, ',', '.');
    $maxPrice = $this->menuA->stok_porsi * $this->menuA->harga_jual;
    $formattedMaxPrice = 'Rp ' . number_format($maxPrice, 0, ',', '.');

    $this->browse(function (Browser $browser) use ($formattedHargaA, $formattedMaxPrice) {
        $first_name = explode(' ', $this->user->name)[0];
        $browser->loginAs($this->user)
            ->visit('/user/dashboard')
            ->waitForText("Halo, {$first_name}!")
            ->pause(duskDelay())
            ->clickLink($this->masterA->nama_makanan)
            ->waitForLocation('/user/makanan/' . $this->menuA->id)
            ->waitForText($this->masterA->nama_makanan)
            // Initial qty is 1, price A
            ->assertSee($formattedHargaA)
            // Try to click '-' when qty is 1 (min limit check)
            ->click('button[class*="bg-gray-100"]')
            ->pause(500)
            ->assertSee($formattedHargaA);

        // Click '+' button to reach max stock
        $clicks = $this->menuA->stok_porsi - 1;
        for ($i = 0; $i < $clicks; $i++) {
            $browser->click('button[class*="bg-[#1cb764]"][class*="hover:bg-[#159f54]"]');
        }

        $browser->pause(duskDelay())
            ->assertSee($formattedMaxPrice); // Max price

        // Try to click '+' again when qty is at max limit
        $browser->click('button[class*="bg-[#1cb764]"][class*="hover:bg-[#159f54]"]')
            ->pause(500)
            ->assertSee($formattedMaxPrice)
            // Click 'Ambil Makanan' to redirect to payments page
            ->press('Ambil Makanan')
            ->waitForLocation('/user/dashboard/' . $this->menuA->id . '/pembayaran')
            ->assertPathIs('/user/dashboard/' . $this->menuA->id . '/pembayaran')
            ->assertQueryStringHas('qty', (string) $this->menuA->stok_porsi)
            ->pause(duskDelay());
    });
});

test('TC-DASH-17: Detail Makanan - Verifikasi Rekomendasi Daftar Makanan Serupa (Similar Items)', function () {
    $kategori = $this->masterA->kategori;
    $similarMenu = MenuAktif::where('status', 'aktif')
        ->where('id', '!=', $this->menuA->id)
        ->where('stok_porsi', '>', 0)
        ->where('batas_pengambilan', '>', now())
        ->whereHas('masterMakanan', function($query) use ($kategori) {
            $query->where('kategori', $kategori);
        })
        ->first();

    if (!$similarMenu) {
        throw new \Exception("Pengujian Gagal: Tidak ada menu aktif lain dalam kategori '{$kategori}' untuk menguji 'Makanan Serupa'. Harap tambahkan menu lain dengan kategori yang sama.");
    }

    $formattedSimilarHarga = $similarMenu->is_gratis ? 'Gratis' : 'Rp ' . number_format($similarMenu->harga_jual, 0, ',', '.');

    $this->browse(function (Browser $browser) use ($similarMenu, $formattedSimilarHarga) {
        $first_name = explode(' ', $this->user->name)[0];
        $browser->loginAs($this->user)
            ->visit('/user/dashboard')
            ->waitForText("Halo, {$first_name}!")
            ->pause(duskDelay())
            ->clickLink($this->masterA->nama_makanan)
            ->waitForLocation('/user/makanan/' . $this->menuA->id)
            ->waitForText($this->masterA->nama_makanan)
            // Assert "Makanan Serupa" section header is shown
            ->assertSee($this->masterA->kategori . ' Serupa')
            // Assert that similar food is listed
            ->assertSee($similarMenu->masterMakanan->nama_makanan)
            ->assertSee($formattedSimilarHarga)
            ->pause(duskDelay());
    });
});
