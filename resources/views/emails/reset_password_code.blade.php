<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kode Reset Password - SIPRASA</title>
    <style>
        body {
            font-family: 'Segoe UI', Helvetica, Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 500px;
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
            padding: 25px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 600;
            letter-spacing: 2px;
        }
        .content {
            padding: 30px;
            line-height: 1.6;
            text-align: center;
        }
        .code-display {
            font-size: 36px;
            font-weight: bold;
            letter-spacing: 5px;
            color: #C9A961;
            background: #f9f9f9;
            border: 1px dashed #ddd;
            padding: 15px;
            border-radius: 6px;
            margin: 25px auto;
            width: fit-content;
        }
        .info-note {
            font-size: 13px;
            color: #777;
            margin-top: 20px;
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
            <h1>RESET PASSWORD</h1>
        </div>
        <div class="content">
            <p>Halo,</p>
            <p>Kami menerima permintaan untuk mereset password akun SIPRASA Anda. Gunakan kode verifikasi di bawah ini untuk memproses pengaturan ulang password:</p>
            
            <div class="code-display">{{ $code }}</div>

            <p class="info-note">Kode verifikasi ini berlaku selama 15 menit. Jika Anda tidak merasa melakukan permintaan ini, silakan abaikan email ini dan password Anda akan tetap aman.</p>
        </div>
        <div class="footer">
            <p>Email ini dikirim otomatis oleh Sistem SIPRASA Asrama Haji Landasan Ulin.</p>
            <p>&copy; {{ date('Y') }} UPT Asrama Haji Embarkasi Banjarmasin.</p>
        </div>
    </div>
</body>
</html>
