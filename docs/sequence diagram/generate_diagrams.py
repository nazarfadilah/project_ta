import os

# Define the sequence diagrams data
diagrams = {
    "1_autentikasi": {
        "title": "1. Autentikasi (Login & Logout)",
        "description": "Alur masuk (login) dan keluar (logout) sistem untuk seluruh pengguna.",
        "participants": ["Actor", "View", "Controller", "Database"],
        "steps": [
            ("msg", "Actor", "View", "Input username & password, klik Login", "call"),
            ("msg", "View", "Controller", "Kirim data login (POST)", "call"),
            ("msg", "Controller", "Database", "Cari user berdasarkan email/username", "call"),
            ("msg", "Database", "Controller", "Kembalikan data user & password hash", "reply"),
            ("self", "Controller", "Verifikasi password & session"),
            ("alt_start", "Password Valid"),
            ("msg", "Controller", "View", "Redirect ke Dashboard (Session Aktif)", "reply"),
            ("alt_else", "Password Salah"),
            ("msg", "Controller", "View", "Kembalikan pesan error (401)", "reply"),
            ("alt_end",),
            ("msg", "Actor", "View", "Klik Logout", "call"),
            ("msg", "View", "Controller", "Request logout (POST)", "call"),
            ("msg", "Controller", "View", "Destroy session, redirect ke Login", "reply")
        ]
    },
    "2_kelola_pengguna": {
        "title": "2. Kelola Pengguna (User Management)",
        "description": "Pengelolaan data pengguna sistem (Admin memiliki akses penuh, Pimpinan & Petugas diblokir).",
        "participants": ["Actor", "View", "Middleware", "Controller", "Database"],
        "steps": [
            ("msg", "Actor", "View", "Buka Halaman Kelola Pengguna", "call"),
            ("msg", "View", "Middleware", "GET /admin/users", "call"),
            ("alt_start", "Role == Petugas (3) / Pimpinan (2)"),
            ("msg", "Middleware", "View", "403 Forbidden (Blocked)", "reply"),
            ("alt_else", "Role == Admin (1)"),
            ("msg", "Middleware", "Controller", "Forward request", "call"),
            ("msg", "Controller", "Database", "Get all users", "call"),
            ("msg", "Database", "Controller", "Users list data", "reply"),
            ("msg", "Controller", "View", "Tampilkan index users dengan tombol Aksi", "reply"),
            ("alt_end",),
            ("msg", "Actor", "View", "Klik tombol Hapus Pengguna (Admin)", "call"),
            ("msg", "View", "Middleware", "DELETE /admin/users/{email}", "call"),
            ("msg", "Middleware", "Controller", "Forward request", "call"),
            ("msg", "Controller", "Database", "Delete user record", "call"),
            ("msg", "Database", "Controller", "Success", "reply"),
            ("msg", "Controller", "View", "Refresh & tampilkan pesan sukses", "reply")
        ]
    },
    "3_kelola_tamu": {
        "title": "3. Kelola Tamu (Guest Management)",
        "description": "Pengelolaan data tamu (Guest) penyewa (Admin kelola penuh, Pimpinan read-only, Petugas diblokir).",
        "participants": ["Actor", "View", "Middleware", "Controller", "Database"],
        "steps": [
            ("msg", "Actor", "View", "Buka Halaman Kelola Tamu", "call"),
            ("msg", "View", "Middleware", "GET /admin/tamu-management", "call"),
            ("alt_start", "Role == Pimpinan (2)"),
            ("msg", "Middleware", "Controller", "Forward request (Read-only)", "call"),
            ("msg", "Controller", "Database", "Get guest list", "call"),
            ("msg", "Database", "Controller", "Return data", "reply"),
            ("msg", "Controller", "View", "Tampilkan index (Tombol Tambah & Aksi hidden)", "reply"),
            ("alt_else", "Role == Admin (1)"),
            ("msg", "Middleware", "Controller", "Forward request (Full Access)", "call"),
            ("msg", "Controller", "Database", "Get guest list", "call"),
            ("msg", "Database", "Controller", "Return data", "reply"),
            ("msg", "Controller", "View", "Tampilkan index (Tombol Tambah & Aksi visible)", "reply"),
            ("alt_end",),
            ("msg", "Actor", "View", "Klik Tambah Tamu (Admin)", "call"),
            ("msg", "View", "Middleware", "POST /admin/tamu-management", "call"),
            ("alt_start", "Role == Pimpinan (2)"),
            ("msg", "Middleware", "View", "403 Forbidden (Blocked)", "reply"),
            ("alt_else", "Role == Admin (1)"),
            ("msg", "Middleware", "Controller", "Forward request", "call"),
            ("msg", "Controller", "Database", "Simpan data tamu", "call"),
            ("msg", "Database", "Controller", "Success", "reply"),
            ("msg", "Controller", "View", "Refresh list tamu", "reply"),
            ("alt_end",)
        ]
    },
    "4_kelola_gedung": {
        "title": "4. Kelola Gedung",
        "description": "Pengelolaan master data gedung (Admin kelola penuh, Pimpinan read-only, Petugas diblokir).",
        "participants": ["Actor", "View", "Middleware", "Controller", "Database"],
        "steps": [
            ("msg", "Actor", "View", "Buka Halaman Kelola Gedung", "call"),
            ("msg", "View", "Middleware", "GET /admin/gedung", "call"),
            ("alt_start", "Role == Pimpinan (2)"),
            ("msg", "Middleware", "Controller", "Forward request", "call"),
            ("msg", "Controller", "Database", "Get gedung list", "call"),
            ("msg", "Database", "Controller", "Return data", "reply"),
            ("msg", "Controller", "View", "Tampilkan index (Tombol Tambah & Aksi hidden)", "reply"),
            ("alt_else", "Role == Admin (1)"),
            ("msg", "Middleware", "Controller", "Forward request", "call"),
            ("msg", "Controller", "Database", "Get gedung list", "call"),
            ("msg", "Database", "Controller", "Return data", "reply"),
            ("msg", "Controller", "View", "Tampilkan index (Tombol Tambah & Aksi visible)", "reply"),
            ("alt_end",),
            ("msg", "Actor", "View", "Klik Tambah Gedung (Admin)", "call"),
            ("msg", "View", "Middleware", "POST /admin/gedung", "call"),
            ("alt_start", "Role == Pimpinan (2)"),
            ("msg", "Middleware", "View", "403 Forbidden (Blocked)", "reply"),
            ("alt_else", "Role == Admin (1)"),
            ("msg", "Middleware", "Controller", "Forward request", "call"),
            ("msg", "Controller", "Database", "Simpan data gedung", "call"),
            ("msg", "Database", "Controller", "Success", "reply"),
            ("msg", "Controller", "View", "Refresh list gedung", "reply"),
            ("alt_end",)
        ]
    },
    "5_kelola_ruangan": {
        "title": "5. Kelola Ruangan",
        "description": "Pengelolaan master data ruangan (Petugas kelola penuh, Pimpinan read-only, Admin diblokir).",
        "participants": ["Actor", "View", "Middleware", "Controller", "Database"],
        "steps": [
            ("msg", "Actor", "View", "Buka Halaman Kelola Ruangan", "call"),
            ("msg", "View", "Middleware", "GET /admin/ruangan", "call"),
            ("alt_start", "Role == Admin (1)"),
            ("msg", "Middleware", "View", "403 Forbidden (Blocked)", "reply"),
            ("alt_else", "Role == Pimpinan (2)"),
            ("msg", "Middleware", "Controller", "Forward request", "call"),
            ("msg", "Controller", "Database", "Get ruangan list", "call"),
            ("msg", "Database", "Controller", "Return data", "reply"),
            ("msg", "Controller", "View", "Tampilkan index (Tombol Tambah & Aksi hidden)", "reply"),
            ("alt_else", "Role == Petugas (3)"),
            ("msg", "Middleware", "Controller", "Forward request", "call"),
            ("msg", "Controller", "Database", "Get ruangan list", "call"),
            ("msg", "Database", "Controller", "Return data", "reply"),
            ("msg", "Controller", "View", "Tampilkan index (Tombol Tambah & Aksi visible)", "reply"),
            ("alt_end",)
        ]
    },
    "6_kelola_sarana": {
        "title": "6. Kelola Sarana & Prasarana",
        "description": "Pengelolaan master data sarana (Petugas kelola penuh, Pimpinan read-only, Admin diblokir).",
        "participants": ["Actor", "View", "Middleware", "Controller", "Database"],
        "steps": [
            ("msg", "Actor", "View", "Buka Halaman Kelola Sarana", "call"),
            ("msg", "View", "Middleware", "GET /admin/sarana", "call"),
            ("alt_start", "Role == Admin (1)"),
            ("msg", "Middleware", "View", "403 Forbidden (Blocked)", "reply"),
            ("alt_else", "Role == Pimpinan (2)"),
            ("msg", "Middleware", "Controller", "Forward request", "call"),
            ("msg", "Controller", "Database", "Get sarana list", "call"),
            ("msg", "Database", "Controller", "Return data", "reply"),
            ("msg", "Controller", "View", "Tampilkan index (Tombol Tambah & Aksi hidden)", "reply"),
            ("alt_else", "Role == Petugas (3)"),
            ("msg", "Middleware", "Controller", "Forward request", "call"),
            ("msg", "Controller", "Database", "Get sarana list", "call"),
            ("msg", "Database", "Controller", "Return data", "reply"),
            ("msg", "Controller", "View", "Tampilkan index (Tombol Tambah & Aksi visible)", "reply"),
            ("alt_end",)
        ]
    },
    "7_kelola_paket_ruangan": {
        "title": "7. Kelola Paket Ruangan",
        "description": "Pengelolaan paket sewa dan durasi sewa ruangan (Petugas kelola penuh, Pimpinan read-only, Admin diblokir).",
        "participants": ["Actor", "View", "Middleware", "Controller", "Database"],
        "steps": [
            ("msg", "Actor", "View", "Buka Halaman Kelola Paket", "call"),
            ("msg", "View", "Middleware", "GET /admin/paket-ruangan", "call"),
            ("alt_start", "Role == Admin (1)"),
            ("msg", "Middleware", "View", "403 Forbidden (Blocked)", "reply"),
            ("alt_else", "Role == Pimpinan (2)"),
            ("msg", "Middleware", "Controller", "Forward request", "call"),
            ("msg", "Controller", "Database", "Get paket list", "call"),
            ("msg", "Database", "Controller", "Return data", "reply"),
            ("msg", "Controller", "View", "Tampilkan index (Tombol Tambah & Aksi hidden)", "reply"),
            ("alt_else", "Role == Petugas (3)"),
            ("msg", "Middleware", "Controller", "Forward request", "call"),
            ("msg", "Controller", "Database", "Get paket list", "call"),
            ("msg", "Database", "Controller", "Return data", "reply"),
            ("msg", "Controller", "View", "Tampilkan index (Tombol Tambah & Aksi visible)", "reply"),
            ("alt_end",)
        ]
    },
    "8_pengajuan_peminjaman": {
        "title": "8. Pengajuan Peminjaman (Reservasi)",
        "description": "Alur pengajuan sewa ruangan dan sarana pendukung oleh Guest (Peminjam).",
        "participants": ["Actor (Peminjam)", "View", "Controller", "Database"],
        "steps": [
            ("msg", "Actor (Peminjam)", "View", "Isi Form Permohonan Peminjaman", "call"),
            ("msg", "View", "Controller", "Kirim Permohonan Peminjaman (POST)", "call"),
            ("msg", "Controller", "Database", "Cek ketersediaan Ruangan & Sarana", "call"),
            ("msg", "Database", "Controller", "Return status ketersediaan", "reply"),
            ("alt_start", "Fasilitas Tersedia"),
            ("msg", "Controller", "Database", "Simpan data peminjaman (status=PENDING)", "call"),
            ("msg", "Controller", "Database", "Simpan detail sarana yang dipinjam", "call"),
            ("msg", "Database", "Controller", "Success", "reply"),
            ("msg", "Controller", "View", "Tampilkan sukses (Menunggu Verifikasi)", "reply"),
            ("alt_else", "Fasilitas Terbooking / Stok Habis"),
            ("msg", "Controller", "View", "Tampilkan pesan error ketersediaan", "reply"),
            ("alt_end",)
        ]
    },
    "9_verifikasi_approval": {
        "title": "9. Verifikasi & Approval Peminjaman",
        "description": "Proses persetujuan atau penolakan pengajuan sewa oleh Admin atau Petugas.",
        "participants": ["Actor", "View", "Middleware", "Controller", "Database"],
        "steps": [
            ("msg", "Actor", "View", "Buka detail peminjaman pending", "call"),
            ("msg", "View", "Middleware", "GET /admin/transaksi/peminjaman/{id}", "call"),
            ("msg", "Middleware", "Controller", "Forward request", "call"),
            ("msg", "Controller", "Database", "Get peminjaman & detail sarana", "call"),
            ("msg", "Database", "Controller", "Return data", "reply"),
            ("msg", "Controller", "View", "Tampilkan detail peminjaman", "reply"),
            ("alt_start", "Role == Pimpinan (2)"),
            ("self", "View", "Sembunyikan tombol Setujui/Tolak"),
            ("alt_else", "Role == Admin (1) atau Petugas (3)"),
            ("msg", "Actor", "View", "Klik tombol Setujui", "call"),
            ("msg", "View", "Middleware", "POST /admin/transaksi/peminjaman/{id}/approve", "call"),
            ("msg", "Middleware", "Controller", "Forward request", "call"),
            ("msg", "Controller", "Database", "Update statusApproval=APPROVED & set tglApproval", "call"),
            ("msg", "Controller", "Database", "Auto-generate Invoice record (status=UNPAID)", "call"),
            ("msg", "Database", "Controller", "Success", "reply"),
            ("msg", "Controller", "View", "Redirect & tampilkan status APPROVED", "reply"),
            ("alt_end",)
        ]
    },
    "10_checkin_checkout": {
        "title": "10. Proses Check-In & Check-Out",
        "description": "Pemberian check-in tamu pada hari H dan check-out saat masa sewa berakhir.",
        "participants": ["Actor", "View", "Middleware", "Controller", "Database"],
        "steps": [
            ("msg", "Actor (Petugas)", "View", "Klik tombol Proses Check-In", "call"),
            ("msg", "View", "Middleware", "POST /admin/transaksi/peminjaman/{id}/check-in", "call"),
            ("msg", "Middleware", "Controller", "Forward request", "call"),
            ("msg", "Controller", "Database", "Update statusPeminjaman=CHECK_IN, checkIn=now()", "call"),
            ("msg", "Database", "Controller", "Success", "reply"),
            ("msg", "Controller", "View", "Refresh page & status menjadi CHECK_IN", "reply"),
            ("msg", "Actor (Petugas)", "View", "Tamu selesai, klik Check-Out & isi form kerusakan", "call"),
            ("msg", "View", "Middleware", "POST /admin/transaksi/peminjaman/{id}/check-out", "call"),
            ("msg", "Middleware", "Controller", "Forward request", "call"),
            ("msg", "Controller", "Database", "Update statusPeminjaman=CHECK_OUT, checkOut=now()", "call"),
            ("msg", "Controller", "Database", "Simpan kondisiReturn, denda, & biayaTambahan", "call"),
            ("msg", "Controller", "Database", "Update Invoice (tambah biaya denda ke totalHarga)", "call"),
            ("msg", "Database", "Controller", "Success", "reply"),
            ("msg", "Controller", "View", "Redirect & status menjadi CHECK_OUT", "reply")
        ]
    },
    "11_kelola_invoice": {
        "title": "11. Kelola & Cetak Invoice",
        "description": "Pembuatan invoice tagihan sewa dan update status pembayaran oleh Admin.",
        "participants": ["Actor", "View", "Middleware", "Controller", "Database"],
        "steps": [
            ("msg", "Actor", "View", "Buka halaman kelola invoice", "call"),
            ("msg", "View", "Middleware", "GET /admin/transaksi/invoice/{id}", "call"),
            ("msg", "Middleware", "Controller", "Forward request", "call"),
            ("msg", "Controller", "Database", "Get invoice & peminjaman details", "call"),
            ("msg", "Database", "Controller", "Return data", "reply"),
            ("msg", "Controller", "View", "Tampilkan Invoice Detail", "reply"),
            ("msg", "Actor (Admin)", "View", "Konfirmasi Pembayaran Lunas", "call"),
            ("msg", "View", "Middleware", "POST /admin/transaksi/invoice/{id}/pay", "call"),
            ("alt_start", "Role == Pimpinan (2)"),
            ("msg", "Middleware", "View", "403 Forbidden (Blocked)", "reply"),
            ("alt_else", "Role == Admin (1)"),
            ("msg", "Middleware", "Controller", "Forward request", "call"),
            ("msg", "Controller", "Database", "Update statusInvoice=PAID, status_pembayaran=LUNAS", "call"),
            ("msg", "Database", "Controller", "Success", "reply"),
            ("msg", "Controller", "View", "Tampilkan status PAID", "reply"),
            ("alt_end",)
        ]
    },
    "12_kelola_berita": {
        "title": "12. Kelola Berita",
        "description": "Penyusunan berita oleh Petugas (sebagai draft) dan penerbitan oleh Admin.",
        "participants": ["Actor", "View", "Middleware", "Controller", "Database"],
        "steps": [
            ("msg", "Actor (Petugas)", "View", "Submit berita baru", "call"),
            ("msg", "View", "Middleware", "POST /admin/berita", "call"),
            ("msg", "Middleware", "Controller", "Forward request", "call"),
            ("msg", "Controller", "Database", "Simpan berita dengan status=draft", "call"),
            ("msg", "Database", "Controller", "Success", "reply"),
            ("msg", "Controller", "View", "Tampilkan sukses (Disimpan sebagai Draft)", "reply"),
            ("msg", "Actor (Admin)", "View", "Review berita draft, klik Publikasikan", "call"),
            ("msg", "View", "Middleware", "POST /admin/berita/{id}/approve", "call"),
            ("msg", "Middleware", "Controller", "Forward request", "call"),
            ("msg", "Controller", "Database", "Update status=approved & set tanggal_publish", "call"),
            ("msg", "Database", "Controller", "Success", "reply"),
            ("msg", "Controller", "View", "Tampilkan status APPROVED (Terbit)", "reply")
        ]
    },
    "13_kelola_landing_page": {
        "title": "13. Kelola Landing Page",
        "description": "Pengaturan informasi profil, FAQ, dan galeri web publik (Hanya untuk Admin).",
        "participants": ["Actor", "View", "Middleware", "Controller", "Database"],
        "steps": [
            ("msg", "Actor", "View", "Buka kelola Landing Page", "call"),
            ("msg", "View", "Middleware", "GET /admin/landing-page/tentang", "call"),
            ("alt_start", "Role != Admin (1)"),
            ("msg", "Middleware", "View", "403 Forbidden (Blocked)", "reply"),
            ("alt_else", "Role == Admin (1)"),
            ("msg", "Middleware", "Controller", "Forward request", "call"),
            ("msg", "Controller", "Database", "Get landing page data", "call"),
            ("msg", "Database", "Controller", "Return data", "reply"),
            ("msg", "Controller", "View", "Tampilkan form edit landing page", "reply"),
            ("alt_end",)
        ]
    },
    "14_cetak_laporan": {
        "title": "14. Cetak Laporan",
        "description": "Cetak dan ekspor laporan peminjaman & penggunaan fasilitas (Admin & Pimpinan).",
        "participants": ["Actor", "View", "Middleware", "Controller", "Database"],
        "steps": [
            ("msg", "Actor", "View", "Buka Halaman Laporan & pilih filter tanggal", "call"),
            ("msg", "View", "Middleware", "GET /admin/laporan", "call"),
            ("alt_start", "Role == Petugas (3)"),
            ("msg", "Middleware", "View", "403 Forbidden (Blocked)", "reply"),
            ("alt_else", "Role in [Admin, Pimpinan]"),
            ("msg", "Middleware", "Controller", "Forward request", "call"),
            ("msg", "Controller", "Database", "Query aggregated peminjaman & usage data", "call"),
            ("msg", "Database", "Controller", "Return data", "reply"),
            ("msg", "Controller", "View", "Tampilkan Halaman Laporan dengan data & tombol Cetak", "reply"),
            ("alt_end",)
        ]
    }
}

# Theme details
COLORS = {
    "Actor": "#C9A961",           # Amber/Gold
    "Actor (Peminjam)": "#C9A961",
    "Actor (Petugas)": "#C9A961",
    "View": "#17A2B8",            # Teal
    "Middleware": "#6C757D",      # Gray
    "Controller": "#007BFF",      # Blue
    "Database": "#28A745"         # Green
}

def get_color(participant):
    for key, color in COLORS.items():
        if key in participant:
            return color
    return "#333333"

def generate_svg(name, data):
    title = data["title"]
    participants = data["participants"]
    steps = data["steps"]

    # Dimensions
    width = 960
    margin_x = 100
    n_part = len(participants)
    spacing_x = (width - 2 * margin_x) / (n_part - 1) if n_part > 1 else 0

    def get_x(p):
        try:
            idx = participants.index(p)
        except ValueError:
            # Match partially (e.g. Actor (Petugas) matches Actor)
            idx = 0
            for i, part in enumerate(participants):
                if p.split()[0] in part or part.split()[0] in p:
                    idx = i
                    break
        return margin_x + idx * spacing_x

    # First pass: calculate step heights and alt blocks
    y = 120
    rendered_steps = []
    alt_stack = []
    
    for step in steps:
        stype = step[0]
        if stype == "msg":
            _, p_from, p_to, label, mtype = step
            x1, x2 = get_x(p_from), get_x(p_to)
            rendered_steps.append({
                "type": "msg",
                "from": p_from, "to": p_to,
                "x1": x1, "x2": x2,
                "y": y,
                "label": label,
                "mtype": mtype
            })
            y += 50
        elif stype == "self":
            _, p, label = step
            x = get_x(p)
            rendered_steps.append({
                "type": "self",
                "x": x,
                "y": y,
                "label": label
            })
            y += 65
        elif stype == "alt_start":
            _, cond = step
            alt_stack.append({
                "y_start": y - 10,
                "cond": cond,
                "splits": [],
                "idx": len(rendered_steps)
            })
            y += 25
        elif stype == "alt_else":
            _, cond = step
            if alt_stack:
                alt_stack[-1]["splits"].append((y - 10, cond))
            y += 25
        elif stype == "alt_end":
            if alt_stack:
                alt = alt_stack.pop()
                y_end = y
                rendered_steps.append({
                    "type": "alt_block",
                    "y_start": alt["y_start"],
                    "y_end": y_end,
                    "cond": alt["cond"],
                    "splits": alt["splits"]
                })
                y += 15

    total_height = y + 80

    # Start SVG content
    svg = []
    svg.append(f'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {width} {total_height}" width="100%" height="100%">')
    
    # CSS Styling
    svg.append("""  <defs>
    <style>
      .title { font-family: 'Inter', system-ui, sans-serif; font-weight: bold; font-size: 18px; fill: #1e293b; }
      .participant-box { rx: 6px; ry: 6px; }
      .participant-text { font-family: 'Inter', system-ui, sans-serif; font-weight: 600; font-size: 13px; fill: #ffffff; text-anchor: middle; }
      .lifeline { stroke: #cbd5e1; stroke-width: 1.5; stroke-dasharray: 6,4; }
      .message-line { stroke-width: 2; }
      .message-text { font-family: 'Inter', system-ui, sans-serif; font-size: 12px; fill: #334155; }
      .self-msg-text { font-family: 'Inter', system-ui, sans-serif; font-size: 11px; fill: #334155; }
      .alt-box { fill: #f8fafc; fill-opacity: 0.5; stroke: #94a3b8; stroke-width: 1.5; stroke-dasharray: 4,4; rx: 4px; ry: 4px; }
      .alt-label { font-family: 'Inter', system-ui, sans-serif; font-weight: bold; font-size: 11px; fill: #475569; }
      .alt-cond { font-family: 'Inter', system-ui, sans-serif; font-style: italic; font-size: 11px; fill: #64748b; }
      .activation-bar { fill: #f1f5f9; stroke: #94a3b8; stroke-width: 1.5; width: 12px; }
    </style>
    <marker id="arrow" viewBox="0 0 10 10" refX="8" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-reverse">
      <path d="M 0 1 L 10 5 L 0 9 z" fill="#334155" />
    </marker>
    <marker id="reply-arrow" viewBox="0 0 10 10" refX="8" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-reverse">
      <path d="M 0 1 L 8 5 L 0 9" fill="none" stroke="#475569" stroke-width="2" />
    </marker>
  </defs>""")

    # Background
    svg.append(f'  <rect width="100%" height="100%" fill="#ffffff" />')

    # Draw Title
    svg.append(f'  <text x="40" y="45" class="title">{title}</text>')

    # Draw Lifelines (Dashed Lines)
    for p in participants:
        x = get_x(p)
        svg.append(f'  <line x1="{x}" y1="80" x2="{x}" y2="{total_height - 60}" class="lifeline" />')

    # Draw Top Participant Boxes
    for p in participants:
        x = get_x(p)
        color = get_color(p)
        svg.append(f'  <g transform="translate({x - 80}, 75)">')
        svg.append(f'    <rect x="0" y="0" width="160" height="35" class="participant-box" fill="{color}" />')
        svg.append(f'    <text x="80" y="22" class="participant-text">{p}</text>')
        svg.append(f'  </g>')

    # Draw Bottom Participant Boxes
    for p in participants:
        x = get_x(p)
        color = get_color(p)
        svg.append(f'  <g transform="translate({x - 80}, {total_height - 50})">')
        svg.append(f'    <rect x="0" y="0" width="160" height="35" class="participant-box" fill="{color}" />')
        svg.append(f'    <text x="80" y="22" class="participant-text">{p}</text>')
        svg.append(f'  </g>')

    # Draw Steps
    for s in rendered_steps:
        stype = s["type"]
        if stype == "msg":
            x1, x2, y_val = s["x1"], s["x2"], s["y"]
            label = s["label"]
            mtype = s["mtype"]
            
            # Line style & marker
            if mtype == "call":
                stroke_style = 'stroke="#334155" class="message-line"'
                marker = 'marker-end="url(#arrow)"'
            else:
                stroke_style = 'stroke="#475569" class="message-line" stroke-dasharray="5,5"'
                marker = 'marker-end="url(#reply-arrow)"'

            # Text alignment
            text_anchor = "middle"
            text_x = (x1 + x2) / 2
            
            svg.append(f'  <line x1="{x1}" y1="{y_val}" x2="{x2}" y2="{y_val}" {stroke_style} {marker} />')
            svg.append(f'  <text x="{text_x}" y="{y_val - 8}" text-anchor="{text_anchor}" class="message-text">{label}</text>')
            
        elif stype == "self":
            x, y_val = s["x"], s["y"]
            label = s["label"]
            # Draw self-call loop
            svg.append(f'  <path d="M {x} {y_val} L {x+35} {y_val} L {x+35} {y_val+40} L {x+10} {y_val+40}" fill="none" stroke="#334155" stroke-width="2" marker-end="url(#arrow)" />')
            svg.append(f'  <text x="{x + 42}" y="{y_val + 24}" text-anchor="start" class="self-msg-text">{label}</text>')

        elif stype == "alt_block":
            y_start, y_end = s["y_start"], s["y_end"]
            cond = s["cond"]
            splits = s["splits"]
            
            # Draw alternative container box
            x_min = margin_x - 30
            x_max = width - margin_x + 30
            svg.append(f'  <rect x="{x_min}" y="{y_start}" width="{x_max - x_min}" height="{y_end - y_start}" class="alt-box" />')
            
            # Alt tag
            svg.append(f'  <rect x="{x_min}" y="{y_start}" width="45" height="18" fill="#94a3b8" rx="2" ry="2" />')
            svg.append(f'  <text x="{x_min + 22}" y="{y_start + 13}" fill="#ffffff" font-family="Inter, sans-serif" font-weight="bold" font-size="10" text-anchor="middle">alt</text>')
            
            # Alt condition
            svg.append(f'  <text x="{x_min + 55}" y="{y_start + 14}" class="alt-cond">[{cond}]</text>')

            # Draw separators & split conditions
            for split_y, split_cond in splits:
                svg.append(f'  <line x1="{x_min}" y1="{split_y}" x2="{x_max}" y2="{split_y}" stroke="#94a3b8" stroke-dasharray="3,3" />')
                svg.append(f'  <text x="{x_min + 15}" y="{split_y + 16}" class="alt-cond">[{split_cond}]</text>')

    svg.append('</svg>')
    return "\n".join(svg)

# Main generator execution
if __name__ == "__main__":
    output_dir = "docs/sequence diagram"
    os.makedirs(output_dir, exist_ok=True)
    
    print("Generating 14 SVG Sequence Diagrams...")
    for filename, data in diagrams.items():
        svg_content = generate_svg(filename, data)
        filepath = os.path.join(output_dir, f"{filename}.svg")
        with open(filepath, "w", encoding="utf-8") as f:
            f.write(svg_content)
        print(f"Generated: {filepath}")

    print("Sequence Diagrams generation completed successfully.")
