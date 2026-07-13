# DAFTAR STRUKTUR TABEL DAN PENJELASAN (SIPRASA)

Dokumen ini memuat daftar keseluruhan tabel database terbaru berdasarkan skema SQL `siprasa_db_model.sql` lengkap dengan penjelasan/caption akademis yang terstruktur untuk bab perancangan sistem.

---

Tabel role menyimpan data hak akses (role) pengguna, seperti Admin, Pimpinan, Petugas, dan Tamu/Peminjam. Kolom name_role menyimpan nama hak akses secara unik. Rincian atribut tabel role ditunjukkan pada Tabel 3.1.

**Tabel 3.1 Struktur Tabel role**

| No | Field | Tipe Data | Size | Keterangan |
| :---: | :--- | :--- | :---: | :--- |
| 1 | id_role | INT | - | Primary Key, Auto Increment |
| 2 | name_role | VARCHAR | 100 | Unique, Not Null |
| 3 | description_role | TEXT | - | Nullable |
| 4 | permissions | JSON | - | Nullable |

---

Tabel guest menyimpan biodata lengkap tamu (guest) penyewa ruangan atau sarana prasarana. Kolom nik menyimpan nomor induk kependudukan secara unik sebagai penanda identitas utama, sedangkan kolom name_guest menyimpan nama lengkap tamu. Kolom instansi dan address masing-masing mencatat nama asal instansi dan alamat tempat tinggal tamu. Rincian atribut tabel guest ditunjukkan pada Tabel 3.2.

**Tabel 3.2 Struktur Tabel guest**

| No | Field | Tipe Data | Size | Keterangan |
| :---: | :--- | :--- | :---: | :--- |
| 1 | id_guest | INT | - | Primary Key, Auto Increment |
| 2 | nik | CHAR | 16 | Unique, Not Null |
| 3 | name_guest | VARCHAR | 255 | Not Null |
| 4 | gender | ENUM | 'MALE', 'FEMALE' | Not Null |
| 5 | phone_guest | CHAR | 15 | Nullable |
| 6 | instansi | TEXT | - | Nullable |
| 7 | address | TEXT | - | Nullable |
| 8 | bloodType | VARCHAR | 5 | Nullable |
| 9 | notes_guest | TEXT | - | Nullable |

---

Tabel users menyimpan data akun pengguna sistem untuk keperluan login, baik staf internal maupun tamu. Kolom email menyimpan alamat email unik untuk autentikasi, sedangkan kolom roleId merujuk pada hak akses akun tersebut pada tabel role. Kolom guestId menghubungkan akun dengan biodata tamu jika pengguna merupakan pihak eksternal. Rincian atribut tabel users ditunjukkan pada Tabel 3.3.

**Tabel 3.3 Struktur Tabel users**

| No | Field | Tipe Data | Size | Keterangan |
| :---: | :--- | :--- | :---: | :--- |
| 1 | id_users | BIGINT | - | Primary Key, Auto Increment |
| 2 | google_id | VARCHAR | 255 | Nullable |
| 3 | username | VARCHAR | 100 | Unique, Not Null |
| 4 | email | VARCHAR | 255 | Unique, Not Null |
| 5 | password | VARCHAR | 255 | Nullable (jika login Google) |
| 6 | roleId | INT | - | Foreign Key -> role(id_role) |
| 7 | phone_users | VARCHAR | 20 | Nullable |
| 8 | profile_photo | VARCHAR | 255 | Nullable |
| 9 | guestId | INT | - | Foreign Key -> guest(id_guest), Nullable |
| 10 | status_users | ENUM | 'ACTIVE', 'INACTIVE', 'SUSPENDED', 'SUSPENDED_PERMANENT' | Default: 'ACTIVE' |
| 11 | blocked_reason | TEXT | - | Nullable |
| 12 | lastLoginAt | TIMESTAMP | - | Nullable |

---

Tabel ruangan menyimpan data unit ruangan atau fasilitas peminjaman utama di asrama haji. Kolom nama_ruangan menyimpan nama ruangan, sedangkan kolom tipe_ruangan menentukan kategori tipe ruangan seperti Kamar Standar, Kamar VIP, Aula, maupun Ruang Meeting. Kolom kapasitas mencatat batas maksimal daya tampung orang dalam ruangan tersebut. Rincian atribut tabel ruangan ditunjukkan pada Tabel 3.4.

**Tabel 3.4 Struktur Tabel ruangan**

| No | Field | Tipe Data | Size | Keterangan |
| :---: | :--- | :--- | :---: | :--- |
| 1 | id_ruangan | INT | - | Primary Key, Auto Increment |
| 2 | gedung_id | INT | - | Nullable |
| 3 | nama_ruangan | VARCHAR | 255 | Not Null |
| 4 | tipe_ruangan | ENUM | 'KAMAR_STANDAR', 'KAMAR_VIP', 'KAMAR_PREMIUM', 'AULA', 'RUANG_MEETING', 'RUANG_LAINNYA' | Default: 'KAMAR_STANDAR' |
| 5 | lantai | INT | - | Nullable |
| 6 | kapasitas | INT | - | Default: 1 |
| 7 | gender_policy | ENUM | 'MALE_ONLY', 'FEMALE_ONLY', 'MIXED' | Nullable |
| 8 | keterangan_ruangan | TEXT | - | Nullable |

---

Tabel paket_ruangan menyimpan informasi paket sewa (durasi dan harga sewa) untuk masing-masing ruangan yang tersedia. Kolom harga menyimpan nominal tarif sewa ruangan, sedangkan kolom ruangan_id menghubungkan paket ini ke ruangan terkait. Kolom tipe_paket menentukan jenis paket dan kolom status_paket merekam ketersediaan paket. Rincian atribut tabel paket_ruangan ditunjukkan pada Tabel 3.5.

**Tabel 3.5 Struktur Tabel paket_ruangan**

| No | Field | Tipe Data | Size | Keterangan |
| :---: | :--- | :--- | :---: | :--- |
| 1 | id_paket_ruangan | INT | - | Primary Key, Auto Increment |
| 2 | ruangan_id | INT | - | Foreign Key -> ruangan(id_ruangan) |
| 3 | nama_paket | VARCHAR | 255 | Not Null |
| 4 | tipe_paket | TINYINT | 1 | Default: 0, Not Null |
| 5 | durasi_paket | INT | - | Nullable |
| 6 | harga | DECIMAL | 15,2 | Not Null |
| 7 | currency | VARCHAR | 3 | Default: 'IDR' |
| 8 | isExclusive | TINYINT | 1 | Default: 0 |
| 9 | status_paket | ENUM | 'ACTIVE', 'INACTIVE', 'MAINTENANCE' | Default: 'ACTIVE' |

---

Tabel peminjaman_transaksi menyimpan data transaksi peminjaman ruangan beserta log status sewa dan persetujuannya oleh admin. Kolom kodePeminjaman menyimpan kode transaksi unik untuk pelacakan, sedangkan kolom statusPeminjaman memantau tahapan transaksi dari reservasi, check-in, check-out, batal, hingga selesai. Kolom tanggal dan jamMulai mencatat jadwal waktu peminjaman. Rincian atribut tabel peminjaman_transaksi ditunjukkan pada Tabel 3.6.

**Tabel 3.6 Struktur Tabel peminjaman_transaksi**

| No | Field | Tipe Data | Size | Keterangan |
| :---: | :--- | :--- | :---: | :--- |
| 1 | id_peminjaman_transaksi | INT | - | Primary Key, Auto Increment |
| 2 | kodePeminjaman | VARCHAR | 50 | Unique, Not Null |
| 3 | guestId | INT | - | Foreign Key -> guest(id_guest) |
| 4 | facilityId | INT | - | Foreign Key -> paket_ruangan(id_paket_ruangan) |
| 5 | tanggal | DATE | - | Not Null |
| 6 | jamMulai | DATETIME | - | Not Null |
| 7 | checkIn | DATETIME | - | Nullable |
| 8 | checkOut | DATETIME | - | Nullable |
| 9 | durasi_pt | INT | - | Nullable |
| 10 | statusPeminjaman | ENUM | 'RESERVASI', 'CHECK_IN', 'CHECK_OUT', 'BATAL', 'SELESAI' | Default: 'RESERVASI' |
| 11 | keterangan_pt | TEXT | - | Nullable |
| 12 | alasan_pembatalan | TEXT | - | Nullable |
| 13 | userId | BIGINT | - | Foreign Key -> users(id_users), Nullable |
| 14 | statusApproval | ENUM | 'PENDING', 'APPROVED', 'REJECTED' | Default: 'PENDING' |
| 15 | reminderSent | TINYINT | 1 | Default: 0 |
| 16 | catatanApproval | TEXT | - | Nullable |
| 17 | tanggalApproval | DATETIME | - | Nullable |
| 18 | biayaTambahan_pt | DECIMAL | 15,2 | Default: 0.00 |
| 19 | kondisiReturn | ENUM | 'BAIK', 'RUSAK_RINGAN', 'RUSAK_BERAT', 'HILANG' | Nullable |
| 20 | catatanKerusakan | TEXT | - | Nullable |
| 21 | estimasiDamage | DECIMAL | 15,2 | Nullable |

---

Tabel invoice menyimpan dokumen tagihan pembayaran dari transaksi peminjaman ruangan yang dilakukan oleh tamu. Kolom noInvoice menyimpan nomor tagihan unik, sedangkan kolom totalHarga merekam akumulasi nominal tagihan. Kolom statusInvoice mencatat status tagihan (Unpaid, Paid, Overdue) dan kolom status_pembayaran mencatat kelunasan pembayaran. Rincian atribut tabel invoice ditunjukkan pada Tabel 3.7.

**Tabel 3.7 Struktur Tabel invoice**

| No | Field | Tipe Data | Size | Keterangan |
| :---: | :--- | :--- | :---: | :--- |
| 1 | id_invoice | INT | - | Primary Key, Auto Increment |
| 2 | noInvoice | VARCHAR | 50 | Unique, Not Null |
| 3 | peminjamanId | INT | - | Foreign Key -> peminjaman_transaksi(id_peminjaman_transaksi), Unique, Not Null |
| 4 | subtotal | DECIMAL | 15,2 | Not Null |
| 5 | biayaTambahan_invoice | DECIMAL | 15,2 | Default: 0.00 |
| 6 | totalHarga | DECIMAL | 15,2 | Not Null |
| 7 | statusInvoice | ENUM | 'UNPAID', 'PARTIAL', 'PAID', 'OVERDUE' | Default: 'UNPAID' |
| 8 | status_pembayaran | ENUM | 'BELUM_BAYAR', 'SEBAGIAN', 'LUNAS' | Default: 'BELUM_BAYAR' |
| 9 | tglInvoice | DATETIME | - | Default: CURRENT_TIMESTAMP |
| 10 | tglDueDate | DATETIME | - | Nullable |
| 11 | tgl_pembayaran | DATETIME | - | Nullable |
| 12 | tglPaid | DATETIME | - | Nullable |
| 13 | notes_invoice | TEXT | - | Nullable |

---

Tabel sarana menyimpan data barang sarana prasarana pendukung operasional yang dapat disewa atau dipinjam bersama ruangan. Kolom nama_sarana menyimpan nama barang pendukung, sedangkan kolom stok mencatat jumlah ketersediaan fisik barang. Kolom kondisi memantau kelayakan sarana tersebut (Baik, Baik Sekali, Perlu Perbaikan). Rincian atribut tabel sarana ditunjukkan pada Tabel 3.8.

**Tabel 3.8 Struktur Tabel sarana**

| No | Field | Tipe Data | Size | Keterangan |
| :---: | :--- | :--- | :---: | :--- |
| 1 | id_sarana | BIGINT | - | Primary Key, Auto Increment |
| 2 | nama_sarana | VARCHAR | 128 | Not Null |
| 3 | kondisi | ENUM | 'Baik', 'Baik Sekali', 'Perlu Perbaikan' | Default: 'Baik' |
| 4 | tgl_penerimaan | DATE | - | Not Null |
| 5 | stok | VARCHAR | 5 | Not Null |

---

Tabel detail_peminjaman_sarana berfungsi sebagai tabel jembatan relasi many-to-many untuk menyimpan daftar sarana pendukung yang dipinjam dalam satu transaksi peminjaman ruangan. Kolom sarana_id merujuk pada sarana pendukung, peminjaman_id merujuk pada transaksi peminjaman utama, dan kolom jumlah mencatat kuantitas sarana yang dipinjam. Rincian atribut tabel detail_peminjaman_sarana ditunjukkan pada Tabel 3.9.

**Tabel 3.9 Struktur Tabel detail_peminjaman_sarana**

| No | Field | Tipe Data | Size | Keterangan |
| :---: | :--- | :--- | :---: | :--- |
| 1 | id_detail_peminjaman_sarana | BIGINT | - | Primary Key, Auto Increment |
| 2 | sarana_id | BIGINT | - | Foreign Key -> sarana(id_sarana) |
| 3 | peminjaman_id | INT | - | Foreign Key -> peminjaman_transaksi(id_peminjaman_transaksi) |
| 4 | jumlah | VARCHAR | 5 | Not Null |

---

Tabel media_file menyimpan berkas gambar visual yang merepresentasikan kondisi fisik dari setiap ruangan. Kolom path_media_file menyimpan lokasi penyimpanan berkas foto di server, sedangkan kolom ruangan_id menghubungkan gambar tersebut dengan ruangan terkait. Rincian atribut tabel media_file ditunjukkan pada Tabel 3.10.

**Tabel 3.10 Struktur Tabel media_file**

| No | Field | Tipe Data | Size | Keterangan |
| :---: | :--- | :--- | :---: | :--- |
| 1 | id_media_file | BIGINT | - | Primary Key, Auto Increment |
| 2 | ruangan_id | INT | - | Foreign Key -> ruangan(id_ruangan) |
| 3 | path_media_file | VARCHAR | 255 | Not Null |

---

Tabel reviews menyimpan ulasan, penilaian bintang, dan komentar dari tamu setelah transaksi peminjaman ruangan selesai dilakukan. Kolom rating menyimpan skor kepuasan berupa angka, sedangkan kolom komentar berisi komentar ulasan tertulis dari tamu. Kolom transaksi_id mereferensikan transaksi peminjaman terkait. Rincian atribut tabel reviews ditunjukkan pada Tabel 3.11.

**Tabel 3.11 Struktur Tabel reviews**

| No | Field | Tipe Data | Size | Keterangan |
| :---: | :--- | :--- | :---: | :--- |
| 1 | id_reviews | BIGINT | - | Primary Key, Auto Increment |
| 2 | transaksi_id | INT | - | Foreign Key -> peminjaman_transaksi(id_peminjaman_transaksi), Unique, Not Null |
| 3 | rating | TINYINT | - | Not Null |
| 4 | komentar | VARCHAR | 255 | Nullable |
| 5 | foto_ulasan | VARCHAR | 255 | Nullable |

---

Tabel unblock_requests menyimpan permohonan pembukaan blokir akun pengguna yang dinonaktifkan oleh sistem. Kolom verification_code menyimpan kode verifikasi berupa kode OTP yang dikirimkan ke email pengguna, sedangkan kolom reason menyimpan alasan pembukaan blokir. Rincian atribut tabel unblock_requests ditunjukkan pada Tabel 3.12.

**Tabel 3.12 Struktur Tabel unblock_requests**

| No | Field | Tipe Data | Size | Keterangan |
| :---: | :--- | :--- | :---: | :--- |
| 1 | id_unblock_requests | BIGINT | - | Primary Key, Auto Increment |
| 2 | user_id | BIGINT | - | Foreign Key -> users(id_users) |
| 3 | verification_code | VARCHAR | 10 | Nullable |
| 4 | reason | TEXT | - | Nullable |
| 5 | status_ur | ENUM | 'PENDING', 'APPROVED', 'REJECTED' | Default: 'PENDING' |
| 6 | expires_at | TIMESTAMP | - | Nullable |

---

Tabel notifications menyimpan log notifikasi pesan sistem yang dikirimkan kepada masing-masing pengguna. Kolom message menyimpan pesan teks notifikasi, kolom type mencatat tipe notifikasi, dan kolom read_at mencatat waktu pembacaan pesan oleh pengguna. Rincian atribut tabel notifications ditunjukkan pada Tabel 3.13.

**Tabel 3.13 Struktur Tabel notifications**

| No | Field | Tipe Data | Size | Keterangan |
| :---: | :--- | :--- | :---: | :--- |
| 1 | id_notifications | BIGINT | - | Primary Key, Auto Increment |
| 2 | user_id | BIGINT | - | Foreign Key -> users(id_users) |
| 3 | type | VARCHAR | 255 | Not Null |
| 4 | message | TEXT | - | Not Null |
| 5 | related_id | VARCHAR | 255 | Nullable |
| 6 | read_at | TIMESTAMP | - | Nullable |

---

Tabel berita menyimpan artikel, berita, atau pengumuman resmi yang dipublikasikan oleh administrator atau humas. Kolom judul_berita menyimpan judul artikel berita, sedangkan kolom isi_berita berisi konten lengkap artikel. Kolom status_berita mencatat status publikasi (Approved, Draft, Rejected). Rincian atribut tabel berita ditunjukkan pada Tabel 3.14.

**Tabel 3.14 Struktur Tabel berita**

| No | Field | Tipe Data | Size | Keterangan |
| :---: | :--- | :--- | :---: | :--- |
| 1 | id_berita | BIGINT | - | Primary Key, Auto Increment |
| 2 | userId | BIGINT | - | Foreign Key -> users(id_users), Nullable |
| 3 | judul_berita | VARCHAR | 128 | Not Null |
| 4 | slug | VARCHAR | 128 | Unique, Not Null |
| 5 | isi_berita | TEXT | - | Not Null |
| 6 | gambar_berita | VARCHAR | 255 | Not Null |
| 7 | tanggal_publish | DATE | - | Not Null |
| 8 | status_berita | ENUM | 'approved', 'draft', 'rejected' | Default: 'draft' |
| 9 | keterangan_berita | TEXT | - | Nullable |

---

Tabel tentang menyimpan konfigurasi dinamis untuk landing page sistem seperti profil asrama haji, visi, misi, dan kontak. Kolom key_tentang menyimpan nama konfigurasi, sedangkan kolom value_tentang menyimpan nilai atau isi teks konfigurasi tersebut. Rincian atribut tabel tentang ditunjukkan pada Tabel 3.15.

**Tabel 3.15 Struktur Tabel tentang**

| No | Field | Tipe Data | Size | Keterangan |
| :---: | :--- | :--- | :---: | :--- |
| 1 | id_tentang | BIGINT | - | Primary Key, Auto Increment |
| 2 | key_tentang | VARCHAR | 255 | Not Null |
| 3 | key2_tentang | VARCHAR | 255 | Nullable |
| 4 | value_tentang | TEXT | - | Not Null |

---

Tabel gambar_dashboard menyimpan daftar gambar slider utama yang dipajang di halaman depan (landing page) sistem. Kolom path_gambar_dashboard menyimpan lokasi penyimpanan file gambar, sedangkan kolom posisi menentukan urutan tampilan gambar slider. Rincian atribut tabel gambar_dashboard ditunjukkan pada Tabel 3.16.

**Tabel 3.16 Struktur Tabel gambar_dashboard**

| No | Field | Tipe Data | Size | Keterangan |
| :---: | :--- | :--- | :---: | :--- |
| 1 | id_gambar_dashboard | BIGINT | - | Primary Key, Auto Increment |
| 2 | posisi | TINYINT | - | Not Null |
| 3 | path_gambar_dashboard | VARCHAR | 255 | Not Null |
| 4 | waktu_upload_gambar_dashboard | TIMESTAMP | - | Not Null |

---

Tabel galeri menyimpan berkas foto atau gambar portofolio dokumentasi fasilitas asrama haji berdasarkan kategori tertentu. Kolom kategori_galeri membagi foto berdasarkan area seperti aula, masjid, atau gedung, sedangkan kolom judul_galeri menyimpan nama foto galeri. Rincian atribut tabel galeri ditunjukkan pada Tabel 3.17.

**Tabel 3.17 Struktur Tabel galeri**

| No | Field | Tipe Data | Size | Keterangan |
| :---: | :--- | :--- | :---: | :--- |
| 1 | id_galeri | BIGINT | - | Primary Key, Auto Increment |
| 2 | kategori_galeri | ENUM | 'pengapian', 'moshulla', 'aula', 'gedung' | Not Null |
| 3 | judul_galeri | VARCHAR | 128 | Not Null |
| 4 | isi_galeri | TEXT | - | Not Null |
| 5 | gambar_galeri | VARCHAR | 255 | Not Null |

---

Tabel activity_log merekam aktivitas audit trail sistem yang mencatat setiap aksi modifikasi data penting pada database untuk kebutuhan keamanan data dan audit kepatuhan. Kolom action menyimpan jenis aksi (CREATE, UPDATE, DELETE), sedangkan kolom detailPerubahan merekam rincian data sebelum dan setelah diubah dalam format JSON. Rincian atribut tabel activity_log ditunjukkan pada Tabel 3.18.

**Tabel 3.18 Struktur Tabel activity_log**

| No | Field | Tipe Data | Size | Keterangan |
| :---: | :--- | :--- | :---: | :--- |
| 1 | id_activity_log | INT | - | Primary Key, Auto Increment |
| 2 | userId | BIGINT | - | Foreign Key -> users(id_users), Nullable |
| 3 | action | VARCHAR | 100 | Not Null |
| 4 | tabelNama | VARCHAR | 100 | Not Null |
| 5 | recordId | VARCHAR | 100 | Not Null |
| 6 | detailPerubahan | JSON | - | Nullable |
| 7 | ipAddress | VARCHAR | 50 | Nullable |

---

