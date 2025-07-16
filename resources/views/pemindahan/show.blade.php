<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-12">
                    <div class="card card-background card-background-after-none align-items-start mt-4 mb-5">
                        <div class="full-background" style="background-image: url('../assets/img/header-blue-purple.jpg')"></div>
                        <div class="card-body text-start p-4 w-100">
                            <h3 class="text-white mb-2">Detail Pemindahan Arsip</h3>
                            <p class="mb-4 font-weight-semibold text-white">
                                Lihat detail permintaan pemindahan arsip
                            </p>
                            <a href="{{ route('pemindahan.index') }}" class="btn btn-outline-white btn-blur btn-icon d-flex align-items-center mb-0">
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
                <div class="col-md-8">
                    <!-- Data Arsip -->
                    <div class="card border shadow-xs mb-4">
                        <div class="card-header border-bottom pb-0">
                            <h6 class="font-weight-semibold text-lg mb-0">Data Arsip</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-control-label text-sm font-weight-semibold">Kode Arsip</label>
                                    <p class="text-sm mb-0">{{ $pemindahan->arsip->nomor_dokumen }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-control-label text-sm font-weight-semibold">Nama Dokumen</label>
                                    <p class="text-sm mb-0">{{ $pemindahan->arsip->nama_dokumen }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-control-label text-sm font-weight-semibold">Kategori</label>
                                    <p class="text-sm mb-0">{{ $pemindahan->arsip->kategori }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-control-label text-sm font-weight-semibold">Tanggal Arsip</label>
                                    <p class="text-sm mb-0">{{ \Carbon\Carbon::parse($pemindahan->arsip->tanggal_dokumen)->format('d M Y') }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-control-label text-sm font-weight-semibold">Dibuat oleh</label>
                                    <p class="text-sm mb-0">{{ $pemindahan->arsip->user->name ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-control-label text-sm font-weight-semibold">Status Arsip</label>
                                    <span class="badge badge-sm bg-success">{{ ucfirst($pemindahan->arsip->status ?? 'aktif') }}</span>
                                </div>
                            </div>
                            @if($pemindahan->arsip->keterangan)
                            <div class="row">
                                <div class="col-12">
                                    <label class="form-control-label text-sm font-weight-semibold">Keterangan Arsip</label>
                                    <p class="text-sm mb-0">{{ $pemindahan->arsip->keterangan }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Data Pemindahan -->
                    <div class="card border shadow-xs mb-4">
                        <div class="card-header border-bottom pb-0">
                            <h6 class="font-weight-semibold text-lg mb-0">Data Pemindahan</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-control-label text-sm font-weight-semibold">Tingkat Perkembangan</label>
                                    <p class="text-sm mb-0">
                                        <span class="badge badge-sm bg-success">{{ $pemindahan->tingkat_perkembangan_text }}</span>
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-control-label text-sm font-weight-semibold">Jumlah Folder</label>
                                    <p class="text-sm mb-0">{{ $pemindahan->jumlah_folder }} folder</p>
                                </div>
                            </div>
                            @if($pemindahan->keterangan)
                            <div class="row">
                                <div class="col-12">
                                    <label class="form-control-label text-sm font-weight-semibold">Keterangan Pemindahan</label>
                                    <p class="text-sm mb-0">{{ $pemindahan->keterangan }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Timeline/Riwayat -->
                    <div class="card border shadow-xs mb-4">
                        <div class="card-header border-bottom pb-0">
                            <h6 class="font-weight-semibold text-lg mb-0">Riwayat Status</h6>
                        </div>
                        <div class="card-body">
                            <div class="timeline timeline-one-side">
                                <!-- Pengajuan -->
                                <div class="timeline-block mb-3">
                                    <span class="timeline-step">
                                        <i class="fas fa-plus text-success"></i>
                                    </span>
                                    <div class="timeline-content">
                                        <h6 class="text-dark text-sm font-weight-bold mb-0">Permintaan Dibuat</h6>
                                        <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">{{ $pemindahan->created_at->format('d M Y H:i') }}</p>
                                        <p class="text-sm mt-3 mb-2">Permintaan pemindahan dibuat oleh {{ $pemindahan->user->name }}</p>
                                    </div>
                                </div>

                                @if($pemindahan->approved_at)
                                <!-- Persetujuan/Penolakan -->
                                <div class="timeline-block mb-3">
                                    <span class="timeline-step">
                                        <i class="fas {{ $pemindahan->status === 'approved' ? 'fa-check text-success' : 'fa-times text-danger' }}"></i>
                                    </span>
                                    <div class="timeline-content">
                                        <h6 class="text-dark text-sm font-weight-bold mb-0">
                                            {{ $pemindahan->status === 'approved' ? 'Disetujui' : 'Ditolak' }}
                                        </h6>
                                        <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">{{ $pemindahan->approved_at->format('d M Y H:i') }}</p>
                                        <p class="text-sm mt-3 mb-2">
                                            {{ $pemindahan->status === 'approved' ? 'Disetujui' : 'Ditolak' }} oleh {{ $pemindahan->approver->name ?? 'N/A' }}
                                        </p>
                                        @if($pemindahan->catatan_admin)
                                        <div class="alert alert-{{ $pemindahan->status === 'approved' ? 'success' : 'danger' }} text-sm">
                                            {{ $pemindahan->catatan_admin }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endif

                                @if($pemindahan->completed_at)
                                <!-- Penyelesaian -->
                                <div class="timeline-block mb-3">
                                    <span class="timeline-step">
                                        <i class="fas fa-flag text-info"></i>
                                    </span>
                                    <div class="timeline-content">
                                        <h6 class="text-dark text-sm font-weight-bold mb-0">Pemindahan Selesai</h6>
                                        <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">{{ $pemindahan->completed_at->format('d M Y H:i') }}</p>
                                        <p class="text-sm mt-3 mb-2">Pemindahan diselesaikan oleh {{ $pemindahan->completer->name ?? 'N/A' }}</p>
                                        @if($pemindahan->catatan_penyelesaian)
                                        <div class="alert alert-info text-sm">
                                            {{ $pemindahan->catatan_penyelesaian }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-md-4">
                    <!-- Status -->
                    <div class="card border shadow-xs mb-4">
                        <div class="card-header border-bottom pb-0">
                            <h6 class="font-weight-semibold text-lg mb-0">Status Pemindahan</h6>
                        </div>
                        <div class="card-body">
                            <div class="text-center">
                                <span class="badge badge-lg {{ $pemindahan->status_badge }} text-white">{{ $pemindahan->status_text }}</span>
                                <p class="text-sm text-secondary mt-2">Permintaan dibuat {{ $pemindahan->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    @if($pemindahan->status === 'pending')
                    <div class="card border shadow-xs mb-4">
                        <div class="card-header border-bottom pb-0">
                            <h6 class="font-weight-semibold text-lg mb-0">Aksi</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal">
                                    <i class="fas fa-check me-2"></i>Setujui
                                </button>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                    <i class="fas fa-times me-2"></i>Tolak
                                </button>
                                <a href="{{ route('pemindahan.edit', $pemindahan) }}" class="btn btn-warning">
                                    <i class="fas fa-edit me-2"></i>Edit
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($pemindahan->status === 'approved')
                    <div class="card border shadow-xs mb-4">
                        <div class="card-header border-bottom pb-0">
                            <h6 class="font-weight-semibold text-lg mb-0">Aksi</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#completeModal">
                                    <i class="fas fa-flag me-2"></i>Selesaikan
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($pemindahan->status === 'completed')
                    <div class="card border shadow-xs mb-4">
                        <div class="card-header border-bottom pb-0">
                            <h6 class="font-weight-semibold text-lg mb-0">Download Surat</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('pemindahan.download-surat', $pemindahan) }}" method="POST">
                                @csrf
                                
                                <h6 class="font-weight-semibold mb-3">Pihak Pertama</h6>
                                <div class="row mb-3">
                                    <div class="col-12 mb-2">
                                        <label class="form-control-label text-sm">Nama</label>
                                        <input type="text" class="form-control form-control-sm" name="nama_pihak_pertama" placeholder="Nama Pihak Pertama" required>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <label class="form-control-label text-sm">NIP</label>
                                        <input type="text" class="form-control form-control-sm" name="nip_pihak_pertama" placeholder="NIP" required>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <label class="form-control-label text-sm">Jabatan</label>
                                        <input type="text" class="form-control form-control-sm" name="jabatan_pihak_pertama" placeholder="Jabatan" required>
                                    </div>
                                </div>

                                <h6 class="font-weight-semibold mb-3">Pihak Kedua</h6>
                                <div class="row mb-3">
                                    <div class="col-12 mb-2">
                                        <label class="form-control-label text-sm">Nama</label>
                                        <input type="text" class="form-control form-control-sm" name="nama_pihak_kedua" placeholder="Nama Pihak Kedua" required>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <label class="form-control-label text-sm">NIP</label>
                                        <input type="text" class="form-control form-control-sm" name="nip_pihak_kedua" placeholder="NIP" required>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <label class="form-control-label text-sm">Jabatan</label>
                                        <input type="text" class="form-control form-control-sm" name="jabatan_pihak_kedua" placeholder="Jabatan" required>
                                    </div>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-download me-2"></i>Download Surat Pemindahan
                                    </button>
                                </div>
                            </form>
                            
                            <div class="alert alert-info alert-dismissible fade show mt-3" role="alert">
                                <i class="fas fa-info-circle me-2"></i>
                                <span class="text-sm">Isi form di atas sebelum mengunduh surat. Data yang diisi akan otomatis muncul di surat.</span>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Info Pemohon -->
                    <div class="card border shadow-xs mb-4">
                        <div class="card-header border-bottom pb-0">
                            <h6 class="font-weight-semibold text-lg mb-0">Info Pemohon</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-md bg-gradient-primary rounded-circle">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-0 text-sm font-weight-semibold">{{ $pemindahan->user->name }}</h6>
                                    <p class="text-xs text-secondary mb-0">{{ $pemindahan->user->email }}</p>
                                    <p class="text-xs text-secondary mb-0">{{ ucfirst($pemindahan->user->role ?? 'user') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modals -->
            @if($pemindahan->status === 'pending')
            <!-- Approve Modal -->
            <div class="modal fade" id="approveModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('pemindahan.approve', $pemindahan) }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Setujui Pemindahan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>Yakin ingin menyetujui permintaan pemindahan arsip <strong>{{ $pemindahan->arsip->nama_dokumen }}</strong>?</p>
                                <div class="form-group">
                                    <label class="form-control-label text-sm">Catatan (Opsional)</label>
                                    <textarea class="form-control" name="catatan_admin" rows="3" placeholder="Tambahkan catatan..."></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-success">Setujui</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Reject Modal -->
            <div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('pemindahan.reject', $pemindahan) }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Tolak Pemindahan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>Yakin ingin menolak permintaan pemindahan arsip <strong>{{ $pemindahan->arsip->nama_dokumen }}</strong>?</p>
                                <div class="form-group">
                                    <label class="form-control-label text-sm">Alasan Penolakan <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="catatan_admin" rows="3" required placeholder="Masukkan alasan penolakan..."></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger">Tolak</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif

            @if($pemindahan->status === 'approved')
            <!-- Complete Modal -->
            <div class="modal fade" id="completeModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('pemindahan.complete', $pemindahan) }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Selesaikan Pemindahan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>Yakin ingin menyelesaikan pemindahan arsip <strong>{{ $pemindahan->arsip->nama_dokumen }}</strong>?</p>
                                <div class="form-group">
                                    <label class="form-control-label text-sm">Catatan Penyelesaian (Opsional)</label>
                                    <textarea class="form-control" name="catatan_penyelesaian" rows="3" placeholder="Tambahkan catatan penyelesaian..."></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-info">Selesaikan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif

            <x-app.footer />
        </div>
    </main>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle form download surat
        const downloadForm = document.querySelector('form[action*="download-surat"]');
        if (downloadForm) {
            downloadForm.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                
                // Show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Membuat Surat...';
                
                // Re-enable button after 3 seconds (assuming download will complete)
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }, 3000);
            });
        }
    });
    </script>
</x-app-layout>
