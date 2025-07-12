<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-12">
                    <div class="card card-background card-background-after-none align-items-start mt-4 mb-5">
                        <div class="full-background" style="background-image: url('../assets/img/header-blue-purple.jpg')"></div>
                        <div class="card-body text-start p-4 w-100">
                            <h3 class="text-white mb-2">Edit JRE</h3>
                            <p class="mb-4 font-weight-semibold text-white">
                                Perbarui status dan catatan JRE
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
                                    <h6 class="font-weight-semibold text-lg mb-0">Form Edit JRE</h6>
                                    <p class="text-sm mb-sm-0">Edit status dan catatan JRE untuk arsip: <span class="font-weight-bold">{{ $jre->arsip->nama_dokumen }}</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-4">
                                <label for="status" class="form-control-label text-sm">Status</label>
                                <select class="form-select" id="status" name="status" readonly disabled>
                                    <option value="inactive" {{ $jre->status == 'inactive' ? 'selected' : '' }}>Inaktif</option>
                                    <option value="destroyed" {{ $jre->status == 'destroyed' ? 'selected' : '' }}>Dimusnahkan</option>
                                    <option value="transferred" {{ $jre->status == 'transferred' ? 'selected' : '' }}>Dipindahkan</option>
                                    <option value="recovered" {{ $jre->status == 'recovered' ? 'selected' : '' }}>Dipulihkan</option>
                                </select>
                            </div>

                            @if($jre->status == 'transferred')
                            <div class="form-group mb-4">
                                <div class="alert alert-warning" role="alert">
                                    <h6 class="alert-heading mb-1">Informasi Pemindahan</h6>
                                    <p class="mb-1"><strong>Tanggal Pemindahan:</strong> {{ $jre->transferred_at ? $jre->transferred_at->format('d M Y H:i') : 'N/A' }}</p>
                                    <p class="mb-1"><strong>Dipindahkan oleh:</strong> {{ $jre->transferrer ? $jre->transferrer->name : 'N/A' }}</p>
                                    @if($jre->transfer_notes)
                                    <p class="mb-1"><strong>Catatan Pemindahan:</strong> {{ $jre->transfer_notes }}</p>
                                    @endif
                                </div>
                            </div>
                            @endif
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
                                    <h6 class="font-weight-semibold text-lg mb-0">Aksi Cepat</h6>
                                    <p class="text-sm mb-sm-0">Tindakan khusus untuk arsip di JRE</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Recover Archive -->
                                @if($jre->status != 'transferred' && $jre->status != 'destroyed')
                                <div class="col-md-3 mb-3">
                                    <div class="card border h-100">
                                        <div class="card-body">
                                            <h6 class="font-weight-semibold mb-3">Pulihkan Arsip</h6>
                                            <p class="text-sm mb-3">Mengembalikan arsip ke status aktif dan menghapus dari JRE</p>

                                            <!-- Recover with custom years -->
                                            <button type="button" class="btn btn-outline-success w-100" data-bs-toggle="modal" data-bs-target="#recoverModal">
                                                <i class="fas fa-calendar-alt me-2"></i> Pilih Tahun
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Destroy Archive -->
                                @if($jre->status != 'transferred' && $jre->status != 'destroyed')
                                <div class="col-md-3 mb-3">
                                    <div class="card border h-100">
                                        <div class="card-body">
                                            <h6 class="font-weight-semibold mb-3">Musnahkan Arsip</h6>
                                            <p class="text-sm mb-3">Menandai arsip sebagai dimusnahkan secara permanen</p>
                                            <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#destroyModal">
                                                <i class="fas fa-trash-alt me-2"></i> Musnahkan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Transfer Archive -->


                                <!-- Pemindahan Archive -->
                                @if($jre->status != 'transferred')
                                <div class="col-md-3 mb-3">
                                    <div class="card border h-100">
                                        <div class="card-body">
                                            <h6 class="font-weight-semibold mb-3">Pemindahan Arsip</h6>
                                            <p class="text-sm mb-3">Buat permintaan pemindahan arsip dengan tingkat perkembangan</p>
                                            <button type="button" class="btn btn-warning w-100" data-bs-toggle="modal" data-bs-target="#pemindahanModal">
                                                <i class="fas fa-truck-moving me-2"></i> Ajukan Pemindahan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div class="d-flex justify-content-center mt-4">
                                <a href="{{ route('jre.index') }}" class="btn btn-light">
                                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar JRE
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Destroy Modal -->
            <div class="modal fade" id="destroyModal" tabindex="-1" aria-labelledby="destroyModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('jre.destroy-archive', $jre->id) }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="destroyModalLabel">Musnahkan Arsip</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Peringatan:</strong> Arsip <strong>{{ $jre->arsip->nama_dokumen }}</strong> akan dimusnahkan secara permanen. Tindakan ini tidak dapat dibatalkan.
                                </div>

                                <div class="form-group">
                                    <label for="destruction_notes" class="form-control-label text-sm">Catatan Pemusnahan <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('destruction_notes') is-invalid @enderror" id="destruction_notes" name="destruction_notes" rows="3" required placeholder="Masukkan alasan dan detail proses pemusnahan..." minlength="10">{{ old('destruction_notes') }}</textarea>
                                    @error('destruction_notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Catatan ini akan tersimpan dalam riwayat pemusnahan dan tidak dapat diubah.
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash-alt me-2"></i>
                                    Musnahkan Arsip
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Transfer Modal -->
            <div class="modal fade" id="transferModal" tabindex="-1" aria-labelledby="transferModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('jre.transfer', $jre->id) }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="transferModalLabel">Pindahkan Arsip dan Ubah Status JRE</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-warning">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Informasi:</strong> Setelah dipindahkan, status arsip ini akan berubah menjadi "Dipindahkan" di JRE dan tidak akan muncul dalam daftar JRE aktif.
                                </div>
                                <div class="form-group mb-3">
                                    <label for="transfer_location" class="form-control-label text-sm">Lokasi Pemindahan <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="transfer_location" name="transfer_location" required placeholder="Contoh: Gudang Pusat">
                                    <small class="text-muted">Lokasi akan disimpan dalam catatan pemindahan</small>
                                </div>
                                <div class="form-group">
                                    <label for="transfer_notes" class="form-control-label text-sm">Catatan Tambahan</label>
                                    <textarea class="form-control" id="transfer_notes" name="transfer_notes" rows="3" placeholder="Tambahkan catatan tentang pemindahan ini"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-warning">Pindahkan & Ubah Status JRE</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Recover with Years Modal -->
            <div class="modal fade" id="recoverModal" tabindex="-1" aria-labelledby="recoverModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('jre.recover-with-years', $jre->id) }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="recoverModalLabel">Pulihkan Arsip dengan Masa Retensi Custom</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Informasi:</strong> Arsip <strong>{{ $jre->arsip->nama_dokumen }}</strong> akan dipulihkan dengan masa retensi yang Anda tentukan.
                                </div>

                                <div class="form-group mb-3">
                                    <label for="recovery_years" class="form-control-label text-sm">Masa Retensi (Tahun) <span class="text-danger">*</span></label>
                                    <select class="form-select" id="recovery_years" name="recovery_years" required>
                                        <option value="">Pilih Masa Retensi</option>
                                        <option value="1">1 Tahun</option>
                                        <option value="2">2 Tahun</option>
                                        <option value="3">3 Tahun</option>
                                        <option value="5" selected>5 Tahun (Default)</option>
                                        <option value="7">7 Tahun</option>
                                        <option value="10">10 Tahun</option>
                                        <option value="15">15 Tahun</option>
                                        <option value="20">20 Tahun</option>
                                        <option value="25">25 Tahun</option>
                                        <option value="30">30 Tahun</option>
                                        <option value="permanent">Permanen</option>
                                    </select>
                                    <div class="form-text">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        Masa retensi akan dihitung dari tanggal hari ini: <strong>{{ \Carbon\Carbon::now()->format('d M Y') }}</strong>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-control-label text-sm">Prediksi Tanggal Retensi:</label>
                                    <div id="retentionPreview" class="alert alert-light mt-2" style="display: none;">
                                        <i class="fas fa-calendar-check me-2"></i>
                                        <span id="retentionDate"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-undo me-2"></i>
                                    Pulihkan Arsip
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Pemindahan Modal -->
            <div class="modal fade" id="pemindahanModal" tabindex="-1" aria-labelledby="pemindahanModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form action="{{ route('pemindahan.store') }}" method="POST">
                            @csrf
                            <input type="hidden" id="modal_arsip_id" name="arsip_id" value="{{ $jre->arsip->id }}">
                            <div class="modal-header">
                                <h5 class="modal-title" id="pemindahanModalLabel">Form Pemindahan Arsip</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-warning mb-4" style="border: 2px solid #ffc107;">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-exclamation-triangle me-3" style="font-size: 1.5rem;"></i>
                                        <div>
                                            <strong style="font-size: 1.1rem;">PERHATIAN PENTING:</strong><br>
                                            Setelah proses pemindahan selesai, status arsip ini akan berubah menjadi <strong>"Dipindahkan"</strong> di JRE dan tidak akan muncul dalam daftar JRE aktif.
                                            Halaman akan otomatis dialihkan ke daftar JRE.
                                        </div>
                                    </div>
                                </div>
                                <!-- Data Arsip Section (Auto-filled) -->
                                <h6 class="font-weight-semibold mb-3">Data Arsip</h6>
                                <div class="card bg-gray-100 border-0 mb-4">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-control-label text-sm">Kode Arsip</label>
                                                <input type="text" class="form-control" id="modal_kode_arsip" readonly value="{{ $jre->arsip->kode }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-control-label text-sm">Nama Dokumen</label>
                                                <input type="text" class="form-control" id="modal_nama_dokumen" readonly value="{{ $jre->arsip->nama_dokumen }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-control-label text-sm">Kategori</label>
                                                <input type="text" class="form-control" id="modal_kategori" readonly value="{{ $jre->arsip->kategori }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-control-label text-sm">Tanggal Arsip</label>
                                                <input type="text" class="form-control" id="modal_tanggal_arsip" readonly value="{{ $jre->arsip->tanggal_arsip ? \Carbon\Carbon::parse($jre->arsip->tanggal_arsip)->format('d M Y') : '' }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-control-label text-sm">Dibuat oleh</label>
                                                <input type="text" class="form-control" id="modal_dibuat_oleh" readonly value="{{ $jre->arsip->creator->name ?? 'N/A' }}">
                                            </div>
                                        </div>
                                        @if($jre->arsip->keterangan)
                                        <div class="row">
                                            <div class="col-12">
                                                <label class="form-control-label text-sm">Keterangan Arsip</label>
                                                <textarea class="form-control" id="modal_keterangan_arsip" readonly rows="2">{{ $jre->arsip->keterangan }}</textarea>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Data Pemindahan Section (Manual Input) -->
                                <h6 class="font-weight-semibold mb-3">Data Pemindahan</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="modal_tingkat_perkembangan" class="form-control-label text-sm">Tingkat Perkembangan <span class="text-danger">*</span></label>
                                        <select class="form-select" id="modal_tingkat_perkembangan" name="tingkat_perkembangan" required>
                                            <option value="">Pilih Tingkat Perkembangan</option>
                                            <option value="asli">Asli</option>
                                            <option value="copy">Copy</option>
                                            <option value="asli_dan_copy">Asli dan Copy</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="modal_jumlah_folder" class="form-control-label text-sm">Jumlah Folder <span class="text-danger">*</span></label>
                                        <select class="form-select" id="modal_jumlah_folder" name="jumlah_folder" required>
                                            <option value="">Pilih Jumlah Folder</option>
                                            @for($i = 1; $i <= 10; $i++)
                                                <option value="{{ $i }}">{{ $i }} Folder</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="modal_keterangan" class="form-control-label text-sm">Keterangan Pemindahan</label>
                                        <textarea class="form-control" id="modal_keterangan" name="keterangan" rows="3" placeholder="Tambahkan keterangan atau alasan pemindahan..."></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-warning" id="btn-pemindahan-submit">
                                    <i class="fas fa-truck-moving me-2"></i>
                                    Pindahkan & Ubah Status JRE
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <x-app.footer />
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Retention years preview functionality
            const recoveryYearsSelect = document.getElementById('recovery_years');
            const retentionPreview = document.getElementById('retentionPreview');
            const retentionDate = document.getElementById('retentionDate');

            if (recoveryYearsSelect && retentionPreview && retentionDate) {
                recoveryYearsSelect.addEventListener('change', function() {
                    const selectedYears = this.value;

                    if (selectedYears) {
                        const today = new Date();
                        let futureDate;

                        if (selectedYears === 'permanent') {
                            retentionDate.textContent = 'Arsip akan disimpan secara permanen';
                        } else {
                            futureDate = new Date(today);
                            futureDate.setFullYear(today.getFullYear() + parseInt(selectedYears));

                            const options = { year: 'numeric', month: 'long', day: 'numeric' };
                            const formattedDate = futureDate.toLocaleDateString('id-ID', options);

                            retentionDate.textContent = `Arsip akan otomatis masuk ke JRE pada: ${formattedDate}`;
                        }

                        retentionPreview.style.display = 'block';
                    } else {
                        retentionPreview.style.display = 'none';
                    }
                });
            }

            // Handle quick recovery (default years) form submission
            const quickRecoveryForm = document.querySelector('form[action*="jre.recover"]:not([action*="jre.recover-with-years"])');
            if (quickRecoveryForm) {
                quickRecoveryForm.addEventListener('submit', function(e) {
                    console.log('Quick recovery form submit triggered');

                    if (!confirm('Yakin ingin memulihkan arsip ini dengan masa retensi default?')) {
                        e.preventDefault();
                        return false;
                    }

                    // Show loading indicator
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        const originalBtnText = submitBtn.innerHTML;
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Memproses...';
                    }

                    console.log('Quick recovery form validation passed, submitting...');
                });
            }

            // Handle custom years recovery form submission
            const customRecoveryForm = document.querySelector('#recoverModal form');
            if (customRecoveryForm) {
                customRecoveryForm.addEventListener('submit', function(e) {
                    console.log('Custom recovery form submit triggered');

                    const selectedYears = document.getElementById('recovery_years').value;

                    if (!selectedYears) {
                        alert('Silakan pilih masa retensi terlebih dahulu!');
                        e.preventDefault();
                        return false;
                    }

                    if (!confirm('Yakin ingin memulihkan arsip dengan masa retensi yang dipilih?')) {
                        e.preventDefault();
                        return false;
                    }

                    // Show loading indicator
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        const originalBtnText = submitBtn.innerHTML;
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Memproses...';
                    }

                    console.log('Custom recovery form validation passed, submitting...');
                });
            }

            // Pemindahan form submission handler
            const pemindahanForm = document.querySelector('#pemindahanModal form');
            if (pemindahanForm) {
                pemindahanForm.addEventListener('submit', function(e) {
                    e.preventDefault(); // Prevent default form submission

                    const tingkatPerkembangan = document.getElementById('modal_tingkat_perkembangan').value;
                    const jumlahFolder = document.getElementById('modal_jumlah_folder').value;

                    if (!tingkatPerkembangan) {
                        alert('Silakan pilih tingkat perkembangan!');
                        return false;
                    }

                    if (!jumlahFolder) {
                        alert('Silakan pilih jumlah folder!');
                        return false;
                    }

                    // Konfirmasi sebelum submit
                    if (!confirm('PENTING: Yakin ingin memindahkan arsip ini? Arsip akan dihapus PERMANEN dari daftar JRE dan tidak akan muncul lagi di sini.')) {
                        return false;
                    }

                    // Show loading indicator
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalBtnText = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Memproses...';

                    // Submit form via AJAX
                    const formData = new FormData(this);
                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show success message
                            alert('Arsip berhasil dipindahkan dan dihapus dari JRE!');
                            // Force redirect to JRE index and prevent going back
                            window.location.replace("{{ route('jre.index') }}");
                        } else {
                            // Show error
                            alert('Error: ' + (data.message || 'Terjadi kesalahan saat memproses permintaan'));
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalBtnText;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat memproses permintaan. Silahkan coba lagi.');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnText;
                    });
                });
            }

            // Destroy form validation
            const destroyForm = document.querySelector('#destroyModal form');
            if (destroyForm) {
                destroyForm.addEventListener('submit', function(e) {
                    console.log('Form destroy submit triggered');

                    // Validasi form
                    const notes = document.getElementById('destruction_notes').value;
                    const method = document.getElementById('destruction_method').value;

                    if (!notes || notes.length < 10) {
                        alert('Catatan pemusnahan minimal 10 karakter!');
                        e.preventDefault();
                        return false;
                    }

                    if (!method) {
                        alert('Silakan pilih metode pemusnahan!');
                        e.preventDefault();
                        return false;
                    }

                    // Final confirmation
                    if (!confirm('Yakin ingin memusnahkan arsip ini? Tindakan ini tidak dapat dibatalkan.')) {
                        e.preventDefault();
                        return false;
                    }

                    console.log('Form validation passed, submitting...');
                });
            }
        });
    </script>
</x-app-layout>
