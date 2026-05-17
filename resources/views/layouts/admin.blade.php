<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — SIPRASA Admin</title>
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
            --sidebar-w: 260px;
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

        /* SIDEBAR */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 50
        }

        .sidebar-brand {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, .08)
        }

        .sidebar-logo {
            font-size: 1.3rem;
            font-weight: 900;
            color: white
        }

        .sidebar-logo span {
            color: #4ade80
        }

        .sidebar-sub {
            font-size: .6rem;
            color: #6b7280;
            margin-top: .2rem
        }

        .sidebar-nav {
            padding: 1rem 0;
            flex: 1;
            overflow-y: auto
        }

        .sidebar-section {
            padding: .5rem 1.25rem;
            font-size: .65rem;
            font-weight: 700;
            color: #4b5563;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: .5rem
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .75rem 1.5rem;
            color: #94a3b8;
            font-size: .875rem;
            font-weight: 500;
            transition: all .2s;
            border-left: 3px solid transparent
        }

        .sidebar-link:hover {
            background: rgba(255, 255, 255, .05);
            color: white
        }

        .sidebar-link.active {
            background: rgba(74, 222, 128, .1);
            color: #4ade80;
            border-left-color: #4ade80
        }

        .sidebar-link .icon {
            width: 18px;
            text-align: center;
            font-size: 1rem
        }

        .sidebar-bottom {
            padding: 1rem;
            border-top: 1px solid rgba(255, 255, 255, .08);
            margin-top: auto
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .75rem;
            background: rgba(255, 255, 255, .05);
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
            font-size: .9rem
        }

        .sidebar-user-info p:first-child {
            font-size: .8rem;
            font-weight: 600;
            color: white
        }

        .sidebar-user-info p:last-child {
            font-size: .7rem;
            color: #6b7280
        }

        /* MAIN */
        .admin-main {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh
        }

        .admin-topbar {
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

        .admin-topbar h1 {
            font-size: 1.1rem;
            font-weight: 700
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 1rem
        }

        /* PROFIL DROPDOWN */
        .topbar-user {
            position: relative;
        }

        .topbar-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, #16a34a, #0ea5e9);
            color: white;
            font-weight: 700;
            font-size: .85rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: 2px solid #e2e8f0;
            transition: border-color .2s, box-shadow .2s;
            user-select: none;
        }

        .topbar-avatar:hover {
            border-color: #16a34a;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, .15);
        }

        .topbar-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            background: white;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, .12);
            min-width: 200px;
            padding: .5rem;
            opacity: 0;
            pointer-events: none;
            transform: translateY(-6px);
            transition: opacity .18s, transform .18s;
            z-index: 999;
        }

        .topbar-dropdown.open {
            opacity: 1;
            pointer-events: all;
            transform: translateY(0);
        }

        .dropdown-header {
            padding: .65rem .85rem .5rem;
            border-bottom: 1px solid #f1f5f9;
            margin-bottom: .35rem;
        }

        .dropdown-header strong {
            display: block;
            font-size: .85rem;
            font-weight: 700;
            color: #1e293b;
        }

        .dropdown-header small {
            font-size: .72rem;
            color: #64748b;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: .6rem;
            padding: .55rem .85rem;
            border-radius: 8px;
            font-size: .875rem;
            color: #1e293b;
            font-weight: 500;
            cursor: pointer;
            transition: background .15s;
            text-decoration: none;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            font-family: inherit;
        }

        .dropdown-item:hover {
            background: #f8fafc;
        }

        .dropdown-item.danger {
            color: #dc2626;
        }

        .dropdown-item.danger:hover {
            background: #fef2f2;
        }

        .dropdown-divider {
            height: 1px;
            background: #f1f5f9;
            margin: .35rem 0;
        }

        .admin-content {
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

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem
        }

        .card-title {
            font-size: 1rem;
            font-weight: 700
        }

        /* STATS */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem
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
            width: 52px;
            height: 52px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0
        }

        .stat-info p:first-child {
            font-size: 1.8rem;
            font-weight: 900;
            line-height: 1
        }

        .stat-info p:last-child {
            font-size: .8rem;
            color: var(--text-muted);
            margin-top: .2rem
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse
        }

        thead th {
            padding: .85rem 1rem;
            text-align: left;
            font-size: .8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: var(--text-muted);
            border-bottom: 2px solid var(--border);
            background: var(--bg)
        }

        tbody td {
            padding: .9rem 1rem;
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
            transition: border-color .2s;
            background: white
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(22, 163, 74, .1)
        }

        select.form-control {
            cursor: pointer
        }

        .form-error {
            color: var(--danger);
            font-size: .8rem;
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

        .btn-warning {
            background: var(--warning);
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

        /* BADGE */
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
            max-width: 560px;
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

        .modal-title {
            font-size: 1.1rem;
            font-weight: 700
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--text-muted);
            padding: .25rem;
            line-height: 1
        }

        .flash {
            padding: .85rem 1.25rem;
            border-radius: 10px;
            margin-bottom: 1.25rem;
            font-size: .875rem;
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
    </style>
    @stack('styles')
</head>

<body>

    {{-- SIDEBAR --}}
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-logo">SI<span>PRASA</span></div>
            <div class="sidebar-sub">Admin Panel</div>
        </div>
        @php $role = auth('admin')->user()->role ?? ''; @endphp
        <nav class="sidebar-nav">
            {{-- ── SEMUA ROLE: Dashboard & Profil ── --}}
            <div class="sidebar-section">Menu Utama</div>
            <a href="{{ route('admin.dashboard') }}"
                class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="icon">📊</span> Dashboard
            </a>
            <a href="{{ route('admin.profil') }}"
                class="sidebar-link {{ request()->routeIs('admin.profil') ? 'active' : '' }}">
                <span class="icon">👤</span> Profil Saya
            </a>

            {{-- ── ADMIN & PETUGAS: Verifikasi Peminjaman ── --}}
            @if(in_array($role, ['admin', 'petugas']))
                <div class="sidebar-section">Peminjaman</div>
                <a href="{{ route('admin.peminjaman.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.peminjaman.*') ? 'active' : '' }}">
                    <span class="icon">📋</span> Validasi Peminjaman
                    @php $pending = \App\Models\PeminjamanTransaksi::where('status_peminjaman', 'Diajukan')->count(); @endphp
                    @if($pending > 0)
                        <span style="margin-left:auto;background:#f59e0b;color:white;border-radius:10px;
                                                 padding:.1rem .45rem;font-size:.65rem;font-weight:700">{{ $pending }}</span>
                    @endif
                </a>
            @endif

            {{-- ── ADMIN & PIMPINAN: Laporan ── --}}
            @if(in_array($role, ['admin', 'pimpinan']))
                <div class="sidebar-section">Laporan</div>
                <a href="{{ route('admin.laporan') }}"
                    class="sidebar-link {{ request()->routeIs('admin.laporan') ? 'active' : '' }}">
                    <span class="icon">📈</span> Laporan Penggunaan
                </a>
            @endif

            {{-- ── ADMIN ONLY: Fasilitas, Konten & Data ── --}}
            @if($role === 'admin')
                <div class="sidebar-section">Manajemen Fasilitas</div>
                <a href="{{ route('admin.gedung.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.gedung.*') ? 'active' : '' }}">
                    <span class="icon">🏢</span> Gedung
                </a>
                <a href="{{ route('admin.ruangan.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.ruangan.*') ? 'active' : '' }}">
                    <span class="icon">🚪</span> Ruangan
                </a>
                <a href="{{ route('admin.sarana.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.sarana.*') ? 'active' : '' }}">
                    <span class="icon">📦</span> Sarana
                </a>

                <div class="sidebar-section">Konten &amp; Data</div>
                <a href="{{ route('admin.berita.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.berita.*') ? 'active' : '' }}">
                    <span class="icon">📰</span> Berita
                </a>
                <a href="{{ route('admin.users.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <span class="icon">👥</span> Data Pengguna
                </a>
                <a href="{{ route('admin.tentang') }}"
                    class="sidebar-link {{ request()->routeIs('admin.tentang') ? 'active' : '' }}">
                    <span class="icon">ℹ️</span> Tentang Instansi
                </a>
            @endif
        </nav>
        <div class="sidebar-bottom">
            <div class="sidebar-user">
                <div class="sidebar-avatar">{{ strtoupper(substr(auth('admin')->user()->name_admin ?? 'A', 0, 1)) }}
                </div>
                <div class="sidebar-user-info">
                    <p>{{ auth('admin')->user()->name_admin ?? 'Admin' }}</p>
                    <p>{{ ucfirst(auth('admin')->user()->role ?? '') }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="btn btn-ghost" style="width:100%;justify-content:center">🚪 Keluar</button>
            </form>
        </div>
    </aside>

    {{-- MAIN --}}
    <div class="admin-main">
        <div class="admin-topbar">
            <h1>@yield('page-title', 'Dashboard')</h1>
            <div class="topbar-actions">
                <span style="font-size:.875rem;color:var(--text-muted)">{{ now()->format('l, d M Y') }}</span>
                <a href="{{ route('home') }}" class="btn btn-ghost btn-sm" target="_blank">🌐 Lihat Website</a>

                {{-- ── Profil Dropdown ── --}}
                @php
                    $initials = strtoupper(implode('', array_map(fn($w) => $w[0], explode(' ', auth('admin')->user()->name_admin ?? 'A'))));
                    $initials = substr($initials, 0, 2);
                @endphp
                <div class="topbar-user">
                    <div class="topbar-avatar" id="topbarAvatarBtn" title="Profil & Pengaturan">{{ $initials }}</div>
                    <div class="topbar-dropdown" id="topbarDropdown">
                        <div class="dropdown-header">
                            <strong>{{ auth('admin')->user()->name_admin }}</strong>
                            <small>{{ auth('admin')->user()->email_admin }}</small>
                        </div>
                        <a href="{{ route('admin.profil') }}" class="dropdown-item">👤 Profil Saya</a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item danger">🚪 Keluar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="admin-content">
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
    <script>
        // Dropdown profil toggle
        const avatarBtn = document.getElementById('topbarAvatarBtn');
        const dropdown = document.getElementById('topbarDropdown');
        if (avatarBtn && dropdown) {
            avatarBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                dropdown.classList.toggle('open');
            });
            document.addEventListener('click', () => dropdown.classList.remove('open'));
            dropdown.addEventListener('click', (e) => e.stopPropagation());
        }
    </script>
</body>

</html>