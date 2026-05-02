<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ManajemenUserController;
use App\Http\Controllers\RegistUnitBisnisController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\KomunitasController;

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
require __DIR__.'/auth.php';

// ==========================================
// Authentication Routes (Custom) - Overrides Breeze
// ==========================================
Route::get('/login',  [LoginController::class, 'index'])->name('login');
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
        Route::get('/dashboard', function () { return view('user.dashboard'); })->name('dashboard');
        Route::get('/riwayat', function () { return view('user.riwayat'); })->name('riwayat');
        Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
        Route::get('/pengaturan', function () { return view('user.pengaturan'); })->name('pengaturan');
    });

    // Unit Bisnis Dashboard Routes
    Route::prefix('unit')->name('unit.')->group(function () {
        Route::get('/dashboard', function () { return view('unit_bisnis.dashboard'); })->name('dashboard');
        
        // Menu Aktif
        Route::get('/kelola-makanan', [\App\Http\Controllers\MenuAktifController::class, 'index'])->name('kelola_makanan');
        Route::get('/kelola-makanan/tambah', [\App\Http\Controllers\MenuAktifController::class, 'create'])->name('menu_aktif.create');
        Route::post('/kelola-makanan/tambah', [\App\Http\Controllers\MenuAktifController::class, 'store'])->name('menu_aktif.store');
        
        // Master Data (placeholder)
        Route::get('/kelola-master-data', function () { return view('unit_bisnis.dashboard'); })->name('master_data.index');

        Route::get('/pesanan', function () { return view('unit_bisnis.pesanan'); })->name('pesanan');
        Route::get('/riwayat', function () { return view('unit_bisnis.riwayat'); })->name('riwayat');
        Route::get('/profil', function () { return view('unit_bisnis.profil'); })->name('profil');
        Route::get('/pengaturan', function () { return view('unit_bisnis.pengaturan'); })->name('pengaturan');
    });

    // Admin Dashboard Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () { return view('admin.dashboard'); })->name('dashboard');
        Route::get('/manajemen-pengguna', [ManajemenUserController::class, 'index'])->name('manajemen_pengguna');
        Route::put('/manajemen-pengguna/{id}', [ManajemenUserController::class, 'update'])->name('manajemen_pengguna.update');
        Route::delete('/manajemen-pengguna/{id}', [ManajemenUserController::class, 'destroy'])->name('manajemen_pengguna.destroy');
        Route::get('/chat', function () { return view('admin.chat'); })->name('chat');
    });

});

// ==========================================
// Application Features Routes
// ==========================================

// User Management
Route::resource('manajemen-user', ManajemenUserController::class);
