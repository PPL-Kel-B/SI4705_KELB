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
                'name'     => 'Ruang Duduk',
                'password' => bcrypt('password'),
                'role'     => 'unit_bisnis',
                'no_hp'    => '+6282178830750',
            ]
        );
        $unitUser->update(['name' => 'Ruang Duduk']);

        $unitProfile = UnitBisnisProfile::firstOrCreate(
            ['user_id' => $unitUser->id],
            [
                'nama_usaha'           => 'Ruang Duduk',
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
        $unitProfile->update(['nama_usaha' => 'Ruang Duduk']);

        // Ambil atau buat user volunteer (komunitas berbagi)
        $volunteer = User::firstOrCreate(
            ['email' => 'komunitas@sharebite.com'],
            [
                'name'     => 'Komunitas Berbagi',
                'password' => bcrypt('password'),
                'role'     => 'komunitas',
                'no_hp'    => '+628123456781',
            ]
        );
        $volunteer->update([
            'name' => 'Komunitas Berbagi',
            'role' => 'komunitas'
        ]);

        // Ambil atau buat master makanan dan menu aktif
        $existingMaster = \App\Models\MasterMakanan::where('nama_makanan', 'burgir')->first();

        $master = \App\Models\MasterMakanan::firstOrCreate(
            [
                'unit_bisnis_id' => $unitProfile->id,
                'nama_makanan'   => 'burgir',
            ],
            [
                'kategori'  => $existingMaster ? $existingMaster->kategori : 'Makanan Berat',
                'harga'     => 20000,
                'berat'     => $existingMaster ? $existingMaster->berat : 0.3,
                'deskripsi' => $existingMaster ? $existingMaster->deskripsi : 'Burger lezat.',
            ]
        );
        $master->update(['harga' => 20000]);

        $menu = MenuAktif::firstOrCreate(
            [
                'master_makanan_id' => $master->id,
                'unit_bisnis_id'    => $unitProfile->id,
            ],
            [
                'is_gratis'         => false,
                'harga_jual'        => 20000,
                'stok_porsi'        => 10,
                'batas_pengambilan' => now()->addHours(5),
                'status'            => 'aktif',
            ]
        );

        // Pastikan batas pengambilan selalu di masa depan dan harga ter-update
        $menu->update([
            'harga_jual'        => 20000,
            'status'            => 'aktif',
            'batas_pengambilan' => now()->addHours(5)
        ]);

        // Hapus pesanan-pesanan lama unit bisnis ini agar pengujian tab filter bersih
        Pesanan::where('unit_bisnis_id', $unitProfile->id)->delete();
        Pesanan::whereIn('kode_unik', ['SB-111-AAA', 'SB-222-BBB', 'SB-333-CCC'])->delete();

        // 1. Pesanan Menunggu Diambil (status: dibayar)
        $p1 = Pesanan::create([
            'menu_aktif_id'  => $menu->id,
            'unit_bisnis_id' => $unitProfile->id,
            'user_id'        => $volunteer->id,
            'jumlah_porsi'   => 1,
            'total_harga'    => 20000,
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
            'total_harga'    => 40000,
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
            'total_harga'    => 60000,
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

    // ═════════════════════════════════════════════════════════════
    // TEST 1 — Menu Sidebar 'Pesanan' menampilkan halaman pesanan
    // ═════════════════════════════════════════════════════════════

    public function testSidebarPesananLink(): void
    {
        $this->browse(function (Browser $browser) {
            $this->closeExtraTabs($browser);
            $user = User::where('email', 'unit@sharebite.com')->firstOrFail();
            $browser->loginAs($user)
                    ->visit('/unit/dashboard')
                    ->pause(2000)
                    ->waitForText('Pesanan', 10)
                    ->pause(2000)
                    ->clickLink('Pesanan')
                    ->pause(2000)
                    ->waitForLocation('/unit/pesanan', 10)
                    ->pause(2000)
                    ->assertPathIs('/unit/pesanan')
                    ->assertSee('Pesanan Masuk')
                    ->pause(2000);
        });
    }

    // ═════════════════════════════════════════════════════════════
    // TEST 2 — Pengujian Filter Tabs (Terbaru, Menunggu, Selesai)
    // ═════════════════════════════════════════════════════════════

    public function testFilterTabs(): void
    {
        $this->browse(function (Browser $browser) {
            $this->closeExtraTabs($browser);
            $user = User::where('email', 'unit@sharebite.com')->firstOrFail();
            $browser->loginAs($user)
                    ->visit('/unit/pesanan')
                    ->pause(2000)
                    ->waitForText('Daftar Pesanan Aktif', 10)
                    ->pause(2000);

            // 1. Klik Terbaru -> Harus ada teks "MENUNGGU DIAMBIL", "SELESAI", dan "TIDAK DIAMBIL"
            $browser->click('#tab-terbaru')
                    ->pause(2000)
                    ->assertSee('MENUNGGU DIAMBIL')
                    ->assertSee('SELESAI')
                    ->assertSee('TIDAK DIAMBIL')
                    ->pause(2000);

            // 2. Klik Menunggu -> Harus ada "MENUNGGU DIAMBIL", tapi tidak ada "SELESAI" dan "TIDAK DIAMBIL"
            $browser->click('#tab-menunggu')
                    ->pause(2000)
                    ->assertSee('MENUNGGU DIAMBIL')
                    ->assertDontSee('SELESAI')
                    ->assertDontSee('TIDAK DIAMBIL')
                    ->pause(2000);

            // 3. Klik Selesai -> Harus ada "SELESAI" dan "TIDAK DIAMBIL", tapi tidak ada "MENUNGGU DIAMBIL"
            $browser->click('#tab-selesai')
                    ->pause(2000)
                    ->assertSee('SELESAI')
                    ->assertSee('TIDAK DIAMBIL')
                    ->assertDontSee('MENUNGGU DIAMBIL')
                    ->pause(2000);
        });
    }

    // ═════════════════════════════════════════════════════════════
    // TEST 3 — Klik Detail Pesanan dan tombol Back
    // ═════════════════════════════════════════════════════════════

    public function testDetailPesananAndBackButton(): void
    {
        $this->browse(function (Browser $browser) {
            $this->closeExtraTabs($browser);
            $user = User::where('email', 'unit@sharebite.com')->firstOrFail();
            $browser->loginAs($user)
                    ->visit('/unit/pesanan')
                    ->pause(2000)
                    ->waitForText('Daftar Pesanan Aktif', 10)
                    ->pause(2000);

            // Klik Detail Pesanan pada kartu pesanan yang menunggu diambil
            $browser->clickLink('Detail Pesanan')
                    ->pause(2500)
                    ->assertPathContains('/unit/pesanan/')
                    ->assertSee('INFORMASI PEMBELI / RELAWAN')
                    ->pause(2000);

            // Klik tombol Back (ikon panah kiri)
            $browser->click('a[href*="unit/pesanan"]')
                    ->pause(2000)
                    ->waitForLocation('/unit/pesanan', 10)
                    ->pause(2000)
                    ->assertPathIs('/unit/pesanan')
                    ->pause(2000);
        });
    }

    // ═════════════════════════════════════════════════════════════
    // TEST 4 — Baca Selengkapnya Panduan Pengambilan dan tombol Back
    // ═════════════════════════════════════════════════════════════

    public function testPanduanPengambilanAndBackButton(): void
    {
        $this->browse(function (Browser $browser) {
            $this->closeExtraTabs($browser);
            $user = User::where('email', 'unit@sharebite.com')->firstOrFail();
            $browser->loginAs($user)
                    ->visit('/unit/pesanan')
                    ->pause(2000)
                    ->waitForText('Panduan Pengambilan', 10)
                    ->pause(2000)
                    ->clickLink('BACA SELENGKAPNYA')
                    ->pause(2000)
                    ->waitForLocation('/unit/pesanan/panduan', 10)
                    ->pause(2000)
                    ->assertPathIs('/unit/pesanan/panduan')
                    ->assertSee('Aturan Utama')
                    ->pause(2000)
                    ->clickLink('Selesai Membaca')
                    ->pause(2000)
                    ->waitForLocation('/unit/pesanan', 10)
                    ->pause(2000)
                    ->assertPathIs('/unit/pesanan')
                    ->pause(2000);
        });
    }

    // ═════════════════════════════════════════════════════════════
    // TEST 5 — Tombol Hubungi CS mengarahkan ke Chat Admin
    // ═════════════════════════════════════════════════════════════

    public function testHubungiCsLink(): void
    {
        $this->browse(function (Browser $browser) {
            $this->closeExtraTabs($browser);
            $user = User::where('email', 'unit@sharebite.com')->firstOrFail();
            $browser->loginAs($user)
                    ->visit('/unit/pesanan')
                    ->pause(2000)
                    ->waitForText('Butuh Bantuan?', 10)
                    ->pause(2000)
                    ->clickLink('Hubungi CS')
                    ->pause(2000)
                    ->waitForLocation('/unit/chat', 10)
                    ->pause(2000)
                    ->assertPathIs('/unit/chat')
                    ->pause(2000);
        });
    }

    // ═════════════════════════════════════════════════════════════
    // TEST 6 — Pencarian pesanan lewat Search Bar
    // ═════════════════════════════════════════════════════════════

    public function testSearchPesanan(): void
    {
        $this->browse(function (Browser $browser) {
            $this->closeExtraTabs($browser);
            $user = User::where('email', 'unit@sharebite.com')->firstOrFail();
            $browser->loginAs($user)
                    ->visit('/unit/pesanan')
                    ->pause(2000)
                    ->waitForText('Daftar Pesanan Aktif', 10)
                    ->pause(2000);

            // Cari kata kunci yang benar
            $browser->type('#search-pesanan', 'burgir')
                    ->pause(2000)
                    ->assertSee('burgir')
                    ->assertDontSee('Pesanan Tidak Ditemukan')
                    ->pause(2000);

            // Cari kata kunci yang salah
            $browser->type('#search-pesanan', 'makanan_tidak_ada_123')
                    ->pause(2000)
                    ->assertSee('Pesanan Tidak Ditemukan')
                    ->pause(2000);
        });
    }

    // ═════════════════════════════════════════════════════════════
    // TEST 7 — Tombol Verifikasi Code mengarahkan ke halaman verifikasi
    // ═════════════════════════════════════════════════════════════

    public function testVerifikasiCodeButton(): void
    {
        $this->browse(function (Browser $browser) {
            $this->closeExtraTabs($browser);
            $user = User::where('email', 'unit@sharebite.com')->firstOrFail();
            $browser->loginAs($user)
                    ->visit('/unit/pesanan')
                    ->pause(2000)
                    ->clickLink('Verifikasi Code')
                    ->pause(2000)
                    ->waitForLocation('/unit/pesanan/verifikasi', 10)
                    ->pause(2000)
                    ->assertPathIs('/unit/pesanan/verifikasi')
                    ->assertSee('INPUT 6-DIGIT KODE VERIFIKASI')
                    ->pause(2000);
        });
    }

    // ═════════════════════════════════════════════════════════════
    // TEST 8 — Pengisian Code Salah (Muncul SweetAlert Popup)
    // ═════════════════════════════════════════════════════════════

    public function testVerifikasiCodeInvalid(): void
    {
        $this->browse(function (Browser $browser) {
            $this->closeExtraTabs($browser);
            $user = User::where('email', 'unit@sharebite.com')->firstOrFail();
            $browser->loginAs($user)
                    ->visit('/unit/pesanan/verifikasi')
                    ->pause(2000)
                    ->waitForText('INPUT 6-DIGIT KODE VERIFIKASI', 10)
                    ->pause(2000);

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
            $browser->pause(2000);
            
            $browser->click('button[onclick="submitCode()"]')
                    ->pause(2000)
                    ->waitForText('Verifikasi Gagal', 10)
                    ->pause(2000)
                    ->assertSee('Kode salah atau pesanan sudah diambil!')
                    ->pause(2000)
                    ->click('.swal2-confirm')
                    ->pause(2000);
        });
    }

    // ═════════════════════════════════════════════════════════════
    // TEST 9 — Pengisian Code Benar & Selesaikan Pesanan (Redirect ke Riwayat)
    // ═════════════════════════════════════════════════════════════

    public function testVerifikasiCodeValidAndComplete(): void
    {
        $this->browse(function (Browser $browser) {
            $this->closeExtraTabs($browser);
            $user = User::where('email', 'unit@sharebite.com')->firstOrFail();
            $pesanan = Pesanan::where('kode_unik', 'SB-111-AAA')->firstOrFail();

            $browser->loginAs($user)
                    ->visit('/unit/pesanan/verifikasi')
                    ->pause(2000)
                    ->waitForText('INPUT 6-DIGIT KODE VERIFIKASI', 10)
                    ->pause(2000);

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
            $browser->pause(2000);

            $browser->click('button[onclick="submitCode()"]')
                    ->pause(2000)
                    ->waitForText('Pesanan Ditemukan', 10)
                    ->pause(2000)
                    ->assertSee('Verifikasi & Selesaikan Pesanan')
                    ->pause(2000);

            // Klik tombol "Verifikasi & Selesaikan Pesanan"
            $browser->press('Verifikasi & Selesaikan Pesanan')
                    ->pause(2000)
                    ->waitForLocation('/unit/riwayat/' . $pesanan->id, 10)
                    ->pause(2000)
                    ->assertPathIs('/unit/riwayat/' . $pesanan->id)
                    ->assertSee('Pesanan berhasil diserahkan!')
                    ->pause(2000);
        });
    }
}
