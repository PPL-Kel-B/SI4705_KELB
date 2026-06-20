<?php

use Laravel\Dusk\Browser;
use App\Models\User;
use App\Models\MasterMakanan;
use App\Models\MenuAktif;
use App\Models\Pesanan;
use App\Models\Pembayaran;

if (! function_exists('duskDelay')) {
    function duskDelay(): int {
        return (int) env('DUSK_PAUSE_MS', 2000); // 2000ms delay for clear lecturer visibility
    }
}

beforeEach(function () {
    // Force database connection to sharebite_dusk
    \Illuminate\Support\Facades\Config::set('database.connections.mysql.database', 'sharebite_dusk');
    \Illuminate\Support\Facades\Config::set('database.connections.mysql.username', 'root');
    \Illuminate\Support\Facades\Config::set('database.connections.mysql.password', '');
    \Illuminate\Support\Facades\Config::set('database.default', 'mysql');
    \Illuminate\Support\Facades\DB::purge('mysql');
    \Illuminate\Support\Facades\DB::reconnect('mysql');

    // Always ensure Jaki and Farid coordinates are set so distance filtering works
    $jaki = User::where('email', 'jaki.munawaroh@bakery.com')->first();
    if ($jaki) {
        $jaki->update([
            'latitude' => -6.900000,
            'longitude' => 107.600000,
        ]);
        $profile = $jaki->unitBisnisProfile;
        if ($profile) {
            $profile->update([
                'lokasi_lat' => -6.900000,
                'lokasi_lng' => 107.600000,
                'radius_penjemputan' => 15,
                'status_verifikasi' => 'terverifikasi',
            ]);
        }
    }

    $farid = User::where('email', 'farid@gmail.com')->first();
    if ($farid) {
        $farid->update([
            'latitude' => -6.901000,
            'longitude' => 107.601000,
        ]);
    }
});

test('TC-PN-01: Jaki menambahkan menu aktif pertama MARTABAK DUSK NOTIF', function () {
    $jaki = User::where('email', 'jaki.munawaroh@bakery.com')->firstOrFail();
    $profile = $jaki->unitBisnisProfile;

    // Reset settings to enabled at the beginning
    if ($profile) {
        $profile->update([
            'notifikasi_aktif' => true,
            'notifikasi_pesanan' => true,
        ]);
        // Clean up Jaki's existing active menus to prevent dashboard limit overflow
        MenuAktif::where('unit_bisnis_id', $profile->id)->delete();
    }

    $farid = User::where('email', 'farid@gmail.com')->first();
    if ($farid) {
        $farid->update([
            'notif_donasi' => true,
        ]);
    }

    // Clear previous database notifications & test menus/orders
    $jaki->notifications()->delete();
    if ($farid) $farid->notifications()->delete();
    User::where('role', 'admin')->first()?->notifications()->delete();

    MasterMakanan::where('nama_makanan', 'MARTABAK DUSK NOTIF')
        ->where('unit_bisnis_id', $profile->id)
        ->forceDelete();

    $this->browse(function (Browser $browser) use ($jaki) {
        $browser->loginAs($jaki)
            ->visit('/unit/kelola-master-data/create')
            ->type('Nama_Makanan', 'MARTABAK DUSK NOTIF')
            ->select('Kategori', 'Makanan Ringan')
            ->type('deskripsi', 'Martabak Dusk Notif Terlezat')
            ->value('#Harga', '20000')
            ->value('#Berat', '1')
            ->attach('Foto', base_path('tests/Browser/stubs/MARTABAK.jpg'))
            ->pause(1500);

        $browser->script("
            document.getElementById('btn-simpan').disabled = false;
        ");

        $browser->click('#btn-simpan')
            ->pause(3000);

        $master = MasterMakanan::where('nama_makanan', 'MARTABAK DUSK NOTIF')->firstOrFail();

        $browser->visit('/unit/kelola-makanan/tambah')
            ->waitForText('Tambah Menu Aktif', 10)
            ->pause(1000);

        $browser->clear('input[x-model="searchQuery"]')->pause(500);
        $chars = str_split('MARTABAK DUSK NOTIF');
        foreach ($chars as $char) {
            $browser->append('input[x-model="searchQuery"]', $char)->pause(150);
        }

        $browser->pause(1500)
            ->click('h4.line-clamp-1')
            ->pause(1500);

        $browser->script("
            let checkbox = document.querySelector('input[name=\"is_gratis\"]');
            checkbox.checked = false;
            checkbox.dispatchEvent(new Event('change'));
        ");
        $browser->pause(1000);

        $browser->script("
            let stok = document.querySelector('input[name=\"stok_porsi\"]');
            stok.value = '10';
            stok.dispatchEvent(new Event('input'));
        ");
        $browser->pause(1000);

        $browser->script("
            let batas = document.querySelector('input[name=\"batas_pengambilan\"]');
            batas.value = '23:59';
            batas.dispatchEvent(new Event('input'));
        ");
        $browser->pause(1000);

        $browser->click('#btn-submit')
            ->pause(3000);
    });

    $master = MasterMakanan::where('nama_makanan', 'MARTABAK DUSK NOTIF')->firstOrFail();
    expect(MenuAktif::where('master_makanan_id', $master->id)->where('status', 'aktif')->exists())->toBeTrue();
});

test('TC-PN-02: Farid memesan menu aktif pertama MARTABAK DUSK NOTIF', function () {
    $farid = User::where('email', 'farid@gmail.com')->firstOrFail();
    $menu = MenuAktif::whereHas('masterMakanan', function ($q) {
        $q->where('nama_makanan', 'MARTABAK DUSK NOTIF');
    })->firstOrFail();

    // Clean previous orders for this menu
    $pesananIds = Pesanan::where('user_id', $farid->id)
        ->where('menu_aktif_id', $menu->id)
        ->pluck('id');
    Pembayaran::whereIn('pesanan_id', $pesananIds)->delete();
    Pesanan::where('user_id', $farid->id)
        ->where('menu_aktif_id', $menu->id)
        ->delete();

    $this->browse(function (Browser $browser) use ($farid, $menu) {
        $browser->loginAs($farid)
            ->visit('/user/dashboard')
            ->waitForText('Halo,')
            ->clickLink('MARTABAK DUSK NOTIF')
            ->waitForLocation("/user/makanan/{$menu->id}", 10)
            ->pause(duskDelay())
            ->press('Ambil Makanan')
            ->waitForLocation("/user/dashboard/{$menu->id}/pembayaran", 10)
            ->assertPathIs("/user/dashboard/{$menu->id}/pembayaran")
            ->pause(duskDelay());

        // Open new tab to scan
        $browser->script("window.open('', '_blank');");
        $browser->pause(1000);

        $handles = $browser->driver->getWindowHandles();
        if (count($handles) > 1) {
            $browser->driver->switchTo()->window(end($handles));
            $browser->visit("/public/scan-qris/{$menu->id}");
            $browser->waitForText('Pembayaran Berhasil', 15)
                    ->pause(duskDelay());
            $browser->driver->close();
            $browser->driver->switchTo()->window(reset($handles));
        }

        $browser->waitForLocation("/user/dashboard/{$menu->id}/pembayaran/berhasil", 25)
            ->assertPathIs("/user/dashboard/{$menu->id}/pembayaran/berhasil")
            ->pause(duskDelay());
    });

    expect(Pesanan::where('user_id', $farid->id)->where('menu_aktif_id', $menu->id)->where('status', 'dibayar')->exists())->toBeTrue();
});

test('TC-PN-03: Pendaftaran Unit Bisnis baru secara lengkap dimulai dari Landing Page', function () {
    User::where('email', 'new.partner@sharebite.com')->delete();

    $this->browse(function (Browser $browser) {
        // 1. Start from landing page, click link
        $browser->logout()
            ->visit('/')
            ->waitForText('SELAMATKAN')
            ->pause(duskDelay())
            ->click('a[href="/register/unit-bisnis"]')
            ->waitForLocation('/register/unit-bisnis')
            ->assertSee('Informasi Bisnis')
            ->type('Nama_Usaha', 'Ayam Geprek Meriam Jaki')
            ->select('Jenis_Usaha', 'Restoran')
            ->type('Alamat', 'Jalan Rancawangi II, Tegalluar, Bojongsoang, Kabupaten Bandung, Jawa Barat, 40295, Indonesia')
            ->attach('NIB_File', base_path('tests/Browser/stubs/Format Yang Sesuai.jpeg'))
            ->type('Nomor_hp', '081298765499')
            ->type('Email', 'new.partner@sharebite.com')
            ->type('Password', 'Password123!');

        // Search location via Leaflet autocomplete
        $browser->type('#location-search', 'Jalan Rancawangi II, Tegalluar, Bojongsoang, Kabupaten Bandung, Jawa Barat, 40295, Indonesia')
            ->pause(500)
            ->click('#btn-search-loc')
            ->waitFor('#search-suggestions li', 10)
            ->click('#search-suggestions li')
            ->pause(1000);

        // Click terms checkbox
        $browser->click('#terms')
            ->pause(duskDelay());

        // Click submit button directly
        $browser->click('#submitBtn')
            ->waitForLocation('/login')
            ->assertPathIs('/login')
            ->pause(duskDelay());
    });

    expect(User::where('email', 'new.partner@sharebite.com')->where('role', 'unit_bisnis')->exists())->toBeTrue();
});

test('TC-PN-04: Jaki menambahkan menu aktif kedua DONAT KEJU NOTIF', function () {
    // Disable settings in database first so menu 2 does not generate new notifications
    $jaki = User::where('email', 'jaki.munawaroh@bakery.com')->firstOrFail();
    $profile = $jaki->unitBisnisProfile;
    if ($profile) {
        $profile->update([
            'notifikasi_aktif' => false,
            'notifikasi_pesanan' => false,
        ]);
    }

    $farid = User::where('email', 'farid@gmail.com')->first();
    if ($farid) {
        $farid->update([
            'notif_donasi' => false,
        ]);
    }

    MasterMakanan::where('nama_makanan', 'DONAT KEJU NOTIF')
        ->where('unit_bisnis_id', $profile->id)
        ->forceDelete();

    $this->browse(function (Browser $browser) use ($jaki) {
        $browser->loginAs($jaki)
            ->visit('/unit/kelola-master-data/create')
            ->type('Nama_Makanan', 'DONAT KEJU NOTIF')
            ->select('Kategori', 'Makanan Ringan')
            ->type('deskripsi', 'Donat Keju Notif Terlezat')
            ->value('#Harga', '25000')
            ->value('#Berat', '1')
            ->attach('Foto', base_path('tests/Browser/stubs/Format Yang Sesuai.jpeg'))
            ->pause(1500);

        $browser->script("
            document.getElementById('btn-simpan').disabled = false;
        ");

        $browser->click('#btn-simpan')
            ->pause(3000);

        $master = MasterMakanan::where('nama_makanan', 'DONAT KEJU NOTIF')->firstOrFail();

        $browser->visit('/unit/kelola-makanan/tambah')
            ->waitForText('Tambah Menu Aktif', 10)
            ->pause(1000);

        $browser->clear('input[x-model="searchQuery"]')->pause(500);
        $chars = str_split('DONAT KEJU NOTIF');
        foreach ($chars as $char) {
            $browser->append('input[x-model="searchQuery"]', $char)->pause(150);
        }

        $browser->pause(1500)
            ->click('h4.line-clamp-1')
            ->pause(1500);

        $browser->script("
            let checkbox = document.querySelector('input[name=\"is_gratis\"]');
            checkbox.checked = false;
            checkbox.dispatchEvent(new Event('change'));
        ");
        $browser->pause(1000);

        $browser->script("
            let stok = document.querySelector('input[name=\"stok_porsi\"]');
            stok.value = '10';
            stok.dispatchEvent(new Event('input'));
        ");
        $browser->pause(1000);

        $browser->script("
            let batas = document.querySelector('input[name=\"batas_pengambilan\"]');
            batas.value = '23:59';
            batas.dispatchEvent(new Event('input'));
        ");
        $browser->pause(1000);

        $browser->click('#btn-submit')
            ->pause(3000);
    });

    $master = MasterMakanan::where('nama_makanan', 'DONAT KEJU NOTIF')->firstOrFail();
    expect(MenuAktif::where('master_makanan_id', $master->id)->where('status', 'aktif')->exists())->toBeTrue();
});

test('TC-PN-05: Farid memesan menu aktif kedua DONAT KEJU NOTIF', function () {
    $farid = User::where('email', 'farid@gmail.com')->firstOrFail();
    $menu = MenuAktif::whereHas('masterMakanan', function ($q) {
        $q->where('nama_makanan', 'DONAT KEJU NOTIF');
    })->firstOrFail();

    // Clean previous orders for this menu
    $pesananIds = Pesanan::where('user_id', $farid->id)
        ->where('menu_aktif_id', $menu->id)
        ->pluck('id');
    Pembayaran::whereIn('pesanan_id', $pesananIds)->delete();
    Pesanan::where('user_id', $farid->id)
        ->where('menu_aktif_id', $menu->id)
        ->delete();

    $this->browse(function (Browser $browser) use ($farid, $menu) {
        $browser->loginAs($farid)
            ->visit('/user/dashboard')
            ->waitForText('Halo,')
            ->clickLink('DONAT KEJU NOTIF')
            ->waitForLocation("/user/makanan/{$menu->id}", 10)
            ->pause(duskDelay())
            ->press('Ambil Makanan')
            ->waitForLocation("/user/dashboard/{$menu->id}/pembayaran", 10)
            ->assertPathIs("/user/dashboard/{$menu->id}/pembayaran")
            ->pause(duskDelay());

        // Open new tab to scan
        $browser->script("window.open('', '_blank');");
        $browser->pause(1000);

        $handles = $browser->driver->getWindowHandles();
        if (count($handles) > 1) {
            $browser->driver->switchTo()->window(end($handles));
            $browser->visit("/public/scan-qris/{$menu->id}");
            $browser->waitForText('Pembayaran Berhasil', 15)
                    ->pause(duskDelay());
            $browser->driver->close();
            $browser->driver->switchTo()->window(reset($handles));
        }

        $browser->waitForLocation("/user/dashboard/{$menu->id}/pembayaran/berhasil", 25)
            ->assertPathIs("/user/dashboard/{$menu->id}/pembayaran/berhasil")
            ->pause(duskDelay());
    });

    expect(Pesanan::where('user_id', $farid->id)->where('menu_aktif_id', $menu->id)->where('status', 'dibayar')->exists())->toBeTrue();
});
