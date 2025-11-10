<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DahsboardController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [DahsboardController::class, 'index'])->name('dashboard');
