<?php

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
    Route::view('/dashboard', 'admin.dashboard')->name('dashboard');
});

Route::middleware('kurir')->prefix('kurir')->name('kurir.')->group(function () {
    Route::view('/dashboard', 'kurir.dashboard')->name('dashboard');
});