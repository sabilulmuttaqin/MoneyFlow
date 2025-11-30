<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TabunganController;
use App\Http\Controllers\SetoranController;
use App\Http\Controllers\AnggaranController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/kategori', function () {
    return view('featureview.kategori.kategori');
})->middleware('auth')->name('kategori');

Route::get('/dashboard', function () {
    return view('featureview.home.home');
})->middleware('auth')->name('dashboard');

// =====================
//      TABUNGAN
// =====================
Route::middleware(['auth'])->group(function () {

    // halaman tabungan
    Route::get('/tabungan', [TabunganController::class, 'index'])->name('tabungan.index');
    Route::post('/tabungan', [TabunganController::class, 'store'])->name('tabungan.store');
    
    // detail tabungan
    Route::get('/tabungan/{id}', [TabunganController::class, 'show'])->name('tabungan.show');

    // tambah setoran
    Route::post('/tabungan/{id}/setoran', [SetoranController::class, 'store'])->name('setoran.store');
});

// =====================
//      ANGGARAN
// =====================
Route::resource('anggaran', AnggaranController::class)->middleware('auth');
