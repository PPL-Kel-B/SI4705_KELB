<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KomunitasController; 

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', function () {
    return view('RegisterKomunitas');
});

Route::post('/register/store', [KomunitasController::class, 'store'])->name('register.store');

// Admin Dashboard Routes
Route::get('/admin/kelola-menu-makanan', function () {
    return view('KelolaMenuMakanan');
})->name('admin.kelola-menu-makanan');