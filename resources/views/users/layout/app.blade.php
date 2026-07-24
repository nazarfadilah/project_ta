<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIPRASA')</title>
    <script>
        // Apply theme immediately to prevent flashing
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            if (savedTheme === 'dark') {
                document.documentElement.classList.add('dark-theme');
            }
        })();
    </script>
    <link rel="icon" type="image/png" href="{{ asset('assets/image/icon.png') }}">
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
            justify-content: flex-start;
            height: var(--navbar-height);
            padding: 0 15px;
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
            gap: 0;
        }

        #sidebar.collapsed .brand-text {
            display: none;
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

        /* ===== DARK THEME OVERRIDES ===== */
        .dark-theme body {
            background-color: #121212 !important;
            color: #e0e0e0 !important;
        }

        .dark-theme #topNavbar {
            background-color: #1e1e1e !important;
            border-bottom-color: #2d2d2d !important;
            color: #e0e0e0 !important;
            box-shadow: 0 1px 3px rgba(0,0,0,0.3) !important;
        }

        .dark-theme #topNavbar .page-title {
            color: #fff !important;
        }

        .dark-theme #topNavbar .welcome-text {
            color: #b0b0b0 !important;
        }

        .dark-theme #topNavbar .vr {
            background-color: #444 !important;
        }

        .dark-theme .navbar-toggle-btn {
            border-color: #444 !important;
            color: #ccc !important;
        }

        .dark-theme .navbar-toggle-btn:hover {
            background-color: #2d2d2d !important;
            color: #fff !important;
            border-color: #555 !important;
        }

        .dark-theme .card {
            background-color: #1e1e1e !important;
            border-color: #2d2d2d !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.25) !important;
        }

        .dark-theme .card-header {
            background-color: #252525 !important;
            border-bottom-color: #2d2d2d !important;
            color: #fff !important;
        }

        .dark-theme .card-header h5 {
            color: #fff !important;
        }

        .dark-theme .detail-item .value,
        .dark-theme .ruangan-info-value,
        .dark-theme .guest-info-value {
            color: #fff !important;
        }

        .dark-theme .detail-item label,
        .dark-theme .ruangan-info-label,
        .dark-theme .guest-info-label,
        .dark-theme .keterangan-section label,
        .dark-theme .timeline-content .label {
            color: #a0a0a0 !important;
        }

        .dark-theme .form-card {
            background: #1e1e1e !important;
            color: #e0e0e0 !important;
        }

        .dark-theme .form-label {
            color: #ccc !important;
        }

        .dark-theme .form-control,
        .dark-theme .form-select {
            background-color: #2d2d2d !important;
            border-color: #444 !important;
            color: #fff !important;
        }

        .dark-theme .form-control:focus,
        .dark-theme .form-select:focus {
            background-color: #2d2d2d !important;
            color: #fff !important;
            border-color: var(--gold-primary) !important;
            box-shadow: 0 0 0 3px rgba(201, 169, 97, 0.25) !important;
        }

        .dark-theme .input-group-text {
            background-color: #2d2d2d !important;
            border-color: #444 !important;
            color: #ccc !important;
        }

        .dark-theme table {
            color: #e0e0e0 !important;
            border-color: #2d2d2d !important;
        }

        .dark-theme table thead {
            background-color: #252525 !important;
        }

        .dark-theme table thead th {
            color: #ccc !important;
            background-color: #252525 !important;
            border-bottom-color: #333 !important;
        }

        .dark-theme table tbody td {
            background-color: #1e1e1e !important;
            color: #e0e0e0 !important;
            border-color: #2d2d2d !important;
        }

        .dark-theme table tbody tr:hover td {
            background-color: #252525 !important;
        }

        .dark-theme .keterangan-section,
        .dark-theme .guest-info,
        .dark-theme .ruangan-info,
        .dark-theme .timeline-content {
            background-color: #252525 !important;
            border-color: var(--gold-primary) !important;
        }

        .dark-theme .text-muted,
        .dark-theme .text-muted * {
            color: #888 !important;
        }

        .dark-theme .modal-content {
            background-color: #1e1e1e !important;
            color: #e0e0e0 !important;
            border: 1px solid #333 !important;
        }

        .dark-theme .modal-header {
            background-color: #252525 !important;
            border-bottom-color: #333 !important;
        }

        .dark-theme .modal-title {
            color: #fff !important;
        }

        .dark-theme .btn-close {
            filter: invert(1) grayscale(1) brightness(2) !important;
        }

        .dark-theme .profile-card {
            background: #1e1e1e !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2) !important;
        }
        .dark-theme .profile-card-body h4 {
            color: #fff !important;
        }
        .dark-theme .profile-info-item {
            border-bottom-color: #2d2d2d !important;
        }
        .dark-theme .profile-info-text p {
            color: #e0e0e0 !important;
        }
        .dark-theme .avatar-wrapper {
            background-color: #1e1e1e !important;
        }
        .dark-theme .profile-avatar {
            background-color: #2d2d2d !important;
            border-color: var(--gold-primary) !important;
        }
        .dark-theme .gender-radio-label {
            border-color: #444 !important;
            color: #a0a0a0 !important;
        }
        .dark-theme .gender-radio-card input:checked + .gender-radio-label {
            border-color: var(--gold-primary) !important;
            background-color: rgba(201, 169, 97, 0.1) !important;
            color: var(--gold-light) !important;
        }
        .dark-theme .form-section-title {
            border-bottom-color: rgba(201, 169, 97, 0.3) !important;
        }
        .dark-theme .alert-success-gold, .dark-theme .alert-error-gold {
            background-color: #252525 !important;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2) !important;
        }
        .dark-theme .alert-success-gold span {
            color: #d4edda !important;
        }
        .dark-theme .alert-error-gold span {
            color: #f8d7da !important;
        }
    </style>
    @yield('css')
    @stack('styles')
</head>
<body>
    <!-- Sidebar Overlay for mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    @include('users.layout.navbar')

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

            <!-- Notifications Dropdown -->
            <div class="dropdown me-2" id="notifDropdownWrapper">
                <button class="navbar-toggle-btn position-relative" type="button" id="notifDropdownBtn" data-bs-toggle="dropdown" aria-expanded="false" title="Notifikasi" style="border: 1px solid #ddd; background: transparent; width: 34px; height: 34px; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: var(--sidebar-text);">
                    <i class="fas fa-bell"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notifCountBadge" style="font-size: 9px; display: none;">0</span>
                </button>
                <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 p-0" aria-labelledby="notifDropdownBtn" style="width: 320px; border-radius: 12px; overflow: hidden; margin-top: 10px;">
                    <div class="d-flex justify-content-between align-items-center p-3 border-bottom" style="background-color: var(--gold-primary); color: white;">
                        <h6 class="fw-bold mb-0" style="font-size: 14px;"><i class="fas fa-bell me-1"></i> Notifikasi Baru</h6>
                        <button class="btn btn-link btn-sm text-white p-0 text-decoration-none fw-semibold" id="btnNotifMarkAllRead" style="font-size: 11px;">Tandai Dibaca</button>
                    </div>
                    <div class="list-group list-group-flush overflow-auto" id="notifDropdownList" style="max-height: 280px;">
                        <div class="text-center py-4 text-muted">
                            <i class="far fa-bell fa-2x mb-2 text-muted" style="opacity: 0.5;"></i>
                            <p class="mb-0" style="font-size: 12px;">Tidak ada notifikasi baru.</p>
                        </div>
                    </div>
                    <a href="{{ route('notifications.index') }}" class="dropdown-item text-center fw-bold py-2 border-top text-secondary" style="font-size: 12px; background-color: #f8fafc; color: #718096 !important;">Lihat Semua Notifikasi</a>
                </div>
            </div>

            <span class="welcome-text">
                <i class="fas fa-hand-wave me-1"></i> Selamat datang, {{ Auth::user()->name ?? 'Pengguna' }}!
            </span>
            <button class="navbar-toggle-btn" id="themeToggle" type="button" title="Toggle Tema" style="margin-left: 10px;">
                <i class="fas fa-moon" id="themeIcon"></i>
            </button>
            @if(Auth::user()->profile_photo)
                <img src="{{ asset(Auth::user()->profile_photo) }}" alt="Foto Profil" class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover; border: 2px solid var(--gold-light); margin-left: 5px;">
            @endif
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
            @include('users.layout.footer')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Global helper for delete or confirmation actions using SweetAlert2
        function hapusData(url, message = 'Apakah Anda yakin ingin menghapus data ini?') {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    form.innerHTML = `
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="_method" value="DELETE">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Automatically scan all forms and apply confirm-submit class and attributes
            document.querySelectorAll('form').forEach(form => {
                // Logout form detection
                if (form.action && form.action.includes('logout')) {
                    form.classList.add('confirm-submit');
                    form.setAttribute('data-confirm-title', 'Konfirmasi Keluar');
                    form.setAttribute('data-confirm-text', 'Apakah Anda yakin ingin keluar dari sistem?');
                    form.setAttribute('data-confirm-button', 'Ya, Keluar');
                }
                // General create / edit form detection (method POST, exclude DELETE, modals, and no-confirm)
                else if (form.method && form.method.toUpperCase() === 'POST' && !form.classList.contains('no-confirm') && !form.closest('.modal')) {
                    const isDelete = form.querySelector('input[name="_method"][value="DELETE"]');
                    if (!isDelete && !form.classList.contains('confirm-submit')) {
                        const isEdit = form.querySelector('input[name="_method"][value="PUT"]') || 
                                       form.querySelector('input[name="_method"][value="PATCH"]') ||
                                       document.title.toLowerCase().includes('edit') ||
                                       document.title.toLowerCase().includes('ubah');
                        
                        const actionWord = isEdit ? 'menyimpan perubahan data' : 'menambahkan data';
                        const titleText = isEdit ? 'Simpan Perubahan' : 'Tambah Data';
                        const confirmBtnText = isEdit ? 'Ya, Simpan' : 'Ya, Tambahkan';
                        
                        form.classList.add('confirm-submit');
                        form.setAttribute('data-confirm-title', titleText);
                        form.setAttribute('data-confirm-text', `Apakah Anda yakin ingin ${actionWord} ini?`);
                        form.setAttribute('data-confirm-button', confirmBtnText);
                    }
                }
            });

            // Intercept submit event for forms with confirm-submit class
            document.addEventListener('submit', function(e) {
                const form = e.target;
                if (form.classList.contains('confirm-submit')) {
                    e.preventDefault();
                    
                    const title = form.getAttribute('data-confirm-title') || 'Konfirmasi';
                    const text = form.getAttribute('data-confirm-text') || 'Apakah Anda yakin ingin menyimpan data ini?';
                    const confirmText = form.getAttribute('data-confirm-button') || 'Ya, Lanjutkan';
                    
                    Swal.fire({
                        title: title,
                        text: text,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#C9A961',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: confirmText,
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.classList.remove('confirm-submit');
                            form.submit();
                        }
                    });
                }
            });
            // Custom Indonesian validation messages for HTML5 native tooltips
            document.addEventListener('invalid', function(e) {
                const target = e.target;
                if (target.validity.valueMissing) {
                    target.setCustomValidity('Kolom ini wajib diisi.');
                } else if (target.validity.typeMismatch && target.type === 'email') {
                    target.setCustomValidity('Alamat email tidak valid.');
                } else if (target.validity.rangeUnderflow) {
                    target.setCustomValidity(`Nilai tidak boleh kurang dari ${target.min}.`);
                } else if (target.validity.rangeOverflow) {
                    target.setCustomValidity(`Nilai tidak boleh lebih dari ${target.max}.`);
                } else if (target.validity.tooShort) {
                    target.setCustomValidity(`Input terlalu pendek (minimal ${target.minLength} karakter).`);
                } else if (target.validity.tooLong) {
                    target.setCustomValidity(`Input terlalu panjang (maksimal ${target.maxLength} karakter).`);
                }
            }, true);

            // Reset validation message on new input
            document.addEventListener('input', function(e) {
                e.target.setCustomValidity('');
            });

            // Real-time numeric input validation (restrict input values from exceeding max value)
            document.addEventListener('input', function(e) {
                if (e.target && e.target.type === 'number') {
                    const val = parseInt(e.target.value);
                    if (!isNaN(val)) {
                        const max = parseInt(e.target.max);
                        if (!isNaN(max) && val > max) {
                            e.target.value = max;
                        }
                    }
                }
            });

            document.addEventListener('change', function(e) {
                if (e.target && e.target.type === 'number') {
                    const val = parseInt(e.target.value);
                    if (!isNaN(val)) {
                        const min = parseInt(e.target.min);
                        if (!isNaN(min) && val < min) {
                            e.target.value = min;
                        }
                    }
                }
            });

            const themeToggle = document.getElementById('themeToggle');
            const themeIcon = document.getElementById('themeIcon');

            if (themeToggle) {
                themeToggle.addEventListener('click', function() {
                    const isDark = document.documentElement.classList.toggle('dark-theme');
                    localStorage.setItem('theme', isDark ? 'dark' : 'light');
                    updateThemeIcon(isDark);
                });
            }

            function updateThemeIcon(isDark) {
                if (themeIcon) {
                    if (isDark) {
                        themeIcon.classList.remove('fa-moon');
                        themeIcon.classList.add('fa-sun');
                        themeIcon.style.color = '#ffc107'; // yellow color for sun icon
                    } else {
                        themeIcon.classList.remove('fa-sun');
                        themeIcon.classList.add('fa-moon');
                        themeIcon.style.color = '';
                    }
                }
            }

            // Initial sync on load
            updateThemeIcon(document.documentElement.classList.contains('dark-theme'));
        });

        // Notifications Javascript Logic
        document.addEventListener('DOMContentLoaded', function() {
            const notifDropdownBtn = document.getElementById('notifDropdownBtn');
            const notifCountBadge = document.getElementById('notifCountBadge');
            const notifDropdownList = document.getElementById('notifDropdownList');
            const btnNotifMarkAllRead = document.getElementById('btnNotifMarkAllRead');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // 1. Request Browser Native Notification Permission
            if (window.Notification && Notification.permission === 'default') {
                Notification.requestPermission();
            }

            // 2. Fetch Notifications
            function loadNotifications() {
                fetch('{{ route("notifications.unread.json") }}')
                    .then(response => response.json())
                    .then(data => {
                        // Update badge
                        if (data.count > 0) {
                            notifCountBadge.textContent = data.count;
                            notifCountBadge.style.display = 'block';
                            
                            // Check if we should trigger native push popup (for any new unread notification)
                            const lastCount = localStorage.getItem('last_notif_count') || 0;
                            if (data.count > lastCount && window.Notification && Notification.permission === 'granted') {
                                const latestNotif = data.notifications[0];
                                const cleanMsg = latestNotif.message.replace(/<[^>]*>?/gm, ''); // strip HTML tags
                                new Notification(latestNotif.type, {
                                    body: cleanMsg,
                                    icon: '{{ asset("assets/image/icon.png") }}'
                                });
                            }
                            localStorage.setItem('last_notif_count', data.count);
                        } else {
                            notifCountBadge.style.display = 'none';
                            localStorage.setItem('last_notif_count', 0);
                        }

                        // Populate dropdown list
                        if (data.notifications.length > 0) {
                            let html = '';
                            data.notifications.forEach(item => {
                                const readBg = item.read ? '' : 'background-color: rgba(201, 169, 97, 0.04); font-weight: 500;';
                                const unreadIndicator = item.read ? '' : '<i class="fas fa-circle text-primary ms-2" style="font-size: 7px;"></i>';
                                html += `
                                    <div class="list-group-item list-group-item-action d-flex align-items-start gap-2 border-0 border-bottom p-3 notif-item" data-id="${item.id}" style="cursor: pointer; font-size: 13px; transition: all 0.2s; ${readBg}">
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <strong class="text-dark">${item.type}</strong>
                                                <small class="text-muted" style="font-size: 10px;">${item.time_ago}</small>
                                            </div>
                                            <div class="text-secondary" style="line-height: 1.4;">${item.message}</div>
                                        </div>
                                        ${unreadIndicator}
                                    </div>
                                `;
                            });
                            notifDropdownList.innerHTML = html;

                            // Add click handler to list items to mark as read
                            notifDropdownList.querySelectorAll('.notif-item').forEach(el => {
                                el.addEventListener('click', function(e) {
                                    // Bypassing click redirection if they clicked on links inside message
                                    const notifId = el.getAttribute('data-id');
                                    fetch(`/notifications/${notifId}/read`, {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': csrfToken,
                                            'Content-Type': 'application/json'
                                        }
                                    }).then(() => {
                                        // Find link clicked, if not, redirect to index
                                        if (e.target.tagName === 'A') {
                                            window.location.href = e.target.href;
                                        } else {
                                            loadNotifications();
                                        }
                                    });
                                });
                            });
                        } else {
                            notifDropdownList.innerHTML = `
                                <div class="text-center py-4 text-muted">
                                    <i class="far fa-bell fa-2x mb-2 text-muted" style="opacity: 0.5;"></i>
                                    <p class="mb-0" style="font-size: 12px;">Tidak ada notifikasi baru.</p>
                                </div>
                            `;
                        }
                    });
            }

            // Load initially
            loadNotifications();

            // Auto poll every 30 seconds to keep updated
            setInterval(loadNotifications, 30000);

            // Mark all read inside dropdown
            if (btnNotifMarkAllRead) {
                btnNotifMarkAllRead.addEventListener('click', function(e) {
                    e.stopPropagation();
                    fetch('{{ route("notifications.read.all") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json'
                        }
                    }).then(() => {
                        loadNotifications();
                    });
                });
            }
        });
    </script>
    @yield('js')
    @stack('scripts')
</body>
</html>
