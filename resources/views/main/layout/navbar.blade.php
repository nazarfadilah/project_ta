<!-- Sidebar -->
<nav id="sidebar" class="collapsed">
    <!-- Brand Area: Logo + App Name -->
    <div class="sidebar-brand" style="cursor: pointer;" onclick="window.location.href='{{ route('dashboard') }}'">
        <div class="brand-logo">
            @php
                $logo = \App\Models\Tentang::where('key', 'logo')->first()?->value;
            @endphp
            @if($logo)
                <img src="{{ filter_var($logo, FILTER_VALIDATE_URL) ? $logo : (str_starts_with($logo, 'storage/') ? asset($logo) : asset('storage/' . $logo)) }}" style="border-radius: 50%; aspect-ratio: 1; object-fit: cover; width: 34px; height: 34px;">
            @else
                <i class="fas fa-hotel brand-icon"></i>
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
            @if(Auth::user()->roleId == 1)
            <li class="nav-item">
                <a href="#kelolaUserMenu" class="nav-link dropdown-toggle {{ request()->routeIs('main.users.*') ? 'active' : '' }}" data-bs-toggle="collapse" title="Kelola User">
                    <i class="fas fa-users menu-icon"></i>
                    <span class="menu-text">Kelola User</span>
                </a>
                <div class="collapse {{ request()->routeIs('main.users.*') ? 'show' : '' }}" id="kelolaUserMenu">
                    <ul class="nav flex-column ps-4">
                        <li class="nav-item">
                            <a href="{{ route('main.users.index') }}" class="nav-link {{ request()->routeIs('main.users.index') || request()->routeIs('main.users.edit') ? 'active' : '' }}" title="Daftar Pengguna">
                                <i class="fas fa-user-check menu-icon"></i>
                                <span class="menu-text">Pengguna</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('main.users.blocked') }}" class="nav-link {{ request()->routeIs('main.users.blocked') || request()->routeIs('main.users.blocked.request') ? 'active' : '' }}" title="Daftar Terblokir">
                                <i class="fas fa-user-lock menu-icon"></i>
                                <span class="menu-text">Terblokir</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            @endif

            <!-- Kelola Tamu -->
            @if(in_array(Auth::user()->roleId, [2, 3]))
            <li class="nav-item">
                <a href="{{ route('main.tamu.index') }}" class="nav-link {{ request()->routeIs('main.tamu.*') ? 'active' : '' }}" title="Kelola Tamu">
                    <i class="fas fa-user-tie menu-icon"></i>
                    <span class="menu-text">Kelola Tamu</span>
                </a>
            </li>
            @endif

            <!-- Divider -->
            @if(in_array(Auth::user()->roleId, [1, 2, 3]))
            <hr class="sidebar-divider">
            @endif

            <!-- Kelola Berita -->
            @if(in_array(Auth::user()->roleId, [1, 2, 3]))
            <li class="nav-item">
                <a href="{{ route('main.berita.index') }}" class="nav-link {{ request()->routeIs('main.berita.*') ? 'active' : '' }}" title="Kelola Berita">
                    <i class="fas fa-newspaper menu-icon"></i>
                    <span class="menu-text">Kelola Berita</span>
                </a>
            </li>
            @endif

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Transaksi Peminjaman Dropdown -->
            @if(in_array(Auth::user()->roleId, [2, 3]))
            <li class="nav-item">
                <a href="#transaksiPeminjamanMenu" class="nav-link dropdown-toggle {{ request()->routeIs('main.transaksi.peminjaman.*') || request()->routeIs('main.peminjaman_sarana.*') ? 'active' : '' }}" data-bs-toggle="collapse" title="Transaksi Peminjaman">
                    <i class="fas fa-calendar-check menu-icon"></i>
                    <span class="menu-text">Peminjaman</span>
                </a>
                <div class="collapse {{ request()->routeIs('main.transaksi.peminjaman.*') || request()->routeIs('main.peminjaman_sarana.*') ? 'show' : '' }}" id="transaksiPeminjamanMenu">
                    <ul class="nav flex-column ps-4">
                        <li class="nav-item">
                            <a href="{{ route('main.transaksi.peminjaman.index') }}" class="nav-link {{ request()->routeIs('main.transaksi.peminjaman.index') ? 'active' : '' }}" title="Diajukan & Disetujui">
                                <i class="fas fa-door-open menu-icon"></i>
                                <span class="menu-text">Diajukan & Disetujui</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('main.transaksi.peminjaman.history') }}" class="nav-link {{ request()->routeIs('main.transaksi.peminjaman.history') ? 'active' : '' }}" title="Selesai & Batal">
                                <i class="fas fa-history menu-icon"></i>
                                <span class="menu-text">Selesai & Batal</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            @endif

            <!-- Divider -->
            @if(Auth::user()->roleId == 1)
            <hr class="sidebar-divider">

            <!-- Landing Page Dropdown -->
            <li class="nav-item">
                <a href="#landingPageMenu" class="nav-link dropdown-toggle {{ request()->routeIs('main.landing.*') ? 'active' : '' }}" data-bs-toggle="collapse" title="Landing Page">
                    <i class="fas fa-desktop menu-icon"></i>
                    <span class="menu-text">Landing Page</span>
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
            @endif

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Data Master Dropdown -->
            @if(in_array(Auth::user()->roleId, [2, 3]))
            <li class="nav-item">
                <a href="#dataMasterMenu" class="nav-link dropdown-toggle {{ request()->routeIs('main.ruangan.*') || request()->routeIs('main.sarana.*') || request()->routeIs('main.paket_ruangan.*') ? 'active' : '' }}" data-bs-toggle="collapse" title="Data Master">
                    <i class="fas fa-database menu-icon"></i>
                    <span class="menu-text">Data Master</span>
                </a>
                <div class="collapse {{ request()->routeIs('main.ruangan.*') || request()->routeIs('main.sarana.*') || request()->routeIs('main.paket_ruangan.*') ? 'show' : '' }}" id="dataMasterMenu">
                    <ul class="nav flex-column ps-4">
                        @if(in_array(Auth::user()->roleId, [2, 3]))
                        <li class="nav-item">
                            <a href="{{ route('main.ruangan.index') }}" class="nav-link {{ request()->routeIs('main.ruangan.*') ? 'active' : '' }}" title="Ruangan">
                                <i class="fas fa-door-open menu-icon"></i>
                                <span class="menu-text">Ruangan</span>
                            </a>
                        </li>
                        @endif
                        @if(in_array(Auth::user()->roleId, [2, 3]))
                        <li class="nav-item">
                            <a href="{{ route('main.sarana.index') }}" class="nav-link {{ request()->routeIs('main.sarana.*') ? 'active' : '' }}" title="Sarana">
                                <i class="fas fa-tools menu-icon"></i>
                                <span class="menu-text">Sarana</span>
                            </a>
                        </li>
                        @endif
                        {{-- Disabled Gedung Menu Link
                        @if(in_array(Auth::user()->roleId, [2, 3]))
                        <li class="nav-item">
                            <a href="{{ route('main.gedung.index') }}" class="nav-link {{ request()->routeIs('main.gedung.*') ? 'active' : '' }}" title="Gedung">
                                <i class="fas fa-building menu-icon"></i>
                                <span class="menu-text">Gedung</span>
                            </a>
                        </li>
                        @endif
                        --}}
                        @if(in_array(Auth::user()->roleId, [2, 3]))
                        <li class="nav-item">
                            <a href="{{ route('main.paket_ruangan.index') }}" class="nav-link {{ request()->routeIs('main.paket_ruangan.*') ? 'active' : '' }}" title="Paket Ruangan">
                                <i class="fas fa-box-open menu-icon"></i>
                                <span class="menu-text">Paket Ruangan</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
            </li>
            @endif

            <!-- Divider -->
            @if(in_array(Auth::user()->roleId, [1, 2]))
            <hr class="sidebar-divider">

            <!-- Laporan -->
            <li class="nav-item">
                <a href="{{ route('main.laporan.index') }}" class="nav-link {{ request()->routeIs('main.laporan.*') ? 'active' : '' }}" title="Laporan">
                    <i class="fas fa-file-invoice menu-icon"></i>
                    <span class="menu-text">Laporan</span>
                </a>
            </li>
            @endif
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
