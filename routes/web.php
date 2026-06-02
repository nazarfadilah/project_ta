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
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\UsersRuanganController;
use App\Http\Controllers\UsersReservasiController;
use App\Http\Controllers\UsersInvoiceController;
use App\Http\Controllers\UsersProfilController;
use App\Http\Controllers\UsersGedungController;
use App\Http\Controllers\UsersSaranaController;
use App\Http\Controllers\SaranaController;
use App\Http\Controllers\SaranaAvailabilityController;
use App\Http\Controllers\PeminjamanSaranaController;
use App\Http\Controllers\GedungController;
use App\Http\Controllers\AdminPeminjamanTransaksiController;
use App\Http\Controllers\AdminInvoiceController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PaketRuanganController;
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

// ─── ADMIN ROUTES ────────────────────────────────────────────────
Route::middleware('auth:admin')->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'adminIndex'])->name('admin.dashboard');

    // User Management
    Route::prefix('users')->name('main.users.')->group(function () {
        Route::get('/', [ManageUserController::class, 'index'])->name('index');
        Route::get('/{email}/edit', [ManageUserController::class, 'edit'])->name('edit');
        Route::put('/{email}', [ManageUserController::class, 'update'])->name('update');
    });

    // Guest (Tamu) Management
    Route::prefix('tamu-management')->name('main.tamu.')->group(function () {
        Route::get('/', [App\Http\Controllers\GuestController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\GuestController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\GuestController::class, 'store'])->name('store');
        Route::get('/{id}', [App\Http\Controllers\GuestController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [App\Http\Controllers\GuestController::class, 'edit'])->name('edit');
        Route::put('/{id}', [App\Http\Controllers\GuestController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\GuestController::class, 'destroy'])->name('destroy');
    });

    // Landing Page Management
    Route::prefix('landing-page/tentang')->name('main.landing.tentang.')->group(function () {
        Route::get('/', [TentangController::class, 'index'])->name('index');
        Route::get('/create', [TentangController::class, 'create'])->name('create');
        Route::post('/', [TentangController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [TentangController::class, 'edit'])->name('edit');
        Route::put('/{id}', [TentangController::class, 'update'])->name('update');
        Route::delete('/{id}', [TentangController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('landing-page/galeri')->name('main.landing.galeri.')->group(function () {
        Route::get('/', [GaleriController::class, 'index'])->name('index');
        Route::get('/create', [GaleriController::class, 'create'])->name('create');
        Route::post('/', [GaleriController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [GaleriController::class, 'edit'])->name('edit');
        Route::put('/{id}', [GaleriController::class, 'update'])->name('update');
        Route::delete('/{id}', [GaleriController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('landing-page/gambar')->name('main.landing.gambar.')->group(function () {
        Route::get('/', [GambarLandingController::class, 'index'])->name('index');
        Route::get('/create', [GambarLandingController::class, 'create'])->name('create');
        Route::post('/', [GambarLandingController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [GambarLandingController::class, 'edit'])->name('edit');
        Route::put('/{id}', [GambarLandingController::class, 'update'])->name('update');
        Route::delete('/{id}', [GambarLandingController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('landing-page/faq')->name('main.landing.faq.')->group(function () {
        Route::get('/', [FaqController::class, 'index'])->name('index');
        Route::get('/create', [FaqController::class, 'create'])->name('create');
        Route::post('/', [FaqController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [FaqController::class, 'edit'])->name('edit');
        Route::put('/{id}', [FaqController::class, 'update'])->name('update');
        Route::delete('/{id}', [FaqController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('landing-page/terms')->name('main.landing.terms.')->group(function () {
        Route::get('/', [TermsController::class, 'index'])->name('index');
        Route::get('/create', [TermsController::class, 'create'])->name('create');
        Route::post('/', [TermsController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [TermsController::class, 'edit'])->name('edit');
        Route::put('/{id}', [TermsController::class, 'update'])->name('update');
        Route::delete('/{id}', [TermsController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('landing-page/privacy')->name('main.landing.privacy.')->group(function () {
        Route::get('/', [PrivacyController::class, 'index'])->name('index');
        Route::get('/create', [PrivacyController::class, 'create'])->name('create');
        Route::post('/', [PrivacyController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PrivacyController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PrivacyController::class, 'update'])->name('update');
        Route::delete('/{id}', [PrivacyController::class, 'destroy'])->name('destroy');
    });

    // Data Master Routes
    Route::prefix('data-master/ruangan')->name('main.ruangan.')->group(function () {
        Route::get('/', [RuanganController::class, 'index'])->name('index');
        Route::get('/create', [RuanganController::class, 'create'])->name('create');
        Route::post('/', [RuanganController::class, 'store'])->name('store');
        Route::get('/{id}', [RuanganController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [RuanganController::class, 'edit'])->name('edit');
        Route::put('/{id}', [RuanganController::class, 'update'])->name('update');
        Route::delete('/{id}', [RuanganController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('data-master/sarana')->name('main.sarana.')->group(function () {
        Route::get('/', [SaranaController::class, 'index'])->name('index');
        Route::get('/create', [SaranaController::class, 'create'])->name('create');
        Route::post('/', [SaranaController::class, 'store'])->name('store');
        Route::get('/{id}', [SaranaController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [SaranaController::class, 'edit'])->name('edit');
        Route::put('/{id}', [SaranaController::class, 'update'])->name('update');
        Route::delete('/{id}', [SaranaController::class, 'destroy'])->name('destroy');
    });

    // Sarana Availability Routes
    Route::prefix('sarana/availability')->name('main.sarana.availability.')->group(function () {
        Route::get('/check', [SaranaAvailabilityController::class, 'check'])->name('check');
        Route::get('/list', [SaranaAvailabilityController::class, 'listAvailable'])->name('list');
    });

    // Peminjaman Sarana Routes
    Route::prefix('peminjaman-sarana')->name('main.peminjaman_sarana.')->group(function () {
        Route::get('/', [PeminjamanSaranaController::class, 'index'])->name('index');
        Route::get('/create', [PeminjamanSaranaController::class, 'create'])->name('create');
        Route::post('/', [PeminjamanSaranaController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PeminjamanSaranaController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PeminjamanSaranaController::class, 'update'])->name('update');
        Route::delete('/{id}', [PeminjamanSaranaController::class, 'destroy'])->name('destroy');
    });

    // Transaksi - Peminjaman Routes
    Route::prefix('transaksi/peminjaman')->name('main.transaksi.peminjaman.')->group(function () {
        Route::get('/', [AdminPeminjamanTransaksiController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminPeminjamanTransaksiController::class, 'show'])->name('show');
        Route::post('/{id}/approve', [AdminPeminjamanTransaksiController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [AdminPeminjamanTransaksiController::class, 'reject'])->name('reject');
        Route::post('/{id}/check-in', [AdminPeminjamanTransaksiController::class, 'checkIn'])->name('checkin');
        Route::post('/{id}/check-out', [AdminPeminjamanTransaksiController::class, 'checkOut'])->name('checkout');
    });

    // Transaksi - Invoice Routes
    Route::prefix('transaksi/invoice')->name('main.transaksi.invoice.')->group(function () {
        Route::get('/{peminjaman_id}', [AdminInvoiceController::class, 'show'])->name('show');
    });

    Route::prefix('data-master/gedung')->name('main.gedung.')->group(function () {
        Route::get('/', [GedungController::class, 'index'])->name('index');
        Route::get('/create', [GedungController::class, 'create'])->name('create');
        Route::post('/', [GedungController::class, 'store'])->name('store');
        Route::get('/{id}', [GedungController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [GedungController::class, 'edit'])->name('edit');
        Route::put('/{id}', [GedungController::class, 'update'])->name('update');
        Route::delete('/{id}', [GedungController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('data-master/paket-ruangan')->name('main.paket_ruangan.')->group(function () {
        Route::get('/', [PaketRuanganController::class, 'index'])->name('index');
        Route::get('/create', [PaketRuanganController::class, 'create'])->name('create');
        Route::post('/', [PaketRuanganController::class, 'store'])->name('store');
        Route::get('/{id}', [PaketRuanganController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [PaketRuanganController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PaketRuanganController::class, 'update'])->name('update');
        Route::delete('/{id}', [PaketRuanganController::class, 'destroy'])->name('destroy');
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

    // Laporan Management
    Route::prefix('laporan')->name('main.laporan.')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        Route::get('/export', [LaporanController::class, 'export'])->name('export');
    });
});

// ─── USERS ROUTES ────────────────────────────────────────────────
Route::middleware('auth:web')->prefix('users')->name('users.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'usersindex'])->name('dashboard');

    // Profil Saya (My Profile)
    Route::get('/profil', [UsersProfilController::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [UsersProfilController::class, 'update'])->name('profil.update');

    // Users Main Routes - Ruangan & Reservasi
    Route::prefix('main')->name('main.')->group(function () {
        // Ruangan routes (view-only)
        Route::prefix('ruangan')->name('ruangan.')->group(function () {
            Route::get('/', [UsersRuanganController::class, 'index'])->name('index');
            Route::get('/{slug}', [UsersRuanganController::class, 'show'])->name('show');
        });

        // Gedung routes (view-only)
        Route::prefix('gedung')->name('gedung.')->group(function () {
            Route::get('/', [UsersGedungController::class, 'index'])->name('index');
        });

        // Sarana routes (view-only)
        Route::prefix('sarana')->name('sarana.')->group(function () {
            Route::get('/', [UsersSaranaController::class, 'index'])->name('index');
        });

        // Reservasi routes
        Route::prefix('reservasi')->name('reservasi.')->group(function () {
            Route::get('/', [UsersReservasiController::class, 'index'])->name('index');
            Route::post('/create', [UsersReservasiController::class, 'create'])->name('create');
            Route::post('/', [UsersReservasiController::class, 'store'])->name('store');
            Route::get('/{id}', [UsersReservasiController::class, 'show'])->name('show');
        });

        // Invoice routes
        Route::prefix('invoice')->name('invoice.')->group(function () {
            Route::get('/{peminjaman_id}', [UsersInvoiceController::class, 'index'])->name('index');
        });
    });
});

// Redirect old dashboard to users dashboard
Route::get('/dashboard', function () {
    return redirect('/users/dashboard');
})->name('dashboard');

