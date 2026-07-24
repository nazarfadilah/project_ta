# Revisi Bab II: Penambahan Sub-bab Blackbox Testing Berdasarkan Jurnal Wijaya & Astuti (2021)

Dokumen ini berisi materi revisi untuk Bab II Proposal/Laporan Tugas Akhir Anda. Seluruh konten di bawah ini disadur dan diselaraskan langsung dari jurnal **"Pengujian Blackbox Sistem Informasi Penilaian Kinerja Karyawan PT INKA (Persero) Berbasis Equivalence Partitions"** oleh **Yahya Dwi Wijaya dan Muna Wardah Astuti (2021)** yang diterbitkan pada *Jurnal Digital Teknologi Informasi*.

---

## 1. Penambahan Baris pada Tabel 2.1 (State of the Art)

Lakukan penambahan baris berikut pada **Tabel 2.1 State of the art** Anda di bagian akhir (baris nomor 13):

| No. | Penulis, Tahun | Fokus/Objek | Metode/Tools | Hasil Utama |
| :---: | :--- | :--- | :--- | :--- |
| **11** | (Kanwil Kemenag Kalsel, 2020) | Rencana strategis sarana keagamaan. | Dokumen Perencanaan (Renstra). | Panduan arah kebijakan pelayanan dan pengelolaan fasilitas operasional instansi. |
| **12** | (Mongkau et al., 2023) | Analisis performa dan kecepatan website. | Automated software testing GTMetrix. | Pengujian otomatis website instansi pendidikan memperoleh predikat Grade F dengan nilai rata-rata *performance* 31% dan *structure* 45%, serta mengidentifikasi pentingnya optimasi pada LCP, TBT, dan CLS. |
| **13** | (Wijaya & Astuti, 2021) | Pengujian fungsionalitas sistem informasi penilaian kinerja karyawan. | Black Box Testing berbasis teknik *Equivalence Partitions*. | Pengujian fungsionalitas menggunakan 11 butir kasus uji (*test case*) pada formulir login, registrasi, dan tambah data membuktikan seluruh fitur sistem berjalan dengan baik tanpa ditemukannya kesalahan fungsionalitas. |

---

## 2. Pengisian Materi Sub-bab 2.2.9 Blackbox Testing

Ganti atau tambahkan sub-bab **2.2.9 Blackbox Testing** pada naskah Bab II Anda dengan penjelasan teoritis dan kontribusi implementasi berikut:

### **2.2.9 Blackbox Testing**
*Blackbox testing* (pengujian kotak hitam) merupakan salah satu metodologi pengujian kualitas perangkat lunak yang berfokus sepenuhnya pada aspek fungsionalitas sistem tanpa melibatkan pengetahuan tentang struktur kode program, logika internal, atau jalur eksekusi di dalam aplikasi (Wijaya & Astuti, 2021). Melalui pendekatan ini, penguji memosisikan diri sebagai pengguna akhir untuk memastikan bahwa setiap fungsi yang disediakan oleh perangkat lunak dapat merespons masukan (*input*) dan menghasilkan keluaran (*output*) yang sesuai dengan spesifikasi kebutuhan yang telah ditentukan. Menurut Wijaya & Astuti (2021), pengujian ini dirancang secara khusus untuk mendeteksi berbagai jenis kesalahan atau galat, seperti fungsi yang tidak benar atau hilang, kesalahan pada antarmuka pengguna, kesalahan pada struktur data atau akses basis data eksternal, kesalahan performansi sistem, serta kesalahan dalam proses inisialisasi dan terminasi program.

Untuk menunjang efektivitas pengujian kotak hitam, salah satu teknik terstruktur yang sangat umum digunakan adalah teknik *Equivalence Partitions* (EP). *Equivalence partitions* merupakan metode pengujian berbasis data masukan di mana rentang atau domain input dari setiap formulir dikelompokkan ke dalam beberapa kelas data ekuivalen yang bernilai valid maupun tidak valid, lalu penguji memilih satu atau beberapa perwakilan nilai dari setiap kelas tersebut untuk dijadikan sebagai kasus uji (*test case*) (Wijaya & Astuti, 2021). Secara operasional, tahapan pelaksanaan pengujian ini diawali dengan mengidentifikasi seluruh formulir masukan pada sistem, menentukan batasan nilai input yang valid dan invalid untuk setiap kolom, menyusun skenario serta rancangan kasus uji yang memuat ID pengujian dan hasil yang diharapkan, serta diakhiri dengan mengeksekusi pengujian tersebut pada lingkungan uji guna mendokumentasikan kesesuaian antara hasil aktual dan hasil yang diharapkan (Wijaya & Astuti, 2021).

Dalam pengembangan Sistem Informasi Peminjaman Ruangan dan Sarana Prasarana (SIPRASA), teori *blackbox testing* dengan teknik *equivalence partitions* ini diterapkan sebagai instrumen pengujian fungsional untuk menguji keandalan sistem secara menyeluruh. Pengujian difokuskan pada semua formulir interaktif sistem—seperti formulir masuk (*login*), pendaftaran akun tamu (*guest register*), pemesanan ruangan dan sarana, input data master berupa gedung, ruangan, maupun paket sewa, serta formulir pengelolaan transaksi—untuk memastikan sistem secara dinamis mampu mengenali serta menolak data masukan yang tidak valid seperti kolom kosong, kesalahan format karakter, maupun konflik jadwal peminjaman, serta memproses seluruh data masukan yang valid ke dalam database dengan benar (Wijaya & Astuti, 2021).

---

## 3. Hasil Audit dan Perbaikan Sitasi Bab II

Berdasarkan penambahan materi *blackbox testing*, sesuaikan keselarasan sitasi pada naskah Anda sebagai berikut:

1. **Format Sitasi dalam Naskah (*In-text Citation*):**
   Gunakan format berikut di dalam narasi Bab II Anda:
   - **Format kurung (di akhir kalimat):** `(Wijaya & Astuti, 2021)`
   - **Format aktif (sebagai subjek):** `Wijaya & Astuti (2021)`
2. **Penambahan Jurnal Blackbox Testing (`Wijaya & Astuti, 2021`):**
   Tambahkan referensi jurnal Wijaya & Astuti (2021) ke dalam Daftar Pustaka Anda di bawah urutan abjad huruf **W** (setelah referensi *Wahid, A. A.* dan sebelum *Wijonarko, P.*).

---

## 4. Pembaruan Halaman Daftar Pustaka (Halaman 45)

Berikut adalah pembaruan pada bagian bawah **Daftar Pustaka** Anda. Sisipkan referensi baru ini di antara referensi **Wahid (2020)** dan **Wijonarko (2024)**:

```text
...
Tindi, A. P., & Lumasuge, O. (2025). Sistem Penjadwalan dan Notifikasi Petugas 
    Ibadah Kelompok Berbasis Web dengan API Pesan Instan. JUMINTAL: 
    Jurnal Manajemen Informatika dan Bisnis Digital, 4(1), 38–49.

Wahid, A. A. (2020). Analisis Metode Waterfall Untuk Pengembangan Sistem 
    Informasi. Jurnal Ilmu-ilmu Informatika dan Manajemen STMIK 
    (INFOMAN'S), 14(2), 1-5.

Wijaya, Y. D., & Astuti, M. W. (2021). Pengujian Blackbox Sistem Informasi 
    Penilaian Kinerja Karyawan PT INKA (Persero) Berbasis Equivalence 
    Partitions. Jurnal Digital Teknologi Informasi, 4(1), 22–26. 
    http://jurnal.um-palembang.ac.id/index.php/digital

Wijonarko, P., Cahya, R. D., Purnomo, B. A., Iswanto, W., Burhanudin, & Akbar, 
    R. (2024). Pengenalan Website sebagai Media Informasi dan Promosi Desa. 
    MENGABDI: Jurnal Hasil Kegiatan Bersama Masyarakat, 2(4), 198–208.
```
