<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/customer', [HomeController::class, 'customer'])->name('customer.dashboard');
Route::get('/customer/tracking', [CustomerController::class, 'tracking'])->name('customer.tracking');
Route::post('/customer/tracking', [CustomerController::class, 'cariTracking'])->name('customer.tracking.search');

Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.process');

Route::get('/kurir/login', [AuthController::class, 'showKurirLogin'])->name('kurir.login');
Route::post('/kurir/login', [AuthController::class, 'kurirLogin'])->name('kurir.login.process');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/kurir', [AdminController::class, 'kurir'])->name('kurir.index');
    Route::get('/kurir/tambah', [AdminController::class, 'kurirCreate'])->name('kurir.create');
    Route::post('/kurir', [AdminController::class, 'kurirStore'])->name('kurir.store');

    Route::get('/tarif', [AdminController::class, 'tarif'])->name('tarif.index');
    Route::get('/pesanan', [AdminController::class, 'pesanan'])->name('pesanan.index');
});

Route::middleware('kurir')->prefix('kurir')->name('kurir.')->group(function () {
    Route::view('/dashboard', 'kurir.dashboard')->name('dashboard');
});
