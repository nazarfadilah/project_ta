<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIPRASA</title>
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
        .features-list {
            width: 100%;
            margin-top: 40px;
        }
        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .feature-item::before {
            content: "●";
            color: #C9A961;
            font-size: 16px;
            margin-right: 12px;
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
        .login-card p {
            color: #999;
            font-size: 14px;
            margin-bottom: 30px;
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
        .btn-login:hover:not(:disabled) {
            background-color: #333;
        }
        .btn-login:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
        .btn-google {
            width: 100%;
            padding: 12px;
            background-color: white;
            color: #333;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-google:hover {
            background-color: #f9f9f9;
            border-color: #ccc;
        }
        .captcha-box {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 12px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .captcha-display {
            font-size: 28px;
            font-weight: bold;
            color: #1a1a1a;
            min-width: 120px;
            text-align: center;
            background: white;
            padding: 8px 12px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .captcha-refresh {
            background-color: #C9A961;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 8px 12px;
            cursor: pointer;
            font-size: 12px;
            margin-left: 8px;
        }
        .captcha-refresh:hover {
            background-color: #a8884a;
        }
        .forgot-password {
            text-align: right;
            margin-top: 15px;
        }
        .forgot-password a {
            font-size: 12px;
            color: #C9A961;
            text-decoration: none;
        }
        .forgot-password a:hover {
            text-decoration: underline;
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
        .divider {
            display: flex;
            align-items: center;
            margin: 20px 0;
            color: #999;
        }
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #ddd;
        }
        .divider span {
            margin: 0 10px;
            font-size: 12px;
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
                <h3>Masuk ke Sistem</h3>

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

                <form action="{{ route('login.process') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="login">Email atau Username</label>
                        <input type="text" class="form-control @error('login') is-invalid @enderror" id="login" name="login" value="{{ old('login') }}" placeholder="Email atau username" required>
                        @error('login')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="••••••••" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Verifikasi Captcha</label>
                        <div class="captcha-box">
                            <div class="captcha-display" id="captchaDisplay">0000</div>
                            <button type="button" class="captcha-refresh" onclick="refreshCaptcha()">Refresh</button>
                        </div>
                        <input type="hidden" id="captcha" name="captcha">
                        <input type="number" class="form-control @error('captcha_value') is-invalid @enderror" id="captcha_value" name="captcha_value" placeholder="Masukkan angka di atas" required>
                        @error('captcha_value')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="forgot-password">
                        <a href="#">Lupa password?</a>
                    </div>

                    <button type="submit" class="btn-login" id="loginBtn" disabled>Masuk</button>

                    <div class="divider"><span>atau</span></div>

                    <button type="button" class="btn-google" onclick="loginWithGoogle()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="1"></circle>
                            <path d="M12 1v6m0 6v6"></path>
                            <path d="M4.22 4.22l4.24 4.24m6.08 0l4.24-4.24"></path>
                            <path d="M1 12h6m6 0h6"></path>
                            <path d="M4.22 19.78l4.24-4.24m6.08 0l4.24 4.24"></path>
                        </svg>
                        Masuk dengan Google
                    </button>

                    <div class="register-section">
                        <p>Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>
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
    
    <script>
        let currentCaptcha = null;

        // Initialize captcha on page load
        document.addEventListener('DOMContentLoaded', function() {
            refreshCaptcha();
        });

        function refreshCaptcha() {
            fetch('{{ route("captcha.generate") }}')
                .then(response => response.json())
                .then(data => {
                    currentCaptcha = data.captcha;
                    document.getElementById('captcha').value = data.captcha;
                    document.getElementById('captchaDisplay').textContent = String(data.captcha).padStart(4, '0');
                    document.getElementById('captcha_value').value = '';
                    checkCaptchaInput();
                })
                .catch(error => console.error('Error:', error));
        }

        function checkCaptchaInput() {
            const captchaValue = document.getElementById('captcha_value').value;
            const loginBtn = document.getElementById('loginBtn');
            
            if (captchaValue.length > 0) {
                loginBtn.disabled = false;
            } else {
                loginBtn.disabled = true;
            }
        }

        function loginWithGoogle() {
            window.location.href = '{{ route("login.google") }}';
        }

        // Add event listener to captcha input
        document.getElementById('captcha_value').addEventListener('input', checkCaptchaInput);

        // Validate captcha before form submission
        document.querySelector('form').addEventListener('submit', function(e) {
            const captchaInput = document.getElementById('captcha_value').value;
            if (captchaInput !== String(currentCaptcha)) {
                e.preventDefault();
                alert('Captcha tidak sesuai. Silakan coba lagi.');
                return false;
            }
        });
    </script>
</body>
</html>
