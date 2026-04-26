<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TambahMenuController;
use App\Http\Controllers\ManajemenUserController;
use App\Http\Controllers\RegistUnitBisnisController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\KomunitasController;
use App\Http\Controllers\FoodController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('registerkomunitas', KomunitasController::class)->names([
    'index' => 'registerkomunitas',
]);
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


// Route::get('/manajemen-user', function () {
//     return view('manajemen_user');
// });

Route::resource('manajemen-user', ManajemenUserController::class);
Route::get('/register/unit-bisnis', [RegistUnitBisnisController::class, 'create'])->name('unit-bisnis.create');
Route::post('/register/unit-bisnis', [RegistUnitBisnisController::class, 'store'])->name('unit-bisnis.store');
Route::get('/login',  [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');  

Route::view('/login', 'login');
Route::get('/register', function () {
    return view('RegisterKomunitas');
});

Route::post('/register/store', [KomunitasController::class, 'store'])->name('register.store');

Route::get('/tambah-menu', function () {
    return view('TambahMenuAktif');
});
Route::post('/tambah-menu/store', [TambahMenuController::class, 'store'])->name('makanan.store');

// ===== UNIT BISNIS — KELOLA MASTER DATA MAKANAN =====
// Alias tanpa prefix (dipakai di Blade dengan nama pendek)
Route::resource('kelolamasterdata', FoodController::class)
     ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
