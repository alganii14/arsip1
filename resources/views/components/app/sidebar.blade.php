<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-white" id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="{{ route('dashboard') }}">
            <img src="{{ asset('assets/img/logo-ct-dark.png') }}" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-1 font-weight-bold">Sistem Arsip</span>
        </a>
    </div>

    <hr class="horizontal dark mt-0">

    <div class="collapse navbar-collapse w-auto h-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-home text-dark"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>

            {{-- Arsip menu - Visible to all users but with different permissions --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('arsip.*') ? 'active' : '' }}" href="{{ route('arsip.index') }}">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-file-archive text-dark"></i>
                    </div>
                    <span class="nav-link-text ms-1">Arsip</span>
                </a>
            </li>

            {{-- JRE menu - Only visible to admin and petugas --}}
            @if(Auth::user()->role !== 'peminjam')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('jre.*') ? 'active' : '' }}" href="{{ route('jre.index') }}">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-archive text-dark"></i>
                    </div>
                    <span class="nav-link-text ms-1">JRE</span>
                </a>
            </li>
            @endif

            {{-- Peminjaman menu - Visible to all users --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('peminjaman.*') ? 'active' : '' }}" href="{{ route('peminjaman.index') }}">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-exchange-alt text-dark"></i>
                    </div>
                    <span class="nav-link-text ms-1">Peminjaman</span>
                </a>
            </li>

            {{-- Tables menu - Only visible to admin and petugas --}}

            {{-- User Management - Only visible to admin --}}
            @if(Auth::user()->role === 'admin')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-users text-dark"></i>
                    </div>
                    <span class="nav-link-text ms-1">Manajemen User</span>
                </a>
            </li>
            @endif

            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Account pages</h6>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}" href="{{ route('profile') }}">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-user text-dark"></i>
                    </div>
                    <span class="nav-link-text ms-1">Profile</span>
                </a>
            </li>

            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                    @csrf
                </form>
                <a class="nav-link" href="javascript:;" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-sign-out-alt text-dark"></i>
                    </div>
                    <span class="nav-link-text ms-1">Logout</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
