<!-- Sidebar -->
<nav id="sidebar" class="collapsed">
    <!-- Brand Area: Logo + App Name -->
    <div class="sidebar-brand" style="cursor: pointer;" onclick="window.location.href='{{ route('dashboard') }}'">
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
            <!-- Beranda -->
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" title="Beranda">
                    <i class="fas fa-home menu-icon"></i>
                    <span class="menu-text">Beranda</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Kelola User -->
            <li class="nav-item">
                <a href="{{ route('main.users.index') }}" class="nav-link {{ request()->routeIs('main.users.*') ? 'active' : '' }}" title="Kelola User">
                    <i class="fas fa-users menu-icon"></i>
                    <span class="menu-text">Kelola User</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Kelola Berita -->
            <li class="nav-item">
                <a href="{{ route('main.berita.index') }}" class="nav-link {{ request()->routeIs('main.berita.*') ? 'active' : '' }}" title="Kelola Berita">
                    <i class="fas fa-newspaper menu-icon"></i>
                    <span class="menu-text">Kelola Berita</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Landing Page Dropdown -->
            <li class="nav-item">
                <a href="#landingPageMenu" class="nav-link dropdown-toggle {{ request()->routeIs('main.landing.*') ? 'active' : '' }}" data-bs-toggle="collapse" title="Landing Page">
                    <i class="fas fa-desktop menu-icon"></i>
                    <span class="menu-text">Landing Page</span>
                    <!-- <i class="fas fa-chevron-down ms-auto dropdown-indicator"></i> -->
                </a>
                <div class="collapse {{ request()->routeIs('main.landing.*') ? 'show' : '' }}" id="landingPageMenu">
                    <ul class="nav flex-column ps-4">
                        <li class="nav-item">
                            <a href="{{ route('main.landing.tentang.index') }}" class="nav-link {{ request()->routeIs('main.landing.tentang.*') ? 'active' : '' }}" title="Kelola Tentang">
                                <i class="fas fa-info-circle menu-icon"></i>
                                <span class="menu-text">Kelola Tentang</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('main.landing.galeri.index') }}" class="nav-link {{ request()->routeIs('main.landing.galeri.*') ? 'active' : '' }}" title="Kelola Galeri">
                                <i class="fas fa-images menu-icon"></i>
                                <span class="menu-text">Kelola Galeri</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('main.landing.gambar.index') }}" class="nav-link {{ request()->routeIs('main.landing.gambar.*') ? 'active' : '' }}" title="Kelola Gambar Landing">
                                <i class="fas fa-image menu-icon"></i>
                                <span class="menu-text">Kelola Gambar Landing</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('main.landing.faq.index') }}" class="nav-link {{ request()->routeIs('main.landing.faq.*') ? 'active' : '' }}" title="Kelola FAQ">
                                <i class="fas fa-question-circle menu-icon"></i>
                                <span class="menu-text">Kelola FAQ</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('main.landing.terms.index') }}" class="nav-link {{ request()->routeIs('main.landing.terms.*') ? 'active' : '' }}" title="Kelola Syarat & Ketentuan">
                                <i class="fas fa-file-contract menu-icon"></i>
                                <span class="menu-text">Kelola Syarat & Ketentuan</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('main.landing.privacy.index') }}" class="nav-link {{ request()->routeIs('main.landing.privacy.*') ? 'active' : '' }}" title="Kelola Kebijakan & Privasi">
                                <i class="fas fa-shield-alt menu-icon"></i>
                                <span class="menu-text">Kelola Kebijakan & Privasi</span>
                            </a>
                        </li>
                    </ul>
                </div>
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
