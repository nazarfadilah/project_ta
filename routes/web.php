<?php

use App\Http\Controllers\LandingPage\LandingPageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManageUserController;
use App\Http\Controllers\TentangController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\GambarLandingController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\TermsController;
use App\Http\Controllers\PrivacyController;
use Illuminate\Support\Facades\Route;

// ─── PUBLIC PAGE ROUTES ───────────────────────────────────────────
Route::get('/', [LandingPageController::class, 'beranda'])->name('home');
Route::get('/fasilitas', [LandingPageController::class, 'fasilitas'])->name('fasilitas');
Route::get('/galeri', [LandingPageController::class, 'galeri'])->name('galeri');
Route::get('/reservasi', [LandingPageController::class, 'reservasi'])->name('reservasi');
Route::get('/tentang-kami', [LandingPageController::class, 'tentang'])->name('tentang');
Route::get('/faq', [LandingPageController::class, 'faq'])->name('faq');
Route::get('/kebijakan-privasi', [LandingPageController::class, 'privacy'])->name('privacy');
Route::get('/syarat-ketentuan', [LandingPageController::class, 'terms'])->name('terms');
Route::get('/kontak-kami', [LandingPageController::class, 'kontakKami'])->name('kontak');

Route::get('/berita', [BeritaController::class, 'indexPublic'])->name('public.berita.index');
Route::get('/berita/{slug}', [BeritaController::class, 'showPublic'])->name('public.berita.show');

// ─── AUTHENTICATION ROUTES ────────────────────────────────────────

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.process');
Route::get('/captcha/generate', [AuthController::class, 'generateCaptcha'])->name('captcha.generate');
Route::get('/login/google', [AuthController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/login/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('login.google.callback');


Route::post('/logout', [AuthController::class, 'logoutUser'])->name('logout')->middleware('auth:web');
Route::post('/admin/logout', [AuthController::class, 'logoutAdmin'])->name('admin.logout')->middleware('auth:admin');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// ─── USER MANAGEMENT ROUTES ──────────────────────────────────────
Route::get('/users', [ManageUserController::class, 'index'])->name('main.users.index');
Route::get('/users/{email}/edit', [ManageUserController::class, 'edit'])->name('main.users.edit');
Route::put('/users/{email}', [ManageUserController::class, 'update'])->name('main.users.update');

// ─── LANDING PAGE MANAGEMENT ROUTES ───────────────────────────────
// Tentang
Route::prefix('landing-page/tentang')->name('main.landing.tentang.')->group(function () {
    Route::get('/', [TentangController::class, 'index'])->name('index');
    Route::get('/create', [TentangController::class, 'create'])->name('create');
    Route::post('/', [TentangController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [TentangController::class, 'edit'])->name('edit');
    Route::put('/{id}', [TentangController::class, 'update'])->name('update');
    Route::delete('/{id}', [TentangController::class, 'destroy'])->name('destroy');
});

// Galeri
Route::prefix('landing-page/galeri')->name('main.landing.galeri.')->group(function () {
    Route::get('/', [GaleriController::class, 'index'])->name('index');
    Route::get('/create', [GaleriController::class, 'create'])->name('create');
    Route::post('/', [GaleriController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [GaleriController::class, 'edit'])->name('edit');
    Route::put('/{id}', [GaleriController::class, 'update'])->name('update');
    Route::delete('/{id}', [GaleriController::class, 'destroy'])->name('destroy');
});

// Gambar Landing
Route::prefix('landing-page/gambar')->name('main.landing.gambar.')->group(function () {
    Route::get('/', [GambarLandingController::class, 'index'])->name('index');
    Route::get('/create', [GambarLandingController::class, 'create'])->name('create');
    Route::post('/', [GambarLandingController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [GambarLandingController::class, 'edit'])->name('edit');
    Route::put('/{id}', [GambarLandingController::class, 'update'])->name('update');
    Route::delete('/{id}', [GambarLandingController::class, 'destroy'])->name('destroy');
});

// FAQ
Route::prefix('landing-page/faq')->name('main.landing.faq.')->group(function () {
    Route::get('/', [FaqController::class, 'index'])->name('index');
    Route::get('/create', [FaqController::class, 'create'])->name('create');
    Route::post('/', [FaqController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [FaqController::class, 'edit'])->name('edit');
    Route::put('/{id}', [FaqController::class, 'update'])->name('update');
    Route::delete('/{id}', [FaqController::class, 'destroy'])->name('destroy');
});

// Syarat & Ketentuan
Route::prefix('landing-page/terms')->name('main.landing.terms.')->group(function () {
    Route::get('/', [TermsController::class, 'index'])->name('index');
    Route::get('/create', [TermsController::class, 'create'])->name('create');
    Route::post('/', [TermsController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [TermsController::class, 'edit'])->name('edit');
    Route::put('/{id}', [TermsController::class, 'update'])->name('update');
    Route::delete('/{id}', [TermsController::class, 'destroy'])->name('destroy');
});

// Kebijakan & Privasi
Route::prefix('landing-page/privacy')->name('main.landing.privacy.')->group(function () {
    Route::get('/', [PrivacyController::class, 'index'])->name('index');
    Route::get('/create', [PrivacyController::class, 'create'])->name('create');
    Route::post('/', [PrivacyController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [PrivacyController::class, 'edit'])->name('edit');
    Route::put('/{id}', [PrivacyController::class, 'update'])->name('update');
    Route::delete('/{id}', [PrivacyController::class, 'destroy'])->name('destroy');
});

// Berita Management
Route::prefix('berita-management')->name('main.berita.')->group(function () {
    Route::get('/', [BeritaController::class, 'index'])->name('index');
    Route::get('/create', [BeritaController::class, 'create'])->name('create');
    Route::post('/', [BeritaController::class, 'store'])->name('store');
    Route::get('/{id}', [BeritaController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [BeritaController::class, 'edit'])->name('edit');
    Route::put('/{id}', [BeritaController::class, 'update'])->name('update');
    Route::delete('/{id}', [BeritaController::class, 'destroy'])->name('destroy');
    Route::post('/{id}/approve', [BeritaController::class, 'approve'])->name('approve');
    Route::post('/{id}/reject', [BeritaController::class, 'reject'])->name('reject');
});

