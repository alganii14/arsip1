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
                                <div class="col-md-4 mb-3">
                                    <div class="card border h-100">
                                        <div class="card-body">
                                            <h6 class="font-weight-semibold mb-3">Pulihkan Arsip</h6>
                                            <p class="text-sm mb-3">Mengembalikan arsip ke status aktif dan menghapus dari JRE</p>

                                            <!-- Quick recover with default years -->
                                            <form action="{{ route('jre.recover', $jre->id) }}" method="POST" class="mb-2">
                                                @csrf
                                                <button type="submit" class="btn btn-success w-100" onclick="return confirm('Yakin ingin memulihkan arsip ini dengan masa retensi default?')">
                                                    <i class="fas fa-undo me-2"></i> Pulihkan (Default)
                                                </button>
                                            </form>

                                            <!-- Recover with custom years -->
                                            <button type="button" class="btn btn-outline-success w-100" data-bs-toggle="modal" data-bs-target="#recoverModal">
                                                <i class="fas fa-calendar-alt me-2"></i> Pilih Tahun
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Destroy Archive -->
                                <div class="col-md-4 mb-3">
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

                                <!-- Transfer Archive -->
                                <div class="col-md-4 mb-3">
                                    <div class="card border h-100">
                                        <div class="card-body">
                                            <h6 class="font-weight-semibold mb-3">Pindahkan Arsip</h6>
                                            <p class="text-sm mb-3">Memindahkan arsip ke lokasi penyimpanan lain</p>
                                            <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#transferModal">
                                                <i class="fas fa-exchange-alt me-2"></i> Pindahkan
                                            </button>
                                        </div>
                                    </div>
                                </div>
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

                                <div class="form-group mb-3">
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

                                <div class="form-group mb-3">
                                    <label for="destruction_method" class="form-control-label text-sm">Metode Pemusnahan <span class="text-danger">*</span></label>
                                    <select class="form-select @error('destruction_method') is-invalid @enderror" id="destruction_method" name="destruction_method" required>
                                        <option value="">Pilih Metode Pemusnahan</option>
                                        <option value="shredding" {{ old('destruction_method') == 'shredding' ? 'selected' : '' }}>Penghancuran Fisik (Shredding)</option>
                                        <option value="burning" {{ old('destruction_method') == 'burning' ? 'selected' : '' }}>Pembakaran</option>
                                        <option value="digital_deletion" {{ old('destruction_method') == 'digital_deletion' ? 'selected' : '' }}>Penghapusan Digital</option>
                                        <option value="chemical_treatment" {{ old('destruction_method') == 'chemical_treatment' ? 'selected' : '' }}>Perlakuan Kimia</option>
                                        <option value="other" {{ old('destruction_method') == 'other' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    @error('destruction_method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="destruction_location" class="form-control-label text-sm">Lokasi Pemusnahan</label>
                                    <input type="text" class="form-control @error('destruction_location') is-invalid @enderror" id="destruction_location" name="destruction_location" value="{{ old('destruction_location') }}" placeholder="Contoh: Ruang Pemusnahan, Gedung A">
                                    @error('destruction_location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="destruction_witnesses" class="form-control-label text-sm">Saksi Pemusnahan</label>
                                    <input type="text" class="form-control @error('destruction_witnesses') is-invalid @enderror" id="destruction_witnesses" name="destruction_witnesses" value="{{ old('destruction_witnesses') }}" placeholder="Nama saksi yang menyaksikan proses pemusnahan">
                                    @error('destruction_witnesses')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin memusnahkan arsip ini? Tindakan ini tidak dapat dibatalkan.')">
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
                                <h5 class="modal-title" id="transferModalLabel">Pindahkan Arsip</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group mb-3">
                                    <label for="transfer_location" class="form-control-label text-sm">Lokasi Pemindahan <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="transfer_location" name="transfer_location" required placeholder="Contoh: Gudang Pusat">
                                </div>
                                <div class="form-group">
                                    <label for="transfer_notes" class="form-control-label text-sm">Catatan Pemindahan</label>
                                    <textarea class="form-control" id="transfer_notes" name="transfer_notes" rows="3" placeholder="Tambahkan catatan tentang pemindahan ini"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Pindahkan</button>
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
                                <button type="submit" class="btn btn-success" onclick="return confirm('Yakin ingin memulihkan arsip dengan masa retensi yang dipilih?')">
                                    <i class="fas fa-undo me-2"></i>
                                    Pulihkan Arsip
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
            const recoveryYearsSelect = document.getElementById('recovery_years');
            const retentionPreview = document.getElementById('retentionPreview');
            const retentionDate = document.getElementById('retentionDate');

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

            // Debug untuk form destroy
            const destroyForm = document.querySelector('#destroyModal form');
            if (destroyForm) {
                destroyForm.addEventListener('submit', function(e) {
                    console.log('Form destroy submit triggered');
                    console.log('Form action:', this.action);
                    console.log('Form method:', this.method);

                    // Validasi form
                    const notes = document.getElementById('destruction_notes').value;
                    const method = document.getElementById('destruction_method').value;

                    console.log('Destruction notes:', notes);
                    console.log('Destruction method:', method);

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

                    // Jika sampai sini, form akan disubmit
                    console.log('Form validation passed, submitting...');
                });
            }
        });
    </script>
</x-app-layout>
