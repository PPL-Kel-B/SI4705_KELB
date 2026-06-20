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
});

test('TC-NOTIF-01: Farid mengecek notifikasi kosong di awal', function () {
    $farid = User::where('email', 'farid@gmail.com')->firstOrFail();
    $farid->refresh();
    $unreadCount = $farid->unreadNotifications()->count();
    
    // Explicitly isolate notification preference for this test
    $farid->notif_donasi = true;
    $farid->save();

    $this->browse(function (Browser $browser) use ($farid, $unreadCount) {
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
        $browser->pause(duskDelay());

        if ($unreadCount > 0) {
            $browser->assertPresent('header button[class*="focus:outline-none"] span.bg-red-500');
        } else {
            $browser->assertMissing('header button[class*="focus:outline-none"] span.bg-red-500');
        }

        $browser->click('header button[class*="focus:outline-none"]')
            ->pause(1000)
            ->assertVisible('div[class*="absolute right-0 mt-2"]') // Verify dropdown opened
            ->script("
                const bell = document.querySelector('header button[class*=\"focus:outline-none\"]');
                if (bell) bell.style.outline = '';
            ");
        $browser->pause(1000);
    });
});

test('TC-NOTIF-02: Jaki mengecek notifikasi kosong di awal', function () {
    $jaki = User::where('email', 'jaki.munawaroh@bakery.com')->firstOrFail();
    $jaki->refresh();
    $unreadCount = $jaki->unreadNotifications()->count();

    // Explicitly isolate notification preference for Jaki
    $profile = $jaki->unitBisnisProfile;
    if ($profile) {
        $profile->update([
            'notifikasi_aktif' => true,
            'notifikasi_pesanan' => true,
        ]);
    }

    $this->browse(function (Browser $browser) use ($jaki, $unreadCount) {
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
        $browser->pause(duskDelay());

        if ($unreadCount > 0) {
            $browser->assertPresent('header button[class*="focus:outline-none"] span.rounded-full');
        } else {
            $browser->assertMissing('header button[class*="focus:outline-none"] span.rounded-full');
        }

        $browser->click('header button[class*="focus:outline-none"]')
            ->pause(1000)
            ->assertVisible('div[class*="absolute right-0 mt-2"]') // Verify dropdown opened
            ->script("
                const bell = document.querySelector('header button[class*=\"focus:outline-none\"]');
                if (bell) bell.style.outline = '';
            ");
        $browser->pause(1000);
    });
});

test('TC-NOTIF-03: Admin mengecek notifikasi kosong di awal', function () {
    $admin = User::where('email', 'faridzaridzaridzarid@gmail.com')->firstOrFail();
    $admin->refresh();
    $unreadCount = $admin->unreadNotifications()->count();

    $this->browse(function (Browser $browser) use ($admin, $unreadCount) {
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
        $browser->pause(duskDelay());

        if ($unreadCount > 0) {
            $browser->assertPresent('header button[class*="focus:outline-none"] span.bg-red-500');
        } else {
            $browser->assertMissing('header button[class*="focus:outline-none"] span.bg-red-500');
        }

        $browser->click('header button[class*="focus:outline-none"]')
            ->pause(1000)
            ->assertVisible('div[class*="absolute right-0 mt-2"]') // Verify dropdown opened
            ->script("
                const bell = document.querySelector('header button[class*=\"focus:outline-none\"]');
                if (bell) bell.style.outline = '';
            ");
        $browser->pause(1000);
    });
});

test('TC-NOTIF-04: Farid mengecek indikator merah notifikasi pada bell header dan daftar notifikasi aktif pada dropdown bell', function () {
    $farid = User::where('email', 'farid@gmail.com')->firstOrFail();
    $menu = MenuAktif::whereHas('masterMakanan', function ($q) {
        $q->where('nama_makanan', 'MARTABAK DUSK NOTIF');
    })->firstOrFail();
    
    // Explicitly set notification preference
    $farid->notif_donasi = true;
    $farid->save();
    
    $farid->refresh();
    $farid->notify(new \App\Notifications\MakananDekatNotification($menu));

    // Small delay to ensure DB write is committed and visible to the web server
    usleep(500000); // 500ms

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
            ->waitFor('header button[class*="focus:outline-none"] span.bg-red-500');

        $browser->script("
                const bell = document.querySelector('header button[class*=\"focus:outline-none\"]');
                if (bell) bell.style.outline = '';
            ");

        $browser->click('header button[class*="focus:outline-none"]')
            ->waitForText('Makanan Tersedia Dekat Anda!')
            // Highlight the notification dropdown content
            ->script("
                const dropdown = document.querySelector('div[class*=\"absolute right-0 mt-2\"]');
                if (dropdown) {
                    dropdown.style.outline = '3px solid #1cb764';
                    dropdown.style.outlineOffset = '2px';
                }
            ");
        $browser->pause(duskDelay())
            ->assertSee('Makanan Tersedia Dekat Anda!')
            ->script("
                const dropdown = document.querySelector('div[class*=\"absolute right-0 mt-2\"]');
                if (dropdown) dropdown.style.outline = '';
            ");
        $browser->pause(1000);
    });
});

test('TC-NOTIF-05: Farid mengklik notifikasi dan diredirect ke halaman detail menu', function () {
    $farid = User::where('email', 'farid@gmail.com')->firstOrFail();
    $menu = MenuAktif::whereHas('masterMakanan', function ($q) {
        $q->where('nama_makanan', 'MARTABAK DUSK NOTIF');
    })->firstOrFail();
    
    // Explicitly set notification preference
    $farid->notif_donasi = true;
    $farid->save();
    
    $farid->refresh();
    $farid->notify(new \App\Notifications\MakananDekatNotification($menu));

    usleep(500000);

    $this->browse(function (Browser $browser) use ($farid, $menu) {
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
        $browser->pause(duskDelay());

        $browser->script("
                const bell = document.querySelector('header button[class*=\"focus:outline-none\"]');
                if (bell) bell.style.outline = '';
            ");

        $browser->click('header button[class*="focus:outline-none"]')
            ->waitForText('Makanan Tersedia Dekat Anda!')
            // Highlight the specific notification item in the dropdown
            ->script("
                const items = Array.from(document.querySelectorAll('div.max-h-64 a'));
                const target = items.find(el => el.textContent.includes('Makanan Tersedia Dekat Anda!'));
                if (target) {
                    const container = target.closest('div[class*=\"p-4\"]');
                    if (container) {
                        container.style.outline = '3px solid #ef4444';
                        container.style.outlineOffset = '-4px';
                    }
                }
            ");
        $browser->pause(1500)
            ->clickLink('Makanan Tersedia Dekat Anda!')
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

test('TC-NOTIF-06: Farid menonaktifkan pengaturan notifikasi donasi via UI', function () {
    $farid = User::where('email', 'farid@gmail.com')->firstOrFail();
    
    // Explicitly set notification preference before toggle off via UI
    $farid->notif_donasi = true;
    $farid->save();

    $this->browse(function (Browser $browser) use ($farid) {
        $browser->loginAs($farid)
            ->visit('/user/dashboard')
            ->waitForText('Halo,')
            ->clickLink('Pengaturan')
            ->waitForLocation('/user/pengaturan')
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

test('TC-NOTIF-07: Farid memverifikasi tidak menerima notifikasi baru setelah dinonaktifkan', function () {
    $farid = User::where('email', 'farid@gmail.com')->firstOrFail();
    $menu = MenuAktif::whereHas('masterMakanan', function ($q) {
        $q->where('nama_makanan', 'MARTABAK DUSK NOTIF');
    })->firstOrFail();
    
    // Get initial notifications count from DB
    $initialCount = $farid->notifications()->count();
    
    // Attempt to notify farid (should be ignored since notif_donasi is false)
    $farid->refresh();
    $farid->notify(new \App\Notifications\MakananDekatNotification($menu));

    // Confirm that the count has not increased in the database
    $farid->refresh();
    expect($farid->notifications()->count())->toBe($initialCount);

    usleep(500000);

    $this->browse(function (Browser $browser) use ($farid, $initialCount) {
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
            ->assertMissing('header button[class*="focus:outline-none"] span.bg-red-500');

        $browser->script("
                const bell = document.querySelector('header button[class*=\"focus:outline-none\"]');
                if (bell) bell.style.outline = '';
            ");

        $browser->click('header button[class*="focus:outline-none"]')
            ->pause(1000)
            // Assert that the number of visible notifications in the dropdown has not increased
            ->assertScript("document.querySelectorAll('div.max-h-64 > div').length === {$initialCount}")
            ->pause(1000);
    });
});

test('TC-NOTIF-08: Jaki mengecek indikator merah notifikasi pada bell header', function () {
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
    
    // Explicitly set notification preference
    $profile = $jaki->unitBisnisProfile;
    if ($profile) {
        $profile->update(['notifikasi_aktif' => true, 'notifikasi_pesanan' => true]);
    }
    $jaki->notify(new \App\Notifications\PesananMasukNotification($pesanan));

    usleep(500000);

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

test('TC-NOTIF-09: Jaki mengklik Tandai Semua Dibaca pada dropdown bell', function () {
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

    // Explicitly set notification settings to true
    $profile = $jaki->unitBisnisProfile;
    if ($profile) {
        $profile->update(['notifikasi_aktif' => true, 'notifikasi_pesanan' => true]);
    }

    $jaki->notify(new \App\Notifications\PesananMasukNotification($pesanan));

    usleep(500000);

    $this->browse(function (Browser $browser) use ($jaki) {
        $browser->loginAs($jaki)
            ->visit('/unit/dashboard')
            ->waitForText('Dashboard')
            // Highlight the bell button showing the orange dot indicator
            ->script("
                const bell = document.querySelector('header button[class*=\"focus:outline-none\"]');
                if (bell) {
                    bell.style.outline = '3px solid #f7b055';
                    bell.style.outlineOffset = '2px';
                }
            ");
        $browser->pause(duskDelay());

        $browser->script("
                const bell = document.querySelector('header button[class*=\"focus:outline-none\"]');
                if (bell) bell.style.outline = '';
            ");

        $browser->click('header button[class*="focus:outline-none"]')
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
            // Assert that the notification bell orange dot is missing
            ->assertMissing('header button[class*="focus:outline-none"] span.rounded-full')
            ->pause(1000)
            // Click the notification bell again to prove all notifications are read
            ->click('header button[class*="focus:outline-none"]')
            ->pause(1000)
            // Highlight the dropdown to show there are no unread green dots
            ->script("
                const dropdown = document.querySelector('div[class*=\"absolute right-0 mt-2\"]');
                if (dropdown) {
                    dropdown.style.outline = '3px solid #1cb764';
                    dropdown.style.outlineOffset = '2px';
                }
            ");
        $browser->pause(duskDelay())
            // Assert that no unread green dot buttons remain in the dropdown
            ->assertMissing('div.max-h-64 button[class*="bg-[#1cb764]"]')
            ->script("
                const dropdown = document.querySelector('div[class*=\"absolute right-0 mt-2\"]');
                if (dropdown) dropdown.style.outline = '';
            ");
        $browser->pause(1000);
    });
});

test('TC-NOTIF-10: Jaki menonaktifkan pengaturan notifikasi pesanan via UI', function () {
    $jaki = User::where('email', 'jaki.munawaroh@bakery.com')->firstOrFail();
    $profile = $jaki->unitBisnisProfile;

    $this->browse(function (Browser $browser) use ($jaki) {
        $browser->loginAs($jaki)
            ->visit('/unit/dashboard')
            ->waitForText('Dashboard')
            ->clickLink('Pengaturan')
            ->waitForLocation('/unit/pengaturan')
            ->waitForText('Pengaturan Operasional')
            ->pause(1000)
            // Highlight the toggle label
            ->script("
                const label = document.querySelector('label[class*=\"relative inline-flex items-center\"]');
                if (label) {
                    label.style.outline = '3px solid #1cb764';
                    label.style.outlineOffset = '4px';
                }
            ");
        $browser->pause(1500)
            // Click the toggle label to disable notifications
            ->click('label[class*="relative inline-flex items-center"]')
            ->pause(1500)
            // Remove toggle highlight
            ->script("
                const label = document.querySelector('label[class*=\"relative inline-flex items-center\"]');
                if (label) label.style.outline = '';
            ");
        $browser->pause(1000);

        // Submit form
        $browser->click('#settings-form button[type="submit"]')
            ->waitForLocation('/unit/pengaturan')
            ->assertSee('Pengaturan berhasil diperbarui!')
            ->pause(duskDelay());
    });

    if ($profile) {
        $profile->refresh();
        expect((bool)$profile->notifikasi_aktif)->toBeFalse();
    }
});

test('TC-NOTIF-11: Jaki memverifikasi tidak menerima notifikasi pesanan baru setelah dinonaktifkan', function () {
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
    
    // Get initial notifications count from DB
    $initialCount = $jaki->notifications()->count();
    
    // Attempt to notify jaki (should be ignored since notifikasi_aktif is false)
    $jaki->refresh();
    $jaki->notify(new \App\Notifications\PesananMasukNotification($pesanan));

    // Confirm that the count has not increased in the database
    $jaki->refresh();
    expect($jaki->notifications()->count())->toBe($initialCount);

    usleep(500000);

    $this->browse(function (Browser $browser) use ($jaki, $initialCount) {
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
            ->assertMissing('header button[class*="focus:outline-none"] span.rounded-full');

        $browser->script("
                const bell = document.querySelector('header button[class*=\"focus:outline-none\"]');
                if (bell) bell.style.outline = '';
            ");

        $browser->click('header button[class*="focus:outline-none"]')
            ->pause(1000)
            // Assert that the number of visible notifications in the dropdown has not increased
            ->assertScript("document.querySelectorAll('div.max-h-64 > div').length === {$initialCount}")
            ->pause(1000);
    });
});

test('TC-NOTIF-12: Admin mengecek indikator merah pendaftaran unit bisnis baru dan dialihkan ke halaman manajemen pengguna saat mengklik notifikasi', function () {
    $admin = User::where('email', 'faridzaridzaridzarid@gmail.com')->firstOrFail();
    $newUnit = User::where('email', 'new.partner@sharebite.com')->firstOrFail();

    $this->browse(function (Browser $browser) use ($admin) {
        $browser->loginAs($admin)
            ->visit('/admin/dashboard')
            ->waitForText('Dashboard')
            // Highlight Admin's bell button showing the red dot indicator
            ->script("
                const bell = document.querySelector('header button[class*=\"focus:outline-none\"]');
                if (bell) {
                    bell.style.outline = '3px solid #ef4444';
                    bell.style.outlineOffset = '2px';
                }
            ");
        $browser->pause(duskDelay())
            ->assertPresent('header button[class*="focus:outline-none"] span.bg-red-500');

        $browser->script("
                const bell = document.querySelector('header button[class*=\"focus:outline-none\"]');
                if (bell) bell.style.outline = '';
            ");

        $browser->click('header button[class*="focus:outline-none"]')
            ->waitForText('Mitra Baru Mendaftar!')
            // Highlight the specific notification item in the dropdown
            ->script("
                const items = Array.from(document.querySelectorAll('div.max-h-64 a'));
                const target = items.find(el => el.textContent.includes('Mitra Baru Mendaftar!'));
                if (target) {
                    const container = target.closest('div[class*=\"p-4\"]');
                    if (container) {
                        container.style.outline = '3px solid #ef4444';
                        container.style.outlineOffset = '-4px';
                    }
                }
            ");
        $browser->pause(1500)
            ->clickLink('Mitra Baru Mendaftar!')
            ->waitForLocation('/admin/manajemen-pengguna')
            ->assertPathIs('/admin/manajemen-pengguna')
            ->pause(duskDelay());
    });
});
