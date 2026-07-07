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
-- Struktur dari tabel `gambar_dashboard`
--

CREATE TABLE `gambar_dashboard` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `posisi` tinyint(4) NOT NULL,
  `path` varchar(255) NOT NULL,
  `waktu_upload` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `gambar_dashboard`
--

INSERT INTO `gambar_dashboard` (`id`, `posisi`, `path`, `waktu_upload`) VALUES
(1, 1, 'gambar-landing/Jz1PZpKp7ku6FNaQ7pIByPnBnMMlTGYEFfCuM2vO.jpg', '2026-06-26 20:34:51'),
(2, 2, 'gambar-landing/PnWMW16zrue71XkToxWvr6DEKptbyAsWuDPX48cG.jpg', '2026-06-28 20:34:51'),
(3, 3, 'gambar-landing/Yp4MUDeQaEnUV5QO9EjR62Th7bQYFF5pVLWOK0Vt.jpg', '2026-06-17 20:34:51'),
(4, 4, 'gambar-landing/iTqcS47vdqdJNvZXaP7S6pEeR5f1R75PwJDeF4DE.jpg', '2026-06-08 20:34:51'),
(5, 5, 'gambar-landing/z660dARuZtozCMKX9hEyzScyUzVD0iTTOsReBB1F.jpg', '2026-06-10 20:34:51'),
(6, 6, 'gambar-landing/OOW4AFDSXh68f2za8V1UR1biw4n8WYhklWYkHcNp.jpg', '2026-07-01 20:34:51'),
(7, 7, 'gambar-landing/sCEXRkLDNLBrmNjPVE5xcCP5Lx7uNEzUROpCnyjL.jpg', '2026-06-13 20:34:51'),
(8, 8, 'gambar-landing/JkrnDMXoFzXdP4IYxDWQcVBuquMPw75Ci5CuAeqx.jpg', '2026-06-27 20:34:51');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `gambar_dashboard`
--
ALTER TABLE `gambar_dashboard`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `gambar_dashboard`
--
ALTER TABLE `gambar_dashboard`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
