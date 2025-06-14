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
                            <form action="{{ route('jre.update', $jre->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="form-group mb-4">
                                    <label for="status" class="form-control-label text-sm">Status <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                        <option value="inactive" {{ $jre->status == 'inactive' ? 'selected' : '' }}>Inaktif</option>
                                        <option value="destroyed" {{ $jre->status == 'destroyed' ? 'selected' : '' }}>Dimusnahkan</option>
                                        <option value="transferred" {{ $jre->status == 'transferred' ? 'selected' : '' }}>Dipindahkan</option>
                                        <option value="recovered" {{ $jre->status == 'recovered' ? 'selected' : '' }}>Dipulihkan</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-group mb-4">
                                    <label for="notes" class="form-control-label text-sm">Catatan</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="4">{{ old('notes', $jre->notes) }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="d-flex justify-content-end mt-4">
                                    <a href="{{ route('jre.index') }}" class="btn btn-light me-3">Batal</a>
                                    <button type="submit" class="btn btn-warning">
                                        <span class="btn-inner--icon">
                                            <i class="fas fa-save me-2"></i>
                                        </span>
                                        Update JRE
                                    </button>
                                </div>
                            </form>
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
                                            <form action="{{ route('jre.recover', $jre->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-success w-100" onclick="return confirm('Yakin ingin memulihkan arsip ini?')">
                                                    <i class="fas fa-undo me-2"></i> Pulihkan
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Destroy Archive -->
                                <div class="col-md-4 mb-3">
                                    <div class="card border h-100">
                                        <div class="card-body">
                                            <h6 class="font-weight-semibold mb-3">Musnahkan Arsip</h6>
                                            <p class="text-sm mb-3">Menandai arsip sebagai dimusnahkan secara permanen</p>
                                            <form action="{{ route('jre.destroy-archive', $jre->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Yakin ingin memusnahkan arsip ini? Tindakan ini tidak dapat dibatalkan.')">
                                                    <i class="fas fa-trash-alt me-2"></i> Musnahkan
                                                </button>
                                            </form>
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
                        </div>
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
            
            <x-app.footer />
        </div>
    </main>
</x-app-layout>