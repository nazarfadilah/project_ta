# Dokumentasi Workflow dan Pengembangan Sistem Informasi Asrama Haji Kelas 1 Banjarmasin

Sistem Informasi Asrama Haji Kelas 1 Banjarmasin dibangun dengan mengadaptasi standar sistem informasi perhotelan (Property Management System / PMS) yang disesuaikan dengan kebutuhan spesifik operasional asrama haji. Sistem ini beroperasi dalam dua mode utama:

1. **HAJJ SEASON**: Mode operasional massal yang fokus pada pengelolaan jemaah berdasarkan Kloter (Kelompok Terbang).
2. **PUBLIC SERVICE**: Mode pelayanan umum komersial (sewa kamar, gedung, fasilitas) yang beroperasi seperti hotel pada umumnya.

Dokumen ini menjelaskan workflow operasional dan panduan tahapan pengembangan sistem berdasarkan skema database (`schema.prisma`) yang telah didefinisikan.

---

## 1. WORKFLOW SISTEM (Alur Proses Bisnis)

### 1.1 Alur Mode Musim Haji (HAJJ SEASON)

Fokus pada penerimaan dan penempatan massal.

1. **Persiapan Data**: Admin mengelola data `Kloter` (Kelompok Terbang) dan mendaftarkan `Guest` (Jemaah) yang tergabung dalam kloter tersebut.
2. **Plotting Kamar**: Menggunakan `Facility` (dengan tipe kamar/ruangan), admin melakukan alokasi/plotting jemaah ke kamar-kamar yang tersedia berdasarkan `GenderPolicy` dan kapasitas.
3. **Check-In Massal**: Pembuatan `Occupancy` untuk rombongan kloter saat tiba di asrama.
4. **Logistik & Konsumsi**: Penggunaan `Item` (barang habis pakai) didistribusikan dan dicatat melalui `StockTransaction` untuk melayani jemaah selama menginap.
5. **Check-Out Massal**: Update status `Occupancy` menjadi `CHECKED_OUT` saat kloter berangkat ke embarkasi/tanah suci.

### 1.2 Alur Mode Pelayanan Umum (PUBLIC SERVICE)

Fokus pada reservasi individual/kelompok secara komersial layaknya hotel.

1. **Pencarian Fasilitas**: Masyarakat umum dapat melihat `Facility` (Kamar, Aula, Lapangan) dan paket harganya (`FacilityService`) melalui Landing Page.
2. **Reservasi**: Tamu (`Guest`) membuat pesanan (`Reservation`). Sistem menghasilkan `bookingCode` unik. Status pesanan awal adalah `PENDING` (menunggu verifikasi/pembayaran).
3. **Pilihan Layanan**: Tamu memilih detail pesanan (`ReservationItem`) yang berisi fasilitas dan paket waktu yang disewa (misal: Sewa Aula 8 Jam).
4. **Tagihan & Pembayaran**: Sistem men-generate `Invoice`. Tamu melakukan pembayaran (`Payment`), lalu status reservasi berubah menjadi `CONFIRMED`.
5. **Check-In & Check-Out**: Saat hari H, tamu datang dan dibuatkan `Occupancy` (Check-In). Setelah selesai, dilakukan Check-Out.

### 1.3 Alur Back-Office & Manajemen Enterprise

1. **Manajemen Konten (CMS)**: Humas/Admin membuat berita/pengumuman (`Post`) yang akan tayang di Landing Page.
2. **Manajemen Aset & Pemeliharaan**: Bagian rumah tangga mendata `AssetItem` (AC, Kasur, dll) di setiap `Location` (Gedung/Lantai/Kamar). Jika ada kerusakan, dicatat dalam `AssetMaintenance`.
3. **Manajemen Logistik**: Keluar masuk barang inventaris habis pakai (`Item`) seperti sabun atau sprei dicatat dalam `StockTransaction`.
4. **Manajemen Arsip**: Dokumen fisik kantor dicatat lokasinya dalam `ArchiveItem`.
5. **Audit Trail**: Setiap perubahan data krusial dicatat dalam `ActivityLog` untuk transparansi.

---

## 2. TAHAPAN PENGEMBANGAN SISTEM

Sistem akan dikembangkan dalam beberapa modul secara bertahap menggunakan framework Next.js (App Router) dan Prisma ORM.

### Tahap 1: Konfigurasi Sistem & Autentikasi (System Configuration & Auth)

**Fokus**: Membangun fondasi keamanan dan pengaturan dasar sistem.

- **Manajemen Role & User**:
  - CRUD `Role` (Admin, Resepsionis, Manajer, dll).
  - CRUD `User` beserta sistem Login/Logout yang aman (Hashed password).
  - Implementasi Middleware untuk memproteksi _Admin Panel_.
- **Konfigurasi Global**:
  - CRUD `SystemSetting` untuk mengatur mode aplikasi (`active_mode`: HAJJ_SEASON atau PUBLIC_SERVICE), nama situs, dan pengaturan global lainnya.
- **Audit Logging**:
  - Implementasi interceptor/middleware untuk mencatat setiap aksi user ke dalam `ActivityLog`.

### Tahap 2: Manajemen Media & File (Media Management)

**Fokus**: Sistem penyimpanan file terpusat.

- Pembuatan layanan upload file lokal atau cloud.
- Pencatatan metadata file ke dalam `MediaFile`.
- Implementasi sistem relasi eksplisit pada `MediaLink` (menggunakan *explicit foreign keys*) sehingga satu file fisik (`mediaId`) dapat dilampirkan ke entitas yang berbeda seperti `Facility`, `Post`, `AssetItem`, `ArchiveItem`, maupun `Item` dengan mudah.

### Tahap 3: Landing Page & Manajemen Konten (CMS)

**Fokus**: Tampilan publik untuk masyarakat.

- **Manajemen Berita**: CRUD `Post` di Admin Panel dengan status (`DRAFT`, `PUBLISHED`, `ARCHIVED`). Pengikatan gambar thumbnail via `MediaLink`.
- **Halaman Depan (Public)**:
  - _Hero Section_: Mengambil data `SystemSetting` untuk banner.
  - _Berita Terbaru_: Menampilkan `Post` dengan status `PUBLISHED`.
  - _Katalog Fasilitas_: Menampilkan daftar kamar, aula, dan harga layanannya kepada publik.

### Tahap 4: Manajemen Fasilitas & Lokasi (Facility & Location Management)

**Fokus**: Pendataan ruang dan paket layanan.

- **Hierarki Lokasi**: CRUD `Location` (Gedung -> Lantai -> Ruangan/Kamar).
- **Manajemen Fasilitas**: CRUD `Facility` (Kamar, Aula) yang dihubungkan ke lokasi tertentu. Pengaturan atribut kapasitas, kebijakan gender (`genderPolicy`), dan status ketersediaan.
- **Paket Layanan Fasilitas**: CRUD `FacilityService` untuk menentukan harga sewa (misal: "Kamar VIP Harian", "Sewa Aula 8 Jam").
- Upload galeri foto fasilitas menggunakan modul Media.

### Tahap 5: Manajemen Reservasi & Okupansi (Core Hotel PMS)

**Fokus**: Jantung dari transaksi pelayanan umum dan operasional.

- **Manajemen Tamu**: CRUD profil `Guest`.
- **Sistem Pemesanan (Reservation)**:
  - Form booking public & internal untuk membuat `Reservation`.
  - Menambah detail pesanan ke `ReservationItem` (menghitung `quantity` x harga `FacilityService`).
- **Billing & Pembayaran (Keuangan)**:
  - Pembuatan `Invoice` berdasarkan total reservasi.
  - Pencatatan `Payment` (Uang masuk) dan update status reservasi menjadi `CONFIRMED`.
- **Check-In / Check-Out (Occupancy)**:
  - Modul Resepsionis: Eksekusi check-in tamu yang mengubah pesanan menjadi data `Occupancy` di kamar tertentu.
  - Proses Check-Out dan update status ketersediaan fasilitas.

### Tahap 6: Mode Musim Haji (Hajj Season Operations)

**Fokus**: Modul spesifik asrama haji.

- **Manajemen Kloter**: CRUD `Kloter` (kode kloter, asal, total jemaah).
- **Data Jemaah**: Integrasi/import data jemaah (`Guest`) dan asosiasi ke `Kloter`.
- **Plotting Kamar Massal**: Antarmuka khusus untuk menempatkan (drag & drop atau auto-assign) ratusan jemaah ke fasilitas kamar secara cepat (membuat `Occupancy` massal berdasarkan _gender policy_).

### Tahap 7: Manajemen Aset & Logistik (Enterprise / ERP)

**Fokus**: Perawatan fasilitas dan stok barang.

- **Aset Tetap (Asset Management)**:
  - Pendataan `AssetItem` (AC, Genset, dll) di setiap lokasi.
  - Pencatatan riwayat servis/kerusakan pada `AssetMaintenance`.
- **Barang Habis Pakai (Inventory/Logistics)**:
  - Master data `Item` (Sabun, Tisu, Makanan).
  - Pencatatan barang masuk/keluar melalui `StockTransaction`.
- **Manajemen Arsip**: Pencatatan lokasi penyimpanan dokumen fisik (`ArchiveItem`).

---

## Ringkasan Struktur Folder Next.js (Saran)

Pengembangan di App Router akan dibagi kurang lebih seperti ini:

- `app/(public)/`: Landing page, profil, berita, formulir booking publik.
- `app/(admin)/`: Dashboard admin panel, diproteksi dengan autentikasi.
  - `app/(admin)/dashboard/`
  - `app/(admin)/facilities/`
  - `app/(admin)/reservations/`
  - `app/(admin)/hajj-season/`
  - `app/(admin)/inventory/`
- `app/api/`: Endpoint REST API untuk mendukung frontend dan mobile apps (jika ada).
- `components/`: Komponen UI yang reusable (Button, Table, Form).
- `lib/`: Utility functions, Prisma client instance (`app/generated/prisma`).
