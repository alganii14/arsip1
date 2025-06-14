<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-12">
                    <div class="card card-background card-background-after-none align-items-start mt-4 mb-5">
                        <div class="full-background" style="background-image: url('../assets/img/header-blue-purple.jpg')"></div>
                        <div class="card-body text-start p-4 w-100">
                            <h3 class="text-white mb-2">Detail JRE</h3>
                            <p class="mb-4 font-weight-semibold text-white">
                                Informasi detail tentang arsip di JRE
                            </p>
                            <a href="{{ route('jre.index') }}" class="btn btn-outline-white btn-blur btn-icon d-flex align-items-center mb-0">
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
                                    <h6 class="font-weight-semibold text-lg mb-0">Informasi JRE</h6>
                                    <p class="text-sm mb-sm-0">Detail lengkap JRE dan arsip terkait</p>
                                </div>
                                <div class="ms-auto">
                                    <a href="{{ route('jre.edit', $jre->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit me-1"></i> Edit JRE
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-sm font-weight-semibold mb-3">Informasi JRE</h6>
                                    <div class="border rounded p-3 mb-4">
                                        <div class="mb-3">
                                            <p class="text-xs text-secondary mb-1">Status</p>
                                            @php
                                                $statusClass = 'success';
                                                if($jre->status == 'inactive') {
                                                    $statusClass = 'warning';
                                                } elseif($jre->status == 'destroyed') {
                                                    $statusClass = 'danger';
                                                }
                                            @endphp
                                            <span class="badge badge-sm border border-{{ $statusClass }} text-{{ $statusClass }} bg-{{ $statusClass }}">
                                                {{ ucfirst($jre->status) }}
                                            </span>
                                        </div>
                                        <div class="mb-3">
                                            <p class="text-xs text-secondary mb-1">Tanggal Proses</p>
                                            <p class="font-weight-semibold mb-0">{{ $jre->processed_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                        <div class="mb-0">
                                            <p class="text-xs text-secondary mb-1">Catatan</p>
                                            <p class="font-weight-semibold mb-0">{{ $jre->notes ?? 'Tidak ada catatan' }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <h6 class="text-sm font-weight-semibold mb-3">Informasi Arsip</h6>
                                    <div class="border rounded p-3">
                                        <div class="mb-3">
                                            <p class="text-xs text-secondary mb-1">Kode</p>
                                            <p class="font-weight-semibold mb-0">{{ $jre->arsip->kode }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <p class="text-xs text-secondary mb-1">Nama Dokumen</p>
                                            <p class="font-weight-semibold mb-0">{{ $jre->arsip->nama_dokumen }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <p class="text-xs text-secondary mb-1">Kategori</p>
                                            <p class="font-weight-semibold mb-0">{{ $jre->arsip->kategori }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <p class="text-xs text-secondary mb-1">Tanggal Arsip</p>
                                            <p class="font-weight-semibold mb-0">{{ $jre->arsip->tanggal_arsip->format('d/m/Y') }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <p class="text-xs text-secondary mb-1">Tanggal Retensi</p>
                                            <p class="font-weight-semibold mb-0">{{ $jre->arsip->retention_date->format('d/m/Y') }}</p>
                                        </div>
                                        <div class="mb-0">
                                            <p class="text-xs text-secondary mb-1">File</p>
                                            @if($jre->arsip->file_path)
                                                <a href="{{ route('arsip.download', $jre->arsip->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-download me-1"></i> Download File
                                                </a>
                                            @else
                                                <p class="font-weight-semibold mb-0">Tidak ada file</p>
                                            @endif
                                        </div>
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