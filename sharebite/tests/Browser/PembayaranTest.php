<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;

class PembayaranTest extends DuskTestCase
{
    // =========================================================================
    // KONFIGURASI — Sesuaikan dengan data di database kamu
    // =========================================================================

    /** Slug makanan = nama_makanan di DB, spasi diganti '-' (sesuai controller) */
    protected string $slug = 'piscok';

    /**
     * ID user (bukan unit bisnis / admin) yang bisa mengakses halaman pembayaran.
     * Cek di tabel `users`: pilih user dengan role komunitas/individu.
     */
    protected int $userId = 1;

    // =========================================================================
    // URL — Sudah sesuai dengan web.php, JANGAN diubah
    // =========================================================================
    protected string $paymentUrl;   // /user/dashboard/{slug}/pembayaran
    protected string $successUrl;   // /user/dashboard/{slug}/pembayaran/berhasil
    protected string $scanUrl;      // /public/scan-qris/{slug}
    protected string $riwayatUrl;   // /user/riwayat

    protected function setUp(): void
    {
        parent::setUp();
        $this->paymentUrl = "/user/dashboard/{$this->slug}/pembayaran";
        $this->successUrl = "/user/dashboard/{$this->slug}/pembayaran/berhasil";
        $this->scanUrl    = "/public/scan-qris/{$this->slug}";
        $this->riwayatUrl = '/user/riwayat';
    }

    // ─── Helper: login sebagai user ──────────────────────────────────────────
    private function asUser(Browser $browser): Browser
    {
        return $browser->loginAs($this->userId);
    }

    // ─── Helper: cek classList elemen via JavaScript ──────────────────────────
    private function hasClass(Browser $browser, string $id, string $class): bool
    {
        $result = $browser->script(
            "return document.getElementById('{$id}').classList.contains('{$class}');"
        );
        return (bool)($result[0] ?? false);
    }

    // =========================================================================
    // TEST 1
    // Klik tombol panah (<- Pembayaran) → kembali ke halaman riwayat
    // =========================================================================
    public function testBackButtonNavigatesToRiwayat(): void
    {
        $this->browse(function (Browser $browser) {
            // Kunjungi riwayat dulu agar history.back() ada tujuannya
            $this->asUser($browser)
                 ->visit($this->riwayatUrl)
                 ->waitForText('Riwayat', 10)
                 ->visit($this->paymentUrl)
                 ->waitForText('Pembayaran', 10)
                 // Tombol back adalah <button onclick="window.history.back()">
                 // Selector: button pertama di header dengan class rounded-full
                 ->click('button[onclick="window.history.back()"]')
                 ->pause(1500)
                 ->assertPathIs($this->riwayatUrl);
        });
    }

    // =========================================================================
    // TEST 2
    // Hover ke map → muncul overlay teks 'Buka Map'
    // =========================================================================
    public function testMapHoverShowsBukaMap(): void
    {
        $this->browse(function (Browser $browser) {
            $this->asUser($browser)
                 ->visit($this->paymentUrl)
                 ->waitFor('#map-pembayaran', 10)
                 ->pause(1500); // Tunggu Leaflet selesai render tile

            // Container map memiliki class 'group' (dari blade) — hover ke sana
            $browser->mouseover('.group')
                    ->pause(800)
                    ->assertSee('Buka Map');
        });
    }

    // =========================================================================
    // TEST 3
    // Link 'Buka di Google Maps' → href mengandung google.com/maps + target _blank
    // =========================================================================
    public function testBukaGoogleMapsLinkIsValid(): void
    {
        $this->browse(function (Browser $browser) {
            $this->asUser($browser)
                 ->visit($this->paymentUrl)
                 ->waitForText('Buka di Google Maps', 10);

            // Ambil href dan pastikan mengarah ke Google Maps
            $href = $browser->attribute('a[href*="google.com/maps"]', 'href');
            $this->assertStringContainsString('google.com/maps', $href,
                'Href harus mengandung google.com/maps');

            // Harus terbuka di tab baru
            $target = $browser->attribute('a[href*="google.com/maps"]', 'target');
            $this->assertEquals('_blank', $target,
                'Link Google Maps harus punya target="_blank"');
        });
    }

    // =========================================================================
    // TEST 4
    // Scan QRIS lewat HP → halaman laptop otomatis redirect ke pembayaran berhasil
    //
    // Cara kerja simulasi (2 browser tab):
    //   - $laptop : membuka halaman pembayaran, menunggu polling tiap 2 detik
    //   - $hp     : mengakses /public/scan-qris/{slug} → set Cache sukses
    //   Polling JS di $laptop mendeteksi cache → submit form → redirect ke berhasil
    // =========================================================================
    public function testQrisScanRedirectsToSuccessPage(): void
    {
        $this->browse(function (Browser $laptop, Browser $hp) {
            // LANGKAH 1 — Laptop: buka halaman pembayaran dan tunggu QR render
            $this->asUser($laptop)
                 ->visit($this->paymentUrl)
                 ->waitFor('#qr-canvas', 10)
                 ->pause(500);

            // LANGKAH 2 — HP: akses endpoint scan publik (tidak perlu login)
            // Route: GET /public/scan-qris/{slug} → Cache::put('scan_qris_{slug}', true)
            $hp->visit($this->scanUrl)
               ->assertSee('Pembayaran Berhasil'); // teks dari simulasiScan() di controller

            // LANGKAH 3 — Laptop: tunggu polling JS (setInterval 2000ms) mendeteksi
            // cache dan otomatis submit form → redirect ke halaman berhasil
            $laptop->waitForText('Pembayaran Berhasil!', 10)
                   ->assertSee('Pembayaran Berhasil!')
                   ->assertPathIs($this->successUrl);
        });
    }

    // =========================================================================
    // TEST 5
    // Timer habis → form auto-submit → redirect ke halaman riwayat
    //
    // Supaya tidak menunggu 15 menit, localStorage dimanipulasi
    // agar expire hanya 3 detik dari sekarang.
    // =========================================================================
    public function testTimerExpiredRedirectsToRiwayat(): void
    {
        $this->browse(function (Browser $browser) {
            $this->asUser($browser)
                 ->visit($this->paymentUrl)
                 ->waitFor('#payment-timer', 10);

            // Key localStorage sesuai kode JS di blade:
            // 'timer_bayar_' + slugMenu.replace(/[^a-zA-Z0-9]/g, '_').toLowerCase()
            $cleanSlug  = preg_replace('/[^a-zA-Z0-9]/', '_', strtolower($this->slug));
            $storageKey = 'timer_bayar_' . $cleanSlug;

            // Set expire 3 detik dari sekarang
            $browser->script(
                "localStorage.setItem('{$storageKey}', (Date.now() + 3000).toString());"
            );

            // Reload halaman agar setInterval timer membaca nilai baru dari localStorage
            $browser->refresh()
                    ->waitFor('#payment-timer', 10);

            // Tunggu timer habis (3 detik) + setInterval bekerja + form submit
            $browser->pause(6000)
                    ->assertPathIs($this->riwayatUrl);
        });
    }

    // =========================================================================
    // TEST 6
    // Refresh halaman pembayaran → timer LANJUT dari waktu sebelumnya (tidak reset)
    // =========================================================================
    public function testTimerContinuesAfterPageRefresh(): void
    {
        $this->browse(function (Browser $browser) {
            $this->asUser($browser)
                 ->visit($this->paymentUrl)
                 ->waitFor('#payment-timer', 10)
                 ->pause(3000); // Biarkan timer berjalan 3 detik

            $timerBefore = $browser->text('#payment-timer'); // contoh: "14:51"

            $browser->refresh()
                    ->waitFor('#payment-timer', 10)
                    ->pause(800);

            $timerAfter = $browser->text('#payment-timer'); // contoh: "14:47"

            // Konversi mm:ss → total detik
            [$mB, $sB] = explode(':', $timerBefore);
            [$mA, $sA] = explode(':', $timerAfter);
            $secBefore = (int)$mB * 60 + (int)$sB;
            $secAfter  = (int)$mA * 60 + (int)$sA;

            // Setelah refresh, sisa waktu harus LEBIH KECIL dari sebelum refresh
            // (artinya timer lanjut, bukan reset ke 15:00)
            $this->assertLessThan(
                $secBefore,
                $secAfter,
                "Timer harus lanjut setelah refresh. Sebelum: {$timerBefore}, Sesudah: {$timerAfter}"
            );

            // Pastikan tidak reset ke 15:00 penuh (900 detik)
            $this->assertNotEquals(900, $secAfter,
                'Timer tidak boleh reset ke 15:00 setelah refresh');
        });
    }

    // =========================================================================
    // TEST 7
    // Klik 'Lihat Kode Verifikasi' → kode tampil (class blur dihilangkan)
    // =========================================================================
    public function testLihatKodeVerifikasiShowsCode(): void
    {
        $this->browse(function (Browser $browser) {
            $this->asUser($browser)
                 ->visit($this->successUrl)
                 ->waitFor('#teks-kode', 10);

            // Sebelum diklik: #teks-kode harus punya class blur-[8px]
            $this->assertTrue(
                $this->hasClass($browser, 'teks-kode', 'blur-[8px]'),
                'Kode harus blur sebelum tombol lihat diklik'
            );

            // Klik tombol 'Lihat Kode Verifikasi'
            $browser->click('#btn-lihat-kode')->pause(400);

            // Setelah diklik: blur harus hilang
            $this->assertFalse(
                $this->hasClass($browser, 'teks-kode', 'blur-[8px]'),
                'Kode tidak boleh blur setelah tombol lihat diklik'
            );

            // Tombol lihat harus tidak terlihat (opacity-0)
            $this->assertTrue(
                $this->hasClass($browser, 'btn-lihat-kode', 'opacity-0'),
                'Tombol lihat harus opacity-0 setelah kode ditampilkan'
            );

            // Tombol tutup harus muncul (class hidden dihapus)
            $this->assertFalse(
                $this->hasClass($browser, 'btn-tutup-kode', 'hidden'),
                'Tombol tutup harus terlihat setelah kode ditampilkan'
            );
        });
    }

    // =========================================================================
    // TEST 8
    // Klik ikon mata pojok kanan atas (#btn-tutup-kode) → kode kembali blur
    // =========================================================================
    public function testTutupKodeHidesCodeAgain(): void
    {
        $this->browse(function (Browser $browser) {
            $this->asUser($browser)
                 ->visit($this->successUrl)
                 ->waitFor('#teks-kode', 10);

            // Buka kode dulu
            $browser->click('#btn-lihat-kode')->pause(400);

            // Pastikan kode sudah tampil sebelum ditutup
            $this->assertFalse(
                $this->hasClass($browser, 'teks-kode', 'blur-[8px]'),
                'Kode harus sudah tampil sebelum tombol tutup diklik'
            );

            // Klik tombol tutup (ikon mata pojok kanan atas)
            $browser->click('#btn-tutup-kode')->pause(400);

            // Kode harus kembali blur
            $this->assertTrue(
                $this->hasClass($browser, 'teks-kode', 'blur-[8px]'),
                'Kode harus kembali blur setelah tombol tutup diklik'
            );

            // Tombol lihat harus kembali muncul (opacity-0 dihapus)
            $this->assertFalse(
                $this->hasClass($browser, 'btn-lihat-kode', 'opacity-0'),
                'Tombol lihat harus kembali muncul'
            );

            // Tombol tutup harus kembali hidden
            $this->assertTrue(
                $this->hasClass($browser, 'btn-tutup-kode', 'hidden'),
                'Tombol tutup harus kembali hidden'
            );
        });
    }

    // =========================================================================
    // TEST 9
    // Refresh halaman berhasil → kode verifikasi tetap SAMA (tidak berubah)
    // (Kode disimpan di session Laravel dengan key 'kode_verifikasi_{slug}')
    // =========================================================================
    public function testVerificationCodeRemainsAfterRefresh(): void
    {
        $this->browse(function (Browser $browser) {
            $this->asUser($browser)
                 ->visit($this->successUrl)
                 ->waitFor('#teks-kode', 10);

            // Tampilkan dan ambil kode pertama kali
            $browser->click('#btn-lihat-kode')->pause(400);
            $kodeBefore = trim($browser->text('#teks-kode'));

            // Refresh halaman
            $browser->refresh()
                    ->waitFor('#teks-kode', 10);

            // Tampilkan dan ambil kode setelah refresh
            $browser->click('#btn-lihat-kode')->pause(400);
            $kodeAfter = trim($browser->text('#teks-kode'));

            $this->assertEquals(
                $kodeBefore,
                $kodeAfter,
                "Kode verifikasi harus tetap sama setelah refresh. Sebelum: [{$kodeBefore}], Sesudah: [{$kodeAfter}]"
            );
        });
    }

    // =========================================================================
    // TEST 10
    // Map di halaman berhasil bisa di-drag (Leaflet dragging: true)
    // =========================================================================
    public function testMapOnSuccessPageIsDraggable(): void
    {
        $this->browse(function (Browser $browser) {
            $this->asUser($browser)
                 ->visit($this->successUrl)
                 ->waitFor('#map-berhasil', 10)
                 ->pause(2000); // Tunggu Leaflet + tiles render

            // Verifikasi Leaflet berhasil menginisialisasi container
            $isLeaflet = $browser->script(
                "return document.getElementById('map-berhasil')
                        .classList.contains('leaflet-container');"
            );
            $this->assertTrue((bool)($isLeaflet[0] ?? false),
                '#map-berhasil harus berhasil diinisialisasi sebagai Leaflet container');

            // Ambil koordinat tengah map lalu simulasikan drag kiri 60px
            $browser->script("
                const el  = document.getElementById('map-berhasil');
                const box = el.getBoundingClientRect();
                const cx  = box.left + box.width  / 2;
                const cy  = box.top  + box.height / 2;

                el.dispatchEvent(new MouseEvent('mousedown', {bubbles:true, clientX: cx,      clientY: cy}));
                el.dispatchEvent(new MouseEvent('mousemove', {bubbles:true, clientX: cx + 60, clientY: cy}));
                el.dispatchEvent(new MouseEvent('mouseup',   {bubbles:true, clientX: cx + 60, clientY: cy}));
            ");

            $browser->pause(800);

            // Map masih ada dan tile masih ter-render setelah drag
            $browser->assertPresent('#map-berhasil');

            $hasTiles = $browser->script(
                "return document.querySelector('#map-berhasil .leaflet-tile-container') !== null;"
            );
            $this->assertTrue((bool)($hasTiles[0] ?? false),
                'Tile Leaflet harus tetap ter-render setelah drag');
        });
    }

    // =========================================================================
    // TEST 11
    // Link 'Petunjuk Arah' di halaman berhasil → href Google Maps + target _blank
    // =========================================================================
    public function testPetunjukArahLinkIsValid(): void
    {
        $this->browse(function (Browser $browser) {
            $this->asUser($browser)
                 ->visit($this->successUrl)
                 ->waitForText('Petunjuk Arah', 10);

            $href   = $browser->attribute('a[href*="google.com/maps"]', 'href');
            $target = $browser->attribute('a[href*="google.com/maps"]', 'target');

            $this->assertStringContainsString('google.com/maps', $href,
                'Link Petunjuk Arah harus mengarah ke Google Maps');
            $this->assertEquals('_blank', $target,
                'Link Petunjuk Arah harus terbuka di tab baru (target="_blank")');

            $browser->assertSee('Petunjuk Arah');
        });
    }

    // =========================================================================
    // TEST 12
    // Tombol 'Lihat Riwayat Pesanan' → redirect ke /user/riwayat
    // =========================================================================
    public function testLihatRiwayatPesananNavigatesToRiwayat(): void
    {
        $this->browse(function (Browser $browser) {
            $this->asUser($browser)
                 ->visit($this->successUrl)
                 ->waitForText('Lihat Riwayat Pesanan', 10)
                 ->clickLink('Lihat Riwayat Pesanan')
                 ->pause(1000)
                 ->assertPathIs($this->riwayatUrl);
        });
    }
}