<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-12">
                    <div class="card card-background card-background-after-none align-items-start mt-4 mb-5">
                        <div class="full-background" style="background-image: url('../assets/img/header-blue-purple.jpg')"></div>
                        <div class="card-body text-start p-4 w-100">
                            <h3 class="text-white mb-2">Jadwal Retensi Elektronik (JRE)</h3>
                            <p class="mb-4 font-weight-semibold text-white">
                                Kelola arsip yang telah melewati masa retensi
                            </p>
                            <div class="d-flex">
                                <a href="{{ route('jre.create') }}" class="btn btn-outline-white btn-blur btn-icon d-flex align-items-center me-2">
                                    <span class="btn-inner--icon">
                                        <svg width="14" height="14" viewBox="0 0 14 14" xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="d-block me-2">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M7 0C3.13401 0 0 3.13401 0 7C0 10.866 3.13401 14 7 14C10.866 14 14 10.866 14 7C14 3.13401 10.866 0 7 0ZM7.5 3.5C7.5 3.22386 7.27614 3 7 3C6.72386 3 6.5 3.22386 6.5 3.5V6.5H3.5C3.22386 6.5 3 6.72386 3 7C3 7.27614 3.22386 7.5 3.5 7.5H6.5V10.5C6.5 10.7761 6.72386 11 7 11C7.27614 11 7.5 10.7761 7.5 10.5V7.5H10.5C10.7761 7.5 11 7.27614 11 7C11 6.72386 10.7761 6.5 10.5 6.5H7.5V3.5Z" />
                                        </svg>
                                    </span>
                                    <span class="btn-inner--text">Tambah JRE</span>
                                </a>
                                <a href="{{ route('archive-destructions.index') }}" class="btn btn-outline-white btn-blur btn-icon d-flex align-items-center me-2">
                                    <span class="btn-inner--icon">
                                        <i class="fas fa-fire me-2"></i>
                                    </span>
                                    <span class="btn-inner--text">Riwayat Pemusnahan</span>
                                </a>
                                <a href="{{ route('arsip.index') }}" class="btn btn-outline-white btn-blur btn-icon d-flex align-items-center">
                                    <span class="btn-inner--icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block me-2">
                                            <path d="M19 12H5M12 19l-7-7 7-7"/>
                                        </svg>
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

            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border shadow-xs">
                        <div class="card-header border-bottom pb-0">
                            <div class="d-sm-flex align-items-center">
                                <div>
                                    <h6 class="font-weight-semibold text-lg mb-0">Daftar Arsip di JRE</h6>
                                    @if(request('search'))
                                        <small class="text-muted d-block">
                                            <i class="fas fa-search me-1"></i>
                                            Hasil pencarian untuk "<strong>{{ request('search') }}</strong>" - {{ count($jres) }} arsip ditemukan
                                        </small>
                                    @else
                                        <small class="text-muted d-block">
                                            <i class="fas fa-archive me-1"></i>
                                            Total: {{ count($jres) }} arsip
                                        </small>
                                    @endif
                                </div>
                                <div class="ms-auto d-flex align-items-center">
                                    <form method="GET" action="{{ route('jre.index') }}" class="d-flex align-items-center">
                                        <div class="input-group" style="width: 350px;">
                                            <span class="input-group-text bg-white border-end-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16px" height="16px" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="text-muted">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"></path>
                                                </svg>
                                            </span>
                                            <input type="text" name="search" class="form-control border-start-0 border-end-0" placeholder="Cari berdasarkan kode, nama dokumen, atau rak..." value="{{ request('search') }}" style="box-shadow: none;">
                                            @if(request('search'))
                                                <a href="{{ route('jre.index') }}" class="input-group-text bg-white border-start-0 text-decoration-none" title="Hapus pencarian">
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
                                        <a href="{{ route('jre.index') }}" class="btn btn-sm btn-outline-info ms-auto">
                                            <i class="fas fa-times me-1"></i> Hapus Filter
                                        </a>
                                    </div>
                                </div>
                            @endif
                            <div class="table-responsive p-0 {{ request('search') ? 'search-active' : '' }}">
                                <table class="table align-items-center mb-0">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7">Kode Arsip</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Nama Dokumen</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Tanggal Arsip</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Tanggal Retensi</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Rak</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Status JRE</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Masa Pemulihan</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Tanggal Proses</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($jres as $jre)
                                        <tr>
                                            <td class="ps-2">
                                                <p class="text-sm font-weight-semibold mb-0">{{ $jre->arsip->kode }}</p>
                                            </td>
                                            <td>
                                                <p class="text-sm font-weight-semibold mb-0">{{ $jre->arsip->nama_dokumen }}</p>
                                            </td>
                                            <td>
                                                <p class="text-sm font-weight-normal mb-0">{{ $jre->arsip->tanggal_arsip->format('d/m/Y') }}</p>
                                            </td>
                                            <td>
                                                <p class="text-sm font-weight-normal mb-0">{{ $jre->arsip->retention_date->format('d/m/Y') }}</p>
                                            </td>
                                            <td>
                                                <p class="text-sm font-weight-normal mb-0">{{ $jre->arsip->rak ?: '-' }}</p>
                                            </td>
                                            <td>
                                                @php
                                                    $statusClass = 'info';
                                                    $statusText = 'Inaktif';

                                                    if($jre->status == 'inactive') {
                                                        $statusClass = 'info';
                                                        $statusText = 'Inaktif';
                                                    } elseif($jre->status == 'destroyed') {
                                                        $statusClass = 'danger';
                                                        $statusText = 'Dimusnahkan';
                                                    } elseif($jre->status == 'transferred') {
                                                        $statusClass = 'warning';
                                                        $statusText = 'Dipindahkan';
                                                    } elseif($jre->status == 'recovered') {
                                                        $statusClass = 'success';
                                                        $statusText = 'Dipulihkan';
                                                    }
                                                @endphp
                                                <span class="badge badge-sm border border-{{ $statusClass }} text-{{ $statusClass }} bg-{{ $statusClass }}">
                                                    {{ $statusText }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($jre->recovery_years)
                                                    <p class="text-sm font-weight-normal mb-0">
                                                        @if($jre->recovery_years >= 999)
                                                            <span class="badge badge-sm bg-warning text-dark">
                                                                <i class="fas fa-infinity me-1"></i> Permanen
                                                            </span>
                                                        @else
                                                            <span class="badge badge-sm bg-primary text-white">
                                                                {{ $jre->recovery_years }} Tahun
                                                            </span>
                                                        @endif
                                                    </p>
                                                @else
                                                    <p class="text-sm font-weight-normal mb-0 text-muted">-</p>
                                                @endif
                                            </td>
                                            <td>
                                                <p class="text-sm font-weight-normal mb-0">{{ $jre->processed_at->format('d/m/Y H:i') }} WIB</p>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <a href="{{ route('jre.show', $jre->id) }}" class="btn btn-sm btn-info me-2">
                                                        <i class="fas fa-eye me-1"></i> Detail
                                                    </a>
                                                    <a href="{{ route('jre.edit', $jre->id) }}" class="btn btn-sm btn-warning me-2">
                                                        <i class="fas fa-edit me-1"></i> Edit
                                                    </a>
                                                    <form action="{{ route('jre.destroy', $jre->id) }}" method="POST" style="display:inline;">
                                                        @csrf @method('DELETE')
                                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus? Ini akan mengembalikan arsip ke status normal.')">
                                                            <i class="fas fa-trash me-1"></i> Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if(count($jres) == 0)
                            <div class="text-center p-5">
                                @if(request('search'))
                                    <div class="mb-3">
                                        <i class="fas fa-search-minus text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                    <h6 class="text-muted mb-3">Tidak ada hasil untuk pencarian "{{ request('search') }}"</h6>
                                    <p class="text-muted mb-3">Coba gunakan kata kunci yang berbeda atau pastikan ejaan sudah benar</p>
                                    <a href="{{ route('jre.index') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-arrow-left me-1"></i> Kembali ke semua JRE
                                    </a>
                                @else
                                    <div class="mb-3">
                                        <i class="fas fa-inbox text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                    <h6 class="text-muted mb-3">Belum ada arsip yang dipindahkan ke JRE</h6>
                                    <p class="text-muted mb-3">Arsip yang telah melewati masa retensi akan muncul di sini</p>

                                @endif
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

        /* Search indicator */
        .search-indicator {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #5e72e4;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
    </style>
</x-app-layout>
