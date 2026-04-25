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