<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\MenuAktif;
use App\Models\Pesanan;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\DB;

class PembayaranTest extends DuskTestCase
{
    protected string $userEmail = 'komunitas@sharebite.com';
    protected string $namaMenu  = 'eskrim'; // nama_makanan di tabel master_makanans

    /** ID MenuAktif yang akan diambil saat setUp */
    protected int $menuId;

    /**
     * Paksa pakai DB_DATABASE dari .env utama, bukan 'sharebite_dusk'.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $dbName = 'sharebite';
        if (file_exists(base_path('.env'))) {
            $envContent = file_get_contents(base_path('.env'));
            if (preg_match('/^DB_DATABASE\s*=\s*(.+)$/m', $envContent, $matches)) {
                $dbName = trim($matches[1], " \t\n\r\0\x0B\"'");
            }
        }

        config(["database.connections.mysql.database" => $dbName]);
        DB::purge('mysql');
        DB::reconnect('mysql');

        // Clean up conflicting unique codes
        Pesanan::where('kode_unik', 'SB-111-AAA')->delete();

        // Ambil ID MenuAktif berdasarkan nama makanan
        $this->menuId = MenuAktif::whereHas('masterMakanan', function ($q) {
            $q->where('nama_makanan', $this->namaMenu);
        })->firstOrFail()->id;

        // Pastikan batas pengambilan selalu di masa depan agar tidak dianggap kadaluarsa oleh database
        MenuAktif::find($this->menuId)->update([
            'batas_pengambilan' => now()->addHours(5)
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // Helper: login dan kunjungi halaman pembayaran
    // ─────────────────────────────────────────────────────────────

    private function loginUser(Browser $browser): void
    {
        $user = User::where('email', $this->userEmail)->firstOrFail();
        $browser->loginAs($user);
    }

    private function visitPembayaran(Browser $browser): void
    {
        $this->loginUser($browser);
        $browser->visit("/user/dashboard/{$this->menuId}/pembayaran?qty=1")
                ->waitForLocation("/user/dashboard/{$this->menuId}/pembayaran", 10);
    }

    private function visitBerhasil(Browser $browser): void
    {
        $user = User::where('email', $this->userEmail)->firstOrFail();
        $menu = MenuAktif::findOrFail($this->menuId);

        // Pastikan ada pesanan dengan status 'dibayar' agar halaman berhasil bisa dibuka
        $pesanan = Pesanan::where('user_id', $user->id)
            ->where('menu_aktif_id', $menu->id)
            ->whereIn('status', ['proses', 'siap_diambil', 'dibayar', 'diambil'])
            ->first();

        if (!$pesanan) {
            $pesanan = Pesanan::create([
                'user_id' => $user->id,
                'menu_aktif_id' => $menu->id,
                'status' => 'dibayar',
                'unit_bisnis_id' => $menu->unit_bisnis_id,
                'jumlah_porsi' => 1,
                'total_harga' => $menu->harga_jual,
                'kode_unik' => 'SB-111-AAA',
                'waktu_pesan' => now(),
            ]);

            Pembayaran::create([
                'pesanan_id' => $pesanan->id,
                'status' => 'berhasil',
                'qrcode' => 'SB-QR-SUCCESS',
                'waktu_bayar' => now(),
            ]);
        }

        $this->loginUser($browser);
        $browser->visit("/user/dashboard/{$this->menuId}/pembayaran/berhasil?qty=1")
                ->waitForLocation("/user/dashboard/{$this->menuId}/pembayaran/berhasil", 10);
    }

    // ═════════════════════════════════════════════════════════════
    // TEST 1 — Tombol ← (back arrow) → halaman riwayat
    // ═════════════════════════════════════════════════════════════

    public function testBackArrowRedirectsToRiwayat(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visitPembayaran($browser);

            $browser->click('a[href*="riwayat"]')
                    ->assertPathIs('/user/riwayat')
                    ->assertSee('MENUNGGU PEMBAYARAN');
        });
    }

    // ═════════════════════════════════════════════════════════════
    // TEST 2a — Hover map di halaman PEMBAYARAN → muncul "Buka Map"
    // ═════════════════════════════════════════════════════════════

    public function testHoverMapPembayaranShowsBukaMap(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visitPembayaran($browser);

            $browser->waitFor('#map-pembayaran', 10);
            $browser->mouseover('[onclick*="google.com/maps"]');
            $browser->waitForText('Buka Map', 5)
                    ->assertSee('Buka Map');
        });
    }

    // ═════════════════════════════════════════════════════════════
    // TEST 2b — Klik area map PEMBAYARAN → onclick ke Google Maps
    // ═════════════════════════════════════════════════════════════

    public function testClickMapPembayaranHasGoogleMapsUrl(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visitPembayaran($browser);

            $browser->waitFor('#map-pembayaran', 10);

            $onclick = $browser->attribute('[onclick*="google.com/maps"]', 'onclick');
            $this->assertStringContainsString('google.com/maps', $onclick);
        });
    }

    // ═════════════════════════════════════════════════════════════
    // TEST 3 — Link "Buka di Google Maps" → href mengandung google.com/maps
    // ═════════════════════════════════════════════════════════════

    public function testBukaDiGoogleMapsLinkIsCorrect(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visitPembayaran($browser);

            $href = $browser->attribute('a[href*="google.com/maps"]', 'href');
            $this->assertStringContainsString('google.com/maps', $href);
        });
    }

    // ═════════════════════════════════════════════════════════════
    // TEST 4 — Scan QRIS dari HP → laptop otomatis ke halaman berhasil
    // ═════════════════════════════════════════════════════════════

    public function testScanQrisRedirectsToPembayaranBerhasil(): void
    {
        $this->browse(function (Browser $laptop, Browser $hp) {
            $this->visitPembayaran($laptop);

            // HP scan QR — route publik, tidak perlu login
            $hp->visit("/public/scan-qris/{$this->menuId}")
               ->waitForText('Pembayaran Berhasil', 10);

            // Laptop polling tiap 2 detik, tunggu hingga redirect
            $laptop->pause(5000)
                   ->assertPathContains('berhasil');
        });
    }

    // ═════════════════════════════════════════════════════════════
    // TEST 5 — Ketika timer habis -> arahkan ke halaman riwayat
    // ═════════════════════════════════════════════════════════════

    public function testTimerExpiredRedirectsToRiwayat(): void
    {
        $this->browse(function (Browser $browser) {
            $user = User::where('email', $this->userEmail)->firstOrFail();
            $menu = MenuAktif::findOrFail($this->menuId);

            // Hapus pesanan lama milik user untuk menu ini jika ada
            Pesanan::where('user_id', $user->id)
                   ->where('menu_aktif_id', $menu->id)
                   ->delete();

            // Buat pesanan baru yang akan habis dalam 2 detik (14 menit 58 detik yang lalu)
            // Jadi belum terhapus oleh cancelExpiredPaymentOrders (karena belum 15 menit),
            // tetapi begitu halaman dimuat, timer akan bernilai 2 detik dan kemudian mati.
            $pesanan = Pesanan::create([
                'user_id' => $user->id,
                'menu_aktif_id' => $menu->id,
                'status' => 'menunggu_pembayaran',
                'unit_bisnis_id' => $menu->unit_bisnis_id,
                'jumlah_porsi' => 1,
                'total_harga' => $menu->harga_jual,
                'kode_unik' => 'SB-777-XYZ',
                'waktu_pesan' => now()->subMinutes(14)->subSeconds(58),
            ]);

            Pembayaran::create([
                'pesanan_id' => $pesanan->id,
                'status' => 'menunggu',
                'qrcode' => 'SB-QR-EXPIRED',
            ]);

            $this->loginUser($browser);
            $browser->visit("/user/dashboard/{$this->menuId}/pembayaran?qty=1")
                    ->waitForLocation("/user/riwayat", 10)
                    ->assertPathIs('/user/riwayat')
                    ->assertSee('BATAL');
        });
    }

    // ═════════════════════════════════════════════════════════════
    // TEST 6 — Refresh halaman pembayaran -> Timer tetap lanjut
    // ═════════════════════════════════════════════════════════════

    public function testTimerContinuesAfterRefresh(): void
    {
        $this->browse(function (Browser $browser) {
            // Bersihkan pesanan lama agar dimulai dari 15 menit penuh
            $user = User::where('email', $this->userEmail)->firstOrFail();
            Pesanan::where('user_id', $user->id)
                   ->where('menu_aktif_id', $this->menuId)
                   ->delete();

            $this->visitPembayaran($browser);

            $browser->waitFor('#payment-timer', 10)
                    ->pause(1500);
            $timeBefore = $browser->text('#payment-timer');

            $browser->pause(2000)->refresh();
            $browser->waitFor('#payment-timer', 10)
                    ->pause(1500);
            $timeAfter = $browser->text('#payment-timer');

            $parseSeconds = function ($timeStr) {
                $parts = explode(':', $timeStr);
                return (int)$parts[0] * 60 + (int)$parts[1];
            };

            $secsBefore = $parseSeconds($timeBefore);
            $secsAfter = $parseSeconds($timeAfter);

            $this->assertLessThan($secsBefore, $secsAfter, "Timer should decrease after refresh.");
            $this->assertLessThanOrEqual(900, $secsAfter);
        });
    }

    // ═════════════════════════════════════════════════════════════
    // TEST 7 — Klik "Unduh QR Code" → halaman tidak berpindah
    // ═════════════════════════════════════════════════════════════

    public function testUnduhQRCodeTriggersDownload(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visitPembayaran($browser);

            $browser->waitFor('#qr-canvas', 10);

            $browser->click('button[onclick="downloadQR()"]')
                    ->pause(1500);

            $browser->assertPathIs("/user/dashboard/{$this->menuId}/pembayaran");
        });
    }

    // ═════════════════════════════════════════════════════════════
    // TEST 8 — Klik "Lihat Kode Verifikasi" → kode tampil (tidak blur)
    // ═════════════════════════════════════════════════════════════

    public function testLihatKodeVerifikasiShowsKode(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visitBerhasil($browser);

            $classAwal = $browser->attribute('#teks-kode', 'class');
            $this->assertStringContainsString('blur', $classAwal, 'Kode seharusnya blur di awal.');

            $browser->click('#btn-lihat-kode')->pause(500);

            $classSetelah = $browser->attribute('#teks-kode', 'class');
            $this->assertStringNotContainsString('blur', $classSetelah, 'Kode masih blur setelah tombol diklik.');
        });
    }

    // ═════════════════════════════════════════════════════════════
    // TEST 9 — Klik ikon mata tutup → kode kembali blur
    // ═════════════════════════════════════════════════════════════

    public function testTutupKodeVerifikasiReturnsBlur(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visitBerhasil($browser);

            $browser->click('#btn-lihat-kode')->pause(500);
            $browser->click('#btn-tutup-kode')->pause(500);

            $classSetelah = $browser->attribute('#teks-kode', 'class');
            $this->assertStringContainsString('blur', $classSetelah, 'Kode seharusnya kembali blur setelah tombol tutup diklik.');
        });
    }

    // ═════════════════════════════════════════════════════════════
    // TEST 10 — Refresh halaman berhasil → kode verifikasi tetap sama
    // ═════════════════════════════════════════════════════════════

    public function testKodeVerifikasiSameAfterRefresh(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visitBerhasil($browser);

            $browser->click('#btn-lihat-kode')->pause(500);
            $kodeBefore = trim($browser->text('#teks-kode'));

            $browser->refresh()->waitForLocation(
                "/user/dashboard/{$this->menuId}/pembayaran/berhasil",
                10
            );

            $browser->click('#btn-lihat-kode')->pause(500);
            $kodeAfter = trim($browser->text('#teks-kode'));

            $this->assertEquals(
                $kodeBefore,
                $kodeAfter,
                'Kode verifikasi berubah setelah refresh — seharusnya tetap sama.'
            );
        });
    }

    // ═════════════════════════════════════════════════════════════
    // TEST 11 — Link "Petunjuk Arah" → Google Maps
    // ═════════════════════════════════════════════════════════════

    public function testPetunjukArahLinkIsGoogleMaps(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visitBerhasil($browser);

            $href = $browser->attribute('a[href*="google.com/maps"]', 'href');
            $this->assertStringContainsString(
                'google.com/maps',
                $href,
                'Link Petunjuk Arah tidak mengarah ke Google Maps.'
            );
        });
    }

    // ═════════════════════════════════════════════════════════════
    // TEST 2c — Hover map di halaman BERHASIL → muncul "Buka Map"
    // ═════════════════════════════════════════════════════════════

    public function testHoverMapBerhasilShowsBukaMap(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visitBerhasil($browser);

            $browser->waitFor('#map-berhasil', 10);
            $browser->mouseover('[onclick*="google.com/maps"]');
            $browser->waitForText('Buka Map', 5)
                    ->assertSee('Buka Map');
        });
    }

    // ═════════════════════════════════════════════════════════════
    // TEST 2d — Klik area map BERHASIL → onclick ke Google Maps
    // ═════════════════════════════════════════════════════════════

    public function testClickMapBerhasilHasGoogleMapsUrl(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visitBerhasil($browser);

            $browser->waitFor('#map-berhasil', 10);

            $onclick = $browser->attribute('[onclick*="google.com/maps"]', 'onclick');
            $this->assertStringContainsString('google.com/maps', $onclick);
        });
    }

    // ═════════════════════════════════════════════════════════════
    // TEST 12 — Tombol "Lihat Riwayat Pesanan" → halaman riwayat
    // ═════════════════════════════════════════════════════════════

    public function testLihatRiwayatPesananRedirectsToRiwayat(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visitBerhasil($browser);

            $browser->clickLink('Lihat Riwayat Pesanan')
                    ->assertPathIs('/user/riwayat')
                    ->assertSee('PROSES');
        });
    }
}