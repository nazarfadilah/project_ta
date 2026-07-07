-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Waktu pembuatan: 07 Jul 2026 pada 13.43
-- Versi server: 11.8.8-MariaDB-log
-- Versi PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u568987435_asramahajibdj`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `berita`
--

CREATE TABLE `berita` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `userId` bigint(20) UNSIGNED DEFAULT NULL,
  `judul` varchar(128) NOT NULL,
  `slug` varchar(128) NOT NULL,
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

INSERT INTO `berita` (`id`, `userId`, `judul`, `slug`, `isi`, `gambar`, `tanggal_publish`, `status`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 1, 'Pelayanan Pendaftaran Haji Dibuka', 'pelayanan-pendaftaran-haji-dibuka', 'Kabar gembira! Asrama Haji Landasan Ulin telah resmi membuka pelayanan pendaftaran calon jamaah haji untuk tahun 2026. Tim kami siap melayani Anda dengan sepenuh hati untuk mewujudkan impian ibadah haji Anda.', 'storage/berita/POi5MI4wFVHb0Y2o8WYIFCUrXC2ZljFmDBfNXoZc.jpg', '2026-04-15', 'approved', 'Berita resmi tentang pembukaan pendaftaran haji', '2026-07-07 20:34:51', '2026-07-07 21:01:12'),
(2, 1, 'Tips Persiapan Ibadah Haji yang Sempurna', 'tips-persiapan-ibadah-haji-yang-sempurna', 'Persiapan ibadah haji memerlukan perencanaan yang matang baik dari segi fisik, mental, maupun spiritual. Dalam artikel ini kami bagikan beberapa tips untuk membantu Anda mempersiapkan diri dengan baik sebelum berangkat ke Tanah Suci.', 'storage/berita/wGjmlXwrvf3NLuMWh5L1vmNCCtcALXiXOTe6NUCI.jpg', '2026-04-18', 'approved', 'Artikel informatif tentang persiapan haji', '2026-07-07 20:34:51', '2026-07-07 20:58:26'),
(3, 1, 'Pemberitahuan: Penutupan Kantor Hari Libur Nasional', 'pemberitahuan-penutupan-kantor-hari-libur-nasional', 'Untuk memperingati Hari Raya Idul Fitri, Asrama Haji Landasan Ulin akan ditutup dari tanggal 1-5 Mei 2026. Pelayanan akan kembali normal pada tanggal 6 Mei 2026. Terima kasih atas pengertian Anda.', 'storage/berita/Z9GrzkkR8wJnUA4msDikVxj3fYBv94PUyPunlouz.jpg', '2026-04-20', 'approved', 'Pemberitahuan resmi tutup kantor', '2026-07-07 20:34:51', '2026-07-07 20:57:55'),
(5, 1, 'Syarat dan Ketentuan Pendaftaran Calon Jamaah Haji', 'syarat-dan-ketentuan-pendaftaran-calon-jamaah-haji', 'Sebelum melakukan pendaftaran, pastikan Anda telah memenuhi semua syarat dan ketentuan yang berlaku. Dokumentasi yang lengkap dan akurat sangat diperlukan untuk memproses pendaftaran Anda dengan lancar.', 'storage/berita/JXN9doFuwWIKAW6cyzrACDFYyCSkzSLVJcjiDWfF.jpg', '2026-04-12', 'approved', 'Informasi syarat dan ketentuan pendaftaran', '2026-07-07 20:34:51', '2026-07-07 20:58:46'),
(6, 1, 'Program Edukasi Pramanifestasi Calon Jamaah Haji', 'program-edukasi-pramanifestasi-haji', 'Kami menyediakan program edukasi lengkap untuk mempersiapkan Anda secara holistik sebelum berangkat haji. Program ini mencakup materi tentang tata cara ibadah, kesehatan, administrasi, hingga persiapan mental dan spiritual.', 'https://placehold.co/500x300?text=Program+Edukasi', '2026-04-22', 'draft', 'Program edukasi untuk calon jamaah haji', '2026-07-07 20:34:51', '2026-07-07 20:34:51'),
(7, 1, 'Fasilitas Baru di Asrama Haji Landasan Ulin', 'fasilitas-baru-asrama-haji', 'Dalam upaya meningkatkan kualitas pelayanan, kami telah menambahkan beberapa fasilitas baru termasuk ruang tunggu yang lebih nyaman, sistem informasi online, dan layanan konsultasi kesehatan gratis.', 'https://placehold.co/500x300?text=Fasilitas+Baru', '2026-04-25', 'draft', 'Informasi fasilitas baru yang tersedia', '2026-07-07 20:34:51', '2026-07-07 20:34:51'),
(8, 1, 'Panduan Pembayaran BPIH Calon Jamaah Haji', 'panduan-pembayaran-bpih-haji', 'BPIH (Biaya Penyelenggaraan Ibadah Haji) adalah biaya wajib yang harus dibayarkan oleh setiap calon jamaah haji. Pelajari panduan lengkap tentang tata cara pembayaran BPIH melalui artikel ini.', 'https://placehold.co/500x300?text=Panduan+Pembayaran', '2026-04-28', 'draft', 'Panduan pembayaran BPIH', '2026-07-07 20:34:51', '2026-07-07 20:34:51');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `berita`
--
ALTER TABLE `berita`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `berita_slug_unique` (`slug`),
  ADD KEY `berita_userid_foreign` (`userId`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `berita`
--
ALTER TABLE `berita`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `berita`
--
ALTER TABLE `berita`
  ADD CONSTRAINT `berita_userid_foreign` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
