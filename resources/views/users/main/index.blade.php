@extends('users.layout.app')

@section('title', 'Dashboard Pengguna')

@section('css')
<style>
    .welcome-banner {
        background: linear-gradient(135deg, #C9A961 0%, #A48135 100%);
        border-radius: 16px;
        color: white;
        padding: 40px;
        margin-bottom: 35px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(201, 169, 97, 0.2);
    }

    .welcome-banner::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 400px;
        height: 400px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.08);
        z-index: 1;
    }

    .welcome-banner::after {
        content: '';
        position: absolute;
        bottom: -30%;
        right: 10%;
        width: 250px;
        height: 250px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.05);
        z-index: 1;
    }

    .welcome-content {
        position: relative;
        z-index: 2;
        max-width: 70%;
    }

    .welcome-title {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 12px;
        letter-spacing: -0.5px;
    }

    .welcome-subtitle {
        font-size: 16px;
        opacity: 0.95;
        line-height: 1.6;
        font-weight: 400;
    }

    .stat-card {
        border: none;
        border-radius: 16px;
        color: white;
        min-height: 160px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        text-decoration: none !important;
        display: block;
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 16px 30px rgba(0, 0, 0, 0.15);
    }

    .stat-card::after {
        content: '';
        position: absolute;
        right: -15px;
        bottom: -15px;
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        font-size: 110px;
        opacity: 0.12;
        transition: all 0.3s ease;
    }

    .card-gedung::after { content: '\f1ad'; }
    .card-ruangan::after { content: '\f52b'; }
    .card-sarana::after { content: '\f7d9'; }

    .stat-card:hover::after {
        transform: scale(1.1) rotate(-10deg);
        opacity: 0.18;
    }

    .stat-body {
        padding: 28px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        z-index: 2;
    }

    .stat-number {
        font-size: 44px;
        font-weight: 800;
        line-height: 1;
        margin-bottom: 8px;
        letter-spacing: -1px;
    }

    .stat-label {
        font-size: 15px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        opacity: 0.9;
    }

    .action-card {
        background: #ffffff;
        border: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 16px;
        padding: 35px;
        height: 100%;
        transition: all 0.3s ease;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.03);
    }

    .action-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.08);
        border-color: rgba(201, 169, 97, 0.3);
    }

    .action-icon {
        width: 60px;
        height: 60px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        margin-bottom: 25px;
        transition: all 0.3s ease;
    }

    .action-card:hover .action-icon {
        transform: scale(1.1);
    }

    .icon-profile {
        background-color: rgba(201, 169, 97, 0.12);
        color: #A48135;
    }

    .icon-reservasi {
        background-color: rgba(40, 167, 69, 0.12);
        color: #28a745;
    }

    .icon-ketersediaan {
        background-color: rgba(0, 123, 255, 0.12);
        color: #007bff;
    }

    .action-title {
        font-size: 20px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 12px;
    }

    .action-desc {
        font-size: 14px;
        color: #7f8c8d;
        line-height: 1.6;
        margin-bottom: 25px;
        min-height: 66px;
    }

    .btn-action-card {
        padding: 10px 22px;
        font-weight: 600;
        font-size: 14px;
        border-radius: 8px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-profile {
        background-color: #C9A961;
        color: white;
    }

    .btn-profile:hover {
        background-color: #A48135;
        color: white;
        box-shadow: 0 4px 12px rgba(201, 169, 97, 0.3);
    }

    .btn-reservasi {
        background-color: #28a745;
        color: white;
    }

    .btn-reservasi:hover {
        background-color: #218838;
        color: white;
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
    }

    .btn-ketersediaan {
        background-color: #007bff;
        color: white;
    }

    .btn-ketersediaan:hover {
        background-color: #0056b3;
        color: white;
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4" style="padding-left: 30px; padding-right: 30px;">
    
    <!-- Welcome Banner -->
    <div class="welcome-banner">
        <div class="welcome-content">
            <h1 class="welcome-title">Selamat Datang, {{ Auth::user()->name ?? 'Pengguna' }}!</h1>
            <p class="welcome-subtitle">
                Di portal layanan SIPRASA (Sistem Informasi Peminjaman Ruangan & Sarana). Kami berkomitmen memberikan kemudahan bagi Anda untuk mengelola profil pribadi, menelusuri ketersediaan gedung, ruangan, sarana pendukung, hingga melakukan pengajuan reservasi dengan cepat dan akurat.
            </p>
        </div>
    </div>

    <!-- Stat Cards Row -->
    <div class="row g-4 mb-5">
        {{-- Card Gedung - DISABLED
        <div class="col-md-4">
            <a href="{{ route('users.main.gedung.index') }}" class="stat-card card-gedung" style="background: linear-gradient(135deg, #3A6073 0%, #16222F 100%);">
                <div class="stat-body">
                    <div>
                        <div class="stat-number">{{ $buildings }}</div>
                        <div class="stat-label">Jumlah Gedung</div>
                    </div>
                    <div style="font-size: 13px; opacity: 0.8; font-weight: 500;">
                        Lihat Daftar Gedung <i class="fas fa-arrow-right ms-1"></i>
                    </div>
                </div>
            </a>
        </div>
        --}}

        <!-- Card Ruangan -->
        <div class="col-md-6">
            <a href="{{ route('users.main.ruangan.index') }}" class="stat-card card-ruangan" style="background: linear-gradient(135deg, #C9A961 0%, #856404 100%);">
                <div class="stat-body">
                    <div>
                        <div class="stat-number">{{ $rooms }}</div>
                        <div class="stat-label">Jumlah Ruangan</div>
                    </div>
                    <div style="font-size: 13px; opacity: 0.8; font-weight: 500;">
                        Lihat Daftar Ruangan <i class="fas fa-arrow-right ms-1"></i>
                    </div>
                </div>
            </a>
        </div>

        <!-- Card Sarana -->
        <div class="col-md-6">
            <a href="{{ route('users.main.sarana.index') }}" class="stat-card card-sarana" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                <div class="stat-body">
                    <div>
                        <div class="stat-number">{{ $saranas }}</div>
                        <div class="stat-label">Jumlah Sarana</div>
                    </div>
                    <div style="font-size: 13px; opacity: 0.8; font-weight: 500;">
                        Lihat Daftar Sarana <i class="fas fa-arrow-right ms-1"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Action Cards Row -->
    <div class="row g-4">
        <!-- Action: Kelola Profil -->
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="action-card">
                <div class="action-icon icon-profile">
                    <i class="fas fa-user-gear"></i>
                </div>
                <h4 class="action-title">Kelola Profil Peminjam</h4>
                <p class="action-desc">
                    Lengkapi atau perbarui informasi data diri Anda seperti Nomor NIK, Nama Lengkap, Alamat, Golongan Darah, hingga pengaturan kata sandi baru untuk menjaga keamanan akun Anda.
                </p>
                <div>
                    <a href="{{ route('users.profil.edit') }}" class="btn-action-card btn-profile">
                        <i class="fas fa-sliders me-1"></i> Pengaturan Profil Saya
                    </a>
                </div>
            </div>
        </div>

        <!-- Action: Reservasi Ruangan -->
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="action-card">
                <div class="action-icon icon-reservasi">
                    <i class="fas fa-calendar-days"></i>
                </div>
                <h4 class="action-title">Mulai Reservasi Ruangan</h4>
                <p class="action-desc">
                    Jelajahi berbagai tipe ruangan yang ditawarkan Asrama Haji, periksa kapasitas peserta, fasilitas yang ada di setiap ruangan, lalu buat pengajuan peminjaman baru secara instan.
                </p>
                <div>
                    <a href="{{ route('users.main.ruangan.index') }}" class="btn-action-card btn-reservasi">
                        <i class="fas fa-plus me-1"></i> Cari & Pesan Ruangan
                    </a>
                </div>
            </div>
        </div>

        <!-- Action: Cek Ketersediaan -->
        <div class="col-lg-4 col-md-12 col-sm-12">
            <div class="action-card">
                <div class="action-icon icon-ketersediaan">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h4 class="action-title">Cek Ketersediaan Ruangan</h4>
                <p class="action-desc">
                    Periksa ketersediaan ruangan yang kosong pada tanggal penyewaan pilihan Anda berdasarkan kategori tertentu untuk merencanakan acara Anda dengan tepat.
                </p>
                <div>
                    <a href="{{ route('users.main.ruangan.ketersediaan') }}" class="btn-action-card btn-ketersediaan">
                        <i class="fas fa-magnifying-glass me-1"></i> Cek Ketersediaan Ruangan
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
