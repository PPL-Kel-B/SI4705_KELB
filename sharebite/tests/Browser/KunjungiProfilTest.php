<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\MasterMakanan;
use App\Models\MenuAktif;
use App\Models\UnitBisnisProfile;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class KunjungiProfilTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected $userKomunitas;
    protected $profile;
    protected $menuAktif;
    protected $namaMakananTest = 'Rawon';

    protected function setUp(): void
    {
        parent::setUp();
        
        // 1. Jalankan seeder bawaan (untuk role, default users, dll jika ada)
        $this->artisan('db:seed');

        $this->userKomunitas = User::where('role', 'komunitas')->first();
        if (!$this->userKomunitas) {
            $this->userKomunitas = User::factory()->create(['role' => 'komunitas']);
        }

        $userUnitBisnis = User::where('role', 'unit_bisnis')->first();
        if (!$userUnitBisnis) {
            $userUnitBisnis = User::factory()->create(['role' => 'unit_bisnis']);
        }

        $this->profile = $userUnitBisnis->unitBisnisProfile;
        if (!$this->profile) {
            $this->profile = UnitBisnisProfile::create([
                'user_id' => $userUnitBisnis->id,
                'nama_usaha' => 'katsuna',
                'jenis_usaha' => 'Kafe',
                'jam_buka' => '08:00',
                'jam_tutup' => '21:00',
                'status_verifikasi' => 'terverifikasi'
            ]);
        }

        $masterMakanan = MasterMakanan::updateOrCreate(
            [
                'unit_bisnis_id' => $this->profile->id, 
                'nama_makanan' => $this->namaMakananTest
            ],
            [
                'kategori' => 'Makanan Berat', 
                'harga' => 20000, 
                'berat' => 400
            ]
        );

        $this->menuAktif = MenuAktif::updateOrCreate(
            [
                'master_makanan_id' => $masterMakanan->id,
                'unit_bisnis_id' => $this->profile->id,
            ],
            [
                'stok_porsi' => 15, 
                'batas_pengambilan' => now()->endOfDay(), 
                'status' => 'aktif'
            ]
        );
    }

    /**
     * Helper method untuk login dan navigasi ke halaman profil unit bisnis.
     */
    protected function goToProfile(Browser $browser): void
    {
        $browser->loginAs($this->userKomunitas)
                ->visit('/user/makanan/' . $this->menuAktif->id)
                ->waitForText('Kunjungi Profil', 10)
                ->clickLink('Kunjungi Profil')
                ->waitForLocation('/user/unit-bisnis/' . $this->profile->id, 10)
                ->assertPathIs('/user/unit-bisnis/' . $this->profile->id);
    }

    /**
     * PM-01: Verifikasi Identitas & Kontak Mitra
     */
    public function test_PM_01_VerifikasiIdentitasDanKontakMitra(): void
    {
        $this->browse(function (Browser $browser) {
            $this->goToProfile($browser);
            
            $browser->assertSee($this->profile->nama_usaha)
                    ->assertSee('Verified')
                    ->assertSee('Jam Operasional')
                    ->assertSee('Hubungi Mitra')
                    ->assertSee('Email Mitra');
        });
    }

    /**
     * PM-02: Verifikasi "Tentang Mitra" & "Spesialisasi"
     */
    public function test_PM_02_VerifikasiTentangMitraDanSpesialisasi(): void
    {
        $this->browse(function (Browser $browser) {
            $this->goToProfile($browser);
            
            $browser->assertSee('Tentang Mitra')
                    ->assertSee('SPESIALISASI');
        });
    }

    /**
     * PM-03: Tampilan Galeri (Empty State)
     */
    public function test_PM_03_TampilanGaleriEmptyState(): void
    {
        $this->browse(function (Browser $browser) {
            $this->goToProfile($browser);
            
            $browser->assertSee('GALERI AKTIVITAS DONASI')
                    ->assertSee('Bukti Aktivitas Donasi Belum Tersedia');
        });
    }

    /**
     * PM-04: Tampilan Ulasan Komunitas (Empty State)
     */
    public function test_PM_04_TampilanUlasanKomunitasEmptyState(): void
    {
        $this->browse(function (Browser $browser) {
            $this->goToProfile($browser);
            
            $browser->assertSee('ULASAN KOMUNITAS')
                    ->assertSee('Belum Ada Ulasan');
        });
    }

    /**
     * PM-05: Verifikasi Statistik Mitra
     */
    public function test_PM_05_VerifikasiStatistikMitra(): void
    {
        $this->browse(function (Browser $browser) {
            $this->goToProfile($browser);
            
            $browser->assertSee('TOTAL DONASI')
                    ->assertSee('REPUTASI / RATING');
        });
    }

    /**
     * PM-06: Interaksi Daftar "Makanan Tersedia"
     */
    public function test_PM_06_InteraksiDaftarMakananTersedia(): void
    {
        $this->browse(function (Browser $browser) {
            $this->goToProfile($browser);
            
            $browser->assertSee('Makanan yang Tersedia Saat Ini')
                    ->assertSee($this->namaMakananTest)
                    ->clickLink('Ambil')
                    ->pause(1000)
                    ->assertPathBeginsWith('/user/makanan/');
        });
    }
}