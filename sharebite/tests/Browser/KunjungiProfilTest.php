<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class KunjungiProfilTest extends DuskTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Paksa koneksi database ke sharebite_dusk untuk menggunakan data riil/seeder yang sudah ada
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
                cursor.style.backgroundColor = 'rgba(239, 68, 68, 0.85)';
                cursor.style.border = '2px solid #ffffff';
                cursor.style.boxShadow = '0 0 12px rgba(239, 68, 68, 0.6)';
                cursor.style.pointerEvents = 'none';
                cursor.style.zIndex = '999999';
                cursor.style.left = '50vw';
                cursor.style.top = '50vh';
                cursor.style.transition = 'left 0.7s cubic-bezier(0.25, 0.8, 0.25, 1), top 0.7s cubic-bezier(0.25, 0.8, 0.25, 1), transform 0.2s ease';
                document.body.appendChild(cursor);

                document.addEventListener('mousemove', (e) => {
                    cursor.style.left = e.clientX - 12 + 'px';
                    cursor.style.top = e.clientY - 12 + 'px';
                });

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

    protected function pastikanLogin(Browser $browser, string $targetUrl = '/user/dashboard'): void
    {
        $currentUrl = $browser->driver->getCurrentURL();
        
        // Hanya visit jika belum berada di target URL
        if (!str_contains($currentUrl, $targetUrl)) {
            $browser->visit($targetUrl);
        }
        
        $url = $browser->driver->getCurrentURL();

        if (strpos($url, '/login') !== false) {
            $browser->waitForText('Selamat Datang', 10)
                ->pause(2000);

            $this->injectVisualCursor($browser);

            $browser->clear('email')
                ->typeSlowly('email', 'farid@gmail.com', 80)
                ->pause(1500);

            $browser->clear('[name="password"]')
                ->typeSlowly('[name="password"]', 'Password123!', 80)
                ->pause(1500);

            $this->moveAndClick($browser, '#loginBtn');

            $browser->waitForLocation('/user/dashboard')
                ->pause(2500);
            
            // Jika target URL bukan dashboard, pergi ke target setelah login
            if ($targetUrl !== '/user/dashboard') {
                $browser->visit($targetUrl)->pause(2000);
            }
        }
    }

    /**
     * TC1: Login dari awal sebagai individu Farid, ke detail makanan sushi, klik kunjungi profil,
     * scroll down, cek makanan tersedia sesuai database.
     */
    public function test_TC1_login_dan_kunjungi_profil_sushi(): void
    {
        $this->browse(function (Browser $browser) {
            $this->pastikanLogin($browser);

            // Pergi ke detail makanan Sushi (ID: 4)
            $browser->visit('/user/makanan/4')
                    ->pause(3000);
            
            $this->injectVisualCursor($browser);

            // Pastikan kita ada di halaman detail sushi
            $browser->assertSee('SUSHI');

            // Identifikasi tombol Kunjungi Profil dan klik
            $browser->script("
                const btn = Array.from(document.querySelectorAll('a')).find(el => el.textContent.includes('Kunjungi Profil'));
                if(btn) btn.classList.add('btn-kunjungi-profil-test');
            ");
            
            $this->moveAndClick($browser, '.btn-kunjungi-profil-test');

            // Tunggu hingga navigasi ke profil unit bisnis berhasil
            $browser->waitForLocation('/user/unit-bisnis/1', 10)
                    ->pause(2500);

            // Scroll down perlahan ke akhir halaman
            $browser->script("window.scrollTo({top: document.body.scrollHeight / 2, behavior: 'smooth'});");
            $browser->pause(1500);
            $browser->script("window.scrollTo({top: document.body.scrollHeight, behavior: 'smooth'});");
            $browser->pause(2000);

            // Verifikasi bahwa seksi makanan yang tersedia saat ini terlihat
            $browser->assertSee('Makanan yang Tersedia Saat Ini');

            // Cek ke database riil untuk mencocokkan menu yang harusnya tampil
            $menuAktifs = DB::table('menu_aktifs')
                ->join('master_makanans', 'menu_aktifs.master_makanan_id', '=', 'master_makanans.id')
                ->where('menu_aktifs.status', 'aktif')
                ->where('master_makanans.unit_bisnis_id', 1)
                ->pluck('master_makanans.nama_makanan');

            foreach ($menuAktifs as $makanan) {
                // Konversi ke format penulisan yang biasa muncul (uppercase/camelcase dll)
                $browser->assertSee($makanan);
            }
            
            $browser->pause(3000);
        });
    }

    /**
     * TC2: Klik galeri aktivitas donasi lalu klik gambar hingga muncul di tab baru
     */
    public function test_TC2_buka_galeri_aktivitas_donasi(): void
    {
        $this->browse(function (Browser $browser) {
            $this->pastikanLogin($browser, '/user/unit-bisnis/1');

            $this->injectVisualCursor($browser);

            // Scroll sedikit agar galeri aktivitas donasi terlihat di tengah layar
            $browser->script("window.scrollTo({top: 400, behavior: 'smooth'});");
            $browser->pause(2000);

            // Klik salah satu gambar bukti donasi (thumbnail)
            $this->moveAndClick($browser, 'img[alt^="Bukti Donasi"]');
            
            // Tunggu modal pop-up muncul
            $browser->pause(2500);

            // Klik gambar bukti donasi lengkap di dalam modal (memiliki target _blank)
            $this->moveAndClick($browser, 'img[alt^="Bukti Donasi Lengkap"]');
            
            // Jeda sebentar untuk membiarkan tab baru terbuka
            $browser->pause(4000);
            
            // Dapatkan semua tab yang terbuka
            $windowHandles = $browser->driver->getWindowHandles();
            if (count($windowHandles) > 1) {
                // Beralih ke tab baru
                $browser->driver->switchTo()->window($windowHandles[1]);
                $browser->pause(2000);
                // Tutup tab baru
                $browser->driver->close();
                // Kembali ke tab utama
                $browser->driver->switchTo()->window($windowHandles[0]);
            } else {
                $browser->pause(2000);
            }
            
            // Refresh halaman untuk membersihkan sisa modal yang terbuka sebelum lanjut ke test berikutnya
            $browser->refresh()->pause(1000);
        });
    }

    /**
     * TC3: Scroll down ke makanan tersedia, pilih menu Ayam Panggang, klik Ambil
     */
    public function test_TC3_ambil_makanan_ayam_panggang(): void
    {
        $this->browse(function (Browser $browser) {
            $this->pastikanLogin($browser, '/user/unit-bisnis/1');

            $this->injectVisualCursor($browser);

            // Scroll down perlahan ke area Makanan yang Tersedia Saat Ini
            $browser->script("window.scrollTo({top: document.body.scrollHeight / 2, behavior: 'smooth'});");
            $browser->pause(1500);
            $browser->script("window.scrollTo({top: document.body.scrollHeight, behavior: 'smooth'});");
            $browser->pause(2500);

            // Identifikasi tombol Ambil khusus untuk Ayam Panggang (menu_aktif ID 1)
            // Selector menggunakan atribut href yang berisi ID spesifik
            $this->moveAndClick($browser, 'a[href*="/user/makanan/1"]');

            // Verifikasi bahwa kita berhasil diarahkan kembali ke halaman detail ayam panggang
            $browser->waitForLocation('/user/makanan/1', 10)
                    ->pause(3000)
                    ->assertSee('AYAM PANGGANG');
        });
    }
}