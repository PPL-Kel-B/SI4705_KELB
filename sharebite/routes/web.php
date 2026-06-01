<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ManajemenUserController;
use App\Http\Controllers\RegistUnitBisnisController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\KomunitasController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\RegistIndividuController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\PembayaranController;

use App\Http\Controllers\DashboardUnitBisnisController;
use App\Http\Controllers\UnitBisnisController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [\App\Http\Controllers\LandingController::class, 'index'])->name('home');
Route::get('/mitra', [\App\Http\Controllers\LandingController::class, 'mitra'])->name('mitra');
Route::get('/komunitas', [\App\Http\Controllers\LandingController::class, 'komunitas'])->name('komunitas');
Route::get('/tentang-kami', function () {
    return view('tentang_kami');
})->name('tentang-kami');

// ==========================================
// Laravel Breeze Default Auth Routes
// ==========================================
require __DIR__ . '/auth.php';

// ==========================================
// Authentication Routes (Custom) - Overrides Breeze
// ==========================================
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

// ==========================================
// Registration Routes
// ==========================================
Route::prefix('register')->group(function () {
    // Komunitas
    Route::get('/', [KomunitasController::class, 'create'])->name('register');
    Route::post('/store', [KomunitasController::class, 'store'])->name('register.store');

    // Unit Bisnis
    Route::get('/unit-bisnis', [RegistUnitBisnisController::class, 'create'])->name('unit-bisnis.create');
    Route::post('/unit-bisnis', [RegistUnitBisnisController::class, 'store'])->name('unit-bisnis.store');

    // Individu
    Route::get('/individu', [RegistIndividuController::class, 'create'])->name('individu.create');
    Route::post('/individu', [RegistIndividuController::class, 'store'])->name('individu.store');
});

Route::resource('registerkomunitas', KomunitasController::class)->names([
    'index' => 'registerkomunitas',
]);

// =========================================================================
// ROUTE PUBLIK UNTUK SCAN HP (TIDAK PERLU LOGIN AGAR HP BISA AKSES)
// =========================================================================
Route::get('/public/scan-qris/{slug}', [PembayaranController::class, 'simulasiScan'])->name('pembayaran.scan.public');

// ==========================================
// Authenticated Routes
// ==========================================
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware('verified')->name('dashboard');

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Notifications
    Route::post('/notifications/{id}/read', function ($id) {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return back();
    })->name('notifications.read');

    Route::post('/notifications/read-all', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.read_all');

    Route::get('/test-notifications', function () {
        $user = auth()->user();
        
        // 1. MakananDekatNotification
        $menuAktif = \App\Models\MenuAktif::first();
        if ($menuAktif) {
            $user->notify(new \App\Notifications\MakananDekatNotification($menuAktif));
        }

        // 2. PesananMasukNotification
        $pesanan = \App\Models\Pesanan::first();
        if ($pesanan) {
            $user->notify(new \App\Notifications\PesananMasukNotification($pesanan));
        }

        // 3. UnitBisnisMendaftarNotification
        $user->notify(new \App\Notifications\UnitBisnisMendaftarNotification($user));

        // 4. ChatMasukNotification
        $chat = \App\Models\Chat::first();
        if ($chat) {
            $user->notify(new \App\Notifications\ChatMasukNotification($chat));
        } else {
            $mockChat = new \App\Models\Chat([
                'sender_id' => $user->id,
                'receiver_id' => $user->id,
                'pesan' => 'Halo, ini adalah pesan uji coba notifikasi chat!',
                'waktu' => now(),
            ]);
            $mockChat->setRelation('sender', $user);
            $user->notify(new \App\Notifications\ChatMasukNotification($mockChat));
        }

        return redirect()->back()->with('success', 'Uji coba notifikasi berhasil dikirim! Silakan periksa ikon lonceng.');
    })->name('test_notifications');

    // User Dashboard Routes
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', function () {
            return view('user.dashboard');
        })->name('dashboard');
        Route::get('/riwayat', [\App\Http\Controllers\RiwayatController::class, 'index'])->name('riwayat');
        Route::get('/riwayat/{id}', [App\Http\Controllers\RiwayatController::class, 'show'])->name('riwayat.show');
        Route::post('/riwayat/{id}/rate', [App\Http\Controllers\RiwayatController::class, 'storeRating'])->name('riwayat.storeRating');
        Route::get('/profile/edit', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/update', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
        Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
      
        Route::get('/pengaturan', [\App\Http\Controllers\SettingsController::class, 'index'])->name('pengaturan');
        Route::get('/pengaturan/kebijakan/{type}', [\App\Http\Controllers\SettingsController::class, 'policy'])->name('pengaturan.policy');
        Route::delete('/pengaturan/session/{id}', [\App\Http\Controllers\SettingsController::class, 'logoutSession'])->name('pengaturan.logout_session');
        Route::post('/pengaturan/update', [\App\Http\Controllers\SettingsController::class, 'update'])->name('pengaturan.update');

       Route::get('/unit-bisnis/{id}', [\App\Http\Controllers\ProfilUnitBisnisController::class, 'show'])->name('unit-bisnis.show');

        // ROUTE SEMENTARA UNTUK TES TOMBOL (Nanti dihapus saat digabung)
        Route::get('/tes-tombol-profil/{menu_aktif_id?}', [\App\Http\Controllers\ProfilUnitBisnisController::class, 'simulasiDetail'])->name('tes-tombol-profil');
        // Route Pembayaran Utama
        Route::get('/dashboard/{slug}/pembayaran', [PembayaranController::class, 'show'])->name('makanan.pembayaran');
        
        // Laptop diam-diam mengecek status scan ke sini
        Route::get('/dashboard/{slug}/pembayaran/check', [PembayaranController::class, 'cekStatusScan'])->name('pembayaran.check');
        
        // Proses Pembayaran & Halaman Berhasil
        Route::post('/dashboard/{slug}/pembayaran/proses', [PembayaranController::class, 'store'])->name('pembayaran.proses');
        Route::get('/dashboard/{slug}/pembayaran/berhasil', [PembayaranController::class, 'berhasil'])->name('pembayaran.berhasil');
    });

    // Unit Bisnis Dashboard Routes
    Route::prefix('unit')->name('unit.')->group(function () {
        Route::get('/dashboard', [DashboardUnitBisnisController::class, 'index'])->name('dashboard');

        // Menu Aktif
        Route::get('/kelola-makanan', [\App\Http\Controllers\MenuAktifController::class, 'index'])->name('kelola_makanan');
        Route::get('/kelola-makanan/tambah', [\App\Http\Controllers\MenuAktifController::class, 'create'])->name('menu_aktif.create');
        Route::post('/kelola-makanan/tambah', [\App\Http\Controllers\MenuAktifController::class, 'store'])->name('menu_aktif.store');
        Route::get('/kelola-makanan/{menuAktif}/edit', [\App\Http\Controllers\MenuAktifController::class, 'edit'])->name('menu_aktif.edit');
        Route::put('/kelola-makanan/{menuAktif}', [\App\Http\Controllers\MenuAktifController::class, 'update'])->name('menu_aktif.update');
        Route::delete('/kelola-makanan/{menuAktif}', [\App\Http\Controllers\MenuAktifController::class, 'destroy'])->name('menu_aktif.destroy');
        
        
        // Master Data
        Route::resource('kelola-master-data', MasterDataController::class)->names([
            'index' => 'master_data.index',
            'create' => 'master_data.create',
            'store' => 'master_data.store',
            'edit' => 'master_data.edit',
            'update' => 'master_data.update',
            'destroy' => 'master_data.destroy',
        ])->parameters(['kelola-master-data' => 'master_datum']);

        Route::get('/pesanan', function () {
            return view('unit_bisnis.pesanan');
        })->name('pesanan');
        Route::get('/riwayat', function () {
            return view('unit_bisnis.riwayat');
        })->name('riwayat');

        // Profil Unit Bisnis
        Route::get('/profil', [UnitBisnisController::class, 'showProfile'])->name('profil');
        Route::post('/profil/update', [UnitBisnisController::class, 'updateProfile'])->name('profil.update');

        // Upload Foto Profile Unit Bisnis
        Route::post('/profil/upload-foto', [UnitBisnisController::class, 'uploadFotoProfile'])
             ->name('profil.upload-foto');

        // Hapus Foto Profile Unit Bisnis
        Route::delete('/profil/hapus-foto', [UnitBisnisController::class, 'hapusFotoProfile'])
            ->name('profil.hapus-foto');
            
        // Pengaturan Unit Bisnis
        Route::get('/pengaturan', [UnitBisnisController::class, 'showSettings'])->name('pengaturan');
        Route::post('/pengaturan/update', [UnitBisnisController::class, 'updateSettings'])->name('pengaturan.update');
        Route::post('/pengaturan/update-password', [UnitBisnisController::class, 'updatePassword'])->name('pengaturan.update-password');
    });

    // Admin Dashboard Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/laporan', [AdminDashboardController::class, 'report'])->name('laporan');
        Route::get('/transaksi', [AdminDashboardController::class, 'allTransactions'])->name('transaksi');
        Route::get('/manajemen-pengguna', [ManajemenUserController::class, 'index'])->name('manajemen_pengguna');
        Route::get('/manajemen-pengguna/verifikasi/{id}', [ManajemenUserController::class, 'reviewNib'])->name('manajemen_pengguna.review_nib');
        Route::post('/manajemen-pengguna/verifikasi/{id}', [ManajemenUserController::class, 'processNib'])->name('manajemen_pengguna.process_nib');
        Route::put('/manajemen-pengguna/{id}', [ManajemenUserController::class, 'update'])->name('manajemen_pengguna.update');
        Route::delete('/manajemen-pengguna/{id}', [ManajemenUserController::class, 'destroy'])->name('manajemen_pengguna.destroy');
        Route::get('/chat', function () {
            return view('admin.chat');
        })->name('chat');
    });
});

// ==========================================
// Application Features Routes
// ==========================================

// User Management
Route::resource('manajemen-user', ManajemenUserController::class);