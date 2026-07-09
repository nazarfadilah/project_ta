<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Status Pengajuan Buka Blokir - SIPRASA</title>
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
            background: {{ $status === 'APPROVED' ? '#4bb543' : '#d9534f' }};
            color: #ffffff;
            padding: 25px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
            letter-spacing: 1px;
        }
        .content {
            padding: 30px;
            line-height: 1.6;
        }
        .btn-login {
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
        .info-box {
            background: #f9f9f9;
            border: 1px solid #eee;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
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
            <h1>Hasil Pengajuan Buka Blokir Akun</h1>
        </div>
        <div class="content">
            <p>Halo <strong>{{ $user->username }}</strong>,</p>
            
            @if($status === 'APPROVED')
                <p>Kami dengan senang hati memberitahukan bahwa permohonan buka blokir akun Anda telah <strong>DISETUJUI</strong> oleh administrator.</p>
                <p>Status akun Anda kini telah <strong>Aktif</strong> kembali. Password Anda telah di-reset ke nilai default demi keamanan.</p>
                
                <div class="info-box">
                    <strong>Password Baru Anda (Default):</strong> <span style="font-family: monospace; font-size: 16px; color: #d9534f;">password</span><br>
                    <small style="color: #777; display: block; margin-top: 5px;">*Demi keamanan akun Anda, silakan segera ubah password Anda di halaman profil setelah berhasil login.</small>
                </div>

                <a href="{{ route('login') }}" class="btn-login">Login Sekarang</a>
            @else
                <p>Kami memberitahukan bahwa permohonan buka blokir akun Anda telah <strong>DITOLAK</strong> oleh administrator.</p>
                <p>Status akun Anda di-set menjadi <strong>Di Blokir Secara Permanen</strong>. Anda tidak diperkenankan lagi mengajukan pembukaan blokir di masa mendatang.</p>
                <p>Jika Anda merasa memiliki pertanyaan mendesak, silakan hubungi administrator via WhatsApp / kontak resmi kami.</p>
            @endif
        </div>
        <div class="footer">
            <p>Email ini dikirim otomatis oleh Sistem SIPRASA Asrama Haji Landasan Ulin.</p>
            <p>&copy; {{ date('Y') }} UPT Asrama Haji Embarkasi Banjarmasin.</p>
        </div>
    </div>
</body>
</html>
