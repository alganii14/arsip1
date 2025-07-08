<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-12">
                    <div class="card card-background card-background-after-none align-items-start mt-4 mb-5">
                        <div class="full-background" style="background-image: url('../assets/img/header-blue-purple.jpg')"></div>
                        <div class="card-body text-start p-4 w-100">
                            <h3 class="text-white mb-2">Pemindahan Arsip</h3>
                            <p class="mb-4 font-weight-semibold text-white">
                                Kelola permintaan pemindahan arsip dengan tingkat perkembangan
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <span class="text-white">{{ session('success') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <span class="text-white">{{ session('error') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card border shadow-xs mb-4">
                        <div class="card-header border-bottom pb-0">
                            <div class="d-sm-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="font-weight-semibold text-lg mb-0">Daftar Pemindahan Arsip</h6>
                                    <p class="text-sm">Kelola semua permintaan pemindahan arsip</p>
                                </div>
                                <div class="ms-auto my-auto mt-sm-0 mt-3">
                                    <div class="ms-auto my-auto">
                                        <div class="d-flex flex-wrap align-items-center">
                                            <div class="btn-group me-2 mb-2 mb-sm-0">
                                                <a href="{{ route('pemindahan.export.excel') }}{{ request('search') ? '?search='.request('search') : '' }}" class="btn btn-sm btn-success">
                                                    <i class="fas fa-file-excel me-1"></i> Excel
                                                </a>
                                                <a href="{{ route('pemindahan.export.pdf') }}{{ request('search') ? '?search='.request('search') : '' }}" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-file-pdf me-1"></i> PDF
                                                </a>
                                            </div>
                                            <form action="{{ route('pemindahan.index') }}" method="GET" class="d-flex flex-wrap">
                                            <div class="d-flex me-2 mb-2 mb-sm-0">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                                    <input type="text" name="search" class="form-control" placeholder="Cari arsip..." value="{{ request('search') }}">
                                                </div>
                                            </div>
                                            <div class="d-flex">
                                                <button type="submit" class="btn btn-info btn-sm mb-0 me-2">Cari</button>
                                                @if(request('search'))
                                                    <a href="{{ route('pemindahan.index') }}" class="btn btn-outline-secondary btn-sm mb-0">Reset</a>
                                                @endif
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 py-0">
                            <!-- Count information -->
                            <div class="p-3">
                                <div class="row">
                                    <div class="col-12">
                                        @if(request('search'))
                                            <div class="alert alert-info alert-dismissible fade show mb-0 py-2" role="alert">
                                                <span>
                                                    <strong>Filter aktif:</strong>
                                                    Menampilkan {{ $pemindahans->total() }} data
                                                    {{ request('search') ? '(Pencarian: "'.request('search').'")' : '' }}
                                                </span>
                                            </div>
                                        @else
                                            <p class="text-sm text-muted mb-0">
                                                Total {{ $pemindahans->total() }} data pemindahan
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive p-0">
                                <table class="table table-hover align-items-center mb-0">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-3">No</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Kode Arsip</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Nama Arsip</th>
                                            <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">Tahun</th>
                                            <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">Jumlah</th>
                                            <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">Tingkat Perkembangan</th>
                                            <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">Keterangan</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($pemindahans as $index => $pemindahan)
                                        <tr>
                                            <td class="ps-3">
                                                <span class="text-secondary text-sm font-weight-normal">{{ $pemindahans->firstItem() + $index }}</span>
                                            </td>
                                            <td>
                                                <p class="text-sm text-dark font-weight-semibold mb-0">{{ $pemindahan->arsip->kode ?? $pemindahan->arsip->nomor_dokumen ?? 'N/A' }}</p>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-file-archive text-primary me-2"></i>
                                                    </div>
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <p class="text-sm mb-0">{{ Str::limit($pemindahan->arsip->nama_dokumen, 30) }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-sm font-weight-normal">{{ $pemindahan->arsip->tanggal_arsip ? \Carbon\Carbon::parse($pemindahan->arsip->tanggal_arsip)->format('Y') : 'N/A' }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="badge badge-sm bg-gray-200 text-dark">{{ $pemindahan->jumlah_folder }} folder</span>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <span class="badge badge-sm border border-success text-success bg-success">{{ $pemindahan->tingkat_perkembangan_text }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-sm font-weight-normal">{{ Str::limit($pemindahan->keterangan, 20) }}</span>
                                            </td>

                                        </tr>


                                        @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-5">
                                                <div class="d-flex flex-column align-items-center">
                                                    <div class="icon icon-shape icon-lg bg-gradient-warning shadow text-center border-radius-lg mb-3">
                                                        <i class="fas fa-truck-moving opacity-10"></i>
                                                    </div>
                                                    <h6 class="text-secondary font-weight-bold">Belum ada data pemindahan</h6>
                                                    <p class="text-sm text-secondary mb-4">Klik tombol "Tambah Pemindahan" untuk membuat permintaan baru</p>
                                                    <a href="{{ route('pemindahan.create') }}" class="btn btn-sm btn-warning">
                                                        <i class="fas fa-plus me-2"></i>Tambah Pemindahan
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if($pemindahans->hasPages())
                                <div class="border-top py-3 px-3 d-flex align-items-center">
                                    {{ $pemindahans->links() }}
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
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Make table rows clickable to view details
            document.querySelectorAll('tbody tr').forEach(function(row) {
                row.addEventListener('click', function(e) {
                    // Don't trigger when clicking on action buttons
                    if (!e.target.closest('button') && !e.target.closest('a') && !e.target.closest('form')) {
                        const detailLink = this.querySelector('a[title="Lihat Detail"]');
                        if (detailLink) {
                            window.location.href = detailLink.getAttribute('href');
                        }
                    }
                });

                // Add cursor pointer to rows
                row.style.cursor = 'pointer';
            });

            // Add stripe effect to table
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach((row, index) => {
                if (index % 2 === 1) {
                    row.classList.add('bg-gray-50');
                }
            });
        });
    </script>
</x-app-layout>
