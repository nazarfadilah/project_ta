<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Notifikasi Status Reservasi - SIPRASA</title>
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
            background: #1a1a1a;
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
            color: #C9A961;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .content {
            padding: 30px;
            line-height: 1.6;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            font-size: 14px;
            font-weight: bold;
            border-radius: 20px;
            margin-bottom: 20px;
            text-align: center;
        }
        .status-APPROVED {
            background-color: #d4edda;
            color: #155724;
        }
        .status-REJECTED {
            background-color: #f8d7da;
            color: #721c24;
        }
        .status-UPDATED {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-PENDING {
            background-color: #cce5ff;
            color: #004085;
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
            <h1>SIPRASA</h1>
            <p>Sistem Informasi Peminjaman Sarana & Prasarana</p>
        </div>
        <div class="content">
            <p>Halo, <strong>{{ $peminjaman->guest->name ?? 'Pengguna' }}</strong>.</p>
            <p>Kami ingin menginformasikan status terbaru mengenai pengajuan reservasi ruangan/sarana Anda:</p>
            
            <div style="text-align: center;">
                <span class="status-badge status-{{ $statusType }}">
                    @if ($statusType === 'APPROVED')
                        RESERVASI DISETUJUI
                    @elseif ($statusType === 'REJECTED')
                        RESERVASI DITOLAK
                    @elseif ($statusType === 'PENDING')
                        RESERVASI BERHASIL DIAJUKAN
                    @else
                        DATA RESERVASI DIPERBARUI
                    @endif
                </span>
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
                    <td>{{ \Carbon\Carbon::parse($peminjaman->jamMulai)->format('H:i') }} WIB</td>
                </tr>
                <tr>
                    <th>Durasi Sewa</th>
                    <td>{{ $peminjaman->durasi }} Jam</td>
                </tr>
                @if ($peminjaman->catatanApproval)
                <tr>
                    <th>Catatan Admin</th>
                    <td><em>"{{ $peminjaman->catatanApproval }}"</em></td>
                </tr>
                @endif
                <tr>
                    <th>Harga Paket</th>
                    <td>Rp {{ number_format($peminjaman->paketRuangan->harga ?? 0, 0, ',', '.') }}</td>
                </tr>
                @if ($peminjaman->biayaTambahan > 0)
                <tr>
                    <th>Biaya Tambahan</th>
                    <td>Rp {{ number_format($peminjaman->biayaTambahan, 0, ',', '.') }}</td>
                </tr>
                @endif
                <tr style="border-top: 2px solid #ddd;">
                    <th>Total Pembayaran</th>
                    <td><strong>Rp {{ number_format(($peminjaman->paketRuangan->harga ?? 0) + $peminjaman->biayaTambahan, 0, ',', '.') }}</strong></td>
                </tr>
            </table>

            @if ($statusType === 'APPROVED')
                <p>Silakan melakukan pembayaran tagihan Anda jika ada, dan bersiap untuk melakukan <strong>Check-In di lokasi maksimal 1 jam sebelum acara dimulai</strong>.</p>
            @elseif ($statusType === 'REJECTED')
                <p>Mohon maaf, Anda belum dapat menggunakan fasilitas kami pada jadwal tersebut. Silakan lakukan pengajuan reservasi ulang dengan jadwal atau ruangan alternatif lainnya.</p>
            @elseif ($statusType === 'PENDING')
                <p>Reservasi Anda berhasil kami terima dan saat ini sedang menunggu persetujuan dari pihak administrator. Kami akan mengirimkan email notifikasi lain setelah status reservasi Anda diperbarui.</p>
            @else
                <p>Perubahan rincian reservasi Anda telah berhasil disimpan oleh administrator. Harap perhatikan jadwal terupdate di atas.</p>
            @endif

            <p>Jika Anda memiliki pertanyaan lebih lanjut, silakan hubungi pengelola Asrama Haji Landasan Ulin.</p>
        </div>
        <div class="footer">
            <p>Email ini dikirim otomatis oleh Sistem SIPRASA Asrama Haji Landasan Ulin.</p>
            <p>&copy; {{ date('Y') }} UPT Asrama Haji Embarkasi Banjarmasin.</p>
        </div>
    </div>
</body>
</html>
