<?php

use App\Http\Controllers\Api\MobileAuthController;
use App\Http\Controllers\Api\MobileCustomerController;
use App\Http\Controllers\Api\MobileKurirController;
use Illuminate\Support\Facades\Route;

Route::prefix('mobile')->group(function () {
    Route::post('/login', [MobileAuthController::class, 'login']);
    Route::post('/register/customer', [MobileAuthController::class, 'registerCustomer']);
    Route::post('/register/kurir', [MobileAuthController::class, 'registerKurir']);

    // Tracking dibuat publik seperti versi web lama.
    Route::get('/tracking/{kode_resi}', [MobileCustomerController::class, 'tracking']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [MobileAuthController::class, 'me']);
        Route::post('/logout', [MobileAuthController::class, 'logout']);

        Route::prefix('customer')->group(function () {
            Route::get('/dashboard', [MobileCustomerController::class, 'dashboard']);
            Route::get('/tarif/options', [MobileCustomerController::class, 'opsiTarif']);
            Route::post('/cek-ongkir', [MobileCustomerController::class, 'cekOngkir']);
            Route::post('/pesanan', [MobileCustomerController::class, 'buatPesanan']);
            Route::get('/riwayat-pesanan', [MobileCustomerController::class, 'riwayatPesanan']);
        });

        Route::prefix('kurir')->group(function () {
            Route::get('/dashboard', [MobileKurirController::class, 'dashboard']);
            Route::get('/tugas-baru', [MobileKurirController::class, 'tugasBaru']);
            Route::post('/tugas-baru/{id}/ambil', [MobileKurirController::class, 'ambilPesanan']);
            Route::get('/pesanan-saya', [MobileKurirController::class, 'pesananSaya']);
            Route::patch('/pesanan-saya/{id}/status', [MobileKurirController::class, 'updateStatus']);
        });
    });
});
