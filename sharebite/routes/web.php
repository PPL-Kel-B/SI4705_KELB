<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\KomunitasController; 

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login',  [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');  

Route::view('/login', 'login');
Route::get('/register', function () {
    return view('RegisterKomunitas');
});

Route::post('/register/store', [KomunitasController::class, 'store'])->name('register.store');
