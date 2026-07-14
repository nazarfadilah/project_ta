<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pengingat Check-In Reservasi - SIPRASA</title>
    <style>
        body {
            font-family: 'Segoe UI', Helvetica, Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            background: #ffffff;
            margin: 0 auto;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            border: 1px solid #eef2f5;
        }
        .header {
            background: #e67e22; /* Warm orange color for reminder */
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
            letter-spacing: 2px;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 13px;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
            line-height: 1.6;
        }
        .info-box {
            background-color: #fcf8e3;
            border: 1px solid #faebcc;
            color: #8a6d3b;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .details-table th, .details-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #f0f0f0;
        }
        .details-table th {
            font-weight: 600;
            color: #666;
            width: 35%;
        }
        .footer {
            background: #fcfcfc;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #f4f4f4;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>PENGINGAT CHECK-IN</h1>
            <p>Sistem Informasi Peminjaman Sarana & Prasarana</p>
        </div>
        <div class="content">
            <p>Halo, <strong>{{ $peminjaman->guest->name ?? 'Pengguna' }}</strong>.</p>
            <p>Ini adalah email pengingat otomatis bahwa reservasi Anda dengan kode peminjaman <strong>{{ $peminjaman->kodePeminjaman }}</strong> dijadwalkan akan segera dimulai.</p>
            
            <div class="info-box">
                <strong>PENTING:</strong> Sesuai ketentuan operasional, Anda diwajibkan untuk melakukan <strong>Check-In di lokasi minimal 1 jam sebelum jam peminjaman dimulai</strong>.
            </div>

            <table class="details-table">
                <tr>
                    <th>Kode Peminjaman</th>
                    <td><strong>{{ $peminjaman->kodePeminjaman }}</strong></td>
                </tr>
                <tr>
                    <th>Ruangan / Fasilitas</th>
                    <td>{{ $peminjaman->paketRuangan->ruangan->nama_ruangan ?? '-' }} ({{ $peminjaman->paketRuangan->nama ?? '-' }})</td>
                </tr>
                <tr>
                    <th>Tanggal Acara</th>
                    <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal)->format('d F Y') }}</td>
                </tr>
                <tr>
                    <th>Waktu Mulai</th>
                    <td><strong>{{ \Carbon\Carbon::parse($peminjaman->jamMulai)->format('H:i') }} WIB</strong></td>
                </tr>
                <tr>
                    <th>Waktu Check-In Terbuka</th>
                    <td>{{ \Carbon\Carbon::parse($peminjaman->jamMulai)->subHour()->format('H:i') }} WIB (1 jam sebelumnya)</td>
                </tr>
                <tr>
                    <th>Durasi Sewa</th>
                    <td>{{ $peminjaman->durasi }} Jam</td>
                </tr>
            </table>

            <p>Harap pastikan Anda sudah berada di lokasi Asrama Haji Landasan Ulin pada waktu check-in di atas untuk melakukan verifikasi fisik bersama petugas kami.</p>
            
            <p>Terima kasih atas kerja sama Anda.</p>
        </div>
        <div class="footer">
            <p>Email ini dikirim otomatis oleh Sistem SIPRASA Asrama Haji Landasan Ulin.</p>
            <p>&copy; {{ date('Y') }} UPT Asrama Haji Embarkasi Banjarmasin.</p>
        </div>
    </div>
</body>
</html>
