<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TabunganController;
use App\Http\Controllers\SetoranController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CatatCepatController;

Route::get('/', function () {
    return redirect('/login');
});

// Auth
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Semua yang butuh login
Route::middleware(['auth'])->group(function () {

    // Dashboard (pakai controller, data dinamis + catat cepat)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Kategori (sementara masih view biasa)
    Route::get('/kategori', function () {
        return view('featureview.kategori.kategori');
    })->name('kategori');

    // Tabungan
    Route::get('/tabungan', [TabunganController::class, 'index'])->name('tabungan.index');
    Route::post('/tabungan', [TabunganController::class, 'store'])->name('tabungan.store');
    Route::get('/tabungan/{id}', [TabunganController::class, 'show'])->name('tabungan.show');
    Route::post('/tabungan/{id}/setoran', [SetoranController::class, 'store'])->name('setoran.store');

    // // Catat Cepat (submit form modal)
    // Route::post('/catat-cepat', [CatatCepatController::class, 'store'])->name('catat-cepat.store');

    // // Tambahkan ini - List semua transaksi catat cepat
    // Route::get('/catat-cepat', [CatatCepatController::class, 'index'])->name('catatepat.index');
    // Route::delete('/catat-cepat/{id}', [CatatCepatController::class, 'destroy'])->name('catat-cepat.destroy');
    // // Catat Cepat
    Route::post('/catat-cepat', [CatatCepatController::class, 'store'])->name('catat-cepat.store');
    Route::get('/catat-cepat', [CatatCepatController::class, 'index'])->name('catat-cepat.index');
    Route::put('/catat-cepat/{id}', [CatatCepatController::class, 'update'])->name('catat-cepat.update');
    Route::delete('/catat-cepat/{id}', [CatatCepatController::class, 'destroy'])->name('catat-cepat.destroy');
});
