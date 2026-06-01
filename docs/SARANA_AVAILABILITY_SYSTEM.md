# Sarana Availability Filtering System

## Overview
Sistem filtering stok sarana berdasarkan tanggal peminjaman untuk mencegah overbooking. Ketika user membuat peminjaman sarana, sistem secara otomatis menghitung stok yang tersedia berdasarkan peminjaman yang sudah ada pada rentang tanggal yang sama.

## How It Works

### 1. Database Structure
```
Sarana Table:
- id
- nama (e.g., "Proyektor")
- stok (e.g., 5 - total jumlah)
- kondisi
- tgl_penerimaan

PeminjamanTransaksi Table:
- id
- tanggal (tanggal mulai peminjaman)
- jamMulai (waktu mulai)
- durasi (dalam jam)
- statusPeminjaman

DetailPeminjamanSarana Table:
- id
- sarana_id (link ke Sarana)
- peminjaman_id (link ke PeminjamanTransaksi)
- jumlah (berapa banyak unit yang dipinjam)
```

### 2. Calculation Logic

#### Contoh Skenario:
```
Sarana: Proyektor
Total Stok: 5 unit

Peminjaman yang Ada:
- Tanggal: 2026-03-12, Jam: 08:00, Durasi: 4 jam
  Waktu: 08:00-12:00
  Jumlah: 3 unit

- Tanggal: 2026-03-13, Jam: 10:00, Durasi: 8 jam
  Waktu: 10:00-18:00
  Jumlah: 2 unit

User Membuat Peminjaman Baru:
```

#### Case 1: Tanggal 2026-03-12, Durasi 4 jam
```
Periode: 2026-03-12 08:00 - 12:00

Cek Overlapping:
✓ Existing Loan 1 (2026-03-12 08:00-12:00): OVERLAP
✗ Existing Loan 2 (2026-03-13 10:00-18:00): NO OVERLAP

Calculation:
- Total Stok: 5
- Sudah Dipinjam: 3 (dari Loan 1)
- Stok Tersedia: 5 - 3 = 2 unit ✓
```

#### Case 2: Tanggal 2026-03-12, Durasi 20 jam
```
Periode: 2026-03-12 08:00 - 2026-03-13 04:00

Cek Overlapping:
✓ Existing Loan 1 (2026-03-12 08:00-12:00): OVERLAP
✓ Existing Loan 2 (2026-03-13 10:00-18:00): NO OVERLAP (ends at 04:00)
  ✗ Actually NO OVERLAP - loan 1 ends at 12:00, loan 2 starts at 10:00 next day

Wait, let me recalculate:
- Loan 1: 2026-03-12 08:00 to 2026-03-12 12:00
- Loan 2: 2026-03-13 10:00 to 2026-03-13 18:00
- New: 2026-03-12 08:00 to 2026-03-13 04:00

Loan 2 ends at 18:00, New ends at 04:00 (before 10:00 next day)
So NO overlap with Loan 2

Calculation:
- Total Stok: 5
- Sudah Dipinjam: 3 (hanya dari Loan 1)
- Stok Tersedia: 5 - 3 = 2 unit ✓
```

#### Case 3: Tanggal 2026-03-13, Durasi 8 jam
```
Periode: 2026-03-13 08:00 - 16:00

Cek Overlapping:
✗ Existing Loan 1 (2026-03-12 08:00-12:00): NO OVERLAP
✓ Existing Loan 2 (2026-03-13 10:00-18:00): OVERLAP (10:00-16:00)

Calculation:
- Total Stok: 5
- Sudah Dipinjam: 2 (dari Loan 2)
- Stok Tersedia: 5 - 2 = 3 unit ✓
```

#### Case 4: User Minta 3 unit, Tanggal 2026-03-12, Durasi 4 jam
```
Periode: 2026-03-12 08:00 - 12:00
Requested: 3 unit

Cek Overlapping:
✓ Existing Loan 1 (2026-03-12 08:00-12:00): OVERLAP
  Sudah dipinjam: 3 unit
  Stok tersedia: 5 - 3 = 2 unit

Validasi:
- Requested: 3 unit
- Available: 2 unit
- ❌ INSUFFICIENT STOCK (2 < 3)
- Show Warning: "Stok tidak cukup! Tersedia: 2 unit, diminta: 3 unit"
```

## Implementation

### 1. Sarana Model Methods

```php
// Calculate available stock for date range
public function getAvailableStock($startDate, $endDate)
{
    // 1. Get all loans that overlap with requested period
    // 2. Sum the quantities
    // 3. Return: total - sum(borrowed)
}

// Get loans that overlap with date range
public function getOverlappingLoans($startDate, $endDate)
{
    // Returns collection of DetailPeminjamanSarana
    // that overlap with the requested period
}
```

### 2. API Endpoint: `/sarana/availability/check`

**Request:**
```
GET /sarana/availability/check?sarana_id=1&start_date=2026-03-12&end_date=2026-03-13&jumlah=3
```

**Query Parameters:**
- `sarana_id`: ID sarana (required)
- `start_date`: Tanggal mulai (Y-m-d format)
- `end_date`: Tanggal selesai (Y-m-d format)
- `jumlah`: Jumlah yang ingin dipinjam (optional)

**Response:**
```json
{
  "success": true,
  "sarana": {
    "id": 1,
    "nama": "Proyektor",
    "kondisi": "Baik"
  },
  "stok": {
    "total": 5,
    "borrowed": 3,
    "available": 2
  },
  "requested_qty": 3,
  "can_borrow": false,
  "date_range": {
    "start_date": "2026-03-12",
    "end_date": "2026-03-13",
    "duration_days": 2
  },
  "overlapping_loans": [
    {
      "id": 1,
      "peminjaman_id": 1,
      "jumlah": 3,
      "tanggal": "2026-03-12",
      "jam_mulai": "2026-03-12 08:00:00",
      "durasi_jam": 4
    }
  ],
  "message": "Stok tidak cukup. Tersedia: 2 unit, diminta: 3 unit"
}
```

### 3. Form Integration

**JavaScript Flow:**
```javascript
1. User fills form:
   - Select sarana
   - Enter tanggal (start date)
   - Enter durasi (hours)
   - Enter jumlah (qty to borrow)

2. On any field change → Call API: /sarana/availability/check

3. API Response:
   - Display: Total Stok, Sudah Dipinjam, Stok Tersedia
   - If can_borrow = true:
     ✓ Show green info box
     ✓ Enable submit button
   - If can_borrow = false:
     ✗ Show red warning box
     ✗ Show message: "Stok tidak cukup"
     ✗ Could optionally disable submit button
```

### 4. Form UI Display

```
┌─────────────────────────────────────┐
│ Informasi Stok:                     │
│                                     │
│ 📦 Total Stok: 5 unit               │
│ 🛒 Sudah Dipinjam: 3 unit           │
│ ✅ Stok Tersedia: 2 unit            │
│                                     │
│ ⚠️  Stok tidak cukup!               │
│    Tersedia: 2 unit, diminta: 3 unit│
└─────────────────────────────────────┘
```

## Routes

### Availability Endpoints
```
GET /sarana/availability/check
  - Check available stock for specific sarana & date range

GET /sarana/availability/list
  - List all saranas with available stock for date range
```

### Peminjaman Sarana Management
```
GET    /admin/peminjaman-sarana              - List all loans
GET    /admin/peminjaman-sarana/create       - Create form
POST   /admin/peminjaman-sarana              - Store loan
GET    /admin/peminjaman-sarana/{id}/edit    - Edit form
PUT    /admin/peminjaman-sarana/{id}         - Update loan
DELETE /admin/peminjaman-sarana/{id}         - Delete loan
```

## Usage Example

### Scenario: Admin membuat peminjaman sarana

1. **Navigate to:** `/admin/peminjaman-sarana/create`

2. **Fill Form:**
   - Tanggal: 2026-03-12
   - Durasi: 4 jam
   - Sarana: Proyektor
   - Jumlah: 2 unit

3. **System Checks:**
   ```
   API Call: /sarana/availability/check
   ?sarana_id=1&start_date=2026-03-12&end_date=2026-03-12&jumlah=2
   
   Response:
   {
     "stok": {
       "total": 5,
       "borrowed": 3,
       "available": 2
     },
     "can_borrow": true
   }
   ```

4. **Display Result:**
   - ✅ Total Stok: 5
   - 🛒 Sudah Dipinjam: 3
   - ✅ Stok Tersedia: 2 ✓

5. **Submit Form:** ✅ Allowed (2 == 2)

## Files Modified/Created

### Models
- `app/Models/Sarana.php` - Added methods for availability calculation

### Controllers
- `app/Http/Controllers/SaranaAvailabilityController.php` - NEW (API endpoints)
- `app/Http/Controllers/PeminjamanSaranaController.php` - NEW (CRUD)

### Views
- `resources/views/main/peminjaman_sarana/index.blade.php` - NEW (List)
- `resources/views/main/peminjaman_sarana/form.blade.php` - NEW (Create/Edit with real-time validation)

### Routes
- `routes/web.php` - Added availability and peminjaman-sarana routes

## Key Features

✅ **Real-time Stock Checking:** Checks availability as user fills form  
✅ **Date Range Overlap Detection:** Correctly identifies overlapping loan periods  
✅ **Hour-Based Precision:** Supports loans with start time and duration in hours  
✅ **Visual Feedback:** Shows total, borrowed, and available quantities  
✅ **Warning System:** Alerts if insufficient stock  
✅ **JSON API:** Reusable endpoints for integrations  
✅ **Date Calculation:** Automatically calculates end date from duration
