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

            <!-- Divider -->
            <hr class="sidebar-divider">
        </ul>
    </div>

    <!-- Sidebar Footer -->
    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-avatar">
                @php
                    $user = Auth::user();
                    echo strtoupper(substr($user->name ?? 'U', 0, 1));
                @endphp
            </div>
            <div class="sidebar-user-info">
                <p>{{ Auth::user()->name ?? 'User' }}</p>
                <p>{{ Auth::user()->email ?? 'user@email.com' }}</p>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST" style="width: 100%;">
            @csrf
            <button type="submit" class="btn btn-logout w-100">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </button>
        </form>
    </div>
</nav>

<style>
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
        text-align: center;
        flex-shrink: 0;
    }

    .sidebar-menu hr {
        margin: 6px 0;
        border: 0;
        border-top: 1px solid rgba(0,0,0,0.1);
    }

    /* -- Sidebar Footer -- */
    .sidebar-footer {
        padding: 10px;
        border-top: 1px solid rgba(0,0,0,0.1);
        margin-top: auto;
    }

    .sidebar-user {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 8px;
        margin-bottom: 8px;
        background-color: rgba(0,0,0,0.05);
        border-radius: 5px;
    }

    .sidebar-avatar {
        width: 36px;
        height: 36px;
        background-color: rgba(0,0,0,0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 700;
        font-size: 14px;
        flex-shrink: 0;
    }

    .sidebar-user-info {
        flex: 1;
        min-width: 0;
        overflow: hidden;
    }

    .sidebar-user-info p {
        margin: 0;
        font-size: 12px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .sidebar-user-info p:first-child {
        color: #000;
        font-weight: 600;
    }

    .sidebar-user-info p:last-child {
        color: #666;
        font-size: 11px;
    }

    .btn-logout {
        background-color: rgba(0,0,0,0.1);
        color: var(--sidebar-text);
        border: none;
        padding: 8px 12px;
        border-radius: 5px;
        font-size: 12px;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .btn-logout:hover {
        background-color: rgba(0,0,0,0.15);
        color: #000;
    }

    #sidebar.collapsed .sidebar-user-info,
    #sidebar.collapsed .btn-logout {
        display: none;
    }
</style>
