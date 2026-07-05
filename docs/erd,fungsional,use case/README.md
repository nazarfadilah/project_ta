# Dokumentasi Entity-Relationship Diagram (ERD) - SIPRASA

Folder ini berisi Entity-Relationship Diagram (ERD) untuk **Sistem Informasi Asrama Haji Kelas I Banjarmasin (SIPRASA)** dalam format **Chen Notation** (Notasi Chen). ERD ini dibuat berdasarkan keseluruhan skema database aktif, dengan mengecualikan tabel bawaan Laravel (`migrations`, `sessions`, `password_reset_tokens`, dll) serta tabel audit `activity_logs`.

## Berkas yang Tersedia

1. **`erd_chen.svg`**  
   Gambar vektor resolusi tinggi (SVG) dari ERD Notasi Chen. Sangat cocok untuk dicetak (print) atau dimasukkan ke dalam dokumen laporan/skripsi.
   
2. **`erd_viewer.html`**  
   Aplikasi penampil interaktif (Interactive ERD Viewer) yang dapat dibuka langsung melalui peramban (browser) web. Fitur-fitur utama:
   - **Pan & Zoom**: Geser dan perbesar diagram menggunakan tetikus (mouse) atau touchpad.
   - **Highlight Tabel**: Klik pada nama tabel di bilah sisi (sidebar) atau langsung pada diagram untuk menyoroti entitas, atribut, dan relasi terkait.
   - **Filter Atribut**: Sembunyikan atribut biasa untuk hanya menampilkan Primary Key (PK) & Foreign Key (FK), atau sembunyikan semua atribut agar diagram terlihat sangat bersih.
   - **Tema Gelap/Terang**: Tombol saklar tema untuk kenyamanan visual.
   - **Ekspor Gambar**: Unduh SVG hasil penyesuaian secara langsung.

3. **`erd_chen.drawio`**  
   Berkas XML diagrams.net/Draw.io yang berisi keseluruhan diagram ERD. Berkas ini dapat diimpor langsung ke [Draw.io](https://app.diagrams.net/) sehingga Anda dapat mengubah layout, menambah entitas/atribut, atau menyesuaikan konten diagram secara visual dengan mudah.

---

## Legenda Notasi Chen

- **Persegi Panjang (Rectangles)**: Merepresentasikan **Entitas** (Tabel database utama).
- **Belah Ketupat (Diamonds)**: Merepresentasikan **Relasi** (Hubungan logika antar tabel, contoh: *memiliki*, *melakukan*, *dipesan*).
- **Elips / Oval (Ellipses)**: Merepresentasikan **Atribut** (Kolom/field tabel).
  - Teks Bergaris Bawah: **Primary Key (PK)** (Kunci utama unik).
  - Kolom tanpa garis bawah: **Atribut Biasa / Foreign Key (FK)**.

---

## Struktur Entitas & Relasi Utama

Berikut adalah ringkasan hubungan antar entitas utama yang dimodelkan:

### 1. Autentikasi & Akun
- **`role`** (1) $\rightarrow$ memiliki $\rightarrow$ (N) **`users`**  
  Mengatur otorisasi akses (Admin, Staf, Humas, Tamu).
- **`guest`** (1) $\rightarrow$ memiliki $\rightarrow$ (1) **`users`**  
  Menghubungkan tamu terdaftar dengan akun login mereka.
- **`users`** (1) $\rightarrow$ menulis $\rightarrow$ (N) **`berita`**  
  Menandai penulis artikel berita/CMS di sistem.

### 2. Fasilitas & Kamar
- **`gedung`** (1) $\rightarrow$ memiliki $\rightarrow$ (N) **`ruangan`**  
  Struktur fisik gedung asrama haji yang berisi ruangan-ruangan.
- **`ruangan`** (1) $\rightarrow$ memiliki $\rightarrow$ (N) **`media_file`**  
  Galeri foto/media spesifik untuk tiap ruangan.
- **`ruangan`** (1) $\rightarrow$ memiliki $\rightarrow$ (N) **`paket_ruangan`**  
  Paket sewa ruangan (misal: sewa kamar harian, sewa aula 4 jam).

### 3. Transaksi & Reservasi
- **`paket_ruangan`** (1) $\rightarrow$ dipesan $\rightarrow$ (N) **`peminjaman_transaksi`**  
  Transaksi peminjaman/booking yang memesan suatu layanan/paket ruangan.
- **`guest`** (1) $\rightarrow$ melakukan $\rightarrow$ (N) **`peminjaman_transaksi`**  
  Tamu yang memesan dan bertanggung jawab atas reservasi.
- **`users`** (1) $\rightarrow$ mengelola $\rightarrow$ (N) **`peminjaman_transaksi`**  
  Staf operasional/admin yang melakukan approval/penolakan atas reservasi.
- **`peminjaman_transaksi`** (1) $\rightarrow$ memiliki $\rightarrow$ (1) **`invoice`**  
  Detail tagihan keuangan pembayaran untuk setiap booking yang dibuat.

### 4. Inventaris & Sarana
- **`sarana`** (1) $\rightarrow$ memiliki $\rightarrow$ (N) **`detail_peminjaman_sarana`**
- **`peminjaman_transaksi`** (1) $\rightarrow$ memiliki $\rightarrow$ (N) **`detail_peminjaman_sarana`**  
  Hubungan *Many-to-Many* antara transaksi peminjaman dengan sarana/fasilitas tambahan (seperti meja, sound system, kursi) yang dijembatani oleh entitas detail.

### 5. Konten & Pengaturan (Standalone)
- **`tentang`**: Pengaturan profil instansi/kontak.
- **`galeri`**: Galeri dokumentasi foto umum instansi.
