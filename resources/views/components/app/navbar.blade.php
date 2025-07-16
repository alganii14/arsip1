<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
    <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
                @if(request()->routeIs('dashboard'))
                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Dashboard</li>
                @elseif(request()->routeIs('profile'))
                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Profile</li>
                @elseif(request()->routeIs('arsip.index'))
                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Arsip</li>
                @elseif(request()->routeIs('arsip.create'))
                    <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ route('arsip.index') }}">Arsip</a></li>
                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Tambah Arsip</li>
                @elseif(request()->routeIs('arsip.edit'))
                    <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ route('arsip.index') }}">Arsip</a></li>
                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Edit Arsip</li>
                @elseif(request()->routeIs('pemindahan.index'))
                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Pemindahan</li>
                @elseif(request()->routeIs('pemindahan.create'))
                    <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ route('pemindahan.index') }}">Pemindahan</a></li>
                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Tambah Pemindahan</li>
                @elseif(request()->routeIs('pemindahan.edit'))
                    <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ route('pemindahan.index') }}">Pemindahan</a></li>
                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Edit Pemindahan</li>
                @elseif(request()->routeIs('pemindahan.show'))
                    <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ route('pemindahan.index') }}">Pemindahan</a></li>
                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Detail Pemindahan</li>
                @endif
            </ol>
            <h6 class="font-weight-bolder mb-0">
                @if(request()->routeIs('dashboard'))
                    Dashboard
                @elseif(request()->routeIs('profile'))
                    Profile
                @elseif(request()->routeIs('arsip.index'))
                    Daftar Arsip
                @elseif(request()->routeIs('arsip.create'))
                    Tambah Arsip
                @elseif(request()->routeIs('arsip.edit'))
                    Edit Arsip
                @elseif(request()->routeIs('pemindahan.index'))
                    Daftar Pemindahan
                @elseif(request()->routeIs('pemindahan.create'))
                    Tambah Pemindahan
                @elseif(request()->routeIs('pemindahan.edit'))
                    Edit Pemindahan
                @elseif(request()->routeIs('pemindahan.show'))
                    Detail Pemindahan
                @endif
            </h6>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
            <ul class="navbar-nav justify-content-center w-100">
                <li class="nav-item d-flex align-items-center">
                    <a href="{{ route('profile') }}" class="nav-link text-body font-weight-bold px-0">
                        <i class="fa fa-user me-sm-1"></i>
                        <span class="d-sm-inline d-none">{{ Auth::user()->name }}</span>
                    </a>
                </li>


            </ul>
        </div>
    </div>
</nav>
