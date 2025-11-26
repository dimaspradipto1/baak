<?php

use App\Http\Middleware\Checkrole;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\DahsboardController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\SuratAktifController;
use App\Http\Controllers\ProgramStudiController;
use App\Http\Controllers\SuratAkademikController;
use App\Http\Controllers\TahunAkademikController;

Route::controller(LoginController::class)->group(function(){
    Route::get('/', 'login')->name('login');
    Route::post('/loginproses', 'loginproses')->name('loginproses');
    Route::get('/logout', 'logout')->name('logout');
    
});
Route::post('/suratAktif/pengajuan', [SuratAktifController::class, 'pengajuan'])->name('suratAktif.pengajuan');
Route::post('/suratAkademik/pengajuan', [SuratAkademikController::class, 'pengajuan'])->name('suratAkademik.pengajuan');

Route::middleware(['auth', 'checkrole'])->group(function(){
    Route::get('/admin', [DahsboardController::class, 'dashboard'])->name('dashboard');
    Route::resource('users', UserController::class);
    Route::get('/user/{id}/update-password',[UserController::class, 'showUpdatePasswordForm'])->name('users.showUpdatePasswordForm');
    Route::put('/user/{id}/update-password', [UserController::class, 'updatePassword'])->name('users.updatePassword');
    Route::resource('tahunAkademik', TahunAkademikController::class);
    Route::resource('mahasiswa', MahasiswaController::class);
    Route::resource('programStudi', ProgramStudiController::class);
    Route::resource('pegawai', PegawaiController::class);
    Route::resource('suratAktif', SuratAktifController::class);
    Route::resource('suratAkademik', SuratAkademikController::class);
});