<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Permohonan Buka Blokir - SIPRASA</title>
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
            background: #f0ad4e;
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
        .details-box {
            background: #f9f9f9;
            border: 1px solid #eee;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .details-box table {
            width: 100%;
            border-collapse: collapse;
        }
        .details-box td {
            padding: 6px 0;
            font-size: 14px;
        }
        .details-box td.label {
            font-weight: bold;
            width: 130px;
        }
        .reason-box {
            background: #fffdf5;
            border-left: 4px solid #f0ad4e;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
            font-style: italic;
        }
        .btn-admin {
            display: block;
            width: fit-content;
            background: #1a1a1a;
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
            <h1>Permohonan Buka Blokir Baru</h1>
        </div>
        <div class="content">
            <p>Halo Administrator,</p>
            <p>Seorang pengguna telah berhasil memverifikasi OTP dan mengirimkan permohonan pembukaan blokir akun. Berikut rinciannya:</p>
            
            <div class="details-box">
                <table>
                    <tr>
                        <td class="label">Username:</td>
                        <td>{{ $user->username }}</td>
                    </tr>
                    <tr>
                        <td class="label">Email:</td>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <td class="label">No. Telepon:</td>
                        <td>{{ $user->phone ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Tanggal Diajukan:</td>
                        <td>{{ date('d-m-Y H:i') }} WITA</td>
                    </tr>
                </table>
            </div>

            <h3>Catatan/Alasan Pengguna:</h3>
            <div class="reason-box">
                "{{ $reason }}"
            </div>

            <p>Silakan masuk ke halaman Pengguna Terblokir di panel admin untuk meninjau secara mendalam dan mengambil tindakan.</p>
            
            <a href="{{ route('main.users.blocked') }}" class="btn-admin">Masuk ke Dashboard Admin</a>
        </div>
        <div class="footer">
            <p>Email ini dikirim otomatis oleh Sistem SIPRASA Asrama Haji Landasan Ulin.</p>
            <p>&copy; {{ date('Y') }} UPT Asrama Haji Embarkasi Banjarmasin.</p>
        </div>
    </div>
</body>
</html>
