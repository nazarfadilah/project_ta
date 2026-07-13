<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error {{ $status ?? 'Error' }} - SIPRASA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --gold-primary: #C9A961;
            --gold-dark: #B8953F;
            --gold-light: #E5D5B2;
            --gold-bg: #FCF9F2;
            --text-dark: #2D3748;
            --text-gray: #718096;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(circle at top right, rgba(201, 169, 97, 0.08) 0%, rgba(255, 255, 255, 1) 70%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-dark);
            padding: 20px;
        }

        .error-container {
            max-width: 550px;
            width: 100%;
            text-align: center;
        }

        .error-card {
            background: #ffffff;
            border: 1px solid rgba(201, 169, 97, 0.15);
            border-radius: 24px;
            box-shadow: 0 15px 35px rgba(201, 169, 97, 0.08), 0 5px 15px rgba(0, 0, 0, 0.02);
            padding: 50px 40px;
            position: relative;
            overflow: hidden;
        }

        .error-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, var(--gold-light) 0%, var(--gold-primary) 50%, var(--gold-dark) 100%);
        }

        .error-badge {
            font-family: 'Outfit', sans-serif;
            font-size: 80px;
            font-weight: 800;
            line-height: 1;
            background: linear-gradient(135deg, var(--gold-dark) 0%, var(--gold-primary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 15px;
            letter-spacing: -2px;
        }

        .error-icon-wrapper {
            width: 100px;
            height: 100px;
            background-color: var(--gold-bg);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px auto;
            border: 1px solid rgba(201, 169, 97, 0.2);
            color: var(--gold-primary);
            font-size: 40px;
            position: relative;
            animation: float 4s ease-in-out infinite;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .error-title {
            font-family: 'Outfit', sans-serif;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 15px;
            color: var(--text-dark);
        }

        .error-desc {
            font-size: 15px;
            color: var(--text-gray);
            line-height: 1.6;
            margin-bottom: 35px;
        }

        .btn-gold {
            background: linear-gradient(135deg, #C9A961 0%, #B89750 100%);
            border: none;
            color: #ffffff;
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(201, 169, 97, 0.2);
            transition: all 0.3s ease;
            font-size: 14.5px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(201, 169, 97, 0.3);
            color: #ffffff;
        }

        .btn-outline-gold {
            border: 2px solid var(--gold-primary);
            background: transparent;
            color: var(--gold-dark);
            font-weight: 600;
            padding: 10px 28px;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-size: 14.5px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-outline-gold:hover {
            background-color: var(--gold-bg);
            color: var(--gold-dark);
            transform: translateY(-2px);
        }

        .btn-danger-custom {
            background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
            border: none;
            color: #ffffff;
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(229, 62, 62, 0.2);
            transition: all 0.3s ease;
            font-size: 14.5px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-danger-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(229, 62, 62, 0.3);
            color: #ffffff;
        }

        .app-brand {
            margin-top: 30px;
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 14px;
            letter-spacing: 1px;
            color: var(--text-gray);
            opacity: 0.6;
            text-transform: uppercase;
        }
    </style>
</head>
<body>

<div class="error-container">
    <div class="error-card">
        
        {{-- Icon & Badge based on Status --}}
        @php
            $icon = 'fa-circle-exclamation';
            $title = 'Terjadi Kesalahan';
            $desc = $message ?? 'Ada kendala internal pada server. Silakan coba kembali.';
            
            if (str_contains($status, '403') || str_contains($status, '404')) {
                $icon = 'fa-compass';
                $title = 'Halaman Tidak Ditemukan';
            } elseif ($status == 419) {
                $icon = 'fa-key';
                $title = 'Sesi Berakhir / Token CSRF Kedaluwarsa';
            }
        @endphp

        <div class="error-icon-wrapper">
            <i class="fas {{ $icon }}"></i>
        </div>

        <div class="error-badge">
            {{ $status ?? '500' }}
        </div>

        <h3 class="error-title">{{ $title }}</h3>
        <p class="error-desc">{{ $desc }}</p>

        <div class="d-flex flex-wrap justify-content-center gap-3">
            @if($status == 419)
                {{-- Form Logout untuk Sesi Kadaluarsa --}}
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    {{-- Form ini lolos dari CSRF check via middleware --}}
                    <button type="submit" class="btn btn-danger-custom">
                        <i class="fas fa-sign-out-alt"></i> Keluar & Bersihkan Sesi
                    </button>
                </form>
                <a href="{{ route('login') }}" class="btn btn-outline-gold">
                    <i class="fas fa-sign-in-alt"></i> Login Kembali
                </a>
            @else
                {{-- Opsi Tombol 403, 404, 500 --}}
                @if(Auth::check())
                    <a href="{{ route('dashboard') }}" class="btn btn-gold">
                        <i class="fas fa-house"></i> Kembali ke Dashboard
                    </a>
                @else
                    <a href="{{ route('home') }}" class="btn btn-gold">
                        <i class="fas fa-house"></i> Halaman Utama
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-outline-gold">
                        <i class="fas fa-sign-in-alt"></i> Halaman Login
                    </a>
                @endif
            @endif
        </div>
    </div>
    
    <div class="app-brand">
        <i class="fas fa-shield-halved me-1"></i> Sistem Keamanan SIPRASA
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
