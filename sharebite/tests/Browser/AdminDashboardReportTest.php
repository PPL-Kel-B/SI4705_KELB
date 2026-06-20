<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Facades\DB;

/**
 * Helper to get user-defined pause duration for slow-motion demo.
 * Default is 1500ms so actions are clearly visible during presentation.
 */
if (! function_exists('duskDelay')) {
    function duskDelay(): int {
        return (int) env('DUSK_PAUSE_MS', 1500);
    }
}

class AdminDashboardReportTest extends DuskTestCase
{
    // Menggunakan DatabaseMigrations agar setiap kali test dijalankan,
    // database testing (sharebite_dusk) di-reset dan data dummy dibuat ulang.
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        // 1. Jalankan seeder bawaan (untuk User Admin, Komunitas, Unit Bisnis)
        $this->artisan('db:seed');

        // 2. Insert dummy profile & pesanans untuk menguji Paginasi dan Filter (TC-DT-03 & TC-DT-02)
        // Kita matikan sementara FOREIGN_KEY_CHECKS agar insert data pesanan lebih mudah
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Buat dummy unit bisnis profile untuk user ID 2 (Lestari Food)
        DB::table('unit_bisnis_profiles')->insertOrIgnore([
            'id' => 999,
            'user_id' => 2,
            'nama_usaha' => 'Lestari Food',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Buat dummy master makanan & menu aktif agar relasi valid
        DB::table('master_makanans')->insertOrIgnore([
            'id' => 999,
            'unit_bisnis_id' => 999,
            'nama_makanan' => 'Nasi Goreng Spesial',
            'kategori' => 'Makanan Utama',
            'created_at' => now(),
        ]);

        DB::table('menu_aktifs')->insertOrIgnore([
            'id' => 999,
            'master_makanan_id' => 999,
            'unit_bisnis_id' => 999,
            'created_at' => now(),
        ]);

        // Buat 12 dummy transaksi (pesanan) agar paginasi > 10 data aktif (halaman 1 dan 2)
        for ($i = 1; $i <= 12; $i++) {
            DB::table('pesanans')->insert([
                'menu_aktif_id' => 999,
                'unit_bisnis_id' => 999,
                'user_id' => 3, // Komunitas Berbagi
                'jumlah_porsi' => 1,
                'total_harga' => 0,
                // Berikan 1 transaksi status batal agar bisa difilter di TC-DT-02
                'status' => ($i == 1) ? 'dibatalkan' : 'selesai',
                'kode_unik' => 'DUMMY_TRX_' . $i,
                'waktu_pesan' => now()->subMinutes($i),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Helper untuk login sebagai admin.
     */
    protected function loginAdmin(Browser $browser): void
    {
        $browser->visit('/login');

        // Jika setelah visit /login malah diredirect ke dashboard (berarti masih login dari test sebelumnya)
        if (str_contains($browser->driver->getCurrentURL(), '/admin/dashboard')) {
            return;
        }

        $browser->type('email', 'faridzaridzaridzarid@gmail.com')
            ->pause(duskDelay())
            ->type('password', 'Admin@2024!')
            ->pause(duskDelay())
            ->waitUntilEnabled('#loginBtn')
            ->press('#loginBtn')
            ->waitForLocation('/admin/dashboard')
            ->pause(duskDelay());
    }

    /* -------------------------------------------------------------------------
     * 1. Test Case Dashboard (TC-DS)
     * ------------------------------------------------------------------------- */

    /**
     * TC-DS-01: Fungsionalitas Pencarian Global
     */
    public function test_TC_DS_01_FungsionalitasPencarianGlobal(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAdmin($browser);

            $browser->type('search', 'Lestari')
                ->pause(duskDelay())
                ->waitForReload(function (Browser $browser) {
                    $browser->press('Filter Data');
                })
                ->pause(duskDelay())
                ->assertQueryStringHas('search', 'Lestari')
                ->pause(duskDelay());
        });
    }

    /**
     * TC-DS-02: Filter Rentang Waktu & Kategori Entitas
     */
    public function test_TC_DS_02_FilterRentangWaktuKategoriEntitas(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAdmin($browser);

            $browser->select('rentang_waktu', 'bulan_ini')
                ->pause(duskDelay())
                ->select('kategori_entitas', 'komunitas')
                ->pause(duskDelay())
                ->waitForReload(function (Browser $browser) {
                    $browser->press('Filter Data');
                })
                ->pause(duskDelay())
                ->assertSelected('rentang_waktu', 'bulan_ini')
                ->assertSelected('kategori_entitas', 'komunitas')
                ->pause(duskDelay());
        });
    }

    /**
     * TC-DS-03: Edge Case: Filter Tidak Relevan
     */
    public function test_TC_DS_03_EdgeCaseFilterTidakRelevan(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAdmin($browser);

            // Ubah kategori menjadi Unit Bisnis yang tidak memiliki keterkaitan metrik Total Komunitas
            $browser->select('kategori_entitas', 'unit_bisnis')
                ->waitForReload(function (Browser $browser) {
                    $browser->press('Filter Data');
                });

            // Metrik Total Komunitas seharusnya menampilkan '-' karena di-hide/kosong
            $text = $browser->script('return document.querySelector(".grid > div:nth-child(2) h3").innerText;')[0];
            $this->assertEquals('-', trim($text));
        });
    }

    /* -------------------------------------------------------------------------
     * 2. Test Case Generate Laporan (TC-GL)
     * ------------------------------------------------------------------------- */

    /**
     * TC-GL-01: Integrasi Parameter Filter dari Dashboard
     */
    public function test_TC_GL_01_IntegrasiParameterFilterDariDashboard(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAdmin($browser);

            $browser->select('rentang_waktu', 'bulan_ini')
                ->select('kategori_entitas', 'komunitas')
                ->waitForReload(function (Browser $browser) {
                    $browser->press('Filter Data');
                });

            $browser->waitForReload(function (Browser $browser) {
                $browser->clickLink('Generate Laporan');
            })
                ->assertPathBeginsWith('/admin/laporan')
                ->assertSee('FILTER AKTIF:')
                ->assertSee('KOMUNITAS')
                ->assertSee('BULAN INI');
        });
    }

    /**
     * TC-GL-02: Penanganan Laporan Kosong (Edge Case)
     */
    public function test_TC_GL_02_PenangananLaporanKosongEdgeCase(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAdmin($browser);

            // Pilih bulan lalu agar tidak ada data riwayat (dummy data semua di bulan ini)
            $browser->select('rentang_waktu', 'bulan_lalu')
                ->waitForReload(function (Browser $browser) {
                    $browser->press('Filter Data');
                });

            $browser->waitForReload(function (Browser $browser) {
                $browser->clickLink('Generate Laporan');
            })
                ->assertPathBeginsWith('/admin/laporan')
                ->assertSee('Belum ada transaksi di periode ini.');
        });
    }

    /* -------------------------------------------------------------------------
     * 3. Test Case Daftar Transaksi (TC-DT)
     * ------------------------------------------------------------------------- */

    /**
     * TC-DT-01: Navigasi dari Dashboard
     */
    public function test_TC_DT_01_NavigasiDariDashboard(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAdmin($browser);

            // Klik 'Lihat Semua' pada tabel Transaksi Terbaru
            $browser->waitForReload(function (Browser $browser) {
                $browser->clickLink('Lihat Semua');
            })
                ->assertPathIs('/admin/transaksi')
                ->assertSee('Daftar Transaksi');
        });
    }

    /**
     * TC-DT-02: Pencarian & Filter Kombinasi
     */
    public function test_TC_DT_02_PencarianDanFilterKombinasi(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAdmin($browser);
            $browser->visit('/admin/transaksi');

            // Ketik nama mitra (Lestari) dan pilih status (Batal)
            $browser->type('search', 'Lestari')
                ->select('status', 'dibatalkan')
                ->waitForReload(function (Browser $browser) {
                    $browser->press('Filter');
                })
                ->assertQueryStringHas('search', 'Lestari')
                ->assertQueryStringHas('status', 'dibatalkan');
        });
    }

    /**
     * TC-DT-03: Navigasi Nomor Page
     */
    public function test_TC_DT_03_NavigasiNomorPage(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAdmin($browser);
            $browser->visit('/admin/transaksi');

            // Karena kita inject 12 data dummy, akan ada halaman ke-2 (karena max 10 per page)
            // Klik menggunakan script JS agar tidak terkena error element not interactable (hidden tailwind mobile pagination)
            $browser->waitForReload(function (Browser $browser) {
                $browser->script("
                    const link = document.querySelector('.sm\\\\:flex-1 a[href*=\"page=2\"]') || document.querySelector('a[href*=\"page=2\"]');
                    if(link) link.click();
                ");
            })
                ->assertQueryStringHas('page', '2');
        });
    }

    /**
     * TC-DT-04: Edge Case: Data Tidak Ada
     */
    public function test_TC_DT_04_EdgeCaseDataTidakAda(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAdmin($browser);
            $browser->visit('/admin/transaksi');

            // Ketik kata pencarian acak/fiktif
            $browser->type('search', 'KATA_KUNCI_FIKTIF_TIDAK_ADA_123')
                ->waitForReload(function (Browser $browser) {
                    $browser->press('Filter');
                })
                ->assertSee('Tidak ada transaksi ditemukan.');
        });
    }

    /**
     * TC-DT-05: Tombol Reset & Kembali
     */
    public function test_TC_DT_05_TombolResetDanKembali(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAdmin($browser);
            $browser->visit('/admin/transaksi');

            // Set filter sembarang
            $browser->type('search', 'Sembarang')
                ->waitForReload(function (Browser $browser) {
                    $browser->press('Filter');
                });

            // Klik tombol Reset
            $browser->waitForReload(function (Browser $browser) {
                $browser->clickLink('Reset');
            })
                ->assertInputValue('search', '');

            // Klik Kembali ke Dashboard
            $browser->waitForReload(function (Browser $browser) {
                $browser->clickLink('Kembali ke Dashboard');
            })
                ->assertPathIs('/admin/dashboard');
        });
    }
}
