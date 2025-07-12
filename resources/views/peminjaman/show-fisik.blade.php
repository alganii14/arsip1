<x-app-layout>
    <style>
        .border-purple {
            border-color: #8b5cf6 !important;
        }
        .text-purple {
            color: #8b5cf6 !important;
        }
        .border-3 {
            border-width: 3px !important;
        }
        .alert {
            border-radius: 10px;
        }
        .card {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border-radius: 12px;
        }
        .badge {
            font-size: 0.8rem;
            padding: 0.5rem 0.75rem;
        }
    </style>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-12">
                    <div class="card card-background card-background-after-none align-items-start mt-4 mb-5">
                        <div class="full-background" style="background-image: url('../assets/img/header-blue-purple.jpg')"></div>
                        <div class="card-body text-start p-4 w-100">
                            <h3 class="text-white mb-2">
                                <i class="fas fa-file-alt me-2"></i>
                                Detail Peminjaman Fisik
                            </h3>
                            <p class="mb-4 font-weight-semibold text-white">
                                Informasi lengkap peminjaman arsip fisik
                            </p>
                            <a href="{{ route('peminjaman.index') }}" class="btn btn-outline-white btn-blur btn-icon d-flex align-items-center mb-0">
                                <span class="btn-inner--icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block me-2">
                                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                                    </svg>
                                </span>
                                <span class="btn-inner--text">Kembali</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

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

            <div class="row">
                <div class="col-12">
                    <div class="card border shadow-xs mb-4">
                        <div class="card-header border-bottom pb-0">
                            <div class="d-sm-flex align-items-center mb-3">
                                <div>
                                    <h6 class="font-weight-semibold text-lg mb-0">Informasi Peminjaman Fisik</h6>
                                    <p class="text-sm mb-sm-0">Detail peminjaman arsip fisik</p>
                                </div>
                                <div class="ms-auto d-flex">
                                    <span class="badge bg-warning me-2">
                                        <i class="fas fa-file-alt me-1"></i>
                                        Peminjaman Fisik
                                    </span>

                                    {{-- Status Badge --}}
                                    @if($peminjaman->confirmation_status === 'pending')
                                        <span class="badge bg-warning">
                                            <i class="fas fa-clock me-1"></i>
                                            Menunggu Persetujuan
                                        </span>
                                    @elseif($peminjaman->confirmation_status === 'approved')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>
                                            Disetujui
                                        </span>
                                    @elseif($peminjaman->confirmation_status === 'rejected')
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times me-1"></i>
                                            Ditolak
                                        </span>
                                    @endif

                                    {{-- Hanya admin yang bisa mengembalikan arsip fisik yang sudah disetujui --}}
                                    @if(Auth::user()->role === 'admin' &&
                                        $peminjaman->status !== 'dikembalikan' &&
                                        $peminjaman->confirmation_status === 'approved')
                                        <a href="{{ route('peminjaman.return-form', $peminjaman->id) }}" class="btn btn-sm btn-success ms-2">
                                            <i class="fas fa-undo me-1"></i> Kembalikan Arsip
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <!-- Identitas Peminjam -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="font-weight-bolder mb-3 text-primary">
                                        <i class="fas fa-user me-2"></i>
                                        Identitas Peminjam
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="border-start border-primary border-3 ps-3 mb-3">
                                                <label class="text-xs text-muted mb-1">Nama Lengkap</label>
                                                <p class="font-weight-semibold mb-0">{{ $peminjaman->peminjam ?: '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="border-start border-primary border-3 ps-3 mb-3">
                                                <label class="text-xs text-muted mb-1">Kontak</label>
                                                <p class="font-weight-semibold mb-0">{{ $peminjaman->kontak ?: '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="border-start border-primary border-3 ps-3 mb-3">
                                                <label class="text-xs text-muted mb-1">Departemen</label>
                                                <p class="font-weight-semibold mb-0">{{ $peminjaman->departemen ?: '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Detail Waktu Peminjaman -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="font-weight-bolder mb-3 text-info">
                                        <i class="fas fa-clock me-2"></i>
                                        Waktu Peminjaman
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="border-start border-info border-3 ps-3 mb-3">
                                                <label class="text-xs text-muted mb-1">Tanggal Pinjam</label>
                                                <p class="font-weight-semibold mb-0">
                                                    {{ $peminjaman->tanggal_pinjam ? $peminjaman->tanggal_pinjam->format('d/m/Y') : '-' }}
                                                </p>
                                                <small class="text-muted">
                                                    {{ $peminjaman->tanggal_pinjam ? $peminjaman->tanggal_pinjam->format('H:i') : '' }}
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="border-start border-warning border-3 ps-3 mb-3">
                                                <label class="text-xs text-muted mb-1">Batas Waktu</label>
                                                <p class="font-weight-semibold mb-0">
                                                    {{ $peminjaman->batas_waktu ? $peminjaman->batas_waktu->format('d/m/Y') : '-' }}
                                                    @if($peminjaman->status === 'terlambat')
                                                        <span class="badge bg-danger ms-2">Terlambat</span>
                                                    @endif
                                                </p>
                                                <small class="text-muted">
                                                    {{ $peminjaman->batas_waktu ? $peminjaman->batas_waktu->format('H:i') : '' }}
                                                </small>
                                            </div>
                                        </div>
                                        @if($peminjaman->tanggal_kembali)
                                        <div class="col-md-4">
                                            <div class="border-start border-success border-3 ps-3 mb-3">
                                                <label class="text-xs text-muted mb-1">Tanggal Kembali</label>
                                                <p class="font-weight-semibold mb-0">
                                                    {{ $peminjaman->tanggal_kembali->format('d/m/Y') }}
                                                </p>
                                                <small class="text-muted">
                                                    {{ $peminjaman->tanggal_kembali->format('H:i') }}
                                                </small>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Status Peminjaman -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="font-weight-bolder mb-3 text-success">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Status Peminjaman
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="border-start border-success border-3 ps-3 mb-3">
                                                <label class="text-xs text-muted mb-1">Status Peminjaman</label>
                                                <p class="mb-0">
                                                    @if($peminjaman->status === 'pending')
                                                        <span class="badge bg-warning">
                                                            <i class="fas fa-clock me-1"></i>Pending
                                                        </span>
                                                    @elseif($peminjaman->status === 'dipinjam')
                                                        <span class="badge bg-info">
                                                            <i class="fas fa-file-export me-1"></i>Dipinjam
                                                        </span>
                                                    @elseif($peminjaman->status === 'dikembalikan')
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check me-1"></i>Dikembalikan
                                                        </span>
                                                    @elseif($peminjaman->status === 'terlambat')
                                                        <span class="badge bg-danger">
                                                            <i class="fas fa-exclamation-triangle me-1"></i>Terlambat
                                                        </span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="border-start border-success border-3 ps-3 mb-3">
                                                <label class="text-xs text-muted mb-1">Status Konfirmasi</label>
                                                <p class="mb-0">
                                                    @if($peminjaman->confirmation_status === 'pending')
                                                        <span class="badge bg-warning">
                                                            <i class="fas fa-hourglass-half me-1"></i>Menunggu Persetujuan
                                                        </span>
                                                    @elseif($peminjaman->confirmation_status === 'approved')
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check-circle me-1"></i>Disetujui
                                                        </span>
                                                    @elseif($peminjaman->confirmation_status === 'rejected')
                                                        <span class="badge bg-danger">
                                                            <i class="fas fa-times-circle me-1"></i>Ditolak
                                                        </span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                            @if($peminjaman->tujuan_peminjaman)
                            <hr class="my-4">

                            <!-- Informasi Tambahan -->
                            <div class="row">
                                <div class="col-12">
                                    <h6 class="font-weight-bolder mb-3 text-secondary">
                                        <i class="fas fa-sticky-note me-2"></i>
                                        Informasi Tambahan
                                    </h6>
                                    <div class="border-start border-secondary border-3 ps-3 mb-3">
                                        <label class="text-xs text-muted mb-1">Tujuan Peminjaman</label>
                                        <p class="mb-0">{{ $peminjaman->tujuan_peminjaman }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Informasi Arsip (Tanpa Akses File) -->
            <div class="row">
                <div class="col-12">
                    <div class="card border shadow-xs mb-4">
                        <div class="card-header border-bottom pb-0">
                            <div class="d-sm-flex align-items-center mb-3">
                                <div>
                                    <h6 class="font-weight-semibold text-lg mb-0">Informasi Arsip</h6>
                                    <p class="text-sm mb-sm-0">Detail arsip yang dipinjam (fisik)</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="alert alert-info border-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Peminjaman Fisik:</strong> Untuk peminjaman fisik, Anda tidak dapat mengakses file digital arsip melalui sistem. Silahkan hubungi petugas arsip untuk mendapatkan dokumen fisik.
                            </div>

                            @if(!$peminjaman->arsip)
                                <div class="alert alert-warning border-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Perhatian:</strong> Data arsip tidak ditemukan. Arsip ID: {{ $peminjaman->arsip_id }}
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-8">
                                    <h6 class="font-weight-bolder mb-3 text-dark">
                                        <i class="fas fa-archive me-2"></i>
                                        Detail Arsip
                                    </h6>

                                    <div class="border-start border-dark border-3 ps-3 mb-4">
                                        <label class="text-xs text-muted mb-1 d-block">Nomor Arsip</label>
                                        <p class="font-weight-semibold mb-0 h6">
                                            @if($peminjaman->arsip && $peminjaman->arsip->kode)
                                                {{ $peminjaman->arsip->kode }}
                                            @else
                                                <span class="text-danger">Nomor arsip tidak tersedia (ID: {{ $peminjaman->arsip_id }})</span>
                                            @endif
                                        </p>
                                    </div>

                                    <div class="border-start border-dark border-3 ps-3 mb-4">
                                        <label class="text-xs text-muted mb-1 d-block">Nama Arsip</label>
                                        <p class="font-weight-semibold mb-0">
                                            @if($peminjaman->arsip && $peminjaman->arsip->nama_dokumen)
                                                {{ $peminjaman->arsip->nama_dokumen }}
                                            @else
                                                <span class="text-danger">Nama arsip tidak tersedia</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Petugas -->
            <div class="row">
                <div class="col-12">
                    <div class="card border shadow-xs mb-4">
                        <div class="card-header border-bottom pb-0">
                            <h6 class="font-weight-semibold text-lg mb-0">Informasi Petugas</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-12">
                                    <h6 class="font-weight-bolder mb-3 text-purple">
                                        <i class="fas fa-user-tie me-2"></i>
                                        Riwayat Petugas
                                    </h6>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="border-start border-purple border-3 ps-3 mb-3">
                                                <label class="text-xs text-muted mb-1">Petugas Peminjaman</label>
                                                <p class="font-weight-semibold mb-0">{{ $peminjaman->petugas_peminjaman ?: '-' }}</p>
                                            </div>
                                        </div>

                                        @if($peminjaman->approved_at)
                                        <div class="col-md-6">
                                            <div class="border-start border-purple border-3 ps-3 mb-3">
                                                <label class="text-xs text-muted mb-1">Tanggal Persetujuan</label>
                                                <p class="font-weight-semibold mb-0">{{ $peminjaman->approved_at->format('d/m/Y') }}</p>
                                                <small class="text-muted">{{ $peminjaman->approved_at->format('H:i') }}</small>
                                            </div>
                                        </div>
                                        @endif

                                        @if($peminjaman->petugas_pengembalian)
                                        <div class="col-md-6">
                                            <div class="border-start border-purple border-3 ps-3 mb-3">
                                                <label class="text-xs text-muted mb-1">Petugas Pengembalian</label>
                                                <p class="font-weight-semibold mb-0">{{ $peminjaman->petugas_pengembalian }}</p>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if($peminjaman->rejection_reason)
                            <hr class="my-4">
                            <div class="row">
                                <div class="col-12">
                                    <div class="alert alert-danger border-danger">
                                        <h6 class="alert-heading">
                                            <i class="fas fa-times-circle me-2"></i>
                                            Alasan Penolakan
                                        </h6>
                                        <p class="mb-0">{{ $peminjaman->rejection_reason }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>
