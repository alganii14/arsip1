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
                                @if(Auth::user()->role !== 'admin')
                                <a href="{{ route('peminjaman.create') }}" class="btn btn-outline-white btn-blur btn-icon d-flex align-items-center mb-0 me-2">
                                    <span class="btn-inner--icon">
                                        <svg width="14" height="14" viewBox="0 0 14 14" xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="d-block me-2">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M7 0C3.13401 0 0 3.13401 0 7C0 10.866 3.13401 14 7 14C10.866 14 14 10.866 14 7C14 3.13401 10.866 0 7 0ZM7.5 3.5C7.5 3.22386 7.27614 3 7 3C6.72386 3 6.5 3.22386 6.5 3.5V6.5H3.5C3.22386 6.5 3 6.72386 3 7C3 7.27614 3.22386 7.5 3.5 7.5H6.5V10.5C6.5 10.7761 6.72386 11 7 11C7.27614 11 7.5 10.7761 7.5 10.5V7.5H10.5C10.7761 7.5 11 7.27614 11 7C11 6.72386 10.7761 6.5 10.5 6.5H7.5V3.5Z" />
                                        </svg>
                                    </span>
                                    <span class="btn-inner--text">Tambah Peminjaman</span>
                                </a>
                                @endif
                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('peminjaman.pending') }}" class="btn btn-outline-white btn-blur btn-icon d-flex align-items-center mb-0 me-2">
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
                                        @if(Auth::user()->role === 'admin')
                                            Semua Arsip Tersedia
                                        @else
                                            Arsip Tersedia untuk Dipinjam
                                        @endif
                                    </h6>
                                    <p class="text-sm">
                                        @if(Auth::user()->role === 'admin')
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
                                                <div class="d-flex">
                                                    <a href="{{ route('arsip.detail', $arsip->id) }}" class="btn btn-sm btn-info me-2">
                                                        <i class="fas fa-eye me-1"></i> Detail
                                                    </a>
                                                    @if($arsip->file_path && Auth::user()->role === 'admin')
                                                        <a href="{{ route('arsip.view', $arsip->id) }}" class="btn btn-sm btn-success me-2">
                                                            <i class="fas fa-file me-1"></i> Lihat Arsip
                                                        </a>
                                                    @endif
                                                    @if(Auth::user()->role !== 'admin')
                                                        <a href="{{ route('peminjaman.create') }}?arsip_id={{ $arsip->id }}" class="btn btn-sm btn-primary">
                                                            <i class="fas fa-download me-1"></i> Pinjam
                                                        </a>
                                                    @endif
                                                </div>
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
                                        @if(Auth::user()->isPeminjam())
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

                                    @if(!Auth::user()->isPeminjam())
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

                                                    @if(Auth::user()->role === 'peminjam')
                                                        {{-- Peminjam hanya bisa mengembalikan arsip miliknya sendiri yang sudah disetujui --}}
                                                        @if($peminjaman->peminjam_user_id == Auth::id() &&
                                                            $peminjaman->status !== 'dikembalikan' &&
                                                            $peminjaman->confirmation_status === 'approved')
                                                            <a href="{{ route('peminjaman.return-form', $peminjaman->id) }}" class="btn btn-sm btn-success me-2">
                                                                <i class="fas fa-undo me-1"></i> Kembalikan
                                                            </a>
                                                        @endif
                                                    @else
                                                        {{-- Admin tidak bisa mengembalikan arsip, hanya edit dan hapus --}}
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
                    const tglPinjam = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() || '';
                    const batasWaktu = row.querySelector('td:nth-child(4)')?.textContent.toLowerCase() || '';
                    const tglKembali = row.querySelector('td:nth-child(5)')?.textContent.toLowerCase() || '';
                    const status = row.querySelector('td:nth-child(6)')?.textContent.toLowerCase() || '';

                    if (arsip.includes(searchValue) ||
                        peminjam.includes(searchValue) ||
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
                    emptyRow.innerHTML = `
                        <td colspan="9" class="text-center p-4">
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
    </script>
</x-app-layout>
