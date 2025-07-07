<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-12">
                    <div class="card card-background card-background-after-none align-items-start mt-4 mb-5">
                        <div class="full-background" style="background-image: url('../assets/img/header-blue-purple.jpg')"></div>
                        <div class="card-body text-start p-4 w-100">
                            <h3 class="text-white mb-2">Daftar Arsip</h3>
                            <p class="mb-4 font-weight-semibold text-white">
                                @if(Auth::user()->isPeminjam())
                                    Lihat dan pinjam arsip dokumen yang tersedia
                                @else
                                    Kelola semua arsip dokumen Anda di satu tempat
                                @endif
                            </p>
                            <div class="d-flex">
                                @if(!Auth::user()->isPeminjam())
                                <a href="{{ route('arsip.create') }}" class="btn btn-outline-white btn-blur btn-icon d-flex align-items-center mb-0 me-2">
                                    <span class="btn-inner--icon">
                                        <svg width="14" height="14" viewBox="0 0 14 14" xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="d-block me-2">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M7 0C3.13401 0 0 3.13401 0 7C0 10.866 3.13401 14 7 14C10.866 14 14 10.866 14 7C14 3.13401 10.866 0 7 0ZM7.5 3.5C7.5 3.22386 7.27614 3 7 3C6.72386 3 6.5 3.22386 6.5 3.5V6.5H3.5C3.22386 6.5 3 6.72386 3 7C3 7.27614 3.22386 7.5 3.5 7.5H6.5V10.5C6.5 10.7761 6.72386 11 7 11C7.27614 11 7.5 10.7761 7.5 10.5V7.5H10.5C10.7761 7.5 11 7.27614 11 7C11 6.72386 10.7761 6.5 10.5 6.5H7.5V3.5Z" />
                                        </svg>
                                    </span>
                                    <span class="btn-inner--text">Tambah Arsip</span>
                                </a>
                                <a href="{{ route('jre.index') }}" class="btn btn-outline-white btn-blur btn-icon d-flex align-items-center mb-0 me-2">
                                    <span class="btn-inner--icon">
                                        <i class="fas fa-archive me-2"></i>
                                    </span>
                                    <span class="btn-inner--text">Kelola JRE</span>
                                </a>
                                @endif
                                <a href="{{ route('peminjaman.index') }}" class="btn btn-outline-white btn-blur btn-icon d-flex align-items-center mb-0">
                                    <span class="btn-inner--icon">
                                        <i class="fas fa-exchange-alt me-2"></i>
                                    </span>
                                    <span class="btn-inner--text">Peminjaman</span>
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

            <div class="row">
                <div class="col-12">
                    <div class="card border shadow-xs mb-4">
                        <div class="card-header border-bottom pb-0">
                            <div class="d-sm-flex align-items-center">
                                <div>
                                    <h6 class="font-weight-semibold text-lg mb-0">Daftar Arsip</h6>
                                    @if(request('search'))
                                        <small class="text-muted d-block">
                                            <i class="fas fa-search me-1"></i>
                                            Hasil pencarian untuk "<strong>{{ request('search') }}</strong>" - {{ count($arsips) }} arsip ditemukan
                                        </small>
                                    @else
                                        <small class="text-muted d-block">
                                            <i class="fas fa-archive me-1"></i>
                                            Informasi tentang semua arsip dokumen - Total: {{ count($arsips) }} arsip
                                        </small>
                                    @endif
                                </div>
                                <div class="ms-auto d-flex align-items-center">
                                    <form method="GET" action="{{ route('arsip.index') }}" class="d-flex align-items-center">
                                        <div class="input-group" style="width: 350px;">
                                            <span class="input-group-text bg-white border-end-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16px" height="16px" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="text-muted">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"></path>
                                                </svg>
                                            </span>
                                            <input type="text" name="search" class="form-control border-start-0 border-end-0" placeholder="Cari berdasarkan kode, nama dokumen, kategori, atau rak..." value="{{ request('search') }}" style="box-shadow: none;">
                                            @if(request('search'))
                                                <a href="{{ route('arsip.index') }}" class="input-group-text bg-white border-start-0 text-decoration-none" title="Hapus pencarian">
                                                    <i class="fas fa-times text-danger"></i>
                                                </a>
                                            @else
                                                <button type="submit" class="input-group-text bg-primary border-start-0 text-white" title="Cari">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 py-0">
                            @if(request('search'))
                                <div class="alert alert-info mx-3 mt-3 mb-0">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-filter me-2"></i>
                                        <span>Menampilkan hasil pencarian untuk: <strong>"{{ request('search') }}"</strong></span>
                                        <a href="{{ route('arsip.index') }}" class="btn btn-sm btn-outline-info ms-auto">
                                            <i class="fas fa-times me-1"></i> Hapus Filter
                                        </a>
                                    </div>
                                </div>
                            @endif
                            <div class="table-responsive p-0 {{ request('search') ? 'search-active' : '' }}">
                                <table class="table align-items-center mb-0">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7">Kode</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Nama Dokumen</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Kategori</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Tanggal Arsip</th>
                                            @if(!Auth::user()->isPeminjam())
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Tanggal Retensi</th>
                                            @endif

                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Status</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($arsips as $arsip)
                                        <tr class="{{ $arsip->has_retention_notification ? 'bg-warning-subtle' : '' }}">
                                            <td class="ps-2">
                                                <p class="text-sm font-weight-semibold mb-0">{{ $arsip->kode }}</p>
                                            </td>
                                            <td>
                                                <p class="text-sm font-weight-semibold mb-0">
                                                    {{ $arsip->nama_dokumen }}
                                                    @if($arsip->has_retention_notification && !Auth::user()->isPeminjam())
                                                        <span class="badge bg-warning text-dark ms-1">
                                                            <i class="fas fa-exclamation-triangle me-1"></i> Masa retensi telah berakhir
                                                        </span>
                                                    @endif
                                                </p>
                                            </td>
                                            <td>
                                                @php
                                                    $kategori = $arsip->kategori;
                                                    $badgeClass = \App\Helpers\ClassificationFormatter::getBadgeClass($kategori);
                                                    $description = \App\Helpers\ClassificationFormatter::getDescription($kategori);
                                                @endphp

                                                <span class="badge badge-sm border {{ $badgeClass }}">
                                                    {{ $arsip->kategori }}
                                                </span>

                                                @if($description)
                                                    <small class="d-block text-muted mt-1">
                                                    {{ explode(' - ', $description)[0] }} <!-- Only show the main description -->
                                                    </small>
                                                @endif
                                            </td>
                                            <td>
                                                <p class="text-sm font-weight-normal mb-0">{{ $arsip->tanggal_arsip->format('d/m/Y') }}</p>
                                            </td>
                                            @if(!Auth::user()->isPeminjam())
                                            <td>
                                                <p class="text-sm font-weight-normal mb-0">
                                                    {{ $arsip->retention_date ? $arsip->retention_date->format('d/m/Y') : '-' }}
                                                </p>
                                                @if($arsip->retention_years && $arsip->retention_years != 5)
                                                    <small class="d-block text-muted">
                                                        ({{ $arsip->retention_years }} tahun)
                                                    </small>
                                                @endif
                                            </td>
                                            @endif

                                            <td>
                                                @if($arsip->is_archived_to_jre)
                                                    <span class="badge badge-sm border border-info text-info bg-info">
                                                        Dipindahkan ke JRE
                                                    </span>
                                                @elseif($arsip->isCurrentlyBorrowed())
                                                    <span class="badge badge-sm border border-warning text-warning bg-warning">
                                                        Sedang Dipinjam
                                                    </span>
                                                @else
                                                    <span class="badge badge-sm border border-success text-success bg-success">
                                                        Aktif
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    @if(Auth::user()->isPeminjam())
                                                        <!-- Tombol untuk Peminjam -->
                                                        @if(!$arsip->is_archived_to_jre && !$arsip->isCurrentlyBorrowed())
                                                            <a href="{{ route('arsip.detail', $arsip->id) }}" class="btn btn-sm btn-info me-2">
                                                                <i class="fas fa-eye me-1"></i> Detail
                                                            </a>
                                                            <a href="{{ route('peminjaman.create') }}?arsip_id={{ $arsip->id }}" class="btn btn-sm btn-primary">
                                                                <i class="fas fa-download me-1"></i> Pinjam
                                                            </a>
                                                        @elseif($arsip->isCurrentlyBorrowed())
                                                            <a href="{{ route('arsip.detail', $arsip->id) }}" class="btn btn-sm btn-info me-2">
                                                                <i class="fas fa-eye me-1"></i> Detail
                                                            </a>
                                                            <button class="btn btn-sm btn-secondary" disabled>
                                                                <i class="fas fa-ban me-1"></i> Sedang Dipinjam
                                                            </button>
                                                        @else
                                                            <button class="btn btn-sm btn-secondary" disabled>
                                                                <i class="fas fa-archive me-1"></i> Di Arsip JRE
                                                            </button>
                                                        @endif
                                                    @else
                                                        <!-- Tombol untuk Admin/Petugas -->
                                                        @if(!$arsip->is_archived_to_jre)
                                                            <a href="{{ route('arsip.edit', $arsip->id) }}" class="btn btn-sm btn-warning me-2">
                                                                <svg width="14" height="14" viewBox="0 0 15 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M11.2201 2.02495C10.8292 1.63482 10.196 1.63545 9.80585 2.02636C9.41572 2.41727 9.41635 3.05044 9.80726 3.44057L11.2201 2.02495ZM12.5572 6.18502C12.9481 6.57516 13.5813 6.57453 13.9714 6.18362C14.3615 5.79271 14.3609 5.15954 13.97 4.7694L12.5572 6.18502ZM11.6803 1.56839L12.3867 2.2762L12.3867 2.27619L11.6803 1.56839ZM14.4302 4.31284L15.1367 5.02065L15.1367 5.02064L14.4302 4.31284ZM3.72198 15V16C3.98686 16 4.24091 15.8949 4.42839 15.7078L3.72198 15ZM0.999756 15H-0.000244141C-0.000244141 15.5523 0.447471 16 0.999756 16L0.999756 15ZM0.999756 12.2279L0.293346 11.5201C0.105383 11.7077 -0.000244141 11.9624 -0.000244141 12.2279H0.999756ZM9.80726 3.44057L12.5572 6.18502L13.97 4.7694L11.2201 2.02495L9.80726 3.44057ZM12.3867 2.27619C12.7557 1.90794 13.3549 1.90794 13.7238 2.27619L15.1367 0.860593C13.9869 -0.286864 12.1236 -0.286864 10.9739 0.860593L12.3867 2.27619ZM13.7238 2.27619C14.0917 2.64337 14.0917 3.23787 13.7238 3.60504L15.1367 5.02064C16.2875 3.8721 16.2875 2.00913 15.1367 0.860593L13.7238 2.27619ZM13.7238 3.60504L3.01557 14.2922L4.42839 15.7078L15.1367 5.02065L13.7238 3.60504ZM3.72198 14H0.999756V16H3.72198V14ZM1.99976 15V12.2279H-0.000244141V15H1.99976ZM1.70617 12.9357L12.3867 2.2762L10.9739 0.86059L0.293346 11.5201L1.70617 12.9357Z" fill="currentColor" />
                                                                </svg>
                                                                Edit
                                                            </a>

                                                            @if(!$arsip->isCurrentlyBorrowed())
                                                                <a href="{{ route('peminjaman.create') }}?arsip_id={{ $arsip->id }}" class="btn btn-sm btn-primary me-2">
                                                                    <i class="fas fa-exchange-alt me-1"></i> Pinjam
                                                                </a>
                                                            @else
                                                                <a href="{{ route('peminjaman.show', $arsip->getCurrentBorrower()->id) }}" class="btn btn-sm btn-info me-2">
                                                                    <i class="fas fa-info-circle me-1"></i> Info Peminjam
                                                                </a>
                                                            @endif

                                                            <form action="{{ route('arsip.destroy', $arsip->id) }}" method="POST" style="display:inline;">
                                                                @csrf @method('DELETE')
                                                                <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                                    </svg>
                                                                    Hapus
                                                                </button>
                                                            </form>
                                                        @else
                                                            <a href="{{ route('jre.show', $arsip->jre->id) }}" class="btn btn-sm btn-info">
                                                                <i class="fas fa-eye me-1"></i> Lihat di JRE
                                                            </a>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if(count($arsips) == 0)
                                <div class="text-center p-5">
                                    @if(request('search'))
                                        <div class="mb-3">
                                            <i class="fas fa-search-minus text-muted" style="font-size: 3rem;"></i>
                                        </div>
                                        <h6 class="text-muted mb-3">Tidak ada hasil untuk pencarian "{{ request('search') }}"</h6>
                                        <p class="text-muted mb-3">Coba gunakan kata kunci yang berbeda atau pastikan ejaan sudah benar</p>
                                        <a href="{{ route('arsip.index') }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-arrow-left me-1"></i> Kembali ke semua arsip
                                        </a>
                                    @else
                                        <div class="mb-3">
                                            <i class="fas fa-inbox text-muted" style="font-size: 3rem;"></i>
                                        </div>
                                        <h6 class="text-muted mb-3">Belum ada arsip yang tersedia</h6>
                                        <p class="text-muted mb-3">Mulai dengan menambahkan arsip dokumen pertama Anda</p>
                                        @if(!Auth::user()->isPeminjam())
                                            <a href="{{ route('arsip.create') }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-plus me-1"></i> Tambah Arsip
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            @else
                                <div class="border-top py-3 px-3 d-flex align-items-center">
                                    <p class="font-weight-semibold mb-0 text-dark text-sm">
                                        @if(request('search'))
                                            Menampilkan {{ count($arsips) }} dari hasil pencarian
                                        @else
                                            Halaman 1 dari 1 - Total {{ count($arsips) }} arsip
                                        @endif
                                    </p>
                                    <div class="ms-auto">
                                        <button class="btn btn-sm btn-white mb-0" disabled>Sebelumnya</button>
                                        <button class="btn btn-sm btn-white mb-0" disabled>Selanjutnya</button>
                                    </div>
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
        // Enhanced search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('input[name="search"]');
            const searchForm = searchInput.closest('form');
            let searchTimeout;

            // Auto-submit on typing
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    if (this.value.length >= 2 || this.value.length === 0) {
                        searchForm.submit();
                    }
                }, 800);
            });

            // Allow search on Enter key
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    clearTimeout(searchTimeout);
                    searchForm.submit();
                }
            });

            // Focus on search input when pressing '/' key
            document.addEventListener('keydown', function(e) {
                if (e.key === '/' && !e.ctrlKey && !e.metaKey && !e.altKey) {
                    e.preventDefault();
                    searchInput.focus();
                }
            });

            // Add visual feedback for search
            if (searchInput.value.length > 0) {
                searchInput.classList.add('border-primary');
            }

            // Highlight search terms in results
            const searchTerm = searchInput.value.toLowerCase();
            if (searchTerm.length > 0) {
                const cells = document.querySelectorAll('.table tbody td');
                cells.forEach(cell => {
                    const text = cell.textContent;
                    if (text.toLowerCase().includes(searchTerm)) {
                        const highlightedText = text.replace(
                            new RegExp(`(${searchTerm})`, 'gi'),
                            '<mark class="bg-warning text-dark">$1</mark>'
                        );
                        cell.innerHTML = highlightedText;
                    }
                });
            }
        });
    </script>

    <style>
        /* Custom search styling */
        .input-group .form-control:focus {
            border-color: #5e72e4;
            box-shadow: 0 0 0 0.2rem rgba(94, 114, 228, 0.25);
        }

        .input-group-text {
            border-color: #dee2e6;
        }

        .input-group .form-control:focus + .input-group-text,
        .input-group .input-group-text:focus {
            border-color: #5e72e4;
        }

        /* Search results highlighting */
        .search-active .table tbody tr {
            transition: all 0.3s ease;
        }

        .search-active .table tbody tr:hover {
            background-color: rgba(94, 114, 228, 0.1);
        }

        /* Retention notification styling */
        .bg-warning-subtle {
            background-color: rgba(255, 193, 7, 0.1);
            border-left: 4px solid #ffc107;
        }

        /* Search highlight */
        mark {
            padding: 0.1em 0.2em;
            border-radius: 0.2em;
        }

        /* Custom badge improvements */
        .badge {
            font-size: 0.75em;
            padding: 0.35em 0.65em;
        }

        /* Status indicators */
        .text-success { color: #28a745 !important; }
        .text-warning { color: #ffc107 !important; }
        .text-info { color: #17a2b8 !important; }
        .text-danger { color: #dc3545 !important; }

        /* Animation for new elements */
        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</x-app-layout>
