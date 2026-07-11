import os
import math
from PIL import Image, ImageDraw, ImageFont

# Define nodes and connections for the 19 flowcharts
flowcharts_data = {}

# Helper to generate typical CRUD flowchart data
def make_crud_flow(title, desc, roles, ds, dp):
    return {
        "title": title,
        "description": desc,
        "roles": roles,
        "nodes": {
            "start": {"type": "start", "label": "Mulai", "row": 0, "col": "center"},
            "select": {"type": "input", "label": f"Pilih Data & Klik Tambah/Ubah {ds}", "row": 1, "col": "center"},
            "input": {"type": "input", "label": f"Isi Formulir Data {ds}", "row": 2, "col": "center"},
            "validate": {"type": "process", "label": f"Validasi Kelayakan Data {ds}", "row": 3, "col": "center"},
            "decision": {"type": "decision", "label": "Data Valid?", "row": 4, "col": "center"},
            "save": {"type": "process", "label": f"Simpan Data {ds} ke Database", "row": 5, "col": "center"},
            "error": {"type": "output", "label": "Tampilkan Notifikasi Error Form", "row": 4.8, "col": "right"},
            "end": {"type": "end", "label": "Selesai", "row": 6, "col": "center"}
        },
        "connections": [
            {"from": "start", "to": "select"},
            {"from": "select", "to": "input"},
            {"from": "input", "to": "validate"},
            {"from": "validate", "to": "decision"},
            {"from": "decision", "to": "save", "label": "Ya", "route": "down"},
            {"from": "decision", "to": "error", "label": "Tidak", "route": "right"},
            {"from": "save", "to": "end"},
            {"from": "error", "to": "end", "route": "merge_right"}
        ]
    }

# 1. Login Sistem
flowcharts_data["1_login_sistem"] = {
    "title": "1. Login Sistem",
    "description": "Proses otentikasi login pengguna (Admin, Petugas, Pimpinan, dan Tamu).",
    "roles": ["Admin", "Petugas", "Pimpinan", "Tamu"],
    "nodes": {
        "start": {"type": "start", "label": "Mulai", "row": 0, "col": "center"},
        "input": {"type": "input", "label": "Masukkan Email & Password", "row": 1, "col": "center"},
        "process": {"type": "process", "label": "Enkripsi Password & Verifikasi Akun", "row": 2, "col": "center"},
        "decision": {"type": "decision", "label": "Cocok & Aktif?", "row": 3, "col": "center"},
        "success": {"type": "process", "label": "Buat Session Login & Buka Dashboard", "row": 4, "col": "center"},
        "error": {"type": "output", "label": "Tampilkan Peringatan Gagal Login", "row": 3.8, "col": "right"},
        "end": {"type": "end", "label": "Selesai", "row": 5, "col": "center"}
    },
    "connections": [
        {"from": "start", "to": "input"},
        {"from": "input", "to": "process"},
        {"from": "process", "to": "decision"},
        {"from": "decision", "to": "success", "label": "Ya", "route": "down"},
        {"from": "decision", "to": "error", "label": "Tidak", "route": "right"},
        {"from": "success", "to": "end"},
        {"from": "error", "to": "end", "route": "merge_right"}
    ]
}

# 2. Registrasi Akun
flowcharts_data["2_registrasi_akun"] = {
    "title": "2. Registrasi Akun",
    "description": "Proses pendaftaran akun baru oleh Tamu beserta verifikasi kode OTP email.",
    "roles": ["Tamu"],
    "nodes": {
        "start": {"type": "start", "label": "Mulai", "row": 0, "col": "center"},
        "input_reg": {"type": "input", "label": "Masukkan Data Akun (Nama, Email, dll)", "row": 1, "col": "center"},
        "process_val": {"type": "process", "label": "Validasi Email Unik & Form Input", "row": 2, "col": "center"},
        "decision_valid": {"type": "decision", "label": "Data Valid?", "row": 3, "col": "center"},
        "save_inactive": {"type": "process", "label": "Simpan User Inaktif & Kirim OTP", "row": 4, "col": "center"},
        "err_val": {"type": "output", "label": "Tampilkan Notifikasi Error Form", "row": 3.8, "col": "right"},
        "input_otp": {"type": "input", "label": "Input Kode OTP dari Email", "row": 5, "col": "center"},
        "decision_otp": {"type": "decision", "label": "OTP Benar?", "row": 6, "col": "center"},
        "activate": {"type": "process", "label": "Ubah Status Akun Menjadi Aktif", "row": 7, "col": "center"},
        "err_otp": {"type": "output", "label": "Tampilkan Pesan Error OTP", "row": 6.8, "col": "right"},
        "end": {"type": "end", "label": "Selesai", "row": 8, "col": "center"}
    },
    "connections": [
        {"from": "start", "to": "input_reg"},
        {"from": "input_reg", "to": "process_val"},
        {"from": "process_val", "to": "decision_valid"},
        {"from": "decision_valid", "to": "save_inactive", "label": "Ya", "route": "down"},
        {"from": "decision_valid", "to": "err_val", "label": "Tidak", "route": "right"},
        {"from": "save_inactive", "to": "input_otp"},
        {"from": "input_otp", "to": "decision_otp"},
        {"from": "decision_otp", "to": "activate", "label": "Ya", "route": "down"},
        {"from": "decision_otp", "to": "err_otp", "label": "Tidak", "route": "right"},
        {"from": "activate", "to": "end"},
        {"from": "err_val", "to": "end", "route": "merge_right"},
        {"from": "err_otp", "to": "end", "route": "merge_right"}
    ]
}

# 3 - 9 CRUD Flowcharts
flowcharts_data["3_kelola_pengguna"] = make_crud_flow("3. Kelola Pengguna", "Pengelolaan data akun pengguna oleh Admin.", ["Admin"], "Pengguna", "pengguna")
flowcharts_data["4_kelola_landing_page"] = make_crud_flow("4. Kelola Landing Page", "Pengelolaan visual slide, FAQ, dan informasi landing page oleh Admin.", ["Admin"], "Landing Page", "landing page")
flowcharts_data["5_kelola_tamu"] = make_crud_flow("5. Kelola Tamu", "Pengelolaan data identitas dan instansi Tamu oleh Admin dan Petugas.", ["Admin", "Petugas"], "Tamu", "tamu")
flowcharts_data["6_kelola_ulasan"] = make_crud_flow("6. Kelola Ulasan", "Pengelolaan ulasan/rating ruangan yang disewa oleh Tamu.", ["Tamu"], "Ulasan", "ulasan")
flowcharts_data["7_kelola_ruangan"] = make_crud_flow("7. Kelola Ruangan", "Pengelolaan master data ruangan, kapasitas, dan tarif sewa oleh Petugas.", ["Petugas"], "Ruangan", "ruangan")
flowcharts_data["8_kelola_sarana"] = make_crud_flow("8. Kelola Sarana & Prasarana", "Pengelolaan master data sarana pendukung sewa oleh Petugas.", ["Petugas"], "Sarana", "sarana")
flowcharts_data["9_kelola_paket"] = make_crud_flow("9. Kelola Paket Ruangan", "Pengelolaan data paket sewa bundling oleh Petugas.", ["Petugas"], "Paket", "paket")

# 10. Kelola Profil
flowcharts_data["10_kelola_profil"] = {
    "title": "10. Kelola Profil",
    "description": "Pengelolaan data profil diri dan ubah password oleh Tamu.",
    "roles": ["Tamu"],
    "nodes": {
        "start": {"type": "start", "label": "Mulai", "row": 0, "col": "center"},
        "input_profile": {"type": "input", "label": "Buka Halaman Profil & Edit Form", "row": 1, "col": "center"},
        "process_val": {"type": "process", "label": "Validasi NIK, No Telp & Password", "row": 2, "col": "center"},
        "decision": {"type": "decision", "label": "Data Valid?", "row": 3, "col": "center"},
        "save": {"type": "process", "label": "Update Profil & Password di Database", "row": 4, "col": "center"},
        "error": {"type": "output", "label": "Tampilkan Notifikasi Error Form", "row": 3.8, "col": "right"},
        "end": {"type": "end", "label": "Selesai", "row": 5, "col": "center"}
    },
    "connections": [
        {"from": "start", "to": "input_profile"},
        {"from": "input_profile", "to": "process_val"},
        {"from": "process_val", "to": "decision"},
        {"from": "decision", "to": "save", "label": "Ya", "route": "down"},
        {"from": "decision", "to": "error", "label": "Tidak", "route": "right"},
        {"from": "save", "to": "end"},
        {"from": "error", "to": "end", "route": "merge_right"}
    ]
}

# 11. Kelola Invoice
flowcharts_data["11_kelola_invoice"] = {
    "title": "11. Kelola & Cetak Invoice",
    "description": "Pengelolaan status bayar invoice peminjaman oleh Petugas.",
    "roles": ["Petugas"],
    "nodes": {
        "start": {"type": "start", "label": "Mulai", "row": 0, "col": "center"},
        "input_select": {"type": "input", "label": "Pilih Invoice Transaksi Tamu", "row": 1, "col": "center"},
        "process_pay": {"type": "process", "label": "Input Pembayaran & Validasi Jumlah", "row": 2, "col": "center"},
        "decision": {"type": "decision", "label": "Sesuai?", "row": 3, "col": "center"},
        "save": {"type": "process", "label": "Set Status PAID & Cetak Kuitansi", "row": 4, "col": "center"},
        "error": {"type": "output", "label": "Tampilkan Error Nominal Kurang", "row": 3.8, "col": "right"},
        "end": {"type": "end", "label": "Selesai", "row": 5, "col": "center"}
    },
    "connections": [
        {"from": "start", "to": "input_select"},
        {"from": "input_select", "to": "process_pay"},
        {"from": "process_pay", "to": "decision"},
        {"from": "decision", "to": "save", "label": "Ya", "route": "down"},
        {"from": "decision", "to": "error", "label": "Tidak", "route": "right"},
        {"from": "save", "to": "end"},
        {"from": "error", "to": "end", "route": "merge_right"}
    ]
}

# 12. Check-In Peminjaman
flowcharts_data["12_checkin"] = {
    "title": "12. Proses Check-In",
    "description": "Proses check-in peminjaman ruangan pada hari H pemakaian oleh Petugas.",
    "roles": ["Petugas"],
    "nodes": {
        "start": {"type": "start", "label": "Mulai", "row": 0, "col": "center"},
        "select": {"type": "input", "label": "Pilih Peminjaman status APPROVED", "row": 1, "col": "center"},
        "click": {"type": "process", "label": "Klik Tombol Proses Check-In", "row": 2, "col": "center"},
        "decision": {"type": "decision", "label": "Sudah Hari H?", "row": 3, "col": "center"},
        "success": {"type": "process", "label": "Update Status CHECK_IN & Jam Masuk", "row": 4, "col": "center"},
        "error": {"type": "output", "label": "Tampilkan Pesan Peringatan Tanggal", "row": 3.8, "col": "right"},
        "end": {"type": "end", "label": "Selesai", "row": 5, "col": "center"}
    },
    "connections": [
        {"from": "start", "to": "select"},
        {"from": "select", "to": "click"},
        {"from": "click", "to": "decision"},
        {"from": "decision", "to": "success", "label": "Ya", "route": "down"},
        {"from": "decision", "to": "error", "label": "Tidak", "route": "right"},
        {"from": "success", "to": "end"},
        {"from": "error", "to": "end", "route": "merge_right"}
    ]
}

# 13. Check-Out Peminjaman
flowcharts_data["13_checkout"] = {
    "title": "13. Proses Check-Out",
    "description": "Proses pengembalian ruangan, pencatatan kondisi barang, denda, dan checkout oleh Petugas.",
    "roles": ["Petugas"],
    "nodes": {
        "start": {"type": "start", "label": "Mulai", "row": 0, "col": "center"},
        "select": {"type": "input", "label": "Pilih Peminjaman status CHECK_IN", "row": 1, "col": "center"},
        "form": {"type": "process", "label": "Klik Check-Out & Input Kondisi", "row": 2, "col": "center"},
        "decision": {"type": "decision", "label": "Ada Rusak/Telat?", "row": 3, "col": "center"},
        "calc": {"type": "process", "label": "Hitung Denda & Update Total Tagihan", "row": 4, "col": "center"},
        "no_fine": {"type": "process", "label": "Set Biaya Denda = Rp 0", "row": 3.8, "col": "right"},
        "finish": {"type": "process", "label": "Update Status SELESAI & Jam Keluar", "row": 5, "col": "center"},
        "end": {"type": "end", "label": "Selesai", "row": 6, "col": "center"}
    },
    "connections": [
        {"from": "start", "to": "select"},
        {"from": "select", "to": "form"},
        {"from": "form", "to": "decision"},
        {"from": "decision", "to": "calc", "label": "Ya", "route": "down"},
        {"from": "decision", "to": "no_fine", "label": "Tidak", "route": "right"},
        {"from": "calc", "to": "finish"},
        {"from": "no_fine", "to": "finish", "route": "merge_right"},
        {"from": "finish", "to": "end"}
    ]
}

# 14. Verifikasi Peminjaman
flowcharts_data["14_verifikasi_peminjaman"] = {
    "title": "14. Verifikasi Peminjaman",
    "description": "Verifikasi kelayakan berkas pengajuan sewa pending oleh Petugas.",
    "roles": ["Petugas"],
    "nodes": {
        "start": {"type": "start", "label": "Mulai", "row": 0, "col": "center"},
        "open": {"type": "input", "label": "Buka Berkas Peminjaman Pending", "row": 1, "col": "center"},
        "check": {"type": "process", "label": "Cek Dokumen & Persetujuan Kuota", "row": 2, "col": "center"},
        "decision": {"type": "decision", "label": "Disetujui?", "row": 3, "col": "center"},
        "approve": {"type": "process", "label": "Status APPROVED & Generate Invoice", "row": 4, "col": "center"},
        "reject": {"type": "process", "label": "Status REJECTED & Input Alasan", "row": 3.8, "col": "right"},
        "end": {"type": "end", "label": "Selesai", "row": 5, "col": "center"}
    },
    "connections": [
        {"from": "start", "to": "open"},
        {"from": "open", "to": "check"},
        {"from": "check", "to": "decision"},
        {"from": "decision", "to": "approve", "label": "Ya", "route": "down"},
        {"from": "decision", "to": "reject", "label": "Tidak", "route": "right"},
        {"from": "approve", "to": "end"},
        {"from": "reject", "to": "end", "route": "merge_right"}
    ]
}

# 15. Ajukan Peminjaman
flowcharts_data["15_ajukan_peminjaman"] = {
    "title": "15. Ajukan Peminjaman (Reservasi)",
    "description": "Proses permohonan sewa ruangan dan sarana pendukung oleh Tamu.",
    "roles": ["Tamu"],
    "nodes": {
        "start": {"type": "start", "label": "Mulai", "row": 0, "col": "center"},
        "input": {"type": "input", "label": "Pilih Ruangan, Sarana, & Tanggal Sewa", "row": 1, "col": "center"},
        "check": {"type": "process", "label": "Cek Ketersediaan Kuota & Jadwal", "row": 2, "col": "center"},
        "decision": {"type": "decision", "label": "Fasilitas Tersedia?", "row": 3, "col": "center"},
        "save": {"type": "process", "label": "Simpan Transaksi status PENDING", "row": 4, "col": "center"},
        "error": {"type": "output", "label": "Tampilkan Notifikasi Sudah Terbooking", "row": 3.8, "col": "right"},
        "end": {"type": "end", "label": "Selesai", "row": 5, "col": "center"}
    },
    "connections": [
        {"from": "start", "to": "input"},
        {"from": "input", "to": "check"},
        {"from": "check", "to": "decision"},
        {"from": "decision", "to": "save", "label": "Ya", "route": "down"},
        {"from": "decision", "to": "error", "label": "Tidak", "route": "right"},
        {"from": "save", "to": "end"},
        {"from": "error", "to": "end", "route": "merge_right"}
    ]
}

# 16. Batalkan Peminjaman
flowcharts_data["16_batalkan_peminjaman"] = {
    "title": "16. Batalkan Peminjaman",
    "description": "Pembatalan sewa ruangan secara mandiri oleh Tamu sebelum H-1.",
    "roles": ["Tamu"],
    "nodes": {
        "start": {"type": "start", "label": "Mulai", "row": 0, "col": "center"},
        "select": {"type": "input", "label": "Pilih Reservasi Aktif di Menu Saya", "row": 1, "col": "center"},
        "validate": {"type": "process", "label": "Validasi Batas Waktu Pembatalan", "row": 2, "col": "center"},
        "decision": {"type": "decision", "label": "Bisa Batal?", "row": 3, "col": "center"},
        "cancel": {"type": "process", "label": "Update Status BATAL & Bebaskan Jadwal", "row": 4, "col": "center"},
        "error": {"type": "output", "label": "Tampilkan Peringatan Melebihi Batas", "row": 3.8, "col": "right"},
        "end": {"type": "end", "label": "Selesai", "row": 5, "col": "center"}
    },
    "connections": [
        {"from": "start", "to": "select"},
        {"from": "select", "to": "validate"},
        {"from": "validate", "to": "decision"},
        {"from": "decision", "to": "cancel", "label": "Ya", "route": "down"},
        {"from": "decision", "to": "error", "label": "Tidak", "route": "right"},
        {"from": "cancel", "to": "end"},
        {"from": "error", "to": "end", "route": "merge_right"}
    ]
}

# 17. Lihat Laporan
flowcharts_data["17_lihat_laporan"] = {
    "title": "17. Lihat Laporan Penggunaan",
    "description": "Penyaringan, peninjauan statistik okupansi, dan cetak laporan oleh Admin dan Pimpinan.",
    "roles": ["Admin", "Pimpinan"],
    "nodes": {
        "start": {"type": "start", "label": "Mulai", "row": 0, "col": "center"},
        "filter": {"type": "input", "label": "Masukkan Parameter Filter Tanggal", "row": 1, "col": "center"},
        "query": {"type": "process", "label": "Query Okupansi & Pemasukan Sewa", "row": 2, "col": "center"},
        "view": {"type": "process", "label": "Tampilkan Tabel Data & Chart Okupansi", "row": 3, "col": "center"},
        "decision": {"type": "decision", "label": "Cetak Laporan?", "row": 4, "col": "center"},
        "pdf": {"type": "process", "label": "Export Laporan ke File PDF", "row": 5, "col": "center"},
        "error": {"type": "process", "label": "Tetap Tampilkan Data di Layar", "row": 4.8, "col": "right"},
        "end": {"type": "end", "label": "Selesai", "row": 6, "col": "center"}
    },
    "connections": [
        {"from": "start", "to": "filter"},
        {"from": "filter", "to": "query"},
        {"from": "query", "to": "view"},
        {"from": "view", "to": "decision"},
        {"from": "decision", "to": "pdf", "label": "Ya", "route": "down"},
        {"from": "decision", "to": "error", "label": "Tidak", "route": "right"},
        {"from": "pdf", "to": "end"},
        {"from": "error", "to": "end", "route": "merge_right"}
    ]
}

# 18. Kelola Berita
flowcharts_data["18_kelola_berita"] = make_crud_flow("18. Kelola Berita", "Penyusunan draft berita/pengumuman oleh Admin dan Petugas.", ["Admin", "Petugas"], "Berita", "berita")

# 19. Publish Berita
flowcharts_data["19_publish_berita"] = {
    "title": "19. Publish Berita",
    "description": "Proses publikasi draft berita/pengumuman ke halaman publik oleh Admin.",
    "roles": ["Admin"],
    "nodes": {
        "start": {"type": "start", "label": "Mulai", "row": 0, "col": "center"},
        "select": {"type": "input", "label": "Pilih Berita Status DRAFT", "row": 1, "col": "center"},
        "preview": {"type": "process", "label": "Pratinjau Berita & Isi Pengumuman", "row": 2, "col": "center"},
        "decision": {"type": "decision", "label": "Klik Publish?", "row": 3, "col": "center"},
        "publish": {"type": "process", "label": "Ubah Status PUBLISHED & Tampilkan", "row": 4, "col": "center"},
        "error": {"type": "process", "label": "Pertahankan Berita sebagai DRAFT", "row": 3.8, "col": "right"},
        "end": {"type": "end", "label": "Selesai", "row": 5, "col": "center"}
    },
    "connections": [
        {"from": "start", "to": "select"},
        {"from": "select", "to": "preview"},
        {"from": "preview", "to": "decision"},
        {"from": "decision", "to": "publish", "label": "Ya", "route": "down"},
        {"from": "decision", "to": "error", "label": "Tidak", "route": "right"},
        {"from": "publish", "to": "end"},
        {"from": "error", "to": "end", "route": "merge_right"}
    ]
}

# PIL configuration
def get_font():
    paths = [
        "C:\\Windows\\Fonts\\segoeui.ttf",
        "C:\\Windows\\Fonts\\arial.ttf",
        "segoeui.ttf",
        "arial.ttf"
    ]
    for path in paths:
        try:
            font_title = ImageFont.truetype(path, 15)
            font_bold = ImageFont.truetype(path, 12)
            font_regular = ImageFont.truetype(path, 11)
            return font_title, font_bold, font_regular
        except IOError:
            continue
    fallback = ImageFont.load_default()
    return fallback, fallback, fallback

font_title, font_bold, font_regular = get_font()

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

def draw_text_centered(draw, text, x, y, font, fill):
    lines = wrap_text(text, font, 140, draw)
    line_heights = []
    for line in lines:
        bbox = draw.textbbox((0, 0), line, font=font)
        line_heights.append(bbox[3] - bbox[1] + 3)
    total_h = sum(line_heights)
    
    curr_y = y - total_h / 2
    for line in lines:
        bbox = draw.textbbox((0, 0), line, font=font)
        w = bbox[2] - bbox[0]
        h = bbox[3] - bbox[1]
        draw.text((x - w / 2, curr_y), line, font=font, fill=fill)
        curr_y += h + 3

def draw_pill(draw, x, y, w, h, text, font):
    draw.rounded_rectangle([x - w/2, y - h/2, x + w/2, y + h/2], radius=h/2, fill=(255, 255, 255, 255), outline=(30, 41, 59), width=2)
    draw_text_centered(draw, text, x, y, font, (30, 41, 59))

def draw_parallelogram(draw, x, y, w, h, text, font, skew=15):
    p1 = (x - w/2 + skew, y - h/2)
    p2 = (x + w/2 + skew, y - h/2)
    p3 = (x + w/2 - skew, y + h/2)
    p4 = (x - w/2 - skew, y + h/2)
    draw.polygon([p1, p2, p3, p4], fill=(255, 255, 255, 255), outline=(30, 41, 59), width=2)
    draw_text_centered(draw, text, x, y, font, (30, 41, 59))

def draw_rounded_rect(draw, x, y, w, h, text, font, radius=8):
    draw.rounded_rectangle([x - w/2, y - h/2, x + w/2, y + h/2], radius=radius, fill=(255, 255, 255, 255), outline=(30, 41, 59), width=2)
    draw_text_centered(draw, text, x, y, font, (30, 41, 59))

def draw_diamond(draw, x, y, w, h, text, font):
    top = (x, y - h/2)
    right = (x + w/2, y)
    bottom = (x, y + h/2)
    left = (x - w/2, y)
    draw.polygon([top, right, bottom, left], fill=(255, 255, 255, 255), outline=(30, 41, 59), width=2)
    draw_text_centered(draw, text, x, y, font, (30, 41, 59))

def draw_arrowhead(draw, target_point, direction="down"):
    x, y = target_point
    if direction == "down":
        draw.polygon([(x, y), (x - 5, y - 8), (x + 5, y - 8)], fill=(30, 41, 59))
    elif direction == "left":
        draw.polygon([(x, y), (x + 8, y - 5), (x + 8, y + 5)], fill=(30, 41, 59))

def draw_flowchart_png(name, data, output_dir):
    nodes = data["nodes"]
    connections = data["connections"]

    # Calculate coordinates
    col_x_map = {
        "center": 300,
        "right": 480,
        "left": 120
    }
    
    def get_node_coords(n_name):
        n = nodes[n_name]
        cx = col_x_map[n["col"]]
        cy = 80 + n["row"] * 105
        return cx, cy
        
    max_row = max(n["row"] for n in nodes.values())
    canvas_height = int(80 + max_row * 105 + 70)
    
    img = Image.new("RGBA", (600, canvas_height), (255, 255, 255, 255))
    draw = ImageDraw.Draw(img)
    
    # Draw Title
    draw.text((40, 25), data["title"], fill=(30, 41, 59), font=font_title)
    
    # Draw Connections
    for conn in connections:
        from_node = conn["from"]
        to_node = conn["to"]
        route = conn.get("route", "down")
        label = conn.get("label", "")
        
        x_from, y_from = get_node_coords(from_node)
        x_to, y_to = get_node_coords(to_node)
        
        # Offsets based on shape sizes
        h_from = 44 if nodes[from_node]["type"] in ["start", "end"] else (76 if nodes[from_node]["type"] == "decision" else 46)
        h_to = 44 if nodes[to_node]["type"] in ["start", "end"] else (76 if nodes[to_node]["type"] == "decision" else 46)
        w_from = 120 if nodes[from_node]["type"] in ["start", "end"] else (130 if nodes[from_node]["type"] == "decision" else 170)
        w_to = 120 if nodes[to_node]["type"] in ["start", "end"] else (130 if nodes[to_node]["type"] == "decision" else 170)
        
        if route == "down":
            p_start = (x_from, y_from + h_from/2)
            p_end = (x_to, y_to - h_to/2)
            draw.line([p_start, p_end], fill=(30, 41, 59), width=2)
            draw_arrowhead(draw, p_end, "down")
            
            # Draw Ya/Tidak label
            if label:
                draw.text((p_start[0] + 8, p_start[1] + 15), label, fill=(100, 116, 139), font=font_bold)
                
        elif route == "right":
            p1 = (x_from + w_from/2, y_from)
            p2 = (x_to, y_from)
            p3 = (x_to, y_to - h_to/2)
            draw.line([p1, p2, p3], fill=(30, 41, 59), width=2)
            draw_arrowhead(draw, p3, "down")
            
            if label:
                draw.text((p1[0] + 10, p1[1] - 16), label, fill=(100, 116, 139), font=font_bold)
                
        elif route == "merge_right":
            p1 = (x_from, y_from + h_from/2)
            # Merge just above the target end node
            y_merge = y_to - h_to/2 - 35
            p2 = (x_from, y_merge)
            p3 = (x_to, y_merge)
            draw.line([p1, p2, p3], fill=(30, 41, 59), width=2)
            
    # Draw Nodes
    for n_id, n in nodes.items():
        cx, cy = get_node_coords(n_id)
        ntype = n["type"]
        lbl = n["label"]
        
        if ntype in ["start", "end"]:
            draw_pill(draw, cx, cy, 120, 44, lbl, font_bold)
        elif ntype in ["input", "output"]:
            draw_parallelogram(draw, cx, cy, 170, 46, lbl, font_regular)
        elif ntype == "process":
            draw_rounded_rect(draw, cx, cy, 170, 46, lbl, font_regular)
        elif ntype == "decision":
            draw_diamond(draw, cx, cy, 130, 76, lbl, font_bold)

    # Save to PNG
    img_rgb = Image.new("RGB", img.size, (255, 255, 255))
    img_rgb.paste(img, mask=img.split()[3])
    filepath = os.path.join(output_dir, f"{name}.png")
    img_rgb.save(filepath, "PNG", dpi=(300, 300))
    print(f"Generated PNG: {filepath}")

def xml_escape(text):
    if not text:
        return ""
    return text.replace("&", "&amp;").replace("<", "&lt;").replace(">", "&gt;").replace('"', "&quot;").replace("'", "&apos;")

def generate_flowchart_drawio(name, data, output_dir):
    nodes = data["nodes"]
    connections = data["connections"]
    
    col_x_map = {
        "center": 300,
        "right": 480,
        "left": 120
    }
    
    def get_node_geometry(n_name):
        n = nodes[n_name]
        cx = col_x_map[n["col"]]
        cy = 80 + n["row"] * 105
        
        h = 44 if n["type"] in ["start", "end"] else (76 if n["type"] == "decision" else 46)
        w = 120 if n["type"] in ["start", "end"] else (130 if n["type"] == "decision" else 170)
        return cx - w//2, cy - h//2, w, h

    xml_lines = []
    xml_lines.append('<?xml version="1.0" encoding="UTF-8"?>')
    xml_lines.append('<mxfile host="Electron" modified="2026-06-15T00:00:00.000Z" agent="5.0" version="20.0.0" type="device">')
    xml_lines.append(f'  <diagram id="{name}" name="{xml_escape(data["title"])}">')
    xml_lines.append('    <mxGraphModel dx="1000" dy="1000" grid="1" gridSize="10" guides="1" tooltips="1" connect="1" arrows="1" fold="1" page="1" pageScale="1" pageWidth="827" pageHeight="1169" math="0" shadow="0">')
    xml_lines.append('      <root>')
    xml_lines.append('        <mxCell id="0"/>')
    xml_lines.append('        <mxCell id="1" parent="0"/>')
    
    cell_id = 2
    node_id_map = {}
    
    # Draw title
    title_id = cell_id
    cell_id += 1
    xml_lines.append(f'        <mxCell id="{title_id}" value="{xml_escape(data["title"])}" style="text;html=1;strokeColor=none;fillColor=none;align=center;verticalAlign=middle;whiteSpace=wrap;rounded=0;fontStyle=1;fontSize=15;" vertex="1" parent="1">')
    xml_lines.append('          <mxGeometry x="10" y="10" width="580" height="40" as="geometry"/>')
    xml_lines.append('        </mxCell>')
    
    # Draw Nodes
    for n_id, n in nodes.items():
        node_id = cell_id
        cell_id += 1
        node_id_map[n_id] = node_id
        
        x, y, w, h = get_node_geometry(n_id)
        lbl = n["label"]
        
        if n["type"] in ["start", "end"]:
            # Pill shape
            style = "rounded=1;whiteSpace=wrap;html=1;arcSize=50;fillColor=#ffffff;strokeColor=#000000;strokeWidth=2;fontStyle=1;align=center;verticalAlign=middle;"
        elif n["type"] in ["input", "output"]:
            # Parallelogram
            style = "shape=parallelogram;perimeter=parallelogramPerimeter;whiteSpace=wrap;html=1;fixedSize=1;fillColor=#ffffff;strokeColor=#000000;strokeWidth=2;align=center;verticalAlign=middle;"
        elif n["type"] == "process":
            # Rounded process rect
            style = "rounded=1;whiteSpace=wrap;html=1;arcSize=15;fillColor=#ffffff;strokeColor=#000000;strokeWidth=2;align=center;verticalAlign=middle;"
        elif n["type"] == "decision":
            # Diamond
            style = "rhombus;whiteSpace=wrap;html=1;fillColor=#ffffff;strokeColor=#000000;strokeWidth=2;fontStyle=1;align=center;verticalAlign=middle;"
            
        xml_lines.append(f'        <mxCell id="{node_id}" value="{xml_escape(lbl)}" style="{style}" vertex="1" parent="1">')
        xml_lines.append(f'          <mxGeometry x="{x}" y="{y}" width="{w}" height="{h}" as="geometry"/>')
        xml_lines.append('        </mxCell>')
        
    # Draw Connections
    for conn in connections:
        edge_id = cell_id
        cell_id += 1
        
        from_id = node_id_map[conn["from"]]
        to_id = node_id_map[conn["to"]]
        label = conn.get("label", "")
        route = conn.get("route", "down")
        
        style = "edgeStyle=orthogonalEdgeStyle;rounded=0;orthogonalLoop=1;jettySize=auto;html=1;strokeColor=#000000;strokeWidth=2;endArrow=block;endFill=1;"
        if label:
            style += "labelBackgroundColor=#ffffff;"
            
        xml_lines.append(f'        <mxCell id="{edge_id}" value="{xml_escape(label)}" style="{style}" edge="1" parent="1" source="{from_id}" target="{to_id}">')
        xml_lines.append('          <mxGeometry relative="1" as="geometry"/>')
        xml_lines.append('        </mxCell>')
        
    xml_lines.append('      </root>')
    xml_lines.append('    </mxGraphModel>')
    xml_lines.append('  </diagram>')
    xml_lines.append('</mxfile>')
    
    filepath = os.path.join(output_dir, f"{name}.drawio")
    with open(filepath, "w", encoding="utf-8") as f:
        f.write("\n".join(xml_lines))
    print(f"Generated draw.io: {filepath}")

# Main execution
if __name__ == "__main__":
    output_dir = "docs/flowchart"
    os.makedirs(output_dir, exist_ok=True)
    
    # Generate all 19 flowcharts
    for key, data in flowcharts_data.items():
        draw_flowchart_png(key, data, output_dir)
        generate_flowchart_drawio(key, data, output_dir)
        
    print("Flowcharts generation completed successfully.")
