<x-app-layout>
    <style>
        .badge {
            font-size: 0.75rem;
            font-weight: 500;
        }
        .badge.badge-sm {
            padding: 0.375rem 0.75rem;
            border-radius: 0.375rem;
        }
        .dropdown-toggle::after {
            margin-left: 0.5rem;
        }
        .dropdown-item div {
            flex: 1;
        }
        .dropdown-item .font-weight-semibold {
            font-weight: 600;
            margin-bottom: 2px;
        }
        .modal-backdrop {
            z-index: 1040;
        }
        .modal {
            z-index: 1050;
        }
        
        /* Fix sidebar overlap */
        .main-content {
            transition: margin-left 0.3s ease;
            padding-right: 15px;
        }
        
        @media (max-width: 1199px) {
            .main-content {
                margin-left: 0 !important;
                padding-left: 15px;
                padding-right: 15px;
            }
        }
        
        @media (min-width: 1200px) {
            .main-content {
                margin-left: 250px;
                padding-left: 15px;
            }
        }
        
        /* Ensure proper spacing */
        .container-fluid {
            max-width: 100%;
            overflow-x: auto;
            padding-left: 0;
            padding-right: 0;
        }
        
        /* Table responsive improvements */
        .table-responsive {
            min-height: 300px;
            margin-bottom: 0;
        }
        
        .card {
            margin-bottom: 1.5rem;
        }
        
        /* Improve row spacing */
        .row {
            margin-left: 0;
            margin-right: 0;
        }
        
        .col-12, .col-xl-3, .col-sm-6 {
            padding-left: 12px;
            padding-right: 12px;
        }
        
        /* Header card responsive */
        .card-background {
            margin-bottom: 2rem;
        }
        
        /* Header button improvements */
        .card-background .d-flex {
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        
        .card-background .btn {
            margin-bottom: 0.5rem;
            white-space: nowrap;
        }
        
        @media (max-width: 768px) {
            .card-background .d-flex {
                flex-direction: column;
                align-items: stretch;
            }
            
            .card-background .btn {
                width: 100%;
                justify-content: center;
            }
        }
        
        @media (max-width: 992px) {
            .card-background .btn .btn-inner--text {
                font-size: 0.875rem;
            }
        }
        
        /* Dropdown menu improvements */
        .dropdown-menu {
            min-width: 320px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(0, 0, 0, 0.1);
        }
        
        .dropdown-item {
            padding: 0.75rem 1rem;
            transition: all 0.15s ease-in-out;
        }
        
        .dropdown-item:hover {
            background-color: #f8f9fa;
            transform: translateX(2px);
        }
        
        .dropdown-item div {
            line-height: 1.3;
        }
        
        .dropdown-item .font-weight-semibold {
            color: #2d3748;
            margin-bottom: 2px;
        }
        
        .dropdown-item small {
            color: #718096;
        }
        
        /* Modal improvements */
        .modal {
            z-index: 1060;
        }
        
        .modal-backdrop {
            z-index: 1050;
        }
        
        /* Button improvements */
        .btn-blur {
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        
        /* Action buttons spacing */
        .d-flex .btn + .btn {
            margin-left: 0.25rem;
        }
        
        /* Ensure tables don't overflow */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        /* Card improvements */
        .card-header {
            border-bottom: 1px solid #e9ecef;
        }
        
        /* Button group spacing */
        .btn-group {
            margin-right: 0.5rem;
        }
        
        /* Search input improvements */
        .input-group-dynamic {
            min-width: 200px;
        }
        
        @media (max-width: 768px) {
            .input-group-dynamic {
                min-width: 150px;
            }
            
            .btn-group {
                margin-bottom: 0.5rem;
            }
            
            .dropdown-menu {
                min-width: 250px;
            }
        }
    </style>
        <x-app.navbar />
        <div class="main-content">
            <div class="container-fluid px-3 py-4">
           
            
            <div class="row">
                <div class="col-12">
                    <div class="card card-background card-background-after-none align-items-start mt-4 mb-5">
                        <div class="full-background" style="background-image: url('../assets/img/header-blue-purple.jpg')"></div>
                        <div class="card-body text-start p-4 w-100">
                            <h3 class="text-white mb-2">Peminjaman Arsip</h3>
                            <p class="mb-4 font-weight-semibold text-white">
                                @if(Auth::user()->role === 'unit_pengelola')
                                    Kelola peminjaman arsip Anda dari departemen {{ Auth::user()->department }}
                                @else
                                    Kelola peminjaman arsip dokumen dan persetujuan permintaan
                                @endif
                            </p>
                            <div class="d-flex flex-wrap gap-2">
                                @if(Auth::user()->role === 'unit_pengelola')
                                <!-- Button untuk peminjaman fisik -->
                                <a href="{{ route('peminjaman.create') }}?type=fisik" class="btn btn-outline-white btn-blur btn-icon d-flex align-items-center mb-0">
                                    <span class="btn-inner--icon">
                                        <i class="fas fa-file-alt me-2"></i>
                                    </span>
                                    <span class="btn-inner--text">Pinjam Arsip Fisik</span>
                                </a>
                                
                                <!-- Button untuk peminjaman digital -->
                                <button type="button" class="btn btn-outline-white btn-blur btn-icon d-flex align-items-center mb-0" onclick="showDigitalBorrowModal()">
                                    <span class="btn-inner--icon">
                                        <i class="fas fa-download me-2"></i>
                                    </span>
                                    <span class="btn-inner--text">Pinjam Arsip Digital</span>
                                </button>
                                @endif
                                @if(Auth::user()->role === 'unit_kerja')
                                    <a href="{{ route('peminjaman.pending') }}" class="btn btn-outline-white btn-blur btn-icon d-flex align-items-center mb-0">
                                        <span class="btn-inner--icon">
                                            <i class="fas fa-hourglass-half me-2"></i>
                                        </span>
                                        <span class="btn-inner--text">Persetujuan Pending</span>
                                    </a>
                                @endif
                                <a href="{{ route('arsip.index') }}" class="btn btn-outline-white btn-blur btn-icon d-flex align-items-center mb-0">
                                    <span class="btn-inner--icon">
                                        <i class="fas fa-archive me-2"></i>
                                    </span>
                                    <span class="btn-inner--text">Kembali ke Arsip</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <span class="alert-icon"><i class="fas fa-check-circle"></i></span>
                        <span class="alert-text">{{ session('success') }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <span class="alert-icon"><i class="fas fa-exclamation-circle"></i></span>
                        <span class="alert-text">{{ session('error') }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>
            @endif

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card border shadow-xs">
                        <div class="card-body text-start p-3">
                            <div class="d-flex">
                                <div class="icon icon-shape icon-sm bg-primary text-white text-center border-radius-sm d-flex align-items-center justify-content-center">
                                    <i class="fas fa-download text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-uppercase font-weight-bold opacity-7">Total Peminjaman</p>
                                        <h5 class="font-weight-bolder">
                                            {{ $peminjamans->count() }}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card border shadow-xs">
                        <div class="card-body text-start p-3">
                            <div class="d-flex">
                                <div class="icon icon-shape icon-sm bg-warning text-white text-center border-radius-sm d-flex align-items-center justify-content-center">
                                    <i class="fas fa-clock text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-uppercase font-weight-bold opacity-7">Sedang Dipinjam</p>
                                        <h5 class="font-weight-bolder">
                                            {{ $peminjamans->where('status', 'dipinjam')->count() }}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card border shadow-xs">
                        <div class="card-body text-start p-3">
                            <div class="d-flex">
                                <div class="icon icon-shape icon-sm bg-success text-white text-center border-radius-sm d-flex align-items-center justify-content-center">
                                    <i class="fas fa-check text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-uppercase font-weight-bold opacity-7">Dikembalikan</p>
                                        <h5 class="font-weight-bolder">
                                            {{ $peminjamans->where('status', 'dikembalikan')->count() }}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6">
                    <div class="card border shadow-xs">
                        <div class="card-body text-start p-3">
                            <div class="d-flex">
                                <div class="icon icon-shape icon-sm bg-danger text-white text-center border-radius-sm d-flex align-items-center justify-content-center">
                                    <i class="fas fa-exclamation-triangle text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-uppercase font-weight-bold opacity-7">Terlambat</p>
                                        <h5 class="font-weight-bolder">
                                            {{ $peminjamans->where('status', 'terlambat')->count() }}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border shadow-xs">
                        <div class="card-header border-bottom pb-0">
                            <div class="d-sm-flex align-items-center">
                                <div>
                                    <h6 class="font-weight-semibold text-lg mb-0">
                                        @if(Auth::user()->role === 'unit_kerja')
                                            Semua Arsip Tersedia
                                        @else
                                            Arsip Tersedia untuk Dipinjam
                                        @endif
                                    </h6>
                                    <p class="text-sm">
                                        @if(Auth::user()->role === 'unit_kerja')
                                            Daftar semua arsip yang dapat Anda akses langsung
                                        @else
                                            Daftar arsip yang dapat Anda pinjam (kecuali milik sendiri)
                                        @endif
                                    </p>
                                </div>
                                <div class="ms-auto d-flex">
                                    <div class="input-group input-group-sm input-group-dynamic me-3 w-auto">
                                        <span class="input-group-text text-body">
                                            <i class="fas fa-search"></i>
                                        </span>
                                        <input type="text" class="form-control form-control-sm ps-3" placeholder="Cari arsip tersedia..." id="searchAvailableArsip">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 py-0">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0" id="availableArsipTable">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7">Kode Arsip</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Nama Dokumen</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Kategori</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Tanggal Arsip</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Pemilik</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Status</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($availableArsips as $arsip)
                                        <tr>
                                            <td class="ps-2">
                                                <p class="text-sm font-weight-semibold mb-0">{{ $arsip->kode }}</p>
                                            </td>
                                            <td>
                                                <p class="text-sm font-weight-semibold mb-0">{{ $arsip->nama_dokumen }}</p>
                                                <p class="text-xs text-secondary mb-0">{{ strlen($arsip->keterangan) > 50 ? substr($arsip->keterangan, 0, 50) . '...' : $arsip->keterangan }}</p>
                                            </td>
                                            <td>
                                                <p class="text-sm font-weight-normal mb-0">{{ $arsip->kategori }}</p>
                                            </td>
                                            <td>
                                                <p class="text-sm font-weight-normal mb-0">{{ $arsip->tanggal_arsip->format('d/m/Y') }}</p>
                                            </td>
                                            <td>
                                                <p class="text-sm font-weight-normal mb-0">{{ $arsip->creator->name ?? 'N/A' }}</p>
                                                <p class="text-xs text-secondary mb-0">{{ $arsip->creator->department ?? '' }}</p>
                                            </td>
                                            <td>
                                                <span class="badge badge-sm border border-success text-success bg-success">
                                                    Tersedia
                                                </span>
                                            </td>
                                            <td>
                                                @if(Auth::user()->role === 'unit_kerja')
                                                <div class="d-flex">
                                                    <a href="{{ route('arsip.detail', $arsip->id) }}" class="btn btn-sm btn-info me-2">
                                                        <i class="fas fa-eye me-1"></i> Detail
                                                    </a>
                                                </div>
                                                @else
                                                <span class="text-muted text-sm">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center p-4">
                                                <div class="mb-3">
                                                    <i class="fas fa-inbox text-muted" style="font-size: 2rem;"></i>
                                                </div>
                                                <h6 class="text-muted mb-2">Tidak ada arsip yang tersedia untuk dipinjam</h6>
                                                <p class="text-muted text-sm mb-0">Semua arsip sedang dipinjam atau tidak ada arsip dari seksi lain</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border shadow-xs">
                        <div class="card-header border-bottom pb-0">
                            <div class="d-sm-flex align-items-center">
                                <div>
                                    <h6 class="font-weight-semibold text-lg mb-0">Riwayat Peminjaman Arsip</h6>
                                    <p class="text-sm">
                                        @if(Auth::user()->role === 'unit_pengelola')
                                            Informasi tentang peminjaman arsip Anda
                                        @else
                                            Informasi tentang semua peminjaman arsip
                                        @endif
                                    </p>
                                </div>
                                <div class="ms-auto d-flex">
                                    <!-- Export Buttons -->
                                    <div class="btn-group me-3">
                                        <button type="button" class="btn btn-outline-success btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-download me-1"></i> Export Laporan
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('peminjaman.export.excel') }}">
                                                    <i class="fas fa-file-excel me-2 text-success"></i> Export Excel
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('peminjaman.export.pdf') }}">
                                                    <i class="fas fa-file-pdf me-2 text-danger"></i> Export PDF
                                                </a>
                                            </li>
                                        </ul>
                                    </div>

                                    @if(Auth::user()->role === 'unit_kerja')
                                    <form action="{{ route('peminjaman.check-overdue') }}" method="POST" class="me-3">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-dark">
                                            <i class="fas fa-clock me-1"></i> Cek Keterlambatan
                                        </button>
                                    </form>
                                    @endif
                                    <div class="input-group input-group-sm input-group-dynamic w-auto">
                                        <span class="input-group-text text-body">
                                            <i class="fas fa-search"></i>
                                        </span>
                                        <input type="text" class="form-control form-control-sm ps-3" placeholder="Cari riwayat peminjaman..." id="searchPeminjaman">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 py-0">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0" id="peminjamanTable">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7">Arsip</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Peminjam</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Jenis</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Tanggal Pinjam</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Batas Waktu</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Tanggal Kembali</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Status</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Konfirmasi</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Durasi</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($peminjamans as $peminjaman)
                                        <tr class="{{ $peminjaman->status === 'terlambat' ? 'bg-danger-subtle' : '' }}">
                                            <td class="ps-2">
                                                <p class="text-sm font-weight-semibold mb-0">{{ $peminjaman->arsip->kode }}</p>
                                                <p class="text-xs text-secondary mb-0">{{ $peminjaman->arsip->nama_dokumen }}</p>
                                            </td>
                                            <td>
                                                <p class="text-sm font-weight-semibold mb-0">{{ $peminjaman->peminjam }}</p>
                                                <p class="text-xs text-secondary mb-0">
                                                    {{ $peminjaman->departemen ? $peminjaman->departemen : '' }}
                                                    {{ $peminjaman->jabatan ? '- ' . $peminjaman->jabatan : '' }}
                                                </p>
                                            </td>
                                            <td>
                                                @php
                                                    $jenisClass = 'secondary';
                                                    $jenisText = 'Umum';
                                                    $jenisIcon = 'fas fa-file';

                                                    if($peminjaman->jenis_peminjaman === 'digital') {
                                                        $jenisClass = 'success';
                                                        $jenisText = 'Digital';
                                                        $jenisIcon = 'fas fa-download';
                                                    } elseif($peminjaman->jenis_peminjaman === 'fisik') {
                                                        $jenisClass = 'primary';
                                                        $jenisText = 'Fisik';
                                                        $jenisIcon = 'fas fa-file-alt';
                                                    }
                                                @endphp
                                                <span class="badge badge-sm border border-{{ $jenisClass }} text-{{ $jenisClass }} bg-{{ $jenisClass }}">
                                                    <i class="{{ $jenisIcon }} me-1"></i>{{ $jenisText }}
                                                </span>
                                            </td>
                                            <td>
                                                <p class="text-sm font-weight-normal mb-0">{{ $peminjaman->tanggal_pinjam->format('d/m/Y') }}</p>
                                            </td>
                                            <td>
                                                <p class="text-sm font-weight-normal mb-0">{{ $peminjaman->batas_waktu->format('d/m/Y') }}</p>
                                            </td>
                                            <td>
                                                <p class="text-sm font-weight-normal mb-0">
                                                    {{ $peminjaman->tanggal_kembali ? $peminjaman->tanggal_kembali->format('d/m/Y') : '-' }}
                                                </p>
                                            </td>
                                            <td>
                                                @php
                                                    $statusClass = 'primary';
                                                    $statusText = 'Dipinjam';

                                                    if($peminjaman->status === 'dipinjam') {
                                                        $statusClass = 'primary';
                                                        $statusText = 'Dipinjam';
                                                    } elseif($peminjaman->status === 'dikembalikan') {
                                                        $statusClass = 'success';
                                                        $statusText = 'Dikembalikan';
                                                    } elseif($peminjaman->status === 'terlambat') {
                                                        $statusClass = 'danger';
                                                        $statusText = 'Terlambat';
                                                    } elseif($peminjaman->status === 'pending') {
                                                        $statusClass = 'warning';
                                                        $statusText = 'Pending';
                                                    }
                                                @endphp
                                                <span class="badge badge-sm border border-{{ $statusClass }} text-{{ $statusClass }} bg-{{ $statusClass }}">
                                                    {{ $statusText }}
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    $confirmationClass = 'secondary';
                                                    $confirmationText = 'N/A';

                                                    if($peminjaman->confirmation_status === 'pending') {
                                                        $confirmationClass = 'warning';
                                                        $confirmationText = 'Pending';
                                                    } elseif($peminjaman->confirmation_status === 'approved') {
                                                        $confirmationClass = 'success';
                                                        $confirmationText = 'Disetujui';
                                                    } elseif($peminjaman->confirmation_status === 'rejected') {
                                                        $confirmationClass = 'danger';
                                                        $confirmationText = 'Ditolak';
                                                    }
                                                @endphp
                                                <span class="badge badge-sm border border-{{ $confirmationClass }} text-{{ $confirmationClass }} bg-{{ $confirmationClass }}">
                                                    {{ $confirmationText }}
                                                </span>
                                            </td>
                                            <td>
                                                <p class="text-sm font-weight-normal mb-0">
                                                    {{ $peminjaman->getDurasiPinjam() ? $peminjaman->getDurasiPinjam() . ' hari' : '-' }}
                                                </p>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <a href="{{ route('peminjaman.show', $peminjaman->id) }}" class="btn btn-sm btn-info me-2">
                                                        <i class="fas fa-eye me-1"></i> Detail
                                                    </a>

                                                    @if(Auth::user()->role === 'unit_pengelola')
                                                        {{-- Hanya peminjaman digital yang bisa dikembalikan oleh unit pengelola sendiri --}}
                                                        @if($peminjaman->jenis_peminjaman === 'digital' &&
                                                            $peminjaman->peminjam_user_id == Auth::id() &&
                                                            $peminjaman->status !== 'dikembalikan' &&
                                                            $peminjaman->confirmation_status === 'approved')
                                                            <a href="{{ route('peminjaman.return-form', $peminjaman->id) }}" class="btn btn-sm btn-success me-2">
                                                                <i class="fas fa-undo me-1"></i> Kembalikan
                                                            </a>
                                                        @endif
                                                    @else
                                                        {{-- Unit kerja bisa mengembalikan arsip fisik yang sudah disetujui --}}
                                                        @if($peminjaman->jenis_peminjaman === 'fisik' &&
                                                            $peminjaman->status !== 'dikembalikan' &&
                                                            $peminjaman->confirmation_status === 'approved')
                                                            <a href="{{ route('peminjaman.return-form', $peminjaman->id) }}" class="btn btn-sm btn-success me-2">
                                                                <i class="fas fa-undo me-1"></i> Kembalikan
                                                            </a>
                                                        @endif

                                                        <a href="{{ route('peminjaman.edit', $peminjaman->id) }}" class="btn btn-sm btn-warning me-2">
                                                            <i class="fas fa-edit me-1"></i> Edit
                                                        </a>

                                                        <form action="{{ route('peminjaman.destroy', $peminjaman->id) }}" method="POST" style="display:inline;">
                                                            @csrf @method('DELETE')
                                                            <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus data peminjaman ini?')">
                                                                <i class="fas fa-trash me-1"></i> Hapus
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if(count($peminjamans) == 0)
                            <div class="text-center p-4">
                                <p class="mb-0">Belum ada data peminjaman arsip</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <x-app.footer />
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Debug: Check if modal exists
            const modalElement = document.getElementById('digitalBorrowModal');
            console.log('Digital Borrow Modal exists:', !!modalElement);
            console.log('Bootstrap available:', typeof bootstrap !== 'undefined');
            console.log('jQuery available:', typeof $ !== 'undefined');
            
            // Responsive sidebar adjustment
            function adjustMainContent() {
                const mainContent = document.querySelector('.main-content');
                const sidebar = document.querySelector('.sidenav');
                
                if (window.innerWidth >= 1200) {
                    if (sidebar && !sidebar.classList.contains('bg-white')) {
                        mainContent.style.marginLeft = '250px';
                    }
                } else {
                    mainContent.style.marginLeft = '0';
                }
            }
            
            // Initial adjustment
            adjustMainContent();
            
            // Adjust on window resize
            window.addEventListener('resize', adjustMainContent);
            
            // Search functionality for available archives
            const searchAvailableInput = document.getElementById('searchAvailableArsip');
            const availableTableRows = document.querySelectorAll('#availableArsipTable tbody tr');

            searchAvailableInput.addEventListener('keyup', function() {
                const searchValue = this.value.toLowerCase();
                let visibleCount = 0;

                availableTableRows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    if (text.includes(searchValue)) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Show "no results" message if no rows are visible
                handleNoResults('availableArsipTable', visibleCount, searchValue, 'arsip tersedia');
            });

            // Search functionality for borrowing records
            const searchPeminjamanInput = document.getElementById('searchPeminjaman');
            const peminjamanTableRows = document.querySelectorAll('#peminjamanTable tbody tr');

            searchPeminjamanInput.addEventListener('keyup', function() {
                const searchValue = this.value.toLowerCase();
                let visibleCount = 0;

                peminjamanTableRows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    if (text.includes(searchValue)) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Show "no results" message if no rows are visible
                handleNoResults('peminjamanTable', visibleCount, searchValue, 'riwayat peminjaman');
            });

            // Function to handle no results message
            function handleNoResults(tableId, visibleCount, searchTerm, itemType) {
                const table = document.getElementById(tableId);
                const tbody = table.querySelector('tbody');
                const noResultsRow = tbody.querySelector('.no-results-row');

                if (visibleCount === 0 && searchTerm !== '') {
                    if (!noResultsRow) {
                        const newRow = document.createElement('tr');
                        newRow.className = 'no-results-row';
                        newRow.innerHTML = `
                            <td colspan="100%" class="text-center py-4">
                                <div class="text-center">
                                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                    <h6 class="text-muted mb-2">Tidak ditemukan ${itemType}</h6>
                                    <p class="text-muted text-sm mb-0">Tidak ada data yang cocok dengan pencarian "${searchTerm}"</p>
                                </div>
                            </td>
                        `;
                        tbody.appendChild(newRow);
                    }
                } else if (noResultsRow) {
                    noResultsRow.remove();
                }
            }

            // Export button loading state
            const exportButtons = document.querySelectorAll('.dropdown-item');
            exportButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    if (this.href && this.href.includes('export')) {
                        const originalText = this.innerHTML;
                        this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Mengunduh...';
                        this.style.pointerEvents = 'none';

                        setTimeout(() => {
                            this.innerHTML = originalText;
                            this.style.pointerEvents = 'auto';
                        }, 3000);
                    }
                });
            });

            // Add hover effects to summary cards
            const summaryCards = document.querySelectorAll('.card');
            summaryCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                    this.style.transition = 'transform 0.2s ease';
                });

                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Search functionality for Available Arsip
            document.getElementById('searchAvailableArsip').addEventListener('keyup', function() {
                const searchValue = this.value.toLowerCase();
                const availableTable = document.getElementById('availableArsipTable');
                const rows = availableTable.querySelectorAll('tbody tr');

                rows.forEach(function(row) {
                    const kodeArsip = row.querySelector('td:nth-child(1)')?.textContent.toLowerCase() || '';
                    const namaDokumen = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase() || '';
                    const kategori = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() || '';
                    const tanggal = row.querySelector('td:nth-child(4)')?.textContent.toLowerCase() || '';
                    const pemilik = row.querySelector('td:nth-child(5)')?.textContent.toLowerCase() || '';

                    if (kodeArsip.includes(searchValue) ||
                        namaDokumen.includes(searchValue) ||
                        kategori.includes(searchValue) ||
                        tanggal.includes(searchValue) ||
                        pemilik.includes(searchValue)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Show or hide empty state message
                const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
                const emptyMessage = availableTable.querySelector('tbody tr[data-empty-message]');

                if (visibleRows.length === 0 && !emptyMessage) {
                    // Create and insert empty message if not exists
                    const emptyRow = document.createElement('tr');
                    emptyRow.setAttribute('data-empty-message', 'true');
                    emptyRow.innerHTML = `
                        <td colspan="7" class="text-center p-4">
                            <div class="mb-3">
                                <i class="fas fa-search text-muted" style="font-size: 2rem;"></i>
                            </div>
                            <h6 class="text-muted mb-2">Tidak ditemukan hasil untuk pencarian "${searchValue}"</h6>
                            <p class="text-muted text-sm mb-0">Coba dengan kata kunci lain atau reset pencarian</p>
                        </td>
                    `;
                    availableTable.querySelector('tbody').appendChild(emptyRow);
                } else if (visibleRows.length > 0 && emptyMessage) {
                    // Remove empty message if results found
                    emptyMessage.remove();
                }
            });

            // Search functionality for Peminjaman History
            document.getElementById('searchPeminjaman').addEventListener('keyup', function() {
                const searchValue = this.value.toLowerCase();
                const peminjamanTable = document.getElementById('peminjamanTable');
                const rows = peminjamanTable.querySelectorAll('tbody tr');

                rows.forEach(function(row) {
                    const arsip = row.querySelector('td:nth-child(1)')?.textContent.toLowerCase() || '';
                    const peminjam = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase() || '';
                    const jenis = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() || '';
                    const tglPinjam = row.querySelector('td:nth-child(4)')?.textContent.toLowerCase() || '';
                    const batasWaktu = row.querySelector('td:nth-child(5)')?.textContent.toLowerCase() || '';
                    const tglKembali = row.querySelector('td:nth-child(6)')?.textContent.toLowerCase() || '';
                    const status = row.querySelector('td:nth-child(7)')?.textContent.toLowerCase() || '';

                    if (arsip.includes(searchValue) ||
                        peminjam.includes(searchValue) ||
                        jenis.includes(searchValue) ||
                        tglPinjam.includes(searchValue) ||
                        batasWaktu.includes(searchValue) ||
                        tglKembali.includes(searchValue) ||
                        status.includes(searchValue)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Show or hide empty state message
                const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
                const emptyMessage = peminjamanTable.querySelector('tbody tr[data-empty-message]');

                if (visibleRows.length === 0 && !emptyMessage) {
                    // Create and insert empty message if not exists
                    const emptyRow = document.createElement('tr');
                    emptyRow.setAttribute('data-empty-message', 'true');
                    emptyRow.innerHTML = `                                        <td colspan="10" class="text-center p-4">
                            <div class="mb-3">
                                <i class="fas fa-search text-muted" style="font-size: 2rem;"></i>
                            </div>
                            <h6 class="text-muted mb-2">Tidak ditemukan hasil untuk pencarian "${searchValue}"</h6>
                            <p class="text-muted text-sm mb-0">Coba dengan kata kunci lain atau reset pencarian</p>
                        </td>
                    `;
                    peminjamanTable.querySelector('tbody').appendChild(emptyRow);
                } else if (visibleRows.length > 0 && emptyMessage) {
                    // Remove empty message if results found
                    emptyMessage.remove();
                }
            });

            // Add clear button to search inputs
            document.querySelectorAll('#searchAvailableArsip, #searchPeminjaman').forEach(input => {
                const wrapper = document.createElement('div');
                wrapper.className = 'position-relative';
                input.parentNode.insertBefore(wrapper, input);
                wrapper.appendChild(input);

                const clearButton = document.createElement('button');
                clearButton.className = 'btn btn-link position-absolute top-50 end-0 translate-middle-y text-muted p-1';
                clearButton.innerHTML = '<i class="fas fa-times"></i>';
                clearButton.style.display = 'none';
                wrapper.appendChild(clearButton);

                input.addEventListener('input', function() {
                    clearButton.style.display = this.value ? 'block' : 'none';
                });

                clearButton.addEventListener('click', function() {
                    input.value = '';
                    input.dispatchEvent(new Event('keyup'));
                    this.style.display = 'none';
                    input.focus();
                });
            });
        });

        // Function untuk menampilkan modal peminjaman digital
        function showDigitalBorrowModal() {
            console.log('showDigitalBorrowModal called'); // Debug log
            
            // Try Bootstrap 5 method first
            if (typeof bootstrap !== 'undefined') {
                const modalElement = document.getElementById('digitalBorrowModal');
                if (modalElement) {
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                } else {
                    console.error('Modal element not found');
                }
            } 
            // Fallback to jQuery if available
            else if (typeof $ !== 'undefined') {
                $('#digitalBorrowModal').modal('show');
            }
            // Manual fallback
            else {
                const modal = document.getElementById('digitalBorrowModal');
                if (modal) {
                    modal.style.display = 'block';
                    modal.classList.add('show');
                    document.body.classList.add('modal-open');
                    
                    // Add backdrop
                    const backdrop = document.createElement('div');
                    backdrop.className = 'modal-backdrop fade show';
                    document.body.appendChild(backdrop);
                    
                    // Close modal when clicking backdrop
                    backdrop.addEventListener('click', function() {
                        modal.style.display = 'none';
                        modal.classList.remove('show');
                        document.body.classList.remove('modal-open');
                        backdrop.remove();
                    });
                } else {
                    alert('Modal tidak dapat ditampilkan. Silakan refresh halaman.');
                }
            }
        }

        // Function untuk menutup modal peminjaman digital
        function closeDigitalBorrowModal() {
            const modal = document.getElementById('digitalBorrowModal');
            const backdrop = document.querySelector('.modal-backdrop');
            
            if (modal) {
                modal.style.display = 'none';
                modal.classList.remove('show');
                document.body.classList.remove('modal-open');
                
                if (backdrop) {
                    backdrop.remove();
                }
            }
        }

        // Function untuk meminjam arsip digital
        function borrowDigital(arsipId) {
            console.log('borrowDigital called with arsipId:', arsipId); // Debug log
            if (confirm('Apakah Anda yakin ingin meminjam arsip ini secara digital?')) {
                // Show loading
                const button = document.querySelector(`[data-arsip-id="${arsipId}"]`);
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Memproses...';
                button.disabled = true;
                
                // Create form and submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("peminjaman.digital.store") }}';

                // Add CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                // Add arsip_id
                const arsipIdInput = document.createElement('input');
                arsipIdInput.type = 'hidden';
                arsipIdInput.name = 'arsip_id';
                arsipIdInput.value = arsipId;
                form.appendChild(arsipIdInput);

                // Add to body and submit
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Search functionality for digital arsip modal
        document.addEventListener('DOMContentLoaded', function() {
            const searchDigitalInput = document.getElementById('searchDigitalArsip');
            if (searchDigitalInput) {
                searchDigitalInput.addEventListener('keyup', function() {
                    const searchValue = this.value.toLowerCase();
                    const digitalTable = document.getElementById('digitalArsipTable');
                    const rows = digitalTable.querySelectorAll('tbody tr');

                    rows.forEach(function(row) {
                        const kode = row.querySelector('td:nth-child(1)')?.textContent.toLowerCase() || '';
                        const nama = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase() || '';
                        const kategori = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() || '';

                        if (kode.includes(searchValue) || nama.includes(searchValue) || kategori.includes(searchValue)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }
        });
    </script>

    <!-- Modal Peminjaman Digital -->
    <div class="modal fade" id="digitalBorrowModal" tabindex="-1" aria-labelledby="digitalBorrowModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="digitalBorrowModalLabel">
                        <i class="fas fa-download me-2"></i>
                        Peminjaman Digital (E-Arsip)
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeDigitalBorrowModal()"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Peminjaman Digital:</strong> Anda dapat langsung mengakses file arsip tanpa mengisi form. Peminjaman akan otomatis disetujui dengan batas waktu 7 hari.
                    </div>

                    <div class="alert alert-success mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle me-2"></i>
                            <div>
                                <strong>Keunggulan Peminjaman Digital:</strong>
                                <ul class="mb-0 mt-1">
                                    <li>Akses instan tanpa persetujuan admin</li>
                                    <li>Dapat mengunduh file arsip langsung</li>
                                    <li>Pengembalian otomatis atau manual</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" class="form-control" id="searchDigitalArsip" placeholder="Cari arsip untuk dipinjam secara digital...">
                        </div>
                    </div>

                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-sm" id="digitalArsipTable">
                            <thead class="thead-light sticky-top">
                                <tr>
                                    <th>Kode Arsip</th>
                                    <th>Nama Dokumen</th>
                                    <th>Kategori</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($availableArsips as $arsip)
                                <tr>
                                    <td>
                                        <small class="font-weight-semibold">{{ $arsip->kode }}</small>
                                    </td>
                                    <td>
                                        <small class="font-weight-semibold">{{ $arsip->nama_dokumen }}</small>
                                        <br>
                                        <small class="text-muted">{{ strlen($arsip->keterangan) > 30 ? substr($arsip->keterangan, 0, 30) . '...' : $arsip->keterangan }}</small>
                                    </td>
                                    <td>
                                        <small>{{ $arsip->kategori }}</small>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-success btn-sm" data-arsip-id="{{ $arsip->id }}" onclick="borrowDigital(this.dataset.arsipId)">
                                            <i class="fas fa-download me-1"></i> Pinjam Digital
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">
                                        <i class="fas fa-inbox fa-2x mb-2"></i>
                                        <br>
                                        Tidak ada arsip yang tersedia untuk dipinjam
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeDigitalBorrowModal()">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
