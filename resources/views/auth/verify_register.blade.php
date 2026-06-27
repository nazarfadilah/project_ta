<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - SIPRASA</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/image/icon.png') }}">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            min-height: 100vh;
        }
        .login-wrapper {
            display: flex;
            min-height: 100vh;
        }
        .login-left {
            width: 40%;
            background: linear-gradient(135deg, #C9A961 0%, #ab8b46 100%);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }
        .login-right {
            width: 60%;
            background-color: #f5f5f5;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px 40px;
            overflow-y: auto;
        }
        .logo-box {
            border: 2px solid white;
            padding: 30px;
            border-radius: 8px;
            margin-bottom: 50px;
            text-align: center;
            background-color: #000000;
        }
        .logo-box h2 {
            font-size: 36px;
            font-weight: bold;
            letter-spacing: 3px;
            margin-bottom: 10px;
        }
        .logo-box p {
            font-size: 12px;
            color: #ccc;
        }
        .login-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 35px 25px;
            width: 100%;
            max-width: 480px;
            text-align: center;
        }
        .login-card h3 {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #333;
        }
        .login-card p.sub {
            color: #666;
            font-size: 14px;
            margin-bottom: 25px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .form-control {
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            text-align: center;
            letter-spacing: 4px;
            font-weight: bold;
        }
        .form-control:focus {
            border-color: #C9A961;
            box-shadow: 0 0 0 0.2rem rgba(201, 169, 97, 0.25);
        }
        .btn-login {
            width: 100%;
            padding: 12px;
            background-color: #1a1a1a;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s;
        }
        .btn-login:hover {
            background-color: #333;
        }
        .contact-info {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #999;
        }
        .contact-info a {
            color: #C9A961;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-left">
            <div class="logo-box">
                <h2>SIPRASA</h2>
                <p>Sistem Informasi Peminjaman Ruangan & Sarana</p>
            </div>
            <p style="font-size: 14px; text-align: center; line-height: 1.6; max-width: 300px; color: rgba(255,255,255,0.85);">
                Dapatkan kemudahan proses peminjaman tempat dan sarana di Asrama Haji Landasan Ulin dengan cepat dan transparan.
            </p>
        </div>
        <div class="login-right">
            <div class="login-card">
                <h3>Verifikasi Akun</h3>
                <p class="sub">Kami telah mengirimkan 6-digit kode verifikasi ke alamat email <strong>{{ session('register_data.email') }}</strong>. Masukkan kode tersebut di bawah ini:</p>

                @if($errors->any())
                    <div class="alert alert-danger mb-4 text-start">
                        <ul class="mb-0 ps-3" style="font-size: 13px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success mb-4 text-start" style="font-size: 13px;">
                        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('register.verify.process') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="code">Kode Verifikasi (OTP)</label>
                        <input type="text" name="code" id="code" class="form-control" placeholder="******" maxlength="6" required autocomplete="off" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </div>

                    <button type="submit" class="btn-login">Verifikasi & Buat Akun</button>
                </form>

                <div class="mt-4 pt-2">
                    <span class="text-muted small">Tidak menerima kode?</span>
                    <form id="resend-form" action="{{ route('register.verify.resend') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" id="btn-resend" class="btn btn-link p-0 text-decoration-none small" style="color: #C9A961; font-weight: 600; font-size: 13px; display: none;">Kirim Ulang Kode</button>
                    </form>
                    <span id="countdown-text" class="small text-muted" style="font-weight: 600;">Kirim ulang dalam <span id="timer">60</span> detik</span>
                </div>
            </div>

            <div class="contact-info">
                Butuh bantuan? Hubungi Admin SIPRASA via
                @if(!empty($whatsapp))
                    <a href="https://wa.me/{{ $whatsapp }}" target="_blank"><i class="fab fa-whatsapp"></i> WhatsApp</a>
                @else
                    WhatsApp
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let timeLeft = 60;
            const timerEl = document.getElementById('timer');
            const countdownEl = document.getElementById('countdown-text');
            const resendBtn = document.getElementById('btn-resend');

            const countdownInterval = setInterval(function() {
                timeLeft--;
                timerEl.textContent = timeLeft;

                if (timeLeft <= 0) {
                    clearInterval(countdownInterval);
                    countdownEl.style.display = 'none';
                    resendBtn.style.display = 'inline-block';
                }
            }, 1000);
        });
    </script>
</body>
</html>
