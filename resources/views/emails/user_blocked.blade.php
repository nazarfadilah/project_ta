<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Akun Ditangguhkan - SIPRASA</title>
    <style>
        body {
            font-family: 'Segoe UI', Helvetica, Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 550px;
            background: #ffffff;
            margin: 0 auto;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            border: 1px solid #eef2f5;
        }
        .header {
            background: #dc3545;
            color: #ffffff;
            padding: 25px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 600;
            letter-spacing: 1px;
        }
        .content {
            padding: 30px;
            line-height: 1.6;
        }
        .reason-box {
            background: #fff8f8;
            border-left: 4px solid #dc3545;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
            font-style: italic;
        }
        .btn-unblock {
            display: block;
            width: fit-content;
            background: #C9A961;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 5px;
            font-weight: bold;
            margin: 25px auto;
            text-align: center;
        }
        .footer {
            background: #fcfcfc;
            padding: 20px;
            text-align: center;
            font-size: 11px;
            color: #999;
            border-top: 1px solid #f4f4f4;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Pemberitahuan Akun Ditangguhkan</h1>
        </div>
        <div class="content">
            <p>Halo <strong>{{ $user->username }}</strong>,</p>
            <p>Kami memberitahukan bahwa akun Anda pada sistem SIPRASA saat ini telah <strong>ditangguhkan (di-blokir sementara)</strong> oleh administrator karena alasan berikut:</p>
            
            <div class="reason-box">
                "{{ $reason ?? 'Tidak ada alasan spesifik yang dicantumkan.' }}"
            </div>

            <p>Jika Anda merasa pemblokiran ini adalah kekeliruan atau ingin mengajukan permohonan pembukaan blokir kembali, silakan klik tombol di bawah ini:</p>
            
            <a href="{{ $unblockLink }}" class="btn-unblock">Ajukan Buka Blokir Akun</a>

            <p>Atau salin tautan berikut ke browser Anda:<br>
            <a href="{{ $unblockLink }}">{{ $unblockLink }}</a></p>
        </div>
        <div class="footer">
            <p>Email ini dikirim otomatis oleh Sistem SIPRASA Asrama Haji Landasan Ulin.</p>
            <p>&copy; {{ date('Y') }} UPT Asrama Haji Embarkasi Banjarmasin.</p>
        </div>
    </div>
</body>
</html>
