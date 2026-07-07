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
(2, 'logo', NULL, 'storage/logo/jN2j0l6Kc9KeXHoNOqLyGrdHSfBKcrIKPlTqE5pW.png'),
(3, 'email', NULL, 'uptbanjarmasin@gmail.com'),
(4, 'alamat', NULL, 'Jalan Jenderal A. Yani Km. 28, Landasan Ulin, Banjarbaru, Kalimantan Selatan'),
(5, 'no_hp', NULL, '08114838448'),
(6, 'kantor', NULL, '(0511) 4705150'),
(7, 'jam_mulai', NULL, '08.00'),
(8, 'jam_akhir', NULL, '17.00'),
(9, 'jam_sabtu', NULL, '09.00'),
(10, 'link_google_maps', NULL, 'https://maps.google.com/maps?q=asrama+haji+landasan+ulin'),
(11, 'tentang', NULL, 'Asrama Haji Landasan Ulin merupakan fasilitas penunjang yang disediakan untuk memenuhi kebutuhan pendaftaran dan pelayanan calon jamaah haji. Kami berkomitmen memberikan pelayanan terbaik untuk kenyamanan Anda.'),
(12, 'facebook', NULL, 'https://www.facebook.com/share/1BWd3BbQwj/'),
(13, 'instagram', NULL, 'https://www.instagram.com/kemenhaj.asramahaji.bjm'),
(14, 'youtube', NULL, 'https://www.youtube.com/@asramahajibanjarmasin'),
(15, 'telegram', NULL, 'https://t.me/asramahajilandasanulin'),
(16, 'twitter/x', NULL, 'https://x.com/asramahajilandasanulin'),
(17, 'whatsapp', NULL, 'https://wa.me/628114838448'),
(18, 'faq', 'Bagaimana cara mendaftar sebagai calon jamaah haji?', 'Anda dapat mendaftar dengan mengunjungi kantor kami atau melalui website resmi dengan membawa dokumen yang lengkap.'),
(19, 'faq', 'Berapa biaya pendaftaran calon jamaah haji?', 'Biaya pendaftaran dapat Anda lihat di website resmi atau hubungi customer service kami untuk informasi terbaru.'),
(20, 'faq', 'Apa saja persyaratan untuk mendaftar haji?', 'Persyaratan umum meliputi KTP, KK, Akte Lahir, Kartu BPIH, Foto 4x6, dan memenuhi kriteria kesehatan yang ditentukan.'),
(21, 'term&conditions', 'Sikap Santun', 'Peserta harus bersikap santun dan mematuhi peraturan yang berlaku di Asrama Haji Landasan Ulin.'),
(22, 'term&conditions', 'Kerahasiaan Data', 'Segala data pribadi peserta dijaga kerahasiaannya sesuai dengan peraturan perlindungan data pribadi.'),
(23, 'term&conditions', 'Barang Berharga', 'Asrama Haji tidak bertanggung jawab atas barang berharga yang hilang selama di fasilitas kami.'),
(24, 'kebijakan_privasi', 'Informasi yang Kami Kumpulkan', 'Kami mengumpulkan informasi pribadi yang Anda berikan kepada kami secara sukarela saat Anda mendaftar di situs kami, mengekspresikan minat untuk memperoleh informasi tentang kami atau produk dan layanan kami, atau sebaliknya saat Anda menghubungi kami.'),
(25, 'kebijakan_privasi', 'Bagaimana Kami Menggunakan Informasi Anda', 'Kami menggunakan informasi pribadi yang dikumpulkan melalui situs kami untuk berbagai tujuan bisnis yang dijelaskan di bawah ini. Kami memproses informasi pribadi Anda untuk tujuan-tujuan ini berdasarkan kepentingan bisnis kami yang sah, dalam rangka masuk ke dalam atau melakukan kontrak dengan Anda, dengan persetujuan Anda, dan/atau untuk kepatuhan dengan kewajiban hukum kami.'),
(26, 'kebijakan_privasi', 'Keamanan Informasi', 'Meskipun kami berusaha keras untuk melindungi informasi Anda, tidak ada metode transmisi melalui internet atau penyimpanan elektronik yang 100% aman. Oleh karena itu, kami tidak dapat menjamin keamanan mutlak.'),
(27, 'kebijakan_privasi', 'Berbagi Informasi Anda', 'Kami tidak menjual, menyewakan, atau membagikan informasi pribadi Anda kepada pihak ketiga tanpa persetujuan Anda terlebih dahulu, kecuali sebagaimana diharuskan oleh hukum.'),
(28, 'kebijakan_privasi', 'Retensi Data', 'Kami menyimpan informasi pribadi Anda hanya selama diperlukan untuk memberikan layanan kepada Anda dan untuk memenuhi tujuan yang dijelaskan dalam kebijakan privasi ini.'),
(29, 'kebijakan_privasi', 'Hak Anda', 'Anda memiliki hak untuk mengakses, memperbaiki, dan menghapus informasi pribadi Anda.');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `tentang`
--
ALTER TABLE `tentang`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `tentang`
--
ALTER TABLE `tentang`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
