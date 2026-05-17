<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - SIPRASA</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
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
            padding: 25px;
            width: 100%;
            max-width: 480px;
        }
        .login-card h3 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        .form-control {
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
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
        .register-section {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .register-section p {
            font-size: 13px;
            color: #666;
        }
        .register-section a {
            color: #C9A961;
            text-decoration: none;
            font-weight: 600;
        }
        .register-section a:hover {
            text-decoration: underline;
        }
        .alert {
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 13px;
        }
        @media (max-width: 768px) {
            .login-wrapper {
                flex-direction: column;
            }
            .login-left {
                width: 100%;
                height: 40%;
            }
            .login-right {
                width: 100%;
                height: 60%;
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <!-- Left Section -->
        <div class="login-left">
            <div class="logo-box">
                <h2>SIPRASA</h2>
                <p>Sistem Informasi Peminjaman<br>Ruangan & Sarana</p>
            </div>
        </div>

        <!-- Right Section -->
        <div class="login-right">
            <div class="login-card">
                <h3>Daftar Akun</h3>

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ $errors->first() }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('register.process') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" placeholder="Masukkan username" required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Alamat Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="contoh@email.com" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone">Nomor Telepon (Opsional)</label>
                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Contoh: 081234567890">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Minimal 6 karakter" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi password Anda" required>
                    </div>

                    <button type="submit" class="btn-login">Daftar</button>

                    <div class="register-section">
                        <p>Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></p>
                        <a href="{{ route('home') }}" style="display: block; margin-top: 10px; font-size: 12px;">&larr; Kembali ke Beranda</a>
                        @if($whatsapp)
                            <a href="https://wa.me/{{ str_replace(['+', '-', ' '], '', $whatsapp) }}" target="_blank" style="display: block; margin-top: 8px; font-size: 12px; color: #25D366; text-decoration: none;">💬 Hubungi Admin via WhatsApp</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>