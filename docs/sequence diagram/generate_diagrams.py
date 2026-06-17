import os
import math
from PIL import Image, ImageDraw, ImageFont

# Define the sequence diagrams data
# Group A: "Kelola Data" CRUD diagrams
kelola_templates = {
    "2_kelola_pengguna": {
        "title": "2. Kelola Pengguna (User Management)",
        "description": "Pengelolaan data pengguna sistem (Admin memiliki akses penuh, Pimpinan & Petugas diblokir).",
        "actor": "Admin",
        "menu": "Pengguna",
        "submenu": "Manajemen",
        "halaman": "Halaman Manajemen Pengguna",
        "controller": "User Controller",
        "model": "User Model",
        "data_singular": "pengguna",
        "data_plural": "pengguna",
        "roles": ["Admin"]
    },
    "3_kelola_tamu": {
        "title": "3. Kelola Tamu (Guest Management)",
        "description": "Pengelolaan data tamu (Guest) penyewa (Admin kelola penuh, Pimpinan read-only, Petugas diblokir).",
        "actor": "Admin",
        "menu": "Tamu",
        "submenu": "Manajemen",
        "halaman": "Halaman Manajemen Tamu",
        "controller": "Tamu Controller",
        "model": "Tamu Model",
        "data_singular": "tamu",
        "data_plural": "tamu",
        "roles": ["Admin", "Pimpinan"]
    },
    "4_kelola_gedung": {
        "title": "4. Kelola Gedung",
        "description": "Pengelolaan master data gedung (Admin kelola penuh, Pimpinan read-only, Petugas diblokir).",
        "actor": "Admin",
        "menu": "Gedung",
        "submenu": "Manajemen",
        "halaman": "Halaman Manajemen Gedung",
        "controller": "Gedung Controller",
        "model": "Gedung Model",
        "data_singular": "gedung",
        "data_plural": "gedung",
        "roles": ["Admin", "Pimpinan"]
    },
    "5_kelola_ruangan": {
        "title": "5. Kelola Ruangan",
        "description": "Pengelolaan master data ruangan (Petugas kelola penuh, Pimpinan read-only, Admin diblokir).",
        "actor": "Petugas",
        "menu": "Ruangan",
        "submenu": "Manajemen",
        "halaman": "Halaman Manajemen Ruangan",
        "controller": "Ruangan Controller",
        "model": "Ruangan Model",
        "data_singular": "ruangan",
        "data_plural": "ruangan",
        "roles": ["Petugas", "Pimpinan"]
    },
    "6_kelola_sarana": {
        "title": "6. Kelola Sarana & Prasarana",
        "description": "Pengelolaan master data sarana (Petugas kelola penuh, Pimpinan read-only, Admin diblokir).",
        "actor": "Petugas",
        "menu": "Sarana",
        "submenu": "Manajemen",
        "halaman": "Halaman Manajemen Sarana",
        "controller": "Sarana Controller",
        "model": "Sarana Model",
        "data_singular": "sarana",
        "data_plural": "sarana",
        "roles": ["Petugas", "Pimpinan"]
    },
    "7_kelola_paket_ruangan": {
        "title": "7. Kelola Paket Ruangan",
        "description": "Pengelolaan paket sewa dan durasi sewa ruangan (Petugas kelola penuh, Pimpinan read-only, Admin diblokir).",
        "actor": "Petugas",
        "menu": "Paket",
        "submenu": "Manajemen",
        "halaman": "Halaman Manajemen Paket",
        "controller": "Paket Controller",
        "model": "Paket Model",
        "data_singular": "paket",
        "data_plural": "paket",
        "roles": ["Petugas", "Pimpinan"]
    },
    "12_kelola_berita": {
        "title": "12. Kelola Berita",
        "description": "Penyusunan berita oleh Petugas (sebagai draft) dan penerbitan oleh Admin.",
        "actor": "Petugas",
        "menu": "Berita",
        "submenu": "Manajemen",
        "halaman": "Halaman Manajemen Berita",
        "controller": "Berita Controller",
        "model": "Berita Model",
        "data_singular": "berita",
        "data_plural": "berita",
        "roles": ["Petugas", "Admin"]
    },
    "13_kelola_landing_page": {
        "title": "13. Kelola Landing Page",
        "description": "Pengaturan informasi profil, FAQ, dan galeri web publik (Hanya untuk Admin).",
        "actor": "Admin",
        "menu": "Landing Page",
        "submenu": "Manajemen",
        "halaman": "Halaman Manajemen Landing Page",
        "controller": "Landing Page Controller",
        "model": "Landing Page Model",
        "data_singular": "landing page",
        "data_plural": "landing page",
        "roles": ["Admin"]
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
diagrams["1_autentikasi"] = {
    "title": "1. Autentikasi (Login & Logout)",
    "description": "Alur masuk (login) dan keluar (logout) sistem untuk seluruh pengguna.",
    "roles": ["Admin", "Petugas", "Pimpinan", "Guest"],
    "participants": [
        {"name": "Actor", "type": "actor"},
        {"name": "Halaman Autentikasi", "type": "boundary"},
        {"name": "Auth Controller", "type": "control"},
        {"name": "User Model", "type": "entity"}
    ],
    "steps": [
        ("msg", "Actor", "Halaman Autentikasi", "1: Input username & password, klik Login", "call"),
        ("msg", "Halaman Autentikasi", "Auth Controller", "1.1: Kirim data login (POST)", "call"),
        ("msg", "Auth Controller", "User Model", "1.1.1: Cari user berdasarkan email/username", "call"),
        ("msg", "User Model", "Auth Controller", "1.1.2: Kembalikan data user & password hash", "reply"),
        ("self", "Auth Controller", "1.1.3: Verifikasi password & session"),
        ("alt_start", "Password Valid"),
        ("msg", "Auth Controller", "Halaman Autentikasi", "1.1.4: Redirect ke Dashboard (Session Aktif)", "reply"),
        ("alt_else", "Password Salah"),
        ("msg", "Auth Controller", "Halaman Autentikasi", "1.1.5: Kembalikan pesan error (401)", "reply"),
        ("alt_end",),
        ("msg", "Actor", "Halaman Autentikasi", "2: Klik Logout", "call"),
        ("msg", "Halaman Autentikasi", "Auth Controller", "2.1: Request logout (POST)", "call"),
        ("msg", "Auth Controller", "Halaman Autentikasi", "2.2: Destroy session, redirect ke Login", "reply")
    ]
}

diagrams["8_pengajuan_peminjaman"] = {
    "title": "8. Pengajuan Peminjaman (Reservasi)",
    "description": "Alur pengajuan sewa ruangan dan sarana pendukung oleh Guest (Peminjam).",
    "roles": ["Guest"],
    "participants": [
        {"name": "Actor (Peminjam)", "type": "actor"},
        {"name": "Halaman Pengajuan", "type": "boundary"},
        {"name": "Peminjaman Controller", "type": "control"},
        {"name": "Peminjaman Model", "type": "entity"}
    ],
    "steps": [
        ("msg", "Actor (Peminjam)", "Halaman Pengajuan", "1: Isi Form Permohonan Peminjaman", "call"),
        ("msg", "Halaman Pengajuan", "Peminjaman Controller", "1.1: Kirim Permohonan Peminjaman (POST)", "call"),
        ("msg", "Peminjaman Controller", "Peminjaman Model", "1.1.1: Cek ketersediaan Ruangan & Sarana", "call"),
        ("msg", "Peminjaman Model", "Peminjaman Controller", "1.1.2: Return status ketersediaan", "reply"),
        ("alt_start", "Fasilitas Tersedia"),
        ("msg", "Peminjaman Controller", "Peminjaman Model", "1.1.3: Simpan data peminjaman (status=PENDING)", "call"),
        ("msg", "Peminjaman Controller", "Peminjaman Model", "1.1.4: Simpan detail sarana yang dipinjam", "call"),
        ("msg", "Peminjaman Model", "Peminjaman Controller", "1.1.5: Success", "reply"),
        ("msg", "Peminjaman Controller", "Halaman Pengajuan", "1.1.6: Tampilkan sukses (Menunggu Verifikasi)", "reply"),
        ("alt_else", "Fasilitas Terbooking / Stok Habis"),
        ("msg", "Peminjaman Controller", "Halaman Pengajuan", "1.1.7: Tampilkan pesan error ketersediaan", "reply"),
        ("alt_end",)
    ]
}

diagrams["9_verifikasi_approval"] = {
    "title": "9. Verifikasi & Approval Peminjaman",
    "description": "Proses persetujuan atau penolakan pengajuan sewa oleh Admin atau Petugas.",
    "roles": ["Admin", "Petugas", "Pimpinan (View-only)"],
    "participants": [
        {"name": "Actor", "type": "actor"},
        {"name": "Halaman Detail Peminjaman", "type": "boundary"},
        {"name": "Approval Controller", "type": "control"},
        {"name": "Peminjaman Model", "type": "entity"}
    ],
    "steps": [
        ("msg", "Actor", "Halaman Detail Peminjaman", "1: Buka detail peminjaman pending", "call"),
        ("msg", "Halaman Detail Peminjaman", "Approval Controller", "1.1: GET /admin/transaksi/peminjaman/{id}", "call"),
        ("msg", "Approval Controller", "Peminjaman Model", "1.1.1: Get peminjaman & detail sarana", "call"),
        ("msg", "Peminjaman Model", "Approval Controller", "1.1.2: Return data", "reply"),
        ("msg", "Approval Controller", "Halaman Detail Peminjaman", "1.1.3: Tampilkan detail peminjaman", "reply"),
        ("alt_start", "Role == Pimpinan"),
        ("self", "Halaman Detail Peminjaman", "1.1.4: Sembunyikan tombol Setujui/Tolak"),
        ("alt_else", "Role == Admin atau Petugas"),
        ("msg", "Actor", "Halaman Detail Peminjaman", "2: Klik tombol Setujui", "call"),
        ("msg", "Halaman Detail Peminjaman", "Approval Controller", "2.1: POST /admin/transaksi/peminjaman/{id}/approve", "call"),
        ("msg", "Approval Controller", "Peminjaman Model", "2.1.1: Update statusApproval=APPROVED & set tglApproval", "call"),
        ("msg", "Approval Controller", "Peminjaman Model", "2.1.2: Auto-generate Invoice record (status=UNPAID)", "call"),
        ("msg", "Peminjaman Model", "Approval Controller", "2.1.3: Success", "reply"),
        ("msg", "Approval Controller", "Halaman Detail Peminjaman", "2.1.4: Redirect & tampilkan status APPROVED", "reply"),
        ("alt_end",)
    ]
}

diagrams["10_checkin_checkout"] = {
    "title": "10. Proses Check-In & Check-Out",
    "description": "Pemberian check-in tamu pada hari H dan check-out saat masa sewa berakhir.",
    "roles": ["Petugas", "Admin"],
    "participants": [
        {"name": "Actor (Petugas)", "type": "actor"},
        {"name": "Halaman Transaksi", "type": "boundary"},
        {"name": "Peminjaman Controller", "type": "control"},
        {"name": "Peminjaman Model", "type": "entity"}
    ],
    "steps": [
        ("msg", "Actor (Petugas)", "Halaman Transaksi", "1: Klik tombol Proses Check-In", "call"),
        ("msg", "Halaman Transaksi", "Peminjaman Controller", "1.1: POST /admin/transaksi/peminjaman/{id}/check-in", "call"),
        ("msg", "Peminjaman Controller", "Peminjaman Model", "1.1.1: Update statusPeminjaman=CHECK_IN, checkIn=now()", "call"),
        ("msg", "Peminjaman Model", "Peminjaman Controller", "1.1.2: Success", "reply"),
        ("msg", "Peminjaman Controller", "Halaman Transaksi", "1.1.3: Refresh page & status menjadi CHECK_IN", "reply"),
        ("msg", "Actor (Petugas)", "Halaman Transaksi", "2: Tamu selesai, klik Check-Out & isi form kerusakan", "call"),
        ("msg", "Halaman Transaksi", "Peminjaman Controller", "2.1: POST /admin/transaksi/peminjaman/{id}/check-out", "call"),
        ("msg", "Peminjaman Controller", "Peminjaman Model", "2.1.1: Update statusPeminjaman=CHECK_OUT, checkOut=now()", "call"),
        ("msg", "Peminjaman Controller", "Peminjaman Model", "2.1.2: Simpan kondisiReturn, denda, & biayaTambahan", "call"),
        ("msg", "Peminjaman Controller", "Peminjaman Model", "2.1.3: Update Invoice (tambah biaya denda ke totalHarga)", "call"),
        ("msg", "Peminjaman Model", "Peminjaman Controller", "2.1.4: Success", "reply"),
        ("msg", "Peminjaman Controller", "Halaman Transaksi", "2.1.5: Redirect & status menjadi CHECK_OUT", "reply")
    ]
}

diagrams["11_kelola_invoice"] = {
    "title": "11. Kelola & Cetak Invoice",
    "description": "Pembuatan invoice tagihan sewa dan update status pembayaran oleh Admin.",
    "roles": ["Admin"],
    "participants": [
        {"name": "Actor", "type": "actor"},
        {"name": "Halaman Detail Invoice", "type": "boundary"},
        {"name": "Invoice Controller", "type": "control"},
        {"name": "Invoice Model", "type": "entity"}
    ],
    "steps": [
        ("msg", "Actor", "Halaman Detail Invoice", "1: Buka halaman kelola invoice", "call"),
        ("msg", "Halaman Detail Invoice", "Invoice Controller", "1.1: GET /admin/transaksi/invoice/{id}", "call"),
        ("msg", "Invoice Controller", "Invoice Model", "1.1.1: Get invoice & peminjaman details", "call"),
        ("msg", "Invoice Model", "Invoice Controller", "1.1.2: Return data", "reply"),
        ("msg", "Invoice Controller", "Halaman Detail Invoice", "1.1.3: Tampilkan Invoice Detail", "reply"),
        ("msg", "Actor", "Halaman Detail Invoice", "2: Konfirmasi Pembayaran Lunas", "call"),
        ("msg", "Halaman Detail Invoice", "Invoice Controller", "2.1: POST /admin/transaksi/invoice/{id}/pay", "call"),
        ("alt_start", "Role == Pimpinan"),
        ("msg", "Invoice Controller", "Halaman Detail Invoice", "2.1.1: 403 Forbidden (Blocked)", "reply"),
        ("alt_else", "Role == Admin"),
        ("msg", "Invoice Controller", "Invoice Model", "2.1.2: Update statusInvoice=PAID, status_pembayaran=LUNAS", "call"),
        ("msg", "Invoice Model", "Invoice Controller", "2.1.3: Success", "reply"),
        ("msg", "Invoice Controller", "Halaman Detail Invoice", "2.1.4: Tampilkan status PAID", "reply"),
        ("alt_end",)
    ]
}

diagrams["14_cetak_laporan"] = {
    "title": "14. Cetak Laporan",
    "description": "Cetak dan ekspor laporan peminjaman & penggunaan fasilitas (Admin & Pimpinan).",
    "roles": ["Admin", "Pimpinan"],
    "participants": [
        {"name": "Actor", "type": "actor"},
        {"name": "Halaman Laporan", "type": "boundary"},
        {"name": "Laporan Controller", "type": "control"},
        {"name": "Peminjaman Model", "type": "entity"}
    ],
    "steps": [
        ("msg", "Actor", "Halaman Laporan", "1: Buka Halaman Laporan & pilih filter tanggal", "call"),
        ("msg", "Halaman Laporan", "Laporan Controller", "1.1: GET /admin/laporan", "call"),
        ("alt_start", "Role == Petugas"),
        ("msg", "Laporan Controller", "Halaman Laporan", "1.1.1: 403 Forbidden (Blocked)", "reply"),
        ("alt_else", "Role in [Admin, Pimpinan]"),
        ("msg", "Laporan Controller", "Peminjaman Model", "1.1.2: Query aggregated data", "call"),
        ("msg", "Peminjaman Model", "Laporan Controller", "1.1.3: Return data", "reply"),
        ("msg", "Laporan Controller", "Halaman Laporan", "1.1.4: Tampilkan Halaman Laporan dengan data & tombol Cetak", "reply"),
        ("alt_end",)
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
    
    # 1. Generate PNGs
    print("Generating 14 PNG Sequence Diagrams...")
    for filename, data in diagrams.items():
        generate_png(filename, data, output_dir)
        
    # 2. Cleanup old SVG files
    print("Cleaning up old SVG files...")
    svg_files = [f for f in os.listdir(output_dir) if f.endswith(".svg")]
    for svg_file in svg_files:
        svg_path = os.path.join(output_dir, svg_file)
        try:
            os.remove(svg_path)
            print(f"Removed SVG: {svg_path}")
        except Exception as e:
            print(f"Failed to remove {svg_path}: {e}")
            
    print("Sequence Diagrams generation and cleanup completed successfully.")
