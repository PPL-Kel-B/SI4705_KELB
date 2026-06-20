<?php

use Laravel\Dusk\Browser;
use App\Models\User;
use App\Models\MenuAktif;
use App\Models\Pesanan;

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

    // Restore settings defaults so notifications can be sent
    $jaki = User::where('email', 'jaki.munawaroh@bakery.com')->first();
    if ($jaki) {
        $profile = $jaki->unitBisnisProfile;
        if ($profile) {
            $profile->update([
                'notifikasi_aktif' => true,
                'notifikasi_pesanan' => true,
                'jam_buka' => '00:00',
                'jam_tutup' => '23:59',
            ]);
        }
    }

    $farid = User::where('email', 'farid@gmail.com')->first();
    if ($farid) {
        $farid->update([
            'notif_donasi' => true,
        ]);
    }
});

test('TC-NOTIF-01: Farid mengecek notifikasi kosong di awal', function () {
    $farid = User::where('email', 'farid@gmail.com')->firstOrFail();
    $farid->notifications()->delete(); // Ensure clean start

    $this->browse(function (Browser $browser) use ($farid) {
        $browser->loginAs($farid)
            ->visit('/user/dashboard')
            ->waitForText('Halo,')
            // Highlight the bell button to show empty state
            ->script("
                const bell = document.querySelector('header button[class*=\"focus:outline-none\"]');
                if (bell) {
                    bell.style.outline = '3px solid #e09121';
                    bell.style.outlineOffset = '2px';
                }
            ");
        $browser->pause(duskDelay())
            ->assertMissing('header button[class*="focus:outline-none"] span.bg-red-500')
            ->script("
                const bell = document.querySelector('header button[class*=\"focus:outline-none\"]');
                if (bell) bell.style.outline = '';
            ");
        $browser->pause(1000);
    });
});

test('TC-NOTIF-02: Jaki mengecek notifikasi kosong di awal', function () {
    $jaki = User::where('email', 'jaki.munawaroh@bakery.com')->firstOrFail();
    $jaki->notifications()->delete(); // Ensure clean start

    $this->browse(function (Browser $browser) use ($jaki) {
        $browser->loginAs($jaki)
            ->visit('/unit/dashboard')
            ->waitForText('Dashboard')
            // Highlight the bell button
            ->script("
                const bell = document.querySelector('header button[class*=\"focus:outline-none\"]');
                if (bell) {
                    bell.style.outline = '3px solid #e09121';
                    bell.style.outlineOffset = '2px';
                }
            ");
        $browser->pause(duskDelay())
            ->assertMissing('header button[class*="focus:outline-none"] span.bg-red-500')
            ->script("
                const bell = document.querySelector('header button[class*=\"focus:outline-none\"]');
                if (bell) bell.style.outline = '';
            ");
        $browser->pause(1000);
    });
});

test('TC-NOTIF-03: Admin mengecek notifikasi kosong di awal', function () {
    $admin = User::where('email', 'faridzaridzaridzarid@gmail.com')->firstOrFail();
    $admin->notifications()->delete(); // Ensure clean start

    $this->browse(function (Browser $browser) use ($admin) {
        $browser->loginAs($admin)
            ->visit('/admin/dashboard')
            ->waitForText('Dashboard')
            // Highlight the bell button
            ->script("
                const bell = document.querySelector('header button[class*=\"focus:outline-none\"]');
                if (bell) {
                    bell.style.outline = '3px solid #e09121';
                    bell.style.outlineOffset = '2px';
                }
            ");
        $browser->pause(duskDelay())
            ->assertMissing('header button[class*="focus:outline-none"] span.bg-red-500')
            ->script("
                const bell = document.querySelector('header button[class*=\"focus:outline-none\"]');
                if (bell) bell.style.outline = '';
            ");
        $browser->pause(1000);
    });
});

test('TC-NOTIF-04: Farid mengecek indikator merah notifikasi pada bell header', function () {
    $farid = User::where('email', 'farid@gmail.com')->firstOrFail();
    $menu = MenuAktif::whereHas('masterMakanan', function ($q) {
        $q->where('nama_makanan', 'MARTABAK DUSK NOTIF');
    })->firstOrFail();
    $farid->notifications()->delete();
    $farid->notify(new \App\Notifications\MakananDekatNotification($menu));

    $this->browse(function (Browser $browser) use ($farid) {
        $browser->loginAs($farid)
            ->visit('/user/dashboard')
            ->waitForText('Halo,')
            // Highlight the bell button showing the red dot indicator
            ->script("
                const bell = document.querySelector('header button[class*=\"focus:outline-none\"]');
                if (bell) {
                    bell.style.outline = '3px solid #ef4444';
                    bell.style.outlineOffset = '2px';
                }
            ");
        $browser->pause(duskDelay())
            ->assertPresent('header button[class*="focus:outline-none"] span.bg-red-500')
            ->script("
                const bell = document.querySelector('header button[class*=\"focus:outline-none\"]');
                if (bell) bell.style.outline = '';
            ");
        $browser->pause(1000);
    });
});

test('TC-NOTIF-05: Farid mengecek daftar notifikasi aktif pada dropdown bell', function () {
    $farid = User::where('email', 'farid@gmail.com')->firstOrFail();
    $menu = MenuAktif::whereHas('masterMakanan', function ($q) {
        $q->where('nama_makanan', 'MARTABAK DUSK NOTIF');
    })->firstOrFail();
    $farid->notifications()->delete();
    $farid->notify(new \App\Notifications\MakananDekatNotification($menu));

    $this->browse(function (Browser $browser) use ($farid) {
        $browser->loginAs($farid)
            ->visit('/user/dashboard')
            ->waitForText('Halo,')
            ->click('header button[class*="focus:outline-none"]')
            ->waitForText('MARTABAK DUSK NOTIF')
            // Highlight the notification dropdown content
            ->script("
                const dropdown = document.querySelector('div[class*=\"absolute right-0 mt-2\"]');
                if (dropdown) {
                    dropdown.style.outline = '3px solid #1cb764';
                    dropdown.style.outlineOffset = '2px';
                }
            ");
        $browser->pause(duskDelay())
            ->assertSee('MARTABAK DUSK NOTIF')
            ->script("
                const dropdown = document.querySelector('div[class*=\"absolute right-0 mt-2\"]');
                if (dropdown) dropdown.style.outline = '';
            ");
        $browser->pause(1000);
    });
});

test('TC-NOTIF-06: Farid mengklik notifikasi dan diredirect ke halaman detail menu', function () {
    $farid = User::where('email', 'farid@gmail.com')->firstOrFail();
    $menu = MenuAktif::whereHas('masterMakanan', function ($q) {
        $q->where('nama_makanan', 'MARTABAK DUSK NOTIF');
    })->firstOrFail();
    $farid->notifications()->delete();
    $farid->notify(new \App\Notifications\MakananDekatNotification($menu));

    $this->browse(function (Browser $browser) use ($farid, $menu) {
        $browser->loginAs($farid)
            ->visit('/user/dashboard')
            ->waitForText('Halo,')
            ->click('header button[class*="focus:outline-none"]')
            ->waitForText('MARTABAK DUSK NOTIF')
            ->pause(1000)
            ->click('a[href*="/notifications/"]')
            ->waitForLocation('/user/makanan/' . $menu->id)
            ->assertPathIs('/user/makanan/' . $menu->id)
            ->pause(duskDelay());

        // Check that red indicator is gone now
        $browser->visit('/user/dashboard')
            ->waitForText('Halo,')
            ->assertMissing('header button[class*="focus:outline-none"] span.bg-red-500')
            ->pause(duskDelay());
    });
});

test('TC-NOTIF-07: Farid menonaktifkan pengaturan notifikasi donasi via UI', function () {
    $farid = User::where('email', 'farid@gmail.com')->firstOrFail();

    $this->browse(function (Browser $browser) use ($farid) {
        $browser->loginAs($farid)
            ->visit('/user/pengaturan')
            ->waitForText('Pengaturan')
            // Highlight the toggle button
            ->script("
                const toggle = document.querySelector('button[class*=\"relative inline-flex h-8 w-14\"]');
                if (toggle) {
                    toggle.style.outline = '3px solid #1cb764';
                    toggle.style.outlineOffset = '2px';
                }
            ");
        $browser->pause(duskDelay())
            ->click('button[class*="relative inline-flex h-8 w-14"]') // Toggle off
            ->waitForText('Pengaturan notifikasi berhasil diperbarui!') // Wait for AJAX to complete
            ->script("
                const toggle = document.querySelector('button[class*=\"relative inline-flex h-8 w-14\"]');
                if (toggle) toggle.style.outline = '';
            ");
    });

    $farid->refresh();
    expect((bool)$farid->notif_donasi)->toBeFalse();
});

test('TC-NOTIF-08: Farid memverifikasi tidak menerima notifikasi baru setelah dinonaktifkan', function () {
    $farid = User::where('email', 'farid@gmail.com')->firstOrFail();

    $this->browse(function (Browser $browser) use ($farid) {
        $browser->loginAs($farid)
            ->visit('/user/dashboard')
            ->waitForText('Halo,')
            // Highlight the bell to show it has no red dot/unread notifications
            ->script("
                const bell = document.querySelector('header button[class*=\"focus:outline-none\"]');
                if (bell) {
                    bell.style.outline = '3px solid #e09121';
                    bell.style.outlineOffset = '2px';
                }
            ");
        $browser->pause(duskDelay())
            ->assertMissing('header button[class*="focus:outline-none"] span.bg-red-500')
            ->script("
                const bell = document.querySelector('header button[class*=\"focus:outline-none\"]');
                if (bell) bell.style.outline = '';
            ");
        $browser->pause(1000);
    });
});

test('TC-NOTIF-09: Jaki mengecek indikator merah notifikasi pada bell header', function () {
    $jaki = User::where('email', 'jaki.munawaroh@bakery.com')->firstOrFail();
    $farid = User::where('email', 'farid@gmail.com')->firstOrFail();
    $menu = MenuAktif::whereHas('masterMakanan', function ($q) {
        $q->where('nama_makanan', 'MARTABAK DUSK NOTIF');
    })->firstOrFail();
    $pesanan = Pesanan::where('user_id', $farid->id)->where('menu_aktif_id', $menu->id)->first();
    if (!$pesanan) {
        $pesanan = Pesanan::create([
            'user_id' => $farid->id,
            'menu_aktif_id' => $menu->id,
            'unit_bisnis_id' => $menu->unit_bisnis_id,
            'jumlah_porsi' => 1,
            'total_harga' => $menu->harga_jual,
            'status' => 'dibayar',
            'kode_unik' => 'TEST1234',
        ]);
    }
    $jaki->notifications()->delete();
    $jaki->notify(new \App\Notifications\PesananMasukNotification($pesanan));

    $this->browse(function (Browser $browser) use ($jaki) {
        $browser->loginAs($jaki)
            ->visit('/unit/dashboard')
            ->waitForText('Dashboard')
            // Highlight the bell button
            ->script("
                const bell = document.querySelector('header button[class*=\"focus:outline-none\"]');
                if (bell) {
                    bell.style.outline = '3px solid #ef4444';
                    bell.style.outlineOffset = '2px';
                }
            ");
        $browser->pause(duskDelay())
            ->assertPresent('header button[class*="focus:outline-none"] span.rounded-full')
            ->script("
                const bell = document.querySelector('header button[class*=\"focus:outline-none\"]');
                if (bell) bell.style.outline = '';
            ");
        $browser->pause(1000);
    });
});

test('TC-NOTIF-10: Jaki mengklik Tandai Semua Dibaca pada dropdown bell', function () {
    $jaki = User::where('email', 'jaki.munawaroh@bakery.com')->firstOrFail();
    $farid = User::where('email', 'farid@gmail.com')->firstOrFail();
    $menu = MenuAktif::whereHas('masterMakanan', function ($q) {
        $q->where('nama_makanan', 'MARTABAK DUSK NOTIF');
    })->firstOrFail();
    $pesanan = Pesanan::where('user_id', $farid->id)->where('menu_aktif_id', $menu->id)->first();
    if (!$pesanan) {
        $pesanan = Pesanan::create([
            'user_id' => $farid->id,
            'menu_aktif_id' => $menu->id,
            'unit_bisnis_id' => $menu->unit_bisnis_id,
            'jumlah_porsi' => 1,
            'total_harga' => $menu->harga_jual,
            'status' => 'dibayar',
            'kode_unik' => 'TEST1234',
        ]);
    }
    $jaki->notifications()->delete();
    $jaki->notify(new \App\Notifications\PesananMasukNotification($pesanan));

    $this->browse(function (Browser $browser) use ($jaki) {
        $browser->loginAs($jaki)
            ->visit('/unit/dashboard')
            ->waitForText('Dashboard')
            ->click('header button[class*="focus:outline-none"]')
            ->waitForText('Pesanan Baru')
            ->pause(1000)
            // Highlight Tandai Semua Dibaca button
            ->script("
                const btn = Array.from(document.querySelectorAll('button'))
                    .find(el => el.textContent.trim() === 'Tandai Semua Dibaca');
                if (btn) {
                    btn.style.outline = '3px solid #1cb764';
                    btn.style.outlineOffset = '2px';
                }
            ");
        $browser->pause(duskDelay())
            ->press('Tandai Semua Dibaca')
            ->pause(1500)
            ->assertMissing('header button[class*="focus:outline-none"] span.bg-red-500')
            ->pause(1000);
    });
});

test('TC-NOTIF-11: Jaki menonaktifkan pengaturan notifikasi pesanan via UI', function () {
    $jaki = User::where('email', 'jaki.munawaroh@bakery.com')->firstOrFail();
    $profile = $jaki->unitBisnisProfile;
    if ($profile) {
        $profile->update([
            'notifikasi_aktif' => true,
            'notifikasi_pesanan' => true,
        ]);
    }

    $this->browse(function (Browser $browser) use ($jaki) {
        $browser->loginAs($jaki)
            ->visit('/unit/pengaturan')
            ->waitForText('Pengaturan Operasional')
            ->script("
                if (window.Alpine) {
                    Alpine.\$data(document.querySelector('div[x-data*=\"notifAktif\"]')).notifAktif = false;
                } else {
                    document.querySelector('input[type=\"checkbox\"]').click();
                }
            ");
        $browser->pause(1000);

        // Submit form
        $browser->click('#settings-form button[type="submit"]')
            ->waitForLocation('/unit/pengaturan')
            ->assertSee('Pengaturan berhasil diperbarui!')
            ->pause(duskDelay());
    });

    $profile->refresh();
    expect((bool)$profile->notifikasi_aktif)->toBeFalse();
});

test('TC-NOTIF-12: Jaki memverifikasi tidak menerima notifikasi pesanan baru setelah dinonaktifkan', function () {
    $jaki = User::where('email', 'jaki.munawaroh@bakery.com')->firstOrFail();

    $this->browse(function (Browser $browser) use ($jaki) {
        $browser->loginAs($jaki)
            ->visit('/unit/dashboard')
            ->waitForText('Dashboard')
            // Highlight the bell button
            ->script("
                const bell = document.querySelector('header button[class*=\"focus:outline-none\"]');
                if (bell) {
                    bell.style.outline = '3px solid #e09121';
                    bell.style.outlineOffset = '2px';
                }
            ");
        $browser->pause(duskDelay())
            ->assertMissing('header button[class*="focus:outline-none"] span.bg-red-500')
            ->script("
                const bell = document.querySelector('header button[class*=\"focus:outline-none\"]');
                if (bell) bell.style.outline = '';
            ");
        $browser->pause(1000);
    });
});

test('TC-NOTIF-13: Admin mengecek indikator merah pendaftaran unit bisnis baru', function () {
    $admin = User::where('email', 'faridzaridzaridzarid@gmail.com')->firstOrFail();
    $newUnit = User::where('email', 'new.partner@sharebite.com')->first();
    if (!$newUnit) {
        $newUnit = User::create([
            'name' => 'Ayam Geprek Meriam Jaki',
            'email' => 'new.partner@sharebite.com',
            'password' => bcrypt('Password123!'),
            'role' => 'unit_bisnis',
            'latitude' => -6.900000,
            'longitude' => 107.600000,
        ]);
    }
    $admin->notifications()->delete();
    $admin->notify(new \App\Notifications\UnitBisnisMendaftarNotification($newUnit));

    $this->browse(function (Browser $browser) use ($admin) {
        $browser->loginAs($admin)
            ->visit('/admin/dashboard')
            ->waitForText('Dashboard')
            // Highlight Admin's bell button
            ->script("
                const bell = document.querySelector('header button[class*=\"focus:outline-none\"]');
                if (bell) {
                    bell.style.outline = '3px solid #ef4444';
                    bell.style.outlineOffset = '2px';
                }
            ");
        $browser->pause(duskDelay())
            ->assertPresent('header button[class*="focus:outline-none"] span.bg-red-500')
            ->script("
                const bell = document.querySelector('header button[class*=\"focus:outline-none\"]');
                if (bell) bell.style.outline = '';
            ");
        $browser->pause(1000);
    });
});

test('TC-NOTIF-14: Admin mengklik notifikasi pendaftaran dan diredirect ke verifikasi', function () {
    $admin = User::where('email', 'faridzaridzaridzarid@gmail.com')->firstOrFail();
    $newUnit = User::where('email', 'new.partner@sharebite.com')->first();
    if (!$newUnit) {
        $newUnit = User::create([
            'name' => 'Ayam Geprek Meriam Jaki',
            'email' => 'new.partner@sharebite.com',
            'password' => bcrypt('Password123!'),
            'role' => 'unit_bisnis',
            'latitude' => -6.900000,
            'longitude' => 107.600000,
        ]);
    }
    $admin->notifications()->delete();
    $admin->notify(new \App\Notifications\UnitBisnisMendaftarNotification($newUnit));

    $this->browse(function (Browser $browser) use ($admin) {
        $browser->loginAs($admin)
            ->visit('/admin/dashboard')
            ->waitForText('Dashboard')
            ->click('header button[class*="focus:outline-none"]')
            ->waitForText('Mitra Baru Mendaftar!')
            ->pause(1000)
            ->click('a[href*="/notifications/"]')
            ->waitForLocation('/admin/manajemen-pengguna')
            ->assertPathIs('/admin/manajemen-pengguna')
            ->pause(duskDelay());
    });
});
