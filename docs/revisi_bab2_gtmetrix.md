# Revisi Bab II: Penambahan Sub-bab GTMetrix Berdasarkan Jurnal Mongkau et al. (2023)

Dokumen ini berisi materi revisi untuk Bab II Proposal/Laporan Tugas Akhir Anda. Seluruh konten di bawah ini disadur langsung dari jurnal **"Analisis Performa Website Menggunakan GTMetrix"** oleh **Desmoon Christopher Mongkau, Arya Berelaku, dan Sitti Arni (2023)** yang diterbitkan pada *Jurnal Minfo Polgan*.

Selain penambahan materi GTMetrix, dokumen ini juga menyertakan hasil audit sitasi untuk menyelaraskan seluruh kutipan pada Bab II dengan Daftar Pustaka.

---

## 1. Pengganti dan Penambahan Baris pada Tabel 2.1 (State of the Art)

Lakukan perubahan berikut pada **Tabel 2.1 State of the art** Anda:

1. **Koreksi Baris 10:** Ganti sitasi penulis `(Anwar et al., 2024)` menjadi `(Arisantoso et al., 2024)` agar sesuai dengan nama penulis utama buku acuan (*A. Arisantoso*).
2. **Tambahkan Baris 12:** Tambahkan baris baru di bagian akhir tabel dengan data penelitian GTMetrix berikut:

| No. | Penulis, Tahun | Fokus/Objek | Metode/Tools | Hasil Utama |
| :---: | :--- | :--- | :--- | :--- |
| **10** | (Arisantoso et al., 2024) | Pemrograman web antarmuka. | HTML dan CSS. | HTML dan CSS merupakan kerangka standar pembangunan antarmuka website. |
| **11** | (Kanwil Kemenag Kalsel, 2020) | Rencana strategis sarana keagamaan. | Dokumen Perencanaan (Renstra). | Panduan arah kebijakan pelayanan dan pengelolaan fasilitas operasional instansi. |
| **12** | (Mongkau et al., 2023) | Analisis performa dan kecepatan website. | Automated software testing GTMetrix. | Pengujian otomatis website instansi pendidikan memperoleh predikat Grade F dengan nilai rata-rata *performance* 31% dan *structure* 45%, serta mengidentifikasi pentingnya optimasi pada LCP, TBT, dan CLS. |

---

## 2. Pengisian Materi Sub-bab 2.2.9 GTMetrix

Ganti sub-bab **2.2.9 GTMetrix** (halaman 11 pada draf PDF Anda) dengan penjelasan teoritis dan kontribusi implementasi berikut:

### **2.2.9 GTMetrix**
GTMetrix merupakan sebuah *tool* atau perangkat lunak berbasis web (*automated software testing tool*) yang digunakan secara otomatis untuk menganalisis, mengukur, dan mengevaluasi kinerja serta kecepatan pemuatan sebuah halaman website (Mongkau et al., 2023). Kualitas performa sebuah website sangat memengaruhi kenyamanan pengguna, di mana performa dan waktu pemuatan (*loading time*) yang cepat akan membantu pengguna mengakses data secara efisien (Mongkau et al., 2023). Melalui pengujian menggunakan GTMetrix, pengembang dapat memperoleh data performa yang objektif berupa indikator kelas (*GTmetrix Grade*) dari A hingga F, persentase nilai performa (*Performance Score*), persentase struktur kode (*Structure Score*), serta visualisasi alur kecepatan pemuatan halaman (*Speed Visualization*) (Mongkau et al., 2023).

Dalam menilai kualitas kecepatan dan responsivitas website, GTMetrix menggunakan metrik standar industri yang dikenal sebagai *Web Vitals* dengan batasan nilai maksimal sebagai berikut (Mongkau et al., 2023):
1. **Largest Contentful Paint (LCP):** Mengukur waktu pemuatan konten visual utama atau terbesar pada halaman. Standar maksimal LCP pada GTMetrix adalah **1.2 detik**.
2. **Total Blocking Time (TBT):** Mengukur total durasi pemblokiran waktu respons halaman akibat proses eksekusi skrip selama pemuatan halaman. Standar maksimal TBT adalah **150 milisekon**.
3. **First Contentful Paint (FCP):** Mengukur waktu yang dibutuhkan sistem untuk menampilkan elemen konten pertama di layar browser. Standar maksimal FCP adalah **0.9 detik**.
4. **Speed Index (SI):** Menunjukkan seberapa cepat konten halaman web secara visual terisi penuh selama proses pemuatan. Standar maksimal SI adalah **1.3 detik**.
5. **Time to Interactive (TTI):** Mengukur durasi waktu hingga halaman website siap sepenuhnya untuk berinteraksi secara responsif dengan tindakan pengguna. Standar maksimal TTI adalah **2.5 detik**.
6. **Cumulative Layout Shift (CLS):** Mengukur stabilitas tata letak visual halaman guna mendeteksi pergeseran elemen yang tidak terduga saat pemuatan halaman. Skor standar maksimal CLS adalah **0.1**.

Jika hasil pengujian suatu website melebihi batas standar maksimal tersebut, maka nilai performa dan grade yang diperoleh akan menurun. Nilai performa GTMetrix dikelompokkan ke dalam empat indikator warna seperti yang ditunjukkan pada Tabel 2.2 berikut (Mongkau et al., 2023):

**Tabel 2.2 Indikator Warna Skor Performa GTMetrix**

| Warna | Score Performa | Keterangan Kualitas |
| :---: | :---: | :--- |
| Hijau | 91% - 100% | Sangat Baik (Optimal) |
| Hijau Muda | 76% - 90% | Baik |
| Orange | 51% - 75% | Cukup Baik (Butuh Perbaikan) |
| Merah | 0% - 50% | Buruk (Kritis) |

Untuk melakukan analisis performa website menggunakan platform GTMetrix, terdapat beberapa langkah operasional yang harus dijalankan, yaitu (Mongkau et al., 2023):
1. Membuka halaman resmi GTMetrix melalui tautan web [https://gtmetrix.com/](https://gtmetrix.com/).
2. Memasukkan tautan URL halaman website yang akan diuji ke dalam kotak input "Enter URL to Analyze" pada halaman utama.
3. Menekan tombol "Analyze" untuk memulai proses pengujian.
4. Menunggu beberapa saat selagi sistem melakukan pemindaian otomatis terhadap server dan elemen aset halaman web.
5. Membaca laporan ringkasan performa yang memuat nilai *PageSpeed*, durasi waktu pemuatan, ukuran file halaman, jumlah *request*, serta rekomendasi perbaikan untuk optimasi.

Dalam Tugas Akhir ini, teori GTMetrix diterapkan sebagai instrumen evaluasi non-fungsional untuk mengukur kualitas performa Sistem Informasi Peminjaman Ruangan dan Sarana Prasarana (SIPRASA) yang telah di-deploy ke server produksi (domain resmi `https://asramahajibdj.com`). Hasil analisis dari GTMetrix berupa rekomendasi optimasi (seperti kompresi gambar, penghapusan *render-blocking assets*, serta *minify* CSS dan JavaScript) akan digunakan sebagai dasar perbaikan untuk meningkatkan responsivitas dan kecepatan akses sistem bagi pengguna (Mongkau et al., 2023).

---

## 3. Hasil Audit dan Perbaikan Sitasi Bab II

Berdasarkan pemeriksaan menyeluruh terhadap keselarasan sitasi pada Bab II dan berkas fisik jurnal/buku asli pada direktori `docs\File Jurnal&Buku`, berikut adalah perbaikan penting yang harus disesuaikan:

1. **Perbaikan Buku Pemrograman Web Dasar (`Arisantoso et al., 2024`):**
   * *Masalah:* Di dalam naskah halaman 5 dan 6, Anda menulis sitasi `(Arisantoso et al., 2024)` dan `(Arisantoso et al., 2024; Wijonarko et al., 2024)`. Namun, di Daftar Pustaka lama Anda menuliskan nama pertamanya sebagai `Anwar, A. S.`, yang merupakan gabungan salah dari penulis ke-2 (*Saipul Anwar*) dan penulis ke-1 (*Arisantoso*).
   * *Perbaikan:* Sesuai dengan buku aslinya, penulis pertama adalah **Arisantoso** dan penulis kedua adalah **Saipul Anwar**. Maka entri di Daftar Pustaka diganti menjadi diawali dengan **Arisantoso, Anwar, S.** dan diletakkan secara alfabetis di bawah huruf **A** (setelah *Akmal* dan sebelum *Budianto*).
2. **Penambahan Regulasi Renstra (`Kanwil Kemenag Kalsel, 2020`):**
   * *Masalah:* Tabel 2.1 State of the Art baris 11 mencantumkan kutipan `(Kanwil Kemenag Kalsel, 2020)`, tetapi dokumen ini belum terdaftar di halaman Daftar Pustaka Anda.
   * *Perbaikan:* Tambahkan entri resmi dokumen Rencana Strategis (Renstra) tersebut ke dalam Daftar Pustaka Anda di bawah huruf **K**.
3. **Penambahan Jurnal GTMetrix (`Mongkau et al., 2023`):**
   * *Masalah:* Materi baru GTMetrix yang ditulis pada sub-bab 2.2.9 mensitasi jurnal baru.
   * *Perbaikan:* Tambahkan referensi jurnal Mongkau, Berelaku, & Arni (2023) ke dalam Daftar Pustaka Anda di bawah huruf **M** (setelah *Melinda*).
4. **Perbaikan Halaman Jurnal Peminjaman Ruangan (`Fajri & Mujiastuti, 2024`):**
   * *Masalah:* Halaman artikel pada Daftar Pustaka tertulis `234-324` yang merupakan jangkauan seluruh halaman edisi jurnal (*issue page range*), bukan jangkauan halaman artikel khusus ini.
   * *Perbaikan:* Berdasarkan dokumen PDF jurnal asli, ganti halaman artikel menjadi jangkauan yang presisi yaitu **299–307**.
5. **Perbaikan Halaman Jurnal Laravel (`Budianto et al., 2025`):**
   * *Masalah:* Jurnal pengabdian Laravel oleh Budianto dkk. belum memiliki informasi nomor halaman di Daftar Pustaka lama.
   * *Perbaikan:* Berdasarkan dokumen PDF jurnal asli, tambahkan nomor halaman artikel yaitu **128–138**.

---

## 4. Pembaruan Halaman Daftar Pustaka (Halaman 45)

Berikut adalah susunan **Daftar Pustaka** terbaru yang sudah diperbarui, diselaraskan, dan diurutkan secara alfabetis. Ganti seluruh daftar pustaka lama Anda dengan daftar di bawah ini:

```text
DAFTAR PUSTAKA


Akmal, F. M., & Suhada, J. (2025). Perancangan Basis Data: Konsep Entitas, 
    Atribut, dan Relasi. BIIKMA: Buletin Ilmiah Ilmu Komputer dan 
    Multimedia, 3(3), 473–478.

Arisantoso, Anwar, S., Lithardo, F. R., Faisal, M., dkk. (2024). Buku Ajar 
    Pemrograman Web Dasar. Purbalingga: CV. Eureka Media Aksara.

Budianto, Wibawa, A., & Said, R. M. (2025). Pengenalan Laravel Sebagai 
    Framework Open Source Dalam Membangun Sebuah Aplikasi Berbasis 
    Web Di SMK Islamiyah Ciputat. Jurnal Peradaban Masyarakat, 5(3), 
    128–138.

Fajri, N. Z., & Mujiastuti, R. (2024). Sistem Informasi Peminjaman Ruangan dan 
    Barang Pada Fakultas Teknik Universitas Muhammadiyah Jakarta Berbasis 
    Website. Just IT: Jurnal Sistem Informasi, Teknologi Informasi dan 
    Komputer, 15(1), 299–307.

Kanwil Kemenag Kalsel. (2020). Rencana Strategis Kantor Wilayah Kementerian 
    Agama Provinsi Kalimantan Selatan Tahun 2020–2024. Banjarmasin: Kantor 
    Wilayah Kementerian Agama Provinsi Kalimantan Selatan.

Melinda, N. (2025). Analisis SWOT Bimbingan Pemantapan Manasik Haji di UPT 
    Asrama Haji Embarkasi Banjarmasin Tahun 2024. Jurnal Manajemen 
    Dakwah, 3(2), 125–141.

Mongkau, D. C., Berelaku, A., & Arni, S. (2023). Analisis Performa Website 
    Menggunakan GTMetrix. Jurnal Minfo Polgan, 12(2), 857–861. 
    https://doi.org/10.33395/jmp.v12i2.12518

Permana, A. A. J., Apriyanto, Nirsal, dkk. (2024). Buku Ajar Pengantar Sistem 
    Informasi. Jambi: PT. Sonpedia Publishing Indonesia.

Siregar, Y. S., Sembiring, B. O., Rahayu, E., Hasdiana, & Franchitika, R. (2024). 
    Pemanfaatan Aplikasi MySQL untuk Membantu Siswa SMK Swasta Nur 
    Azizi dalam Pengolahan Data. Jurnal Pengabdian Masyarakat (JAPAMAS), 
    3(2), 229–240.

Tindi, A. P., & Lumasuge, O. (2025). Sistem Penjadwalan dan Notifikasi Petugas 
    Ibadah Kelompok Berbasis Web dengan API Pesan Instan. JUMINTAL: 
    Jurnal Manajemen Informatika dan Bisnis Digital, 4(1), 38–49.

Wahid, A. A. (2020). Analisis Metode Waterfall Untuk Pengembangan Sistem 
    Informasi. Jurnal Ilmu-ilmu Informatika dan Manajemen STMIK 
    (INFOMAN'S), 14(2), 1-5.

Wijonarko, P., Cahya, R. D., Purnomo, B. A., Iswanto, W., Burhanudin, & Akbar, 
    R. (2024). Pengenalan Website sebagai Media Informasi dan Promosi Desa. 
    MENGABDI: Jurnal Hasil Kegiatan Bersama Masyarakat, 2(4), 198–208.
```
