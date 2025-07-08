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
                                            <p class="font-weight-semibold mb-0">{{ $jre->processed_at->format('d/m/Y H:i') }} WIB</p>
                                        </div>
                                        @if($jre->recovery_years)
                                        <div class="mb-3">
                                            <p class="text-xs text-secondary mb-1">Masa Pemulihan</p>
                                            @if($jre->recovery_years >= 999)
                                                <span class="badge badge-sm bg-warning text-dark">
                                                    <i class="fas fa-infinity me-1"></i> Permanen
                                                </span>
                                            @else
                                                <span class="badge badge-sm bg-primary text-white">
                                                    {{ $jre->recovery_years }} Tahun
                                                </span>
                                            @endif
                                        </div>
                                        @endif

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

            <!-- Timeline Section -->
            <div class="row">
                <div class="col-12">
                    <div class="card border shadow-xs mb-4">
                        <div class="card-header border-bottom pb-0">
                            <div class="d-sm-flex align-items-center mb-3">
                                <div>
                                    <h6 class="font-weight-semibold text-lg mb-0">Timeline Arsip</h6>
                                    <p class="text-sm mb-sm-0">Riwayat perjalanan arsip dari awal hingga masuk JRE</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="timeline timeline-one-side" data-timeline-axis-style="dashed">
                                <!-- Archive Created -->
                                <div class="timeline-block mb-3">
                                    <span class="timeline-step">
                                        <i class="fas fa-file-plus text-success"></i>
                                    </span>
                                    <div class="timeline-content">
                                        <h6 class="text-dark text-sm font-weight-bold mb-0">Arsip Dibuat</h6>
                                        <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">{{ $jre->arsip->created_at->format('d M Y, H:i') }} WIB</p>
                                        <p class="text-sm mt-3 mb-2">
                                            Arsip <strong>{{ $jre->arsip->nama_dokumen }}</strong> dibuat dengan kode <strong>{{ $jre->arsip->kode }}</strong>
                                        </p>
                                    </div>
                                </div>

                                <!-- Archive Active Period -->
                                <div class="timeline-block mb-3">
                                    <span class="timeline-step">
                                        <i class="fas fa-calendar-check text-info"></i>
                                    </span>
                                    <div class="timeline-content">
                                        <h6 class="text-dark text-sm font-weight-bold mb-0">Periode Aktif</h6>
                                        <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">{{ $jre->arsip->tanggal_arsip->format('d M Y') }} - {{ $jre->arsip->retention_date->format('d M Y') }}</p>
                                        <p class="text-sm mt-3 mb-2">
                                            Arsip berada dalam status aktif selama masa retensi
                                        </p>
                                    </div>
                                </div>

                                <!-- Moved to JRE -->
                                <div class="timeline-block mb-3">
                                    <span class="timeline-step">
                                        <i class="fas fa-archive text-warning"></i>
                                    </span>
                                    <div class="timeline-content">
                                        <h6 class="text-dark text-sm font-weight-bold mb-0">Masuk ke JRE</h6>
                                        <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">{{ $jre->processed_at->format('d M Y, H:i') }} WIB</p>
                                        <p class="text-sm mt-3 mb-2">
                                            Arsip otomatis masuk ke Jadwal Retensi Elektronik (JRE) karena telah melewati masa retensi
                                        </p>
                                    </div>
                                </div>

                                <!-- Current Status -->
                                <div class="timeline-block mb-3">
                                    <span class="timeline-step">
                                        @if($jre->status == 'inactive')
                                            <i class="fas fa-pause text-warning"></i>
                                        @elseif($jre->status == 'destroyed')
                                            <i class="fas fa-trash text-danger"></i>
                                        @elseif($jre->status == 'transferred')
                                            <i class="fas fa-exchange-alt text-primary"></i>
                                        @elseif($jre->status == 'recovered')
                                            <i class="fas fa-undo text-success"></i>
                                        @else
                                            <i class="fas fa-clock text-secondary"></i>
                                        @endif
                                    </span>
                                    <div class="timeline-content">
                                        <h6 class="text-dark text-sm font-weight-bold mb-0">Status Saat Ini</h6>
                                        <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">{{ \Carbon\Carbon::now()->format('d M Y, H:i') }} WIB</p>
                                        <p class="text-sm mt-3 mb-2">
                                            @if($jre->status == 'inactive')
                                                Arsip dalam status <span class="badge badge-sm bg-warning text-dark">Inaktif</span> - menunggu tindakan lebih lanjut
                                            @elseif($jre->status == 'destroyed')
                                                Arsip telah <span class="badge badge-sm bg-danger text-white">Dimusnahkan</span> secara permanen
                                            @elseif($jre->status == 'transferred')
                                                Arsip telah <span class="badge badge-sm bg-primary text-white">Dipindahkan</span> ke lokasi lain
                                            @elseif($jre->status == 'recovered')
                                                Arsip telah <span class="badge badge-sm bg-success text-white">Dipulihkan</span> kembali ke status aktif
                                            @else
                                                Arsip dalam status <span class="badge badge-sm bg-secondary text-white">{{ ucfirst($jre->status) }}</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                @if($jre->recovery_years)
                                <!-- Recovery Information -->
                                <div class="timeline-block mb-3">
                                    <span class="timeline-step">
                                        <i class="fas fa-calendar-plus text-info"></i>
                                    </span>
                                    <div class="timeline-content">
                                        <h6 class="text-dark text-sm font-weight-bold mb-0">Informasi Pemulihan</h6>
                                        <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">Masa Retensi Tambahan</p>
                                        <p class="text-sm mt-3 mb-2">
                                            @if($jre->recovery_years >= 999)
                                                Arsip ditetapkan dengan masa retensi <span class="badge badge-sm bg-warning text-dark">Permanen</span>
                                            @else
                                                Masa retensi tambahan: <span class="badge badge-sm bg-primary text-white">{{ $jre->recovery_years }} Tahun</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
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
