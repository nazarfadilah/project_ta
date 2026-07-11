import os
import math
from PIL import Image, ImageDraw, ImageFont

# Define the sequence diagrams data
# Group A: "Kelola Data" CRUD diagrams
kelola_templates = {
    "3_kelola_pengguna": {
        "title": "3. Kelola Pengguna (User Management)",
        "description": "Pengelolaan data pengguna oleh Aktor (tambah, lihat, ubah, hapus).",
        "actor": "Aktor",
        "menu": "Pengguna",
        "submenu": "Manajemen",
        "halaman": "Halaman Manajemen Pengguna",
        "controller": "User Controller",
        "model": "User Model",
        "data_singular": "pengguna",
        "data_plural": "pengguna",
        "roles": ["Aktor"]
    },
    "4_kelola_landing_page": {
        "title": "4. Kelola Landing Page",
        "description": "Pengelolaan slider galeri, Frequently Asked Questions (FAQ), dan landing page oleh Aktor.",
        "actor": "Aktor",
        "menu": "Landing Page",
        "submenu": "Manajemen",
        "halaman": "Halaman Manajemen Landing Page",
        "controller": "Landing Page Controller",
        "model": "Landing Page Model",
        "data_singular": "landing page",
        "data_plural": "landing page",
        "roles": ["Aktor"]
    },
    "5_kelola_tamu": {
        "title": "5. Kelola Tamu (Guest Management)",
        "description": "Pengelolaan data profil tamu oleh Aktor.",
        "actor": "Aktor",
        "menu": "Tamu",
        "submenu": "Manajemen",
        "halaman": "Halaman Manajemen Tamu",
        "controller": "Tamu Controller",
        "model": "Tamu Model",
        "data_singular": "tamu",
        "data_plural": "tamu",
        "roles": ["Aktor"]
    },
    "6_kelola_ulasan": {
        "title": "6. Kelola Ulasan Ruangan",
        "description": "Pengelolaan ulasan dan testimoni tentang ruangan oleh Aktor.",
        "actor": "Aktor",
        "menu": "Ulasan",
        "submenu": "Manajemen",
        "halaman": "Halaman Manajemen Ulasan",
        "controller": "Ulasan Controller",
        "model": "Ulasan Model",
        "data_singular": "ulasan",
        "data_plural": "ulasan",
        "roles": ["Aktor"]
    },
    "7_kelola_ruangan": {
        "title": "7. Kelola Ruangan",
        "description": "Pengelolaan master data ruangan oleh Aktor (kapasitas, fasilitas, tarif).",
        "actor": "Aktor",
        "menu": "Ruangan",
        "submenu": "Manajemen",
        "halaman": "Halaman Manajemen Ruangan",
        "controller": "Ruangan Controller",
        "model": "Ruangan Model",
        "data_singular": "ruangan",
        "data_plural": "ruangan",
        "roles": ["Aktor"]
    },
    "8_kelola_sarana": {
        "title": "8. Kelola Sarana & Prasarana",
        "description": "Pengelolaan data sarana pendukung sewa oleh Aktor.",
        "actor": "Aktor",
        "menu": "Sarana",
        "submenu": "Manajemen",
        "halaman": "Halaman Manajemen Sarana",
        "controller": "Sarana Controller",
        "model": "Sarana Model",
        "data_singular": "sarana",
        "data_plural": "sarana",
        "roles": ["Aktor"]
    },
    "9_kelola_paket": {
        "title": "9. Kelola Paket Ruangan",
        "description": "Pengelolaan paket bundling ruangan dan sarana beserta tarif oleh Aktor.",
        "actor": "Aktor",
        "menu": "Paket",
        "submenu": "Manajemen",
        "halaman": "Halaman Manajemen Paket",
        "controller": "Paket Controller",
        "model": "Paket Model",
        "data_singular": "paket",
        "data_plural": "paket",
        "roles": ["Aktor"]
    },
    "18_kelola_berita": {
        "title": "18. Kelola Berita",
        "description": "Pengelolaan data berita (tulis draf pengumuman/berita) oleh Aktor.",
        "actor": "Aktor",
        "menu": "Berita",
        "submenu": "Manajemen",
        "halaman": "Halaman Manajemen Berita",
        "controller": "Berita Controller",
        "model": "Berita Model",
        "data_singular": "berita",
        "data_plural": "berita",
        "roles": ["Aktor"]
    }
}

diagrams = {}

# Generate Group A CRUD diagrams dynamically based on the kelola templates
for key, tmpl in kelola_templates.items():
    diagrams[key] = {
        "title": tmpl["title"],
        "description": tmpl["description"],
        "roles": tmpl["roles"],
        "participants": [
            {"name": tmpl["actor"], "type": "actor"},
            {"name": "Halaman Utama", "type": "boundary"},
            {"name": tmpl["controller"], "type": "control"},
            {"name": tmpl["model"], "type": "entity"},
            {"name": tmpl["halaman"], "type": "boundary"}
        ],
        "steps": [
            ("msg", tmpl["actor"], "Halaman Utama", f"1: Membuka menu {tmpl['menu']}()", "call"),
            ("self", "Halaman Utama", f"1.1: Menampilkan submenu {tmpl['submenu']}()"),
            ("msg", tmpl["actor"], "Halaman Utama", f"2: Memilih submenu {tmpl['submenu']}()", "call"),
            ("msg", "Halaman Utama", tmpl["controller"], f"2.1: Request halaman {tmpl['submenu']}()", "call"),
            ("msg", tmpl["controller"], tmpl["model"], f"2.1.1: Query data {tmpl['data_plural']}()", "call"),
            ("msg", tmpl["model"], tmpl["controller"], "2.1.2: Return query()", "reply"),
            ("msg", tmpl["controller"], tmpl["halaman"], f"2.1.3: Menampilkan halaman {tmpl['halaman']}()", "reply"),
            ("msg", tmpl["actor"], tmpl["halaman"], f"3: Memilih menu Tambah {tmpl['menu']}()", "call"),
            ("self", tmpl["halaman"], f"3.1: Menampilkan form tambah {tmpl['data_singular']}()"),
            ("msg", tmpl["actor"], tmpl["halaman"], "4: Menekan tombol Simpan()", "call"),
            ("self", tmpl["halaman"], "4.1: Menampilkan form input"),
            ("alt_start", "Input data benar"),
            ("msg", tmpl["actor"], tmpl["halaman"], f"5: Menginputkan data {tmpl['data_singular']} dan menekan tombol Kirim()", "call"),
            ("msg", tmpl["halaman"], tmpl["controller"], f"5.1: Request tambah {tmpl['data_singular']}()", "call"),
            ("msg", tmpl["controller"], tmpl["model"], f"5.1.1: Query tambah {tmpl['data_singular']}()", "call"),
            ("msg", tmpl["model"], tmpl["controller"], "5.1.2: Return query()", "reply"),
            ("msg", tmpl["controller"], tmpl["halaman"], "5.1.3: Menampilkan notifikasi berhasil()", "reply"),
            ("alt_else", "Input data salah atau kosong"),
            ("msg", tmpl["actor"], tmpl["halaman"], "6: Menekan tombol Kirim()", "call"),
            ("msg", tmpl["halaman"], tmpl["controller"], f"6.1: Request tambah {tmpl['data_singular']}()", "call"),
            ("msg", tmpl["controller"], tmpl["halaman"], "6.1.1: Menampilkan notifikasi kesalahan()", "reply"),
            ("alt_end",)
        ]
    }

# Group B: Custom flow diagrams
diagrams["1_login_sistem"] = {
    "title": "1. Login Sistem",
    "description": "Alur masuk (login) dan keluar (logout) sistem untuk seluruh pengguna.",
    "roles": ["Aktor"],
    "participants": [
        {"name": "Aktor", "type": "actor"},
        {"name": "Halaman Login", "type": "boundary"},
        {"name": "Auth Controller", "type": "control"},
        {"name": "User Model", "type": "entity"}
    ],
    "steps": [
        ("msg", "Aktor", "Halaman Login", "1: Input email & password, klik Login", "call"),
        ("msg", "Halaman Login", "Auth Controller", "1.1: Kirim data login (POST)", "call"),
        ("msg", "Auth Controller", "User Model", "1.1.1: Cari user berdasarkan email", "call"),
        ("msg", "User Model", "Auth Controller", "1.1.2: Kembalikan data user & password hash", "reply"),
        ("self", "Auth Controller", "1.1.3: Verifikasi password & session"),
        ("alt_start", "Password Valid"),
        ("msg", "Auth Controller", "Halaman Login", "1.1.4: Redirect ke Dashboard", "reply"),
        ("alt_else", "Password Salah"),
        ("msg", "Auth Controller", "Halaman Login", "1.1.5: Tampilkan notifikasi error", "reply"),
        ("alt_end",),
        ("msg", "Aktor", "Halaman Login", "2: Klik Logout", "call"),
        ("msg", "Halaman Login", "Auth Controller", "2.1: Request logout (POST)", "call"),
        ("msg", "Auth Controller", "Halaman Login", "2.2: Destroy session, redirect ke Login", "reply")
    ]
}

diagrams["2_registrasi_akun"] = {
    "title": "2. Registrasi Akun",
    "description": "Alur registrasi akun baru oleh Aktor beserta pengiriman kode verifikasi ke email.",
    "roles": ["Aktor"],
    "participants": [
        {"name": "Aktor", "type": "actor"},
        {"name": "Halaman Registrasi", "type": "boundary"},
        {"name": "Auth Controller", "type": "control"},
        {"name": "User Model", "type": "entity"},
        {"name": "Halaman Verifikasi", "type": "boundary"}
    ],
    "steps": [
        ("msg", "Aktor", "Halaman Registrasi", "1: Isi form registrasi & klik Register", "call"),
        ("msg", "Halaman Registrasi", "Auth Controller", "1.1: POST /register", "call"),
        ("self", "Auth Controller", "1.1.1: Validasi data input"),
        ("alt_start", "Validasi Sukses"),
        ("msg", "Auth Controller", "User Model", "1.1.2: Simpan user baru (status=INACTIVE)", "call"),
        ("self", "Auth Controller", "1.1.3: Kirim kode verifikasi ke email"),
        ("msg", "Auth Controller", "Halaman Verifikasi", "1.1.4: Redirect ke Halaman Verifikasi Email", "reply"),
        ("msg", "Aktor", "Halaman Verifikasi", "2: Input kode OTP & klik Verifikasi", "call"),
        ("msg", "Halaman Verifikasi", "Auth Controller", "2.1: POST /verify", "call"),
        ("self", "Auth Controller", "2.1.1: Validasi OTP"),
        ("alt_start", "OTP Benar"),
        ("msg", "Auth Controller", "User Model", "2.1.2: Update status user=ACTIVE", "call"),
        ("msg", "Auth Controller", "Halaman Verifikasi", "2.1.3: Redirect ke Login dengan sukses", "reply"),
        ("alt_else", "OTP Salah"),
        ("msg", "Auth Controller", "Halaman Verifikasi", "2.1.4: Tampilkan error OTP tidak valid", "reply"),
        ("alt_end",),
        ("alt_else", "Validasi Gagal"),
        ("msg", "Auth Controller", "Halaman Registrasi", "1.1.5: Tampilkan error validasi", "reply"),
        ("alt_end",)
    ]
}

diagrams["10_kelola_profil"] = {
    "title": "10. Kelola Profil",
    "description": "Pembaruan informasi data diri dan password oleh Aktor.",
    "roles": ["Aktor"],
    "participants": [
        {"name": "Aktor", "type": "actor"},
        {"name": "Halaman Profil", "type": "boundary"},
        {"name": "Profil Controller", "type": "control"},
        {"name": "User Model", "type": "entity"}
    ],
    "steps": [
        ("msg", "Aktor", "Halaman Profil", "1: Membuka Halaman Profil Saya", "call"),
        ("msg", "Halaman Profil", "Profil Controller", "1.1: GET /users/profil", "call"),
        ("msg", "Profil Controller", "User Model", "1.1.1: Query data user login", "call"),
        ("msg", "User Model", "Profil Controller", "1.1.2: Return data user", "reply"),
        ("msg", "Profil Controller", "Halaman Profil", "1.1.3: Tampilkan Halaman Profil dengan form edit", "reply"),
        ("msg", "Aktor", "Halaman Profil", "2: Mengubah data profil & Klik Simpan", "call"),
        ("msg", "Halaman Profil", "Profil Controller", "2.1: PUT /users/profil", "call"),
        ("self", "Profil Controller", "2.1.1: Validasi data diri"),
        ("alt_start", "Validasi Berhasil"),
        ("msg", "Profil Controller", "User Model", "2.1.2: Update data user & password", "call"),
        ("msg", "User Model", "Profil Controller", "2.1.3: Success", "reply"),
        ("msg", "Profil Controller", "Halaman Profil", "2.1.4: Tampilkan pesan sukses update", "reply"),
        ("alt_else", "Validasi Gagal"),
        ("msg", "Profil Controller", "Halaman Profil", "2.1.5: Tampilkan pesan error validasi", "reply"),
        ("alt_end",)
    ]
}

diagrams["11_kelola_invoice"] = {
    "title": "11. Kelola & Cetak Invoice",
    "description": "Pengelolaan data invoice transaksi peminjaman oleh Aktor (update status lunas/unpaid).",
    "roles": ["Aktor"],
    "participants": [
        {"name": "Aktor", "type": "actor"},
        {"name": "Halaman Detail Invoice", "type": "boundary"},
        {"name": "Invoice Controller", "type": "control"},
        {"name": "Invoice Model", "type": "entity"}
    ],
    "steps": [
        ("msg", "Aktor", "Halaman Detail Invoice", "1: Buka halaman detail invoice", "call"),
        ("msg", "Halaman Detail Invoice", "Invoice Controller", "1.1: GET /admin/transaksi/invoice/{id}", "call"),
        ("msg", "Invoice Controller", "Invoice Model", "1.1.1: Get invoice & peminjaman details", "call"),
        ("msg", "Invoice Model", "Invoice Controller", "1.1.2: Return data", "reply"),
        ("msg", "Invoice Controller", "Halaman Detail Invoice", "1.1.3: Tampilkan detail invoice", "reply"),
        ("msg", "Aktor", "Halaman Detail Invoice", "2: Konfirmasi Pembayaran Lunas", "call"),
        ("msg", "Halaman Detail Invoice", "Invoice Controller", "2.1: POST /admin/transaksi/invoice/{id}/pay", "call"),
        ("self", "Invoice Controller", "2.1.1: Validasi status pembayaran"),
        ("alt_start", "Valid"),
        ("msg", "Invoice Controller", "Invoice Model", "2.1.2: Update statusInvoice=PAID, status_pembayaran=LUNAS", "call"),
        ("msg", "Invoice Model", "Invoice Controller", "2.1.3: Success", "reply"),
        ("msg", "Invoice Controller", "Halaman Detail Invoice", "2.1.4: Tampilkan status PAID & Cetak Kuitansi", "reply"),
        ("alt_else", "Tidak Valid"),
        ("msg", "Invoice Controller", "Halaman Detail Invoice", "2.1.5: Tampilkan pesan kesalahan", "reply"),
        ("alt_end",)
    ]
}

diagrams["12_checkin"] = {
    "title": "12. Proses Check-In Peminjaman",
    "description": "Proses check-in peminjam pada hari H penggunaan fasilitas oleh Aktor.",
    "roles": ["Aktor"],
    "participants": [
        {"name": "Aktor", "type": "actor"},
        {"name": "Halaman Transaksi", "type": "boundary"},
        {"name": "Peminjaman Controller", "type": "control"},
        {"name": "Peminjaman Model", "type": "entity"}
    ],
    "steps": [
        ("msg", "Aktor", "Halaman Transaksi", "1: Klik tombol Proses Check-In", "call"),
        ("msg", "Halaman Transaksi", "Peminjaman Controller", "1.1: POST /admin/transaksi/peminjaman/{id}/check-in", "call"),
        ("self", "Peminjaman Controller", "1.1.1: Validasi tanggal hari H"),
        ("alt_start", "Valid"),
        ("msg", "Peminjaman Controller", "Peminjaman Model", "1.1.2: Update statusPeminjaman=CHECK_IN, checkIn=now()", "call"),
        ("msg", "Peminjaman Model", "Peminjaman Controller", "1.1.3: Success", "reply"),
        ("msg", "Peminjaman Controller", "Halaman Transaksi", "1.1.4: Tampilkan status CHECK_IN", "reply"),
        ("alt_else", "Tidak Valid"),
        ("msg", "Peminjaman Controller", "Halaman Transaksi", "1.1.5: Tampilkan peringatan belum hari H", "reply"),
        ("alt_end",)
    ]
}

diagrams["13_checkout"] = {
    "title": "13. Proses Check-Out Peminjaman",
    "description": "Proses check-out peminjam setelah pemakaian fasilitas berakhir oleh Aktor.",
    "roles": ["Aktor"],
    "participants": [
        {"name": "Aktor", "type": "actor"},
        {"name": "Halaman Transaksi", "type": "boundary"},
        {"name": "Peminjaman Controller", "type": "control"},
        {"name": "Peminjaman Model", "type": "entity"}
    ],
    "steps": [
        ("msg", "Aktor", "Halaman Transaksi", "1: Klik Check-Out & isi form kerusakan", "call"),
        ("msg", "Halaman Transaksi", "Peminjaman Controller", "1.1: POST /admin/transaksi/peminjaman/{id}/check-out", "call"),
        ("msg", "Peminjaman Controller", "Peminjaman Model", "1.1.1: Update statusPeminjaman=SELESAI, checkOut=now()", "call"),
        ("msg", "Peminjaman Controller", "Peminjaman Model", "1.1.2: Simpan kondisiReturn, denda, & biayaTambahan", "call"),
        ("msg", "Peminjaman Model", "Peminjaman Controller", "1.1.3: Success", "reply"),
        ("msg", "Peminjaman Controller", "Halaman Transaksi", "1.1.4: Redirect & status menjadi SELESAI", "reply")
    ]
}

diagrams["14_verifikasi_peminjaman"] = {
    "title": "14. Verifikasi & Approval Peminjaman",
    "description": "Verifikasi dan persetujuan pengajuan reservasi masuk oleh Aktor.",
    "roles": ["Aktor"],
    "participants": [
        {"name": "Aktor", "type": "actor"},
        {"name": "Halaman Detail Peminjaman", "type": "boundary"},
        {"name": "Approval Controller", "type": "control"},
        {"name": "Peminjaman Model", "type": "entity"}
    ],
    "steps": [
        ("msg", "Aktor", "Halaman Detail Peminjaman", "1: Buka detail peminjaman pending", "call"),
        ("msg", "Halaman Detail Peminjaman", "Approval Controller", "1.1: GET /admin/transaksi/peminjaman/{id}", "call"),
        ("msg", "Approval Controller", "Peminjaman Model", "1.1.1: Get peminjaman & detail berkas", "call"),
        ("msg", "Peminjaman Model", "Approval Controller", "1.1.2: Return data", "reply"),
        ("msg", "Approval Controller", "Halaman Detail Peminjaman", "1.1.3: Tampilkan detail peminjaman", "reply"),
        ("alt_start", "Setujui"),
        ("msg", "Aktor", "Halaman Detail Peminjaman", "2: Klik tombol Setujui", "call"),
        ("msg", "Halaman Detail Peminjaman", "Approval Controller", "2.1: POST /admin/transaksi/peminjaman/{id}/approve", "call"),
        ("msg", "Approval Controller", "Peminjaman Model", "2.1.1: Update statusApproval=APPROVED & set tglApproval", "call"),
        ("msg", "Approval Controller", "Peminjaman Model", "2.1.2: Auto-generate Invoice record (status=UNPAID)", "call"),
        ("msg", "Peminjaman Model", "Approval Controller", "2.1.3: Success", "reply"),
        ("msg", "Approval Controller", "Halaman Detail Peminjaman", "2.1.4: Tampilkan status APPROVED", "reply"),
        ("alt_else", "Tolak"),
        ("msg", "Aktor", "Halaman Detail Peminjaman", "3: Klik tombol Tolak & input alasan", "call"),
        ("msg", "Halaman Detail Peminjaman", "Approval Controller", "3.1: POST /admin/transaksi/peminjaman/{id}/reject", "call"),
        ("msg", "Approval Controller", "Peminjaman Model", "3.1.1: Update statusApproval=REJECTED & simpan alasan", "call"),
        ("msg", "Peminjaman Model", "Approval Controller", "3.1.2: Success", "reply"),
        ("msg", "Approval Controller", "Halaman Detail Peminjaman", "3.1.3: Tampilkan status REJECTED", "reply"),
        ("alt_end",)
    ]
}

diagrams["15_ajukan_peminjaman"] = {
    "title": "15. Ajukan Peminjaman (Reservasi)",
    "description": "Pengajuan permohonan sewa ruangan dan sarana oleh Aktor.",
    "roles": ["Aktor"],
    "participants": [
        {"name": "Aktor", "type": "actor"},
        {"name": "Halaman Pengajuan", "type": "boundary"},
        {"name": "Peminjaman Controller", "type": "control"},
        {"name": "Peminjaman Model", "type": "entity"}
    ],
    "steps": [
        ("msg", "Aktor", "Halaman Pengajuan", "1: Isi Form Permohonan Peminjaman", "call"),
        ("msg", "Halaman Pengajuan", "Peminjaman Controller", "1.1: Kirim Permohonan Peminjaman (POST)", "call"),
        ("msg", "Peminjaman Controller", "Peminjaman Model", "1.1.1: Cek ketersediaan Ruangan & Sarana", "call"),
        ("msg", "Peminjaman Model", "Peminjaman Controller", "1.1.2: Return status ketersediaan", "reply"),
        ("alt_start", "Fasilitas Tersedia"),
        ("msg", "Peminjaman Controller", "Peminjaman Model", "1.1.3: Simpan data peminjaman (status=PENDING)", "call"),
        ("msg", "Peminjaman Model", "Peminjaman Controller", "1.1.4: Success", "reply"),
        ("msg", "Peminjaman Controller", "Halaman Pengajuan", "1.1.5: Tampilkan sukses (Menunggu Verifikasi)", "reply"),
        ("alt_else", "Fasilitas Terbooking"),
        ("msg", "Peminjaman Controller", "Halaman Pengajuan", "1.1.6: Tampilkan pesan error ketersediaan", "reply"),
        ("alt_end",)
    ]
}

diagrams["16_batalkan_peminjaman"] = {
    "title": "16. Batalkan Peminjaman",
    "description": "Proses pembatalan sewa fasilitas sebelum hari H oleh Aktor.",
    "roles": ["Aktor"],
    "participants": [
        {"name": "Aktor", "type": "actor"},
        {"name": "Halaman Detail Peminjaman", "type": "boundary"},
        {"name": "Peminjaman Controller", "type": "control"},
        {"name": "Peminjaman Model", "type": "entity"}
    ],
    "steps": [
        ("msg", "Aktor", "Halaman Detail Peminjaman", "1: Klik tombol Batalkan Peminjaman", "call"),
        ("self", "Halaman Detail Peminjaman", "1.1: Tampilkan pop-up konfirmasi pembatalan"),
        ("msg", "Aktor", "Halaman Detail Peminjaman", "2: Konfirmasi pembatalan (Klik Ya)", "call"),
        ("msg", "Halaman Detail Peminjaman", "Peminjaman Controller", "2.1: POST /users/main/reservasi/{id}/batal", "call"),
        ("msg", "Peminjaman Controller", "Peminjaman Model", "2.1.1: Update statusPeminjaman=BATAL & statusApproval=REJECTED", "call"),
        ("msg", "Peminjaman Model", "Peminjaman Controller", "2.1.2: Success", "reply"),
        ("msg", "Peminjaman Controller", "Halaman Detail Peminjaman", "2.1.3: Redirect ke daftar reservasi dengan pesan sukses", "reply")
    ]
}

diagrams["17_lihat_laporan"] = {
    "title": "17. Lihat Laporan Penggunaan",
    "description": "Penyaringan dan cetak laporan okupansi/transaksi sewa oleh Aktor.",
    "roles": ["Aktor"],
    "participants": [
        {"name": "Aktor", "type": "actor"},
        {"name": "Halaman Laporan", "type": "boundary"},
        {"name": "Laporan Controller", "type": "control"},
        {"name": "Peminjaman Model", "type": "entity"}
    ],
    "steps": [
        ("msg", "Aktor", "Halaman Laporan", "1: Buka Halaman Laporan & pilih filter tanggal", "call"),
        ("msg", "Halaman Laporan", "Laporan Controller", "1.1: GET /admin/laporan", "call"),
        ("msg", "Laporan Controller", "Peminjaman Model", "1.1.1: Query aggregated data", "call"),
        ("msg", "Peminjaman Model", "Laporan Controller", "1.1.2: Return data", "reply"),
        ("msg", "Laporan Controller", "Halaman Laporan", "1.1.3: Tampilkan Halaman Laporan dengan data & grafik", "reply"),
        ("msg", "Aktor", "Halaman Laporan", "2: Klik tombol Cetak PDF", "call"),
        ("msg", "Halaman Laporan", "Laporan Controller", "2.1: GET /admin/laporan/export-pdf", "call"),
        ("msg", "Laporan Controller", "Halaman Laporan", "2.2: Export & download file PDF Laporan", "reply")
    ]
}

diagrams["19_publish_berita"] = {
    "title": "19. Publish Berita",
    "description": "Proses mempublikasikan draf berita oleh Aktor ke laman publik.",
    "roles": ["Aktor"],
    "participants": [
        {"name": "Aktor", "type": "actor"},
        {"name": "Halaman Detail Berita", "type": "boundary"},
        {"name": "Berita Controller", "type": "control"},
        {"name": "Berita Model", "type": "entity"}
    ],
    "steps": [
        ("msg", "Aktor", "Halaman Detail Berita", "1: Buka detail berita status draft", "call"),
        ("msg", "Halaman Detail Berita", "Berita Controller", "1.1: GET /admin/berita/{id}", "call"),
        ("msg", "Berita Controller", "Berita Model", "1.1.1: Get detail data berita", "call"),
        ("msg", "Berita Model", "Berita Controller", "1.1.2: Return data", "reply"),
        ("msg", "Berita Controller", "Halaman Detail Berita", "1.1.3: Tampilkan detail berita & tombol Publish", "reply"),
        ("msg", "Aktor", "Halaman Detail Berita", "2: Klik tombol Publish Berita", "call"),
        ("msg", "Halaman Detail Berita", "Berita Controller", "2.1: POST /admin/berita/{id}/publish", "call"),
        ("msg", "Berita Controller", "Berita Model", "2.1.1: Update statusBerita=PUBLISHED", "call"),
        ("msg", "Berita Model", "Berita Controller", "2.1.2: Success", "reply"),
        ("msg", "Berita Controller", "Halaman Detail Berita", "2.1.3: Tampilkan pesan sukses & status PUBLISHED", "reply")
    ]
}

# Drawing helpers
def get_font():
    # Attempt to load standard clean fonts from Windows directory
    paths = [
        "C:\\Windows\\Fonts\\segoeui.ttf",
        "C:\\Windows\\Fonts\\arial.ttf",
        "segoeui.ttf",
        "arial.ttf"
    ]
    for path in paths:
        try:
            font_title = ImageFont.truetype(path, 16)
            font_bold = ImageFont.truetype(path, 12)
            font_regular = ImageFont.truetype(path, 11)
            return font_title, font_bold, font_regular
        except IOError:
            continue
    # Default fallback
    fallback = ImageFont.load_default()
    return fallback, fallback, fallback

font_title, font_bold, font_regular = get_font()

def draw_text_centered(draw, text, x, y, font, fill):
    bbox = draw.textbbox((0, 0), text, font=font)
    w = bbox[2] - bbox[0]
    h = bbox[3] - bbox[1]
    draw.text((x - w / 2, y - h / 2), text, font=font, fill=fill)

def wrap_text(text, font, max_width, draw):
    words = text.split(' ')
    lines = []
    current_line = []
    for word in words:
        test_line = ' '.join(current_line + [word])
        bbox = draw.textbbox((0, 0), test_line, font=font)
        w = bbox[2] - bbox[0]
        if w <= max_width:
            current_line.append(word)
        else:
            if current_line:
                lines.append(' '.join(current_line))
                current_line = [word]
            else:
                lines.append(word)
                current_line = []
    if current_line:
        lines.append(' '.join(current_line))
    return lines

def draw_message_text(draw, text, x1, x2, y, font, fill):
    max_w = abs(x2 - x1) - 20
    lines = wrap_text(text, font, max_w, draw)
    mid_x = (x1 + x2) / 2
    
    line_heights = []
    for line in lines:
        bbox = draw.textbbox((0, 0), line, font=font)
        line_heights.append(bbox[3] - bbox[1] + 3)
    total_text_h = sum(line_heights)
    
    curr_y = y - total_text_h - 4
    for line in lines:
        bbox = draw.textbbox((0, 0), line, font=font)
        w = bbox[2] - bbox[0]
        h = bbox[3] - bbox[1]
        draw.text((mid_x - w / 2, curr_y), line, font=font, fill=fill)
        curr_y += h + 3

def draw_self_call_text(draw, text, x, y, font, fill):
    max_w = 180
    lines = wrap_text(text, font, max_w, draw)
    curr_y = y + 5
    for line in lines:
        bbox = draw.textbbox((0, 0), line, font=font)
        h = bbox[3] - bbox[1]
        draw.text((x + 42, curr_y), line, font=font, fill=fill)
        curr_y += h + 3

def draw_dashed_line(draw, x1, y1, x2, y2, fill, width=2, dash_length=6, gap_length=4):
    dx = x2 - x1
    dy = y2 - y1
    distance = math.sqrt(dx**2 + dy**2)
    if distance == 0:
        return
    ux = dx / distance
    uy = dy / distance
    
    curr_dist = 0
    while curr_dist < distance:
        next_dist = min(curr_dist + dash_length, distance)
        draw.line([
            int(round(x1 + ux * curr_dist)),
            int(round(y1 + uy * curr_dist)),
            int(round(x1 + ux * next_dist)),
            int(round(y1 + uy * next_dist))
        ], fill=fill, width=int(width))
        curr_dist += dash_length + gap_length

def draw_uml_actor(draw, x, y, font, label):
    # Head
    draw.ellipse([x - 7, y - 25, x + 7, y - 11], fill=(122, 194, 240), outline=(30, 41, 59), width=2)
    # Body
    draw.line([x, y - 11, x, y + 8], fill=(30, 41, 59), width=2)
    # Arms
    draw.line([x - 14, y - 4, x + 14, y - 4], fill=(30, 41, 59), width=2)
    # Legs
    draw.line([x, y + 8, x - 10, y + 22], fill=(30, 41, 59), width=2)
    draw.line([x, y + 8, x + 10, y + 22], fill=(30, 41, 59), width=2)
    # Label
    draw_text_centered(draw, label, x, y + 36, font, (30, 41, 59))

def draw_uml_boundary(draw, x, y, font, label):
    # Circle on the right
    draw.ellipse([x - 2, y - 12, x + 22, y + 12], fill=(122, 194, 240), outline=(30, 41, 59), width=2)
    # Vertical line on the left
    draw.line([x - 15, y - 18, x - 15, y + 18], fill=(30, 41, 59), width=2)
    # Horizontal link
    draw.line([x - 15, y, x - 2, y], fill=(30, 41, 59), width=2)
    # Label
    draw_text_centered(draw, label, x, y + 36, font, (30, 41, 59))

def draw_uml_control(draw, x, y, font, label):
    # Main Circle
    draw.ellipse([x - 12, y - 12, x + 12, y + 12], fill=(122, 194, 240), outline=(30, 41, 59), width=2)
    # Loop arrow on top
    draw.arc([x - 16, y - 16, x + 16, y + 16], start=-140, end=10, fill=(30, 41, 59), width=2)
    # Arrow head pointing clockwise (down-right at the right side)
    draw.polygon([x + 11, y - 7, x + 20, y - 7, x + 15, y + 1], fill=(30, 41, 59))
    # Label
    draw_text_centered(draw, label, x, y + 36, font, (30, 41, 59))

def draw_uml_entity(draw, x, y, font, label):
    # Circle
    draw.ellipse([x - 12, y - 18, x + 12, y + 6], fill=(122, 194, 240), outline=(30, 41, 59), width=2)
    # Vertical link
    draw.line([x, y + 6, x, y + 14], fill=(30, 41, 59), width=2)
    # Flat base line
    draw.line([x - 18, y + 14, x + 18, y + 14], fill=(30, 41, 59), width=2)
    # Label
    draw_text_centered(draw, label, x, y + 36, font, (30, 41, 59))

def generate_png(name, data, output_dir):
    title = data["title"]
    participants = data["participants"]
    steps = data["steps"]

    # Calculate coordinates
    margin_x = 120
    spacing_x = 220
    n_part = len(participants)
    width = margin_x * 2 + (n_part - 1) * spacing_x
    
    def get_x(p_name):
        for idx, p in enumerate(participants):
            if p["name"] == p_name or p_name.startswith(p["name"]) or p["name"].startswith(p_name):
                return margin_x + idx * spacing_x
        return margin_x

    # Calculate step Y values
    y = 160
    step_data = []
    alt_stack = []
    
    for step in steps:
        stype = step[0]
        if stype == "msg":
            _, p_from, p_to, label, mtype = step
            step_data.append({
                "type": "msg",
                "from": p_from, "to": p_to,
                "x1": get_x(p_from), "x2": get_x(p_to),
                "y": y,
                "label": label,
                "mtype": mtype
            })
            y += 65
        elif stype == "self":
            _, p, label = step
            step_data.append({
                "type": "self",
                "p": p,
                "x": get_x(p),
                "y": y,
                "label": label
            })
            y += 75
        elif stype == "alt_start":
            _, cond = step
            alt_info = {
                "y_start": y - 10,
                "cond": cond,
                "splits": [],
                "y_end": None
            }
            alt_stack.append(alt_info)
            step_data.append({
                "type": "alt_start",
                "info": alt_info
            })
            y += 20
        elif stype == "alt_else":
            _, cond = step
            if alt_stack:
                alt_stack[-1]["splits"].append({
                    "y": y - 10,
                    "cond": cond
                })
            step_data.append({
                "type": "alt_else",
                "cond": cond
            })
            y += 20
        elif stype == "alt_end":
            if alt_stack:
                alt_info = alt_stack.pop()
                alt_info["y_end"] = y - 10
                step_data.append({
                    "type": "alt_end",
                    "info": alt_info
                })
            y += 15

    total_height = y + 80

    # Create image
    img = Image.new("RGBA", (width, total_height), (255, 255, 255, 255))
    draw = ImageDraw.Draw(img)

    # Draw Title
    draw.text((40, 25), title, fill=(30, 41, 59), font=font_title)

    # Lifeline start/end
    y_lifeline_start = 120
    y_lifeline_end = total_height - 60

    # Draw Lifelines (dashed line)
    for p in participants:
        x = get_x(p["name"])
        draw_dashed_line(draw, x, y_lifeline_start, x, y_lifeline_end, fill=(122, 194, 240), width=2)

    # Gather & Draw Activation boxes
    activations = []
    active_calls = {}
    
    for s in step_data:
        if s["type"] == "msg":
            mtype = s["mtype"]
            p_from = s["from"]
            p_to = s["to"]
            y_val = s["y"]
            
            if mtype == "call":
                if p_to not in active_calls:
                    active_calls[p_to] = []
                active_calls[p_to].append(y_val)
                # Actor lifeline is always active
                if p_from not in active_calls:
                    active_calls[p_from] = [y_val]
            elif mtype == "reply":
                if p_from in active_calls and active_calls[p_from]:
                    start_y = active_calls[p_from].pop()
                    activations.append((p_from, start_y, y_val))
        elif s["type"] == "self":
            p = s["p"]
            y_val = s["y"]
            activations.append((p, y_val, y_val + 35))

    # Close remaining active calls
    for p, starts in active_calls.items():
        for start_y in starts:
            activations.append((p, start_y, y_lifeline_end))

    # Draw the activation rectangles
    for p_name, start_y, end_y in activations:
        x = get_x(p_name)
        draw.rectangle([x - 6, start_y, x + 6, end_y], fill=(122, 194, 240, 255), outline=(30, 41, 59), width=1)

    # Draw Top Stereotype Symbols
    for p in participants:
        x = get_x(p["name"])
        y_shape = 70
        ptype = p["type"]
        if ptype == "actor":
            draw_uml_actor(draw, x, y_shape, font_bold, p["name"])
        elif ptype == "boundary":
            draw_uml_boundary(draw, x, y_shape, font_bold, p["name"])
        elif ptype == "control":
            draw_uml_control(draw, x, y_shape, font_bold, p["name"])
        elif ptype == "entity":
            draw_uml_entity(draw, x, y_shape, font_bold, p["name"])

    # Draw Steps (Messages and Alt blocks)
    for s in step_data:
        stype = s["type"]
        if stype == "msg":
            x1, x2, y_val = s["x1"], s["x2"], s["y"]
            label = s["label"]
            mtype = s["mtype"]
            
            if mtype == "call":
                # Solid line
                draw.line([x1, y_val, x2, y_val], fill=(30, 41, 59), width=2)
                # Arrowhead
                if x2 > x1:
                    draw.polygon([x2, y_val, x2 - 10, y_val - 5, x2 - 10, y_val + 5], fill=(30, 41, 59))
                else:
                    draw.polygon([x2, y_val, x2 + 10, y_val - 5, x2 + 10, y_val + 5], fill=(30, 41, 59))
            else:
                # Dashed line
                draw_dashed_line(draw, x1, y_val, x2, y_val, fill=(30, 41, 59), width=2)
                # Open arrowhead
                if x2 > x1:
                    draw.line([x2, y_val, x2 - 8, y_val - 4], fill=(30, 41, 59), width=2)
                    draw.line([x2, y_val, x2 - 8, y_val + 4], fill=(30, 41, 59), width=2)
                else:
                    draw.line([x2, y_val, x2 + 8, y_val - 4], fill=(30, 41, 59), width=2)
                    draw.line([x2, y_val, x2 + 8, y_val + 4], fill=(30, 41, 59), width=2)
                    
            draw_message_text(draw, label, x1, x2, y_val, font_regular, (30, 41, 59))
            
        elif stype == "self":
            x, y_val = s["x"], s["y"]
            label = s["label"]
            # Loop lines
            draw.line([x, y_val, x + 35, y_val], fill=(30, 41, 59), width=2)
            draw.line([x + 35, y_val, x + 35, y_val + 30], fill=(30, 41, 59), width=2)
            draw.line([x + 35, y_val + 30, x + 6, y_val + 30], fill=(30, 41, 59), width=2)
            # Arrowhead pointing left
            draw.polygon([x + 6, y_val + 30, x + 14, y_val + 26, x + 14, y_val + 34], fill=(30, 41, 59))
            
            draw_self_call_text(draw, label, x, y_val, font_regular, (30, 41, 59))
            
        elif stype == "alt_start":
            info = s["info"]
            y_start = info["y_start"]
            y_end = info["y_end"]
            cond = info["cond"]
            splits = info["splits"]
            
            x_min = margin_x - 30
            x_max = width - margin_x + 30
            
            # Draw alt boundary box
            draw.rectangle([x_min, y_start, x_max, y_end], outline=(148, 163, 184), width=2)
            
            # Alt badge
            draw.rectangle([x_min, y_start, x_min + 40, y_start + 18], fill=(148, 163, 184))
            draw_text_centered(draw, "alt", x_min + 20, y_start + 9, font_bold, (255, 255, 255))
            
            # First condition
            draw.text((x_min + 50, y_start + 3), f"[{cond}]", fill=(100, 116, 139), font=font_regular)
            
            # Draw splits
            for split in splits:
                sy = split["y"]
                scond = split["cond"]
                draw_dashed_line(draw, x_min, sy, x_max, sy, fill=(148, 163, 184), width=1, dash_length=4, gap_length=4)
                draw.text((x_min + 15, sy + 4), f"[{scond}]", fill=(100, 116, 139), font=font_regular)

    # Convert to RGB (to drop alpha channel and save as PNG/JPG safely)
    img_rgb = Image.new("RGB", img.size, (255, 255, 255))
    img_rgb.paste(img, mask=img.split()[3]) # paste using alpha as mask

    # Save image
    filepath = os.path.join(output_dir, f"{name}.png")
    img_rgb.save(filepath, "PNG", dpi=(300, 300))
    print(f"Generated: {filepath}")

# Main execution
if __name__ == "__main__":
    output_dir = "docs/sequence diagram"
    os.makedirs(output_dir, exist_ok=True)
    
    # Sort diagrams numerically by the prefix number in the key
    sorted_diagrams = {k: diagrams[k] for k in sorted(diagrams.keys(), key=lambda x: int(x.split('_')[0]))}
    diagrams = sorted_diagrams
    
    # Set of expected file names
    valid_names = {
        "1_login_sistem", "2_registrasi_akun", "3_kelola_pengguna", "4_kelola_landing_page",
        "5_kelola_tamu", "6_kelola_ulasan", "7_kelola_ruangan", "8_kelola_sarana", "9_kelola_paket",
        "10_kelola_profil", "11_kelola_invoice", "12_checkin", "13_checkout", "14_verifikasi_peminjaman",
        "15_ajukan_peminjaman", "16_batalkan_peminjaman", "17_lihat_laporan", "18_kelola_berita",
        "19_publish_berita"
    }

    # 1. Generate PNGs
    print(f"Generating {len(diagrams)} PNG Sequence Diagrams...")
    for filename, data in diagrams.items():
        generate_png(filename, data, output_dir)
        
    # 2. Cleanup old png/svg files
    print("Cleaning up old files...")
    for filename in os.listdir(output_dir):
        base, ext = os.path.splitext(filename)
        if ext in [".png", ".svg"]:
            if base not in valid_names and filename != "images.png":
                filepath = os.path.join(output_dir, filename)
                try:
                    os.remove(filepath)
                    print(f"Removed obsolete file: {filepath}")
                except Exception as e:
                    print(f"Failed to remove {filepath}: {e}")
            
    print("Sequence Diagrams generation and cleanup completed successfully.")
