<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-12">
                    <div class="card card-background card-background-after-none align-items-start mt-4 mb-5">
                        <div class="full-background" style="background-image: url('../assets/img/header-blue-purple.jpg')"></div>
                        <div class="card-body text-start p-4 w-100">
                            <h3 class="text-white mb-2">Peminjaman Arsip</h3>
                            <p class="mb-4 font-weight-semibold text-white">
                                @if(Auth::user()->isPeminjam())
                                    Kelola peminjaman arsip Anda dari departemen {{ Auth::user()->department }}
                                @else
                                    Kelola peminjaman arsip dokumen
                                @endif
                            </p>
                            <div class="d-flex">
                                <a href="{{ route('peminjaman.create') }}" class="btn btn-outline-white btn-blur btn-icon d-flex align-items-center mb-0 me-2">
                                    <span class="btn-inner--icon">
                                        <svg width="14" height="14" viewBox="0 0 14 14" xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="d-block me-2">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M7 0C3.13401 0 0 3.13401 0 7C0 10.866 3.13401 14 7 14C10.866 14 14 10.866 14 7C14 3.13401 10.866 0 7 0ZM7.5 3.5C7.5 3.22386 7.27614 3 7 3C6.72386 3 6.5 3.22386 6.5 3.5V6.5H3.5C3.22386 6.5 3 6.72386 3 7C3 7.27614 3.22386 7.5 3.5 7.5H6.5V10.5C6.5 10.7761 6.72386 11 7 11C7.27614 11 7.5 10.7761 7.5 10.5V7.5H10.5C10.7761 7.5 11 7.27614 11 7C11 6.72386 10.7761 6.5 10.5 6.5H7.5V3.5Z" />
                                        </svg>
                                    </span>
                                    <span class="btn-inner--text">Tambah Peminjaman</span>
                                </a>
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
            
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border shadow-xs">
                        <div class="card-header border-bottom pb-0">
                            <div class="d-sm-flex align-items-center">
                                <div>
                                    <h6 class="font-weight-semibold text-lg mb-0">Daftar Peminjaman Arsip</h6>
                                    <p class="text-sm">
                                        @if(Auth::user()->isPeminjam())
                                            Informasi tentang peminjaman arsip Anda
                                        @else
                                            Informasi tentang semua peminjaman arsip
                                        @endif
                                    </p>
                                </div>
                                <div class="ms-auto d-flex">
                                    @if(!Auth::user()->isPeminjam())
                                    <form action="{{ route('peminjaman.check-overdue') }}" method="POST" class="me-3">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-dark">
                                            <i class="fas fa-clock me-1"></i> Cek Keterlambatan
                                        </button>
                                    </form>
                                    @endif
                                    <div class="input-group w-sm-25 ms-auto">
                                        <span class="input-group-text text-body">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16px" height="16px" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"></path>
                                            </svg>
                                        </span>
                                        <input type="text" class="form-control" placeholder="Cari peminjaman...">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 py-0">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7">Arsip</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Peminjam</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Tanggal Pinjam</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Batas Waktu</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Tanggal Kembali</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Status</th>
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
                                                    }
                                                @endphp
                                                <span class="badge badge-sm border border-{{ $statusClass }} text-{{ $statusClass }} bg-{{ $statusClass }}">
                                                    {{ $statusText }}
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
                                                    
                                                    @if(!Auth::user()->isPeminjam())
                                                        @if($peminjaman->status !== 'dikembalikan')
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
    </main>
</x-app-layout>