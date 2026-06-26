<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="index.html" class="app-brand-link">
            <span class="app-brand-logo demo">
                <i class='bx bxs-face-mask text-primary' style="font-size: 2rem;"></i>
            </span>
            <span class="app-brand-text demo menu-text fw-bolder ms-2">Absensi AI</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Dashboard">Dashboard</div>
            </a>
        </li>

        @if(auth()->user()->role == 'admin' || auth()->user()->role == 'guru')
            <li class="menu-item {{ request()->routeIs('attendance.scan') ? 'active' : '' }}">
                <a href="{{ route('attendance.scan') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-scan"></i>
                    <div data-i18n="Scan Absensi">Scan Absensi</div>
                </a>
            </li>
        @endif

        @if(auth()->user()->role == 'admin' || auth()->user()->role == 'pengurus' || auth()->user()->role == 'guru')
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Master Data</span>
            </li>

            <li class="menu-item {{ request()->routeIs('jamaah.*') ? 'active' : '' }}">
                <a href="{{ route('jamaah.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user-pin"></i>
                    <div data-i18n="Data Jamaah">Data Jamaah</div>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('pengajian.*') ? 'active' : '' }}">
                <a href="{{ route('pengajian.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-calendar-event"></i>
                    <div data-i18n="Manajemen Pengajian">Manajemen Pengajian</div>
                </a>
            </li>

            @if(auth()->user()->role == 'admin')
                <li class="menu-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <a href="{{ route('users.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-cog"></i>
                        <div data-i18n="Admin & Pengurus">Admin & Pengurus</div>
                    </a>
                </li>
            @endif
        @endif

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Laporan & Aktivitas</span>
        </li>

        @if(auth()->user()->role == 'admin' || auth()->user()->role == 'guru')
            <li class="menu-item {{ request()->routeIs('rapot.*') ? 'active' : '' }}">
                <a href="{{ route('rapot.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-book-bookmark"></i>
                    <div data-i18n="Manajemen Rapot">Manajemen Rapot</div>
                </a>
            </li>
        @endif

        <li class="menu-item {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
            <a href="{{ route('laporan.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-table"></i>
                <div data-i18n="Riwayat Absensi">Riwayat Absensi</div>
            </a>
        </li>

    </ul>
</aside>