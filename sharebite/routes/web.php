<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManajemenUserController;
use App\Http\Controllers\RegistUnitBisnisController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\KomunitasController; 

Route::get('/', function () {
    return view('welcome');
});

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
