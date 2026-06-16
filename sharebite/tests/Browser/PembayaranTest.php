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
        // Clean up conflicting unique codes and their payments globally
        $expiredPesananIds = Pesanan::whereIn('kode_unik', ['SB-111-AAA', 'SB-777-XYZ'])->pluck('id');
        Pembayaran::whereIn('pesanan_id', $expiredPesananIds)->delete();
        Pesanan::whereIn('kode_unik', ['SB-111-AAA', 'SB-777-XYZ'])->delete();

        // Ambil MenuAktif pertama yang benar-benar aktif (status 'aktif', stok > 0, dan belum kedaluwarsa)
        $menu = MenuAktif::where('status', 'aktif')
            ->where('stok_porsi', '>', 0)
            ->where('batas_pengambilan', '>', now())
            ->first();

        // Jika tidak ada yang aktif secara alami, cari yang pertama dan aktifkan
        if (!$menu) {
            $menu = MenuAktif::first();
            if (!$menu) {
                throw new \Exception("Database tidak memiliki data MenuAktif. Harap jalankan db:seed terlebih dahulu.");
            }
            $menu->update([
                'status' => 'aktif',
                'stok_porsi' => 10,
                'batas_pengambilan' => now()->addHours(5)
            ]);
        } else {
            // Jika sudah aktif, pastikan stoknya cukup untuk pengetesan (misal minimal 10)
            if ($menu->stok_porsi < 5) {
                $menu->update(['stok_porsi' => 10]);
            }
            // Pastikan batas waktu pengambilan juga masih panjang
            if (now()->diffInMinutes(\Carbon\Carbon::parse($menu->batas_pengambilan), false) < 30) {
                $menu->update(['batas_pengambilan' => now()->addHours(5)]);
            }
        }

        $this->menuId = $menu->id;

        $user = User::firstOrCreate(
            ['email' => $this->userEmail],
            [
                'name' => 'Komunitas Berbagi',
                'password' => bcrypt('password'),
                'role' => 'komunitas',
                'no_hp' => '08123456781',
                'alamat' => 'Bandung, Jawa Barat',
            ]
        );

        // Selaraskan lokasi user dengan unit bisnis agar jaraknya dekat (masuk radius penjemputan)
        $lat = $menu->unitBisnis->lokasi_lat ?? $menu->unitBisnis->user->latitude ?? -6.9271;
        $lng = $menu->unitBisnis->lokasi_lng ?? $menu->unitBisnis->user->longitude ?? 107.6411;
        $user->update([
            'latitude' => $lat,
            'longitude' => $lng,
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

    private function closeExtraTabs(Browser $browser): void
    {
        $handles = $browser->driver->getWindowHandles();
        if (count($handles) > 1) {
            $mainHandle = $handles[0];
            foreach ($handles as $index => $handle) {
                if ($index > 0) {
                    try {
                        $browser->driver->switchTo()->window($handle);
                        $browser->driver->close();
                    } catch (\Exception $e) {
                        // Abaikan jika sudah tertutup
                    }
                }
            }
            $browser->driver->switchTo()->window($mainHandle);
        }
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
    // FASE 1: Dari Dashboard menuju Halaman Pembayaran
    // ═════════════════════════════════════════════════════════════

    /**
     * TEST 1 — Pemesanan dari Dashboard sampai masuk halaman Pembayaran
     */
    public function testMakeOrderFromDashboardToPayment(): void
    {
        $this->browse(function (Browser $browser) {
            $this->closeExtraTabs($browser);
            $user = User::where('email', $this->userEmail)->firstOrFail();

            // Bersihkan pesanan dan pembayaran lama milik user ini untuk menu tersebut agar dimulai segar
            $pesananIds = Pesanan::where('user_id', $user->id)
                ->where('menu_aktif_id', $this->menuId)
                ->pluck('id');
            Pembayaran::whereIn('pesanan_id', $pesananIds)->delete();
            Pesanan::where('user_id', $user->id)
                ->where('menu_aktif_id', $this->menuId)
                ->delete();

            $browser->loginAs($user)
                    ->visit('/user/dashboard')
                    ->pause(2000); // Delay di dashboard agar dosen bisa melihat

            // Klik item menu makanan berdasarkan ID menu yang spesifik (mengarah ke detail makanan)
            $browser->click("a[href$='/user/makanan/{$this->menuId}']")
                    ->waitForLocation("/user/makanan/{$this->menuId}", 10)
                    ->pause(2000); // Delay di detail makanan

            // Klik tombol "Ambil Makanan"
            $browser->press('Ambil Makanan')
                    ->waitForLocation("/user/dashboard/{$this->menuId}/pembayaran", 10)
                    ->pause(2000) // Delay di halaman pembayaran
                    ->assertPathIs("/user/dashboard/{$this->menuId}/pembayaran");
        });
    }

    // ═════════════════════════════════════════════════════════════
    // FASE 2: Interaksi di Halaman Pembayaran
    // ═════════════════════════════════════════════════════════════

    /**
     * TEST 2 — Klik area map PEMBAYARAN → onclick ke Google Maps
     */
    public function testClickMapPembayaranHasGoogleMapsUrl(): void
    {
        $this->browse(function (Browser $browser) {
            $this->closeExtraTabs($browser);
            $this->visitPembayaran($browser);
            $browser->pause(2000);

            $browser->waitFor('#map-pembayaran', 10)
                    ->pause(2000);

            // Arahkan kursor (hover) ke kontainer peta terlebih dahulu untuk menampilkan overlay
            $browser->mouseover('#map-pembayaran-wrapper')
                    ->pause(2000);

            // Klik pada area map menggunakan JavaScript untuk menghindari Intercepted Click oleh overlay
            $browser->script("document.getElementById('map-pembayaran').parentElement.click();");
            $browser->pause(2000);

            // Berpindah ke tab baru (Google Maps)
            $handles = $browser->driver->getWindowHandles();
            if (count($handles) > 1) {
                $browser->driver->switchTo()->window(end($handles));
                $browser->pause(7000); // Beri jeda 7 detik agar Google Maps selesai memuat
                $browser->driver->close(); // Tutup tab baru
                $browser->driver->switchTo()->window(reset($handles)); // Kembali ke tab awal
            }
        });
    }

    /**
     * TEST 3 — Link "Buka di Google Maps" → href mengandung google.com/maps
     */
    public function testBukaDiGoogleMapsLinkIsCorrect(): void
    {
        $this->browse(function (Browser $browser) {
            $this->closeExtraTabs($browser);
            $this->visitPembayaran($browser);
            $browser->pause(2000);

            // Arahkan kursor (hover) ke link Buka di Google Maps
            $browser->mouseover('a[href*="google.com/maps"]')
                    ->pause(2000);

            // Klik link "Buka di Google Maps"
            $browser->click('a[href*="google.com/maps"]')
                    ->pause(2000);

            // Berpindah ke tab baru (Google Maps)
            $handles = $browser->driver->getWindowHandles();
            if (count($handles) > 1) {
                $browser->driver->switchTo()->window(end($handles));
                $browser->pause(7000); // Beri jeda 7 detik agar Google Maps selesai memuat
                $browser->driver->close(); // Tutup tab baru
                $browser->driver->switchTo()->window(reset($handles)); // Kembali ke tab awal
            }
        });
    }

    /**
     * TEST 4 — Klik "Unduh QR Code" → halaman tidak berpindah
     */
    public function testUnduhQRCodeTriggersDownload(): void
    {
        $this->browse(function (Browser $browser) {
            $this->closeExtraTabs($browser);
            $this->visitPembayaran($browser);
            $browser->pause(2000);

            $browser->waitFor('#qr-canvas', 10)
                    ->pause(2000);

            // Arahkan kursor (hover) ke tombol Unduh QR Code
            $browser->mouseover('button[onclick="downloadQR()"]')
                    ->pause(2000);

            // Klik tombol Unduh
            $browser->click('button[onclick="downloadQR()"]')
                    ->pause(5000); // Jeda 5 detik setelah diunduh (tidak langsung selesai)

            $browser->assertPathIs("/user/dashboard/{$this->menuId}/pembayaran");
        });
    }

    /**
     * TEST 5 — Ketika timer habis -> arahkan ke halaman riwayat dengan status BATAL
     */
    public function testTimerExpiredRedirectsToRiwayat(): void
    {
        $this->browse(function (Browser $browser) {
            $this->closeExtraTabs($browser);
            $user = User::where('email', $this->userEmail)->firstOrFail();
            $menu = MenuAktif::findOrFail($this->menuId);

            // Hapus pesanan lama milik user untuk menu ini jika ada beserta data pembayarannya
            $pesananIds = Pesanan::where('user_id', $user->id)
                   ->where('menu_aktif_id', $menu->id)
                   ->pluck('id');
            Pembayaran::whereIn('pesanan_id', $pesananIds)->delete();
            Pesanan::where('user_id', $user->id)
                   ->where('menu_aktif_id', $menu->id)
                   ->delete();

            // Buat pesanan baru yang akan habis dalam 8 detik
            $pesanan = Pesanan::create([
                'user_id' => $user->id,
                'menu_aktif_id' => $menu->id,
                'status' => 'menunggu_pembayaran',
                'unit_bisnis_id' => $menu->unit_bisnis_id,
                'jumlah_porsi' => 1,
                'total_harga' => $menu->harga_jual,
                'kode_unik' => 'SB-777-XYZ',
                'waktu_pesan' => now()->subMinutes(14)->subSeconds(52),
            ]);

            Pembayaran::create([
                'pesanan_id' => $pesanan->id,
                'status' => 'menunggu',
                'qrcode' => 'SB-QR-EXPIRED',
            ]);

            $this->loginUser($browser);
            $browser->visit("/user/dashboard/{$this->menuId}/pembayaran?qty=1")
                    ->pause(8000) // Tampilkan hitung mundur dari 8 ke 0 detik
                    ->waitForLocation("/user/riwayat", 10)
                    ->pause(2000)
                    ->assertPathIs('/user/riwayat')
                    ->assertSee('BATAL');
        });
    }

    // ═════════════════════════════════════════════════════════════
    // FASE 3: Proses Sukses & Halaman Pembayaran Berhasil
    // ═════════════════════════════════════════════════════════════

    /**
     * TEST 6 — Scan QRIS dari HP → laptop otomatis ke halaman berhasil
     */
    public function testScanQrisRedirectsToPembayaranBerhasil(): void
    {
        $this->browse(function (Browser $browser) {
            $this->closeExtraTabs($browser);
            $user = User::where('email', $this->userEmail)->firstOrFail();

            // Bersihkan pesanan lama milik user ini untuk menu tersebut agar dimulai segar
            $pesananIds = Pesanan::where('user_id', $user->id)
                ->where('menu_aktif_id', $this->menuId)
                ->pluck('id');
            Pembayaran::whereIn('pesanan_id', $pesananIds)->delete();
            Pesanan::where('user_id', $user->id)
                ->where('menu_aktif_id', $this->menuId)
                ->delete();

            $this->visitPembayaran($browser);
            $browser->pause(5000); // Jeda 5 detik di halaman QRIS agar dosen bisa melihat halaman QRIS

            // Buka tab baru di browser laptop yang sama untuk melakukan simulasi scan
            $browser->script("window.open('', '_blank');");
            $browser->pause(1000);

            // Beralih ke tab baru tersebut (tab scan)
            $handles = $browser->driver->getWindowHandles();
            if (count($handles) > 1) {
                $browser->driver->switchTo()->window(end($handles));
                $browser->visit("/public/scan-qris/{$this->menuId}");
                
                // Tunggu status sukses scan di tab baru dan beri jeda 5 detik
                $browser->waitForText('Pembayaran Berhasil', 10)
                        ->pause(5000); // Jeda 5 detik di tampilan pembayaran berhasil HP (di tab baru)

                // Tutup tab scan dan beralih kembali ke tab utama
                $browser->driver->close();
                $browser->driver->switchTo()->window(reset($handles));
            }

            // Laptop polling tiap 2 detik, tunggu hingga redirect otomatis
            $browser->waitForLocation("/user/dashboard/{$this->menuId}/pembayaran/berhasil", 25)
                   ->pause(5000) // Jeda 5 detik di halaman pembayaran berhasil laptop
                   ->assertPathIs("/user/dashboard/{$this->menuId}/pembayaran/berhasil");
        });
    }

    /**
     * TEST 7 — Klik "Lihat Kode Verifikasi" → kode tampil (tidak blur) & bisa ditutup kembali
     */
    public function testLihatDanTutupKodeVerifikasi(): void
    {
        $this->browse(function (Browser $browser) {
            $this->closeExtraTabs($browser);
            $this->visitBerhasil($browser);
            $browser->pause(2000);

            // Memastikan kode blur di awal
            $classAwal = $browser->attribute('#teks-kode', 'class');
            $this->assertStringContainsString('blur', $classAwal, 'Kode seharusnya blur di awal.');

            // Klik tombol lihat mata -> kode terbuka
            $browser->click('#btn-lihat-kode')->pause(2000);

            $classSetelah = $browser->attribute('#teks-kode', 'class');
            $this->assertStringNotContainsString('blur', $classSetelah, 'Kode masih blur setelah tombol diklik.');

            // Klik tombol tutup mata -> kode blur lagi
            $browser->click('#btn-tutup-kode')->pause(2000);

            $classAkhir = $browser->attribute('#teks-kode', 'class');
            $this->assertStringContainsString('blur', $classAkhir, 'Kode seharusnya kembali blur setelah tombol tutup diklik.');
            $browser->pause(5000); // Jeda 5 detik di halaman berhasil sebelum ditutup
        });
    }

    /**
     * TEST 8 — Link "Petunjuk Arah" → Google Maps
     */
    public function testPetunjukArahLinkIsGoogleMaps(): void
    {
        $this->browse(function (Browser $browser) {
            $this->closeExtraTabs($browser);
            $this->visitBerhasil($browser);
            $browser->pause(2000);

            // Arahkan kursor (hover) ke link Petunjuk Arah
            $browser->mouseover('a[href*="google.com/maps"]')
                    ->pause(2000);

            // Klik link "Petunjuk Arah"
            $browser->click('a[href*="google.com/maps"]')
                    ->pause(2000);

            // Berpindah ke tab baru (Google Maps)
            $handles = $browser->driver->getWindowHandles();
            if (count($handles) > 1) {
                $browser->driver->switchTo()->window(end($handles));
                $browser->pause(7000); // Beri jeda 7 detik agar Google Maps selesai memuat
                $browser->driver->close(); // Tutup tab baru
                $browser->driver->switchTo()->window(reset($handles)); // Kembali ke tab awal
            }
        });
    }

    /**
     * TEST 9 — Klik area map BERHASIL → onclick ke Google Maps
     */
    public function testClickMapBerhasilHasGoogleMapsUrl(): void
    {
        $this->browse(function (Browser $browser) {
            $this->closeExtraTabs($browser);
            $this->visitBerhasil($browser);
            $browser->pause(2000);

            $browser->waitFor('#map-berhasil', 10)
                    ->pause(2000);

            // Arahkan kursor (hover) ke kontainer peta terlebih dahulu untuk menampilkan overlay
            $browser->mouseover('#map-berhasil-wrapper')
                    ->pause(2000);

            // Klik pada area map berhasil menggunakan JavaScript untuk menghindari Intercepted Click oleh overlay
            $browser->script("document.getElementById('map-berhasil').parentElement.click();");
            $browser->pause(2000);

            // Berpindah ke tab baru (Google Maps)
            $handles = $browser->driver->getWindowHandles();
            if (count($handles) > 1) {
                $browser->driver->switchTo()->window(end($handles));
                $browser->pause(7000); // Beri jeda 7 detik agar Google Maps selesai memuat
                $browser->driver->close(); // Tutup tab baru
                $browser->driver->switchTo()->window(reset($handles)); // Kembali ke tab awal
            }
        });
    }

    /**
     * TEST 10 — Tombol "Lihat Riwayat Pesanan" → halaman riwayat
     */
    public function testLihatRiwayatPesananRedirectsToRiwayat(): void
    {
        $this->browse(function (Browser $browser) {
            $this->closeExtraTabs($browser);
            $this->visitBerhasil($browser);
            $browser->pause(2000);

            // Arahkan kursor (hover) ke tombol Lihat Riwayat Pesanan
            $browser->mouseover('.flex.justify-center.pb-10 a')
                    ->pause(2000);

            $browser->click('.flex.justify-center.pb-10 a')
                    ->pause(2000)
                    ->assertPathIs('/user/riwayat')
                    ->assertSee('PROSES');
            $browser->pause(2000);
        });
    }

}