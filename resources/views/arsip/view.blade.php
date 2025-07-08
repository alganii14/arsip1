<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-12">
                    <div class="card card-background card-background-after-none align-items-start mt-4 mb-5">
                        <div class="full-background" style="background-image: url('../assets/img/header-blue-purple.jpg')"></div>
                        <div class="card-body text-start p-4 w-100">
                            <h3 class="text-white mb-2">Pratinjau Arsip Digital</h3>
                            <p class="mb-4 font-weight-semibold text-white">
                                Melihat isi dokumen arsip: {{ $arsip->nama_dokumen }}
                            </p>
                            <a href="javascript:history.back()" class="btn btn-outline-white btn-blur btn-icon d-flex align-items-center mb-0">
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
                                    <h6 class="font-weight-semibold text-lg mb-0">{{ $arsip->nama_dokumen }}</h6>
                                    <p class="text-sm mb-sm-0">Kode: {{ $arsip->kode }} | Kategori: {{ $arsip->kategori }}</p>
                                </div>
                                <div class="ms-auto d-flex">
                                    @if(Auth::user()->role !== 'peminjam')
                                        <a href="{{ route('arsip.download', $arsip->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-download me-1"></i> Download
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($fileExtension === 'pdf')
                                <div class="text-center mb-3">
                                    <p class="text-muted">Dokumen PDF - Gunakan kontrol di bawah untuk navigasi</p>
                                </div>
                                <iframe src="{{ asset('storage/' . $arsip->file_path) }}"
                                        width="100%"
                                        height="800px"
                                        style="border: 1px solid #ddd; border-radius: 8px;">
                                    <p>Browser Anda tidak mendukung pratinjau PDF.
                                       <a href="{{ asset('storage/' . $arsip->file_path) }}" target="_blank">Klik di sini untuk membuka file</a>
                                    </p>
                                </iframe>
                            @elseif($fileExtension === 'txt')
                                <div class="bg-light p-4 rounded">
                                    <pre style="white-space: pre-wrap; font-family: 'Courier New', monospace;">{{ file_get_contents($filePath) }}</pre>
                                </div>
                            @else
                                <div class="alert alert-warning text-center">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Format file tidak dapat ditampilkan secara langsung. Hanya file PDF dan teks yang didukung untuk pratinjau.
                                </div>
                            @endif

                            <div class="mt-4 p-3 bg-light rounded">
                                <div class="row">
                                    <div class="col-md-3">
                                        <small class="text-muted">Tanggal Arsip:</small>
                                        <p class="mb-0 font-weight-semibold">{{ $arsip->tanggal_arsip }}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted">Rak Penyimpanan:</small>
                                        <p class="mb-0 font-weight-semibold">{{ $arsip->rak ?: 'Tidak ada info' }}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted">Format File:</small>
                                        <p class="mb-0 font-weight-semibold">{{ strtoupper($fileExtension) }}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted">Status:</small>
                                        <span class="badge bg-success">Dapat Diakses</span>
                                    </div>
                                </div>
                            </div>

                            @if(Auth::user()->role === 'peminjam')
                                <div class="alert alert-info mt-3">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Informasi:</strong> Anda dapat melihat arsip ini karena peminjaman Anda telah disetujui.
                                    Akses ini berlaku selama masa peminjaman.
                                </div>
                            @endif

                            <!-- Navigation Buttons -->
                            <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                                <a href="javascript:history.back()" class="btn btn-light">
                                    <i class="fas fa-arrow-left me-2"></i> Kembali
                                </a>

                                @if(Auth::user()->role === 'peminjam')
                                    <a href="{{ route('peminjaman.index') }}" class="btn btn-outline-primary">
                                        <i class="fas fa-list me-2"></i> Daftar Peminjaman
                                    </a>
                                @else
                                    <a href="{{ route('arsip.index') }}" class="btn btn-outline-primary">
                                        <i class="fas fa-archive me-2"></i> Daftar Arsip
                                    </a>
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
