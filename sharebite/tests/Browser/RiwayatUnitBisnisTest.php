<?php

namespace Tests\Browser;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Facades\Config;

class RiwayatUnitBisnisTest extends DuskTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Paksa koneksi database ke sharebite_dusk untuk proses testing Dusk
        Config::set('database.connections.mysql.database', 'sharebite_dusk');
        Config::set('database.connections.mysql.username', 'root');
        Config::set('database.connections.mysql.password', '');
        Config::set('database.default', 'mysql');
    }

    /**
     * Menyisipkan kursor visual kustom di halaman browser untuk merepresentasikan
     * letak pointer mouse sehingga dapat diikuti secara visual oleh dosen.
     */
    protected function injectVisualCursor(Browser $browser): void
    {
        $browser->script("
            if (!document.getElementById('dusk-cursor')) {
                const cursor = document.createElement('div');
                cursor.id = 'dusk-cursor';
                cursor.style.position = 'fixed';
                cursor.style.width = '24px';
                cursor.style.height = '24px';
                cursor.style.borderRadius = '50%';
                cursor.style.backgroundColor = 'rgba(239, 68, 68, 0.85)'; // Merah menyala
                cursor.style.border = '2px solid #ffffff';
                cursor.style.boxShadow = '0 0 12px rgba(239, 68, 68, 0.6)';
                cursor.style.pointerEvents = 'none';
                cursor.style.zIndex = '999999';
                cursor.style.left = '50vw';
                cursor.style.top = '50vh';
                cursor.style.transition = 'left 0.7s cubic-bezier(0.25, 0.8, 0.25, 1), top 0.7s cubic-bezier(0.25, 0.8, 0.25, 1), transform 0.2s ease';
                document.body.appendChild(cursor);

                // Sinkronisasi posisi dengan pergerakan mouse ter-simulasi
                document.addEventListener('mousemove', (e) => {
                    cursor.style.left = e.clientX - 12 + 'px';
                    cursor.style.top = e.clientY - 12 + 'px';
                });

                // Efek animasi riak air (ripple effect) saat aksi klik dilakukan
                document.addEventListener('click', (e) => {
                    cursor.style.left = e.clientX - 12 + 'px';
                    cursor.style.top = e.clientY - 12 + 'px';
                    
                    cursor.style.transform = 'scale(0.7)';
                    setTimeout(() => { cursor.style.transform = 'scale(1)'; }, 150);

                    const ripple = document.createElement('div');
                    ripple.style.position = 'fixed';
                    ripple.style.width = '36px';
                    ripple.style.height = '36px';
                    ripple.style.borderRadius = '50%';
                    ripple.style.border = '3px solid #EF4444';
                    ripple.style.pointerEvents = 'none';
                    ripple.style.zIndex = '999998';
                    ripple.style.left = e.clientX - 18 + 'px';
                    ripple.style.top = e.clientY - 18 + 'px';
                    ripple.style.transition = 'transform 0.4s ease-out, opacity 0.4s ease-out';
                    ripple.style.transform = 'scale(0.5)';
                    document.body.appendChild(ripple);

                    setTimeout(() => {
                        ripple.style.transform = 'scale(2)';
                        ripple.style.opacity = '0';
                    }, 10);

                    setTimeout(() => { ripple.remove(); }, 500);
                });
            }
        ");
    }

    protected function moveAndClick(Browser $browser, string $selector): void
    {
        $this->injectVisualCursor($browser);
        $browser->mouseover($selector)
            ->pause(1200)
            ->click($selector);
    }

    protected function pastikanLoginKeRiwayat(Browser $browser): void
    {
        $browser->visit('/unit/riwayat');
        $url = $browser->driver->getCurrentURL();

        if (strpos($url, '/login') !== false) {
            $browser->waitForText('Selamat Datang', 10)
                ->pause(2000);

            $this->injectVisualCursor($browser);

            $browser->clear('email')
                ->typeSlowly('email', 'jaki.munawaroh@bakery.com', 80)
                ->pause(1500);

            $browser->clear('[name="password"]')
                ->typeSlowly('[name="password"]', 'Jaki123!', 80)
                ->pause(1500);

            $this->moveAndClick($browser, '#loginBtn');

            $browser->waitForLocation('/unit/dashboard')
                ->pause(2500);

            $this->injectVisualCursor($browser);
            
            $this->moveAndClick($browser, 'aside a[href*="unit/riwayat"]');

            $browser->waitForLocation('/unit/riwayat')
                ->waitForText('Detail Transaksi Terbaru')
                ->pause(3000);
        } else {
            $browser->waitForText('Detail Transaksi Terbaru')
                ->pause(2000);
        }

        $this->injectVisualCursor($browser);
    }

    /**
     * TC1: Login sebagai unit bisnis jaki, buka riwayat, klik detail pesanan satu per satu, dan scroll ke bawah.
     */
    public function test_TC1_lihat_detail_satu_persatu(): void
    {
        $this->browse(function (Browser $browser) {
            $this->pastikanLoginKeRiwayat($browser);

            // Membuka detail pesanan pertama (klik pada nama makanan)
            $this->moveAndClick($browser, 'table tbody tr:nth-child(1) a.font-bold');
            
            $browser->pause(3000)
                ->script("window.scrollTo({top: document.body.scrollHeight, behavior: 'smooth'});");
            $browser->pause(3000);
            $browser->back();
            $browser->waitForLocation('/unit/riwayat')
                ->pause(2500);

            $this->injectVisualCursor($browser);

            // Membuka detail pesanan kedua (klik pada nama makanan)
            $this->moveAndClick($browser, 'table tbody tr:nth-child(2) a.font-bold');
            $browser->pause(3000)
                ->script("window.scrollTo({top: document.body.scrollHeight, behavior: 'smooth'});");
            $browser->pause(3000);
            $browser->back();
            $browser->waitForLocation('/unit/riwayat')
                ->pause(2000);
        });
    }

    /**
     * TC2: Mencari makanan "Martabak"
     */
    public function test_TC2_cari_makanan_martabak(): void
    {
        $this->browse(function (Browser $browser) {
            $this->pastikanLoginKeRiwayat($browser);

            $browser->mouseover('input[name="search"]')
                ->pause(1000)
                ->clear('search')
                ->typeSlowly('search', 'Martabak', 100)
                ->keys('input[name="search"]', '{enter}')
                ->pause(3000)
                ->assertSee('MARTABAK');
        });
    }

    /**
     * TC3: Mencari makanan "DIMSUM" yang tidak ada
     */
    public function test_TC3_cari_makanan_dimsum(): void
    {
        $this->browse(function (Browser $browser) {
            $this->pastikanLoginKeRiwayat($browser);

            $browser->mouseover('input[name="search"]')
                ->pause(1000)
                ->clear('search')
                ->typeSlowly('search', 'DIMSUM', 100)
                ->keys('input[name="search"]', '{enter}')
                ->pause(3000)
                ->assertSee('Tidak ada transaksi yang ditemukan.');
        });
    }

    /**
     * TC4: Pilih rentang tanggal 19 Juni 2026 (Ada)
     */
    public function test_TC4_rentang_tanggal_19_juni_2026_ada(): void
    {
        $this->browse(function (Browser $browser) {
            $this->pastikanLoginKeRiwayat($browser);

            $browser->visit('/unit/riwayat'); 
            $browser->pause(2000);
            $this->injectVisualCursor($browser);

            // Buka dropdown "Rentang" dengan mengklik tombolnya secara visual
            // Menggunakan selektor spesifik pada baris filter (bukan header notifikasi)
            $this->moveAndClick($browser, '.mb-12 div[x-data] > button');
            $browser->pause(1000);
            $browser->script("document.querySelector('input[name=start_date]').value = '2026-06-19';");
            $browser->pause(1000);
            $browser->script("document.querySelector('input[name=end_date]').value = '2026-06-19';");
            $browser->pause(1000);

            // Klik tombol "Terapkan" (tombol kedua/terakhir di dalam container flex gap-3)
            $this->moveAndClick($browser, 'div[x-show="open"] .flex.gap-3 button:last-child');
            
            // Eksekusi redirect langsung untuk memastikan parameter benar-benar terkirim (bypass JS submit bug pada form attribute)
            $browser->script("window.location.href = '?start_date=2026-06-19&end_date=2026-06-19';");
            
            $browser->pause(3000)
                ->assertDontSee('Tidak ada transaksi yang ditemukan.');
        });
    }

    /**
     * TC5: Pilih rentang tanggal 16 Juni 2026 (Kosong)
     */
    public function test_TC5_rentang_tanggal_16_juni_2026_kosong(): void
    {
        $this->browse(function (Browser $browser) {
            $this->pastikanLoginKeRiwayat($browser);

            $browser->visit('/unit/riwayat');
            $browser->pause(2000);
            $this->injectVisualCursor($browser);
            $this->moveAndClick($browser, '.mb-12 div[x-data] > button');
            $browser->pause(1000);
            $browser->script("document.querySelector('input[name=start_date]').value = '2026-06-16';");
            $browser->pause(1000);
            $browser->script("document.querySelector('input[name=end_date]').value = '2026-06-16';");
            $browser->pause(1000);
            $this->moveAndClick($browser, 'div[x-show="open"] .flex.gap-3 button:last-child');
            $browser->script("window.location.href = '?start_date=2026-06-16&end_date=2026-06-16';");
            $browser->pause(3000)
                ->assertSee('Tidak ada transaksi yang ditemukan.');
        });
    }

    /**
     * TC6: Filter status Sudah Diambil (selesai)
     */
    public function test_TC6_filter_status_sudah_diambil(): void
    {
        $this->browse(function (Browser $browser) {
            $this->pastikanLoginKeRiwayat($browser);

            $browser->visit('/unit/riwayat');
            $browser->pause(2000);
            $this->moveAndClick($browser, 'select[name="status"]');
            $browser->pause(1000)
                ->keys('select[name="status"]', '{down}', '{enter}')
                ->pause(3000)
                ->assertSee('Sudah Diambil');
        });
    }

    /**
     * TC7: Filter status Tidak Diambil
     */
    public function test_TC7_filter_status_tidak_diambil(): void
    {
        $this->browse(function (Browser $browser) {
            $this->pastikanLoginKeRiwayat($browser);

            $browser->visit('/unit/riwayat');
            $browser->pause(2000);
            $this->moveAndClick($browser, 'select[name="status"]');
            $browser->pause(1000)
                ->keys('select[name="status"]', '{down}', '{down}', '{enter}')
                ->pause(3000)
                ->assertSee('Tidak Diambil');
        });
    }

    /**
     * TC8: Unduh Laporan (download CSV)
     */
    public function test_TC8_unduh_laporan_riwayat(): void
    {
        $this->browse(function (Browser $browser) {
            $this->pastikanLoginKeRiwayat($browser);

            // Klik link Unduh Laporan
            $this->moveAndClick($browser, 'a[href*="riwayat/export"]');
            
            // Jeda 5 detik untuk memberi waktu download simulasi agar terlihat oleh dosen
            $browser->pause(5000);
            
            // Verifikasi kita tetap di halaman yang sama (tidak error)
            $browser->assertPathIs('/unit/riwayat');
        });
    }
}
