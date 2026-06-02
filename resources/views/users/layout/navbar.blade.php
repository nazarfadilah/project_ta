<!-- Sidebar -->
<nav id="sidebar" class="collapsed">
    <!-- Brand Area: Logo + App Name -->
    <div class="sidebar-brand" style="cursor: pointer;" onclick="window.location.href='{{ route('users.dashboard') }}'">
        <div class="brand-logo">
            @php
                $logo = \App\Models\Tentang::where('key', 'logo')->first()?->value;
            @endphp
            @if($logo)
                <img src="{{ asset('storage/' . $logo) }}">
            @else
                <i class="fas fa-building" style="color: #fff; font-size: 22px;"></i>
            @endif
        </div>
        <span class="brand-text">SIPRASA</span>
    </div>

    <!-- Menu Items -->
    <div class="sidebar-menu">
        <ul class="nav flex-column">
            <!-- Dashboard -->
            <li class="nav-item">
                <a href="{{ route('users.dashboard') }}" class="nav-link {{ request()->routeIs('users.dashboard') ? 'active' : '' }}" title="Dashboard">
                    <i class="fas fa-home menu-icon"></i>
                    <span class="menu-text">Dashboard</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Daftar Ruangan -->
            <li class="nav-item">
                <a href="{{ route('users.main.ruangan.index') }}" class="nav-link {{ request()->routeIs('users.main.ruangan.*') ? 'active' : '' }}" title="Daftar Ruangan">
                    <i class="fas fa-door-open menu-icon"></i>
                    <span class="menu-text">Daftar Ruangan</span>
                </a>
            </li>

            <!-- Daftar Gedung -->
            <li class="nav-item">
                <a href="{{ route('users.main.gedung.index') }}" class="nav-link {{ request()->routeIs('users.main.gedung.*') ? 'active' : '' }}" title="Daftar Gedung">
                    <i class="fas fa-building menu-icon"></i>
                    <span class="menu-text">Daftar Gedung</span>
                </a>
            </li>

            <!-- Daftar Sarana -->
            <li class="nav-item">
                <a href="{{ route('users.main.sarana.index') }}" class="nav-link {{ request()->routeIs('users.main.sarana.*') ? 'active' : '' }}" title="Daftar Sarana">
                    <i class="fas fa-tools menu-icon"></i>
                    <span class="menu-text">Daftar Sarana</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Reservasi Saya -->
            <li class="nav-item">
                <a href="{{ route('users.main.reservasi.index') }}" class="nav-link {{ request()->routeIs('users.main.reservasi.*') ? 'active' : '' }}" title="Reservasi Saya">
                    <i class="fas fa-calendar-check menu-icon"></i>
                    <span class="menu-text">Reservasi Saya</span>
                </a>
            </li>

            <!-- Profil Saya -->
            <li class="nav-item">
                <a href="{{ route('users.profil.edit') }}" class="nav-link {{ request()->routeIs('users.profil.*') ? 'active' : '' }}" title="Profil Saya">
                    <i class="fas fa-user-circle menu-icon"></i>
                    <span class="menu-text">Profil Saya</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Logout Button -->
    <div class="sidebar-logout">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger btn-sm" title="Keluar">
                <i class="fas fa-sign-out-alt logout-icon"></i>
                <span class="logout-text">Keluar</span>
            </button>
        </form>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const topNavbar = document.getElementById('topNavbar');
        const contentWrapper = document.getElementById('contentWrapper');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        let isCollapsed = true;

        function isMobile() {
            return window.innerWidth <= 768;
        }

        function updateLayout() {
            if (isMobile()) {
                sidebar.classList.remove('collapsed');
                topNavbar.classList.add('collapsed');
                contentWrapper.classList.add('collapsed');

                if (!isCollapsed) {
                    sidebar.classList.add('mobile-open');
                    sidebarOverlay.classList.add('active');
                } else {
                    sidebar.classList.remove('mobile-open');
                    sidebarOverlay.classList.remove('active');
                }
            } else {
                sidebar.classList.remove('mobile-open');
                sidebarOverlay.classList.remove('active');

                if (isCollapsed) {
                    sidebar.classList.add('collapsed');
                    topNavbar.classList.add('collapsed');
                    contentWrapper.classList.add('collapsed');
                } else {
                    sidebar.classList.remove('collapsed');
                    topNavbar.classList.remove('collapsed');
                    contentWrapper.classList.remove('collapsed');
                }
            }
        }

        sidebarToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            isCollapsed = !isCollapsed;
            updateLayout();
        });

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', function() {
                isCollapsed = true;
                updateLayout();
            });
        }

        window.addEventListener('resize', function() {
            updateLayout();
        });

        document.addEventListener('click', function(event) {
            if (isMobile() && !isCollapsed) {
                if (!sidebar.contains(event.target) && event.target !== sidebarToggle && !sidebarToggle.contains(event.target)) {
                    isCollapsed = true;
                    updateLayout();
                }
            }
        });

        updateLayout();
    });
</script>
