<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AdminDashboardReportTest extends DuskTestCase
{
    // Menggunakan DatabaseMigrations agar setiap kali test dijalankan,
    // database testing (sharebite_dusk) di-reset dan data dummy (seeder) dibuat ulang.
    // Hal ini membuat test bisa di-run berulang kali oleh siapa saja tanpa error.
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        // Jalankan seeder agar ada data transaksi yang bisa difilter & dicari
        $this->artisan('db:seed');
    }

    /**
     * Helper untuk login sebagai admin di awal tiap test.
     */
    protected function loginAdmin(Browser $browser): void
    {
        // Login ulang hanya jika user belum berada di dashboard
        $browser->visit('/login')
            ->type('email', 'admin@sharebite.com')
            ->type('password', 'Admin@2024!')
            ->waitUntilEnabled('#loginBtn')
            ->press('#loginBtn')
            ->waitForLocation('/admin/dashboard');
    }

    /**
     * TC-DS-01: Fungsionalitas Pencarian Global
     */
    public function test_TC_DS_01_FungsionalitasPencarianGlobal(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAdmin($browser);

            // Ketik kata kunci pada form pencarian dan klik Filter
            $browser->type('search', 'Lestari')
                ->waitForReload(function (Browser $browser) {
                    $browser->press('Filter Data'); // Tombol submit di dashboard
                })
                ->assertQueryStringHas('search', 'Lestari');
        });
    }

    /**
     * TC-DS-02: Filter Rentang Waktu & Kategori Entitas
     */
    public function test_TC_DS_02_FilterRentangWaktuKategoriEntitas(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAdmin($browser);

            // Pilih filter waktu dan kategori
            $browser->select('rentang_waktu', 'bulan_ini')
                ->select('kategori_entitas', 'komunitas')
                ->waitForReload(function (Browser $browser) {
                    $browser->press('Filter Data');
                })
                ->assertSelected('rentang_waktu', 'bulan_ini')
                ->assertSelected('kategori_entitas', 'komunitas');
        });
    }

    /**
     * TC-GL-01: Integrasi Parameter Filter dari Dashboard
     */
    public function test_TC_GL_01_IntegrasiParameterFilterDariDashboard(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAdmin($browser);

            // Set filter terlebih dahulu di dashboard
            $browser->select('rentang_waktu', 'bulan_ini')
                ->select('kategori_entitas', 'komunitas')
                ->waitForReload(function (Browser $browser) {
                    $browser->press('Filter Data');
                });

            // Klik tombol "Generate Laporan" dan pastikan filter terbawa
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

            // Filter ke rentang waktu "bulan_lalu" (dimana data transaksi belum ada)
            $browser->select('rentang_waktu', 'bulan_lalu')
                ->waitForReload(function (Browser $browser) {
                    $browser->press('Filter Data');
                });

            // Buka laporan, dan pastikan pesan informatif muncul
            $browser->waitForReload(function (Browser $browser) {
                $browser->clickLink('Generate Laporan');
            })
                ->assertPathBeginsWith('/admin/laporan')
                ->assertSee('Belum ada transaksi di periode ini.');
        });
    }

    /**
     * TC-DT-01: Pencarian & Filter Kombinasi di Daftar Transaksi
     */
    public function test_TC_DT_01_PencarianDanFilterKombinasi(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAdmin($browser);
            
            // Masuk ke halaman daftar transaksi
            $browser->visit('/admin/transaksi');

            // Ketik nama mitra dan pilih status
            $browser->type('search', 'Lestari')
                ->select('status', 'dibatalkan')
                ->waitForReload(function (Browser $browser) {
                    $browser->press('Filter'); // Tombol submit di daftar transaksi
                })
                ->assertQueryStringHas('search', 'Lestari')
                ->assertQueryStringHas('status', 'dibatalkan');
        });
    }

    /**
     * TC-DT-02: Edge Case Data Tidak Ada
     */
    public function test_TC_DT_02_EdgeCaseDataTidakAda(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAdmin($browser);
            $browser->visit('/admin/transaksi');

            // Ketik nama fiktif yang tidak mungkin ada
            $browser->type('search', 'KATA_KUNCI_FIKTIF_TIDAK_ADA_123')
                ->waitForReload(function (Browser $browser) {
                    $browser->press('Filter');
                })
                ->assertSee('Tidak ada transaksi ditemukan.');
        });
    }

    /**
     * TC-DT-03: Tombol Reset & Kembali
     */
    public function test_TC_DT_03_TombolResetDanKembali(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAdmin($browser);
            $browser->visit('/admin/transaksi');

            // Set filter sembarang
            $browser->type('search', 'Sembarang')
                ->waitForReload(function (Browser $browser) {
                    $browser->press('Filter');
                });

            // Uji tombol Reset (pastikan search jadi kosong)
            $browser->waitForReload(function (Browser $browser) {
                $browser->clickLink('Reset');
            })
                ->assertInputValue('search', '');

            // Uji tombol Kembali ke Dashboard (pastikan redirect benar)
            $browser->waitForReload(function (Browser $browser) {
                $browser->clickLink('Kembali ke Dashboard');
            })
                ->assertPathIs('/admin/dashboard');
        });
    }
}
