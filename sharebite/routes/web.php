<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KomunitasController;
use App\Http\Controllers\TambahMenuController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', function () {
    return view('RegisterKomunitas');
});

Route::post('/register/store', [KomunitasController::class, 'store'])->name('register.store');

Route::get('/tambah-menu', function () {
    return view('TambahMenuAktif'); 
});
Route::post('/tambah-menu/store', [TambahMenuController::class, 'store'])->name('makanan.store');