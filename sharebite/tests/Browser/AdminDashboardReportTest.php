<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AdminDashboardReportTest extends DuskTestCase
{
    /**
     * Helper untuk masuk sebagai admin
     */
    protected function loginAdmin(Browser $browser): void
    {
        $browser->visit('/login')
                ->type('email', 'admin@sharebite.com')
                ->type('password', 'Admin@2024!')
                ->waitUntilEnabled('#loginBtn')
                ->press('#loginBtn')
                ->waitForLocation('/admin/dashboard');
    }

    /**
     * 1. Test Case: Validasi Log Transaksi Terbaru dan Navigasi "Lihat Semua"
     */
    public function testValidationLogTransaksiDanNavigasi(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAdmin($browser);

            // Verifikasi kolom-kolom pada tabel Transaksi Terbaru (styled uppercase)
            $browser->assertSee('Transaksi Terbaru')
                    ->assertSee('MITRA PENYALUR')
                    ->assertSee('PENERIMA MANFAAT')
                    ->assertSee('ITEM MAKANAN')
                    ->assertSee('STATUS')
                    ->assertSee('WAKTU');

            // Klik link "Lihat Semua" dan tunggu halaman memuat
            $browser->waitForReload(function (Browser $browser) {
                $browser->clickLink('Lihat Semua');
            })
            ->assertPathIs('/admin/transaksi')
            ->assertSee('Daftar Transaksi'); // Pastikan halaman transaksi termuat
        });
    }

    /**
     * 2. Test Case: Fungsionalitas Tombol "Reset" Filter
     */
    public function testFungsionalitasTombolReset(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAdmin($browser);

            // Mengisi filter dan tunggu halaman memuat ulang
            $browser->type('search', 'Lestari')
                    ->select('rentang_waktu', 'bulan_ini')
                    ->select('kategori_entitas', 'komunitas')
                    ->waitForReload(function (Browser $browser) {
                        $browser->press('Filter Data');
                    })
                    ->assertQueryStringHas('search', 'Lestari')
                    ->assertQueryStringHas('rentang_waktu', 'bulan_ini')
                    ->assertQueryStringHas('kategori_entitas', 'komunitas');

            // Klik tombol Reset dan tunggu halaman memuat ulang
            $browser->waitForReload(function (Browser $browser) {
                $browser->clickLink('Reset');
            })
            ->assertPathIs('/admin/dashboard')
            ->assertQueryStringMissing('search')
            ->assertQueryStringMissing('rentang_waktu')
            ->assertQueryStringMissing('kategori_entitas')
            ->assertInputValue('search', '')
            ->assertSelected('rentang_waktu', 'semua_waktu')
            ->assertSelected('kategori_entitas', 'semua_kategori');
        });
    }

    /**
     * 3. Test Case: Sinkronisasi Filter Rentang Waktu dan Kategori Entitas
     */
    public function testSinkronisasiFilterRentangWaktuDanKategori(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAdmin($browser);

            // Terapkan filter rentang waktu dan kategori entitas dan tunggu halaman memuat ulang
            $browser->select('rentang_waktu', 'bulan_ini')
                    ->select('kategori_entitas', 'komunitas')
                    ->waitForReload(function (Browser $browser) {
                        $browser->press('Filter Data');
                    })
                    ->assertPathIs('/admin/dashboard')
                    ->assertSelected('rentang_waktu', 'bulan_ini')
                    ->assertSelected('kategori_entitas', 'komunitas');
        });
    }

    /**
     * 4. Test Case: Integrasi Filter ke Dokumen Laporan (Generate Laporan)
     */
    public function testIntegrasiFilterKeDokumenLaporan(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAdmin($browser);

            // Terapkan pencarian dan filter dan tunggu halaman memuat ulang
            $browser->type('search', 'Lestari')
                    ->select('rentang_waktu', 'bulan_ini')
                    ->select('kategori_entitas', 'komunitas')
                    ->waitForReload(function (Browser $browser) {
                        $browser->press('Filter Data');
                    });

            // Klik Generate Laporan dan tunggu halaman laporan memuat
            $browser->waitForReload(function (Browser $browser) {
                $browser->clickLink('Generate Laporan');
            })
            ->assertPathBeginsWith('/admin/laporan')
            ->assertQueryStringHas('search', 'Lestari')
            ->assertQueryStringHas('rentang_waktu', 'bulan_ini')
            ->assertQueryStringHas('kategori_entitas', 'komunitas')
            ->assertSee('Laporan Ringkasan Aktivitas')
            ->assertSee('FILTER AKTIF:')
            ->assertSee('ENTITAS: KOMUNITAS')
            ->assertSee('CARI: "LESTARI"');
        });
    }

    /**
     * 5. Test Case: Penanganan Data Kosong pada Laporan (Edge Case)
     */
    public function testPenangananDataKosongLaporan(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAdmin($browser);

            // Terapkan filter yang bernilai kosong (misalnya bulan_lalu yang tidak ada pesanan di seeder default) dan tunggu halaman memuat ulang
            $browser->select('rentang_waktu', 'bulan_lalu')
                    ->waitForReload(function (Browser $browser) {
                        $browser->press('Filter Data');
                    });

            // Klik Generate Laporan dan tunggu halaman laporan memuat
            $browser->waitForReload(function (Browser $browser) {
                $browser->clickLink('Generate Laporan');
            })
            ->assertPathBeginsWith('/admin/laporan')
            ->assertSee('Laporan Ringkasan Aktivitas')
            // Memastikan data kosong ditangani (Total Transaksi 0)
            ->assertSee('Belum ada transaksi di periode ini.');
        });
    }
}
