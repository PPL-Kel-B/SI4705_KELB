<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KomunitasController; 

Route::get('/', function () {
    return view('welcome');
});

Route::resource('registerkomunitas', KomunitasController::class)->names([
    'index' => 'registerkomunitas',
]);