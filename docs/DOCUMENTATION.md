# Sistem Informasi Asrama Haji Kelas I Banjarmasin

## Dokumentasi Lengkap Arsitektur & Workflow

---

## Daftar Isi

1. [Visi & Misi Sistem](#visi--misi)
2. [Arsitektur Sistem](#arsitektur-sistem)
3. [Model Data & Entity Relationships](#model-data)
4. [Workflow Operasional](#workflow-operasional)
5. [Spesifikasi Teknis](#spesifikasi-teknis)
6. [Panduan Integrasi API](#panduan-integrasi-api)

---

## Visi & Misi

### Visi

Menyediakan sistem manajemen terintegrasi untuk mengelola fasilitas Asrama Haji Kelas I Banjarmasin secara efisien, transparan, dan profesional dengan kemampuan dual-mode (Musim Haji & Pelayanan Umum).

### Misi

1. Mengotomasi pengelolaan kamar dan fasilitas
2. Memfasilitasi proses plotting massal jemaah dengan akurat
3. Menyediakan sistem reservasi komersial yang user-friendly
4. Mengintegrasikan manajemen keuangan, aset, dan inventaris
5. Menyediakan dashboard analytics untuk pengambilan keputusan strategis
6. Memastikan audit trail lengkap untuk transparansi dan compliance

---

## Arsitektur Sistem

### 1. Arsitektur Tingkat Tinggi (High-Level)

```
┌─────────────────────────────────────────────────────────────────┐
│                         PUBLIC LAYER                             │
├─────────────────────────────────────────────────────────────────┤
│  Landing Page (Hero, Features, News)                             │
│  Booking Widget / Search & Filter Fasilitas                      │
│  Guest Portal (Tracking Reservasi, e-Ticket)                     │
└────────────┬────────────────────────────────────────────────────┘
             │
             ▼
┌─────────────────────────────────────────────────────────────────┐
│                      API GATEWAY LAYER                           │
├─────────────────────────────────────────────────────────────────┤
│  Authentication (JWT/SessionToken)                               │
│  Authorization & RBAC                                            │
│  Rate Limiting & Throttling                                      │
└────────────┬────────────────────────────────────────────────────┘
             │
        ┌────┴───────────────┬──────────────────┐
        ▼                    ▼                   ▼
┌─────────────────┐  ┌──────────────────┐  ┌──────────────┐
│  ADMIN PANEL    │  │  MOBILE APP API  │  │  INTEGRATION │
│  Dashboard      │  │  (Future)        │  │  Points      │
│  Management     │  │                  │  │              │
└────────┬────────┘  └──────────────────┘  └──────────────┘
         │
         ▼
┌─────────────────────────────────────────────────────────────────┐
│                    BUSINESS LOGIC LAYER                          │
├─────────────────────────────────────────────────────────────────┤
│  ┌─────────────┬─────────────┬─────────────┬──────────────────┐ │
│  │ Auth & RBAC │ Reservation │ Occupancy   │ Financial        │ │
│  │ Management  │ Management  │ Management  │ Management       │ │
│  └─────────────┴─────────────┴─────────────┴──────────────────┘ │
│  ┌─────────────┬─────────────┬─────────────┬──────────────────┐ │
│  │ Facility    │ Hajj Ops    │ Inventory   │ Asset            │ │
│  │ Management  │ (Kloter)    │ Management  │ Management       │ │
│  └─────────────┴─────────────┴─────────────┴──────────────────┘ │
│  ┌─────────────┬─────────────┬─────────────┬──────────────────┐ │
│  │ Media       │ CMS         │ Reporting   │ Audit Logging    │ │
│  │ Management  │ Management  │ & Analytics │                  │ │
│  └─────────────┴─────────────┴─────────────┴──────────────────┘ │
└────────────┬────────────────────────────────────────────────────┘
             │
             ▼
┌─────────────────────────────────────────────────────────────────┐
│                    DATA ACCESS LAYER (Prisma)                   │
├─────────────────────────────────────────────────────────────────┤
│  Query Builder & ORM  │  Migrations  │  Relationships            │
└────────────┬────────────────────────────────────────────────────┘
             │
             ▼
┌─────────────────────────────────────────────────────────────────┐
│              PERSISTENT LAYER (MySQL Database)                  │
├─────────────────────────────────────────────────────────────────┤
│  Schema: Role, User, SystemSetting, Location, Facility...        │
└─────────────────────────────────────────────────────────────────┘
```

### 2. Mode Operasional

Sistem beroperasi dalam **dua mode utama**:

#### Mode A: HAJJ_SEASON (Musim Haji)

- **Fokus**: Pengelolaan massal jemaah berdasarkan kloter
- **Entitas Utama**: `Kloter`, `Guest`, `Location`, `Facility`
- **Proses Inti**: Data entry kloter → Import jemaah → Plotting massal → Check-in massal → Manajemen logistik → Check-out massal
- **Pengguna Utama**: Admin Operasional, Kepala Kloter, Staf Resepsionis
- **Tidak Fokus Pada**: Billing/Payment (umumnya ditangani pemerintah), Reservasi retail

#### Mode B: PUBLIC_SERVICE (Pelayanan Umum)

- **Fokus**: Layanan komersial seperti hotel biasa
- **Entitas Utama**: `Guest` (Individu), `Reservation`, `Invoice`, `Payment`, `Occupancy`
- **Proses Inti**: Browse fasilitas → Booking online → Pembayaran → Check-in → Layanan → Check-out
- **Pengguna Utama**: Calon tamu, Customer service, Admin Frontend Desk
- **Fokus Pada**: Revenue management, availability, pricing dinamis

**Catatan**: Mode dapat di-switch via "Pengaturan Sistem" tanpa migrasi data.

### 3. Modul-Modul Sistem

| No  | Modul                   | Deskripsi                           | Tabel Utama                  |
| --- | ----------------------- | ----------------------------------- | ---------------------------- |
| 1   | **Auth & Security**     | Login, Role-based access control    | Role, User                   |
| 2   | **System Config**       | Pengaturan global, switch mode      | SystemSetting                |
| 3   | **Media Management**    | Upload, penyimpanan file, galeri    | MediaFile, MediaLink         |
| 4   | **CMS**                 | Manajemen berita/pengumuman publik  | Post                         |
| 5   | **Location Manager**    | Hierarchi gedung-lantai-ruangan     | Location                     |
| 6   | **Facility Manager**    | Data kamar, aula, lapangan, pricing | Facility, FacilityService    |
| 7   | **Hajj Operations**     | Kloter, plotting massal, check-in   | Kloter, Guest, Occupancy     |
| 8   | **Reservation**         | Booking online, manajemen pesanan   | Reservation, ReservationItem |
| 9   | **Occupancy**           | Check-in/out, occupancy tracking    | Occupancy                    |
| 10  | **Financial**           | Invoice, payment, laporan keuangan  | Invoice, Payment             |
| 11  | **Inventory**           | Manajemen barang habis pakai        | Item, StockTransaction       |
| 12  | **Asset Management**    | Perawatan aset tetap                | AssetItem, AssetMaintenance  |
| 13  | **Archive Management**  | Penyimpanan dokumen fisik           | ArchiveItem                  |
| 14  | **Audit & Logging**     | Trail aktivitas pengguna            | ActivityLog                  |
| 15  | **Reports & Analytics** | Dashboard, laporan operasional      | (Custom views)               |

---

## Model Data

### Entity Relationship Diagram (ERD) - Ringkas

```
┌──────────────┐
│    Role      │
└──────┬───────┘
       │ 1:N
       ▼
┌──────────────┐        ┌─────────────────┐
│    User      │────────│  ActivityLog    │
└──────┬───────┘        └─────────────────┘
       │ 1:N
       ▼
    ┌──────────┐
    │  Post    │
    └──────┬───┘
           │ 1:N (via MediaLink)
           ▼
┌─────────────────────────────────────────┐
│       Media Management                  │
│  ┌──────────────┐  ┌──────────────────┐ │
│  │ MediaFile    │──│ MediaLink        │ │
│  │              │  │ (Explicit FKs)   │ │
│  └──────────────┘  └──────────────────┘ │
└─────────────────────────────────────────┘

┌────────────────────────────────────┐
│   Location Hierarchy               │
│   ┌──────────────┐                 │
│   │  Location    │                 │
│   │  (Recursive) │─┐               │
│   └──────────────┘ │ 1:N           │
│         ▲          │               │
│         └──────────┘               │
│   Gedung > Lantai > Ruangan        │
└────────────────────────────────────┘
           │ 1:N
           ▼
┌──────────────────────────┐
│  Facility                │
│  (Kamar, Aula, dll)      │
├──────────────────────────┤
│ • name                   │
│ • capacity               │
│ • genderPolicy           │
│ • status                 │
└──────────┬───────────────┘
           │ 1:N
           ▼
    ┌────────────────────┐
    │ FacilityService    │
    │ (Paket harga sewa) │
    └────────┬───────────┘
             │ 1:N
             ▼

┌─────────────────┐       ┌────────────────┐
│   Kloter        │       │    Guest       │
│   (Musim Haji)  │───────│  (Orang)       │
└────────┬────────┘  1:N  └────────┬───────┘
         │                         │
         │ 1:N                     │ 1:N
         │                         │
         └──────────┬──────────────┘
                    ▼
         ┌──────────────────┐
         │   Occupancy      │
         │  (Who-Where-When)│
         └────────┬─────────┘
                  │ 1:1
                  ▼
    ┌───────────────────────┐
    │  Reservation          │
    │  (Public Mode)        │
    └───┬───────────┬───────┘
        │ 1:N       │ 1:1
        ▼           ▼
┌──────────────────┐   ┌─────────────┐
│ ReservationItem  │   │  Invoice    │
└──────────────────┘   └──────┬──────┘
                               │ 1:N
                               ▼
                         ┌──────────────┐
                         │  Payment     │
                         └──────────────┘

┌──────────────────┐       ┌──────────────────┐
│   Item           │       │  AssetItem       │
│  (Barang Habis)  │       │  (Aset Tetap)    │
└────────┬─────────┘       └────────┬─────────┘
         │ 1:N                      │ 1:N
         ▼                          ▼
┌──────────────────┐       ┌──────────────────┐
│ StockTransaction │       │AssetMaintenance  │
└──────────────────┘       └──────────────────┘

┌──────────────────┐
│  ArchiveItem     │
│  (Dokumen)       │
└──────────────────┘

┌──────────────────┐
│ SystemSetting    │
│  (Konfigurasi)   │
└──────────────────┘
```

### Penjelasan Entitas Kunci

#### 1. **Role & User** (Autentikasi & Otorisasi)

- Multi-role: Admin, Manager, Resepsionis, Kepala Kloter, Humas, Staf Operasional
- Password di-hash dengan bcrypt
- Session/JWT untuk stateless API

#### 2. **SystemSetting** (Konfigurasi Global)

- `active_mode`: Nilai `HAJJ_SEASON` atau `PUBLIC_SERVICE`
- `site_name`, `site_description`, `currency`
- Timezone, format tanggal, notasi nominal

#### 3. **Location** (Hierarki Fisik)

- Recursive self-join untuk Gedung > Lantai > Ruangan > Lemari > Rak
- Path helper untuk UI breadcrumb: "Gedung A > Lantai 2 > Ruangan 201"

#### 4. **Facility** (Aset yang Disewakan)

- Atribut `genderPolicy`: `MALE_ONLY`, `FEMALE_ONLY`, `MIXED` (penting untuk plotting haji)
- `capacity`: Jumlah orang/tempat tidur maksimal
- Galeri foto via `MediaLink`

#### 5. **FacilityService** (Paket Layanan)

- Contoh: "Kamar Standard Harian", "Sewa Aula 8 Jam", "VIP Package"
- Harga individual atau per orang
- Flag `isExclusive`: Jika TRUE, satu booking menempati seluruh kapasitas

#### 6. **Kloter & Guest** (Masalah Haji)

- `Kloter`: Kelompok terbang (JKT-01, SUB-05)
- `Guest`: Data jemaah dengan atribut gender (krusial untuk plotting)

#### 7. **Occupancy** (Inti: Siapa di Mana Kapan)

- Dapat merujuk ke individual `Guest` ATAU ke `Kloter` (bukan keduanya)
- Support check-in/out time tracking
- `isExclusive`: Apakah booking exclusive atau berbagi dengan lainnya

#### 8. **Reservation & ReservationItem** (Pesanan Komersial)

- `Reservation`: Header pesanan dengan status (DRAFT, PENDING, CONFIRMED, CANCELLED)
- `ReservationItem`: Detail (fasilitas + paket + qty)
- `bookingCode`: Kode unik otomatis (misal: BK-20260330-00001)

#### 9. **Invoice & Payment** (Finansial)

- Auto-generate invoice dari reservation
- Multiple payment entries (untuk cicilan bulan-bulan)

#### 10. **MediaFile & MediaLink** (Polymorphic File System)

- Satu file bisa dilampirkan ke beberapa entitas (Fasilitas, Post, AssetItem, dll)
- Support soft-delete untuk archived files

#### 11. **Item & StockTransaction** (Logistik)

- Tracking keluar-masuk barang habis pakai (sabun, sprei, makanan)
- Useful untuk costing & procurement planning

#### 12. **AssetItem & AssetMaintenance** (Perawatan)

- Tracking inventaris aset tetap (AC, genset, kasur)
- Riwayat service & biaya perawatan

#### 13. **ActivityLog** (Audit Trail)

- Setiap action user: CREATE, UPDATE, DELETE
- Timestamps dan user identification untuk compliance

---

## Workflow Operasional

### I. Mode HAJJ_SEASON (Musim Haji)

```
FASE 1: PERSIAPAN DATA
─────────────────────
1. Admin membuka SystemSetting, set `active_mode` = HAJJ_SEASON
2. Admin/Manager membuat struktur Location: Gedung → Lantai → Ruangan
3. Admin input Master Facility (Kamar) untuk setiap ruangan
   (Optional: set genderPolicy, kapasitas, dll)
4. Admin input Master FacilityService (jika ada paket harga)

FASE 2: DATA JEMAAH
──────────────────
5. Admin membuat master Kloter (code, province, target total jemaah)
6. Admin/Koordinator Kloter import data Guest (dari file Excel/CSV)
   - Setiap guest memiliki: name, gender, identity, kloterId
   - Validasi: gender harus M/F, no identitas unik
7. System mencatat dalam ActivityLog

FASE 3: PLOTTING KAMAR MASSAL (Manual atau Semi-Automatis)
───────────────────────────────────────────────────────────
8. Admin buka Dashboard "Plotting Kloter"
   - Filter by Kloter, Gender
   - Tampilkan: Daftar guest belum plotting, Daftar kamar kosong
9. Admin assign guest to facility secara batch (drag-drop atau form)
   - System validasi: kapasitas terisi, gender policy sesuai
   - System create Occupancy records untuk setiap guest
10. Alternative: Auto-assign (FIFO atau algoritma optimasi)

FASE 4: CHECK-IN MASSAL
───────────────────────
11. Saat Kloter tiba: Resepsionis scan ID atau batching confirmation
12. System auto-update Occupancy status = CHECKED_IN
13. Generate & print room key cards, welcome package
14. Notify room attendants (housekeeping)

FASE 5: MANAJEMEN OPERASIONAL
──────────────────────────────
15. Distribusi barang habis pakai (sabun, sprei):
    - Staf gudang entry ke StockTransaction (type = OUT)
    - Ledger otomatis update
16. Monitoring occupancy & fasilitas real-time
17. Handle urgent requests: pindah kamar, maintenance call

FASE 6: CHECK-OUT MASSAL
────────────────────────
18. Manager set checkout datetime untuk kloter
19. Resepsionis proses checkout batch:
    - Update Occupancy status = CHECKED_OUT
    - CheckOut time tercatat
    - Assess denda/damage charges (jika ada)
20. Kamar di-flag untuk cleaning/maintenance
21. Final finansial settlement (jika ada biaya tambahan)
22. Generate laporan occupancy & inventory consumption
```

### II. Mode PUBLIC_SERVICE (Pelayanan Umum)

```
FASE 1: BROWSING & INVENTORY
────────────────────────────
1. Public mengakses Landing Page
2. Browse Facility listings & harga dari FacilityService
3. Filter: Tanggal, tipe fasilitas, harga range
4. View galeri foto (dari MediaLink)

FASE 2: BOOKING ONLINE
──────────────────────
5. Guest membuat akun atau login
6. Pilih fasilitas & paket layanan
7. Input tanggal check-in/out, jumlah orang/unit
8. System validasi availabilitas real-time
9. Review harga & total
10. Create Reservation (status = PENDING)
11. System generate `bookingCode` unik

FASE 3: PEMBAYARAN (Payment Gateway)
─────────────────────────────────────
12. System auto-generate Invoice dari Reservation
13. Guest melihat detail pembayaran
14. Guest chose payment method (CASH, TRANSFER, QRIS)
15. Payment processed (sync ke payment gateway)
16. System record Payment entry
17. If PAID: Reservation status = CONFIRMED, Invoice status = PAID

FASE 4: CHECK-IN
────────────────
18. Guest datang pada hari yang dijadwalkan
19. Resepsionis verifikasi booking code & identitas
20. Create Occupancy record (Check-in time)
21. Issue room key/access code
22. Handle special requests

FASE 5: LAYANAN & STAY
──────────────────────
23. Guest menikmati fasilitas
24. Handle in-room service requests
25. Log any damages/losses untuk billing

FASE 6: CHECK-OUT
──────────────────
26. Guest mengajukan check-out (atau otomatis pada jadwal)
27. Resepsionis proses check-out:
    - Update Occupancy status = CHECKED_OUT
    - Check room conditions, assess damages
    - Process additional charges (if any)
    - Update Invoice (add damages, etc.)
    - Update Payment status
28. Return keys, issue receipt

FASE 7: FOLLOW-UP
──────────────────
29. Send satisfaction survey & feedback form
30. Proses data untuk analytics
```

### III. Sub-Workflow: Manajemen Aset & Logistik

```
ASET TETAP (PREVENTIVE MAINTENANCE)
────────────────────────────────────
1. Staf buat AssetItem (AC Kamar 101, Genset Gedung, etc)
2. Link to Location (untuk tracking lokasi aset)
3. Maintenance schedule (setiap 3 bulan, 6 bulan, tahunan)
4. Staf record AssetMaintenance:
   - Tanggal service, deskripsi pekerjaan, biaya
   - Next scheduled maintenance date
5. Dashboard "Asset Health" menampilkan maintenance overdue

BARANG HABIS PAKAI (INVENTORY)
───────────────────────────────
1. Create master Item (Sabun, Sprei, Tisu, Makanan, dll)
2. Set initial stock
3. Setiap pemakaian:
   - Housekeeping atau Staf Logistik entry StockTransaction (type = OUT)
   - qty & item diulangi per transaksi
4. Procurement triggered otomatis saat stock < threshold
5. Receipt penerimaan barang (type = IN) dicatat
6. Dashboard "Inventory Status" menampilkan stok & alert

DOKUMEN & ARSIP
────────────────
1. Buat ArchiveItem untuk setiap dokumen/folder
2. Record lokasi penyimpanan (Lemari A, Rak 3)
3. Link foto/dokumen digital via MediaLink (optional)
4. Retention policy (kapan arsip bisa disikat)
```

### IV. Sub-Workflow: Manajemen Konten & CMS

```
PEMBUATAN BERITA
─────────────────
1. Humas/Author login, buka module "Manajemen Berita"
2. Create Post baru dengan status = DRAFT
3. Input: title, content, slug (auto-generate atau manual)
4. Upload thumbnail/gallerie image → system create MediaLink
5. Preview mode
6. Set publish date/time (bisa jadwal publishing)
7. Change status = PUBLISHED
8. Post tampil di Landing Page

PENGELOLAAN EXISTING POSTS
────────────────────────────
9. List semua posts dengan filter (status, date range, author)
10. Edit, Delete, Unpublish posts
11. Change publish date
12. View engagement metrics (views, shares)

LANDING PAGE CONTENT
─────────────────────
13. Admin buka "Landing Page Settings"
14. Edit Hero section text & image (dari SystemSetting)
15. Drag-drop untuk reorder section
16. Edit "About Us", "Features", "Contact" section
17. All changes refresh page immediately
```

---

## Spesifikasi Teknis

### 1. Tech Stack

| Layer            | Technology               | Notes                                     |
| ---------------- | ------------------------ | ----------------------------------------- |
| **Frontend**     | Next.js 16+ (App Router) | Server Components & Client Components     |
|                  | React 19+                | Hooks, Context API                        |
|                  | Tailwind CSS 4           | Utility-first CSS                         |
|                  | TypeScript               | Type-safe                                 |
| **Backend**      | Next.js API Routes       | Serverless functions                      |
|                  | Prisma 7+                | ORM untuk database operations             |
|                  | JWT / NextAuth.js        | Authentication                            |
| **Database**     | MySQL 8+                 | Relational DB                             |
|                  | Database                 | Hosted atau Self-managed                  |
| **File Storage** | Local Filesystem / NAS   | Atau cloud (AWS S3, Google Cloud Storage) |
| **Utilities**    | bcryptjs                 | Password hashing                          |
|                  | date-fns                 | Date manipulation                         |
|                  | uuid                     | ID generation                             |
|                  | zod atau joi             | Input validation                          |

### 2. Database Versioning & Migrations

```bash
# Workflow Prisma
npx prisma migrate dev --name "deskripsi_perubahan"
# → Create migration file di prisma/migrations/
# → Apply migration ke dev database
# → Run prisma generate (update Prisma Client)

# Untuk production:
npx prisma migrate deploy
# → Apply pending migrations
```

### 3. Folder Structure (Next.js App Router)

```
my-app/
├── app/
│   ├── (public)/                    # Public pages (Landing, Auth)
│   │   ├── page.tsx                 # Landing page (Hero, Berita)
│   │   ├── layout.tsx
│   │   ├── booking/
│   │   │   ├── page.tsx             # Booking form (PUBLIC_SERVICE)
│   │   │   └── confirmation/page.tsx
│   │   └── auth/
│   │       ├── login/page.tsx
│   │       ├── register/page.tsx    # For guests (optional)
│   │       └── actions.ts           # Auth server actions
│   │
│   ├── (admin)/                     # Protected admin panel
│   │   ├── layout.tsx               # Navbar, sidebar
│   │   ├── dashboard/page.tsx       # Dashboard main
│   │   │
│   │   ├── setup/                   # Initial configuration
│   │   │   ├── system-config/
│   │   │   │   ├── page.tsx
│   │   │   │   └── actions.ts
│   │   │   ├── roles/
│   │   │   ├── users/
│   │   │   └── locations/
│   │   │
│   │   ├── facilities/              # Fasilitas management
│   │   │   ├── page.tsx             # List
│   │   │   ├── [id]/page.tsx        # Detail/Edit
│   │   │   └── actions.ts
│   │   │
│   │   ├── services/                # FacilityService management
│   │   │   ├── page.tsx
│   │   │   ├── [id]/page.tsx
│   │   │   └── actions.ts
│   │   │
│   │   ├── reservations/            # Reservasi (PUBLIC_SERVICE mode)
│   │   │   ├── page.tsx
│   │   │   ├── [id]/page.tsx
│   │   │   └── actions.ts
│   │   │
│   │   ├── occupancy/               # Check-in/out
│   │   │   ├── page.tsx
│   │   │   ├── check-in/page.tsx
│   │   │   ├── check-out/page.tsx
│   │   │   └── actions.ts
│   │   │
│   │   ├── hajj-season/             # Hajj-specific modules
│   │   │   ├── kloter/
│   │   │   │   ├── page.tsx         # Kloter list & CRUD
│   │   │   │   ├── [id]/page.tsx
│   │   │   │   └── actions.ts
│   │   │   │
│   │   │   ├── plotting/
│   │   │   │   ├── page.tsx         # Plotting dashboard
│   │   │   │   ├── auto-assign.ts   # Algorithm
│   │   │   │   └── actions.ts
│   │   │   │
│   │   │   └── bulk-check-in/page.tsx
│   │   │
│   │   ├── financial/               # Invoice & Payment
│   │   │   ├── invoices/page.tsx
│   │   │   ├── payments/page.tsx
│   │   │   └── actions.ts
│   │   │
│   │   ├── inventory/               # Item & StockTransaction
│   │   │   ├── items/page.tsx
│   │   │   ├── stock-in/page.tsx
│   │   │   ├── stock-out/page.tsx
│   │   │   └── actions.ts
│   │   │
│   │   ├── assets/                  # Asset & Maintenance
│   │   │   ├── page.tsx
│   │   │   ├── maintenance/page.tsx
│   │   │   └── actions.ts
│   │   │
│   │   ├── media/                   # Media management
│   │   │   ├── page.tsx
│   │   │   ├── upload/page.tsx
│   │   │   └── actions.ts
│   │   │
│   │   ├── posts/                   # CMS
│   │   │   ├── page.tsx
│   │   │   ├── [id]/page.tsx
│   │   │   └── actions.ts
│   │   │
│   │   ├── reports/                 # Analytics & reports
│   │   │   ├── occupancy-report/page.tsx
│   │   │   ├── revenue-report/page.tsx
│   │   │   ├── inventory-report/page.tsx
│   │   │   └── audit-log/page.tsx
│   │   │
│   │   └── profile/page.tsx         # User profile & settings
│   │
│   ├── api/                         # API Routes
│   │   ├── auth/
│   │   │   ├── login/route.ts
│   │   │   ├── logout/route.ts
│   │   │   └── refresh/route.ts
│   │   │
│   │   ├── facilities/
│   │   │   ├── route.ts             # GET/POST
│   │   │   └── [id]/route.ts        # GET/PUT/DELETE
│   │   │
│   │   ├── services/
│   │   │   ├── route.ts
│   │   │   └── [id]/route.ts
│   │   │
│   │   ├── reservations/
│   │   │   ├── route.ts
│   │   │   ├── [id]/route.ts
│   │   │   └── [id]/confirm/route.ts
│   │   │
│   │   ├── occupancy/
│   │   │   ├── check-in/route.ts
│   │   │   └── check-out/route.ts
│   │   │
│   │   ├── media/
│   │   │   ├── upload/route.ts
│   │   │   └── [id]/route.ts
│   │   │
│   │   ├── kloter/
│   │   │   ├── route.ts
│   │   │   ├── [id]/route.ts
│   │   │   └── [id]/plot/route.ts
│   │   │
│   │   └── system/
│   │       └── settings/route.ts
│   │
│   ├── globals.css
│   ├── layout.tsx                   # Root layout
│   └── not-found.tsx
│
├── components/
│   ├── ui/                          # Reusable UI components
│   │   ├── Button.tsx
│   │   ├── Card.tsx
│   │   ├── Modal.tsx
│   │   ├── Table.tsx
│   │   ├── Form.tsx
│   │   ├── Input.tsx
│   │   ├── Select.tsx
│   │   ├── Checkbox.tsx
│   │   ├── Toast.tsx
│   │   ├── Pagination.tsx
│   │   ├── Badge.tsx
│   │   └── ...
│   │
│   ├── admin/                       # Admin-specific components
│   │   ├── Navbar.tsx
│   │   ├── Sidebar.tsx
│   │   ├── Breadcrumb.tsx
│   │   ├── DashboardCard.tsx
│   │   ├── DataTable.tsx
│   │   └── ...
│   │
│   ├── public/                      # Public-facing components
│   │   ├── Hero.tsx                 # Already created
│   │   ├── Navbar.tsx               # Public navbar
│   │   ├── Footer.tsx
│   │   ├── BookingWidget.tsx
│   │   ├── FacilityCard.tsx
│   │   └── ...
│   │
│   └── forms/                       # Form components
│       ├── ReservationForm.tsx
│       ├── FacilityForm.tsx
│       ├── LoginForm.tsx
│       └── ...
│
├── lib/
│   ├── prisma.ts                    # Prisma client singleton
│   ├── auth.ts                      # Auth utilities (JWT, password hash)
│   ├── validations.ts               # Zod schemas
│   ├── api-client.ts                # Fetch wrapper
│   └── helpers.ts                   # Utility functions
│
├── styles/
│   ├── globals.css                  # Global styles
│   ├── theme.ts                     # Tailwind config
│   └── variables.css
│
├── public/
│   ├── images/
│   │   ├── dark.webp                # Hero background
│   │   ├── light.webp
│   │   └── ...
│   ├── icons/
│   └── ...
│
├── prisma/
│   ├── schema.prisma                # Database schema
│   ├── migrations/                  # Migration history
│   │   ├── migration_lock.toml
│   │   ├── 20260329062716_init/
│   │   └── ...
│   └── seed.ts                      # Seeding script
│
├── .env.local                       # Environment variables
├── .env.example
├── package.json
├── tsconfig.json
├── next.config.ts
├── tailwind.config.ts
├── postcss.config.mjs
└── README.md
```

### 4. Authentication & Authorization

```typescript
// Auth Flow
1. User login dengan email + password
2. Server validate & hash password
3. Generate JWT token (exp: 7 days) + refresh token (exp: 30 days)
4. Store refresh token di database atau HTTP-only cookie
5. Return JWT to client (local storage atau cookie)

// RBAC (Role-Based Access Control)
- Middleware check JWT on every request
- Extract user.roleId
- Check against route permission mapping
- Example:
  Route /admin/users → requires Role.name ∈ ["Admin", "Manager"]
  Route /admin/hajj → requires Role.name ∈ ["Admin", "HajjManager"]
```

### 5. API Response Format

```typescript
// Standar Response
{
  success: boolean,
  data: T | null,
  message: string,
  errors?: { [key: string]: string[] },
  pagination?: {
    page: number,
    pageSize: number,
    total: number,
    totalPages: number
  }
}
```

---

## Panduan Integrasi API

### Endpoint Mapping

#### Auth API

```
POST   /api/auth/login              # Login
POST   /api/auth/logout             # Logout
POST   /api/auth/refresh            # Refresh token
GET    /api/auth/me                 # Get current user
```

#### Facility Management

```
GET    /api/facilities              # List semua fasilitas (+ filter)
GET    /api/facilities?status=AVAILABLE
POST   /api/facilities              # Create fasilitas
GET    /api/facilities/[id]         # Get detail
PUT    /api/facilities/[id]         # Update
DELETE /api/facilities/[id]         # Delete
```

#### Service Management

```
GET    /api/services
GET    /api/services/facility/[facilityId]
POST   /api/services
PUT    /api/services/[id]
DELETE /api/services/[id]
```

#### Reservation System

```
GET    /api/reservations            # List reservasi
POST   /api/reservations            # Create booking
GET    /api/reservations/[id]
PUT    /api/reservations/[id]        # Update status
GET    /api/reservations/[id]/availability  # Check availability
```

#### Occupancy Management

```
POST   /api/occupancy/check-in      # Check-in
POST   /api/occupancy/check-out     # Check-out
GET    /api/occupancy               # List occupancy
GET    /api/occupancy/facility/[facilityId]  # Get occupancy by facility
```

#### Hajj Operations

```
GET    /api/kloter
POST   /api/kloter
GET    /api/kloter/[id]/guests      # Get jemaah in kloter
POST   /api/kloter/[id]/plot        # Auto-assign guests to rooms
GET    /api/kloter/[id]/occupancy   # Get occupancy report
```

#### Media Management

```
POST   /api/media/upload            # Upload file
GET    /api/media/[id]              # Download file
DELETE /api/media/[id]              # Delete media
```

#### CMS (Posts)

```
GET    /api/posts                   # List posts (public & admin)
POST   /api/posts                   # Create post
GET    /api/posts/[id]
PUT    /api/posts/[id]
DELETE /api/posts/[id]
```

#### System Settings

```
GET    /api/system/settings         # Get all settings
PUT    /api/system/settings/[key]   # Update setting
```

---

## Best Practices & Standards

### 1. Data Validation

- Gunakan **Zod** atau **Joi** untuk schema validation
- Validasi di server-side (jangan andalkan client-side saja)
- Return error details untuk debugging

### 2. Security

- Hash passwords dengan **bcryptjs** (salt rounds: 10-12)
- Gunakan **HTTPS** di production
- Implement rate limiting pada login endpoint
- Sanitize input untuk prevent SQL injection (Prisma already handles this)
- XSS prevention dengan NextJS built-in escaping

### 3. Performance

- Implement pagination untuk large datasets (default 20 items per page)
- Cache frequent queries (Redis optional)
- Index database columns untuk frequently filtered fields
- Lazy load components & images
- Optimize images untuk web

### 4. Error Handling

- Try-catch global untuk semua API routes
- Distinctive error codes (400, 401, 403, 404, 500, etc)
- Descriptive error messages
- Log errors ke file/monitoring service

### 5. Testing

- Unit tests untuk utilities & helpers
- Integration tests untuk API endpoints
- E2E tests untuk critical workflows (booking, check-in)

---

## Kesimpulan

Sistem Informasi Asrama Haji Kelas I Banjarmasin adalah platform terintegrasi yang mengelola dual-mode operasional: pengelolaan massal jemaah saat musim haji dan layanan komersial umum. Dengan arsitektur modular, data model yang komprehensif, dan workflow yang jelas, sistem ini siap untuk scales dan adaptable terhadap kebutuhan bisnis yang berkembang.

---

**Versi**: 1.0  
**Terakhir diperbarui**: 30 Maret 2026  
**Status**: Draft - Siap untuk development
