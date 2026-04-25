<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManajemenUserController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/manajemen-user', function () {
//     return view('manajemen_user');
// });

Route::resource('manajemen-user', ManajemenUserController::class);