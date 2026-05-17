<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — SIPRASA</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
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
            --danger: #ef4444;
            --warning: #f59e0b;
            --success: #10b981;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --bg: #f8fafc;
            --radius: 12px;
            --sidebar-w: 240px;
        }

        body {
            font-family: 'Outfit', sans-serif;
            color: var(--text-main);
            background: var(--bg);
            display: flex;
            min-height: 100vh
        }

        a {
            text-decoration: none;
            color: inherit
        }

        /* SIDEBAR User */
        .sidebar {
            width: var(--sidebar-w);
            background: white;
            min-height: 100vh;
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 50
        }

        .sidebar-brand {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border)
        }

        .sidebar-logo {
            font-size: 1.25rem;
            font-weight: 900;
            color: var(--primary)
        }

        .sidebar-logo span {
            color: var(--accent)
        }

        .sidebar-sub {
            font-size: .6rem;
            color: var(--text-muted);
            margin-top: .2rem
        }

        .sidebar-nav {
            padding: .75rem 0;
            flex: 1
        }

        .sidebar-section {
            padding: .5rem 1.25rem;
            font-size: .65rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: .5rem
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .7rem 1.5rem;
            color: var(--text-muted);
            font-size: .875rem;
            font-weight: 500;
            transition: all .2s;
            border-left: 3px solid transparent
        }

        .sidebar-link:hover {
            background: var(--accent-light);
            color: var(--accent)
        }

        .sidebar-link.active {
            background: var(--accent-light);
            color: var(--accent);
            border-left-color: var(--accent);
            font-weight: 600
        }

        .sidebar-bottom {
            padding: 1rem;
            border-top: 1px solid var(--border);
            margin-top: auto
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .75rem;
            background: var(--bg);
            border-radius: 10px;
            margin-bottom: .75rem
        }

        .sidebar-avatar {
            width: 36px;
            height: 36px;
            background: var(--accent);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: .9rem;
            flex-shrink: 0
        }

        .sidebar-user-info p:first-child {
            font-size: .8rem;
            font-weight: 600
        }

        .sidebar-user-info p:last-child {
            font-size: .7rem;
            color: var(--text-muted)
        }

        /* MAIN */
        .user-main {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh
        }

        .user-topbar {
            background: white;
            border-bottom: 1px solid var(--border);
            padding: 0 2rem;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 40
        }

        .user-topbar h1 {
            font-size: 1rem;
            font-weight: 700
        }

        .user-content {
            padding: 2rem;
            flex: 1
        }

        /* CARDS */
        .card {
            background: white;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.5rem
        }

        .stat-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0
        }

        .stat-info p:first-child {
            font-size: 1.6rem;
            font-weight: 900;
            line-height: 1
        }

        .stat-info p:last-child {
            font-size: .78rem;
            color: var(--text-muted);
            margin-top: .2rem
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse
        }

        thead th {
            padding: .8rem 1rem;
            text-align: left;
            font-size: .78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: var(--text-muted);
            border-bottom: 2px solid var(--border);
            background: var(--bg)
        }

        tbody td {
            padding: .875rem 1rem;
            font-size: .875rem;
            border-bottom: 1px solid var(--border)
        }

        tbody tr:hover {
            background: #f8fafc
        }

        .table-overflow {
            overflow-x: auto;
            border-radius: var(--radius);
            border: 1px solid var(--border)
        }

        /* FORM */
        .form-group {
            margin-bottom: 1.25rem
        }

        label.form-label {
            display: block;
            font-size: .875rem;
            font-weight: 600;
            margin-bottom: .4rem
        }

        .form-control {
            width: 100%;
            padding: .7rem 1rem;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-family: inherit;
            font-size: .9rem;
            transition: border-color .2s
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(22, 163, 74, .1)
        }

        .form-error {
            color: var(--danger);
            font-size: .78rem;
            margin-top: .3rem
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
            background: var(--accent2)
        }

        .btn-danger {
            background: var(--danger);
            color: white
        }

        .btn-ghost {
            background: transparent;
            color: var(--text-muted);
            border: 1px solid var(--border)
        }

        .btn-ghost:hover {
            background: var(--bg)
        }

        .btn-sm {
            padding: .4rem .9rem;
            font-size: .78rem
        }

        .badge {
            padding: .25rem .75rem;
            border-radius: 20px;
            font-size: .72rem;
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

        .flash {
            padding: .85rem 1.25rem;
            border-radius: 10px;
            margin-bottom: 1.25rem;
            font-size: .875rem;
            font-weight: 500
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

        /* MODAL */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .5);
            z-index: 200;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            opacity: 0;
            pointer-events: none;
            transition: opacity .2s
        }

        .modal-overlay.open {
            opacity: 1;
            pointer-events: all
        }

        .modal {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            width: 100%;
            max-width: 520px;
            max-height: 90vh;
            overflow-y: auto;
            transform: scale(.95);
            transition: transform .2s
        }

        .modal-overlay.open .modal {
            transform: scale(1)
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--text-muted)
        }
    </style>
    @stack('styles')
</head>

<body>
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-logo">SI<span>PRASA</span></div>
            <div class="sidebar-sub">Portal Pengguna</div>
        </div>
        <nav class="sidebar-nav">
            <div class="sidebar-section">Menu</div>
            <a href="{{ route('user.dashboard') }}"
                class="sidebar-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                📊 Dashboard
            </a>
            <a href="{{ route('user.booking.create') }}"
                class="sidebar-link {{ request()->routeIs('user.booking.*') ? 'active' : '' }}">
                📝 Ajukan Peminjaman
            </a>
            <a href="{{ route('user.history') }}"
                class="sidebar-link {{ request()->routeIs('user.history') ? 'active' : '' }}">
                📋 Riwayat Peminjaman
            </a>
            <div class="sidebar-section">Lainnya</div>
            <a href="{{ route('home') }}" class="sidebar-link">🏠 Kembali ke Beranda</a>
        </nav>
        <div class="sidebar-bottom">
            <div class="sidebar-user">
                <div class="sidebar-avatar">{{ strtoupper(substr(auth()->user()->name_users ?? 'U', 0, 1)) }}</div>
                <div class="sidebar-user-info">
                    <p>{{ auth()->user()->name_users ?? 'Pengguna' }}</p>
                    <p>{{ auth()->user()->email_users ?? '' }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('user.logout') }}">
                @csrf
                <button type="submit" class="btn btn-ghost" style="width:100%;justify-content:center">🚪 Keluar</button>
            </form>
        </div>
    </aside>

    <div class="user-main">
        <div class="user-topbar">
            <h1>@yield('page-title', 'Dashboard')</h1>
            <span style="font-size:.875rem;color:var(--text-muted)">Halo, {{ auth()->user()->name_users ?? 'Pengguna' }}
                👋</span>
        </div>
        <div class="user-content">
            @if(session('success'))
                <div class="flash flash-success">✅ {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="flash flash-error">❌ {{ session('error') }}</div>
            @endif
            @yield('content')
        </div>
    </div>
    @stack('scripts')
</body>

</html>