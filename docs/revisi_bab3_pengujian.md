# Revisi Bab III: Restrukturisasi Sub-bab 3.5 Pengujian

Dokumen ini berisi draf perbaikan untuk sub-bab **3.5 Pengujian** pada Proposal/Laporan Tugas Akhir Anda. Struktur baru ini membagi pengujian menjadi dua bagian utama, yaitu **3.5.1 Blackbox Testing** dan **3.5.2 GTMetrix** dengan tetap mempertahankan kalimat pengantar yang sudah ada, serta menambahkan sitasi dan tabel pendukung sesuai standar akademis.

Sumber Referensi & Sitasi:
1. **Blackbox Testing:** Disadur dari jurnal Wijaya & Astuti (2021) (*3163-7623-1-PB.pdf*).
2. **GTMetrix:** Disadur dari jurnal Mongkau et al. (2023) (*Analisis_Performa_Website_Menggunakan_GTMetrix_-.pdf*).

---

## 3.5 Pengujian

Setelah sistem selesai dikembangkan, pada tahap ini dilakukan pengujian terhadap seluruh fitur untuk memastikan fungsi yang dibuat telah berjalan sesuai kebutuhan dan tidak terjadi kesalahan saat digunakan. Pengujian mencakup pengecekan alur proses utama pada sistem, seperti input data, proses pengolahan, hingga keluaran informasi/laporan. Apabila pada tahap ini ditemukan fitur yang belum berfungsi dengan semestinya, maka akan dilakukan perbaikan dan penyesuaian hingga sistem dapat digunakan secara stabil dan memenuhi kebutuhan pengguna.

Dalam penelitian ini, metode pengujian dibagi menjadi dua jenis evaluasi utama, yaitu pengujian fungsionalitas sistem menggunakan metode *Blackbox Testing* dan pengujian performa non-fungsional kecepatan pemuatan website menggunakan *tools* GTMetrix.

### 3.5.1 Blackbox Testing

Pengujian sistem pada aspek fungsional dilakukan menggunakan metode *Blackbox Testing* dengan teknik *Equivalence Partitions* (EP) (Wijaya & Astuti, 2021). Pengujian ini bertujuan untuk mengetahui apakah sistem yang dikembangkan telah berjalan sesuai dengan kebutuhan fungsional yang telah ditentukan. Pengujian dilakukan dengan menguji setiap fitur sistem tanpa melihat kode program, melainkan berdasarkan input dan output yang dihasilkan oleh sistem untuk memastikan kegagalan fungsional seperti kesalahan antarmuka, inisialisasi, maupun kegagalan basis data dapat terdeteksi dan diatasi (Wijaya & Astuti, 2021).

Rancangan kasus uji (*test case*) dalam pengujian ini memuat skenario masukan data untuk memastikan sistem secara dinamis dapat menolak data yang salah (invalid) dan memproses data yang benar (valid) ke dalam basis data (Wijaya & Astuti, 2021). Tabel 3.28 berikut adalah rancangan pengujian sistem menggunakan metode *Blackbox Testing*:

*(Gunakan Tabel 3.28 yang sudah ada di halaman 87-90 draf PDF Anda, yang terdiri dari 24 nomor skenario pengujian).*

### 3.5.2 GTMetrix

Pengujian non-fungsional untuk menilai performa kecepatan akses dan optimalisasi pemuatan halaman website SIPRASA dilakukan menggunakan platform *automated software testing* GTMetrix (Mongkau et al., 2023). Pengujian ini bertujuan untuk mengevaluasi kinerja teknis beranda website pada parameter-parameter seperti ukuran halaman (*page size*), waktu muat (*loading time*), kecepatan respons server, serta stabilitas tata letak visual halaman web (Mongkau et al., 2023). Melalui hasil analisis GTMetrix, sistem akan dinilai berdasarkan akumulasi skor kinerja (*performance score*) dan struktur kode (*structure score*) untuk menghasilkan predikat kualitas berupa *GTMetrix Grade* (Mongkau et al., 2023).

Skor performa dan kelas predikat (*grade*) pada platform GTMetrix dikelompokkan ke dalam tingkat kualitas dari Grade A (terbaik) hingga Grade F (terburuk) sebagaimana ditunjukkan pada Tabel 3.29 berikut (Mongkau et al., 2023):

**Tabel 3.29 Indikator Predikat Nilai (Grade) dan Kualitas GTMetrix**

| Grade | Skor Performa (Performance Score) | Keterangan Kualitas |
| :---: | :---: | :--- |
| **A** | 90% - 100% | Sangat Baik (Sangat Optimal) |
| **B** | 80% - 89% | Baik (Optimal) |
| **C** | 70% - 79% | Cukup Baik (Butuh Sedikit Optimasi) |
| **D** | 60% - 69% | Kurang Baik (Butuh Perbaikan/Optimasi) |
| **E** | 50% - 59% | Buruk (Butuh Perbaikan Signifikan) |
| **F** | 0% - 49% | Sangat Buruk (Kritis, Butuh Optimasi Total) |

Selain pembagian *grade* utama, GTMetrix juga menggunakan visualisasi indikator warna performa untuk mempermudah identifikasi status optimalisasi halaman website. Pengelompokan indikator warna dan skor performa ditunjukkan pada Tabel 3.30 berikut (Mongkau et al., 2023):

**Tabel 3.30 Indikator Warna Skor Performa GTMetrix**

| Warna | Score Performa | Keterangan Kualitas |
| :---: | :---: | :--- |
| Hijau | 91% - 100% | Sangat Baik (Optimal) |
| Hijau Muda | 76% - 90% | Baik |
| Orange | 51% - 75% | Cukup Baik (Butuh Perbaikan) |
| Merah | 0% - 50% | Buruk (Kritis) |

Tahapan operasional pengujian performa website menggunakan GTMetrix meliputi: (1) membuka situs resmi GTMetrix, (2) memasukkan tautan URL halaman website target pada kolom analisis, (3) mengeksekusi proses analisis, (4) menunggu sistem melakukan pemindaian otomatis, dan (5) membaca ringkasan data hasil uji (*LCP, TBT, FCP, TTI, CLS*) serta rekomendasi perbaikan untuk optimasi (Mongkau et al., 2023).
