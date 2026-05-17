<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - SIPRASA')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f8f9fa;
        }

        #topNavbar {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        #sidebar {
            position: fixed;
            left: 0;
            top: 56px;
            bottom: 0;
            width: 250px;
            padding: 0;
            overflow-y: auto;
            transition: width 0.3s ease;
            z-index: 999;
            background-color: #C9A961;
        }

        #sidebar.collapsed {
            width: 70px;
        }

        #sidebar.collapsed .sidebar-header {
            text-align: center;
            padding: 15px 5px;
            border-bottom: 1px solid rgba(0,0,0,0.15);
            margin-bottom: 0;
        }

        #sidebar.collapsed .sidebar-header img {
            max-width: 50px;
            margin-bottom: 0;
        }

        #sidebar.collapsed .sidebar-header h5,
        #sidebar.collapsed .sidebar-header small,
        #sidebar.collapsed .nav-link i:not(:first-child),
        #sidebar.collapsed .nav-link:not(:only-child) span,
        #sidebar.collapsed .sidebar-logout .btn span {
            display: none;
        }

        #sidebar.collapsed .nav-link {
            text-align: center;
            padding: 15px 5px;
            font-size: 12px;
        }

        #sidebar.collapsed .nav-link i {
            margin-right: 0;
            font-size: 20px;
        }

        #sidebar.collapsed .sidebar-logout {
            bottom: 10px;
            padding: 0 5px;
        }

        #sidebar.collapsed .sidebar-logout .btn {
            padding: 10px 5px;
        }

        #sidebar.collapsed .sidebar-logout .btn i {
            margin-right: 0;
        }

        .sidebar-header {
            text-align: center;
            border-bottom: 1px solid rgba(0,0,0,0.15);
            padding: 20px;
            margin-bottom: 20px;
        }

        .sidebar-header img {
            max-width: 60px;
            margin-bottom: 10px;
        }

        .sidebar-header h5 {
            margin-bottom: 5px;
            font-weight: 700;
        }

        .sidebar-header small {
            color: #666;
        }

        .nav-link {
            color: #333;
            border-radius: 6px;
            transition: all 0.2s;
            padding: 10px 15px;
        }

        .nav-link:hover {
            background-color: rgba(0,0,0,0.1);
            color: #000;
        }

        .nav-link.active {
            background-color: rgba(0,0,0,0.15);
            color: #000;
        }

        .sidebar-logout {
            position: absolute;
            bottom: 20px;
            left: 0;
            right: 0;
            padding: 0 20px;
            border-top: 1px solid rgba(0,0,0,0.15);
            padding-top: 20px;
        }

        .sidebar-logout .btn {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .main-content {
            margin-left: 250px;
            margin-top: 56px;
            flex: 1;
            transition: margin-left 0.3s ease;
            padding: 20px;
        }

        .main-content.collapsed {
            margin-left: 70px;
        }

        .main-content.expanded {
            margin-left: 0;
        }

        footer {
            margin-left: 250px;
            transition: margin-left 0.3s ease;
            width: calc(100% - 250px);
        }

        footer.collapsed {
            margin-left: 70px;
            width: calc(100% - 70px);
        }

        footer.full-width {
            margin-left: 0;
            width: 100%;
        }

        @media (max-width: 768px) {
            #sidebar {
                width: 250px;
                transform: translateX(-250px);
            }

            #sidebar.show {
                transform: translateX(0);
            }

            #sidebar.collapsed {
                width: 250px;
                transform: translateX(-250px);
            }

            .main-content {
                margin-left: 0;
            }

            .main-content.collapsed {
                margin-left: 0;
            }

            footer {
                margin-left: 0;
                width: 100%;
            }

            footer.collapsed {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Top Navigation Bar -->
    <nav class="navbar navbar-dark sticky-top" id="topNavbar" style="background-color: #C9A961; top: 0; z-index: 1030; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 10px 15px;">
        <div class="d-flex align-items-center gap-3" style="flex: 1;">
            <!-- Sidebar Toggle Button -->
            <button class="btn btn-sm" id="sidebarToggle" type="button" style="background-color: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; min-width: 38px;">
                <i class="fas fa-bars"></i>
            </button>
            
            <!-- Logo -->
            <div id="navbarLogo" style="display: flex; align-items: center; gap: 8px; min-width: 60px;">
                @php
                    $logo = \App\Models\Tentang::where('key', 'logo')->first()?->value;
                @endphp
                @if($logo)
                    <img src="{{ asset('storage/' . $logo) }}" alt="Logo" style="height: 35px; width: auto;">
                @endif
            </div>
            
            <!-- App Name (visible when sidebar expanded) -->
            <div id="navbarBrand" style="display: none; align-items: center;">
                <span style="color: white; font-weight: 700; font-size: 16px;">SIPRASA</span>
            </div>
            
            <!-- Page Title Right -->
            <div class="ms-auto d-flex align-items-center gap-3">
                <span id="pageTitle" class="fw-semibold" style="color: white;">@yield('page-title', 'Dashboard')</span>
                <div class="vr" style="border-color: rgba(255,255,255,0.3);"></div>
                <span class="text-white" style="font-size: 14px;">Admin Panel</span>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <nav id="sidebar" class="text-dark collapsed" style="background-color: #C9A961;">
        <!-- Logo & App Name -->
        <div class="sidebar-header">
            @php
                $logo = \App\Models\Tentang::where('key', 'logo')->first()?->value;
            @endphp
            
            @if($logo)
                <img src="{{ asset('storage/' . $logo) }}" alt="Logo" class="img-fluid">
            @endif
            <h5 style="color: #333; margin: 10px 0 5px; font-size: 16px;">SIPRASA</h5>
            <small style="color: #555;">Admin Panel</small>
        </div>

        <!-- Menu -->
        <ul class="nav flex-column" style="padding: 0 10px 100px 10px;">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link" style="color: #333; padding: 12px 15px; border-radius: 6px; transition: all 0.2s; display: flex; align-items: center;"
                   onmouseover="this.style.backgroundColor='rgba(0,0,0,0.1)'" onmouseout="this.style.backgroundColor='transparent'">
                    <i class="fas fa-tachometer-alt" style="font-size: 18px;"></i>
                    <span style="margin-left: 12px;">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.users.index') }}" class="nav-link" style="color: #333; padding: 12px 15px; border-radius: 6px; transition: all 0.2s; display: flex; align-items: center;"
                   onmouseover="this.style.backgroundColor='rgba(0,0,0,0.1)'" onmouseout="this.style.backgroundColor='transparent'">
                    <i class="fas fa-users" style="font-size: 18px;"></i>
                    <span style="margin-left: 12px;">Kelola User</span>
                </a>
            </li>
        </ul>

        <!-- Logout Button (at bottom) -->
        <div class="sidebar-logout" style="position: absolute; bottom: 15px; left: 0; right: 0; padding: 0 10px; border-top: 1px solid rgba(0,0,0,0.15); padding-top: 15px;">
            <form action="{{ route('admin.logout') }}" method="POST" class="w-100">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm" style="width: 100%; display: flex; align-items: center; justify-content: center; padding: 8px 12px;" 
                        title="Logout">
                    <i class="fas fa-sign-out-alt"></i>
                    <span style="margin-left: 8px;">Keluar</span>
                </button>
            </form>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer>
        @include('main.layout.footer')
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const mainContent = document.querySelector('.main-content');
            const navbarBrand = document.getElementById('navbarBrand');
            const footer = document.querySelector('footer');
            let isCollapsed = true; // Start with collapsed state

            function updateLayout() {
                if (isCollapsed) {
                    sidebar.classList.add('collapsed');
                    if (mainContent) mainContent.classList.add('collapsed');
                    if (footer) footer.classList.add('collapsed');
                    if (navbarBrand) navbarBrand.style.display = 'none';
                } else {
                    sidebar.classList.remove('collapsed');
                    if (mainContent) mainContent.classList.remove('collapsed');
                    if (footer) footer.classList.remove('collapsed');
                    if (navbarBrand) navbarBrand.style.display = 'flex';
                }
            }

            sidebarToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                isCollapsed = !isCollapsed;
                updateLayout();
            });

            // Handle responsive behavior
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768 && sidebar.classList.contains('show')) {
                    sidebar.classList.remove('show');
                }
            });

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth <= 768) {
                    if (!sidebar.contains(event.target) && event.target !== sidebarToggle && !sidebarToggle.contains(event.target)) {
                        sidebar.classList.remove('show');
                        if (mainContent) mainContent.classList.remove('expanded');
                    }
                }
            });

            // Initialize
            updateLayout();
        });
    </script>
    @stack('scripts')
</body>
</html>
