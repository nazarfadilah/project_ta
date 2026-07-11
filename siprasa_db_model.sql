-- =============================================================================
-- SQL DDL Database Schema: Siprasa
-- Digunakan untuk Impor (Reverse Engineer) pada SAP PowerDesigner
-- DBMS Target: MySQL / MariaDB
-- Naming Convention: Diferensiasi Kolom Tumpang Tindih & Tanpa Kolom Timestamps Framework
-- =============================================================================

CREATE DATABASE IF NOT EXISTS siprasa_db;
USE siprasa_db;

-- Drop tables if exists to avoid conflicts
DROP TABLE IF EXISTS activity_log;
DROP TABLE IF EXISTS galeri;
DROP TABLE IF EXISTS gambar_dashboard;
DROP TABLE IF EXISTS tentang;
DROP TABLE IF EXISTS berita;
DROP TABLE IF EXISTS notifications;
DROP TABLE IF EXISTS unblock_requests;
DROP TABLE IF EXISTS reviews;
DROP TABLE IF EXISTS media_file;
DROP TABLE IF EXISTS detail_peminjaman_sarana;
DROP TABLE IF EXISTS sarana;
DROP TABLE IF EXISTS invoice;
DROP TABLE IF EXISTS peminjaman_transaksi;
DROP TABLE IF EXISTS guest;
DROP TABLE IF EXISTS paket_ruangan;
DROP TABLE IF EXISTS ruangan;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS role;

-- 1. Table: role
CREATE TABLE role (
    id_role INT AUTO_INCREMENT PRIMARY KEY,
    name_role VARCHAR(100) UNIQUE NOT NULL,
    description_role TEXT,
    permissions JSON
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Table: guest
CREATE TABLE guest (
    id_guest INT AUTO_INCREMENT PRIMARY KEY,
    nik CHAR(16) UNIQUE NOT NULL,
    name_guest VARCHAR(255) NOT NULL,
    gender ENUM('MALE', 'FEMALE') NOT NULL,
    phone_guest CHAR(15) NULL,
    instansi TEXT NULL,
    address TEXT NULL,
    bloodType VARCHAR(5) NULL,
    notes_guest TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Table: users
CREATE TABLE users (
    id_users BIGINT AUTO_INCREMENT PRIMARY KEY,
    google_id VARCHAR(255) NULL,
    username VARCHAR(100) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NULL,
    roleId INT NOT NULL,
    phone_users VARCHAR(20) NULL,
    profile_photo VARCHAR(255) NULL,
    guestId INT NULL,
    status_users ENUM('ACTIVE', 'INACTIVE', 'SUSPENDED', 'SUSPENDED_PERMANENT') DEFAULT 'ACTIVE',
    blocked_reason TEXT NULL,
    lastLoginAt TIMESTAMP NULL,
    CONSTRAINT fk_users_role FOREIGN KEY (roleId) REFERENCES role (id_role),
    CONSTRAINT fk_users_guest FOREIGN KEY (guestId) REFERENCES guest (id_guest) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Table: ruangan
CREATE TABLE ruangan (
    id_ruangan INT AUTO_INCREMENT PRIMARY KEY,
    gedung_id INT NULL,
    nama_ruangan VARCHAR(255) NOT NULL,
    tipe_ruangan ENUM('KAMAR_STANDAR', 'KAMAR_VIP', 'KAMAR_PREMIUM', 'AULA', 'RUANG_MEETING', 'RUANG_LAINNYA') DEFAULT 'KAMAR_STANDAR',
    lantai INT NULL,
    kapasitas INT DEFAULT 1,
    gender_policy ENUM('MALE_ONLY', 'FEMALE_ONLY', 'MIXED') NULL,
    keterangan_ruangan TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Table: paket_ruangan
CREATE TABLE paket_ruangan (
    id_paket_ruangan INT AUTO_INCREMENT PRIMARY KEY,
    ruangan_id INT NOT NULL,
    nama_paket VARCHAR(255) NOT NULL,
    tipe_paket TINYINT(1) DEFAULT 0 NOT NULL,
    durasi_paket INT NULL,
    harga DECIMAL(15,2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'IDR',
    isExclusive TINYINT(1) DEFAULT 0,
    status_paket ENUM('ACTIVE', 'INACTIVE', 'MAINTENANCE') DEFAULT 'ACTIVE',
    CONSTRAINT fk_paket_ruangan FOREIGN KEY (ruangan_id) REFERENCES ruangan (id_ruangan) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Table: peminjaman_transaksi
CREATE TABLE peminjaman_transaksi (
    id_peminjaman_transaksi INT AUTO_INCREMENT PRIMARY KEY,
    kodePeminjaman VARCHAR(50) UNIQUE NOT NULL,
    guestId INT NOT NULL,
    facilityId INT NOT NULL,
    tanggal DATE NOT NULL,
    jamMulai DATETIME NOT NULL,
    checkIn DATETIME NULL,
    checkOut DATETIME NULL,
    durasi_pt INT NULL,
    statusPeminjaman ENUM('RESERVASI', 'CHECK_IN', 'CHECK_OUT', 'BATAL', 'SELESAI') DEFAULT 'RESERVASI',
    keterangan_pt TEXT NULL,
    alasan_pembatalan TEXT NULL,
    userId BIGINT NULL,
    statusApproval ENUM('PENDING', 'APPROVED', 'REJECTED') DEFAULT 'PENDING',
    reminderSent TINYINT(1) DEFAULT 0,
    catatanApproval TEXT NULL,
    tanggalApproval DATETIME NULL,
    biayaTambahan_pt DECIMAL(15,2) DEFAULT 0.00,
    kondisiReturn ENUM('BAIK', 'RUSAK_RINGAN', 'RUSAK_BERAT', 'HILANG') NULL,
    catatanKerusakan TEXT NULL,
    estimasiDamage DECIMAL(15,2) NULL,
    CONSTRAINT fk_pt_guest FOREIGN KEY (guestId) REFERENCES guest (id_guest),
    CONSTRAINT fk_pt_user FOREIGN KEY (userId) REFERENCES users (id_users) ON DELETE SET NULL,
    CONSTRAINT fk_pt_facility FOREIGN KEY (facilityId) REFERENCES paket_ruangan (id_paket_ruangan)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Table: invoice
CREATE TABLE invoice (
    id_invoice INT AUTO_INCREMENT PRIMARY KEY,
    noInvoice VARCHAR(50) UNIQUE NOT NULL,
    peminjamanId INT UNIQUE NOT NULL,
    subtotal DECIMAL(15,2) NOT NULL,
    biayaTambahan_invoice DECIMAL(15,2) DEFAULT 0.00,
    totalHarga DECIMAL(15,2) NOT NULL,
    statusInvoice ENUM('UNPAID', 'PARTIAL', 'PAID', 'OVERDUE') DEFAULT 'UNPAID',
    status_pembayaran ENUM('BELUM_BAYAR', 'SEBAGIAN', 'LUNAS') DEFAULT 'BELUM_BAYAR',
    tglInvoice DATETIME DEFAULT CURRENT_TIMESTAMP,
    tglDueDate DATETIME NULL,
    tgl_pembayaran DATETIME NULL,
    tglPaid DATETIME NULL,
    notes_invoice TEXT NULL,
    CONSTRAINT fk_invoice_pt FOREIGN KEY (peminjamanId) REFERENCES peminjaman_transaksi (id_peminjaman_transaksi) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Table: sarana
CREATE TABLE sarana (
    id_sarana BIGINT AUTO_INCREMENT PRIMARY KEY,
    nama_sarana VARCHAR(128) NOT NULL,
    kondisi ENUM('Baik', 'Baik Sekali', 'Perlu Perbaikan') DEFAULT 'Baik',
    tgl_penerimaan DATE NOT NULL,
    stok VARCHAR(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Table: detail_peminjaman_sarana
CREATE TABLE detail_peminjaman_sarana (
    id_detail_peminjaman_sarana BIGINT AUTO_INCREMENT PRIMARY KEY,
    sarana_id BIGINT NOT NULL,
    peminjaman_id INT NOT NULL,
    jumlah VARCHAR(5) NOT NULL,
    CONSTRAINT fk_dps_sarana FOREIGN KEY (sarana_id) REFERENCES sarana (id_sarana) ON DELETE CASCADE,
    CONSTRAINT fk_dps_pt FOREIGN KEY (peminjaman_id) REFERENCES peminjaman_transaksi (id_peminjaman_transaksi) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. Table: media_file
CREATE TABLE media_file (
    id_media_file BIGINT AUTO_INCREMENT PRIMARY KEY,
    ruangan_id INT NOT NULL,
    path_media_file VARCHAR(255) NOT NULL,
    CONSTRAINT fk_media_file_ruangan FOREIGN KEY (ruangan_id) REFERENCES ruangan (id_ruangan) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. Table: reviews
CREATE TABLE reviews (
    id_reviews BIGINT AUTO_INCREMENT PRIMARY KEY,
    transaksi_id INT UNIQUE NOT NULL,
    rating TINYINT UNSIGNED NOT NULL,
    komentar VARCHAR(255) NULL,
    foto_ulasan VARCHAR(255) NULL,
    CONSTRAINT fk_reviews_pt FOREIGN KEY (transaksi_id) REFERENCES peminjaman_transaksi (id_peminjaman_transaksi) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. Table: unblock_requests
CREATE TABLE unblock_requests (
    id_unblock_requests BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT NOT NULL,
    verification_code VARCHAR(10) NULL,
    reason TEXT NULL,
    status_ur ENUM('PENDING', 'APPROVED', 'REJECTED') DEFAULT 'PENDING',
    expires_at TIMESTAMP NULL,
    CONSTRAINT fk_ur_users FOREIGN KEY (user_id) REFERENCES users (id_users) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 13. Table: notifications
CREATE TABLE notifications (
    id_notifications BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT NOT NULL,
    type VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    related_id VARCHAR(255) NULL,
    read_at TIMESTAMP NULL,
    CONSTRAINT fk_notifications_users FOREIGN KEY (user_id) REFERENCES users (id_users) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 14. Table: berita
CREATE TABLE berita (
    id_berita BIGINT AUTO_INCREMENT PRIMARY KEY,
    userId BIGINT NULL,
    judul_berita VARCHAR(128) NOT NULL,
    slug VARCHAR(128) UNIQUE NOT NULL,
    isi_berita TEXT NOT NULL,
    gambar_berita VARCHAR(255) NOT NULL,
    tanggal_publish DATE NOT NULL,
    status_berita ENUM('approved', 'draft', 'rejected') DEFAULT 'draft',
    keterangan_berita TEXT NULL,
    CONSTRAINT fk_berita_users FOREIGN KEY (userId) REFERENCES users (id_users) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 15. Table: tentang
CREATE TABLE tentang (
    id_tentang BIGINT AUTO_INCREMENT PRIMARY KEY,
    key_tentang VARCHAR(255) NOT NULL,
    key2_tentang VARCHAR(255) NULL,
    value_tentang TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 16. Table: gambar_dashboard
CREATE TABLE gambar_dashboard (
    id_gambar_dashboard BIGINT AUTO_INCREMENT PRIMARY KEY,
    posisi TINYINT NOT NULL,
    path_gambar_dashboard VARCHAR(255) NOT NULL,
    waktu_upload_gambar_dashboard TIMESTAMP NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 17. Table: galeri
CREATE TABLE galeri (
    id_galeri BIGINT AUTO_INCREMENT PRIMARY KEY,
    kategori_galeri ENUM('pengapian', 'moshulla', 'aula', 'gedung') NOT NULL,
    judul_galeri VARCHAR(128) NOT NULL,
    isi_galeri TEXT NOT NULL,
    gambar_galeri VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 18. Table: activity_log
CREATE TABLE activity_log (
    id_activity_log INT AUTO_INCREMENT PRIMARY KEY,
    userId BIGINT NULL,
    action VARCHAR(100) NOT NULL,
    tabelNama VARCHAR(100) NOT NULL,
    recordId VARCHAR(100) NOT NULL,
    detailPerubahan JSON NULL,
    ipAddress VARCHAR(50) NULL,
    CONSTRAINT fk_al_users FOREIGN KEY (userId) REFERENCES users (id_users) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
