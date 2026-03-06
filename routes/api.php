<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PeminjamanTransaksiController;
use App\Http\Controllers\Api\SaranaController;
use App\Http\Controllers\Api\RuanganController;
use App\Http\Controllers\Api\GedungController;
use App\Http\Controllers\Api\BeritaController;
use App\Http\Controllers\Api\TentangController;
use App\Http\Controllers\Api\GambarDashboardController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\DetailPeminjamanSaranaController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Custom route untuk sarana ketersediaan stok (harus sebelum apiResource)
Route::get('/sarana/ketersediaan-stok', [SaranaController::class, 'ketersediaanStok']);

// API Resources dengan apiResource
Route::apiResource('peminjaman-transaksi', PeminjamanTransaksiController::class);
Route::apiResource('sarana', SaranaController::class);
Route::apiResource('ruangan', RuanganController::class);
Route::apiResource('gedung', GedungController::class);
Route::apiResource('berita', BeritaController::class);
Route::apiResource('tentang', TentangController::class);
Route::apiResource('gambar-dashboard', GambarDashboardController::class);
Route::apiResource('users', UserController::class);
Route::apiResource('admin', AdminController::class);
Route::apiResource('detail-peminjaman-sarana', DetailPeminjamanSaranaController::class);


