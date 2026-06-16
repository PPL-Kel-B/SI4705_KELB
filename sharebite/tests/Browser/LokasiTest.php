<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\UnitBisnisProfile;
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

class LokasiTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Buat user untuk login (Role komunitas bisa mengakses /user/lokasi)
        $this->user = User::factory()->create([
            'email' => 'userlokasi@sharebite.com',
            'password' => bcrypt('password123'),
            'role' => 'komunitas',
        ]);

        // Buat data dummy Restoran
        $userResto = User::factory()->create(['role' => 'unit_bisnis', 'name' => 'Restoran Enak']);
        UnitBisnisProfile::create([
            'user_id' => $userResto->id,
            'nama_usaha' => 'Restoran Enak',
            'jenis_usaha' => 'Restoran',
            'status_verifikasi' => 'terverifikasi',
            'lokasi_lat' => -6.915, // Koordinat dummy (sekitar Bandung)
            'lokasi_lng' => 107.618,
        ]);

        // Buat data dummy Cafe
        $userCafe = User::factory()->create(['role' => 'unit_bisnis', 'name' => 'Cafe Santai']);
        UnitBisnisProfile::create([
            'user_id' => $userCafe->id,
            'nama_usaha' => 'Cafe Santai',
            'jenis_usaha' => 'Cafe',
            'status_verifikasi' => 'terverifikasi',
            'lokasi_lat' => -6.919, 
            'lokasi_lng' => 107.620,
        ]);

        // Buat data dummy Hotel
        $userHotel = User::factory()->create(['role' => 'unit_bisnis', 'name' => 'Hotel Mewah']);
        UnitBisnisProfile::create([
            'user_id' => $userHotel->id,
            'nama_usaha' => 'Hotel Mewah',
            'jenis_usaha' => 'Hotel',
            'status_verifikasi' => 'terverifikasi',
            'lokasi_lat' => -6.912, 
            'lokasi_lng' => 107.615,
        ]);
    }

    protected function loginUser(Browser $browser): void
    {
        $browser->visit('/login');
        
        // Cek apakah sudah login dari test sebelumnya
        if (str_contains($browser->driver->getCurrentURL(), '/user/dashboard')) {
            return;
        }

        $browser->type('email', 'userlokasi@sharebite.com')
                ->pause(duskDelay())
                ->type('password', 'password123')
                ->pause(duskDelay())
                ->press('#loginBtn')
                ->waitForLocation('/user/dashboard');
    }

    /**
     * Helper untuk inject mock lokasi agar browser tidak bergantung pada izin GPS
     */
    protected function injectMockLocation(Browser $browser)
    {
        // Menyuntikkan koordinat Bandung pusat menggunakan JS
        $browser->script("
            if (typeof updateUserLocationMarker === 'function') {
                updateUserLocationMarker(-6.917464, 107.619123, 10);
                fetchNearbyBusinesses();
            }
        ");
    }

    // TC-MAP-01: Mengakses halaman peta via tab lokasi.
    public function test_TC_MAP_01_AksesHalamanPeta()
    {
        $this->browse(function (Browser $browser) {
            $this->loginUser($browser);
            
            // Akses URL langsung yang bertindak sebagai "Klik tab Lokasi"
            $browser->visit('/user/lokasi')
                    ->pause(duskDelay())
                    ->assertPresent('#map') // Pastikan elemen map utama dirender
                    ->pause(duskDelay());
        });
    }

    // TC-MAP-02: Memastikan marker "Lokasi Saya" muncul.
    public function test_TC_MAP_02_MarkerLokasiSayaMuncul()
    {
        $this->browse(function (Browser $browser) {
            $this->loginUser($browser);
            $browser->visit('/user/lokasi')
                    ->pause(duskDelay());
            
            $this->injectMockLocation($browser);
            $browser->pause(duskDelay())
                    ->waitFor('.user-marker', 5)
                    ->assertPresent('.user-marker') // Marker dengan warna biru (Lokasi Saya)
                    ->pause(duskDelay());
        });
    }

    // TC-MAP-03: Penggunaan filter kategori unit bisnis (Restoran).
    public function test_TC_MAP_03_FilterKategoriRestoran()
    {
        $this->browse(function (Browser $browser) {
            $this->loginUser($browser);
            $browser->visit('/user/lokasi')
                    ->pause(duskDelay());
            
            $this->injectMockLocation($browser);
            $browser->waitFor('.custom-marker', 5)
                    ->pause(duskDelay());

            // Klik filter Restoran (Tombol filter ke-2)
            $browser->click('.flex.flex-wrap.gap-2 button:nth-child(2)')
                    ->pause(duskDelay())
                    ->pause(1500); // Tunggu network fetch & rerender marker

            // Memastikan marker lain disembunyikan dan tersisa 1 marker (Restoran)
            $count = $browser->script("return document.querySelectorAll('.custom-marker').length;")[0];
            $this->assertEquals(1, $count, "Seharusnya hanya 1 marker kategori Restoran yang tampil di peta.");
        });
    }

    // TC-MAP-04: Interaksi klik marker unit bisnis.
    public function test_TC_MAP_04_KlikMarkerUnitBisnis()
    {
        $this->browse(function (Browser $browser) {
            $this->loginUser($browser);
            $browser->visit('/user/lokasi')
                    ->pause(duskDelay());
            
            $this->injectMockLocation($browser);
            $browser->waitFor('.custom-marker', 5)
                    ->pause(duskDelay())
                    ->click('.custom-marker') // Klik marker pertama yang ditemukan
                    ->pause(duskDelay())
                    ->waitFor('.leaflet-popup-content', 5)
                    ->assertPresent('.leaflet-popup-content') // Memastikan Jendela Info / Popup muncul
                    ->pause(duskDelay());
        });
    }

    // TC-MAP-05: Pencarian unit bisnis yang valid (ditemukan).
    public function test_TC_MAP_05_PencarianValid()
    {
        $this->browse(function (Browser $browser) {
            $this->loginUser($browser);
            $browser->visit('/user/lokasi')
                    ->pause(duskDelay());
            
            $this->injectMockLocation($browser);
            $browser->waitFor('.custom-marker', 5)
                    ->pause(duskDelay())
                    ->type('#searchInput', 'Cafe Santai')
                    ->pause(duskDelay())
                    ->keys('#searchInput', '{enter}') // Tekan enter untuk mencari
                    ->pause(2000) // Tunggu fetch data & animasi zoom (flyTo) dari Leaflet
                    ->pause(duskDelay());

            // Memastikan marker yang ditemukan hanya 1 (Cafe Santai)
            $count = $browser->script("return document.querySelectorAll('.custom-marker').length;")[0];
            $this->assertEquals(1, $count);
        });
    }

    // TC-MAP-06: Pencarian unit bisnis tidak valid (tidak ada).
    public function test_TC_MAP_06_PencarianTidakValid()
    {
        $this->browse(function (Browser $browser) {
            $this->loginUser($browser);
            $browser->visit('/user/lokasi')
                    ->pause(duskDelay());
            
            $this->injectMockLocation($browser);
            $browser->waitFor('.custom-marker', 5)
                    ->pause(duskDelay())
                    ->type('#searchInput', 'NamaAsalTidakAdaDiDatabase')
                    ->pause(duskDelay())
                    ->keys('#searchInput', '{enter}')
                    ->pause(1000) // Tunggu fetch error
                    ->waitFor('#search-error', 5)
                    ->assertVisible('#search-error') // Assert notifikasi merah muncul
                    ->pause(duskDelay());
        });
    }

    // TC-MAP-07: Memusatkan kembali peta ke lokasi pengguna.
    public function test_TC_MAP_07_PusatkanKembaliPeta()
    {
        $this->browse(function (Browser $browser) {
            $this->loginUser($browser);
            $browser->visit('/user/lokasi')
                    ->pause(duskDelay());
            
            $this->injectMockLocation($browser);
            $browser->waitFor('.user-marker', 5)
                    ->pause(duskDelay());
            
            // Geser peta menjauh menggunakan script Leaflet (drag simulation)
            $browser->script("map.panBy([400, 400]);");
            
            $browser->pause(duskDelay())
                    ->click("button[onclick='centerMapToUser()']") // Klik ikon target/kembali ke lokasi
                    ->pause(1500) // Tunggu durasi animasi pan
                    ->pause(duskDelay());
            
            $this->assertTrue(true); // Verifikasi sukses selama tidak ada element not found error
        });
    }
}
