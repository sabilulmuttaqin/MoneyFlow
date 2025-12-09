<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TabunganController;
use App\Http\Controllers\SetoranController;
use App\Http\Controllers\AnggaranController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CatatCepatController;
use App\Http\Controllers\RingkasanController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => redirect('/login'));

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| Protected Routes (Auth Required)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // =====================
    //      DASHBOARD
    // =====================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // =====================
    //      TABUNGAN
    // =====================
    Route::get('/tabungan', [TabunganController::class, 'index'])->name('tabungan.index');
    Route::post('/tabungan', [TabunganController::class, 'store'])->name('tabungan.store');
    Route::get('/tabungan/{id}', [TabunganController::class, 'show'])->name('tabungan.show');
    Route::post('/tabungan/{id}/setoran', [SetoranController::class, 'store'])->name('setoran.store');


    // =====================
    //      ANGGARAN
    // =====================
 // =====================
//      ANGGARAN
// =====================
// Route::resource('anggaran', AnggaranController::class); // Hapus ini
    Route::get('/anggaran', [AnggaranController::class, 'index'])->name('anggaran.index');
    Route::post('/anggaran', [AnggaranController::class, 'storeOrUpdate'])->name('anggaran.storeOrUpdate');
    // =====================
    //      CATEGORY
    // =====================
    Route::get('/kategori', [CategoryController::class, 'index'])->name('kategori.index');
    Route::get('/kategori/create', [CategoryController::class, 'create'])->name('kategori.create');
    Route::post('/kategori', [CategoryController::class, 'store'])->name('kategori.store');
    Route::get('/kategori/{category}/edit', [CategoryController::class, 'edit'])->name('kategori.edit');
    Route::put('/kategori/{category}', [CategoryController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{category}', [CategoryController::class, 'destroy'])->name('kategori.destroy');
    Route::get('/kategori/{category}', [CategoryController::class, 'show'])->name('kategori.show');

    // =====================
    //      CATAT CEPAT
    // =====================
    Route::post('/catatcepat', [CatatCepatController::class, 'store'])->name('catatcepat.store');
    Route::get('/catatcepat/{id}/edit', [CatatCepatController::class, 'edit'])->name('catatcepat.edit');
    Route::put('/catatcepat/{id}', [CatatCepatController::class, 'update'])->name('catatcepat.update');
    Route::delete('/catatcepat/{id}', [CatatCepatController::class, 'destroy'])->name('catatcepat.delete');


    Route::get('/ringkasan-bulanan', [RingkasanController::class, 'ringkasanBulanan'])->name('ringkasan.bulanan');
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
