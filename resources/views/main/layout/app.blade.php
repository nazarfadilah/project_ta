<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIPRASA')</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('assets/image/icon.jpg') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 240px;
            --sidebar-collapsed-width: 64px;
            --navbar-height: 50px;
            --gold-primary: #C9A961;
            --gold-dark: #B8953F;
            --gold-light: #D4BA7A;
            --sidebar-text: #3a3a3a;
            --sidebar-hover: rgba(0,0,0,0.08);
            --sidebar-active: rgba(0,0,0,0.12);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            background-color: #f4f6f9;
            overflow-x: hidden;
        }

        /* ===== SIDEBAR ===== */
        #sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background-color: var(--gold-primary);
            z-index: 1040;
            display: flex;
            flex-direction: column;
            transition: width 0.3s ease;
            overflow: hidden;
        }

        #sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        /* -- Sidebar Brand (logo area) -- */
        .sidebar-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            height: var(--navbar-height);
            padding: 0 14px;
            background-color: var(--gold-dark);
            flex-shrink: 0;
            gap: 12px;
            overflow: hidden;
            white-space: nowrap;
        }

        .sidebar-brand .brand-logo {
            flex-shrink: 0;
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-brand .brand-logo img {
            max-height: 34px;
            max-width: 34px;
            object-fit: contain;
        }

        .sidebar-brand .brand-text {
            color: #fff;
            font-weight: 700;
            font-size: 17px;
            letter-spacing: 0.5px;
            opacity: 1;
            transition: opacity 0.25s ease, max-width 0.3s ease;
            overflow: hidden;
            max-width: 150px;
        }

        #sidebar.collapsed .sidebar-brand {
            justify-content: center;
            padding: 0;
        }

        #sidebar.collapsed .brand-text {
            opacity: 0;
            max-width: 0;
        }

        /* -- Sidebar Menu -- */
        .sidebar-menu {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 12px 10px;
        }

        .sidebar-menu ul {
            padding: 0;
            margin: 0;
        }

        .sidebar-menu .nav-item {
            list-style: none;
            margin-bottom: 2px;
        }

        .sidebar-menu .nav-link {
            display: flex;
            align-items: center;
            padding: 8px 12px;
            color: var(--sidebar-text);
            border-radius: 5px;
            transition: all 0.2s ease;
            text-decoration: none;
            white-space: nowrap;
            overflow: hidden;
            gap: 10px;
            font-size: 13px;
        }

        .sidebar-menu .nav-link:hover {
            background-color: var(--sidebar-hover);
            color: #000;
        }

        .sidebar-menu .nav-link.active {
            background-color: var(--sidebar-active);
            color: #000;
            font-weight: 600;
        }

        .sidebar-menu .nav-link .menu-icon {
            width: 20px;
            flex-shrink: 0;
            text-align: center;
            font-size: 14px;
        }

        .sidebar-menu .nav-link .menu-text {
            font-size: 13px;
            opacity: 1;
            transition: opacity 0.25s ease;
        }

        /* Collapsed menu styles */
        #sidebar.collapsed .sidebar-menu .nav-link {
            justify-content: center;
            padding: 10px 0;
            gap: 0;
        }

        #sidebar.collapsed .sidebar-menu .nav-link .menu-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
            position: absolute;
        }

        #sidebar.collapsed .sidebar-menu .nav-link .menu-icon {
            font-size: 16px;
        }

        /* Divider */
        .sidebar-divider {
            border: 0;
            border-top: 1px solid rgba(0,0,0,0.18);
            margin: 10px 14px;
            opacity: 1;
        }

        #sidebar.collapsed .sidebar-divider {
            margin: 8px 8px;
        }

        /* -- Sidebar Logout (above footer, inside sidebar) -- */
        .sidebar-logout {
            flex-shrink: 0;
            padding: 12px 10px;
            border-top: 1px solid rgba(0,0,0,0.12);
            margin-bottom: 80px;
        }

        .sidebar-logout .btn {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 7px 10px;
            font-size: 13px;
            border-radius: 5px;
            gap: 6px;
        }

        .sidebar-logout .btn .logout-icon {
            flex-shrink: 0;
            font-size: 13px;
        }

        .sidebar-logout .btn .logout-text {
            opacity: 1;
            transition: opacity 0.25s ease;
        }

        #sidebar.collapsed .sidebar-logout .btn .logout-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
            position: absolute;
        }

        #sidebar.collapsed .sidebar-logout .btn {
            padding: 9px 0;
        }

        /* ===== TOP NAVBAR ===== */
        #topNavbar {
            position: fixed;
            top: 0;
            right: 0;
            left: var(--sidebar-width);
            height: var(--navbar-height);
            background-color: #fff;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            align-items: center;
            padding: 0 20px;
            z-index: 1030;
            transition: left 0.3s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        #topNavbar.collapsed {
            left: var(--sidebar-collapsed-width);
        }

        /* Toggle button in navbar */
        .navbar-toggle-btn {
            background: none;
            border: 1px solid #ddd;
            color: #555;
            width: 34px;
            height: 34px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .navbar-toggle-btn:hover {
            background-color: #f0f0f0;
            border-color: #bbb;
            color: #333;
        }

        .navbar-info {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-left: auto;
        }

        .navbar-info .page-title {
            font-weight: 600;
            color: #444;
            font-size: 15px;
        }

        .navbar-info .welcome-text {
            color: #777;
            font-size: 14px;
        }

        /* ===== CONTENT WRAPPER ===== */
        .content-wrapper {
            margin-left: var(--sidebar-width);
            padding-top: var(--navbar-height);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s ease;
        }

        .content-wrapper.collapsed {
            margin-left: var(--sidebar-collapsed-width);
        }

        .main-content {
            flex: 1;
            padding: 24px;
        }

        /* ===== FOOTER ===== */
        .main-footer {
            background-color: var(--gold-primary);
            color: white;
            padding: 16px 24px;
            text-align: center;
            font-size: 13px;
            line-height: 1.6;
            flex-shrink: 0;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            #sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-width) !important;
            }

            #sidebar.mobile-open {
                transform: translateX(0);
            }

            #sidebar.collapsed {
                transform: translateX(-100%);
            }

            #topNavbar {
                left: 0 !important;
            }

            .content-wrapper {
                margin-left: 0 !important;
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.4);
                z-index: 1035;
            }

            .sidebar-overlay.active {
                display: block;
            }
        }

        /* ===== SCROLLBAR ===== */
        .sidebar-menu::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar-menu::-webkit-scrollbar-thumb {
            background: rgba(0,0,0,0.15);
            border-radius: 4px;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Sidebar Overlay for mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    @include('main.layout.navbar')

    <!-- Top Navbar -->
    <nav id="topNavbar" class="collapsed">
        <!-- Toggle button on the left of navbar -->
        <button class="navbar-toggle-btn" id="sidebarToggle" type="button" title="Toggle Menu">
            <i class="fas fa-bars" style="font-size: 15px;"></i>
        </button>

        <!-- Right side info -->
        <div class="navbar-info">
            <span class="page-title">@yield('title', 'Dashboard')</span>
            <div class="vr" style="height: 20px; background-color: #ddd;"></div>
            <span class="welcome-text">
                <i class="fas fa-hand-wave me-1"></i> Selamat datang!
            </span>
        </div>
    </nav>

    <!-- Content Wrapper -->
    <div class="content-wrapper collapsed" id="contentWrapper">
        <!-- Main Content -->
        <div class="main-content">
            @yield('content')
        </div>

        <!-- Footer -->
        <div class="main-footer">
            @include('main.layout.footer')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
