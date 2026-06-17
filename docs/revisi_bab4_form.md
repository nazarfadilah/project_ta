# DRAF REVISI BAB IV - DESKRIPSI & PENEMPATAN GAMBAR FORM KELOLA
## SISTEM INFORMASI PEMINJAMAN SARANA DAN PRASARANA (SIPRASA)

Dokumen ini berisi draf kalimat penjelasan (deskripsi) singkat beserta **petunjuk penempatan gambar (screenshot) dan caption gambar** untuk setiap halaman **Form Kelola** di Bab IV laporan Tugas Akhir Anda.

> [!NOTE]
> Karena kita menyisipkan gambar baru untuk setiap form kelola, **nomor urut Gambar (Figure) setelah Gambar 4.10 akan bergeser**. 
> Panduan di bawah ini menyertakan nomor gambar baru (dalam tanda kurung siku) serta nomor gambar lama (sebelum revisi) sebagai referensi penomoran ulang Anda.

---

### 11. Form Kelola Gedung
**Penempatan dalam Dokumen:**
Letakkan bagian ini tepat di bawah penjelasan **10. Halaman Kelola Gedung**, sebelum masuk ke sub-bab **12. Halaman Kelola Ruangan**.

**Draf Teks & Posisi Gambar:**
> Halaman form kelola gedung digunakan oleh pengelola sistem (petugas atau administrator) untuk melakukan penginputan data gedung baru maupun melakukan pembaruan (editing) pada data gedung yang telah tersimpan sebelumnya di dalam sistem. Tampilan halaman form kelola gedung dapat dilihat pada Gambar 4.11.
>
> ---
> **[TEMPATKAN SCREENSHOT FORM KELOLA GEDUNG DI SINI]**
> **Gambar 4.11 Tampilan Halaman Form Kelola Gedung**
> ---
>
> Formulir pengisian data gedung ini dirancang secara ringkas, dengan rincian kolom pengisian yaitu nama gedung, koordinat lokasi gedung, serta keterangan tambahan mengenai gedung yang bersangkutan. Setelah seluruh data terisi dengan benar dan tombol simpan ditekan, sistem akan memvalidasi data dan menyimpannya ke database MySQL.

---

### 13. Form Kelola Ruangan
**Penempatan dalam Dokumen:**
Letakkan bagian ini tepat di bawah penjelasan **12. Halaman Kelola Ruangan** *(catatan: Gambar Halaman Kelola Ruangan yang sebelumnya Gambar 4.11 kini bergeser menjadi Gambar 4.12)*, sebelum masuk ke sub-bab **14. Halaman Kelola Sarana**.

**Draf Teks & Posisi Gambar:**
> Halaman form kelola ruangan digunakan oleh petugas untuk melakukan penambahan data ruangan atau kamar baru, maupun melakukan pembaruan informasi detail ruangan yang berada di bawah masing-masing gedung. Tampilan halaman form kelola ruangan dapat dilihat pada Gambar 4.13.
>
> ---
> **[TEMPATKAN SCREENSHOT FORM KELOLA RUANGAN DI SINI]**
> **Gambar 4.13 Tampilan Halaman Form Kelola Ruangan**
> ---
>
> Formulir pengisian data ruangan dirancang secara komprehensif, dengan rincian kolom pengisian yaitu pilihan gedung, nama ruangan, tipe ruangan, lantai, kapasitas tampung, kebijakan gender, keterangan, serta pilihan unggah berkas foto ruangan. Setelah data dikirim, sistem akan memvalidasi data dan mengunggah berkas foto ke server sebelum disimpan ke basis data.

---

### 15. Form Kelola Sarana
**Penempatan dalam Dokumen:**
Letakkan bagian ini tepat di bawah penjelasan **14. Halaman Kelola Sarana** *(catatan: Gambar Halaman Kelola Sarana yang sebelumnya Gambar 4.12 kini bergeser menjadi Gambar 4.14)*, sebelum masuk ke sub-bab **16. Halaman Kelola Paket Ruangan**.

**Draf Teks & Posisi Gambar:**
> Halaman form kelola sarana berfungsi bagi administrator atau petugas untuk mendaftarkan sarana/inventaris peralatan baru serta mengedit informasi sarana pendukung sewa yang telah terdaftar. Tampilan halaman form kelola sarana dapat dilihat pada Gambar 4.15.
>
> ---
> **[TEMPATKAN SCREENSHOT FORM KELOLA SARANA DI SINI]**
> **Gambar 4.15 Tampilan Halaman Form Kelola Sarana**
> ---
>
> Formulir pengisian data sarana dirancang dengan rincian kolom pengisian yaitu nama sarana, kondisi sarana, tanggal penerimaan inventaris, dan jumlah stok barang. Pengisian form ini bertujuan untuk memastikan kelayakan kondisi barang dan ketersediaan stok inventaris yang terintegrasi dinamis dengan peminjaman sarana.

---

### 17. Form Kelola Paket Ruangan
**Penempatan dalam Dokumen:**
Letakkan bagian ini tepat di bawah penjelasan **16. Halaman Kelola Paket Ruangan** *(catatan: Gambar Halaman Kelola Paket Ruangan yang sebelumnya Gambar 4.13 kini bergeser menjadi Gambar 4.16)*, sebelum masuk ke sub-bab **18. Halaman Kelola Akun Pengguna**.

**Draf Teks & Posisi Gambar:**
> Halaman form kelola paket ruangan digunakan untuk mendefinisikan skema tarif harga sewa dari setiap ruangan yang terdaftar di Asrama Haji. Tampilan halaman form kelola paket ruangan dapat dilihat pada Gambar 4.17.
>
> ---
> **[TEMPATKAN SCREENSHOT FORM KELOLA PAKET RUANGAN DI SINI]**
> **Gambar 4.17 Tampilan Halaman Form Kelola Paket Ruangan**
> ---
>
> Formulir pengisian data paket ruangan memuat rincian kolom pengisian yaitu pilihan ruangan, nama paket sewa, durasi sewa, nominal harga sewa, dan pilihan status keaktifan paket. Pengaturan ini menentukan pilihan tarif yang muncul secara dinamis pada saat tamu melakukan reservasi.

---

### 19. Form Kelola Users/Pengguna
**Penempatan dalam Dokumen:**
Letakkan bagian ini tepat di bawah penjelasan **18. Halaman Kelola Akun Pengguna** *(catatan: Gambar Halaman Kelola Akun Pengguna yang sebelumnya Gambar 4.14 kini bergeser menjadi Gambar 4.18)*, sebelum masuk ke sub-bab **20. Halaman Kelola data Tamu**.

**Draf Teks & Posisi Gambar:**
> Halaman form kelola users/pengguna digunakan oleh administrator untuk melakukan pembaruan (edit) kredensial data akun pengguna yang terdaftar di dalam sistem SIPRASA. Tampilan halaman form kelola pengguna dapat dilihat pada Gambar 4.19.
>
> ---
> **[TEMPATKAN SCREENSHOT FORM KELOLA USERS/PENGGUNA DI SINI]**
> **Gambar 4.19 Tampilan Halaman Form Kelola Users/Pengguna**
> ---
>
> Formulir pengeditan akun pengguna dirancang secara sederhana, dengan rincian kolom pengisian yaitu nama pengguna (username) dan alamat email aktif. Pembaruan data ini berguna untuk verifikasi dan pemeliharaan hak akses akun pengguna sistem.

---

### 21. Form Kelola data Tamu
**Penempatan dalam Dokumen:**
Letakkan bagian ini tepat di bawah penjelasan **20. Halaman Kelola data Tamu** *(catatan: Gambar Halaman Kelola data Tamu yang sebelumnya Gambar 4.15 kini bergeser menjadi Gambar 4.20)*, sebelum masuk ke sub-bab **22. Halaman Kelola Berita**.

**Draf Teks & Posisi Gambar:**
> Halaman form kelola data tamu digunakan oleh petugas atau administrator untuk menginput data identitas tamu baru secara manual atau memperbarui detail informasi tamu yang telah terdaftar guna keperluan verifikasi fisik administrasi. Tampilan halaman form kelola data tamu dapat dilihat pada Gambar 4.21.
>
> ---
> **[TEMPATKAN SCREENSHOT FORM KELOLA DATA TAMU DI SINI]**
> **Gambar 4.21 Tampilan Halaman Form Kelola data Tamu**
> ---
>
> Formulir pengisian data tamu dirancang secara detail, dengan rincian kolom pengisian yaitu Nomor Induk Kependudukan (NIK), nama lengkap, jenis kelamin, golongan darah, alamat lengkap, dan catatan tambahan terkait tamu tersebut.

---

### 23. Form Kelola Berita
**Penempatan dalam Dokumen:**
Letakkan bagian ini tepat di bawah penjelasan **22. Halaman Kelola Berita** *(catatan: Gambar Halaman Kelola Berita yang sebelumnya Gambar 4.16 kini bergeser menjadi Gambar 4.22)*, sebelum masuk ke sub-bab **24. Halaman Kelola Landing Page Admin**.

**Draf Teks & Posisi Gambar:**
> Halaman form kelola berita berfungsi sebagai modul *Content Management System* (CMS) bagi petugas untuk membuat artikel informasi baru atau menyunting berita pengumuman yang akan dipajang di landing page utama website. Tampilan halaman form kelola berita dapat dilihat pada Gambar 4.23.
>
> ---
> **[TEMPATKAN SCREENSHOT FORM KELOLA BERITA DI SINI]**
> **Gambar 4.23 Tampilan Halaman Form Kelola Berita**
> ---
>
> Formulir kelola berita terdiri atas rincian kolom pengisian yaitu judul berita, isi konten berita, tanggal publikasi, dan pilihan unggah berkas gambar thumbnail berita. Artikel baru yang berhasil disimpan akan berstatus draf sebelum divalidasi oleh administrator.

---

### 25. Form Kelola Landing Page
**Penempatan dalam Dokumen:**
Letakkan bagian ini tepat di bawah penjelasan **24. Halaman Kelola Landing Page Admin** *(catatan: Gambar Halaman Kelola Landing Page yang sebelumnya Gambar 4.17 kini bergeser menjadi Gambar 4.24)*, sebelum masuk ke sub-bab **26. Halaman Validasi Peminjaman Petugas** *(catatan: Gambar Halaman Verifikasi Peminjaman yang sebelumnya Gambar 4.18 kini bergeser menjadi Gambar 4.26, dan Gambar Halaman Laporan Statistik yang sebelumnya Gambar 4.19 kini bergeser menjadi Gambar 4.27)*.

**Draf Teks & Posisi Gambar:**
> Halaman form kelola landing page merupakan antarmuka khusus administrator untuk memperbarui data statis landing page secara fleksibel langsung melalui aplikasi tanpa perlu melakukan modifikasi langsung pada baris kode program website (*hardcoded*). Tampilan halaman form kelola landing page dapat dilihat pada Gambar 4.25.
>
> ---
> **[TEMPATKAN SCREENSHOT FORM KELOLA LANDING PAGE DI SINI]**
> **Gambar 4.25 Tampilan Halaman Form Kelola Landing Page**
> ---
>
> Formulir kelola landing page dirancang secara dinamis menurut sub-modul yang dipilih, dengan rincian kolom pengisian berupa berkas logo instansi, profil tentang kami, visi, misi, tanya-jawab FAQ, berkas foto galeri beserta keterangan gambar, berkas gambar banner, serta dokumen kebijakan privasi dan syarat ketentuan.

---

## Ringkasan Pergeseran Nomor Gambar Laporan (Figure Numbering Shift)

| Nama Gambar (Tampilan Halaman) | Nomor Gambar Sebelumnya | Nomor Gambar Sesudah Revisi | Status Gambar |
| :--- | :---: | :---: | :---: |
| Halaman Kelola Gedung | Gambar 4.10 | **Gambar 4.10** | Tetap |
| **Form Kelola Gedung** | *Belum Ada* | **Gambar 4.11** | **[BARU]** |
| Halaman Kelola Ruangan | Gambar 4.11 | **Gambar 4.12** | Bergeser |
| **Form Kelola Ruangan** | *Belum Ada* | **Gambar 4.13** | **[BARU]** |
| Halaman Kelola Sarana | Gambar 4.12 | **Gambar 4.14** | Bergeser |
| **Form Kelola Sarana** | *Belum Ada* | **Gambar 4.15** | **[BARU]** |
| Halaman Kelola Paket Ruangan | Gambar 4.13 | **Gambar 4.16** | Bergeser |
| **Form Kelola Paket Ruangan** | *Belum Ada* | **Gambar 4.17** | **[BARU]** |
| Halaman Kelola Akun Pengguna | Gambar 4.14 | **Gambar 4.18** | Bergeser |
| **Form Kelola Users/Pengguna** | *Belum Ada* | **Gambar 4.19** | **[BARU]** |
| Halaman Kelola data Tamu | Gambar 4.15 | **Gambar 4.20** | Bergeser |
| **Form Kelola data Tamu** | *Belum Ada* | **Gambar 4.21** | **[BARU]** |
| Halaman Kelola Berita | Gambar 4.16 | **Gambar 4.22** | Bergeser |
| **Form Kelola Berita** | *Belum Ada* | **Gambar 4.23** | **[BARU]** |
| Halaman Kelola Landing Page | Gambar 4.17 | **Gambar 4.24** | Bergeser |
| **Form Kelola Landing Page** | *Belum Ada* | **Gambar 4.25** | **[BARU]** |
| Halaman Verifikasi Peminjaman | Gambar 4.18 | **Gambar 4.26** | Bergeser |
| Halaman Statistik Laporan Peminjaman | Gambar 4.19 | **Gambar 4.27** | Bergeser |
