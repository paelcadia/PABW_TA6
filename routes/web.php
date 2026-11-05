<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('lapor.form');
});

Route::get('/lapor', [LaporanController::class, 'createPublic'])->name('lapor.form');
Route::post('/lapor', [LaporanController::class, 'storePublic'])->name('lapor.store');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::resource('laporan', LaporanController::class);
    Route::get('/profil', [ProfileController::class, 'show'])->name('profile');
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
});
