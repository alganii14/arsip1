<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-12">
                    <div class="card card-background card-background-after-none align-items-start mt-4 mb-5">
                        <div class="full-background" style="background-image: url('../assets/img/header-blue-purple.jpg')"></div>
                        <div class="card-body text-start p-4 w-100">
                            <h3 class="text-white mb-2">Pengembalian Arsip</h3>
                            <p class="mb-4 font-weight-semibold text-white">
                                Proses pengembalian arsip yang dipinjam
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
                                    <h6 class="font-weight-semibold text-lg mb-0">Form Pengembalian Arsip</h6>
                                    <p class="text-sm mb-sm-0">Proses pengembalian arsip: <span class="font-weight-bold">{{ $peminjaman->arsip->nama_dokumen }}</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="border rounded p-3">
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
                                        <div class="mb-0">
                                            <p class="text-xs text-secondary mb-1">Rak Penyimpanan</p>
                                            <p class="font-weight-semibold mb-0">{{ $peminjaman->arsip->rak ?: 'Tidak ada informasi rak' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="border rounded p-3">
                                        <h6 class="text-sm font-weight-semibold mb-3">Informasi Peminjaman</h6>
                                        <div class="mb-3">
                                            <p class="text-xs text-secondary mb-1">Peminjam</p>
                                            <p class="font-weight-semibold mb-0">{{ $peminjaman->peminjam }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <p class="text-xs text-secondary mb-1">Tanggal Pinjam</p>
                                            <p class="font-weight-semibold mb-0">{{ $peminjaman->tanggal_pinjam->format('d/m/Y') }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <p class="text-xs text-secondary mb-1">Batas Waktu</p>
                                            <p class="font-weight-semibold mb-0">{{ $peminjaman->batas_waktu->format('d/m/Y') }}</p>
                                        </div>
                                        <div class="mb-0">
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
                                                }
                                            @endphp
                                            <span class="badge badge-sm border border-{{ $statusClass }} text-{{ $statusClass }} bg-{{ $statusClass }}">
                                                {{ $statusText }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <form action="{{ route('peminjaman.process-return', $peminjaman->id) }}" method="POST">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label for="tanggal_kembali" class="form-control-label text-sm">Tanggal Pengembalian <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @error('tanggal_kembali') is-invalid @enderror" id="tanggal_kembali" name="tanggal_kembali" value="{{ old('tanggal_kembali') ?? date('Y-m-d') }}" required>
                                            @error('tanggal_kembali')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label for="durasi" class="form-control-label text-sm">Durasi Peminjaman</label>
                                            <input type="text" class="form-control" id="durasi" value="{{ $peminjaman->getDurasiPinjam() }} hari" disabled>
                                            <small class="form-text text-muted">
                                                Dihitung dari tanggal peminjaman hingga hari ini
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="catatan" class="form-control-label text-sm">Catatan Pengembalian</label>
                                    <textarea class="form-control @error('catatan') is-invalid @enderror" id="catatan" name="catatan" rows="3">{{ old('catatan') }}</textarea>
                                    @error('catatan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Tambahkan catatan tentang kondisi arsip saat dikembalikan atau informasi lainnya
                                    </small>
                                </div>

                                <div class="alert alert-info" role="alert">
                                    <span class="alert-icon"><i class="fas fa-info-circle"></i></span>
                                    <span class="alert-text">
                                        <strong>Informasi!</strong> Setelah dikembalikan, status peminjaman akan berubah menjadi "Dikembalikan" dan arsip akan tersedia untuk dipinjam kembali.
                                    </span>
                                </div>

                                <div class="d-flex justify-content-end mt-4">
                                    <a href="{{ route('peminjaman.index') }}" class="btn btn-light me-3">Batal</a>
                                    <button type="submit" class="btn btn-success">
                                        <span class="btn-inner--icon">
                                            <i class="fas fa-undo me-2"></i>
                                        </span>
                                        Proses Pengembalian
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <x-app.footer />
        </div>
    </main>
</x-app-layout>
