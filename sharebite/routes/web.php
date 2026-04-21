<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterUnitBisnisController;

Route::get('/register/unit-bisnis', [RegisterUnitBisnisController::class, 'index'])->name('register.unit-bisnis');
Route::post('/register/unit-bisnis', [RegisterUnitBisnisController::class, 'store'])->name('register.unit-bisnis.store');
