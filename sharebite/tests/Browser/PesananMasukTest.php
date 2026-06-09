<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\UnitBisnisProfile;
use App\Models\Pesanan;
use App\Models\Pembayaran;
use App\Models\MenuAktif;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Facades\DB;

class PesananMasukTest extends DuskTestCase
{
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

        // Ambil atau buat user unit bisnis
        $unitUser = User::firstOrCreate(
            ['email' => 'unit@sharebite.com'],
            [
                'name'     => 'Lestari Food',
                'password' => bcrypt('password'),
                'role'     => 'unit_bisnis',
                'no_hp'    => '+6282178830750',
            ]
        );

        $unitProfile = UnitBisnisProfile::firstOrCreate(
            ['user_id' => $unitUser->id],
            [
                'nama_usaha'           => 'Lestari Food',
                'jenis_usaha'          => 'Restoran',
                'foto_bisnis'          => 'images/placeholder-bisnis.jpg',
                'lokasi_lat'           => '-6.9271',
                'lokasi_lng'           => '107.6411',
                'radius_penjemputan'   => 15,
                'jam_buka'             => '08:00',
                'jam_tutup'            => '21:00',
                'verified'             => true,
                'status_verifikasi'    => 'terverifikasi',
                'tahun_bergabung'      => 2023,
            ]
        );

        // Ambil atau buat user volunteer
        $volunteer = User::firstOrCreate(
            ['email' => 'individu@sharebite.com'],
            [
                'name'     => 'Volunteer ShareBite',
                'password' => bcrypt('password'),
                'role'     => 'individu',
                'no_hp'    => '+628123456789',
            ]
        );

        // Ambil atau buat master makanan dan menu aktif
        $master = \App\Models\MasterMakanan::firstOrCreate(
            [
                'unit_bisnis_id' => $unitProfile->id,
                'nama_makanan'   => 'eskrim',
            ],
            [
                'kategori'  => 'Cemilan / Makanan Ringan',
                'harga'     => 15000,
                'berat'     => 0.2,
                'deskripsi' => 'Eskrim lezat rasa vanilla.',
            ]
        );

        $menu = MenuAktif::firstOrCreate(
            [
                'master_makanan_id' => $master->id,
                'unit_bisnis_id'    => $unitProfile->id,
            ],
            [
                'is_gratis'         => false,
                'harga_jual'        => 10000,
                'stok_porsi'        => 10,
                'batas_pengambilan' => now()->addHours(5),
                'status'            => 'aktif',
            ]
        );

        // Pastikan batas pengambilan selalu di masa depan
        $menu->update(['batas_pengambilan' => now()->addHours(5)]);

        // Hapus pesanan-pesanan lama unit bisnis ini agar pengujian tab filter bersih
        Pesanan::where('unit_bisnis_id', $unitProfile->id)->delete();
        Pesanan::whereIn('kode_unik', ['SB-111-AAA', 'SB-222-BBB', 'SB-333-CCC'])->delete();

        // 1. Pesanan Menunggu Diambil (status: dibayar)
        $p1 = Pesanan::create([
            'menu_aktif_id'  => $menu->id,
            'unit_bisnis_id' => $unitProfile->id,
            'user_id'        => $volunteer->id,
            'jumlah_porsi'   => 1,
            'total_harga'    => 10000,
            'status'         => 'dibayar',
            'kode_unik'      => 'SB-111-AAA',
            'waktu_pesan'    => now(),
        ]);
        Pembayaran::create([
            'pesanan_id' => $p1->id,
            'status'     => 'berhasil',
            'qrcode'     => 'SB-QR-1',
            'waktu_bayar' => now(),
        ]);

        // 2. Pesanan Selesai (status: selesai)
        $p2 = Pesanan::create([
            'menu_aktif_id'  => $menu->id,
            'unit_bisnis_id' => $unitProfile->id,
            'user_id'        => $volunteer->id,
            'jumlah_porsi'   => 2,
            'total_harga'    => 20000,
            'status'         => 'selesai',
            'kode_unik'      => 'SB-222-BBB',
            'waktu_pesan'    => now()->subHour(),
        ]);
        Pembayaran::create([
            'pesanan_id' => $p2->id,
            'status'     => 'berhasil',
            'qrcode'     => 'SB-QR-2',
            'waktu_bayar' => now()->subHour(),
        ]);

        // 3. Pesanan Tidak Diambil (status: dibatalkan, pembayaran: berhasil)
        $p3 = Pesanan::create([
            'menu_aktif_id'  => $menu->id,
            'unit_bisnis_id' => $unitProfile->id,
            'user_id'        => $volunteer->id,
            'jumlah_porsi'   => 3,
            'total_harga'    => 30000,
            'status'         => 'dibatalkan',
            'kode_unik'      => 'SB-333-CCC',
            'waktu_pesan'    => now()->subHours(2),
        ]);
        Pembayaran::create([
            'pesanan_id' => $p3->id,
            'status'     => 'berhasil',
            'qrcode'     => 'SB-QR-3',
            'waktu_bayar' => now()->subHours(2),
        ]);
    }

    // ═════════════════════════════════════════════════════════════
    // TEST 1 — Menu Sidebar 'Pesanan' menampilkan halaman pesanan
    // ═════════════════════════════════════════════════════════════

    public function testSidebarPesananLink(): void
    {
        $this->browse(function (Browser $browser) {
            $user = User::where('email', 'unit@sharebite.com')->firstOrFail();
            $browser->loginAs($user)
                    ->visit('/unit/dashboard')
                    ->waitForText('Pesanan', 10)
                    ->clickLink('Pesanan')
                    ->waitForLocation('/unit/pesanan', 10)
                    ->assertPathIs('/unit/pesanan')
                    ->assertSee('Pesanan Masuk');
        });
    }

    // ═════════════════════════════════════════════════════════════
    // TEST 2 — Pengujian Filter Tabs (Terbaru, Menunggu, Selesai)
    // ═════════════════════════════════════════════════════════════

    public function testFilterTabs(): void
    {
        $this->browse(function (Browser $browser) {
            $user = User::where('email', 'unit@sharebite.com')->firstOrFail();
            $browser->loginAs($user)
                    ->visit('/unit/pesanan')
                    ->waitForText('Daftar Pesanan Aktif', 10);

            // 1. Klik Terbaru -> Harus ada teks "MENUNGGU DIAMBIL", "SELESAI", dan "TIDAK DIAMBIL"
            $browser->click('#tab-terbaru')
                    ->pause(800)
                    ->assertSee('MENUNGGU DIAMBIL')
                    ->assertSee('SELESAI')
                    ->assertSee('TIDAK DIAMBIL');

            // 2. Klik Menunggu -> Harus ada "MENUNGGU DIAMBIL", tapi tidak ada "SELESAI" dan "TIDAK DIAMBIL"
            $browser->click('#tab-menunggu')
                    ->pause(800)
                    ->assertSee('MENUNGGU DIAMBIL')
                    ->assertDontSee('SELESAI')
                    ->assertDontSee('TIDAK DIAMBIL');

            // 3. Klik Selesai -> Harus ada "SELESAI" dan "TIDAK DIAMBIL", tapi tidak ada "MENUNGGU DIAMBIL"
            $browser->click('#tab-selesai')
                    ->pause(800)
                    ->assertSee('SELESAI')
                    ->assertSee('TIDAK DIAMBIL')
                    ->assertDontSee('MENUNGGU DIAMBIL');
        });
    }

    // ═════════════════════════════════════════════════════════════
    // TEST 3 — Klik Detail Pesanan dan tombol Back
    // ═════════════════════════════════════════════════════════════

    public function testDetailPesananAndBackButton(): void
    {
        $this->browse(function (Browser $browser) {
            $user = User::where('email', 'unit@sharebite.com')->firstOrFail();
            $browser->loginAs($user)
                    ->visit('/unit/pesanan')
                    ->waitForText('Daftar Pesanan Aktif', 10);

            // Klik Detail Pesanan pada kartu pesanan yang menunggu diambil
            $browser->clickLink('Detail Pesanan')
                    ->pause(1500)
                    ->assertPathContains('/unit/pesanan/')
                    ->assertSee('INFORMASI PEMBELI / RELAWAN');

            // Klik tombol Back (ikon panah kiri)
            $browser->click('a[href*="unit/pesanan"]')
                    ->waitForLocation('/unit/pesanan', 10)
                    ->assertPathIs('/unit/pesanan');
        });
    }

    // ═════════════════════════════════════════════════════════════
    // TEST 4 — Baca Selengkapnya Panduan Pengambilan dan tombol Back
    // ═════════════════════════════════════════════════════════════

    public function testPanduanPengambilanAndBackButton(): void
    {
        $this->browse(function (Browser $browser) {
            $user = User::where('email', 'unit@sharebite.com')->firstOrFail();
            $browser->loginAs($user)
                    ->visit('/unit/pesanan')
                    ->waitForText('Panduan Pengambilan', 10)
                    ->clickLink('BACA SELENGKAPNYA')
                    ->waitForLocation('/unit/pesanan/panduan', 10)
                    ->assertPathIs('/unit/pesanan/panduan')
                    ->assertSee('Aturan Utama')
                    ->clickLink('Selesai Membaca')
                    ->waitForLocation('/unit/pesanan', 10)
                    ->assertPathIs('/unit/pesanan');
        });
    }

    // ═════════════════════════════════════════════════════════════
    // TEST 5 — Tombol Hubungi CS mengarahkan ke Chat Admin
    // ═════════════════════════════════════════════════════════════

    public function testHubungiCsLink(): void
    {
        $this->browse(function (Browser $browser) {
            $user = User::where('email', 'unit@sharebite.com')->firstOrFail();
            $browser->loginAs($user)
                    ->visit('/unit/pesanan')
                    ->waitForText('Butuh Bantuan?', 10)
                    ->clickLink('Hubungi CS')
                    ->waitForLocation('/unit/chat', 10)
                    ->assertPathIs('/unit/chat');
        });
    }

    // ═════════════════════════════════════════════════════════════
    // TEST 6 — Pencarian pesanan lewat Search Bar
    // ═════════════════════════════════════════════════════════════

    public function testSearchPesanan(): void
    {
        $this->browse(function (Browser $browser) {
            $user = User::where('email', 'unit@sharebite.com')->firstOrFail();
            $browser->loginAs($user)
                    ->visit('/unit/pesanan')
                    ->waitForText('Daftar Pesanan Aktif', 10);

            // Cari kata kunci yang benar
            $browser->type('#search-pesanan', 'eskrim')
                    ->pause(800)
                    ->assertSee('eskrim')
                    ->assertDontSee('Pesanan Tidak Ditemukan');

            // Cari kata kunci yang salah
            $browser->type('#search-pesanan', 'makanan_tidak_ada_123')
                    ->pause(800)
                    ->assertSee('Pesanan Tidak Ditemukan');
        });
    }

    // ═════════════════════════════════════════════════════════════
    // TEST 7 — Tombol Verifikasi Code mengarahkan ke halaman verifikasi
    // ═════════════════════════════════════════════════════════════

    public function testVerifikasiCodeButton(): void
    {
        $this->browse(function (Browser $browser) {
            $user = User::where('email', 'unit@sharebite.com')->firstOrFail();
            $browser->loginAs($user)
                    ->visit('/unit/pesanan')
                    ->clickLink('Verifikasi Code')
                    ->waitForLocation('/unit/pesanan/verifikasi', 10)
                    ->assertPathIs('/unit/pesanan/verifikasi')
                    ->assertSee('INPUT 6-DIGIT KODE VERIFIKASI');
        });
    }

    // ═════════════════════════════════════════════════════════════
    // TEST 8 — Pengisian Code Salah (Muncul SweetAlert Popup)
    // ═════════════════════════════════════════════════════════════

    public function testVerifikasiCodeInvalid(): void
    {
        $this->browse(function (Browser $browser) {
            $user = User::where('email', 'unit@sharebite.com')->firstOrFail();
            $browser->loginAs($user)
                    ->visit('/unit/pesanan/verifikasi')
                    ->waitForText('INPUT 6-DIGIT KODE VERIFIKASI', 10);

            // Isi dengan kode salah
            $browser->script("
                const boxes = document.querySelectorAll('.code-box');
                boxes[0].value = '9';
                boxes[1].value = '9';
                boxes[2].value = '9';
                boxes[3].value = 'X';
                boxes[4].value = 'Y';
                boxes[5].value = 'Z';
            ");
            
            $browser->click('button[onclick="submitCode()"]')
                    ->waitForText('Verifikasi Gagal', 10)
                    ->assertSee('Kode salah atau pesanan sudah diambil!')
                    ->click('.swal2-confirm');
        });
    }

    // ═════════════════════════════════════════════════════════════
    // TEST 9 — Pengisian Code Benar & Selesaikan Pesanan (Redirect ke Riwayat)
    // ═════════════════════════════════════════════════════════════

    public function testVerifikasiCodeValidAndComplete(): void
    {
        $this->browse(function (Browser $browser) {
            $user = User::where('email', 'unit@sharebite.com')->firstOrFail();
            $pesanan = Pesanan::where('kode_unik', 'SB-111-AAA')->firstOrFail();

            $browser->loginAs($user)
                    ->visit('/unit/pesanan/verifikasi')
                    ->waitForText('INPUT 6-DIGIT KODE VERIFIKASI', 10);

            // Isi dengan kode benar '111-AAA' (dari SB-111-AAA)
            $browser->script("
                const boxes = document.querySelectorAll('.code-box');
                boxes[0].value = '1';
                boxes[1].value = '1';
                boxes[2].value = '1';
                boxes[3].value = 'A';
                boxes[4].value = 'A';
                boxes[5].value = 'A';
            ");

            $browser->click('button[onclick="submitCode()"]')
                    ->waitForText('Pesanan Ditemukan', 10)
                    ->assertSee('Verifikasi & Selesaikan Pesanan');

            // Klik tombol "Verifikasi & Selesaikan Pesanan"
            $browser->press('Verifikasi & Selesaikan Pesanan')
                    ->waitForLocation('/unit/riwayat/' . $pesanan->id, 10)
                    ->assertPathIs('/unit/riwayat/' . $pesanan->id)
                    ->assertSee('Pesanan berhasil diserahkan!');
        });
    }
}
