# Database Design & Setup Guide

## Sistem Informasi Asrama Haji Kelas I Banjarmasin

> ⚠️ **IMPORTANT**: This document contains both **current schema** fields and **planned/future** fields. Fields marked with "Catatan Pengembangan" (Development Note) are **NOT yet implemented** in `schema.prisma`. Always verify against `prisma/schema.prisma` for the actual current state.

---

## Daftar Isi

1. [Database Overview](#database-overview)
2. [Entity Descriptions](#entity-descriptions)
3. [Relationships & Constraints](#relationships--constraints)
4. [Setup Instructions](#setup-instructions)
5. [Migration Strategy](#migration-strategy)
6. [Indexing Strategy](#indexing-strategy)
7. [Query Optimization Tips](#query-optimization-tips)
8. [Backup & Recovery](#backup--recovery)

---

## Database Overview

### Database Technology

- **DBMS**: MySQL 5.7+ / 8.0+
- **ORM**: Prisma 7+
- **Character Set**: UTF8MB4 (support emoji & special characters)
- **Collation**: utf8mb4_unicode_ci (case-insensitive, unicode-aware)

### Key Features

- ✅ Relational schema dengan constraints
- ✅ Soft-delete support untuk archived data (via flags)
- ✅ Audit trail (ActivityLog) untuk compliance
- ✅ Timestamps (createdAt, updatedAt) di setiap tabel
- ✅ UUID primary keys (better security & distribution)
- ✅ Explicit foreign keys untuk MediaLink model (menggantikan polymorphic relationships)

### Data Volume Estimates

```
Users              : ~50-100 (staff)
Facilities         : ~200-500 (kamar, aula, lapangan)
Guests             : ~10,000-50,000 (per season untuk haji)
Reservations       : ~2,000-10,000 (per bulan PUBLIC_SERVICE)
Occupancy          : ~500-5,000 (active/per season)
Posts              : ~100-500 (news items)
Items              : ~200-500 (inventory items)
Assets             : ~500-2,000 (AC, genset, kasur, dll)
ActivityLog        : ~100,000-1,000,000 (audit trail)
```

---

## Entity Descriptions

### A. System & Configuration

#### 1. **Role**

```sql
-- Hak akses sistem
-- Contoh: Admin, Manager, Resepsionis, Kepala Kloter, Humas, Staf
table role {
  id String @id @default(uuid())
  name String @unique
  description String?
  users User[] @relation("UserRole")
}
```

**Roles Standard**:

- `Admin`: Full access ke semua modul
- `Manager`: Manajemen fasilitas, reservasi, laporan (read-only keuangan)
- `Resepsionis`: Check-in/out, booking management
- `HajjManager`: Khusus musim haji, plotting & bulk operations
- `Finance`: Manajemen invoice & payment
- `Humas`: CMS & media management
- `StaffOperasional`: Inventory & asset maintenance

#### 2. **SystemSetting**

```sql
-- Konfigurasi global
table system_setting {
  id String @id @default(uuid())
  key String @unique              -- "active_mode", "site_name", etc
  value String @db.Text          -- "HAJJ_SEASON" atau "PUBLIC_SERVICE"
  group String @default("general") -- "general", "landing", "api"
  updatedAt DateTime @updatedAt
}
```

**Standard Settings**:

```
Key: active_mode
Value: PUBLIC_SERVICE | HAJJ_SEASON
Description: Mode operasional saat ini

Key: site_name
Value: Asrama Haji Kelas I Banjarmasin

Key: site_description
Value: Pusat layanan akomodasi dan pertemuan terpadu...

Key: currency
Value: IDR

Key: timezone
Value: Asia/Jakarta

Key: booking_confirmation_required
Value: true | false (apakah admin harus confirm booking)

Key: auto_checkout_enabled
Value: true | false

Key: max_advance_booking_days
Value: 180 (max hari ke depan untuk booking)
```

### B. Authentication & Users

#### 3. **User**

```sql
table user {
  id String @id @default(uuid())
  name String              -- Nama lengkap staff
  email String @unique     -- Email login
  password String          -- bcrypt hash (jangan simpan plain text!)
  roleId String           -- Foreign key ke Role
  status String @default("active") -- "active", "inactive", "suspended"
  createdAt DateTime @default(now())
  updatedAt DateTime @updatedAt

  -- Relations
  role Role @relation(fields: [roleId], references: [id])
  posts Post[]             -- Berita yang ditulis by user ini
  activityLogs ActivityLog[] -- Activities that user performed
}
```

**Catatan Pengembangan**: Field `lastLoginAt` planned untuk ditambahkan pada fase upgrade schema berikutnya.

**Constraints**:

- Email harus valid format
- Password minimum 8 karakter, minimal 1 uppercase, 1 number, 1 special char
- Status hanya bisa: active, inactive, suspended

#### 4. **ActivityLog**

```sql
table activity_log {
  id String @id @default(uuid())
  userId String?          -- User yang melakukan action
  action String           -- "CREATE", "UPDATE", "DELETE", "LOGIN", etc
  tableName String        -- Nama tabel yang diubah
  recordId String         -- ID record yang diubah
  details String? @db.Text -- JSON stringify dari perubahan
  createdAt DateTime @default(now())

  -- Relations
  user User? @relation(fields: [userId], references: [id], onDelete: SetNull)
}
```

**Catatan Pengembangan**: Field `ipAddress` dan `userAgent` planned untuk ditambahkan pada fase upgrade schema untuk keperluan audit security yang lebih lengkap.

**Example Details JSON**:

```json
{
  "before": { "status": "DRAFT" },
  "after": { "status": "PUBLISHED" },
  "changedFields": ["status"]
}
```

### C. Location & Facility Hierarchy

#### 5. **Location** (Recursive Structure)

```sql
table location {
  id String @id @default(uuid())
  name String              -- "Gedung A", "Lantai 1", "Ruang 101"
  type LocationType        -- ENUM: GEDUNG, LANTAI, RUANGAN, LEMARI, RAK
  parentId String?         -- Self-join untuk hierarki
  path String?             -- "Gedung A > Lantai 1 > Ruang 101" (denormalized for speed)
  createdAt DateTime @default(now())
  updatedAt DateTime @updatedAt

  -- Relations
  parent Location? @relation("LocationToLocation", fields: [parentId], references: [id], onDelete: Restrict)
  children Location[] @relation("LocationToLocation")
  facilities Facility[]    -- Fasilitas di lokasi ini
  assets AssetItem[]       -- Aset di lokasi ini
  archives ArchiveItem[]   -- Arsip disimpan di sini
}
```

**Catatan Pengembangan**: Field `order` untuk manual ordering planned untuk ditambahkan pada fase upgrade schema.

**Hierarki Struktur**:

```
Gedung A (GEDUNG)
├── Lantai 1 (LANTAI)
│   ├── Ruang 101 (RUANGAN)
│   ├── Ruang 102 (RUANGAN)
│   └── Ruang 103 (RUANGAN)
├── Lantai 2 (LANTAI)
│   └── Ruang 201 (RUANGAN)
└── Gudang (RUANGAN)
    ├── Lemari A (LEMARI)
    │   ├── Rak 1 (RAK)
    │   └── Rak 2 (RAK)
    └── Lemari B (LEMARI)
```

#### 6. **Facility**

```sql
table facility {
  id String @id @default(uuid())
  name String              -- "Kamar 101", "Aula Besar", "Lapangan"
  slug String @unique      -- "kamar-101" (untuk URL)
  locationId String        -- Lokasi fisik
  capacity Int @default(1) -- Kapasitas maksimal orang
  genderPolicy String?     -- ENUM: MALE_ONLY, FEMALE_ONLY, MIXED (critical untuk haji)
  status String @default("AVAILABLE") -- "AVAILABLE", "MAINTENANCE", "FULL", "DISABLED"
  createdAt DateTime @default(now())
  updatedAt DateTime @updatedAt

  -- Relations
  location Location @relation(fields: [locationId], references: [id], onDelete: Restrict)
  services FacilityService[] -- Paket layanan
  occupancies Occupancy[]    -- Siapa saja yang menempati
  reservationItems ReservationItem[] -- Dalam reservasi apa
  mediaLinks MediaLink[]     -- Galeri foto
}
```

**Catatan Pengembangan**: Field `description` planned untuk ditambahkan pada fase upgrade schema untuk deskripsi lengkap fasilitas.

**Constraints**:

- Slug harus unique & lowercase
- Capacity > 0
- genderPolicy penting untuk validasi plotting haji

#### 7. **FacilityService**

```sql
table facility_service {
  id String @id @default(uuid())
  facilityId String       -- Fasilitas mana
  name String             -- "Sewa 8 Jam", "Paket Harian", "VIP Package"
  duration Int?           -- Durasi dalam jam (nullable jika terbuka)
  price Decimal @db.Decimal(15, 2) -- Harga per unit
  isExclusive Boolean @default(false) -- Jika TRUE: 1 booking = entire facility reserved
  createdAt DateTime @default(now())

  -- Relations
  facility Facility @relation(fields: [facilityId], references: [id], onDelete: Cascade)
  reservationItems ReservationItem[]
}
```

**Catatan Pengembangan**: Field `currency` (default IDR), `isActive` (untuk soft-delete), dan `description` planned untuk ditambahkan pada fase upgrade schema.

**Contoh Service**:

```
Kamar Standard
├── Harian (24 jam): Rp 250,000
├── 12 Jam Pagi: Rp 150,000
└── 8 Jam: Rp 100,000

Aula Besar
├── Sewa 4 Jam: Rp 500,000 (exclusive)
├── Sewa 8 Jam: Rp 800,000 (exclusive)
└── Sewa Harian: Rp 1,200,000 (exclusive)
```

### D. Hajj Operations

#### 8. **Kloter**

```sql
table kloter {
  id String @id @default(uuid())
  code String @unique      -- "JKT-01", "SUB-05", "BDG-02"
  province String?         -- "Jakarta", "Surabaya", "Bandung"
  totalPilgrims Int @default(0) -- Target jumlah jemaah
  createdAt DateTime @default(now())

  -- Relations
  guests Guest[]           -- Daftar jemaah dalam kloter
  occupancies Occupancy[]  -- Realisasi plotting kamar kloter
}
```

**Catatan Pengembangan**: Field `departureDate`, `arrivalDate`, `notes` planned untuk ditambahkan pada fase upgrade schema untuk tracking jadwal dan catatan kloter.

#### 9. **Guest**

```sql
table guest {
  id String @id @default(uuid())
  name String              -- Nama lengkap sesuai identitas
  identity String? @unique -- NIK, No. Paspor, atau No. Porsi Haji
  gender String?           -- MALE, FEMALE (Krusial untuk plotting)
  phone String?            -- Nomor kontak

  kloterId String?         -- Jika jemaah haji, merujuk ke kloternya
  kloter Kloter? @relation(fields: [kloterId], references: [id], onDelete: SetNull)

  createdAt DateTime @default(now())
  updatedAt DateTime @updatedAt

  -- Relations
  occupancies Occupancy[]   -- Riwayat menempati fasilitas
  reservations Reservation[] -- Riwayat pemesanan (Public Mode)
}
```

**Catatan Pengembangan**: Field `email`, `bloodType`, dan `notes` planned untuk ditambahkan pada fase upgrade schema.

**Constraints**:

- Gender harus "M" atau "F" (critical untuk validasi genderPolicy saat check-in)
- Identity sebaiknya unique (nik/paspor tidak boleh duplikat)

### E. Reservation & Occupancy System

#### 10. **Reservation**

```sql
table reservation {
  id String @id @default(uuid())
  guestId String            -- Pemesan utama
  guest Guest @relation(fields: [guestId], references: [id])
  bookingCode String @unique -- Kode unik reservasi
  status ReservationStatus @default(CONFIRMED) -- DRAFT, PENDING, CONFIRMED, CANCELLED
  totalPrice Decimal @db.Decimal(15, 2) -- Total biaya keseluruhan

  createdAt DateTime @default(now())
  updatedAt DateTime @updatedAt

  -- Relations
  items ReservationItem[] -- Detail item yang dipesan
  occupancies Occupancy[] -- Realisasi check-in dari pesanan
  invoice Invoice?        -- Tagihan untuk pesanan ini
}
```

**Catatan Pengembangan**: Field `currency`, `checkinDate`, `checkoutDate`, `notes` planned untuk ditambahkan pada fase upgrade schema untuk mendukung fitur lengkap booking.

**Status Flow**:

```
DRAFT → PENDING → CONFIRMED → [COMPLETED]
        ↓
        CANCELLED
```

#### 11. **ReservationItem**

```sql
table reservation_item {
  id String @id @default(uuid())
  reservationId String
  reservation Reservation @relation(fields: [reservationId], references: [id])

  facilityId String      -- Fasilitas yang dipesan
  facility Facility @relation(fields: [facilityId], references: [id])

  serviceId String?      -- Paket layanan yang dipilih (8 jam, 12 jam, dll)
  service FacilityService? @relation(fields: [serviceId], references: [id])

  quantity Int @default(1) -- Jumlah unit/orang
  subtotal Decimal @db.Decimal(15, 2) -- Harga x Quantity
}
```

**Catatan Pengembangan**: Field `notes` planned untuk ditambahkan untuk catatan khusus per item reservasi.

**Contoh**:

```
Reservation BK-20260330-00001
├── Item 1: Kamar 101 + Harian (24 jam) × 2 nights = Rp 500,000
└── Item 2: Aula Besar + 4 Jam × 1 = Rp 500,000
Total: Rp 1,000,000
```

#### 12. **Occupancy** - The Heart of the System

```sql
table occupancy {
  id String @id @default(uuid())
  facilityId String       -- Fasilitas yang ditempati
  guestId String?         -- Tamu individual (bisa null jika kloter)
  kloterId String?        -- Kloter (bisa null jika individual)
  reservationId String?   -- Jika dari reservasi retail

  isExclusive Boolean @default(false) -- Apakah menempati eksklusif?
  capacityUsed Int @default(1) -- Berapa kapasitas yang terpakai

  checkInTime DateTime @default(now())
  checkOutTime DateTime?
  expectedCheckOutTime DateTime? -- Prediksi check-out (untuk planning)
  status String @default("CHECKED_IN") -- "CHECKED_IN", "CHECKED_OUT"

  damageNotes String? @db.Text -- Catatan kerusakan saat checkout
  estimatedDamageCost Decimal? @db.Decimal(15, 2)

  createdAt DateTime @default(now())
  updatedAt DateTime @updatedAt

  -- Relations
  facility Facility @relation(fields: [facilityId], references: [id], onDelete: Restrict)
  guest Guest? @relation(fields: [guestId], references: [id], onDelete: SetNull)
  kloter Kloter? @relation(fields: [kloterId], references: [id], onDelete: SetNull)
  reservation Reservation? @relation(fields: [reservationId], references: [id], onDelete: SetNull)
}
```

**Business Rules**:

- Minimum one of `guestId` atau `kloterId` harus ada
- `checkOutTime` nullable (hanya terisi saat checked out)
- `status` auto-update ke CHECKED_OUT saat checkOutTime di-set
- Occupancy adalah "fact" sistem - jangan pernah dihapus (soft-delete saja)

### F. Financial

#### 13. **Invoice**

```sql
table invoice {
  id String @id @default(uuid())
  reservationId String @unique -- Satu invoice per reservasi
  grandTotal Decimal @db.Decimal(15, 2)
  discountAmount Decimal @db.Decimal(15, 2) @default(0)
  taxAmount Decimal @db.Decimal(15, 2) @default(0)
  netTotal Decimal @db.Decimal(15, 2) -- grandTotal - discount + tax
  status String @default("UNPAID") -- "UNPAID", "PARTIAL", "PAID", "OVERDUE"
  issuedAt DateTime @default(now())
  dueDate DateTime?
  notes String? @db.Text
  createdAt DateTime @default(now())
  updatedAt DateTime @updatedAt

  -- Relations
  reservation Reservation @relation(fields: [reservationId], references: [id], onDelete: Restrict)
  payments Payment[] -- Riwayat pembayaran
}
```

#### 14. **Payment**

```sql
table payment {
  id String @id @default(uuid())
  invoiceId String
  amount Decimal @db.Decimal(15, 2)
  method String -- "CASH", "TRANSFER", "QRIS", "CREDIT_CARD", "CHECK"
  reference String? -- Nomor referensi (untuk TRANSFER: nomor bukti transfer)
  notes String? @db.Text
  paidAt DateTime @default(now())
  createdAt DateTime @default(now())

  -- Relations
  invoice Invoice @relation(fields: [invoiceId], references: [id], onDelete: Cascade)
}
```

### G. Inventory & Assets

#### 15. **Item** (Barang Habis Pakai)

```sql
table item {
  id String @id @default(uuid())
  name String              -- "Sabun Cair 5L", "Sprei B2", "Tisu Gulung"
  unit String              -- "PCS", "BOX", "KG", "LITER"
  category String?         -- "Kebersihan", "Tekstil", "Makanan", "Supplies"
  stock Int @default(0)    -- Stok saat ini
  reorderLevel Int @default(10) -- Threshold untuk alert
  reorderQuantity Int @default(50) -- Jumlah reorder default
  unitPrice Decimal @db.Decimal(15, 2) -- Harga satuan (untuk costing)
  notes String? @db.Text
  isActive Boolean @default(true)
  createdAt DateTime @default(now())
  updatedAt DateTime @updatedAt

  -- Relations
  transactions StockTransaction[]
  mediaLinks MediaLink[] -- Foto produk
}
```

#### 16. **StockTransaction**

```sql
table stock_transaction {
  id String @id @default(uuid())
  itemId String
  type String              -- "IN" (masuk), "OUT" (keluar), "ADJUSTMENT"
  quantity Int
  reference String?        -- "PO-2026-0145", "Distribusi Kamar 101", etc
  notes String? @db.Text
  recordedBy String?       -- User yang mencatat
  createdAt DateTime @default(now())

  -- Relations
  item Item @relation(fields: [itemId], references: [id], onDelete: Restrict)
}
```

**Example Transactions**:

```
IN: Diterima 100 PCS sabun (PO-2026-0145)
OUT: Dikeluarkan 50 PCS ke Gedung A (daily distribution)
OUT: Dikeluarkan 5 PCS ke Kamar 101 (maintenance call)
ADJUSTMENT: Stok fisik vs sistem berbeda 2 pcs (inventory count)
```

#### 17. **AssetItem** (Aset Tetap)

```sql
table asset_item {
  id String @id @default(uuid())
  assetCode String @unique -- "AC-2026-001", "GNS-2026-001"
  name String              -- "AC Panasonic 2 PK", "Genset 5 kVA"
  type String?             -- "COOLER", "GENSET", "FURNITURE", "KITCHEN", etc
  locationId String        -- Lokasi aset saat ini
  condition String @default("GOOD") -- "GOOD", "FAIR", "POOR", "BROKEN"
  status String @default("ACTIVE") -- "ACTIVE", "SCRAPPED", "RESERVED"
  purchaseDate DateTime?   -- Tanggal pembelian
  purchasePrice Decimal? @db.Decimal(15, 2)
  warranty DateTime?       -- Garansi sampai kapan
  notes String? @db.Text
  createdAt DateTime @default(now())
  updatedAt DateTime @updatedAt

  -- Relations
  location Location @relation(fields: [locationId], references: [id])
  maintenances AssetMaintenance[]
  mediaLinks MediaLink[] -- Foto aset
}
```

#### 18. **AssetMaintenance** (Riwayat Perawatan)

```sql
table asset_maintenance {
  id String @id @default(uuid())
  assetId String
  maintenanceType String -- "PREVENTIVE", "CORRECTIVE", "INSPECTION"
  maintenanceDate DateTime @default(now())
  cost Decimal? @db.Decimal(15, 2)
  description String? @db.Text -- Detail maintenance yang dilakukan
  vendor String?         -- Vendor/teknisi yang melakukan
  nextScheduled DateTime? -- Jadwal maintenance berikutnya
  notes String? @db.Text
  createdAt DateTime @default(now())

  -- Relations
  asset AssetItem @relation(fields: [assetId], references: [id], onDelete: Cascade)
}
```

### H. Content Management

#### 19. **Post** (Berita/Pengumuman)

```sql
table post {
  id String @id @default(uuid())
  title String            -- "Selamat Datang di Asrama Haji"
  slug String @unique     -- "selamat-datang-asrama-haji"
  content String @db.Text -- Isi berita (markdown atau HTML)
  excerpt String?         -- Ringkasan preview
  status String @default("DRAFT") -- "DRAFT", "PUBLISHED", "ARCHIVED"

  authorId String         -- User yang membuat post
  publishedAt DateTime?   -- Waktu publikasi (nullable jika draft)
  featuredImage String?   -- Thumbnail image path

  createdAt DateTime @default(now())
  updatedAt DateTime @updatedAt

  -- Relations
  author User @relation(fields: [authorId], references: [id], onDelete: Restrict)
  mediaLinks MediaLink[] -- Gallery images
}
```

### I. Media & Archive

#### 20. **MediaFile**

```sql
table media_file {
  id String @id @default(uuid())
  fileName String       -- Nama file asli
  filePath String       -- Path relatif di storage

  -- Relations
  links MediaLink[]     -- Entitas mana saja yang menggunakan file ini
}
```

**Catatan Pengembangan**: Field `mimeType`, `size`, `url`, `uploadedBy`, `deletedAt`, `createdAt`, `updatedAt` planned untuk ditambahkan pada fase upgrade schema untuk tracking lengkap file.

#### 21. **MediaLink** (Explicit Foreign Keys)

```sql
table media_link {
  id String @id @default(uuid())
  mediaId String        -- FK ke MediaFile

  -- Explicit optional FKs untuk masing-masing entity
  facilityId String?
  postId String?
  assetId String?
  archiveId String?
  itemId String?

  -- Relations
  mediaFile MediaFile @relation(fields: [mediaId], references: [id])
  facility Facility? @relation(fields: [facilityId], references: [id])
  post Post? @relation(fields: [postId], references: [id])
  asset AssetItem? @relation(fields: [assetId], references: [id])
  archive ArchiveItem? @relation(fields: [archiveId], references: [id])
  item Item? @relation(fields: [itemId], references: [id])

  @@index([mediaId])
  @@index([facilityId])
  @@index([postId])
  @@index([assetId])
  @@index([archiveId])
  @@index([itemId])
}
```

**Catatan Pengembangan**: Field `orderIndex` planned untuk ditambahkan untuk mengatur urutan media dalam galeri.

**Contoh Usage**:

```
Post "Renovasi Kamar" (id: post-123)
  └── MediaLink: postId="post-123", mediaId="media-456"

Facility "Kamar 101" (id: fac-456)
  ├── MediaLink: facilityId="fac-456", mediaId="media-101"
  └── MediaLink: facilityId="fac-456", mediaId="media-102"

AssetItem "AC Samsung" (id: asset-789)
  └── MediaLink: assetId="asset-789", mediaId="media-201"
```

**Note**: Query dilakukan dengan filter specific foreign key, misalnya `WHERE facilityId = 'fac-456'` untuk mendapatkan semua media milik satu entitas.

#### 22. **ArchiveItem** (Dokumen Fisik)

```sql
table archive_item {
  id String @id @default(uuid())
  code String @unique     -- "AR-2026-0001", "DOK-HAJI-2025"
  name String             -- "Surat Perjanjian Kerjasama 2025"
  description String? @db.Text
  locationId String       -- Disimpan di lemari/rak mana
  category String?        -- "KONTRAK", "LAPORAN", "SURAT", "PERATURAN"
  retentionUntil DateTime? -- Kapan arsip bisa disikat
  notes String? @db.Text
  createdAt DateTime @default(now())
  updatedAt DateTime @updatedAt

  -- Relations
  location Location @relation(fields: [locationId], references: [id])
  mediaLinks MediaLink[] -- Scanned documents
}
```

---

## Relationships & Constraints

### Key Relationships

| From            | To          | Type | Behavior                                                     |
| --------------- | ----------- | ---- | ------------------------------------------------------------ |
| Reservation     | Guest       | M:1  | `onDelete: Restrict` - Jangan hapus guest jika ada reservasi |
| Reservation     | Invoice     | 1:1  | `onDelete: Cascade` - Hapus invoice jika reservasi dihapus   |
| ReservationItem | Reservation | M:1  | `onDelete: Cascade`                                          |
| ReservationItem | Facility    | M:1  | `onDelete: Restrict`                                         |
| Occupancy       | Facility    | M:1  | `onDelete: Restrict`                                         |
| Occupancy       | Guest       | M:1  | `onDelete: SetNull` - Jangan force delete guest              |
| Occupancy       | Kloter      | M:1  | `onDelete: SetNull`                                          |
| Facility        | Location    | M:1  | `onDelete: Restrict`                                         |
| AssetItem       | Location    | M:1  | `onDelete: Restrict`                                         |
| Post            | User        | M:1  | `onDelete: Restrict`                                         |
| User            | Role        | M:1  | `onDelete: Restrict`                                         |

### Unique Constraints

```sql
-- Email harus unique (login identifier)
user.email UNIQUE

-- Guest identity harus unique
guest.identity UNIQUE (nullable field)

-- Facility slug harus unique (untuk URL)
facility.slug UNIQUE

-- Post slug harus unique
post.slug UNIQUE

-- Reservation booking code harus unique
reservation.bookingCode UNIQUE

-- Kloter code harus unique
kloter.code UNIQUE

-- Asset code harus unique
asset_item.assetCode UNIQUE

-- Archive item code harus unique
archive_item.code UNIQUE

-- Location path (parent + name) atau bisa bikin unique key
-- location.name UNIQUE PER parent
```

### Business Rules & Validations

```
--- OCCUPANCY RULES ---
1. Either guestId OR kloterId must be present (NOT NULL check)
   - ValidateOccupancy: !occupancy.guestId && !occupancy.kloterId → ERROR

2. Facility genderPolicy must match guest gender (saat check-in)
   - IF facility.genderPolicy = "MALE_ONLY" AND guest.gender = "F" → ERROR
   - IF facility.genderPolicy = "FEMALE_ONLY" AND guest.gender = "M" → ERROR

3. Facility kapasitas tidak boleh dilampaui
   - SUM(occupancy.capacityUsed WHERE facilityId = X AND status = CHECKED_IN) ≤ facility.capacity

4. Occupancy check-out hanya bisa jika sudah check-in (status = CHECKED_IN)
   - IF occupancy.status = CHECKED_OUT → ERROR (already checked out)

5. Occupancy tidak boleh overlap (untuk exclusive facilities)
   - IF facility.isExclusive AND occupancy_new.checkInTime < occupancy_existing.checkOutTime → CONFLICT

--- RESERVATION RULES ---
1. Reservasi harus memiliki minimal 1 ReservationItem
2. ReservationItem quantity harus > 0
3. Booking code auto-generate unique
4. Status transitions:
   - DRAFT → PENDING (user request)
   - PENDING → CONFIRMED (admin atau payment confirmed)
   - PENDING/CONFIRMED → CANCELLED (anytime)
   - CANCELLED → tidak bisa kembali ke status lain

--- FACILITY RULES ---
1. Slug harus lowercase, no special chars (a-z, 0-9, hyphen)
2. Capacity ≥ 1
3. Untuk HAJJ_SEASON: genderPolicy harus MALE_ONLY atau FEMALE_ONLY (tidak boleh MIXED)
   - (dapat override via system setting jika ada kebutuhan khusus)

--- INVENTORY RULES ---
1. Stock tidak boleh negative
   - Sebelum OUT transaction, validasi stock ≥ quantity OUT
2. Reorder trigger: IF stock ≤ reorderLevel → alert procurement
3. Setiap transaction harus punya reference untuk traceability

--- AUDIT RULES ---
1. Setiap CREATE/UPDATE/DELETE harus log ke ActivityLog
   - Include: userId, action, tableName, recordId, details (JSON), timestamp
2. DeletedAt fields harus soft-delete (jangan hard delete kecuali archival old data)
3. Password hashes NEVER logged/audited (privacy)
```

---

## Setup Instructions

### 1. Prerequisites

```bash
# Install Node.js 18+ & npm
node --version  # v18+
npm --version   # v9+

# Install MySQL 8.0+
mysql --version # 8.0+

# Install Git
git --version
```

### 2. Clone Repository

```bash
git clone https://github.com/asrama-haji/sys-info.git
cd my-app
npm install
```

### 3. Setup Environment Variables

```bash
# Copy dari example
cp .env.example .env.local

# Edit .env.local dengan config lokal
# DATABASE_URL=mysql://user:password@localhost:3306/asrama_haji_db
# JWT_SECRET=your-secret-key-min-32-chars
# NEXTAUTH_SECRET=another-secret-key
```

### 4. Create MySQL Database

```bash
mysql -u root -p
# In MySQL prompt:
CREATE DATABASE asrama_haji_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'asrama_user'@'localhost' IDENTIFIED BY 'SecurePassword123!';
GRANT ALL PRIVILEGES ON asrama_haji_db.* TO 'asrama_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 5. Run Prisma Migrations

```bash
# Generate Prisma Client
npx prisma generate

# Run migrations ke database
npx prisma migrate deploy

# Seed dengan data awal (optional)
npx prisma db seed
```

### 6. Verify Setup

```bash
# Open Prisma Studio untuk inspect database
npx prisma studio
# Buka http://localhost:5555

# Run development server
npm run dev
# Buka http://localhost:3000
```

### 7. Initial Data Seeding

**File**: `prisma/seed.ts`

```typescript
import { PrismaClient } from "@prisma/client";
import bcrypt from "bcryptjs";

const prisma = new PrismaClient();

async function main() {
  // 1. Create Roles
  const adminRole = await prisma.role.upsert({
    where: { name: "Admin" },
    update: {},
    create: {
      name: "Admin",
      description: "Full system access",
    },
  });

  // 2. Create Default Admin User
  const hashedPassword = await bcrypt.hash("Admin123!", 10);
  await prisma.user.upsert({
    where: { email: "admin@asrama.local" },
    update: {},
    create: {
      name: "Administrator",
      email: "admin@asrama.local",
      password: hashedPassword,
      roleId: adminRole.id,
      status: "active",
    },
  });

  // 3. Create Default System Settings
  await prisma.systemSetting.upsert({
    where: { key: "active_mode" },
    update: { value: "PUBLIC_SERVICE" },
    create: { key: "active_mode", value: "PUBLIC_SERVICE", group: "general" },
  });

  await prisma.systemSetting.upsert({
    where: { key: "site_name" },
    update: {},
    create: {
      key: "site_name",
      value: "Asrama Haji Kelas I Banjarmasin",
      group: "general",
    },
  });

  console.log("✅ Seeding completed");
}

main()
  .catch((e) => console.error(e))
  .finally(async () => await prisma.$disconnect());
```

**Run seeding**:

```bash
npx prisma db seed
```

---

## Migration Strategy

### Development Flow

```bash
# 1. Modify schema.prisma
# 2. Create migration dengan deskriptif
npx prisma migrate dev --name "add_user_phone_field"
# → Prisma auto-apply ke dev DB & generate Client

# 3. Test changes locally
npm run dev
# → Manual testing & validation

# 4. Commit perubahan
git add prisma/
git commit -m "feat: add phone field to User model"
```

### Production Flow

```bash
# Testing environment
# 1. Create test database copy
# 2. Run: npx prisma migrate deploy
# 3. Verify aplikasi works
# 4. Backup production database
# 5. Run migration di production
# 6. Monitor error logs

# Rollback procedure (jika ada issue)
# Restore dari backup, tidak ada automatic rollback
# Harus create new migration untuk reverse perubahan
```

---

## Indexing Strategy

### Recommended Indexes

```sql
-- Authentication & Authorization
CREATE INDEX idx_user_email ON user(email);
CREATE INDEX idx_user_roleId ON user(roleId);
CREATE INDEX idx_user_status ON user(status);
CREATE INDEX idx_user_createdAt ON user(createdAt);

-- Location Hierarchy
CREATE INDEX idx_location_parentId ON location(parentId);
CREATE INDEX idx_location_type ON location(type);

-- Facility Management
CREATE INDEX idx_facility_locationId ON facility(locationId);
CREATE INDEX idx_facility_status ON facility(status);
CREATE INDEX idx_facility_slug ON facility(slug);

-- Occupancy (heavily queried)
CREATE INDEX idx_occupancy_facilityId ON occupancy(facilityId);
CREATE INDEX idx_occupancy_guestId ON occupancy(guestId);
CREATE INDEX idx_occupancy_kloterId ON occupancy(kloterId);
CREATE INDEX idx_occupancy_status ON occupancy(status);
CREATE INDEX idx_occupancy_checkInTime ON occupancy(checkInTime);
CREATE INDEX idx_occupancy_checkOutTime ON occupancy(checkOutTime);
CREATE INDEX idx_occupancy_facility_status ON occupancy(facilityId, status);

-- Reservation
CREATE INDEX idx_reservation_guestId ON reservation(guestId);
CREATE INDEX idx_reservation_status ON reservation(status);
CREATE INDEX idx_reservation_bookingCode ON reservation(bookingCode);
CREATE INDEX idx_reservation_createdAt ON reservation(createdAt);

-- ReservationItem
CREATE INDEX idx_reservationItem_reservationId ON reservation_item(reservationId);
CREATE INDEX idx_reservationItem_facilityId ON reservation_item(facilityId);

-- Kloter & Guest
CREATE INDEX idx_guest_kloterId ON guest(kloterId);
CREATE INDEX idx_guest_identity ON guest(identity);
CREATE INDEX idx_kloter_code ON kloter(code);

-- Inventory
CREATE INDEX idx_item_name ON item(name);
CREATE INDEX idx_item_category ON item(category);
CREATE INDEX idx_stockTransaction_itemId ON stock_transaction(itemId);
CREATE INDEX idx_stockTransaction_type ON stock_transaction(type);

-- Assets
CREATE INDEX idx_assetItem_locationId ON asset_item(locationId);
CREATE INDEX idx_assetItem_status ON asset_item(status);
CREATE INDEX idx_assetItem_condition ON asset_item(condition);
CREATE INDEX idx_assetMaintenance_assetId ON asset_maintenance(assetId);

-- Posts
CREATE INDEX idx_post_authorId ON post(authorId);
CREATE INDEX idx_post_status ON post(status);
CREATE INDEX idx_post_slug ON post(slug);
CREATE INDEX idx_post_publishedAt ON post(publishedAt);

-- Activity Log (untuk audit queries)
CREATE INDEX idx_activityLog_userId ON activity_log(userId);
CREATE INDEX idx_activityLog_action ON activity_log(action);
CREATE INDEX idx_activityLog_tableName ON activity_log(tableName);
CREATE INDEX idx_activityLog_createdAt ON activity_log(createdAt);

-- MediaLink (explicit relation queries)
CREATE INDEX idx_mediaLink_mediaId ON media_link(mediaId);
CREATE INDEX idx_mediaLink_facilityId ON media_link(facilityId);
CREATE INDEX idx_mediaLink_postId ON media_link(postId);
CREATE INDEX idx_mediaLink_assetId ON media_link(assetId);
CREATE INDEX idx_mediaLink_archiveId ON media_link(archiveId);
CREATE INDEX idx_mediaLink_itemId ON media_link(itemId);

-- Invoice & Payment
CREATE INDEX idx_invoice_reservationId ON invoice(reservationId);
CREATE INDEX idx_invoice_status ON invoice(status);
CREATE INDEX idx_payment_invoiceId ON payment(invoiceId);
```

---

## Query Optimization Tips

### 1. Use Include/Select untuk nested relations

```typescript
// ❌ BAD: N+1 query problem
const reservations = await prisma.reservation.findMany();
for (const res of reservations) {
  const guest = await prisma.guest.findUnique({ where: { id: res.guestId } });
  console.log(guest.name);
}

// ✅ GOOD: Single query dengan related data
const reservations = await prisma.reservation.findMany({
  include: {
    guest: true,
    items: {
      include: {
        facility: true,
        service: true,
      },
    },
  },
});
```

### 2. Pagination untuk large datasets

```typescript
// ❌ BAD: Fetch semua data
const allOccupancies = await prisma.occupancy.findMany();

// ✅ GOOD: Paginate
const page = 1;
const pageSize = 20;
const occupancies = await prisma.occupancy.findMany({
  skip: (page - 1) * pageSize,
  take: pageSize,
  include: { facility: true, guest: true },
});

const total = await prisma.occupancy.count();
```

### 3. Filtering efficiently

```typescript
// ✅ Filter di query level (database), bukan di aplikasi
const activeOccupancies = await prisma.occupancy.findMany({
  where: {
    status: "CHECKED_IN",
    facility: {
      status: "AVAILABLE",
    },
  },
});
```

### 4. Denormalization untuk frequently accessed data

```typescript
// Jika `occupancy.location.path` frequently needed:
// Add `locationPath` denormalized field di occupancy table
// Atau cache di application layer

const occupancy = await prisma.occupancy.findUnique({
  where: { id: occupancyId },
  include: {
    facility: {
      include: { location: true },
    },
  },
});
```

---

## Backup & Recovery

### Daily Backup Script

```bash
#!/bin/bash
# backup.sh

BACKUP_DIR="/backups/asrama-haji"
DB_NAME="asrama_haji_db"
DB_USER="asrama_user"
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")

# Create backup directory
mkdir -p $BACKUP_DIR

# Mysqldump dengan compression
mysqldump -u $DB_USER -p $DB_NAME | gzip > $BACKUP_DIR/${DB_NAME}_${TIMESTAMP}.sql.gz

# Keep only last 7 days
find $BACKUP_DIR -name "*.sql.gz" -mtime +7 -delete

echo "✅ Backup completed: ${DB_NAME}_${TIMESTAMP}.sql.gz"
```

**Cron Schedule**:

```
# Run daily at 2 AM
0 2 * * * /usr/local/bin/backup.sh >> /var/log/asrama-backup.log 2>&1
```

### Restore from Backup

```bash
# Dekompres
gunzip $BACKUP_DIR/asrama_haji_db_20260330_020000.sql.gz

# Restore
mysql -u asrama_user -p asrama_haji_db < asrama_haji_db_20260330_020000.sql

# Verify
mysql -u asrama_user -p -e "SELECT COUNT(*) FROM asrama_haji_db.user;"
```

---

**Status**: Draft - Ready for Development  
**Version**: 1.0  
**Date**: 30 Maret 2026
