<!-- filepath: c:\laragon\www\PBL_TracerStudy\PBL_TracerStudy\resources\views\layouts\sidebar.blade.php -->

<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2 bg-white my-2"
    id="sidenav-main">
    <div class="sidenav-header">
        <a class="navbar-brand px-4 py-3 m-0" href="#">
            <img src="{{ asset('images/TRACER STUDY LOGO-black.png') }}" class="navbar-brand-img" width="35"
                height="35" alt="main_logo">
            <span class="ms-1 text-sm text-dark">Tracer Study</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0 mb-2">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link {{ Request::is('dashboard') ? 'active bg-gradient-dark text-white' : '' }}"
                    href="{{ route('dashboard') }}">
                    <i class="material-symbols-rounded opacity-5">dashboard</i>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('pertanyaan*') ? 'active bg-gradient-dark text-white' : '' }}"
                    href="{{ route('pertanyaan.index') }}">
                    <i class="material-symbols-rounded opacity-5">quiz</i>
                    <span class="nav-link-text ms-1">Pertanyaan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('alumni*') ? 'active bg-gradient-dark text-white' : '' }}"
                    href="{{ route('alumni.index') }}">
                    <i class="material-symbols-rounded opacity-5">school</i>
                    <span class="nav-link-text ms-1">Alumni</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('instansi*') ? 'active bg-gradient-dark text-white' : '' }}"
                    href="{{ route('instansi.index') }}">
                    <i class="material-symbols-rounded opacity-5">corporate_fare</i>
                    <span class="nav-link-text ms-1">Instansi</span>
                </a>
            </li>
            <!-- filepath: c:\laragon\www\PBL_TracerStudy\PBL_TracerStudy\resources\views\layouts\sidebar.blade.php -->
            <li class="nav-item">
                <a class="nav-link {{ Request::is('penggunaLulusan*') ? 'active bg-gradient-dark text-white' : '' }}"
                    href="{{ route('penggunaLulusan.index') }}">
                    <i class="material-symbols-rounded opacity-5">people</i>
                    <span class="nav-link-text ms-1">Pengguna Lulusan</span>
                </a>
            </li>
            <!-- hanya super admin yang bisa akses jadi disembunyikan -->
            @if (Auth::user() && Auth::user()->canManageAdmins())
                <li class="nav-item {{ request()->is('admin*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.index') }}">
                        <i class="material-symbols-rounded opacity-5">admin_panel_settings</i>
                        <span>Kelola Admin</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>
</aside>
