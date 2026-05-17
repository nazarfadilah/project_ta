-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Waktu pembuatan: 12 Bulan Mei 2026 pada 03.39
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `asrama_haji_peminjaman`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `activity_log`
--

CREATE TABLE `activity_log` (
  `id` int(11) NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `tabelNama` varchar(100) NOT NULL,
  `recordId` varchar(100) NOT NULL,
  `detailPerubahan` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`detailPerubahan`)),
  `ipAddress` varchar(50) DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel audit trail untuk tracking setiap perubahan';

--
-- Dumping data untuk tabel `activity_log`
--

INSERT INTO `activity_log` (`id`, `userId`, `action`, `tabelNama`, `recordId`, `detailPerubahan`, `ipAddress`, `createdAt`) VALUES
(1, 1, 'CREATE', 'peminjaman_transaksi', '1', '{\"kodePeminjaman\":\"PJM/2026/0001\",\"guestId\":1,\"facilityId\":1}', '192.168.1.100', '2026-04-20 23:53:03'),
(2, 2, 'UPDATE', 'peminjaman_transaksi', '1', '{\"statusPeminjaman\":\"RESERVASI -> CHECK_IN\",\"jamMulai\":\"2026-04-20 14:30:00\"}', '192.168.1.101', '2026-04-20 23:53:03'),
(3, 1, 'CREATE', 'invoice', '3', '{\"noInvoice\":\"INV/2026/0003\",\"peminjamanId\":3,\"totalHarga\":800000.00}', '192.168.1.100', '2026-04-20 23:53:03');

-- --------------------------------------------------------

--
-- Struktur dari tabel `berita`
--

CREATE TABLE `berita` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `judul` varchar(128) NOT NULL,
  `slug` varchar(64) NOT NULL,
  `isi` text NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `tanggal_publish` date NOT NULL,
  `status` enum('approved','draft','rejected') NOT NULL DEFAULT 'draft',
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `berita`
--

INSERT INTO `berita` (`id`, `user_id`, `judul`, `slug`, `isi`, `gambar`, `tanggal_publish`, `status`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 1, 'Pelayanan Pendaftaran Haji Dibuka', 'pelayanan-pendaftaran-haji-dibuka', 'Kabar gembira! Asrama Haji Landasan Ulin telah resmi membuka pelayanan pendaftaran calon jamaah haji untuk tahun 2026. Tim kami siap melayani Anda dengan sepenuh hati untuk mewujudkan impian ibadah haji Anda.', 'https://via.placeholder.com/500x300?text=Pelayanan+Pendaftaran', '2026-04-15', 'approved', 'Berita resmi tentang pembukaan pendaftaran haji', NULL, NULL),
(2, 1, 'Tips Persiapan Ibadah Haji yang Sempurna', 'tips-persiapan-ibadah-haji', 'Persiapan ibadah haji memerlukan perencanaan yang matang baik dari segi fisik, mental, maupun spiritual. Dalam artikel ini kami bagikan beberapa tips untuk membantu Anda mempersiapkan diri dengan baik sebelum berangkat ke Tanah Suci.', 'https://via.placeholder.com/500x300?text=Tips+Persiapan', '2026-04-18', 'approved', 'Artikel informatif tentang persiapan haji', NULL, NULL),
(3, 1, 'Pemberitahuan: Penutupan Kantor Hari Libur Nasional', 'penutupan-kantor-hari-libur', 'Untuk memperingati Hari Raya Idul Fitri, Asrama Haji Landasan Ulin akan ditutup dari tanggal 1-5 Mei 2026. Pelayanan akan kembali normal pada tanggal 6 Mei 2026. Terima kasih atas pengertian Anda.', 'https://via.placeholder.com/500x300?text=Pemberitahuan+Libur', '2026-04-20', 'approved', 'Pemberitahuan resmi tutup kantor', NULL, NULL),
(4, 1, 'Testimoni Calon Jamaah Haji 2025', 'testimoni-calon-jamaah-haji-2025', 'Banyak calon jamaah haji yang telah merasakan manfaat fasilitas dan pelayanan kami. Berikut adalah beberapa testimoni positif dari mereka yang telah mendaftar dan bersiap untuk menunaikan ibadah haji tahun lalu.', 'https://via.placeholder.com/500x300?text=Testimoni+Jamaah', '2026-04-10', 'approved', 'Testimoni dari calon jamaah haji sebelumnya', NULL, NULL),
(5, 1, 'Syarat dan Ketentuan Pendaftaran Calon Jamaah Haji', 'syarat-ketentuan-pendaftaran-haji', 'Sebelum melakukan pendaftaran, pastikan Anda telah memenuhi semua syarat dan ketentuan yang berlaku. Dokumentasi yang lengkap dan akurat sangat diperlukan untuk memproses pendaftaran Anda dengan lancar.', 'https://via.placeholder.com/500x300?text=Syarat+Ketentuan', '2026-04-12', 'approved', 'Informasi syarat dan ketentuan pendaftaran', NULL, NULL),
(6, 1, 'Program Edukasi Pramanifestasi Calon Jamaah Haji', 'program-edukasi-pramanifestasi-haji', 'Kami menyediakan program edukasi lengkap untuk mempersiapkan Anda secara holistik sebelum berangkat haji. Program ini mencakup materi tentang tata cara ibadah, kesehatan, administrasi, hingga persiapan mental dan spiritual.', 'https://via.placeholder.com/500x300?text=Program+Edukasi', '2026-04-22', 'draft', 'Program edukasi untuk calon jamaah haji', NULL, NULL),
(7, 1, 'Fasilitas Baru di Asrama Haji Landasan Ulin', 'fasilitas-baru-asrama-haji', 'Dalam upaya meningkatkan kualitas pelayanan, kami telah menambahkan beberapa fasilitas baru termasuk ruang tunggu yang lebih nyaman, sistem informasi online, dan layanan konsultasi kesehatan gratis.', 'https://via.placeholder.com/500x300?text=Fasilitas+Baru', '2026-04-25', 'draft', 'Informasi fasilitas baru yang tersedia', NULL, NULL),
(8, 1, 'Panduan Pembayaran BPIH Calon Jamaah Haji', 'panduan-pembayaran-bpih-haji', 'BPIH (Biaya Penyelenggaraan Ibadah Haji) adalah biaya wajib yang harus dibayarkan oleh setiap calon jamaah haji. Pelajari panduan lengkap tentang tata cara pembayaran BPIH melalui artikel ini.', 'https://via.placeholder.com/500x300?text=Panduan+Pembayaran', '2026-04-28', 'draft', 'Panduan pembayaran BPIH', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_peminjaman_sarana`
--

CREATE TABLE `detail_peminjaman_sarana` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sarana_id` bigint(20) UNSIGNED NOT NULL,
  `peminjaman_id` int(11) NOT NULL,
  `jumlah` varchar(5) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel detail sarana per peminjaman';

--
-- Dumping data untuk tabel `detail_peminjaman_sarana`
--

INSERT INTO `detail_peminjaman_sarana` (`id`, `sarana_id`, `peminjaman_id`, `jumlah`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2', '2026-04-20 23:53:03', '2026-04-20 23:53:03'),
(2, 3, 2, '1', '2026-04-20 23:53:03', '2026-04-20 23:53:03'),
(3, 2, 3, '5', '2026-04-20 23:53:03', '2026-04-20 23:53:03');

-- --------------------------------------------------------

--
-- Struktur dari tabel `galeri`
--

CREATE TABLE `galeri` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kategori` enum('pengapian','moshulla','aula','gedung') NOT NULL,
  `judul` varchar(128) NOT NULL,
  `isi` text NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `galeri`
--

INSERT INTO `galeri` (`id`, `kategori`, `judul`, `isi`, `gambar`, `created_at`) VALUES
(1, 'pengapian', 'Ruang Pengapian Utama', 'Ruang pengapian utama yang luas dan modern dengan kapasitas mengakomodasi ratusan jamaah haji. Dilengkapi dengan sistem ventilasi dan pencahayaan yang baik untuk kenyamanan beribadah.', 'https://via.placeholder.com/400x300?text=Ruang+Pengapian+Utama', '2026-04-01 16:41:17'),
(2, 'pengapian', 'Pengapian untuk Wanita', 'Ruang pengapian khusus untuk calon jamaah haji wanita yang dirancang dengan mempertimbangkan privasi dan kenyamanan maksimal.', 'https://via.placeholder.com/400x300?text=Pengapian+untuk+Wanita', '2026-03-15 16:41:17'),
(3, 'pengapian', 'Area Wudhu Pengapian', 'Area wudhu yang luas dan bersih dengan fasilitas lengkap untuk mempersiapkan diri sebelum beribadah di ruang pengapian.', 'https://via.placeholder.com/400x300?text=Area+Wudhu+Pengapian', '2026-03-08 16:41:17'),
(4, 'moshulla', 'Musholla Pusat', 'Musholla pusat dengan desain modern dan nyaman, dilengkapi dengan speaker system dan layar display untuk informasi jamaah.', 'https://via.placeholder.com/400x300?text=Musholla+Pusat', '2026-03-20 16:41:17'),
(5, 'moshulla', 'Musholla Lantai 2', 'Musholla tambahan di lantai 2 untuk menampung jamaah lebih banyak, terutama saat jam-jam sibuk pendaftaran.', 'https://via.placeholder.com/400x300?text=Musholla+Lantai+2', '2026-03-20 16:41:17'),
(6, 'aula', 'Aula Utama Asrama', 'Aula utama yang dapat digunakan untuk acara penting, seminar, dan workshop tentang persiapan ibadah haji. Dilengkapi dengan sistem audio visual yang lengkap.', 'https://via.placeholder.com/400x300?text=Aula+Utama+Asrama', '2026-03-21 16:41:17'),
(7, 'aula', 'Aula Kecil Meeting Room', 'Ruang pertemuan kecil yang nyaman untuk konsultasi dan diskusi kelompok dengan tim pelayanan kami.', 'https://via.placeholder.com/400x300?text=Aula+Kecil+Meeting+Room', '2026-04-28 16:41:17'),
(8, 'gedung', 'Gedung Utama Asrama Haji', 'Gedung utama Asrama Haji Landasan Ulin yang megah dan modern. Bangunan ini merupakan pusat pelayanan dan administrasi calon jamaah haji.', 'https://via.placeholder.com/400x300?text=Gedung+Utama+Asrama+Haji', '2026-04-07 16:41:17'),
(9, 'gedung', 'Gedung Penunjang Asrama', 'Gedung penunjang dengan berbagai ruang fungsional untuk mendukung kelancaran pelayanan kepada calon jamaah haji.', 'https://via.placeholder.com/400x300?text=Gedung+Penunjang+Asrama', '2026-03-06 16:41:17'),
(10, 'gedung', 'Gedung Asrama dari Udara', 'Pemandangan udara dari komplek Asrama Haji Landasan Ulin menunjukkan tata letak dan infrastruktur yang terorganisir dengan baik.', 'https://via.placeholder.com/400x300?text=Gedung+Asrama+dari+Udara', '2026-03-27 16:41:17');

-- --------------------------------------------------------

--
-- Struktur dari tabel `gambar_dashboard`
--

CREATE TABLE `gambar_dashboard` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `posisi` tinyint(4) NOT NULL,
  `path` varchar(255) NOT NULL,
  `waktu_upload` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `gambar_dashboard`
--

INSERT INTO `gambar_dashboard` (`id`, `posisi`, `path`, `waktu_upload`) VALUES
(1, 1, 'https://via.placeholder.com/1920x600?text=Dashboard+Image+1', '2026-05-01 16:41:16'),
(2, 2, 'https://via.placeholder.com/1920x600?text=Dashboard+Image+2', '2026-04-19 16:41:16'),
(3, 3, 'https://via.placeholder.com/1920x600?text=Dashboard+Image+3', '2026-04-20 16:41:16'),
(4, 4, 'https://via.placeholder.com/1920x600?text=Dashboard+Image+4', '2026-04-16 16:41:16'),
(5, 5, 'https://via.placeholder.com/1920x600?text=Dashboard+Image+5', '2026-04-13 16:41:16'),
(6, 6, 'https://via.placeholder.com/1920x600?text=Dashboard+Image+6', '2026-04-29 16:41:16'),
(7, 7, 'https://via.placeholder.com/1920x600?text=Dashboard+Image+7', '2026-04-23 16:41:16'),
(8, 8, 'https://via.placeholder.com/1920x600?text=Dashboard+Image+8', '2026-04-25 16:41:16');

-- --------------------------------------------------------

--
-- Struktur dari tabel `gedung`
--

CREATE TABLE `gedung` (
  `id_gedung` int(11) NOT NULL,
  `nama_gedung` varchar(255) NOT NULL,
  `koordinat` varchar(100) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel gedung/bangunan utama';

--
-- Dumping data untuk tabel `gedung`
--

INSERT INTO `gedung` (`id_gedung`, `nama_gedung`, `koordinat`, `keterangan`, `createdAt`, `updatedAt`) VALUES
(1, 'Gedung Utama', '-3.1234, 114.5678', 'Gedung utama dengan 2 lantai untuk penginapan standar', '2026-04-20 23:53:02', '2026-04-20 23:53:02'),
(2, 'Gedung Aula', '-3.1235, 114.5679', 'Gedung untuk aula dan ruang pertemuan besar', '2026-04-20 23:53:02', '2026-04-20 23:53:02'),
(3, 'Gedung Pendukung', '-3.1236, 114.5680', 'Gedung untuk fasilitas pendukung dan ruang meeting', '2026-04-20 23:53:02', '2026-04-20 23:53:02');

-- --------------------------------------------------------

--
-- Struktur dari tabel `guest`
--

CREATE TABLE `guest` (
  `id` int(11) NOT NULL,
  `nik` char(16) NOT NULL,
  `name` varchar(255) NOT NULL,
  `gender` enum('MALE','FEMALE') NOT NULL,
  `address` text DEFAULT NULL,
  `bloodType` varchar(5) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel guest/tamu - data minimal';

--
-- Dumping data untuk tabel `guest`
--

INSERT INTO `guest` (`id`, `nik`, `name`, `gender`, `address`, `bloodType`, `notes`, `createdAt`, `updatedAt`) VALUES
(1, '3209072512850001', 'Haji Ahmad Suryanto', 'MALE', 'Jl. Merdeka No. 45, Banjarmasin', 'O', 'Tamu reguler, sering menggunakan layanan', '2026-04-20 23:53:03', '2026-04-20 23:53:03'),
(2, '3209151809870002', 'Hajja Siti Nurhaliza', 'FEMALE', 'Jl. Sultan Adam No. 12, Banjarmasin', 'AB', 'Tamu baru keluarga besar', '2026-04-20 23:53:03', '2026-04-20 23:53:03'),
(3, '3209089907920003', 'Haji Bambang Irawan', 'MALE', 'Jl. Hasanuddin No. 78, Banjarmasin', 'B', 'Tamu korporat dari PT Maju Jaya', '2026-04-20 23:53:03', '2026-04-20 23:53:03');

-- --------------------------------------------------------

--
-- Struktur dari tabel `invoice`
--

CREATE TABLE `invoice` (
  `id` int(11) NOT NULL,
  `noInvoice` varchar(50) NOT NULL,
  `peminjamanId` int(11) NOT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  `biayaTambahan` decimal(15,2) NOT NULL DEFAULT 0.00,
  `totalHarga` decimal(15,2) NOT NULL,
  `statusInvoice` enum('UNPAID','PARTIAL','PAID','OVERDUE') DEFAULT 'UNPAID',
  `status_pembayaran` enum('BELUM_BAYAR','SEBAGIAN','LUNAS') DEFAULT 'BELUM_BAYAR',
  `tglInvoice` datetime NOT NULL DEFAULT current_timestamp(),
  `tglDueDate` datetime DEFAULT NULL,
  `tgl_pembayaran` datetime DEFAULT NULL,
  `tglPaid` datetime DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel invoice/tagihan';

--
-- Dumping data untuk tabel `invoice`
--

INSERT INTO `invoice` (`id`, `noInvoice`, `peminjamanId`, `subtotal`, `biayaTambahan`, `totalHarga`, `statusInvoice`, `status_pembayaran`, `tglInvoice`, `tglDueDate`, `tgl_pembayaran`, `tglPaid`, `notes`, `createdAt`, `updatedAt`) VALUES
(1, 'INV/2026/0001', 1, 300000.00, 0.00, 300000.00, 'UNPAID', 'BELUM_BAYAR', '2026-04-21 07:53:03', '2026-04-28 07:53:03', NULL, NULL, 'Invoice untuk peminjaman kamar premium', '2026-04-20 23:53:03', '2026-04-20 23:53:03'),
(2, 'INV/2026/0002', 2, 2500000.00, 500000.00, 3000000.00, 'PARTIAL', 'SEBAGIAN', '2026-04-21 07:53:03', '2026-04-28 07:53:03', '2026-04-21 07:53:03', NULL, 'Invoice untuk peminjaman aula + setup', '2026-04-20 23:53:03', '2026-04-20 23:53:03'),
(3, 'INV/2026/0003', 3, 800000.00, 0.00, 800000.00, 'PAID', 'LUNAS', '2026-04-21 08:00:00', '2026-04-28 17:00:00', '2026-04-21 16:30:00', '2026-04-21 16:30:00', 'Invoice untuk peminjaman ruang rapat', '2026-04-20 23:53:03', '2026-04-20 23:53:03');

-- --------------------------------------------------------

--
-- Struktur dari tabel `media_file`
--

CREATE TABLE `media_file` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ruangan_id` int(11) NOT NULL,
  `path` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel foto-foto per ruangan (simple, tied to ruangan)';

--
-- Dumping data untuk tabel `media_file`
--

INSERT INTO `media_file` (`id`, `ruangan_id`, `path`, `created_at`, `updated_at`) VALUES
(1, 1, '/uploads/ruangan/101/foto-kamar-standar-1.jpg', '2026-04-20 23:53:03', '2026-04-20 23:53:03'),
(2, 2, '/uploads/aula/foto-aula-besar-1.jpg', '2026-04-20 23:53:03', '2026-04-20 23:53:03'),
(3, 3, '/uploads/meeting/foto-ruang-meeting-1.jpg', '2026-04-20 23:53:03', '2026-04-20 23:53:03');

-- --------------------------------------------------------

--
-- Struktur dari tabel `paket_ruangan`
--

CREATE TABLE `paket_ruangan` (
  `id` int(11) NOT NULL,
  `ruangan_id` int(11) NOT NULL,
  `nama_paket` varchar(255) NOT NULL,
  `durasi` int(11) DEFAULT NULL,
  `harga` decimal(15,2) NOT NULL,
  `currency` varchar(3) DEFAULT 'IDR',
  `isExclusive` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('ACTIVE','INACTIVE','MAINTENANCE') DEFAULT 'ACTIVE',
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel paket harga/layanan per ruangan (1 RUANGAN : N PAKET_RUANGAN)';

--
-- Dumping data untuk tabel `paket_ruangan`
--

INSERT INTO `paket_ruangan` (`id`, `ruangan_id`, `nama_paket`, `durasi`, `harga`, `currency`, `isExclusive`, `status`, `createdAt`, `updatedAt`) VALUES
(1, 1, 'Paket 24 Jam', 24, 150000.00, 'IDR', 0, 'ACTIVE', '2026-04-20 23:53:03', '2026-04-20 23:53:03'),
(2, 2, 'Sewa Aula Harian (Exclusive)', 24, 2500000.00, 'IDR', 1, 'ACTIVE', '2026-04-20 23:53:03', '2026-04-20 23:53:03'),
(3, 3, 'Sewa 4 Jam', 4, 800000.00, 'IDR', 0, 'ACTIVE', '2026-04-20 23:53:03', '2026-04-20 23:53:03');

-- --------------------------------------------------------

--
-- Struktur dari tabel `peminjaman_transaksi`
--

CREATE TABLE `peminjaman_transaksi` (
  `id` int(11) NOT NULL,
  `kodePeminjaman` varchar(50) NOT NULL,
  `guestId` int(11) NOT NULL,
  `facilityId` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `jamMulai` datetime NOT NULL,
  `checkIn` datetime DEFAULT NULL,
  `checkOut` datetime DEFAULT NULL,
  `durasi` int(11) NOT NULL,
  `statusPeminjaman` enum('RESERVASI','CHECK_IN','CHECK_OUT','BATAL','SELESAI') DEFAULT 'RESERVASI',
  `keterangan` text DEFAULT NULL,
  `userId` int(11) DEFAULT NULL,
  `statusApproval` enum('PENDING','APPROVED','REJECTED') DEFAULT 'PENDING',
  `catatanApproval` text DEFAULT NULL,
  `tanggalApproval` datetime DEFAULT NULL,
  `biayaTambahan` decimal(15,2) NOT NULL DEFAULT 0.00,
  `kondisiReturn` enum('BAIK','RUSAK_RINGAN','RUSAK_BERAT','HILANG') DEFAULT NULL,
  `catatanKerusakan` text DEFAULT NULL,
  `estimasiDamage` decimal(15,2) DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel core - transaksi peminjaman';

--
-- Dumping data untuk tabel `peminjaman_transaksi`
--

INSERT INTO `peminjaman_transaksi` (`id`, `kodePeminjaman`, `guestId`, `facilityId`, `tanggal`, `jamMulai`, `checkIn`, `checkOut`, `durasi`, `statusPeminjaman`, `keterangan`, `userId`, `statusApproval`, `catatanApproval`, `tanggalApproval`, `biayaTambahan`, `kondisiReturn`, `catatanKerusakan`, `estimasiDamage`, `createdAt`, `updatedAt`) VALUES
(1, 'PJM/2026/0001', 1, 1, '2026-04-20', '2026-04-20 14:00:00', '2026-04-20 14:30:00', NULL, 2, 'CHECK_IN', 'Peminjaman kamar untuk keluarga Haji Ahmad', 1, 'APPROVED', 'Disetujui sesuai prosedur', '2026-04-20 13:00:00', 0.00, NULL, NULL, NULL, '2026-04-20 23:53:03', '2026-04-20 23:53:03'),
(2, 'PJM/2026/0002', 2, 2, '2026-04-25', '2026-04-25 08:00:00', NULL, NULL, 1, 'RESERVASI', 'Peminjaman aula untuk acara pengajian', 3, 'PENDING', NULL, NULL, 500000.00, NULL, NULL, NULL, '2026-04-20 23:53:03', '2026-04-20 23:53:03'),
(3, 'PJM/2026/0003', 3, 3, '2026-04-21', '2026-04-21 09:00:00', '2026-04-21 09:15:00', '2026-04-21 16:45:00', 1, 'SELESAI', 'Ruang rapat untuk meeting manajemen', 1, 'APPROVED', 'Disetujui', '2026-04-21 08:30:00', 0.00, 'BAIK', 'Tidak ada kerusakan', 0.00, '2026-04-20 23:53:03', '2026-04-20 23:53:03');

-- --------------------------------------------------------

--
-- Struktur dari tabel `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`permissions`)),
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel role untuk akses kontrol';

--
-- Dumping data untuk tabel `role`
--

INSERT INTO `role` (`id`, `name`, `description`, `permissions`, `createdAt`, `updatedAt`) VALUES
(1, 'Admin', 'Full system access', '[\"*\"]', '2026-04-20 23:02:45', '2026-04-20 23:02:45'),
(2, 'Pimpinan', 'View reports, analytics, approve requests', '[\"report.view\", \"analytics.view\", \"verification.approve\"]', '2026-04-20 23:02:45', '2026-04-20 23:02:45'),
(3, 'Petugas', 'Check-in/out, verification, data entry', '[\"checkin.manage\", \"checkout.manage\", \"verification.manage\"]', '2026-04-20 23:02:45', '2026-04-20 23:02:45'),
(4, 'Tamu', 'Browse facilities, make booking, view status', '[\"booking.make\", \"booking.cancel\", \"status.view\"]', '2026-04-20 23:02:45', '2026-04-20 23:02:45');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ruangan`
--

CREATE TABLE `ruangan` (
  `id_ruangan` int(11) NOT NULL,
  `gedung_id` int(11) NOT NULL,
  `nama_ruangan` varchar(255) NOT NULL,
  `tipe_ruangan` enum('KAMAR_STANDAR','KAMAR_VIP','KAMAR_PREMIUM','AULA','RUANG_MEETING','RUANG_LAINNYA') DEFAULT 'KAMAR_STANDAR',
  `lantai` int(11) DEFAULT NULL,
  `kapasitas` int(11) NOT NULL DEFAULT 1,
  `gender_policy` enum('MALE_ONLY','FEMALE_ONLY','MIXED') DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel ruangan/lokasi fisik dalam gedung';

--
-- Dumping data untuk tabel `ruangan`
--

INSERT INTO `ruangan` (`id_ruangan`, `gedung_id`, `nama_ruangan`, `tipe_ruangan`, `lantai`, `kapasitas`, `gender_policy`, `keterangan`, `createdAt`, `updatedAt`) VALUES
(1, 1, 'Ruang 101', 'KAMAR_STANDAR', 1, 2, 'MIXED', 'Ruangan di lantai 1 - Kamar standar dengan AC dan mandi', '2026-04-20 23:53:03', '2026-04-20 23:53:03'),
(2, 2, 'Aula Besar', 'AULA', NULL, 300, 'MIXED', 'Aula dengan kapasitas 300 orang, lengkap dengan audio visual', '2026-04-20 23:53:03', '2026-04-20 23:53:03'),
(3, 3, 'Ruang Meeting Executive', 'RUANG_MEETING', 1, 50, 'MIXED', 'Ruang rapat dengan fasilitas meeting table dan projector', '2026-04-20 23:53:03', '2026-04-20 23:53:03');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sarana`
--

CREATE TABLE `sarana` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(128) NOT NULL,
  `kondisi` enum('Baik','Baik Sekali','Normal','Perlu Perbaikan') DEFAULT 'Baik',
  `tgl_penerimaan` date NOT NULL,
  `stok` varchar(5) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel sarana/peralatan tambahan yang bisa dipilih saat booking';

--
-- Dumping data untuk tabel `sarana`
--

INSERT INTO `sarana` (`id`, `nama`, `kondisi`, `tgl_penerimaan`, `stok`, `created_at`, `updated_at`) VALUES
(1, 'Kasur Lipat', 'Baik', '2026-01-15', '5', '2026-04-20 23:53:03', '2026-04-20 23:53:03'),
(2, 'Kursi Tambahan', 'Baik Sekali', '2026-02-01', '20', '2026-04-20 23:53:03', '2026-04-20 23:53:03'),
(3, 'Proyektor Portable', 'Baik', '2026-01-20', '2', '2026-04-20 23:53:03', '2026-04-20 23:53:03');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tentang`
--

CREATE TABLE `tentang` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `key2` varchar(255) DEFAULT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tentang`
--

INSERT INTO `tentang` (`id`, `key`, `key2`, `value`) VALUES
(1, 'nama_instansi', NULL, 'Asrama Haji Landasan Ulin'),
(2, 'logo', NULL, 'https://example.com/logo.png'),
(3, 'email', NULL, 'asramahlu@gmail.com'),
(4, 'alamat', NULL, 'Jl. Landasan Ulin, Banjarmasin, Kalimantan Selatan'),
(5, 'no_hp', NULL, '0123456789'),
(6, 'kantor', NULL, '0123456789'),
(7, 'jam_mulai', NULL, '08.00'),
(8, 'jam_akhir', NULL, '17.00'),
(9, 'jam_sabtu', NULL, '09.00'),
(10, 'link_google_maps', NULL, 'https://maps.google.com/maps?q=asrama+haji+landasan+ulin'),
(11, 'tentang', NULL, 'Asrama Haji Landasan Ulin merupakan fasilitas penunjang yang disediakan untuk memenuhi kebutuhan pendaftaran dan pelayanan calon jamaah haji. Kami berkomitmen memberikan pelayanan terbaik untuk kenyamanan Anda.'),
(12, 'facebook', NULL, 'https://facebook.com/asramahajilandasanulin'),
(13, 'instagram', NULL, 'https://instagram.com/asramahajilandasanulin'),
(14, 'youtube', NULL, 'https://youtube.com/@asramahajilandasanulin'),
(15, 'telegram', NULL, 'https://t.me/asramahajilandasanulin'),
(16, 'twitter/x', NULL, 'https://x.com/asramahajilandasanulin'),
(17, 'whatsapp', NULL, 'https://wa.me/60123456789'),
(18, 'faq', 'Bagaimana cara mendaftar sebagai calon jamaah haji?', 'Anda dapat mendaftar dengan mengunjungi kantor kami atau melalui website resmi dengan membawa dokumen yang lengkap.'),
(19, 'faq', 'Berapa biaya pendaftaran calon jamaah haji reguler?', 'Biaya pendaftaran dapat Anda lihat di website resmi atau hubungi customer service kami untuk informasi terbaru. sekian'),
(20, 'faq', 'Apa saja persyaratan untuk mendaftar haji?', 'Persyaratan umum meliputi KTP, KK, Akte Lahir, Kartu BPIH, Foto 4x6, dan memenuhi kriteria kesehatan yang ditentukan.'),
(21, 'term&conditions', 'Sikap Santuun', 'Peserta harus bersikap santun dan mematuhi peraturan yang berlaku di Asrama Haji Landasan Ulin.'),
(22, 'term&conditions', 'Kerahasiaan Data', 'Segala data pribadi peserta dijaga kerahasiaannya sesuai dengan peraturan perlindungan data pribadi.'),
(23, 'term&conditions', 'Barang Berharga', 'Asrama Haji tidak bertanggung jawab atas barang berharga yang hilang selama di fasilitas kami.'),
(24, 'kebijakan_privasi', 'Informasi yang Kami Kumpulkn', 'kami mengumpulkan informasi pribadi yang Anda berikan kepada kami secara sukarela saat Anda mendaftar di situs kami, mengekspresikan minat untuk memperoleh informasi tentang kami atau produk dan layanan kami, atau sebaliknya saat Anda menghubungi kami.'),
(25, 'kebijakan_privasi', 'Bagaimana Kami Menggunakan Informasi Anda', 'Kami menggunakan informasi pribadi yang dikumpulkan melalui situs kami untuk berbagai tujuan bisnis yang dijelaskan di bawah ini. Kami memproses informasi pribadi Anda untuk tujuan-tujuan ini berdasarkan kepentingan bisnis kami yang sah, dalam rangka masuk ke dalam atau melakukan kontrak dengan Anda, dengan persetujuan Anda, dan/atau untuk kepatuhan dengan kewajiban hukum kami.'),
(26, 'kebijakan_privasi', 'Keamanan Informasi', 'Meskipun kami berusaha keras untuk melindungi informasi Anda, tidak ada metode transmisi melalui internet atau penyimpanan elektronik yang 100% aman. Oleh karena itu, kami tidak dapat menjamin keamanan mutlak.'),
(27, 'kebijakan_privasi', 'Berbagi Informasi Anda', 'Kami tidak menjual, menyewakan, atau membagikan informasi pribadi Anda kepada pihak ketiga tanpa persetujuan Anda terlebih dahulu, kecuali sebagaimana diharuskan oleh hukum.'),
(28, 'kebijakan_privasi', 'Retensi Data', 'Kami menyimpan informasi pribadi Anda hanya selama diperlukan untuk memberikan layanan kepada Anda dan untuk memenuhi tujuan yang dijelaskan dalam kebijakan privasi ini.'),
(29, 'kebijakan_privasi', 'Hak Anda', 'Anda memiliki hak untuk mengakses, memperbaiki, dan menghapus informasi pribadi Anda.'),
(30, 'term&conditions', 'Judul', 'Isi');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `roleId` int(11) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `guestId` int(11) DEFAULT NULL,
  `status` enum('ACTIVE','INACTIVE','SUSPENDED') DEFAULT 'ACTIVE',
  `lastLoginAt` timestamp NULL DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel pengguna sistem (staff & tamu terdaftar)';

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `roleId`, `phone`, `guestId`, `status`, `lastLoginAt`, `createdAt`, `updatedAt`) VALUES
(1, 'budi.petugas', 'budi@asrama.local', '$2b$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36EZkev6', 2, '082123456789', NULL, 'ACTIVE', '2026-04-20 23:53:03', '2026-04-20 23:53:03', '2026-04-20 23:53:03'),
(2, 'siti.pimpinan', 'siti@asrama.local', '$2b$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36EZkev6', 3, '081234567891', NULL, 'ACTIVE', '2026-04-19 06:30:00', '2026-04-20 23:53:03', '2026-04-20 23:53:03'),
(3, 'ahmad.suryanto', 'ahmad.suryanto@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36EZkev6', 4, '085987654321', 1, 'ACTIVE', '2026-04-18 02:15:00', '2026-04-20 23:53:03', '2026-04-20 23:53:03');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_activityLog_userId` (`userId`),
  ADD KEY `idx_activityLog_action` (`action`),
  ADD KEY `idx_activityLog_tabelNama` (`tabelNama`),
  ADD KEY `idx_activityLog_createdAt` (`createdAt`);

--
-- Indeks untuk tabel `berita`
--
ALTER TABLE `berita`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `detail_peminjaman_sarana`
--
ALTER TABLE `detail_peminjaman_sarana`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_detail_sarana_sarana_id` (`sarana_id`),
  ADD KEY `idx_detail_sarana_peminjaman_id` (`peminjaman_id`);

--
-- Indeks untuk tabel `galeri`
--
ALTER TABLE `galeri`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `gambar_dashboard`
--
ALTER TABLE `gambar_dashboard`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `gedung`
--
ALTER TABLE `gedung`
  ADD PRIMARY KEY (`id_gedung`),
  ADD KEY `idx_gedung_nama` (`nama_gedung`);

--
-- Indeks untuk tabel `guest`
--
ALTER TABLE `guest`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nik` (`nik`),
  ADD KEY `idx_guest_nik` (`nik`),
  ADD KEY `idx_guest_name` (`name`);

--
-- Indeks untuk tabel `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `noInvoice` (`noInvoice`),
  ADD UNIQUE KEY `peminjamanId` (`peminjamanId`),
  ADD KEY `idx_invoice_noInvoice` (`noInvoice`),
  ADD KEY `idx_invoice_peminjamanId` (`peminjamanId`),
  ADD KEY `idx_invoice_statusInvoice` (`statusInvoice`),
  ADD KEY `idx_invoice_payment` (`peminjamanId`,`statusInvoice`,`createdAt`);

--
-- Indeks untuk tabel `media_file`
--
ALTER TABLE `media_file`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_mediaFile_ruangan_id` (`ruangan_id`),
  ADD KEY `idx_mediaFile_created_at` (`created_at`);

--
-- Indeks untuk tabel `paket_ruangan`
--
ALTER TABLE `paket_ruangan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_paket_ruangan_id` (`ruangan_id`),
  ADD KEY `idx_paket_ruangan_status` (`status`);

--
-- Indeks untuk tabel `peminjaman_transaksi`
--
ALTER TABLE `peminjaman_transaksi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kodePeminjaman` (`kodePeminjaman`),
  ADD KEY `idx_peminjaman_kodePeminjaman` (`kodePeminjaman`),
  ADD KEY `idx_peminjaman_guestId` (`guestId`),
  ADD KEY `idx_peminjaman_userId` (`userId`),
  ADD KEY `idx_peminjaman_facilityId` (`facilityId`),
  ADD KEY `idx_peminjaman_status` (`statusPeminjaman`),
  ADD KEY `idx_peminjaman_statusApproval` (`statusApproval`),
  ADD KEY `idx_peminjaman_jamMulai` (`jamMulai`),
  ADD KEY `idx_peminjaman_availability` (`facilityId`,`jamMulai`,`statusPeminjaman`),
  ADD KEY `idx_peminjaman_paket_date` (`facilityId`,`jamMulai`,`statusPeminjaman`),
  ADD KEY `idx_peminjaman_guest_date` (`guestId`,`jamMulai`,`statusPeminjaman`);

--
-- Indeks untuk tabel `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `idx_role_name` (`name`);

--
-- Indeks untuk tabel `ruangan`
--
ALTER TABLE `ruangan`
  ADD PRIMARY KEY (`id_ruangan`),
  ADD KEY `idx_ruangan_gedung_id` (`gedung_id`),
  ADD KEY `idx_ruangan_tipe` (`tipe_ruangan`),
  ADD KEY `idx_ruangan_nama` (`nama_ruangan`),
  ADD KEY `idx_ruangan_lantai` (`lantai`);

--
-- Indeks untuk tabel `sarana`
--
ALTER TABLE `sarana`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sarana_nama` (`nama`),
  ADD KEY `idx_sarana_kondisi` (`kondisi`);

--
-- Indeks untuk tabel `tentang`
--
ALTER TABLE `tentang`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `guestId` (`guestId`),
  ADD KEY `idx_user_email` (`email`),
  ADD KEY `idx_user_roleId` (`roleId`),
  ADD KEY `idx_user_guestId` (`guestId`),
  ADD KEY `idx_user_status` (`status`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `berita`
--
ALTER TABLE `berita`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `detail_peminjaman_sarana`
--
ALTER TABLE `detail_peminjaman_sarana`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `galeri`
--
ALTER TABLE `galeri`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `gambar_dashboard`
--
ALTER TABLE `gambar_dashboard`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `gedung`
--
ALTER TABLE `gedung`
  MODIFY `id_gedung` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `guest`
--
ALTER TABLE `guest`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `media_file`
--
ALTER TABLE `media_file`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `paket_ruangan`
--
ALTER TABLE `paket_ruangan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `peminjaman_transaksi`
--
ALTER TABLE `peminjaman_transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `ruangan`
--
ALTER TABLE `ruangan`
  MODIFY `id_ruangan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `sarana`
--
ALTER TABLE `sarana`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `tentang`
--
ALTER TABLE `tentang`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `activity_log`
--
ALTER TABLE `activity_log`
  ADD CONSTRAINT `activity_log_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `berita`
--
ALTER TABLE `berita`
  ADD CONSTRAINT `berita_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `detail_peminjaman_sarana`
--
ALTER TABLE `detail_peminjaman_sarana`
  ADD CONSTRAINT `detail_peminjaman_sarana_ibfk_1` FOREIGN KEY (`sarana_id`) REFERENCES `sarana` (`id`),
  ADD CONSTRAINT `detail_peminjaman_sarana_ibfk_2` FOREIGN KEY (`peminjaman_id`) REFERENCES `peminjaman_transaksi` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `invoice_ibfk_1` FOREIGN KEY (`peminjamanId`) REFERENCES `peminjaman_transaksi` (`id`);

--
-- Ketidakleluasaan untuk tabel `media_file`
--
ALTER TABLE `media_file`
  ADD CONSTRAINT `media_file_ibfk_1` FOREIGN KEY (`ruangan_id`) REFERENCES `ruangan` (`id_ruangan`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `paket_ruangan`
--
ALTER TABLE `paket_ruangan`
  ADD CONSTRAINT `paket_ruangan_ibfk_1` FOREIGN KEY (`ruangan_id`) REFERENCES `ruangan` (`id_ruangan`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `peminjaman_transaksi`
--
ALTER TABLE `peminjaman_transaksi`
  ADD CONSTRAINT `peminjaman_transaksi_ibfk_1` FOREIGN KEY (`guestId`) REFERENCES `guest` (`id`),
  ADD CONSTRAINT `peminjaman_transaksi_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `peminjaman_transaksi_ibfk_3` FOREIGN KEY (`facilityId`) REFERENCES `paket_ruangan` (`id`);

--
-- Ketidakleluasaan untuk tabel `ruangan`
--
ALTER TABLE `ruangan`
  ADD CONSTRAINT `ruangan_ibfk_1` FOREIGN KEY (`gedung_id`) REFERENCES `gedung` (`id_gedung`);

--
-- Ketidakleluasaan untuk tabel `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`roleId`) REFERENCES `role` (`id`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`guestId`) REFERENCES `guest` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
