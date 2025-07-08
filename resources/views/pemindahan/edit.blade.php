<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-12">
                    <div class="card card-background card-background-after-none align-items-start mt-4 mb-5">
                        <div class="full-background" style="background-image: url('../assets/img/header-blue-purple.jpg')"></div>
                        <div class="card-body text-start p-4 w-100">
                            <h3 class="text-white mb-2">Edit Pemindahan Arsip</h3>
                            <p class="mb-4 font-weight-semibold text-white">
                                Edit permintaan pemindahan arsip
                            </p>
                            <a href="{{ route('pemindahan.show', $pemindahan) }}" class="btn btn-outline-white btn-blur btn-icon d-flex align-items-center mb-0">
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
                                    <h6 class="font-weight-semibold text-lg mb-0">Form Edit Pemindahan Arsip</h6>
                                    <p class="text-sm mb-sm-0">Edit informasi pemindahan arsip di bawah ini</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('pemindahan.update', $pemindahan) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <!-- Data Arsip Section (Read Only) -->
                                    <div class="col-12 mb-4">
                                        <h6 class="font-weight-semibold mb-3">Data Arsip</h6>
                                        <div class="card bg-gray-100 border-0">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-control-label text-sm">Kode Arsip</label>
                                                        <input type="text" class="form-control" readonly value="{{ $pemindahan->arsip->nomor_dokumen }}">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-control-label text-sm">Nama Dokumen</label>
                                                        <input type="text" class="form-control" readonly value="{{ $pemindahan->arsip->nama_dokumen }}">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-control-label text-sm">Kategori</label>
                                                        <input type="text" class="form-control" readonly value="{{ $pemindahan->arsip->kategori }}">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-control-label text-sm">Tanggal Arsip</label>
                                                        <input type="text" class="form-control" readonly value="{{ $pemindahan->arsip->tanggal_dokumen }}">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-control-label text-sm">Dibuat oleh</label>
                                                        <input type="text" class="form-control" readonly value="{{ $pemindahan->arsip->user->name ?? 'N/A' }}">
                                                    </div>
                                                </div>
                                                @if($pemindahan->arsip->keterangan)
                                                <div class="row">
                                                    <div class="col-12 mb-3">
                                                        <label class="form-control-label text-sm">Keterangan Arsip</label>
                                                        <textarea class="form-control" readonly rows="2">{{ $pemindahan->arsip->keterangan }}</textarea>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Data Pemindahan Section (Editable) -->
                                    <div class="col-12 mb-4">
                                        <h6 class="font-weight-semibold mb-3">Data Pemindahan</h6>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="tingkat_perkembangan" class="form-control-label text-sm">Tingkat Perkembangan <span class="text-danger">*</span></label>
                                                <select class="form-select @error('tingkat_perkembangan') is-invalid @enderror" id="tingkat_perkembangan" name="tingkat_perkembangan" required>
                                                    <option value="">Pilih Tingkat Perkembangan</option>
                                                    <option value="asli" {{ (old('tingkat_perkembangan', $pemindahan->tingkat_perkembangan) == 'asli') ? 'selected' : '' }}>Asli</option>
                                                    <option value="copy" {{ (old('tingkat_perkembangan', $pemindahan->tingkat_perkembangan) == 'copy') ? 'selected' : '' }}>Copy</option>
                                                    <option value="asli_dan_copy" {{ (old('tingkat_perkembangan', $pemindahan->tingkat_perkembangan) == 'asli_dan_copy') ? 'selected' : '' }}>Asli dan Copy</option>
                                                </select>
                                                @error('tingkat_perkembangan')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="jumlah_folder" class="form-control-label text-sm">Jumlah Folder <span class="text-danger">*</span></label>
                                                <select class="form-select @error('jumlah_folder') is-invalid @enderror" id="jumlah_folder" name="jumlah_folder" required>
                                                    <option value="">Pilih Jumlah Folder</option>
                                                    @for($i = 1; $i <= 10; $i++)
                                                        <option value="{{ $i }}" {{ (old('jumlah_folder', $pemindahan->jumlah_folder) == $i) ? 'selected' : '' }}>{{ $i }} Folder</option>
                                                    @endfor
                                                </select>
                                                @error('jumlah_folder')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label for="keterangan" class="form-control-label text-sm">Keterangan Pemindahan</label>
                                                <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="4" placeholder="Tambahkan keterangan atau alasan pemindahan...">{{ old('keterangan', $pemindahan->keterangan) }}</textarea>
                                                @error('keterangan')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('pemindahan.show', $pemindahan) }}" class="btn btn-light me-2">Batal</a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>
                                        Update Pemindahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <x-app.footer />
        </div>
    </main>
</x-app-layout>
