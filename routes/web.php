<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KurirController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/customer', [HomeController::class, 'customer'])->name('customer.dashboard');
Route::get('/customer/cek-ongkir', [CustomerController::class, 'cekOngkir'])->name('customer.cek-ongkir');
Route::post('/customer/cek-ongkir', [CustomerController::class, 'prosesCekOngkir'])->name('customer.cek-ongkir.process');
Route::get('/customer/pesanan/buat', [CustomerController::class, 'buatPesanan'])->name('customer.pesanan.create');
Route::post('/customer/pesanan', [CustomerController::class, 'simpanPesanan'])->name('customer.pesanan.store');
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
    Route::delete('/kurir/{id}', [AdminController::class, 'kurirDestroy'])->name('kurir.destroy');
    Route::get('/kurir-riwayat-hapus', [AdminController::class, 'riwayatHapusKurir'])->name('kurir.riwayat-hapus');

    Route::get('/tarif', [AdminController::class, 'tarif'])->name('tarif.index');
    Route::get('/tarif/tambah', [AdminController::class, 'tarifCreate'])->name('tarif.create');
    Route::post('/tarif', [AdminController::class, 'tarifStore'])->name('tarif.store');
    Route::delete('/tarif/{id}', [AdminController::class, 'tarifDestroy'])->name('tarif.destroy');

    Route::get('/pesanan', [AdminController::class, 'pesanan'])->name('pesanan.index');
});

Route::middleware('kurir')->prefix('kurir')->name('kurir.')->group(function () {
    Route::get('/dashboard', [KurirController::class, 'dashboard'])->name('dashboard');
    Route::get('/tugas-baru', [KurirController::class, 'tugasBaru'])->name('tugas-baru');
    Route::post('/tugas-baru/{id}/ambil', [KurirController::class, 'ambilPesanan'])->name('ambil-pesanan');
    Route::get('/pesanan-saya', [KurirController::class, 'pesananSaya'])->name('pesanan-saya');
    Route::patch('/pesanan-saya/{id}/status', [KurirController::class, 'updateStatus'])->name('update-status');
});
