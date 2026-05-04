<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ManajemenUserController;
use App\Http\Controllers\RegistUnitBisnisController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\KomunitasController;
use App\Http\Controllers\MasterMakananController;
use App\Http\Controllers\DevMasterDataController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

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
    Route::get('/', function () {
        return view('auth.register_komunitas');
    });
    Route::post('/store', [KomunitasController::class, 'store'])->name('register.store');

    // Unit Bisnis
    Route::get('/unit-bisnis', [RegistUnitBisnisController::class, 'create'])->name('unit-bisnis.create');
    Route::post('/unit-bisnis', [RegistUnitBisnisController::class, 'store'])->name('unit-bisnis.store');
});

Route::resource('registerkomunitas', KomunitasController::class)->names([
    'index' => 'registerkomunitas',
]);

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

    // User Dashboard Routes
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', function () {
            return view('user.dashboard');
        })->name('dashboard');
        Route::get('/riwayat', function () {
            return view('user.riwayat');
        })->name('riwayat');
        Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
        Route::get('/pengaturan', function () {
            return view('user.pengaturan');
        })->name('pengaturan');
    });

    // Unit Bisnis Dashboard Routes
    Route::prefix('unit')->name('unit.')->group(function () {
        Route::get('/dashboard', function () {
            return view('unit_bisnis.dashboard');
        })->name('dashboard');

        // Menu Aktif
        Route::get('/kelola-makanan', [\App\Http\Controllers\MenuAktifController::class, 'index'])->name('kelola_makanan');
        Route::get('/kelola-makanan/tambah', [\App\Http\Controllers\MenuAktifController::class, 'create'])->name('menu_aktif.create');
        Route::post('/kelola-makanan/tambah', [\App\Http\Controllers\MenuAktifController::class, 'store'])->name('menu_aktif.store');

        // Master Data
        Route::get('/kelola-master-data', [MasterMakananController::class, 'index'])->name('master_data.index');
        Route::get('/kelola-master-data/tambah', [MasterMakananController::class, 'create'])->name('master_data.create');
        Route::post('/kelola-master-data/tambah', [MasterMakananController::class, 'store'])->name('master_data.store');

        Route::get('/pesanan', function () {
            return view('unit_bisnis.pesanan');
        })->name('pesanan');
        Route::get('/riwayat', function () {
            return view('unit_bisnis.riwayat');
        })->name('riwayat');
        Route::get('/profil', function () {
            return view('unit_bisnis.profil');
        })->name('profil');
        Route::get('/pengaturan', function () {
            return view('unit_bisnis.pengaturan');
        })->name('pengaturan');
    });

    // Admin Dashboard Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');
        Route::get('/manajemen-pengguna', [ManajemenUserController::class, 'index'])->name('manajemen_pengguna');

        // Route lama (PUT/DELETE via URL param) - dipertahankan untuk kompatibilitas
        Route::put('/manajemen-pengguna/{id}', [ManajemenUserController::class, 'update'])->name('manajemen_pengguna.update_old');
        Route::delete('/manajemen-pengguna/{id}', [ManajemenUserController::class, 'destroy'])->name('manajemen_pengguna.destroy_old');

        // Route baru (POST via body param) - lebih reliable dengan Alpine.js modal
        Route::post('/manajemen-pengguna/update', [ManajemenUserController::class, 'updateByPost'])->name('manajemen_pengguna.update');
        Route::post('/manajemen-pengguna/destroy', [ManajemenUserController::class, 'destroyByPost'])->name('manajemen_pengguna.destroy');

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

// Master Data (resource route)
Route::resource('kelola-master-data', \App\Http\Controllers\MasterDataController::class)->names([
    'index' => 'master_data.index',
    'create' => 'master_data.create',
    'store' => 'master_data.store',
    'edit' => 'master_data.edit',
    'update' => 'master_data.update',
    'destroy' => 'master_data.destroy',
])->parameters(['kelola-master-data' => 'master_datum']);

// ==========================================
// PUBLIC ROUTES - Tanpa Login (untuk testing/review)
// ==========================================
Route::prefix('dev')->name('dev.')->group(function () {
    // Kelola Master Data - bisa diakses tanpa login
    Route::get('/master-data', [MasterMakananController::class, 'index'])->name('master_data.index');
    Route::get('/master-data/tambah', [MasterMakananController::class, 'create'])->name('master_data.create');
    Route::post('/master-data/tambah', [MasterMakananController::class, 'store'])->name('master_data.store');
    Route::get('/master-data/edit/{id}', [\App\Http\Controllers\MasterDataController::class, 'edit'])->name('master_data.edit');
    Route::put('/master-data/edit/{master_datum}', [\App\Http\Controllers\MasterDataController::class, 'update'])->name('master_data.update');
    Route::delete('/master-data/{master_datum}', [\App\Http\Controllers\MasterDataController::class, 'destroy'])->name('master_data.destroy');
});