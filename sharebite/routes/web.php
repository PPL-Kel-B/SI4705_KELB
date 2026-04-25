<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistUnitBisnisController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register/unit-bisnis', [RegistUnitBisnisController::class, 'create'])->name('unit-bisnis.create');
Route::post('/register/unit-bisnis', [RegistUnitBisnisController::class, 'store'])->name('unit-bisnis.store');
