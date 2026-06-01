<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PembayaranTest extends DuskTestCase
{
    protected string $userEmail = 'komunitas@sharebite.com';
    protected string $slug      = 'eskrim';

    /**
     * Paksa pakai DB_DATABASE dari .env utama, bukan 'sharebite_dusk'.
     * Tidak perlu membuat .env.dusk.local sama sekali.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $dbName = env('DB_DATABASE', 'sharebite');
        config(["database.connections.mysql.database" => $dbName]);
        DB::purge('mysql');
        DB::reconnect('mysql');
    }

    // ─────────────────────────────────────────────────────────────
    // Helper: loginAs() langsung — tidak perlu isi form login
    // ─────────────────────────────────────────────────────────────

    private function loginUser(Browser $browser): void
    {
        $user = User::where('email', $this->userEmail)->firstOrFail();
        $browser->loginAs($user);
    }

    private function visitPembayaran(Browser $browser): void
    {
        $this->loginUser($browser);
        $browser->visit("/user/dashboard/{$this->slug}/pembayaran?qty=1")
                ->waitForLocation("/user/dashboard/{$this->slug}/pembayaran", 10);
    }

    private function visitBerhasil(Browser $browser): void
    {
        $this->loginUser($browser);
        $browser->visit("/user/dashboard/{$this->slug}/pembayaran/berhasil?qty=1")
                ->waitForLocation("/user/dashboard/{$this->slug}/pembayaran/berhasil", 10);
    }

    // ═════════════════════════════════════════════════════════════
    // TEST 1 — Tombol ← (back arrow) → halaman riwayat
    // ═════════════════════════════════════════════════════════════

    public function testBackArrowRedirectsToRiwayat(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visitPembayaran($browser);

            $browser->click('a[href*="riwayat"]')
                    ->assertPathIs('/user/riwayat');
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
            $hp->visit("/public/scan-qris/{$this->slug}")
               ->waitForText('Pembayaran Berhasil', 10);

            // Laptop polling tiap 2 detik, tunggu hingga redirect
            $laptop->pause(5000)
                   ->assertPathContains('berhasil');
        });
    }

    // ═════════════════════════════════════════════════════════════
    // TEST 5 — Timer habis → redirect ke halaman riwayat
    // ═════════════════════════════════════════════════════════════

    public function testTimerExpiredRedirectsToRiwayat(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visitPembayaran($browser); // ← pakai helper yang sudah ada, hapus loginAs($this->user)

            // Set timer di localStorage agar expired dalam 2 detik
            $browser->script("
                const slug = document.getElementById('qr-meta').dataset.slug;
                const cleanName = slug.replace(/[^a-zA-Z0-9]/g, '_').toLowerCase();
                const storageKey = 'timer_bayar_' + cleanName;
                localStorage.setItem(storageKey, (Date.now() + 2000).toString());
            ");

            $browser->refresh()
                    ->pause(5000)
                    ->assertPathIs('/user/riwayat');
        });
    }

    // ═════════════════════════════════════════════════════════════
    // TEST 6 — Refresh halaman → timer TIDAK diulang dari 15:00
    // ═════════════════════════════════════════════════════════════

    public function testTimerContinuesAfterRefresh(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visitPembayaran($browser);

            $browser->waitFor('#payment-timer', 5);

            $cleanSlug  = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $this->slug));
            $storageKey = "timer_bayar_{$cleanSlug}";

            $expireBefore = $browser->script(
                "return localStorage.getItem('{$storageKey}');"
            )[0];

            $this->assertNotNull($expireBefore, 'Timer belum tersimpan di localStorage.');

            $browser->pause(2000)->refresh();
            $browser->waitFor('#payment-timer', 5);

            $expireAfter = $browser->script(
                "return localStorage.getItem('{$storageKey}');"
            )[0];

            $this->assertEquals(
                $expireBefore,
                $expireAfter,
                'Timer di-reset setelah refresh — seharusnya tetap melanjutkan.'
            );

            $timerText = $browser->text('#payment-timer');
            $this->assertNotEquals('15:00', $timerText, 'Timer kembali ke 15:00 setelah refresh.');
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

            $browser->assertPathIs("/user/dashboard/{$this->slug}/pembayaran");
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
                "/user/dashboard/{$this->slug}/pembayaran/berhasil",
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
    // TEST 12 — Tombol "Lihat Riwayat Pesanan" → halaman riwayat
    // ═════════════════════════════════════════════════════════════

    public function testLihatRiwayatPesananRedirectsToRiwayat(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visitBerhasil($browser);

            $browser->clickLink('Lihat Riwayat Pesanan')
                    ->assertPathIs('/user/riwayat');
        });
    }
}