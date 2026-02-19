# Quick API Testing Guide - SIPRASA

## Base URL
```
http://localhost:8000/api
```

## Endpoints tersedia

### 1. Peminjaman Transaksi
```
GET    /api/peminjaman-transaksi
POST   /api/peminjaman-transaksi
GET    /api/peminjaman-transaksi/{id}
PUT    /api/peminjaman-transaksi/{id}
PATCH  /api/peminjaman-transaksi/{id}
DELETE /api/peminjaman-transaksi/{id}
```

### 2. Sarana
```
GET    /api/sarana
POST   /api/sarana
GET    /api/sarana/{id}
PUT    /api/sarana/{id}
PATCH  /api/sarana/{id}
DELETE /api/sarana/{id}
```

### 3. Ruangan
```
GET    /api/ruangan
POST   /api/ruangan
GET    /api/ruangan/{id}
PUT    /api/ruangan/{id}
PATCH  /api/ruangan/{id}
DELETE /api/ruangan/{id}
```

### 4. Gedung
```
GET    /api/gedung
POST   /api/gedung
GET    /api/gedung/{id}
PUT    /api/gedung/{id}
PATCH  /api/gedung/{id}
DELETE /api/gedung/{id}
```

### 5. Berita
```
GET    /api/berita
POST   /api/berita
GET    /api/berita/{id}
PUT    /api/berita/{id}
PATCH  /api/berita/{id}
DELETE /api/berita/{id}
```

### 6. Tentang
```
GET    /api/tentang
POST   /api/tentang
GET    /api/tentang/{id}
PUT    /api/tentang/{id}
PATCH  /api/tentang/{id}
DELETE /api/tentang/{id}
```

## Testing dengan CURL

### Get List Peminjaman (JSON)
```bash
curl http://localhost:8000/api/peminjaman-transaksi
```

### Get Peminjaman with pagination
```bash
curl "http://localhost:8000/api/peminjaman-transaksi?page=1&per_page=5"
```

### Get Detail Peminjaman
```bash
curl http://localhost:8000/api/peminjaman-transaksi/1
```

### Get List Sarana
```bash
curl http://localhost:8000/api/sarana
```

### Get List Ruangan
```bash
curl http://localhost:8000/api/ruangan
```

### Get List Gedung
```bash
curl http://localhost:8000/api/gedung
```

### Get List Berita
```bash
curl http://localhost:8000/api/berita
```

### Get Tentang
```bash
curl http://localhost:8000/api/tentang
```

## Testing dengan Postman / Insomnia

1. Buka https://www.postman.com/ atau Insomnia
2. Request Method: GET
3. URL: http://localhost:8000/api/peminjaman-transaksi
4. Send

## Response Format

Semua response akan dalam format JSON:

```json
{
  "data": [
    {
      "id": 1,
      "email_users": "user@example.com",
      "nama_kegiatan": "Seminar Teknologi",
      "status_peminjaman": "Disetujui",
      ...
    }
  ],
  "links": {
    "first": "...",
    "last": "...",
    "prev": null,
    "next": "..."
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 2,
    "per_page": 15,
    "to": 15,
    "total": 30
  }
}
```

## Troubleshooting

Jika ada error, cek:
1. Database sudah migrate: `php artisan migrate:fresh --seed`
2. Server running: `php artisan serve`
3. URL benar: http://localhost:8000/api/peminjaman-transaksi

## Notes

- Semua data dalam format JSON
- Pagination default: 15-20 items per page
- Sort dan filter bisa ditambahkan di query string
- Resource relationships sudah di-load dengan eager loading
