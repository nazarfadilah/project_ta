# Alur Operasional Sistem Peminjaman Asrama Haji

## Sistem Informasi Peminjaman Ruangan Asrama Haji Kelas I Banjarmasin

> **Document Type**: Workflow & Process Flow  
> **Date**: 17 April 2026  
> **Based on**: OPSI A (Implicit Sarana Model - Asrama Haji)

---

## Daftar Isi

1. [Alur Proses Bisnis](#alur-proses-bisnis)
2. [User Flow (Guest)](#user-flow-guest)
3. [Admin Flow (Resepsionis/Booking)](#admin-flow-resepsionist)
4. [Check-in & Check-out Flow](#check-in--check-out-flow)
5. [Invoice & Payment Flow](#invoice--payment-flow)
6. [User-Guest Auto-Sync Flow](#user-guest-auto-sync-flow)
7. [Damage & Additional Cost Flow](#damage--additional-cost-flow)

---

## Alur Proses Bisnis

### FLOW UMUM SISTEM

```
┌─────────────────────────────────────────────────────────────┐
│ GUEST BOOKING                                               │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│ 1. LANDING PAGE BROWSE                                       │
│    - Guest lihat list gedung & ruangan                       │
│    - Lihat harga per satuan (jam/hari)                       │
│    - Lihat deskripsi sarana yang included                    │
│                                                              │
│ 2. PILIH RUANGAN + DURASI                                    │
│    - Pilih tanggal mulai & selesai                           │
│    - Pilih durasi (jam/hari/malam)                           │
│    - System check availability                               │
│    ❌ TIDAK ada pilihan sarana (implicit included)           │
│                                                              │
│ 3. FORM BOOKING                                              │
│    - Input identitas (NIK, nama, gender, phone)             │
│    - Input keperluan peminjaman                              │
│    - Review harga total                                      │
│                                                              │
│ 4. SUBMIT BOOKING                                            │
│    - Create Peminjaman Transaksi (status: RESERVASI)         │
│    - Auto-generate Invoice (status: UNPAID)                  │
│    - Get Kode Peminjaman (PJMN-xxx)                         │
│                                                              │
│ 5. CONFIRMATION                                             │
│    - Show di landing page: waiting confirmation              │
│    - Show payment details (bayar di tempat)                  │
│    - Show check-in time                                      │
│                                                              │
└─────────────────────────────────────────────────────────────┘

        ↓

┌─────────────────────────────────────────────────────────────┐
│ ADMIN VERIFICATION (Optional)                               │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│ Admin Resepsionis bisa:                                      │
│ - Review booking baru                                        │
│ - Confirm availability                                       │
│ - Add special notes                                          │
│ - Update status → CHECK_IN ready                             │
│                                                              │
└─────────────────────────────────────────────────────────────┘

        ↓

┌─────────────────────────────────────────────────────────────┐
│ CHECK-IN                                                    │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│ 1. Guest datang ke asrama                                   │
│ 2. Resepsionis verify Kode Peminjaman                       │
│ 3. Resepsionis inspect kondisi ruangan & sarana             │
│ 4. Update Peminjaman:                                        │
│    - Status: CHECK_IN                                        │
│    - tglMulaiRil: hari/jam actual checkin                   │
│ 5. E-ticket / bukti check-in cetak                          │
│ 6. Guest masuk ruangan                                       │
│                                                              │
└─────────────────────────────────────────────────────────────┘

        ↓

┌─────────────────────────────────────────────────────────────┐
│ PENGGUNAAN & STAY                                            │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│ Guest using ruangan + sarana (proyektor, mic, kursi, dll)   │
│                                                              │
└─────────────────────────────────────────────────────────────┘

        ↓

┌─────────────────────────────────────────────────────────────┐
│ CHECK-OUT                                                   │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│ 1. Guest keluar, resepsionis inspect kondisi                │
│ 2. Catat kondisi return:                                     │
│    - Baik (OK)                                               │
│    - Rusak Ringan (minor damage)                             │
│    - Rusak Berat (major damage)                              │
│    - Hilang (missing items)                                  │
│ 3. Record damageNotes (detail kerusakan)                    │
│ 4. Hitung estimasiDamage (jika ada)                         │
│ 5. Update Peminjaman:                                        │
│    - Status: CHECK_OUT                                       │
│    - tglSelesaiRil: actual checkout                         │
│    - kondisiReturn: (BAIK/RUSAK/HILANG)                     │
│    - catatanKerusakan: detail                                │
│    - estimasiDamage: biaya kalkulasi                         │
│ 6. Update Invoice jika ada tambahan biaya                    │
│                                                              │
└─────────────────────────────────────────────────────────────┘

        ↓

┌─────────────────────────────────────────────────────────────┐
│ PAYMENT (BAYAR DI TEMPAT)                                    │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│ 1. Kasir hitung total (sewa + damage)                        │
│ 2. Guest bayar (cash/transfer sesuai metode)                 │
│ 3. Record pembayaran:                                        │
│    - metode: TUNAI/TRANSFER/CEKDAN                          │
│    - jumlah                                                  │
│    - referensi (jika transfer)                               │
│ 4. Update Invoice status → PAID/PARTIAL                      │
│ 5. Print receipt                                             │
│ 6. Close peminjaman (status: SELESAI)                        │
│                                                              │
└─────────────────────────────────────────────────────────────┘
```

---

## User Flow (Guest)

### SKENARIO 1: Tamu DENGAN Account (Login)

```
┌────────────────────────────────────────────────────────────────────┐
│ STEP 1: REGISTRATION (First Time)                                 │
├────────────────────────────────────────────────────────────────────┤
│                                                                     │
│ Tamu buka landing page → "Daftar Akun"                            │
│                                                                     │
│ Form Registration:                                                 │
│ ┌─────────────────────────────────┐                                │
│ │ Email: rudi@mail.com            │                                │
│ │ Password: ***                    │                                │
│ │ Confirm Password: ***            │                                │
│ │ NIK: 3171021234567890 ← KUNCI   │                                │
│ │ Nama: Rudi Hartono              │                                │
│ │ Gender: Laki-laki              │                                │
│ │ Agree T&C: [x]                  │                                │
│ │ [DAFTAR]                         │                                │
│ └─────────────────────────────────┘                                │
│                                                                     │
│ Backend Processing:                                                │
│ 1. Hash password dengan bcryptjs                                   │
│ 2. Create User record:                                             │
│    - email: "rudi@mail.com"                                        │
│    - username: auto-generate or from email                         │
│    - roleId: "tamu"                                                │
│    - password: hashed                                              │
│                                                                     │
│ 3. ❌ AUTO-SYNC TRIGGER:                                          │
│    IF NIK "3171021234567890" exist di Guest:                      │
│       - FOUND existing Guest                                       │
│       - UPDATE User.guestId = existing_guest.id                    │
│       - UPDATE Guest.email = "rudi@mail.com"                       │
│       - ✅ AUTO-LINK SELESAI                                       │
│       - Riwayat peminjaman lama muncul di Dashboard               │
│    ELSE:                                                            │
│       - Create new Guest record dengan NIK tadi                    │
│       - Link ke User baru                                          │
│                                                                     │
│ 4. Send verification email (optional)                             │
│ 5. Redirect → Login                                                │
│                                                                     │
└────────────────────────────────────────────────────────────────────┘

↓

┌────────────────────────────────────────────────────────────────────┐
│ STEP 2: LOGIN & BOOKING                                            │
├────────────────────────────────────────────────────────────────────┤
│                                                                     │
│ Tamu login dengan email + password                                 │
│ → Dashboard Tamu                                                   │
│    - List booking history (dari auto-sync tadi!)                   │
│    - Button "Pesan Ruangan Baru"                                   │
│                                                                     │
│ Click "Pesan Ruangan Baru":                                        │
│ 1. Browse gedung & ruangan                                         │
│ 2. Pilih ruangan + tanggal/jam                                     │
│ 3. Review harga (sarana included sudah tertera)                    │
│ 4. Submit booking                                                  │
│    → Auto-generate Kode Peminjaman (PJMN-xxx)                     │
│    → Auto-generate Invoice                                         │
│    → Status: RESERVASI, Invoice: UNPAID                            │
│                                                                     │
│ 5. Dashboard show:                                                 │
│    - Kode Peminjaman                                               │
│    - Check-in time & date                                          │
│    - Total biaya                                                   │
│    - Payment status                                                │
│    - Button: "Lihat Detail" / "Cetak Bukti"                        │
│                                                                     │
└────────────────────────────────────────────────────────────────────┘
```

### SKENARIO 2: Tamu TANPA Account (Input Manual Admin)

```
┌────────────────────────────────────────────────────────────────────┐
│ STEP 1: BOOKING VIA WA/TELEPON (Admin Input)                       │
├────────────────────────────────────────────────────────────────────┤
│                                                                     │
│ Calon tamu hubungi admin via WA:                                   │
│ "Halo, saya mau pesan Aula Utama untuk acara nikahan              │
│  dari tanggal 20-21 April, sekira 200 orang"                       │
│                                                                     │
│ Admin action:                                                      │
│ 1. Login ke admin panel                                            │
│ 2. Click "Tambah Peminjaman Baru"                                  │
│ 3. Input form:                                                     │
│    - Cek availability ruangan                                      │
│    - Input NIK: 3171021234567890                                   │
│    - Input Nama: Rudi Hartono                                      │
│    - Input Gender: Laki-laki                                       │
│    - Input Phone: 081234567890                                     │
│    - Input Ruangan: Aula Utama                                     │
│    - Input Tanggal: 20-21 April                                    │
│    - Input Keperluan: Acara Nikahan                                │
│    - Input Harga: auto-calculate dari ruangan + durasi             │
│    - [SIMPAN]                                                      │
│                                                                     │
│ Backend Processing:                                                │
│ 1. Backend check: NIK "3171021234567890" exist?                    │
│    IF EXIST:                                                       │
│      - Use existing Guest                                          │
│      - Update phone+name jika berbeda                              │
│    ELSE:                                                           │
│      - Create new Guest dengan NIK tadi                            │
│                                                                     │
│ 2. Create Peminjaman Transaksi:                                    │
│    - guestId: [dari step 1]                                        │
│    - userId: NULL (no user yet)                                    │
│    - ruanganId: [aula utama]                                       │
│    - statusPeminjaman: RESERVASI                                   │
│    - kodePeminjaman: PJMN-20260420-001                             │
│                                                                     │
│ 3. Auto-generate Invoice                                          │
│ 4. Show confirmation ke admin                                      │
│ 5. Kasir print invoice untuk delivery ke tamu (via WA/email)      │
│                                                                     │
│ Invoice sample:                                                    │
│ ───────────────────────────────────────                            │
│ Kode Peminjaman: PJMN-20260420-001                                 │
│ Nama Tamu: Rudi Hartono                                            │
│ Ruangan: Aula Utama                                                │
│ Tanggal: 20-21 April 2026                                          │
│ Harga: Rp 1,000,000                                                │
│ Pembayaran: Bayar saat check-in                                    │
│ ───────────────────────────────────────                            │
│                                                                     │
└────────────────────────────────────────────────────────────────────┘

↓

┌────────────────────────────────────────────────────────────────────┐
│ STEP 2: LATER - TAMU BUAT AKUN (AUTO-SYNC)                         │
├────────────────────────────────────────────────────────────────────┤
│                                                                     │
│ Kemudian (kapan saja), Rudi buka landing page → "Daftar Akun"     │
│                                                                     │
│ Form Registration:                                                 │
│ - Email: rudi@mail.com                                             │
│ - Password: ***                                                    │
│ - NIK: 3171021234567890 ← SAMA!                                    │
│ - Nama: Rudi Hartono                                               │
│ - [DAFTAR]                                                         │
│                                                                     │
│ Backend AUTO-SYNC Trigger:                                         │
│ 1. Hash password                                                   │
│ 2. Create User baru dengan email tadi                              │
│ 3. CHECK: NIK exist di Guest?                                      │
│    ✅ YES! Found Guest dari tadi (admin input)                    │
│    - UPDATE User.guestId = guest_id                                │
│    - UPDATE Guest.email = rudi@mail.com                            │
│    - ✅ LINK SUCCESS!                                              │
│                                                                     │
│ 4. Rudi login → Dashboard                                          │
│    ✅ Riwayat peminjaman NIKAH kemarin langsung muncul!           │
│    ✅ Status: SELESAI (dengan Invoice history)                    │
│                                                                     │
│ KESIMPULAN:                                                        │
│ Rudi sekarang bisa:                                                │
│ ✅ Login ke akun                                                   │
│ ✅ Lihat history peminjaman                                        │
│ ✅ Lihat invoice + bukti pembayaran                                │
│ ✅ Buat peminjaman baru                                            │
│                                                                     │
└────────────────────────────────────────────────────────────────────┘
```

---

## Admin Flow (Resepsionis)

### Manajemen Peminjaman

```
┌────────────────────────────────────────────────────────────────────┐
│ ADMIN DASHBOARD - Manajemen Peminjaman                             │
├────────────────────────────────────────────────────────────────────┤
│                                                                     │
│ Main Menu:                                                         │
│ ├─ Peminjaman Hari Ini                                             │
│ │  └─ List check-in yang harus dilakukan                           │
│ │                                                                   │
│ ├─ Semua Peminjaman                                                │
│ │  └─ Filter by status (RESERVASI/CHECK_IN/CHECK_OUT/SELESAI)     │
│ │  └─ Filter by ruangan, tanggal, guest                            │
│ │                                                                   │
│ ├─ Tambah Peminjaman Baru                                          │
│ ├─ Check-in                                                        │
│ ├─ Check-out (termasuk damage assessment)                          │
│ └─ Invoice & Pembayaran                                            │
│                                                                     │
└────────────────────────────────────────────────────────────────────┘
```

---

## Check-in & Check-out Flow

### CHECK-IN PROCESS

```
┌────────────────────────────────────────────────────────────────────┐
│ CHECK-IN (Saat Guest Datang)                                       │
├────────────────────────────────────────────────────────────────────┤
│                                                                     │
│ Resepsionis:                                                       │
│ 1. Guest masuk → Minta Kode Peminjaman                             │
│    Atau: Cek nama di daftar                                        │
│                                                                     │
│ 2. Verify di sistem:                                               │
│    - Cari Peminjaman dgn kodePeminjaman atau nama+tanggal          │
│    - Check status: harus RESERVASI                                 │
│    - Check tanggal: hari ini sesuai?                               │
│    - Confirm total biaya                                           │
│                                                                     │
│ 3. Visual Inspection (Kondisi Awal):                               │
│    - Cek ruangan: bersih? furniture intact? listrik OK?           │
│    - Cek sarana: proyektor hidup? kursi lengkap?                  │
│    - Catat di adminNote jika ada yang maintenance/rusak           │
│                                                                     │
│ 4. Update Peminjaman:                                              │
│    button [CHECK-IN]:                                              │
│    - Status: RESERVASI → CHECK_IN                                  │
│    - tglMulaiRil: sekarang (timestamp)                             │
│    - [SIMPAN]                                                      │
│                                                                     │
│ 5. Print & Cetak:                                                  │
│    - E-Ticket / Bukti Check-in                                     │
│    - Berisi: Kode Peminjaman, ruangan, jam, nama                   │
│                                                                     │
│ 6. Serah kunci + welcome                                           │
│                                                                     │
└────────────────────────────────────────────────────────────────────┘
```

### CHECK-OUT PROCESS (DAMAGE ASSESSMENT)

```
┌────────────────────────────────────────────────────────────────────┐
│ CHECK-OUT (Saat Guest Pergi)                                       │
├────────────────────────────────────────────────────────────────────┤
│                                                                     │
│ Resepsionis:                                                       │
│ 1. Guest checkout → collect kunci                                  │
│                                                                     │
│ 2. Inspect Kondisi Akhir (CRITICAL):                               │
│    Cek semua area ruangan & sarana:                                │
│                                                                     │
│    ✓ Ruangan:                                                      │
│      - Lantai (bersih/kotor/rusak?)                                │
│      - Dinding (ada goresan/noda?)                                 │
│      - Furniture (meja, kursi OK?)                                 │
│      - AC (berfungsi?)                                             │
│      - Listrik (all OK?)                                           │
│      - Pintu/Jendela (intact?)                                     │
│                                                                     │
│    ✓ Sarana:                                                       │
│      - Proyektor (tidak rusak?)                                    │
│      - Mic/Speaker (OK?)                                           │
│      - Kursi (count OK, tidak rusak?)                              │
│      - Meja (scratch, intact?)                                     │
│      - Equipment lain (missing?)                                   │
│                                                                     │
│ 3. Assess Kondisi Return:                                          │
│    ┌─────────────────────────────────┐                             │
│    │ Kondisi Return:                 │                             │
│    │ ○ BAIK                          │ (tidak ada masalah)        │
│    │ ○ RUSAK_RINGAN                  │ (minor, bisa diperbaiki)   │
│    │ ○ RUSAK_BERAT                   │ (major damage)             │
│    │ ○ HILANG                        │ (ada barang hilang)        │
│    │                                 │                             │
│    │ Catatan Kerusakan (TEXT):       │                             │
│    │ ┌─────────────────────────────┐ │                             │
│    │ │ Proyektor rusak (layar     │ │                             │
│    │ │ bergeser), Kursi 3 pcs     │ │                             │
│    │ │ tergores, AC bocor        │ │                             │
│    │ │ (minor water drop)         │ │                             │
│    │ └─────────────────────────────┘ │                             │
│    │                                 │                             │
│    │ Estimasi Biaya Damage:          │                             │
│    │ [Auto-calc OR manual input]     │                             │
│    │ Rp 500,000                      │                             │
│    └─────────────────────────────────┘                             │
│                                                                     │
│ 4. Update Peminjaman (button [CHECK-OUT]):                         │
│    - Status: CHECK_IN → CHECK_OUT                                  │
│    - tglSelesaiRil: now (timestamp)                                │
│    - kondisiReturn: [pilihan]                                      │
│    - catatanKerusakan: [text]                                      │
│    - estimasiDamage: [value]                                       │
│    - [SIMPAN]                                                      │
│                                                                     │
│ 5. System AUTO-UPDATE Invoice:                                     │
│    - biayaTambahan: += estimasiDamage                              │
│    - totalHarga = subtotal + biayaTambahan                         │
│    - statusInvoice: UNPAID (still waiting payment)                 │
│                                                                     │
│ 6. Print Checkout Invoice                                          │
│                                                                     │
└────────────────────────────────────────────────────────────────────┘
```

---

## Invoice & Payment Flow

### INVOICE GENERATION & PAYMENT

```
┌────────────────────────────────────────────────────────────────────┐
│ PAYMENT PROCESS (BAYAR DI TEMPAT)                                  │
├────────────────────────────────────────────────────────────────────┤
│                                                                     │
│ Saat CHECK-OUT atau saat closing:                                  │
│                                                                     │
│ Kasir Action:                                                      │
│ 1. Buka Invoice                                                    │
│    ┌─────────────────────────────────────┐                         │
│    │ INV-20260420-001                    │                         │
│    │ Peminjaman: Aula Utama              │                         │
│    │ Tanggal: 20-21 April 2026           │                         │
│    │ Durasi: 2 hari                      │                         │
│    │                                     │                         │
│    │ Subtotal:        Rp 1,000,000       │                         │
│    │ Biaya Tambahan:  Rp   500,000 (dmg)│                         │
│    │ ─────────────────────────────       │                         │
│    │ TOTAL:           Rp 1,500,000       │                         │
│    │ Status: UNPAID                      │                         │
│    └─────────────────────────────────────┘                         │
│                                                                     │
│ 2. Guest bayar:                                                    │
│    Kasir: "Total Rp 1,500,000. Mau bayar berapa?"                 │
│    Guest: "Saya bayar Rp 1,500,000 (nanti proses transfer"        │
│           untuk sisa Rp 500,000)"                                  │
│                                                                     │
│ 3. Record Pembayaran (PARTIAL):                                    │
│    Kasir input di sistem:                                          │
│    ┌─────────────────────────────────────┐                         │
│    │ [BAYAR] button                      │                         │
│    │                                     │                         │
│    │ Invoice: INV-20260420-001           │                         │
│    │ Jumlah Bayar: Rp 1,000,000          │                         │
│    │ Metode: [dropdown]                  │                         │
│    │  ○ TUNAI (pilih)                    │                         │
│    │  ○ TRANSFER                         │                         │
│    │  ○ CEKDAN                           │                         │
│    │                                     │                         │
│    │ Referensi: -                        │                         │
│    │ Catatan: Partial, sisa transfer     │                         │
│    │ [SIMPAN PEMBAYARAN]                 │                         │
│    │                                     │                         │
│    │ Submitted by: Kasir Username        │                         │
│    │ Time: 21 Apr 2026 14:30             │                         │
│    └─────────────────────────────────────┘                         │
│                                                                     │
│ 4. System AUTO-UPDATE:                                             │
│    - Create Pembayaran record                                      │
│    - Invoice.statusInvoice: UNPAID → PARTIAL                       │
│    - Print Receipt                                                 │
│                                                                     │
│    Receipt sample:                                                 │
│    ───────────────────────────────────                             │
│    BUKTI PEMBAYARAN                                                │
│    ───────────────────────────────────                             │
│    Invoice: INV-20260420-001                                       │
│    Tamu: Rudi Hartono                                              │
│    Ruangan: Aula Utama                                             │
│    Jumlah Bayar: Rp 1,000,000 (TUNAI)                             │
│    Sisa: Rp 500,000 (TRANSFER nanti)                              │
│    Tanggal: 21 Apr 2026 14:30                                      │
│    Penerima: Kasir                                                 │
│    ───────────────────────────────────                             │
│                                                                     │
│ 5. Tunggu pembayaran sisa (transfer bank)                          │
│    Bukti transfer datang → Kasir input lagi                        │
│    ┌─────────────────────────────────────┐                         │
│    │ [BAYAR] button (pembayaran ke-2)     │                         │
│    │ Invoice: INV-20260420-001           │                         │
│    │ Jumlah Bayar: Rp 500,000            │                         │
│    │ Metode: TRANSFER                    │                         │
│    │ Referensi: BRIxxx-20260421-123456   │                         │
│    │ Catatan: Transfer BRI (sisa)        │                         │
│    │ [SIMPAN PEMBAYARAN]                 │                         │
│    └─────────────────────────────────────┘                         │
│                                                                     │
│ 6. System AUTO-UPDATE:                                             │
│    - Create Pembayaran record ke-2                                 │
│    - Calculate: total_paid = 1,000,000 + 500,000 = 1,500,000      │
│    - IF total_paid == invoice.total:                               │
│      Invoice.statusInvoice: PARTIAL → PAID                         │
│      Peminjaman.statusPeminjaman: CHECK_OUT → SELESAI              │
│    - Print Final Receipt (LUNAS)                                   │
│                                                                     │
│ 7. Close & Archive:                                                │
│    - Peminjaman done                                               │
│    - Invoice archived                                              │
│    - Activity logged                                               │
│                                                                     │
└────────────────────────────────────────────────────────────────────┘
```

---

## User-Guest Auto-Sync Flow

### CRITICAL AUTO-SYNC TRIGGER (Saat Registration)

```
┌────────────────────────────────────────────────────────────────────┐
│ BACKEND TRIGGER: Guest↔User Auto-Sync (saat User register)         │
├────────────────────────────────────────────────────────────────────┤
│                                                                     │
│ Event: User buat akun baru                                         │
│                                                                     │
│ Input dari form:                                                   │
│ - email: "rudi@mail.com"                                           │
│ - username: (auto or from email)                                   │
│ - password: "MySecure123!"                                         │
│ - nik: "3171021234567890"  ← CRITICAL                              │
│ - name: "Rudi Hartono"                                             │
│ - gender: "MALE"                                                   │
│                                                                     │
│ Backend Processing:                                                │
│                                                                     │
│ STEP 1: Hash password                                              │
│ ┌───────────────────────────────────────────────────────────────┐  │
│ │ hashedPassword = bcrypt("MySecure123!", 10)                   │  │
│ │ → "$2b$10$abcdefghijklmnopqrstuvwxyz..."                      │  │
│ └───────────────────────────────────────────────────────────────┘  │
│                                                                     │
│ STEP 2: Check if NIK exists in Guest table                         │
│ ┌───────────────────────────────────────────────────────────────┐  │
│ │ SELECT * FROM guest WHERE nik = "3171021234567890"           │  │
│ │                                                               │  │
│ │ Result: IF FOUND                                              │  │
│ │ ┌─────────────────────────────────────────────────────────┐  │  │
│ │ │ id: "guest-uuid-123"                                    │  │  │
│ │ │ nik: "3171021234567890"                                 │  │  │
│ │ │ name: "Rudi Hartono"                                    │  │  │
│ │ │ gender: "MALE"                                          │  │  │
│ │ │ address: NULL                                           │  │  │
│ │ │ bloodType: NULL                                         │  │  │
│ │ │ notes: "Acara Nikahan 20-21 April" (dari admin input)  │  │  │
│ │ └─────────────────────────────────────────────────────────┘  │  │
│ │                                                               │  │
│ │ Action: FOUND → Auto-link!                                   │  │
│ └───────────────────────────────────────────────────────────────┘  │
│                                                                     │
│ STEP 3: Create User + Link Guest                                   │
│ ┌───────────────────────────────────────────────────────────────┐  │
│ │ INSERT INTO users (                                           │  │
│ │   id: "user-uuid-456",                                        │  │
│ │   username: "rudi_hartono",                                   │  │
│ │   email: "rudi@mail.com",                                     │  │
│ │   password: "$2b$10$...",                                     │  │
│ │   roleId: (role.id where name = "Tamu"),                      │  │
│ │   phone: NULL,                                                 │  │
│ │   guestId: "guest-uuid-123", ← LINK!                         │  │
│ │   status: "ACTIVE",                                           │  │
│ │   lastLoginAt: NULL,                                          │  │
│ │   createdAt: now()                                            │  │
│ │ )                                                              │  │
│ │                                                               │  │
│ │ ALSO UPDATE Guest:                                            │  │
│ │ UPDATE guest                                                  │  │
│ │ SET email = "rudi@mail.com"  ← Update email if was NULL      │  │
│ │ WHERE id = "guest-uuid-123"                                   │  │
│ │                                                               │  │
│ │ ✅ AUTO-SYNC COMPLETE!                                        │  │
│ └───────────────────────────────────────────────────────────────┘  │
│                                                                     │
│ STEP 4: Session & Redirect                                         │
│ ┌───────────────────────────────────────────────────────────────┐  │
│ │ Create session/token                                          │  │
│ │ Redirect to: /dashboard/tamu                                  │  │
│ │                                                               │  │
│ │ Dashboard load:                                               │  │
│ │ ✅ Booking history MUNCUL (dari guest lama)                  │  │
│ │    - Acara Nikahan 20-21 April (SELESAI)                     │  │
│ │    - Invoice + bukti pembayaran                              │  │
│ │ ✅ Ready untuk booking baru                                  │  │
│ └───────────────────────────────────────────────────────────────┘  │
│                                                                     │
│                                                                     │
│ CASE 2: NIK NOT FOUND (Tamu baru, tidak pernah booking)           │
│ ┌───────────────────────────────────────────────────────────────┐  │
│ │ SELECT * FROM guest WHERE nik = "3171021234567890"           │  │
│ │ Result: NOT FOUND                                             │  │
│ │                                                               │  │
│ │ Action: Create new Guest                                      │  │
│ │ ┌─────────────────────────────────────────────────────────┐  │  │
│ │ │ INSERT INTO guest (                                     │  │  │
│ │ │   id: "guest-uuid-new",                                 │  │  │
│ │ │   nik: "3171021234567890",                              │  │  │
│ │ │   name: "Rudi Hartono",                                 │  │  │
│ │ │   gender: "MALE",                                       │  │  │
│ │ │   email: "rudi@mail.com",                               │  │  │
│ │ │   address: NULL,                                        │  │  │
│ │ │   bloodType: NULL,                                      │  │  │
│ │ │   notes: NULL                                           │  │  │
│ │ │ )                                                        │  │  │
│ │ └─────────────────────────────────────────────────────────┘  │  │
│ │                                                               │  │
│ │ Then create User + link:                                      │  │
│ │ (same as STEP 3 di atas, tapi guestId = guest_uuid_new)     │  │
│ │                                                               │  │
│ │ ✅ SYNC COMPLETE - Fresh Start                               │  │
│ └───────────────────────────────────────────────────────────────┘  │
│                                                                     │
└────────────────────────────────────────────────────────────────────┘
```

---

## Damage & Additional Cost Flow

### DAMAGE ASSESSMENT & COST CALC

```
┌────────────────────────────────────────────────────────────────────┐
│ DAMAGE SCENARIO EXAMPLES                                           │
├────────────────────────────────────────────────────────────────────┤
│                                                                     │
│ CASE A: Check-out, Kondisi BAIK (No Damage)                       │
│ ├─ Resepsionis inspect → "Ruangan bagus, semua intact"            │
│ ├─ Action: Select "BAIK", no notes needed                         │
│ ├─ System: estimasiDamage = Rp 0, Invoice NOT updated             │
│ ├─ Payment: Bayar sesuai harga original                            │
│ └─ Result: SELESAI                                                │
│                                                                     │
│ CASE B: Rusak Ringan (Minor Damage)                               │
│ ├─ Ex: Kursi 3 tergores, noda di karpet                           │
│ ├─ Resepsionis:                                                    │
│ │  - Select "RUSAK_RINGAN"                                        │
│ │  - Notes: "3 kursi tergores, bisa diperbaiki; noda di karpet"   │
│ │  - Estimate: Rp 200,000 (untuk cleaning + minor repair)         │
│ ├─ System:                                                         │
│ │  - estimasiDamage = Rp 200,000                                  │
│ │  - Invoice.biayaTambahan += 200,000                              │
│ │  - Invoice.totalHarga = Rp 1,000,000 + Rp 200,000 = Rp 1.2M    │
│ ├─ Guest: Bayar total Rp 1,200,000 (sewa + damage)               │
│ └─ Result: Invoice PAID → SELESAI                                │
│                                                                     │
│ CASE C: Rusak Berat (Major Damage)                                │
│ ├─ Ex: Proyektor layar rusak, AC bocor besar, meja patah          │
│ ├─ Resepsionis:                                                    │
│ │  - Select "RUSAK_BERAT"                                         │
│ │  - Notes: "Proyektor layar retak, AC bocor, meja leg patah"     │
│ │  - Estimate: Rp 2,500,000 (replacement cost)                    │
│ ├─ System:                                                         │
│ │  - estimasiDamage = Rp 2,500,000                                │
│ │  - Invoice.biayaTambahan += 2,500,000                            │
│ │  - Invoice.totalHarga = Rp 1,000,000 + Rp 2,500,000 = Rp 3.5M  │
│ ├─ Negotiation: Guest siap bayar total?                           │
│ │  - Jika YES → bayar Rp 3,500,000, SELESAI                      │
│ │  - Jika NO → talk with manager untuk possible discount          │
│ │              atau guest bisa claim insurance                     │
│ └─ Result: Flexible negotiation                                   │
│                                                                     │
│ CASE D: Barang HILANG (Missing Items)                             │
│ ├─ Ex: Gelas 10 pc hilang, towel 5 hilang                         │
│ ├─ Resepsionis:                                                    │
│ │  - Select "HILANG"                                              │
│ │  - Notes: "10 gelas hilang, 5 towel hilang"                     │
│ │  - Estimate: Rp 300,000 (replacement)                           │
│ ├─ System:                                                         │
│ │  - estimasiDamage = Rp 300,000                                  │
│ │  - Invoice.biayaTambahan += 300,000                              │
│ │  - Invoice.totalHarga = Rp 1,000,000 + Rp 300,000 = Rp 1.3M    │
│ ├─ Guest: Bayar total                                             │
│ └─ Result: SELESAI, asset cost absorbed                           │
│                                                                     │
├─────────────────────────────────────────────────────────────────────┤
│ IMPORTANT: estimasiDamage adalah OPTIONAL                          │
│ - Jika tidak ada kerusakan → estimasiDamage = NULL / Rp 0          │
│ - Update Invoice hanya jika ada damage cost                        │
└────────────────────────────────────────────────────────────────────┘
```

---

## Database Interactions Summary

### Tabel yang Berinteraksi di Setiap Proses

```
BOOKING:
  users (who book?) 
  → guest (tamu detail)
  → peminjaman_transaksi (booking record)
  → ruangan (what room?)
  → gedung (in which building?)
  → invoice (bill auto-generate)

CHECK-IN:
  peminjaman_transaksi (status: RESERVASI → CHECK_IN)
  → activity_log (audit)

CHECK-OUT:
  peminjaman_transaksi (status: CHECK_IN → CHECK_OUT, damage notes)
  → invoice (update biayaTambahan)
  → activity_log (audit)

PAYMENT:
  invoice (status: UNPAID/PARTIAL → PAID)
  → pembayaran (record payment)
  → users (kasir)
  → peminjaman_transaksi (status: CHECK_OUT → SELESAI)
  → activity_log (audit)

AUTO-SYNC:
  users (new registration)
  → guest (check exist by NIK)
  → link or create
  → activity_log (audit)
```

---

**Document Status**: Complete  
**Model Implemented**: OPSI A (Implicit Sarana) - Asrama Haji  
**Next Step**: Implementasi di Laravel Code
