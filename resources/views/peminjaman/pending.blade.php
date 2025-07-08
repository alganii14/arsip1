<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-12">
                    <div class="card card-background card-background-after-none align-items-start mt-4 mb-5">
                        <div class="full-background" style="background-image: url('../assets/img/header-blue-purple.jpg')"></div>
                        <div class="card-body text-start p-4 w-100">
                            <h3 class="text-white mb-2">Persetujuan Peminjaman Arsip</h3>
                            <p class="mb-4 font-weight-semibold text-white">
                                Kelola permintaan peminjaman yang menunggu persetujuan admin
                            </p>
                            <div class="d-flex">
                                <a href="{{ route('peminjaman.index') }}" class="btn btn-outline-white btn-blur btn-icon d-flex align-items-center mb-0">
                                    <span class="btn-inner--icon">
                                        <i class="fas fa-arrow-left me-2"></i>
                                    </span>
                                    <span class="btn-inner--text">Kembali ke Peminjaman</span>
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
                        <span class="alert-icon"><i class="fas fa-times-circle"></i></span>
                        <span class="alert-text">{{ session('error') }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card border shadow-xs mb-4">
                        <div class="card-header border-bottom pb-0">
                            <div class="d-sm-flex align-items-center">
                                <div>
                                    <h6 class="font-weight-semibold text-lg mb-0">Permintaan Peminjaman</h6>
                                    <p class="text-sm">Daftar permintaan peminjaman yang menunggu persetujuan</p>
                                </div>
                                <div class="ms-auto d-flex">
                                    <div class="badge bg-gradient-success">{{ count($peminjamans) }} Permintaan</div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 py-0">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7">Peminjam</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Arsip</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Departemen</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Tanggal</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Tujuan</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Status</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($peminjamans as $peminjaman)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar avatar-sm me-3 bg-gradient-primary">
                                                            <span class="text-white font-weight-bold">{{ substr($peminjaman->peminjam, 0, 2) }}</span>
                                                        </div>
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm font-weight-semibold">{{ $peminjaman->peminjam }}</h6>
                                                            <p class="text-xs text-secondary mb-0">{{ $peminjaman->jabatan }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $peminjaman->arsip->nama_dokumen ?? 'N/A' }}</h6>
                                                    <p class="text-xs text-secondary mb-0">{{ $peminjaman->arsip->kode ?? 'N/A' }}</p>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-xs font-weight-semibold">{{ $peminjaman->departemen }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <span class="text-xs font-weight-semibold">{{ $peminjaman->tanggal_pinjam ? \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y') : 'N/A' }}</span>
                                                    <span class="text-xs text-secondary">s/d {{ $peminjaman->batas_waktu ? \Carbon\Carbon::parse($peminjaman->batas_waktu)->format('d M Y') : 'N/A' }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-xs">{{ Str::limit($peminjaman->tujuan_peminjaman, 30) }}</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-sm bg-gradient-warning">Pending</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <button type="button" class="btn btn-sm btn-success me-2" onclick="approveRequest({{ $peminjaman->id }})">
                                                        <i class="fas fa-check"></i> Setujui
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-danger" onclick="showRejectModal({{ $peminjaman->id }})">
                                                        <i class="fas fa-times"></i> Tolak
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <div class="d-flex flex-column align-items-center justify-content-center">
                                                    <i class="fas fa-inbox text-secondary mb-2" style="font-size: 3rem;"></i>
                                                    <h6 class="text-secondary mb-0">Tidak ada permintaan peminjaman yang menunggu persetujuan</h6>
                                                </div>
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
        </div>
    </main>

    <!-- Modal Tolak -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Permintaan Peminjaman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="rejectForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="rejection_reason" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="4" required placeholder="Masukkan alasan penolakan..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Tolak Permintaan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function approveRequest(id) {
            if (confirm('Apakah Anda yakin ingin menyetujui permintaan peminjaman ini?')) {
                // Create form and submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/peminjaman/${id}/approve`;

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                document.body.appendChild(form);
                form.submit();
            }
        }

        function showRejectModal(id) {
            const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
            document.getElementById('rejectForm').action = `/peminjaman/${id}/reject`;
            modal.show();
        }
    </script>
</x-app-layout>
