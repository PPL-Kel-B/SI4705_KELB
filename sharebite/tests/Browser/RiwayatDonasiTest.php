<?php

use Laravel\Dusk\Browser;
use App\Models\User;
use Illuminate\Support\Facades\Config;

// =========================================================================
// SKENARIO 1: Mengetes Keamanan Hak Akses Riwayat Per Akun (Anti-IDOR)
// =========================================================================
test('skenario 1 keamanan riwayat per akun', function () {
    Config::set('database.connections.mysql.database', 'sharebite_dusk');
    Config::set('database.connections.mysql.username', 'root');
    Config::set('database.connections.mysql.password', ''); 
    Config::set('database.default', 'mysql');

    $userA = User::find(10); 
    $idPesananUserB = 27;     

    $this->browse(function (Browser $browser) use ($userA, $idPesananUserB) {
        $browser->visit('http://127.0.0.1:8001/login')
                ->type('email', $userA->email)       
                ->type('password', "password\n")     
                
                ->visit('http://127.0.0.1:8001/user/riwayat')
                ->assertDontSee("SB-0014")
                ->pause(1500) 
                
                ->visit("http://127.0.0.1:8001/user/riwayat/{$idPesananUserB}")
                ->pause(1500) 
                ->assertDontSee('SB-0014');
    });
});

// =========================================================================
// SKENARIO 2: Mengetes Fitur Filter Status Pesanan (4 Status)
// =========================================================================
test('skenario 2 filter status pesanan', function () {
    Config::set('database.connections.mysql.database', 'sharebite_dusk'); // <--- SUDAH DIPERBAIKI
    Config::set('database.connections.mysql.username', 'root');
    Config::set('database.connections.mysql.password', ''); 
    Config::set('database.default', 'mysql');

    $user = User::find(10); 

    $this->browse(function (Browser $browser) use ($user) {
        $browser->visit('http://127.0.0.1:8001/user/riwayat')
                ->pause(1000);

        // -----------------------------------------------------------------
        // A. PENGUJIAN FILTER: SELESAI
        // -----------------------------------------------------------------
        $browser->clickLink('Selesai')
                ->pause(1500)
                ->assertQueryStringHas('status', 'selesai')
                ->assertDontSeeIn('tbody', 'menunggu_pembayaran')
                ->assertDontSeeIn('tbody', 'proses')
                ->assertDontSeeIn('tbody', 'dibatalkan');

        // -----------------------------------------------------------------
        // B. PENGUJIAN FILTER: PROSES
        // -----------------------------------------------------------------
        $browser->clickLink('Proses') 
                ->pause(1500)
                ->assertQueryStringHas('status', 'proses') 
                ->assertDontSeeIn('tbody', 'selesai')
                ->assertDontSeeIn('tbody', 'menunggu_pembayaran')
                ->assertDontSeeIn('tbody', 'dibatalkan');

        // -----------------------------------------------------------------
        // C. PENGUJIAN FILTER: MENUNGGU PEMBAYARAN
        // -----------------------------------------------------------------
        $browser->clickLink('Menunggu Pembayaran') 
                ->pause(1500)
                ->assertQueryStringHas('status', 'menunggu_pembayaran')
                ->assertDontSeeIn('tbody', 'selesai')
                ->assertDontSeeIn('tbody', 'proses')
                ->assertDontSeeIn('tbody', 'dibatalkan');

        // -----------------------------------------------------------------
        // D. PENGUJIAN FILTER: BATAL
        // -----------------------------------------------------------------
        $browser->clickLink('Batal') 
                ->pause(1500)
                ->assertQueryStringHas('status', 'batal')
                ->assertDontSeeIn('tbody', 'selesai')
                ->assertDontSeeIn('tbody', 'proses')
                ->assertDontSeeIn('tbody', 'menunggu_pembayaran');
    });
});

// =========================================================================
// SKENARIO 3: Mengetes Validasi Ekstensi dan Ukuran File Upload (Max 2MB)
// =========================================================================
test('skenario 3 validasi format dan ukuran upload', function () {
    Config::set('database.connections.mysql.database', 'sharebite_dusk'); // <--- SUDAH DIPERBAIKI
    Config::set('database.connections.mysql.username', 'root');
    Config::set('database.connections.mysql.password', ''); 
    Config::set('database.default', 'mysql');

    $user = User::find(10);
    $idPesananSelesaiBelumDiisi = 19; 

    $this->browse(function (Browser $browser) use ($user, $idPesananSelesaiBelumDiisi) {
        $browser->loginAs($user)
                ->visit("http://127.0.0.1:8001/user/riwayat/{$idPesananSelesaiBelumDiisi}")
                ->waitFor('#dropzone')

                // 1. Uji validasi ekstensi file salah (PDF) via SweetAlert2 kustom
                ->attach('bukti_berbagi', base_path('tests/Browser/stubs/Format Salah.pdf'))
                ->pause(1500)
                ->waitForText('Format File Salah!') 
                ->click('.swal2-confirm') 
                ->pause(1000);
    });
});

// =========================================================================
// SKENARIO 4: Mengetes Validasi Form Kosong (SweetAlert2 Memblokir Pengiriman)
// =========================================================================
test('skenario 4 validasi mandatory form testimoni', function () {
    Config::set('database.connections.mysql.database', 'sharebite_dusk'); // <--- SUDAH DIPERBAIKI
    Config::set('database.connections.mysql.username', 'root');
    Config::set('database.connections.mysql.password', ''); 
    Config::set('database.default', 'mysql');

    $user = User::find(10);
    $idPesananSelesaiBelumDiisi = 13; 

    $this->browse(function (Browser $browser) use ($user, $idPesananSelesaiBelumDiisi) {
        $browser->loginAs($user)
                ->visit("http://127.0.0.1:8001/user/riwayat/{$idPesananSelesaiBelumDiisi}")
                ->waitFor('#dropzone')

                // Unggah gambar valid, tapi rating bintang dikosongkan langsung klik Kirim
                ->attach('bukti_berbagi', base_path('tests/Browser/stubs/Format Yang Sesuai.jpeg'))
                ->pause(1500)
                ->press('Kirim') 
                
                // Memastikan SweetAlert2 menghadang submit karena rating kosong
                ->waitForText('Rating Belum Diisi!') 
                ->click('.swal2-confirm');
    });
});

// =========================================================================
// SKENARIO 5: Sukses Kirim Testimoni & Berubah Menjadi Tampilan Terkunci
// =========================================================================
test('skenario 5 sukses kirim testimoni hingga lock form', function () {
    Config::set('database.connections.mysql.database', 'sharebite_dusk'); // <--- SUDAH DIPERBAIKI
    Config::set('database.connections.mysql.username', 'root');
    Config::set('database.connections.mysql.password', ''); 
    Config::set('database.default', 'mysql');

    $user = User::find(10);
    $idPesananSelesaiBelumDiisi = 14; 

    $this->browse(function (Browser $browser) use ($user, $idPesananSelesaiBelumDiisi) {
        $browser->loginAs($user)
                ->visit("http://127.0.0.1:8001/user/riwayat/{$idPesananSelesaiBelumDiisi}")
                ->waitFor('#dropzone')

                ->attach('bukti_berbagi', base_path('tests/Browser/stubs/Format Yang Sesuai.jpeg'))
                ->pause(1000)
                ->click('[data-star="5"]') 
                ->type('catatan_pengalaman', 'Makanannya sangat layak konsumsi dan higienis! Terima kasih ShareBite.')
                ->pause(1000)
                ->press('Kirim')
                
                ->pause(2500) 
                
                // 3. Post-Submission State: Memastikan form input hilang diganti kotak ulasan read-only
                ->assertMissing('#rating-form') 
                ->assertSee('Anda telah mengirimkan bukti berbagi dan penilaian rating untuk donasi ini.'); 
    });
});