# API Documentation - SIPRASA Facility Rental System

## Base URL
```
http://localhost:8000/api
```

## Authentication
Endpoints belum menggunakan authentication khusus (development mode). Untuk production, tambahkan middleware `auth:sanctum`.

---

## 1. PEMINJAMAN TRANSAKSI ENDPOINTS

### 1.1 GET /api/peminjaman-transaksi
List semua peminjaman dengan filter dan sorting

**Query Parameters:**
- `status_peminjaman` (string): `Diajukan`, `Disetujui`, `Dibatalkan`, `Ditolak`
- `email_users` (string): Email user yang meminjam
- `gedung_id` (int): Filter berdasarkan gedung
- `ruangan_id` (int): Filter berdasarkan ruangan
- `tgl_peminjaman_dari` (date): YYYY-MM-DD
- `tgl_peminjaman_sampai` (date): YYYY-MM-DD
- `status_sarana` (string): ` `, `Disiapkan`, `Siap Pakai`
- `nama_kegiatan` (string): Cari berdasarkan nama kegiatan
- `sort_by` (string): Field untuk sorting (default: `created_at`)
- `sort_order` (string): `asc` atau `desc` (default: `desc`)
- `per_page` (int): Jumlah item per halaman (default: 15)

**Example:**
```bash
GET /api/peminjaman-transaksi?status_peminjaman=Disetujui&sort_by=tgl_peminjaman&sort_order=desc&per_page=20
```

**Response:**
```json
{
    "data": [
        {
            "id": 1,
            "email_users": "user@example.com",
            "user": {
                "email": "user@example.com",
                "nama_lengkap": "John Doe",
                "no_telepon": "081234567890"
            },
            "ruangan": { "id": 1, "nama": "Ruang Seminar A", "kapasitas": 50 },
            "gedung": { "id": 1, "nama": "Gedung Utama", "lokasi": "Lantai 2" },
            "nama_kegiatan": "Seminar Teknologi Digital",
            "tgl_peminjaman": "2026-02-20",
            "tgl_pengembalian": null,
            "waktu_mulai": "08:00:00",
            "waktu_selesai": "17:00:00",
            "durasi_jam": 9,
            "status_peminjaman": "Disetujui",
            "status_sarana": "Siap Pakai",
            "detail_sarana": [
                {
                    "id": 1,
                    "sarana": { "id": 5, "nama": "Proyektor", "stok_total": 5 },
                    "jumlah": 2
                }
            ],
            "total_sarana_item": 2,
            "jumlah_jenis_sarana": 1
        }
    ],
    "meta": {
        "total": 7,
        "total_sarana": 15
    }
}
```

---

### 1.2 GET /api/peminjaman-transaksi/{id}
Detail peminjaman dengan semua relasi

**Example:**
```bash
GET /api/peminjaman-transaksi/1
```

---

### 1.3 GET /api/peminjaman-transaksi/laporan/status-summary
Summary status peminjaman dengan statistik agregat

**Response:**
```json
{
    "data": [
        {
            "status_peminjaman": "Disetujui",
            "total": 4,
            "siap_pakai": 3,
            "disiapkan": 1,
            "rata_rata_durasi_jam": 8.50
        },
        {
            "status_peminjaman": "Diajukan",
            "total": 2,
            "siap_pakai": 0,
            "disiapkan": 2,
            "rata_rata_durasi_jam": 9.00
        }
    ],
    "total_peminjaman": 7
}
```

---

### 1.4 GET /api/peminjaman-transaksi/laporan/kompleks
Complex query dengan multiple JOINs untuk analisis mendalam

**Query Parameters:**
- `tanggal_awal` (date): Default 1 bulan lalu
- `tanggal_akhir` (date): Default hari ini

**Complex Query yang dijalankan (contoh SQL):**
```sql
SELECT 
    pt.id,
    pt.nama_kegiatan,
    pt.tgl_peminjaman,
    pt.status_peminjaman,
    g.nama as gedung_nama,
    r.nama as ruangan_nama,
    COALESCE(u.name_users, 'Pengguna Sistem') as peminjam_nama,
    COALESCE(a.name_admin, 'Admin') as admin_nama,
    a.role as admin_role,
    TIMESTAMPDIFF(HOUR, pt.waktu_mulai, pt.waktu_selesai) as durasi_jam,
    (SELECT COUNT(*) FROM detail_peminjaman_saranas WHERE peminjaman_id = pt.id) as jumlah_jenis_sarana,
    (SELECT SUM(jumlah) FROM detail_peminjaman_saranas WHERE peminjaman_id = pt.id) as total_sarana_item,
    CASE WHEN pt.status_peminjaman = 'Disetujui' THEN 'Aktif'
         WHEN pt.status_peminjaman = 'Diajukan' THEN 'Menunggu'
         ELSE pt.status_peminjaman END as status_kategori
FROM peminjaman_transaksis pt
LEFT JOIN ruangans r ON pt.ruangan_id = r.id
LEFT JOIN gedungs g ON r.gedung_id = g.id
LEFT JOIN users u ON pt.email_users = u.email_users
LEFT JOIN admins a ON pt.email_admin = a.email_admin
WHERE pt.tgl_peminjaman BETWEEN ? AND ?
ORDER BY pt.tgl_peminjaman DESC;
```

**Example:**
```bash
GET /api/peminjaman-transaksi/laporan/kompleks?tanggal_awal=2026-01-19&tanggal_akhir=2026-02-19
```

**Response:**
```json
{
    "total": 7,
    "tanggal_awal": "2026-01-19",
    "tanggal_akhir": "2026-02-19",
    "data": [
        {
            "id": 1,
            "nama_kegiatan": "Seminar Teknologi Digital",
            "tgl_peminjaman": "2026-02-20",
            "status_peminjaman": "Disetujui",
            "gedung_nama": "Gedung Utama",
            "ruangan_nama": "Ruang Seminar A",
            "peminjam_nama": "John Doe",
            "admin_nama": "Petugas Gedung",
            "admin_role": "petugas",
            "durasi_jam": 9,
            "jumlah_jenis_sarana": 2,
            "total_sarana_item": 5,
            "status_kategori": "Aktif"
        }
    ]
}
```

---

### 1.5 GET /api/peminjaman-transaksi/laporan/sarana-populer
Laporan sarana yang paling sering dipinjam

**Query Parameters:**
- `limit` (int): Jumlah top sarana (default: 10)

**Response:**
```json
{
    "total_data": 10,
    "data": [
        {
            "id": 5,
            "nama": "Proyektor",
            "kondisi": "Baik",
            "jumlah_peminjaman": 15,
            "total_item_dipinjam": 32,
            "rata_rata_jumlah_per_peminjaman": 2.13,
            "peminjaman_pertama": "2025-08-10",
            "peminjaman_terakhir": "2026-02-19"
        }
    ]
}
```

---

### 1.6 GET /api/peminjaman-transaksi/laporan/ketersediaan-ruangan
Laporan ketersediaan ruangan berdasarkan peminjaman aktif

**Query Parameters:**
- `tanggal` (date): Tanggal untuk check ketersediaan (default: hari ini)

**Response:**
```json
{
    "tanggal": "2026-02-19",
    "total_ruangan": 10,
    "tersedia": 7,
    "digunakan": 3,
    "data": [
        {
            "gedung_id": 1,
            "gedung_nama": "Gedung Utama",
            "ruangan_id": 1,
            "ruangan_nama": "Ruang Seminar A",
            "kapasitas": 50,
            "peminjaman_aktif": 1,
            "status_ketersediaan": "Digunakan"
        },
        {
            "gedung_id": 1,
            "gedung_nama": "Gedung Utama",
            "ruangan_id": 2,
            "ruangan_nama": "Ruang Rapat B",
            "kapasitas": 30,
            "peminjaman_aktif": 0,
            "status_ketersediaan": "Tersedia"
        }
    ]
}
```

---

### 1.7 GET /api/peminjaman-transaksi/laporan/riwayat-peminjam/{emailUsers}
Riwayat peminjaman detail per user

**Example:**
```bash
GET /api/peminjaman-transaksi/laporan/riwayat-peminjam/user@example.com?page=1
```

**Response:**
```json
{
    "current_page": 1,
    "data": [
        {
            "id": 1,
            "nama_kegiatan": "Seminar Teknologi Digital",
            "tgl_peminjaman": "2026-02-20",
            "tgl_pengembalian": null,
            "status_peminjaman": "Disetujui",
            "gedung_nama": "Gedung Utama",
            "ruangan_nama": "Ruang Seminar A",
            "sarana_list": "Proyektor (2 item), Mic (3 item), Layar (1 item)"
        }
    ],
    "per_page": 20,
    "total": 5
}
```

---

## 2. SARANA ENDPOINTS

### 2.1 GET /api/sarana
List semua sarana dengan stok tersedia dihitung dinamis

**Query Parameters:**
- `kondisi` (string): `Baik`, `Rusak Ringan`, `Rusak Berat`
- `nama` (string): Cari berdasarkan nama
- `min_stok` (int): Minimum stok
- `tanggal` (date): Tanggal untuk hitung ketersediaan (default: hari ini)
- `per_page` (int): Jumlah per halaman (default: 20)

**Example:**
```bash
GET /api/sarana?kondisi=Baik&min_stok=1&tanggal=2026-02-20
```

**Response:**
```json
{
    "data": [
        {
            "id": 1,
            "nama": "Meja Panjang",
            "kondisi": "Baik",
            "tgl_penerimaan": "2024-01-15",
            "stok_total": 10,
            "stok_tersedia": 7,
            "stok_dipinjam": 3,
            "persentase_ketersediaan": 70,
            "created_at": "2025-01-20T10:30:00Z",
            "updated_at": "2025-01-20T10:30:00Z"
        },
        {
            "id": 5,
            "nama": "Proyektor",
            "kondisi": "Baik",
            "tgl_penerimaan": "2024-06-10",
            "stok_total": 5,
            "stok_tersedia": 2,
            "stok_dipinjam": 3,
            "persentase_ketersediaan": 40,
            "created_at": "2025-01-20T10:30:00Z",
            "updated_at": "2025-01-20T10:30:00Z"
        }
    ]
}
```

---

### 2.2 GET /api/sarana/laporan/ketersediaan-tanggal
Query ketersediaan sarana pada tanggal tertentu dengan detail

**Query Parameters:**
- `tanggal` (date): Format YYYY-MM-DD (default: hari ini)

**Complex Query:**
```sql
SELECT 
    s.id,
    s.nama,
    s.kondisi,
    s.stok as stok_total,
    COALESCE(SUM(dps.jumlah), 0) as stok_dipinjam,
    s.stok - COALESCE(SUM(dps.jumlah), 0) as stok_tersedia,
    ROUND((COALESCE(SUM(dps.jumlah), 0) / s.stok) * 100, 2) as persentase_digunakan,
    ROUND(((s.stok - COALESCE(SUM(dps.jumlah), 0)) / s.stok) * 100, 2) as persentase_tersedia
FROM saranas s
LEFT JOIN detail_peminjaman_saranas dps ON s.id = dps.sarana_id
LEFT JOIN peminjaman_transaksis pt ON dps.peminjaman_id = pt.id
WHERE pt.id IS NULL 
   OR (pt.tgl_peminjaman <= ? 
       AND (pt.tgl_pengembalian IS NULL OR pt.tgl_pengembalian >= ?)
       AND pt.status_peminjaman IN ('Disetujui', 'Diajukan'))
GROUP BY s.id, s.nama, s.kondisi, s.stok
ORDER BY persentase_digunakan DESC;
```

**Example:**
```bash
GET /api/sarana/laporan/ketersediaan-tanggal?tanggal=2026-02-20
```

**Response:**
```json
{
    "tanggal": "2026-02-20",
    "total_jenis_sarana": 15,
    "total_stok": 85,
    "total_dipinjam": 23,
    "total_tersedia": 62,
    "data": [
        {
            "id": 5,
            "nama": "Proyektor",
            "kondisi": "Baik",
            "stok_total": 5,
            "stok_dipinjam": 3,
            "stok_tersedia": 2,
            "persentase_digunakan": 60.00,
            "persentase_tersedia": 40.00
        }
    ]
}
```

---

### 2.3 GET /api/sarana/laporan/detail-peminjaman
Laporan detail peminjaman sarana dengan history lengkap

**Query Parameters:**
- `sarana_id` (int, required): ID sarana
- `tanggal_awal` (date): Default 1 bulan lalu
- `tanggal_akhir` (date): Default hari ini

**Example:**
```bash
GET /api/sarana/laporan/detail-peminjaman?sarana_id=5&tanggal_awal=2026-01-19&tanggal_akhir=2026-02-19
```

**Response:**
```json
{
    "current_page": 1,
    "data": [
        {
            "peminjaman_id": 1,
            "jumlah": 2,
            "nama_kegiatan": "Seminar Teknologi Digital",
            "tgl_peminjaman": "2026-02-20",
            "tgl_pengembalian": null,
            "waktu_mulai": "08:00:00",
            "waktu_selesai": "17:00:00",
            "status_peminjaman": "Disetujui",
            "peminjam": "John Doe",
            "no_telepon": "081234567890",
            "gedung_nama": "Gedung Utama",
            "ruangan_nama": "Ruang Seminar A",
            "status_kegiatan": "Sedang Dipinjam"
        }
    ],
    "total": 8,
    "per_page": 30
}
```

---

### 2.4 GET /api/sarana/laporan/stok-mulai-habis
Laporan sarana yang mulai habis (stok rendah) dengan alert

**Query Parameters:**
- `threshold` (int): Batas stok dianggap kritis (default: 3)
- `tanggal` (date): Tangal untuk kalkulasi (default: hari ini)

**Complex Query yang mencari sarana dengan stok <= threshold:**
```sql
SELECT 
    s.id,
    s.nama,
    s.kondisi,
    s.stok as stok_total,
    COALESCE(SUM(dps.jumlah), 0) as stok_dipinjam,
    s.stok - COALESCE(SUM(dps.jumlah), 0) as stok_tersedia,
    CASE 
        WHEN (s.stok - COALESCE(SUM(dps.jumlah), 0)) = 0 THEN 'HABIS'
        WHEN (s.stok - COALESCE(SUM(dps.jumlah), 0)) <= 3 THEN 'KRITIS'
        ELSE 'NORMAL'
    END as status_stok,
    (SELECT GROUP_CONCAT(CONCAT(pt.nama_kegiatan, ' - ', pt.tgl_peminjaman) SEPARATOR '; ')
     FROM detail_peminjaman_saranas dps2
     JOIN peminjaman_transaksis pt ON dps2.peminjaman_id = pt.id
     WHERE dps2.sarana_id = s.id 
     AND pt.tgl_peminjaman <= ?
     AND (pt.tgl_pengembalian IS NULL OR pt.tgl_pengembalian >= ?)
     AND pt.status_peminjaman IN ('Disetujui', 'Diajukan')
     LIMIT 5) as peminjam_aktif
FROM saranas s
LEFT JOIN detail_peminjaman_saranas dps ON s.id = dps.sarana_id
LEFT JOIN peminjaman_transaksis pt ON dps.peminjaman_id = pt.id
WHERE pt.id IS NULL 
   OR (pt.tgl_peminjaman <= ?
       AND (pt.tgl_pengembalian IS NULL OR pt.tgl_pengembalian >= ?)
       AND pt.status_peminjaman IN ('Disetujui', 'Diajukan'))
GROUP BY s.id, s.nama, s.kondisi, s.stok
HAVING (s.stok - COALESCE(SUM(dps.jumlah), 0)) <= 3
ORDER BY stok_tersedia ASC;
```

**Example:**
```bash
GET /api/sarana/laporan/stok-mulai-habis?threshold=3&tanggal=2026-02-20
```

**Response:**
```json
{
    "threshold": 3,
    "tanggal": "2026-02-20",
    "total_sarana_kritis": 4,
    "jumlah_habis": 0,
    "jumlah_kritis": 4,
    "data": [
        {
            "id": 5,
            "nama": "Proyektor",
            "kondisi": "Baik",
            "stok_total": 5,
            "stok_dipinjam": 3,
            "stok_tersedia": 2,
            "status_stok": "KRITIS",
            "peminjam_aktif": "Seminar Teknologi Digital - 2026-02-20; Workshop Digital - 2026-02-21"
        },
        {
            "id": 7,
            "nama": "Layar Proyeksi",
            "kondisi": "Baik",
            "stok_total": 3,
            "stok_dipinjam": 2,
            "stok_tersedia": 1,
            "status_stok": "KRITIS",
            "peminjam_aktif": "Training Kepemimpinan - 2026-02-22"
        }
    ]
}
```

---

## 3. RUANGAN ENDPOINTS

### 3.1 GET /api/ruangan
List semua ruangan

**Query Parameters:**
- `gedung_id` (int): Filter berdasarkan gedung
- `nama` (string): Cari berdasarkan nama
- `kapasitas_min` (int): Minimum kapasitas
- `per_page` (int): Default 20

**Example:**
```bash
GET /api/ruangan?gedung_id=1&kapasitas_min=30
```

---

### 3.2 GET /api/ruangan/laporan/peminjaman-per-ruangan
Laporan statistik peminjaman per ruangan

**Query Parameters:**
- `tanggal_awal` (date): Default 1 bulan lalu
- `tanggal_akhir` (date): Default hari ini

**Response:**
```json
{
    "tanggal_awal": "2026-01-19",
    "tanggal_akhir": "2026-02-19",
    "data": [
        {
            "gedung_id": 1,
            "gedung_nama": "Gedung Utama",
            "ruangan_id": 1,
            "ruangan_nama": "Ruang Seminar A",
            "kapasitas": 50,
            "jumlah_peminjaman": 7,
            "disetujui": 4,
            "diajukan": 2,
            "ditolak": 1
        }
    ]
}
```

---

## 4. GEDUNG ENDPOINTS

### 4.1 GET /api/gedung
List semua gedung

**Query Parameters:**
- `nama` (string): Cari berdasarkan nama
- `per_page` (int): Default 20

---

### 4.2 GET /api/gedung/laporan/utilitas-lengkap
Laporan kompleks utilitas gedung dengan statistik detail

**Query Parameters:**
- `tanggal` (date): Tanggal untuk analisis (default: hari ini)

**Complex Query dengan multiple aggregations:**

Response menunjukkan untuk setiap gedung:
- Total ruangan
- Total kapasitas
- Ruangan yang sedang terpakai
- Ruangan tersedia
- Jumlah peminjaman aktif
- Total sarana yang sedang dipinjam
- Jenis sarana yang dipinjam

**Response:**
```json
{
    "tanggal": "2026-02-20",
    "total_gedung": 3,
    "data": [
        {
            "id": 1,
            "gedung_nama": "Gedung Utama",
            "lokasi": "Lantai 2",
            "total_ruangan": 5,
            "total_kapasitas": 250,
            "ruangan_terpakai": 2,
            "ruangan_tersedia": 3,
            "jumlah_peminjaman_aktif": 3,
            "total_sarana_dipinjam": 8,
            "jenis_sarana_dipinjam": 4
        }
    ]
}
```

---

## 5. BERITA ENDPOINTS

### 5.1 GET /api/berita
List semua berita dengan filter

**Query Parameters:**
- `status` (string): `approved`, `draft`
- `judul` (string): Cari berdasarkan judul
- `tgl_posting_dari` (date): Date range start
- `tgl_posting_sampai` (date): Date range end
- `per_page` (int): Default 10

**Example:**
```bash
GET /api/berita?status=approved&sort_by=tgl_posting
```

---

## 6. TENTANG ENDPOINTS

### 6.1 GET /api/tentang
Get informasi organisasi (usually singleton)

---

## Complex Query Examples untuk Testing

### Test 1: Cek stok sarana pada tanggal tertentu dengan peminjaman aktif
```bash
GET /api/sarana/laporan/ketersediaan-tanggal?tanggal=2026-02-22
```

Menampilkan semua sarana yang tersedia/dipinjam pada tanggal 2026-02-22

---

### Test 2: Laporan kepopuleran sarana
```bash
GET /api/peminjaman-transaksi/laporan/sarana-populer?limit=5
```

Menampilkan 5 sarana paling banyak dipinjam dengan statistik lengkap

---

### Test 3: Alert stok sarana yang hampir habis
```bash
GET /api/sarana/laporan/stok-mulai-habis?threshold=2&tanggal=2026-02-20
```

Menampilkan sarana dengan stok tersisa <= 2 item pada tanggal 2026-02-20

---

### Test 4: Riwayat peminjaman user tertentu
```bash
GET /api/peminjaman-transaksi/laporan/riwayat-peminjam/user1@example.com?page=1
```

Menampilkan semua peminjaman yang pernah dilakukan user tertentu

---

### Test 5: Ketersediaan ruangan setiap hari
```bash
GET /api/peminjaman-transaksi/laporan/ketersediaan-ruangan?tanggal=2026-02-20
```

Menampilkan ruangan mana saja yang tersedia atau sedang digunakan pada tanggal tertentu

---

### Test 6: Laporan kompleks dengan multiple JOINs
```bash
GET /api/peminjaman-transaksi/laporan/kompleks?tanggal_awal=2026-02-01&tanggal_akhir=2026-02-28
```

Menampilkan data peminjaman dengan JOIN ke users, admins, ruangans, gedungs, profils
+ Hitungan jumlah sarana per peminjaman
+ Status kategori yang di-derive

---

## Laravel Model Usage Examples

### Query menggunakan Scopes dari Model

```php
// Di dalam Tinker atau Routes

// Contoh 1: Get peminjaman yang aktif (Disetujui dan belum dikembalikan)
$peminjamanAktif = PeminjamanTransaksi::active()->get();

// Contoh 2: Get peminjaman dengan status tertentu
$peminjamanDiajukan = PeminjamanTransaksi::byStatus('Diajukan')->get();

// Contoh 3: Get peminjaman dalam range tanggal
$peminjamanBulanIni = PeminjamanTransaksi::byTanggalRange(
    '2026-02-01',
    '2026-02-28'
)->get();

// Contoh 4: Get peminjaman dalam 1 gedung
$peminjamanGedung1 = PeminjamanTransaksi::byGedung(1)->get();

// Contoh 5: Check ketersediaan sarana pada tanggal tertentu
$sarana = Sarana::find(5);
$stokTersedia = $sarana->getStokTersedia('2026-02-20'); // Hasil: 2
$stokDipinjam = $sarana->getStokDipinjam('2026-02-20'); // Hasil: 3
$bisa = $sarana->canBeBorrowed(1, '2026-02-20'); // true/false

// Contoh 6: Get sarana dengan stok rendah
$saranaLowStock = Sarana::lowStock(3)->get(); // Stok <= 3

// Contoh 7: Search sarana
$hasil = Sarana::search('Proyektor')->get();
```

---

## Notes

1. **Stok Calculation**: Stok tersedia dihitung secara dinamis dengan mempertimbangkan:
   - Semua peminjaman yang status_peminjaman = `Disetujui` atau `Diajukan`
   - Peminjaman hanya dihitung jika tgl_peminjaman <= tanggal yang dicek
   - DAN (tgl_pengembalian IS NULL OR tgl_pengembalian >= tanggal yang dicek)

2. **Performance**: Untuk dataset besar, tambahkan pagination dan caching untuk query kompleks

3. **Authentication**: Implementasi sesaat ini tanpa auth. Untuk production tambah `auth:sanctum` middleware

4. **Validation**: Tambahkan request validation untuk semua POST/PUT/DELETE endpoints
