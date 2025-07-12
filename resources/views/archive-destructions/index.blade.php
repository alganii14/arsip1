<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-12">
                    <div class="card card-background card-background-after-none align-items-start mt-4 mb-5">
                        <div class="full-background" style="background-image: url('../assets/img/header-blue-purple.jpg')"></div>
                        <div class="card-body text-start p-4 w-100">
                            <h3 class="text-white mb-2">Riwayat Pemusnahan</h3>
                            <p class="mb-4 font-weight-semibold text-white">
                                Daftar arsip yang telah dimusnahkan secara permanen
                            </p>
                            <div class="d-flex">
                                <a href="{{ route('jre.index') }}" class="btn btn-outline-white btn-blur btn-icon d-flex align-items-center mb-0">
                                    <span class="btn-inner--icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block me-2">
                                            <path d="M19 12H5M12 19l-7-7 7-7"/>
                                        </svg>
                                    </span>
                                    <span class="btn-inner--text">Kembali ke JRE</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card border shadow-xs">
                        <div class="card-body text-start p-3">
                            <div class="d-flex">
                                <div class="icon icon-shape icon-sm bg-dark text-white text-center border-radius-sm d-flex align-items-center justify-content-center">
                                    <i class="fas fa-fire text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-uppercase font-weight-bold opacity-7">Total Dimusnahkan</p>
                                        <h5 class="font-weight-bolder">
                                            {{ $destructions->count() }}
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
                                <div class="icon icon-shape icon-sm bg-danger text-white text-center border-radius-sm d-flex align-items-center justify-content-center">
                                    <i class="fas fa-calendar text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-uppercase font-weight-bold opacity-7">Bulan Ini</p>
                                        <h5 class="font-weight-bolder">
                                            {{ $destructions->where('destroyed_at', '>=', now()->startOfMonth())->count() }}
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
                                    <i class="fas fa-chart-line text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-uppercase font-weight-bold opacity-7">Tahun Ini</p>
                                        <h5 class="font-weight-bolder">
                                            {{ $destructions->where('destroyed_at', '>=', now()->startOfYear())->count() }}
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
                                <div class="icon icon-shape icon-sm bg-success text-white text-center border-radius-sm d-flex align-items-center justify-content-center">
                                    <i class="fas fa-clock text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-uppercase font-weight-bold opacity-7">Terakhir</p>
                                        <h5 class="font-weight-bolder text-sm">
                                            @if($destructions->count() > 0)
                                                {{ $destructions->first()->destroyed_at->diffForHumans() }}
                                            @else
                                                -
                                            @endif
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
                                    <h6 class="font-weight-semibold text-lg mb-0">Daftar Arsip yang Telah Dimusnahkan</h6>
                                    <p class="text-sm">Arsip yang telah dimusnahkan secara permanen dan tidak dapat dipulihkan</p>
                                </div>
                                <div class="ms-auto d-flex">
                                    <!-- Export Buttons -->
                                    <div class="btn-group me-3">
                                        <button type="button" class="btn btn-outline-success btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-download me-1"></i> Export Laporan
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('archive-destructions.export.excel') }}">
                                                    <i class="fas fa-file-excel me-2 text-success"></i> Export Excel
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('archive-destructions.export.pdf') }}">
                                                    <i class="fas fa-file-pdf me-2 text-danger"></i> Export PDF
                                                </a>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="input-group input-group-sm input-group-dynamic w-auto">
                                        <span class="input-group-text text-body">
                                            <i class="fas fa-search"></i>
                                        </span>
                                        <input type="text" class="form-control form-control-sm ps-3" id="searchInput" placeholder="Cari arsip yang dimusnahkan...">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 py-0">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7">Kode Arsip</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Nama Dokumen</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Tanggal Pemusnahan</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Petugas</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($destructions as $destruction)
                                        <tr>
                                            <td class="ps-2">
                                                <p class="text-sm font-weight-semibold mb-0">{{ $destruction->arsip->kode }}</p>
                                            </td>
                                            <td>
                                                <p class="text-sm font-weight-semibold mb-0">{{ $destruction->arsip->nama_dokumen }}</p>
                                                <p class="text-xs text-secondary mb-0">{{ $destruction->arsip->kategori }}</p>
                                            </td>
                                            <td>
                                                <p class="text-sm font-weight-normal mb-0">{{ $destruction->destroyed_at->format('d/m/Y H:i') }} WIB</p>
                                            </td>
                                            <td>
                                                <p class="text-sm font-weight-normal mb-0">{{ $destruction->user->name }}</p>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <a href="{{ route('archive-destructions.show', $destruction->id) }}" class="btn btn-sm btn-info me-2">
                                                        <i class="fas fa-eye me-1"></i> Detail
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <div class="text-center">
                                                    <i class="fas fa-fire fa-3x text-muted mb-3"></i>
                                                    <p class="text-muted mb-0">Belum ada arsip yang dimusnahkan</p>
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

            <x-app.footer />
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Search functionality with enhanced features
            const searchInput = document.getElementById('searchInput');
            const tableBody = document.querySelector('table tbody');
            const tableRows = tableBody.querySelectorAll('tr');

            // Add clear button to search input
            const wrapper = document.createElement('div');
            wrapper.className = 'position-relative';
            searchInput.parentNode.insertBefore(wrapper, searchInput);
            wrapper.appendChild(searchInput);

            const clearButton = document.createElement('button');
            clearButton.className = 'btn btn-link position-absolute top-50 end-0 translate-middle-y text-muted p-1';
            clearButton.innerHTML = '<i class="fas fa-times"></i>';
            clearButton.style.display = 'none';
            wrapper.appendChild(clearButton);

            searchInput.addEventListener('input', function() {
                clearButton.style.display = this.value ? 'block' : 'none';
            });

            clearButton.addEventListener('click', function() {
                searchInput.value = '';
                searchInput.dispatchEvent(new Event('keyup'));
                this.style.display = 'none';
                searchInput.focus();
            });

            searchInput.addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();

                // More specific search - check individual columns
                tableRows.forEach(row => {
                    if (row.id === 'noResultsRow') return;

                    const kodeArsip = row.querySelector('td:nth-child(1)')?.textContent.toLowerCase() || '';
                    const namaDokumen = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase() || '';
                    const tanggalPemusnahan = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() || '';
                    const petugas = row.querySelector('td:nth-child(4)')?.textContent.toLowerCase() || '';

                    if (kodeArsip.includes(searchTerm) ||
                        namaDokumen.includes(searchTerm) ||
                        tanggalPemusnahan.includes(searchTerm) ||
                        petugas.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Show "no results" message if no rows are visible
                const visibleRows = Array.from(tableRows).filter(row => row.style.display !== 'none' && row.id !== 'noResultsRow');
                const noResultsRow = document.getElementById('noResultsRow');

                if (visibleRows.length === 0 && searchTerm !== '') {
                    if (!noResultsRow) {
                        const newRow = document.createElement('tr');
                        newRow.id = 'noResultsRow';
                        newRow.innerHTML = `
                            <td colspan="5" class="text-center py-4">
                                <div class="text-center">
                                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                    <h6 class="text-muted mb-2">Tidak ditemukan arsip yang cocok dengan pencarian "${searchTerm}"</h6>
                                    <p class="text-muted text-sm mb-0">Coba dengan kata kunci lain atau reset pencarian</p>
                                </div>
                            </td>
                        `;
                        tableBody.appendChild(newRow);
                    } else {
                        // Update the search term in the message
                        const messageElement = noResultsRow.querySelector('h6');
                        if (messageElement) {
                            messageElement.textContent = `Tidak ditemukan arsip yang cocok dengan pencarian "${searchTerm}"`;
                        }
                    }
                } else if (noResultsRow) {
                    noResultsRow.remove();
                }
            });

            // Export button loading state
            const exportButtons = document.querySelectorAll('.dropdown-item');
            exportButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Mengunduh...';

                    setTimeout(() => {
                        this.innerHTML = originalText;
                    }, 2000);
                });
            });

            // Add keyboard shortcut for search focus (Ctrl+K or Cmd+K)
            document.addEventListener('keydown', function(e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    searchInput.focus();
                }
            });
        });
    </script>
</x-app-layout>
