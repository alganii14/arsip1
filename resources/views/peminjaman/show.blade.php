<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-12">
                    <div class="card card-background card-background-after-none align-items-start mt-4 mb-5">
                        <div class="full-background" style="background-image: url('../assets/img/header-blue-purple.jpg')"></div>
                        <div class="card-body text-start p-4 w-100">
                            <h3 class="text-white mb-2">Detail Peminjaman Arsip</h3>
                            <p class="mb-4 font-weight-semibold text-white">
                                Informasi lengkap peminjaman arsip
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
                                    <h6 class="font-weight-semibold text-lg mb-0">Informasi Peminjaman</h6>
                                    <p class="text-sm mb-sm-0">Detail peminjaman arsip</p>
                                </div>
                                <div class="ms-auto d-flex">
                                    @if($peminjaman->status !== 'dikembalikan')
                                        <a href="{{ route('peminjaman.return-form', $peminjaman->id) }}" class="btn btn-sm btn-success me-2">
                                            <i class="fas fa-undo me-1"></i> Kembalikan Arsip
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
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
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
                                        <div class="mb-0">
                                            <p class="text-xs text-secondary mb-1">Rak Penyimpanan</p>
                                            <p class="font-weight-semibold mb-0">{{ $peminjaman->arsip->rak ?: 'Tidak ada informasi rak' }}</p>
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
                                            <p class="text-xs text-secondary mb-1">Jabatan</p>
                                            <p class="font-weight-semibold mb-0">{{ $peminjaman->jabatan ?: 'Tidak ada informasi jabatan' }}</p>
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
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p class="text-xs text-secondary mb-1">Catatan</p>
                                                <p class="font-weight-semibold mb-0">{{ $peminjaman->catatan ?: 'Tidak ada catatan' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end mt-4">
                                <a href="{{ route('peminjaman.index') }}" class="btn btn-light me-3">Kembali ke Daftar</a>
                                @if($peminjaman->status !== 'dikembalikan')
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