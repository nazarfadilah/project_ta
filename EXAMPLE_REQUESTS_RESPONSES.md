# Contoh Request & Response - SIPRASA API

## 1. POST Peminjaman Transaksi

**Request:**
```bash
curl -X POST http://localhost:8000/api/peminjaman-transaksi \
  -H "Content-Type: application/json" \
  -d '{
    "email_users": "user1@example.com",
    "ruangan_id": 1,
    "nama_kegiatan": "Seminar Baru",
    "tgl_peminjaman": "2026-02-25",
    "waktu_mulai": "08:00:00",
    "waktu_selesai": "17:00:00",
    "status_peminjaman": "Diajukan",
    "status_sarana": "Disiapkan",
    "keterangan": "Seminar tentang teknologi terbaru",
    "email_admin": "petugas1@example.com"
  }'
```

**Response:**
```json
{
  "data": {
    "id": 8,
    "email_users": "user1@example.com",
    "user": {
      "email": "user1@example.com",
      "nama_lengkap": "User Satu",
      "no_telepon": "081234567890"
    },
    "ruangan": {
      "id": 1,
      "nama": "Ruang Seminar A",
      "kapasitas": 50,
      "gedung": {
        "id": 1,
        "nama": "Gedung Utama",
        "lokasi": "Lantai 2"
      }
    },
    "gedung": {
      "id": 1,
      "nama": "Gedung Utama",
      "lokasi": "Lantai 2"
    },
    "nama_kegiatan": "Seminar Baru",
    "tgl_peminjaman": "2026-02-25",
    "tgl_pengembalian": null,
    "waktu_mulai": "08:00:00",
    "waktu_selesai": "17:00:00",
    "durasi_jam": 9,
    "status_peminjaman": "Diajukan",
    "status_sarana": "Disiapkan",
    "keterangan": "Seminar tentang teknologi terbaru",
    "admin": {
      "email": "petugas1@example.com",
      "nama_lengkap": "Petugas Gedung",
      "role": "petugas"
    },
    "detail_sarana": [],
    "total_sarana_item": 0,
    "jumlah_jenis_sarana": 0,
    "created_at": "2026-02-19T10:30:00Z",
    "updated_at": "2026-02-19T10:30:00Z"
  }
}
```

---

## 2. GET Semua Peminjaman

**Request:**
```bash
curl http://localhost:8000/api/peminjaman-transaksi
```

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "email_users": "user1@example.com",
      "user": {
        "email": "user1@example.com",
        "nama_lengkap": "User Satu",
        "no_telepon": "081234567890"
      },
      "ruangan": {
        "id": 1,
        "nama": "Ruang Seminar A",
        "kapasitas": 50,
        "gedung": {
          "id": 1,
          "nama": "Gedung Utama",
          "lokasi": "Lantai 2"
        }
      },
      "gedung": {
        "id": 1,
        "nama": "Gedung Utama",
        "lokasi": "Lantai 2"
      },
      "nama_kegiatan": "Seminar Teknologi Digital",
      "tgl_peminjaman": "2026-02-20",
      "tgl_pengembalian": null,
      "waktu_mulai": "08:00:00",
      "waktu_selesai": "17:00:00",
      "durasi_jam": 9,
      "status_peminjaman": "Disetujui",
      "status_sarana": "Siap Pakai",
      "keterangan": "Peminjaman untuk acara Seminar Teknologi Digital",
      "admin": {
        "email": "petugas1@example.com",
        "nama_lengkap": "Petugas Gedung",
        "role": "petugas"
      },
      "detail_sarana": [
        {
          "id": 1,
          "peminjaman_id": 1,
          "sarana_id": 5,
          "sarana": {
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
          },
          "jumlah": 2,
          "created_at": "2026-02-19T10:30:00Z",
          "updated_at": "2026-02-19T10:30:00Z"
        }
      ],
      "total_sarana_item": 2,
      "jumlah_jenis_sarana": 1,
      "created_at": "2026-02-19T10:30:00Z",
      "updated_at": "2026-02-19T10:30:00Z"
    }
  ],
  "links": {
    "first": "http://localhost:8000/api/peminjaman-transaksi?page=1",
    "last": "http://localhost:8000/api/peminjaman-transaksi?page=1",
    "prev": null,
    "next": null
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 1,
    "path": "http://localhost:8000/api/peminjaman-transaksi",
    "per_page": 15,
    "to": 7,
    "total": 7
  }
}
```

---

## 3. POST Sarana

**Request:**
```bash
curl -X POST http://localhost:8000/api/sarana \
  -H "Content-Type: application/json" \
  -d '{
    "nama": "Laptop",
    "kondisi": "Baik",
    "tgl_penerimaan": "2026-01-01",
    "stok": 10
  }'
```

**Response:**
```json
{
  "data": {
    "id": 16,
    "nama": "Laptop",
    "kondisi": "Baik",
    "tgl_penerimaan": "2026-01-01",
    "stok_total": 10,
    "stok_tersedia": 10,
    "stok_dipinjam": 0,
    "persentase_ketersediaan": 100,
    "created_at": "2026-02-19T10:45:00Z",
    "updated_at": "2026-02-19T10:45:00Z"
  }
}
```

---

## 4. GET Sarana (List)

**Request:**
```bash
curl http://localhost:8000/api/sarana?page=1&per_page=5
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
      "id": 2,
      "nama": "Kursi",
      "kondisi": "Baik",
      "tgl_penerimaan": "2024-02-10",
      "stok_total": 50,
      "stok_tersedia": 40,
      "stok_dipinjam": 10,
      "persentase_ketersediaan": 80,
      "created_at": "2025-01-20T10:30:00Z",
      "updated_at": "2025-01-20T10:30:00Z"
    }
  ],
  "links": {
    "first": "http://localhost:8000/api/sarana?page=1",
    "last": "http://localhost:8000/api/sarana?page=3",
    "prev": null,
    "next": "http://localhost:8000/api/sarana?page=2"
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 3,
    "per_page": 5,
    "to": 5,
    "total": 15
  }
}
```

---

## 5. POST Ruangan

**Request:**
```bash
curl -X POST http://localhost:8000/api/ruangan \
  -H "Content-Type: application/json" \
  -d '{
    "gedung_id": 1,
    "nama": "Ruang Rapat Besar",
    "kapasitas": 100,
    "keterangan": "Ruang rapat dengan kapasitas besar"
  }'
```

**Response:**
```json
{
  "data": {
    "id": 11,
    "gedung_id": 1,
    "gedung": {
      "id": 1,
      "nama": "Gedung Utama",
      "kordinat_x": "6.9271,-107.6412",
      "kordinat_y": "-6.9271",
      "lokasi": "Lantai 2",
      "jumlah_ruangan": 11,
      "created_at": "2025-01-20T10:30:00Z",
      "updated_at": "2025-01-20T10:30:00Z"
    },
    "nama": "Ruang Rapat Besar",
    "kapasitas": 100,
    "keterangan": "Ruang rapat dengan kapasitas besar",
    "created_at": "2026-02-19T10:50:00Z",
    "updated_at": "2026-02-19T10:50:00Z"
  }
}
```

---

## 6. GET Ruangan (List)

**Request:**
```bash
curl http://localhost:8000/api/ruangan
```

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "gedung_id": 1,
      "gedung": {
        "id": 1,
        "nama": "Gedung Utama",
        "kordinat_x": "6.9271,-107.6412",
        "kordinat_y": "-6.9271",
        "lokasi": "Lantai 2",
        "jumlah_ruangan": 10,
        "created_at": "2025-01-20T10:30:00Z",
        "updated_at": "2025-01-20T10:30:00Z"
      },
      "nama": "Ruang Seminar A",
      "kapasitas": 50,
      "keterangan": null,
      "created_at": "2025-01-20T10:30:00Z",
      "updated_at": "2025-01-20T10:30:00Z"
    }
  ],
  "links": {
    "first": "http://localhost:8000/api/ruangan?page=1",
    "last": "http://localhost:8000/api/ruangan?page=1",
    "prev": null,
    "next": null
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 1,
    "per_page": 20,
    "to": 10,
    "total": 10
  }
}
```

---

## 7. GET Gedung (List)

**Request:**
```bash
curl http://localhost:8000/api/gedung
```

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "nama": "Gedung Utama",
      "kordinat_x": "6.9271,-107.6412",
      "kordinat_y": "-6.9271",
      "lokasi": "Lantai 2",
      "jumlah_ruangan": 10,
      "created_at": "2025-01-20T10:30:00Z",
      "updated_at": "2025-01-20T10:30:00Z"
    },
    {
      "id": 2,
      "nama": "Gedung Cabang",
      "kordinat_x": "-6.9350,-107.6300",
      "kordinat_y": "-6.9350",
      "lokasi": "Lantai 1",
      "jumlah_ruangan": 5,
      "created_at": "2025-01-20T10:30:00Z",
      "updated_at": "2025-01-20T10:30:00Z"
    }
  ],
  "links": {
    "first": "http://localhost:8000/api/gedung?page=1",
    "last": "http://localhost:8000/api/gedung?page=1",
    "prev": null,
    "next": null
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 1,
    "per_page": 20,
    "to": 3,
    "total": 3
  }
}
```

---

## 8. POST Berita

**Request:**
```bash
curl -X POST http://localhost:8000/api/berita \
  -H "Content-Type: application/json" \
  -d '{
    "email_admin": "admin1@example.com",
    "judul": "Berita Update Sistem",
    "slug": "berita-update-sistem",
    "isi": "Kami telah mengupdate sistem dengan fitur-fitur baru...",
    "tgl_posting": "2026-02-19",
    "status": "approved"
  }'
```

**Response:**
```json
{
  "data": {
    "id": 4,
    "email_admin": "admin1@example.com",
    "admin": {
      "email": "admin1@example.com",
      "nama": "Admin Utama"
    },
    "judul": "Berita Update Sistem",
    "isi": "Kami telah mengupdate sistem dengan fitur-fitur baru...",
    "status": "approved",
    "tgl_posting": "2026-02-19",
    "created_at": "2026-02-19T11:00:00Z",
    "updated_at": "2026-02-19T11:00:00Z"
  }
}
```

---

## 9. GET Berita (List)

**Request:**
```bash
curl http://localhost:8000/api/berita
```

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "email_admin": "admin1@example.com",
      "admin": {
        "email": "admin1@example.com",
        "nama": "Admin Utama"
      },
      "judul": "Peluncuran Sistem SIPRASA",
      "isi": "Sistem peminjaman sarana (SIPRASA) telah diluncurkan...",
      "status": "approved",
      "tgl_posting": "2026-01-10",
      "created_at": "2025-12-20T10:30:00Z",
      "updated_at": "2025-12-20T10:30:00Z"
    }
  ],
  "links": {
    "first": "http://localhost:8000/api/berita?page=1",
    "last": "http://localhost:8000/api/berita?page=1",
    "prev": null,
    "next": null
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 1,
    "per_page": 10,
    "to": 3,
    "total": 3
  }
}
```

---

## 10. GET Tentang

**Request:**
```bash
curl http://localhost:8000/api/tentang
```

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "nama_organisasi": "Universitas Contoh",
      "deskripsi": "Universitas terkemuka di Indonesia",
      "email": "info@uncontoh.ac.id",
      "alamat": "Jl. Kampus No. 1, Bandung",
      "no_telepon": "0274-XXX-XXXX",
      "jam_operasional": "Senin - Jumat, 08:00 - 16:00",
      "kordinat_x": "-6.9271",
      "kordinat_y": "-107.6412",
      "facebook": "facebook.com/uncontoh",
      "instagram": "@uncontoh",
      "twitter": "@uncontoh",
      "updated_at": "2026-02-19T10:30:00Z"
    }
  ]
}
```

---

## HTTP Methods untuk CRUD

| Method | Path | Action |
|--------|------|--------|
| GET | /api/peminjaman-transaksi | List data |
| POST | /api/peminjaman-transaksi | Create data |
| GET | /api/peminjaman-transaksi/{id} | Show detail |
| PUT/PATCH | /api/peminjaman-transaksi/{id} | Update data |
| DELETE | /api/peminjaman-transaksi/{id} | Delete data |

---

## Testing Tips

1. **Browser**: Cukup buka URL di browser untuk GET request
2. **Postman/Insomnia**: Buat request baru, pilih method, masukkan URL dan body
3. **CURL**: Copy-paste command di terminal
4. **Thunder Client (VS Code Extension)**: Install extension, buat request

## Common Errors

| Error | Cause | Solution |
|-------|-------|----------|
| 404 Not Found | Route tidak ada | Check URL dan pastikan routes di-load |
| 500 Internal Server Error | Database error | Check database connection dan run migration |
| 422 Unprocessable Entity | Validation error | Check request body sesuai dengan model |
| 401 Unauthorized | Auth required | Tambahkan token jika diperlukan |
