<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-12">
                    <div class="card card-background card-background-after-none align-items-start mt-4 mb-5">
                        <div class="full-background" style="background-image: url('../assets/img/header-blue-purple.jpg')"></div>
                        <div class="card-body text-start p-4 w-100">
                            <h3 class="text-white mb-2">Detail Pemusnahan Arsip</h3>
                            <p class="mb-4 font-weight-semibold text-white">
                                Informasi lengkap tentang proses pemusnahan arsip
                            </p>
                            <a href="{{ route('archive-destructions.index') }}" class="btn btn-outline-white btn-blur btn-icon d-flex align-items-center mb-0">
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
                                    <h6 class="font-weight-semibold text-lg mb-0">Informasi Pemusnahan</h6>
                                    <p class="text-sm mb-sm-0">Detail lengkap proses pemusnahan arsip</p>
                                </div>
                                <div class="ms-auto">
                                    <span class="badge badge-lg bg-danger text-white">
                                        <i class="fas fa-fire me-1"></i> DIMUSNAHKAN
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-sm font-weight-semibold mb-3">Informasi Arsip</h6>
                                    <div class="border rounded p-3 mb-4">
                                        <div class="mb-3">
                                            <p class="text-xs text-secondary mb-1">Kode Arsip</p>
                                            <p class="font-weight-semibold mb-0">{{ $archiveDestruction->arsip->kode }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <p class="text-xs text-secondary mb-1">Nama Dokumen</p>
                                            <p class="font-weight-semibold mb-0">{{ $archiveDestruction->arsip->nama_dokumen }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <p class="text-xs text-secondary mb-1">Kategori</p>
                                            <p class="font-weight-semibold mb-0">{{ $archiveDestruction->arsip->kategori }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <p class="text-xs text-secondary mb-1">Tanggal Arsip</p>
                                            <p class="font-weight-semibold mb-0">{{ $archiveDestruction->arsip->tanggal_arsip->format('d/m/Y') }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <p class="text-xs text-secondary mb-1">Sifat</p>
                                            <span class="badge badge-sm bg-info text-white">
                                                {{ ucfirst($archiveDestruction->arsip->sifat) }}
                                            </span>
                                        </div>
                                        <div class="mb-0">
                                            <p class="text-xs text-secondary mb-1">Rak Penyimpanan</p>
                                            <p class="font-weight-semibold mb-0">{{ $archiveDestruction->arsip->rak ?: 'Tidak ada' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h6 class="text-sm font-weight-semibold mb-3">Detail Pemusnahan</h6>
                                    <div class="border rounded p-3 mb-4">
                                        <div class="mb-3">
                                            <p class="text-xs text-secondary mb-1">Tanggal Pemusnahan</p>
                                            <p class="font-weight-semibold mb-0">{{ $archiveDestruction->destroyed_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <p class="text-xs text-secondary mb-1">Metode Pemusnahan</p>
                                            <span class="badge badge-sm bg-danger text-white">
                                                {{ $archiveDestruction->destruction_method_text }}
                                            </span>
                                        </div>
                                        <div class="mb-3">
                                            <p class="text-xs text-secondary mb-1">Petugas yang Memusnahkan</p>
                                            <p class="font-weight-semibold mb-0">{{ $archiveDestruction->user->name }}</p>
                                        </div>
                                        @if($archiveDestruction->destruction_location)
                                        <div class="mb-3">
                                            <p class="text-xs text-secondary mb-1">Lokasi Pemusnahan</p>
                                            <p class="font-weight-semibold mb-0">{{ $archiveDestruction->destruction_location }}</p>
                                        </div>
                                        @endif
                                        @if($archiveDestruction->destruction_witnesses)
                                        <div class="mb-3">
                                            <p class="text-xs text-secondary mb-1">Saksi Pemusnahan</p>
                                            <p class="font-weight-semibold mb-0">{{ $archiveDestruction->destruction_witnesses }}</p>
                                        </div>
                                        @endif
                                        <div class="mb-0">
                                            <p class="text-xs text-secondary mb-1">Catatan Pemusnahan</p>
                                            <div class="bg-light rounded p-3">
                                                <p class="font-weight-normal mb-0">{{ $archiveDestruction->destruction_notes }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                                    <h6 class="font-weight-semibold text-lg mb-0">Timeline Arsip</h6>
                                    <p class="text-sm mb-sm-0">Riwayat lengkap arsip dari awal hingga pemusnahan</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="timeline timeline-simple">
                                <div class="timeline-item">
                                    <div class="timeline-icon bg-success">
                                        <i class="fas fa-file-plus text-white"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6 class="text-sm font-weight-semibold mb-1">Arsip Dibuat</h6>
                                        <p class="text-xs text-secondary mb-0">{{ $archiveDestruction->arsip->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>

                                <div class="timeline-item">
                                    <div class="timeline-icon bg-info">
                                        <i class="fas fa-clock text-white"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6 class="text-sm font-weight-semibold mb-1">Masa Retensi Berakhir</h6>
                                        <p class="text-xs text-secondary mb-0">{{ $archiveDestruction->arsip->retention_date ? $archiveDestruction->arsip->retention_date->format('d/m/Y') : 'Tidak ada' }}</p>
                                    </div>
                                </div>

                                <div class="timeline-item">
                                    <div class="timeline-icon bg-warning">
                                        <i class="fas fa-archive text-white"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6 class="text-sm font-weight-semibold mb-1">Dipindahkan ke JRE</h6>
                                        <p class="text-xs text-secondary mb-0">{{ $archiveDestruction->arsip->archived_to_jre_at ? $archiveDestruction->arsip->archived_to_jre_at->format('d/m/Y H:i') : 'Tidak ada' }}</p>
                                    </div>
                                </div>

                                <div class="timeline-item">
                                    <div class="timeline-icon bg-danger">
                                        <i class="fas fa-fire text-white"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6 class="text-sm font-weight-semibold mb-1">Arsip Dimusnahkan</h6>
                                        <p class="text-xs text-secondary mb-0">{{ $archiveDestruction->destroyed_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <x-app.footer />
        </div>
    </main>
</x-app-layout>
