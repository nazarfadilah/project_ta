<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BeritaController;
use App\Http\Controllers\Api\TentangController;
use App\Http\Controllers\Api\GambarDashboardController;
use App\Http\Controllers\Api\GaleriController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Landing Page API Routes
Route::apiResource('berita', BeritaController::class);
Route::apiResource('tentang', TentangController::class);
Route::apiResource('gambar-dashboard', GambarDashboardController::class);
Route::apiResource('galeri', GaleriController::class);


