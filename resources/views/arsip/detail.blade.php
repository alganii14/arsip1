<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />

        <style>
            .classification-badge {
                font-size: 0.875rem;
                padding: 0.5rem 1rem;
                border-radius: 0.375rem;
            }

            .alert-light {
                background-color: #f8f9fa;
                border-color: #e9ecef;
                color: #495057;
            }

            .border-left-primary {
                border-left: 4px solid #007bff !important;
            }

            .classification-hierarchy {
                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                border-radius: 0.375rem;
                padding: 0.75rem;
            }

            .hierarchy-item {
                display: flex;
                align-items: center;
                padding: 0.25rem 0;
                border-bottom: 1px solid rgba(0,0,0,0.05);
            }

            .hierarchy-item:last-child {
                border-bottom: none;
            }

            .hierarchy-badge {
                min-width: 60px;
                text-align: center;
                font-weight: 600;
            }
        </style>

        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-12">
                    <div class="card card-background card-background-after-none align-items-start mt-4 mb-5">
                        <div class="full-background" style="background-image: url('{{ asset('assets/img/header-blue-purple.jpg') }}')"></div>
                        <div class="card-body text-start p-4 w-100">
                            <h3 class="text-white mb-2">Detail Arsip</h3>
                            <p class="mb-4 font-weight-semibold text-white">
                                Informasi lengkap tentang arsip dokumen
                            </p>
                            <div class="d-flex">
                                <a href="{{ route('arsip.index') }}" class="btn btn-outline-white btn-blur btn-icon d-flex align-items-center mb-0 me-2">
                                    <span class="btn-inner--icon">
                                        <i class="fas fa-arrow-left me-2"></i>
                                    </span>
                                    <span class="btn-inner--text">Kembali</span>
                                </a>
                                @if(!$arsip->is_archived_to_jre && !$arsip->isCurrentlyBorrowed())
                                <a href="{{ route('peminjaman.create') }}?arsip_id={{ $arsip->id }}" class="btn btn-outline-white btn-blur btn-icon d-flex align-items-center mb-0">
                                    <span class="btn-inner--icon">
                                        <i class="fas fa-download me-2"></i>
                                    </span>
                                    <span class="btn-inner--text">Pinjam Arsip</span>
                                </a>
                                @endif
                            </div>
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
                                    <h6 class="font-weight-semibold text-lg mb-0">{{ $arsip->nama_dokumen }}</h6>
                                    <p class="text-sm mb-sm-0">Kode: {{ $arsip->kode }}</p>
                                </div>
                                <div class="ms-auto d-flex">
                                    @php
                                        $badgeClass = \App\Helpers\ClassificationFormatter::getBadgeClass($arsip->kategori);
                                    @endphp

                                    <span class="badge badge-sm border {{ $badgeClass }} ms-auto">
                                        {{ $arsip->kategori }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-sm font-weight-semibold mb-1">Informasi Arsip</h6>
                                    <div class="card border shadow-xs mb-4">
                                        <div class="card-body p-3">
                                            <div class="row mb-2">
                                                <div class="col-5 text-sm font-weight-semibold">Tanggal Arsip:</div>
                                                <div class="col-7 text-sm">{{ $arsip->tanggal_arsip->format('d/m/Y') }}</div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-5 text-sm font-weight-semibold">Rak Penyimpanan:</div>
                                                <div class="col-7 text-sm">{{ $arsip->rak ?: 'Tidak ada informasi rak' }}</div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-5 text-sm font-weight-semibold">Masa Retensi:</div>
                                                <div class="col-7 text-sm">
                                                    {{ $arsip->retention_years ?? 5 }} tahun
                                                    @if($arsip->retention_date)
                                                        <small class="d-block text-muted">
                                                            Berakhir: {{ $arsip->retention_date->format('d/m/Y') }}
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-5 text-sm font-weight-semibold">Status:</div>
                                                <div class="col-7 text-sm">
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
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h6 class="text-sm font-weight-semibold mb-1">Keterangan</h6>
                                    <div class="card border shadow-xs mb-4">
                                        <div class="card-body p-3">
                                            <p class="text-sm mb-0">
                                                {{ $arsip->keterangan ?: 'Tidak ada keterangan tambahan.' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Classification Section -->
                            <div class="row">
                                <div class="col-12">
                                    <h6 class="text-sm font-weight-semibold mb-1">Klasifikasi Dokumen</h6>
                                    <div class="card border shadow-xs mb-4">
                                        <div class="card-body p-3">
                                            @php
                                                $description = \App\Helpers\ClassificationFormatter::getDescription($arsip->kategori);
                                                $badgeClass = \App\Helpers\ClassificationFormatter::getBadgeClass($arsip->kategori);
                                            @endphp

                                            <div class="d-flex align-items-center mb-3">
                                                <span class="badge classification-badge border {{ $badgeClass }} me-3">
                                                    {{ $arsip->kategori }}
                                                </span>
                                                <div>
                                                    <h6 class="mb-0">{{ $description ?: 'Klasifikasi Umum' }}</h6>
                                                    <small class="text-muted">Kode Klasifikasi: {{ $arsip->kategori }}</small>
                                                </div>
                                            </div>

                                            @if($description)
                                                <div class="alert alert-light border-left-primary mb-3">
                                                    <div class="d-flex">
                                                        <div class="alert-icon">
                                                            <i class="fas fa-info-circle text-primary"></i>
                                                        </div>
                                                        <div class="ms-3">
                                                            <h6 class="alert-heading text-sm font-weight-semibold mb-2">Deskripsi Klasifikasi</h6>
                                                            <p class="mb-0 text-sm">{{ $description }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            @php
                                                $classificationInfo = \App\Helpers\ClassificationFormatter::getClassificationInfo($arsip->kategori);
                                            @endphp

                                            @if($classificationInfo && count($classificationInfo) > 0)
                                                <div class="classification-hierarchy">
                                                    <small class="text-muted font-weight-semibold d-block mb-2">
                                                        <i class="fas fa-sitemap me-1"></i> Hierarki Klasifikasi:
                                                    </small>
                                                    @foreach($classificationInfo as $level => $info)
                                                        <div class="hierarchy-item">
                                                            <span class="badge hierarchy-badge bg-light text-dark me-3">{{ $level }}</span>
                                                            <span class="text-sm">{{ $info }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif

                                            <!-- Classification Reference -->
                                            <div class="mt-3 pt-3 border-top">
                                                <small class="text-muted">
                                                    <i class="fas fa-book me-1"></i>
                                                    Referensi:
                                                    @if(strpos($arsip->kategori, 'AR.') === 0)
                                                        Sistem Klasifikasi Kearsipan
                                                    @elseif(strpos($arsip->kategori, 'KP') === 0)
                                                        Klasifikasi Kepegawaian
                                                    @elseif(strpos($arsip->kategori, 'TU') === 0)
                                                        Klasifikasi Tata Usaha
                                                    @else
                                                        Klasifikasi Umum
                                                    @endif
                                                    - <a href="https://jdih.bandung.go.id/home/" target="_blank" class="text-primary">jdih.bandung.go.id</a>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('arsip.index') }}" class="btn btn-light">
                                    <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar
                                </a>

                                @if(!$arsip->is_archived_to_jre && !$arsip->isCurrentlyBorrowed())
                                <a href="{{ route('peminjaman.create') }}?arsip_id={{ $arsip->id }}" class="btn btn-primary">
                                    <i class="fas fa-download me-2"></i> Pinjam Arsip
                                </a>
                                @elseif($arsip->isCurrentlyBorrowed())
                                <button class="btn btn-secondary" disabled>
                                    <i class="fas fa-ban me-2"></i> Arsip Sedang Dipinjam
                                </button>
                                @else
                                <button class="btn btn-secondary" disabled>
                                    <i class="fas fa-archive me-2"></i> Arsip di JRE
                                </button>
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
