<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\UnitBisnisProfile;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        // 1. Tentukan database Dusk dan Main
        $duskDb = $this->getDatabaseNameFromEnv('.env.dusk.local', 'sharebite_dusk');
        $mainDb = $this->getDatabaseNameFromEnv('.env', 'sharebite');

        // 2. Ambil detail koneksi untuk membuat database Dusk jika belum ada
        $conn = $this->getDbConnectionDetails('.env.dusk.local');

        // Buat database Dusk secara dinamis jika belum ada sebelum parent::setUp() memicu migrasi
        try {
            $pdo = new \PDO("mysql:host={$conn['host']};port={$conn['port']}", $conn['username'], $conn['password']);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$duskDb}`");
        } catch (\Exception $e) {
            // Abaikan jika gagal
        }

        parent::setUp();

        // 3. Seed ke kedua database agar web server (main db) & dusk test runner (dusk db) sinkron
        $this->seedDatabase($duskDb);
        if ($mainDb !== $duskDb) {
            $this->seedDatabase($mainDb);
        }

        // 4. Kembalikan koneksi ke Dusk DB untuk assertions runner
        config(["database.connections.mysql.database" => $duskDb]);
        DB::purge('mysql');
        DB::reconnect('mysql');

        // 5. Bersihkan semua cookie browser agar setiap test dimulai sebagai guest
        $this->browse(function (Browser $browser) {
            try {
                $browser->visit('/');
                $browser->driver->manage()->deleteAllCookies();
            } catch (\Exception $e) {
                // Abaikan
            }
        });
    }

    /**
     * Membaca nama database dari file environment.
     */
    private function getDatabaseNameFromEnv(string $envFile, string $default): string
    {
        $basePath = dirname(__DIR__, 2);
        $envPath = $basePath . '/' . $envFile;
        if (file_exists($envPath)) {
            $envContent = file_get_contents($envPath);
            if (preg_match('/^DB_DATABASE\s*=\s*(.+)$/m', $envContent, $matches)) {
                return trim($matches[1], " \t\n\r\0\x0B\"'");
            }
        }
        return $default;
    }

    /**
     * Membaca detail koneksi database dari file environment.
     */
    private function getDbConnectionDetails(string $envFile): array
    {
        $basePath = dirname(__DIR__, 2);
        $envPath = $basePath . '/' . $envFile;
        if (!file_exists($envPath)) {
            $envPath = $basePath . '/.env';
        }

        $details = [
            'host' => '127.0.0.1',
            'port' => '3306',
            'username' => 'root',
            'password' => '',
        ];

        if (file_exists($envPath)) {
            $envContent = file_get_contents($envPath);
            if (preg_match('/^DB_HOST\s*=\s*(.+)$/m', $envContent, $matches)) {
                $details['host'] = trim($matches[1], " \t\n\r\0\x0B\"'");
            }
            if (preg_match('/^DB_PORT\s*=\s*(.+)$/m', $envContent, $matches)) {
                $details['port'] = trim($matches[1], " \t\n\r\0\x0B\"'");
            }
            if (preg_match('/^DB_USERNAME\s*=\s*(.+)$/m', $envContent, $matches)) {
                $details['username'] = trim($matches[1], " \t\n\r\0\x0B\"'");
            }
            if (preg_match('/^DB_PASSWORD\s*=\s*(.+)$/m', $envContent, $matches)) {
                $details['password'] = trim($matches[1], " \t\n\r\0\x0B\"'");
            }
        }
        return $details;
    }

    /**
     * Setup test data dynamically in the specified database.
     */
    private function seedDatabase(string $dbName): void
    {
        config(["database.connections.mysql.database" => $dbName]);
        DB::purge('mysql');
        DB::reconnect('mysql');

        // 1. Admin
        User::updateOrCreate(
            ['email' => 'admin@sharebite.com'],
            [
                'name' => 'Admin ShareBite',
                'password' => bcrypt('Admin@2024!'),
                'role' => 'admin',
                'no_hp' => '08123456789',
            ]
        );

        // 2. Unit Bisnis
        $unit = User::updateOrCreate(
            ['email' => 'unit@sharebite.com'],
            [
                'name' => 'Lestari Food',
                'password' => bcrypt('password'),
                'role' => 'unit_bisnis',
                'no_hp' => '08123456780',
            ]
        );

        // Pastikan Unit Bisnis terverifikasi agar tidak kena block login
        $profile = UnitBisnisProfile::where('user_id', $unit->id)->first();
        if ($profile) {
            $profile->update([
                'status_verifikasi' => 'terverifikasi',
                'verified' => true,
            ]);
        } else {
            UnitBisnisProfile::create([
                'user_id' => $unit->id,
                'nama_usaha' => 'Lestari Food',
                'jenis_usaha' => 'Restoran',
                'foto_bisnis' => 'images/placeholder-bisnis.jpg',
                'lokasi_lat' => '-6.9271',
                'lokasi_lng' => '107.6411',
                'radius_penjemputan' => 15,
                'jam_buka' => '08:00',
                'jam_tutup' => '21:00',
                'verified' => true,
                'status_verifikasi' => 'terverifikasi',
                'tahun_bergabung' => 2023,
            ]);
        }

        // 3. Komunitas
        User::updateOrCreate(
            ['email' => 'komunitas@sharebite.com'],
            [
                'name' => 'Komunitas Berbagi',
                'password' => bcrypt('password'),
                'role' => 'komunitas',
                'no_hp' => '08123456781',
            ]
        );

        // 4. Individu
        User::updateOrCreate(
            ['email' => 'individu@sharebite.com'],
            [
                'name' => 'Individu Peduli',
                'password' => bcrypt('password'),
                'role' => 'individu',
                'no_hp' => '08123456783',
            ]
        );
    }

    /**
     * Menutup tab tambahan jika ada yang bocor dari test case sebelumnya.
     */
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

    /**
     * Test Login Admin
     */
    public function testAdminLogin(): void
    {
        $admin = User::where('role', 'admin')->first();

        $this->browse(function (Browser $browser) use ($admin) {
            $this->closeExtraTabs($browser);
            $browser->visit('/login')
                ->waitForText('Selamat Datang', 5)
                ->clear('email')
                ->type('email', $admin->email)
                ->clear('[name="password"]')
                ->type('[name="password"]', 'Admin@2024!')
                ->pause(2000)
                ->click('#loginBtn')
                ->pause(3000)
                ->assertPathIs('/admin/dashboard')
                ->assertSee('Admin')
                ->pause(2000);
        });
    }

    /**
     * Test Login Unit Bisnis
     */
    public function testUnitBisnisLogin(): void
    {
        $unit = User::where('role', 'unit_bisnis')->first();

        $this->browse(function (Browser $browser) use ($unit) {
            $this->closeExtraTabs($browser);
            $browser->visit('/login')
                ->waitForText('Selamat Datang', 5)
                ->clear('email')
                ->type('email', $unit->email)
                ->clear('[name="password"]')
                ->type('[name="password"]', 'password')
                ->pause(2000)
                ->click('#loginBtn')
                ->pause(3000)
                ->assertPathIs('/unit/dashboard')
                ->assertSee($unit->name)
                ->pause(2000);
        });
    }

    /**
     * Test Login Komunitas
     */
    public function testKomunitasLogin(): void
    {
        $komunitas = User::where('role', 'komunitas')->first();

        $this->browse(function (Browser $browser) use ($komunitas) {
            $this->closeExtraTabs($browser);
            $browser->visit('/login')
                ->waitForText('Selamat Datang', 5)
                ->clear('email')
                ->type('email', $komunitas->email)
                ->clear('[name="password"]')
                ->type('[name="password"]', 'password')
                ->pause(2000)
                ->click('#loginBtn')
                ->pause(3000)
                ->assertPathIs('/user/dashboard')
                ->assertSee($komunitas->name)
                ->pause(2000);
        });
    }

    /**
     * Test Login Individu
     */
    public function testIndividuLogin(): void
    {
        $individu = User::where('role', 'individu')->first();

        $this->browse(function (Browser $browser) use ($individu) {
            $this->closeExtraTabs($browser);
            $browser->visit('/login')
                ->waitForText('Selamat Datang', 5)
                ->clear('email')
                ->type('email', $individu->email)
                ->clear('[name="password"]')
                ->type('[name="password"]', 'password')
                ->pause(2000)
                ->click('#loginBtn')
                ->pause(3000)
                ->assertPathIs('/user/dashboard')
                ->assertSee($individu->name)
                ->pause(2000);
        });
    }
}
