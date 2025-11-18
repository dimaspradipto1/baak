<?php

use App\Http\Middleware\Checkrole;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DahsboardController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\ProgramStudiController;
use App\Http\Controllers\TahunAkademikController;
use App\Http\Controllers\PegawaiController;

Route::controller(LoginController::class)->group(function(){
    Route::get('/', 'login')->name('login');
    Route::post('/loginproses', 'loginproses')->name('loginproses');
    Route::get('/logout', 'logout')->name('logout');
    
});

Route::middleware(['auth', 'checkrole'])->group(function(){
    Route::get('/admin', [DahsboardController::class, 'dashboard'])->name('dashboard');
    Route::resource('users', UserController::class);
    Route::get('/user/{id}/update-password',[UserController::class, 'showUpdatePasswordForm'])->name('users.showUpdatePasswordForm');
    Route::put('/user/{id}/update-password', [UserController::class, 'updatePassword'])->name('users.updatePassword');
    Route::resource('tahunAkademik', TahunAkademikController::class);
    Route::resource('mahasiswa', MahasiswaController::class);
    Route::resource('programStudi', ProgramStudiController::class);
    Route::resource('pegawai', PegawaiController::class);
});