<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-12">
                    <div class="card card-background card-background-after-none align-items-start mt-4 mb-5">
                        <div class="full-background" style="background-image: url('../assets/img/header-blue-purple.jpg')"></div>
                        <div class="card-body text-start p-4 w-100">
                            <h3 class="text-white mb-2">
                                <i class="fas fa-download me-2"></i>
                                Detail Peminjaman Digital
                            </h3>
                            <p class="mb-4 font-weight-semibold text-white">
                                Informasi lengkap peminjaman arsip digital - Anda dapat melihat dan mengunduh arsip
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

            <div class="row">
                <div class="col-12">
                    <div class="card border shadow-xs mb-4">
                        <div class="card-header border-bottom pb-0">
                            <div class="d-sm-flex align-items-center mb-3">
                                <div>
                                    <h6 class="font-weight-semibold text-lg mb-0">Informasi Peminjaman Digital</h6>
                                    <p class="text-sm mb-sm-0">Detail peminjaman arsip digital</p>
                                </div>
                                <div class="ms-auto d-flex align-items-center">
                                    <span class="badge bg-success me-2">
                                        <i class="fas fa-download me-1"></i>
                                        Peminjaman Digital
                                    </span>

                                    @if($peminjaman->confirmation_status === 'approved' && $peminjaman->arsip->file_path)
                                        <a href="{{ route('arsip.view', $peminjaman->arsip->id) }}" class="btn btn-sm btn-info me-2">
                                            <i class="fas fa-eye me-1"></i> Lihat Arsip
                                        </a>
                                        <a href="{{ route('arsip.download', $peminjaman->arsip->id) }}" class="btn btn-sm btn-success me-2">
                                            <i class="fas fa-download me-1"></i> Unduh Arsip
                                        </a>
                                    @endif

                                    {{-- Untuk peminjaman digital, peminjam dapat mengembalikan sendiri --}}
                                    @if($peminjaman->jenis_peminjaman === 'digital' &&
                                        Auth::user()->role === 'peminjam' &&
                                        $peminjaman->peminjam_user_id == Auth::id() &&
                                        $peminjaman->status !== 'dikembalikan' &&
                                        $peminjaman->confirmation_status === 'approved')
                                        <a href="{{ route('peminjaman.return-form', $peminjaman->id) }}" class="btn btn-sm btn-success me-2">
                                            <i class="fas fa-undo me-1"></i> Kembalikan Arsip
                                        </a>
                                    @endif

                                    {{-- Admin hanya bisa edit dan hapus --}}
                                    @if(Auth::user()->role !== 'peminjam')
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
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>
                                <strong>Peminjaman Digital Berhasil!</strong> Anda sekarang dapat melihat dan mengunduh arsip ini kapan saja selama masa peminjaman.
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="border rounded p-3 mb-4">
                                        <h6 class="text-sm font-weight-semibold mb-3">Informasi Arsip</h6>
                                        <div class="mb-3">
                                            <p class="text-xs text-secondary mb-1">Kode</p>
                                            <p class="font-weight-semibold mb-0">{{ $peminjaman->arsip->kode }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <p class="text-xs text-secondary mb-1">Nama Dokumen</p>
                                            <p class="font-weight-semibold mb-0">{{ $peminjaman->arsip->nama_dokumen }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <p class="text-xs text-secondary mb-1">Kategori</p>
                                            <p class="font-weight-semibold mb-0">{{ $peminjaman->arsip->kategori }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <p class="text-xs text-secondary mb-1">Tanggal Arsip</p>
                                            <p class="font-weight-semibold mb-0">{{ $peminjaman->arsip->tanggal_arsip->format('d/m/Y') }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <p class="text-xs text-secondary mb-1">Dibuat oleh</p>
                                            <p class="font-weight-semibold mb-0">{{ $peminjaman->arsip->creator->name ?? 'N/A' }}</p>
                                        </div>
                                        <div class="mb-0">
                                            <p class="text-xs text-secondary mb-1">File Arsip</p>
                                            @if($peminjaman->arsip->file_path)
                                                @if($peminjaman->confirmation_status === 'approved')
                                                    <a href="{{ route('arsip.view', $peminjaman->arsip->id) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye me-1"></i> Lihat Arsip Digital
                                                    </a>
                                                @elseif($peminjaman->confirmation_status === 'pending')
                                                    <p class="font-weight-semibold mb-0 text-warning">
                                                        <i class="fas fa-clock me-1"></i> Menunggu Persetujuan
                                                    </p>
                                                    <small class="text-muted">Arsip akan tersedia untuk dilihat setelah peminjaman disetujui</small>
                                                @elseif($peminjaman->confirmation_status === 'rejected')
                                                    <p class="font-weight-semibold mb-0 text-danger">
                                                        <i class="fas fa-times me-1"></i> Peminjaman Ditolak
                                                    </p>
                                                    <small class="text-muted">Arsip tidak dapat diakses</small>
                                                @else
                                                    <p class="font-weight-semibold mb-0">Arsip tersedia</p>
                                                @endif
                                            @else
                                                <p class="font-weight-semibold mb-0 text-muted">Tidak ada file digital</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="border rounded p-3 mb-4">
                                        <h6 class="text-sm font-weight-semibold mb-3">Informasi Peminjam</h6>
                                        <div class="mb-3">
                                            <p class="text-xs text-secondary mb-1">Nama Peminjam</p>
                                            <p class="font-weight-semibold mb-0">{{ $peminjaman->peminjam }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <p class="text-xs text-secondary mb-1">Departemen</p>
                                            <p class="font-weight-semibold mb-0">{{ $peminjaman->departemen ?: 'Tidak ada informasi departemen' }}</p>
                                        </div>
                                        <div class="mb-0">
                                            <p class="text-xs text-secondary mb-1">Kontak</p>
                                            <p class="font-weight-semibold mb-0">{{ $peminjaman->kontak }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="border rounded p-3 mb-4">
                                        <h6 class="text-sm font-weight-semibold mb-3">Detail Peminjaman</h6>
                                        <div class="row">
                                            <div class="col-md-3 mb-3">
                                                <p class="text-xs text-secondary mb-1">Tanggal Pinjam</p>
                                                <p class="font-weight-semibold mb-0">{{ $peminjaman->tanggal_pinjam->format('d/m/Y') }}</p>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <p class="text-xs text-secondary mb-1">Batas Waktu</p>
                                                <p class="font-weight-semibold mb-0">{{ $peminjaman->batas_waktu->format('d/m/Y') }}</p>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <p class="text-xs text-secondary mb-1">Tanggal Kembali</p>
                                                <p class="font-weight-semibold mb-0">{{ $peminjaman->tanggal_kembali ? $peminjaman->tanggal_kembali->format('d/m/Y') : 'Belum dikembalikan' }}</p>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <p class="text-xs text-secondary mb-1">Status</p>
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
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <p class="text-xs text-secondary mb-1">Petugas Peminjaman</p>
                                                <p class="font-weight-semibold mb-0">{{ $peminjaman->petugas_peminjaman }}</p>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <p class="text-xs text-secondary mb-1">Petugas Pengembalian</p>
                                                <p class="font-weight-semibold mb-0">{{ $peminjaman->petugas_pengembalian ?: 'Belum dikembalikan' }}</p>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <p class="text-xs text-secondary mb-1">Tujuan Peminjaman</p>
                                                <p class="font-weight-semibold mb-0">{{ $peminjaman->tujuan_peminjaman ?: 'Tidak ada informasi tujuan' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Persetujuan Section -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="border rounded p-3 mb-4">
                                        <h6 class="text-sm font-weight-semibold mb-3">Status Persetujuan Peminjaman</h6>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <p class="text-xs text-secondary mb-1">Status Konfirmasi</p>
                                                @php
                                                    $confirmationClass = 'secondary';
                                                    $confirmationText = 'Tidak Diketahui';
                                                    $confirmationIcon = 'question';

                                                    if($peminjaman->confirmation_status === 'pending') {
                                                        $confirmationClass = 'warning';
                                                        $confirmationText = 'Menunggu Persetujuan';
                                                        $confirmationIcon = 'clock';
                                                    } elseif($peminjaman->confirmation_status === 'approved') {
                                                        $confirmationClass = 'success';
                                                        $confirmationText = 'Disetujui';
                                                        $confirmationIcon = 'check';
                                                    } elseif($peminjaman->confirmation_status === 'rejected') {
                                                        $confirmationClass = 'danger';
                                                        $confirmationText = 'Ditolak';
                                                        $confirmationIcon = 'times';
                                                    }
                                                @endphp
                                                <span class="badge badge-sm border border-{{ $confirmationClass }} text-{{ $confirmationClass }} bg-{{ $confirmationClass }}">
                                                    <i class="fas fa-{{ $confirmationIcon }} me-1"></i> {{ $confirmationText }}
                                                </span>
                                            </div>

                                            @if($peminjaman->approved_by)
                                            <div class="col-md-6 mb-3">
                                                <p class="text-xs text-secondary mb-1">Disetujui/Ditolak Oleh</p>
                                                <p class="font-weight-semibold mb-0">{{ $peminjaman->adminApprover->name ?? 'Admin' }}</p>
                                                @if($peminjaman->approved_at)
                                                    <small class="text-muted">{{ $peminjaman->approved_at->format('d/m/Y H:i') }} WIB</small>
                                                @endif
                                            </div>
                                            @endif
                                        </div>

                                        @if($peminjaman->confirmation_status === 'rejected' && $peminjaman->rejection_reason)
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p class="text-xs text-secondary mb-1">Alasan Penolakan</p>
                                                <div class="alert alert-danger py-2 mb-0">
                                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                                    {{ $peminjaman->rejection_reason }}
                                                </div>
                                            </div>
                                        </div>
                                        @endif

                                        @if($peminjaman->confirmation_status === 'approved')
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="alert alert-success py-2 mb-0">
                                                    <i class="fas fa-check-circle me-2"></i>
                                                    Peminjaman telah disetujui. Anda dapat melihat arsip secara digital.
                                                </div>
                                            </div>
                                        </div>
                                        @elseif($peminjaman->confirmation_status === 'pending')
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="alert alert-warning py-2 mb-0">
                                                    <i class="fas fa-clock me-2"></i>
                                                    Peminjaman sedang menunggu persetujuan admin. Arsip akan tersedia untuk dilihat setelah disetujui.
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <a href="{{ route('peminjaman.index') }}" class="btn btn-light me-3">Kembali ke Daftar</a>

                                @if($peminjaman->confirmation_status === 'approved' && $peminjaman->arsip->file_path)
                                    <a href="{{ route('arsip.view', $peminjaman->arsip->id) }}" class="btn btn-info me-3">
                                        <i class="fas fa-eye me-2"></i> Lihat Arsip Digital
                                    </a>
                                @endif

                                {{-- Hanya peminjam yang bisa mengembalikan arsip miliknya sendiri yang sudah disetujui --}}
                                @if(Auth::user()->role === 'peminjam' &&
                                    $peminjaman->peminjam_user_id == Auth::id() &&
                                    $peminjaman->status !== 'dikembalikan' &&
                                    $peminjaman->confirmation_status === 'approved')
                                    <a href="{{ route('peminjaman.return-form', $peminjaman->id) }}" class="btn btn-success">
                                        <i class="fas fa-undo me-2"></i> Kembalikan Arsip
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <x-app.footer />
        </div>
    </main>
</x-app-layout>
