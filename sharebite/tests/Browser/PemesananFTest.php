<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\MenuAktif;
use App\Models\Pesanan;
use App\Models\Pembayaran;

if (! function_exists('duskDelay')) {
    function duskDelay(): int {
        return (int) env('DUSK_PAUSE_MS', 1500);
    }
}

class PemesananFTest extends DuskTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Force database connection to sharebite_dusk for the Dusk testing process
        \Illuminate\Support\Facades\Config::set('database.connections.mysql.database', 'sharebite_dusk');
        \Illuminate\Support\Facades\Config::set('database.connections.mysql.username', 'root');
        \Illuminate\Support\Facades\Config::set('database.connections.mysql.password', '');
        \Illuminate\Support\Facades\Config::set('database.default', 'mysql');
        \Illuminate\Support\Facades\DB::purge('mysql');
        \Illuminate\Support\Facades\DB::reconnect('mysql');

        // Pastikan Unit Bisnis Jaki Munawaroh terverifikasi agar aman
        $unitUser = User::where('email', 'jaki.munawaroh@bakery.com')->first();
        if ($unitUser) {
            $unitProfile = \App\Models\UnitBisnisProfile::where('user_id', $unitUser->id)->first();
            if ($unitProfile) {
                $unitProfile->update(['status_verifikasi' => 'terverifikasi']);
            }
        }
    }

    /**
     * TC-PESAN-F01: Pemesanan Makanan dari Dashboard & Simulasikan Scan QRIS
     */
    public function test_TC_PESAN_F01_PemesananDanScanQris(): void
    {
        $user = User::where('email', 'farid@gmail.com')->firstOrFail();
        $unitUser = User::where('email', 'jaki.munawaroh@bakery.com')->firstOrFail();
        $unitProfile = \App\Models\UnitBisnisProfile::where('user_id', $unitUser->id)->firstOrFail();

        // Cari MenuAktif 'AYAM PANGGANG' milik Jaki Munawaroh Bakery
        $menu = MenuAktif::where('unit_bisnis_id', $unitProfile->id)
            ->whereHas('masterMakanan', function ($q) {
                $q->where('nama_makanan', 'AYAM PANGGANG');
            })
            ->first();

        if (!$menu) {
            throw new \Exception("MenuAktif 'AYAM PANGGANG' tidak ditemukan untuk Jaki Munawaroh Bakery.");
        }

        // Pastikan stok menu terisi & waktu belum expired
        $menu->update([
            'status' => 'aktif',
            'stok_porsi' => 10,
            'batas_pengambilan' => now()->addHours(5)
        ]);

        // Bersihkan pesanan lama milik user untuk menu ini agar dimulai segar
        $pesananIds = Pesanan::where('user_id', $user->id)
            ->where('menu_aktif_id', $menu->id)
            ->pluck('id');
        Pembayaran::whereIn('pesanan_id', $pesananIds)->delete();
        Pesanan::where('user_id', $user->id)
            ->where('menu_aktif_id', $menu->id)
            ->delete();

        $this->browse(function (Browser $browser) use ($user, $menu) {
            // 1. Mulai dari halaman login dan login secara manual
            $browser->visit('/login')
                ->assertSee('Selamat Datang')
                ->type('email', $user->email)
                ->type('password', 'Password123!')
                ->click('#loginBtn')
                ->waitForLocation('/user/dashboard')
                ->pause(duskDelay());

            // 2. Klik item makanan untuk pergi ke halaman detail
            $browser->click("a[href$='/user/makanan/{$menu->id}']")
                ->waitForLocation("/user/makanan/{$menu->id}", 10)
                ->pause(duskDelay());

            // 3. Klik tombol "Ambil Makanan"
            $browser->press('Ambil Makanan')
                ->waitForLocation("/user/dashboard/{$menu->id}/pembayaran", 10)
                ->pause(duskDelay())
                ->assertPathIs("/user/dashboard/{$menu->id}/pembayaran");

            // 4. Buka tab baru di browser laptop yang sama untuk melakukan simulasi scan
            $browser->script("window.open('', '_blank');");
            $browser->pause(1000);

            // Beralih ke tab baru tersebut (tab scan)
            $handles = $browser->driver->getWindowHandles();
            if (count($handles) > 1) {
                $browser->driver->switchTo()->window(end($handles));
                $browser->visit("/public/scan-qris/{$menu->id}");
                
                // Tunggu status sukses scan di tab baru dan beri jeda 2 detik
                $browser->waitForText('Pembayaran Berhasil', 15)
                        ->pause(2000);

                // Tutup tab scan dan beralih kembali ke tab utama
                $browser->driver->close();
                $browser->driver->switchTo()->window(reset($handles));
            }

            // 5. Tunggu sampai laptop otomatis me-redirect ke halaman berhasil
            $browser->waitForLocation("/user/dashboard/{$menu->id}/pembayaran/berhasil", 25)
                ->pause(duskDelay())
                ->assertPathIs("/user/dashboard/{$menu->id}/pembayaran/berhasil")
                ->assertSee('Pembayaran Berhasil');
        });

        // Verifikasi database bahwa status pesanan tercatat sebagai 'dibayar'
        $this->assertDatabaseHas('pesanans', [
            'user_id' => $user->id,
            'menu_aktif_id' => $menu->id,
            'status' => 'dibayar',
        ]);
    }
}
