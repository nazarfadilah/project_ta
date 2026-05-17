<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIPRASA') — {{ $tentang_global->nama_instansi ?? 'SI-Prasa' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <style>
        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box
        }

        :root {
            --primary: #0f172a;
            --accent: #16a34a;
            --accent2: #15803d;
            --accent-light: #dcfce7;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --bg: #f8fafc;
            --radius: 12px;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, .1);
            --shadow-lg: 0 20px 40px -10px rgba(0, 0, 0, .15);
        }

        body {
            font-family: 'Outfit', sans-serif;
            color: var(--text-main);
            background: var(--bg);
            min-height: 100vh;
            display: flex;
            flex-direction: column
        }

        a {
            text-decoration: none;
            color: inherit
        }

        /* NAVBAR */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            background: rgba(255, 255, 255, .95);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border);
            transition: box-shadow .3s
        }

        .navbar.scrolled {
            box-shadow: var(--shadow)
        }

        .nav-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 72px
        }

        .logo {
            font-size: 1.4rem;
            font-weight: 900;
            color: var(--primary)
        }

        .logo span {
            color: var(--accent)
        }

        .logo-sub {
            font-size: 0.6rem;
            font-weight: 500;
            color: var(--text-muted);
            display: block;
            margin-top: -4px
        }

        .nav-links {
            display: flex;
            gap: 1.75rem;
            list-style: none
        }

        .nav-links a {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-muted);
            transition: color .2s;
            padding: .25rem 0;
            border-bottom: 2px solid transparent
        }

        .nav-links a:hover,
        .nav-links a.active {
            color: var(--accent);
            border-bottom-color: var(--accent)
        }

        .nav-actions {
            display: flex;
            gap: .75rem;
            align-items: center
        }

        .btn {
            padding: .65rem 1.5rem;
            border-radius: 10px;
            font-family: inherit;
            font-weight: 600;
            font-size: .875rem;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            transition: all .2s;
            text-decoration: none
        }

        .btn-primary {
            background: var(--accent);
            color: white
        }

        .btn-primary:hover {
            background: var(--accent2);
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(22, 163, 74, .3)
        }

        .btn-ghost {
            background: transparent;
            color: var(--text-muted)
        }

        .btn-ghost:hover {
            background: var(--bg)
        }

        .btn-outline {
            background: white;
            color: var(--accent);
            border: 1.5px solid var(--accent)
        }

        .btn-danger {
            background: var(--danger);
            color: white
        }

        .btn-warning {
            background: var(--warning);
            color: white
        }

        .btn-sm {
            padding: .45rem 1rem;
            font-size: .8rem
        }

        /* FLASH */
        .flash {
            padding: .9rem 1.5rem;
            border-radius: var(--radius);
            margin-bottom: 1.5rem;
            font-size: .9rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: .75rem
        }

        .flash-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0
        }

        .flash-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca
        }

        .flash-warning {
            background: #fef9c3;
            color: #854d0e;
            border: 1px solid #fde68a
        }

        /* CONTENT */
        main {
            flex: 1;
            padding-top: 72px
        }

        /* FOOTER */
        .footer {
            background: #020b05;
            padding: 60px 0 0;
            margin-top: auto
        }

        .footer-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 2rem
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 3rem;
            margin-bottom: 48px
        }

        .footer-brand .logo-f {
            font-size: 1.4rem;
            font-weight: 900;
            color: white;
            display: block;
            margin-bottom: .75rem
        }

        .footer-brand p {
            color: #4b5563;
            font-size: .875rem;
            line-height: 1.7
        }

        .footer-col h4 {
            color: white;
            font-weight: 700;
            margin-bottom: 1.25rem;
            font-size: .8rem;
            text-transform: uppercase;
            letter-spacing: 1px
        }

        .footer-col ul {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: .7rem
        }

        .footer-col ul li {
            color: #4b5563;
            font-size: .875rem
        }

        .footer-col ul li a {
            color: #4b5563;
            transition: color .2s
        }

        .footer-col ul li a:hover {
            color: white
        }

        .footer-bottom {
            border-top: 1px solid #0a1f0d;
            padding: 1.5rem 0;
            display: flex;
            justify-content: space-between
        }

        .footer-bottom p {
            color: #374151;
            font-size: .8rem
        }

        /* BADGE */
        .badge {
            padding: .25rem .75rem;
            border-radius: 20px;
            font-size: .75rem;
            font-weight: 700;
            display: inline-block
        }

        .badge-success {
            background: #dcfce7;
            color: #166534
        }

        .badge-warning {
            background: #fef9c3;
            color: #854d0e
        }

        .badge-danger {
            background: #fee2e2;
            color: #991b1b
        }

        .badge-info {
            background: #dbeafe;
            color: #1e40af
        }

        .badge-muted {
            background: #f1f5f9;
            color: #64748b
        }
    </style>
    @stack('styles')
</head>

<body>
    <nav class="navbar" id="navbar">
        <div class="nav-inner">
            <div>
                <a href="{{ route('home') }}" class="logo">SI<span>PRASA</span></a>
                <span
                    class="logo-sub">{{ $tentang_global->nama_instansi ?? 'Sistem Informasi Peminjaman Sarana' }}</span>
            </div>
            <ul class="nav-links">
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
                </li>
                <li><a href="{{ route('fasilitas') }}" class="{{ request()->routeIs('fasilitas') ? 'active' : '' }}">Fasilitas</a></li>
                <li><a href="{{ route('galeri') }}" class="{{ request()->routeIs('galeri') ? 'active' : '' }}">Galeri</a></li>
                <li><a href="{{ route('public.berita.index') }}" class="{{ request()->routeIs('public.berita.*') ? 'active' : '' }}">Berita</a></li>
                <li><a href="{{ route('reservasi') }}" class="{{ request()->routeIs('reservasi') ? 'active' : '' }}">Reservasi</a></li>
            </ul>
            <div class="nav-actions">
                @auth('web')
                    <a href="{{ route('user.dashboard') }}" class="btn btn-ghost">Dashboard</a>
                    <form method="POST" action="{{ route('user.logout') }}" style="display:inline">
                        @csrf
                        <button class="btn btn-primary" type="submit">Keluar</button>
                    </form>
                @elseauth('admin')
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-ghost">Admin Panel</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-ghost">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    <main>
        @if(session('success'))
            <div class="container" style="max-width:1280px;margin:0 auto;padding:1rem 2rem 0">
                <div class="flash flash-success">✅ {{ session('success') }}</div>
            </div>
        @endif
        @if(session('error'))
            <div class="container" style="max-width:1280px;margin:0 auto;padding:1rem 2rem 0">
                <div class="flash flash-error">❌ {{ session('error') }}</div>
            </div>
        @endif
        @yield('content')
    </main>

    <footer class="footer">
        <div class="footer-inner">
            <div class="footer-grid">
                <div class="footer-brand">
                    <span class="logo-f">SI<span style="color:var(--accent)">PRASA</span></span>
                    <p>Sistem Informasi Peminjaman Ruangan & Sarana<br>{{ $tentang_global->nama_instansi ?? 'SIPRASA' }}
                    </p>
                </div>
                <div class="footer-col">
                    <h4>Menu Utama</h4>
                    <ul>
                        <li><a href="{{ route('home') }}">Beranda</a></li>
                        <li><a href="{{ route('fasilitas') }}">Fasilitas</a></li>
                        <li><a href="{{ route('galeri') }}">Galeri</a></li>
                        <li><a href="{{ route('public.berita.index') }}">Berita</a></li>
                        <li><a href="{{ route('reservasi') }}">Reservasi</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Link Lainnya</h4>
                    <ul>
                        <li><a href="{{ route('tentang') }}">Tentang Kami</a></li>
                        <li><a href="{{ route('faq') }}">FAQ</a></li>
                        <li><a href="{{ route('privacy') }}">Privasi</a></li>
                        <li><a href="{{ route('terms') }}">Syarat & Ketentuan</a></li>
                        <li><a href="{{ route('kontak.kami') }}">Kontak Kami</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} SIPRASA — {{ $tentang_global->nama_instansi ?? 'SIPRASA' }}</p>
                <p>{{ $tentang_global->kantor ?? 'Kalimantan Selatan' }}</p>
            </div>
        </div>
    </footer>
    <script>
        window.addEventListener('scroll', () => document.getElementById('navbar')?.classList.toggle('scrolled', scrollY > 30));
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script src="{{ asset('js/landing.js') }}"></script>
    @stack('scripts')
</body>

</html>