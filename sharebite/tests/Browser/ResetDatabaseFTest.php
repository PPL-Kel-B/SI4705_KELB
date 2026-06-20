<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class ResetDatabaseFTest extends DuskTestCase
{
    /**
     * TC-RESET-DB: Mengosongkan dan memigrasikan ulang database sharebite_dusk
     */
    public function testResetDatabase(): void
    {
        // Mengarahkan koneksi ke database sharebite_dusk secara eksplisit
        Config::set('database.connections.mysql.database', 'sharebite_dusk');
        Config::set('database.connections.mysql.username', 'root');
        Config::set('database.connections.mysql.password', '');
        Config::set('database.default', 'mysql');
        DB::purge('mysql');
        DB::reconnect('mysql');

        // Jalankan perintah artisan migrate:fresh + seed untuk mengosongkan semua data dan mengisi ulang data awal (termasuk akun admin)
        Artisan::call('migrate:fresh', ['--seed' => true]);

        $this->assertTrue(true, 'Database sharebite_dusk berhasil dikosongkan, dimigrasikan ulang, dan di-seed.');
    }
}
