<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-12">
                    <div class="card card-background card-background-after-none align-items-start mt-4 mb-5">
                        <div class="full-background" style="background-image: url('../assets/img/header-blue-purple.jpg')"></div>
                        <div class="card-body text-start p-4 w-100">
                            <h3 class="text-white mb-2">Edit Peminjaman Arsip</h3>
                            <p class="mb-4 font-weight-semibold text-white">
                                Perbarui informasi peminjaman arsip
                            </p>
                            <a href="{{ route('peminjaman.index') }}" class="btn btn-outline-white btn-blur btn-icon d-flex align-items-center mb-0">
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

            <!-- Alert Messages -->
            @if(session('error'))
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <span class="alert-icon"><i class="fas fa-exclamation-circle"></i></span>
                        <span class="alert-text">{{ session('error') }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card border shadow-xs mb-4">
                        <div class="card-header border-bottom pb-0">
                            <div class="d-sm-flex align-items-center mb-3">
                                <div>
                                    <h6 class="font-weight-semibold text-lg mb-0">Form Edit Peminjaman</h6>
                                    <p class="text-sm mb-sm-0">Edit informasi peminjaman arsip</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('peminjaman.update', $peminjaman->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label for="arsip_id" class="form-control-label text-sm">Arsip <span class="text-danger">*</span></label>
                                            <select class="form-select @error('arsip_id') is-invalid @enderror" id="arsip_id" name="arsip_id" required>
                                                <option value="" disabled>Pilih Arsip</option>
                                                @foreach($arsips as $arsip)
                                                <option value="{{ $arsip->id }}" {{ old('arsip_id', $peminjaman->arsip_id) == $arsip->id ? 'selected' : '' }}>
                                                    {{ $arsip->kode }} - {{ $arsip->nama_dokumen }} ({{ $arsip->rak ?: 'Rak tidak diketahui' }})
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('arsip_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label for="peminjam" class="form-control-label text-sm">Nama Peminjam <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('peminjam') is-invalid @enderror" id="peminjam" name="peminjam" value="{{ old('peminjam', $peminjaman->peminjam) }}" required>
                                            @error('peminjam')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-4">
                                            <label for="jabatan" class="form-control-label text-sm">Jabatan</label>
                                            <input type="text" class="form-control @error('jabatan') is-invalid @enderror" id="jabatan" name="jabatan" value="{{ old('jabatan', $peminjaman->jabatan) }}">
                                            @error('jabatan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group mb-4">
                                            <label for="departemen" class="form-control-label text-sm">Departemen</label>
                                            <input type="text" class="form-control @error('departemen') is-invalid @enderror" id="departemen" name="departemen" value="{{ old('departemen', $peminjaman->departemen) }}">
                                            @error('departemen')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group mb-4">
                                            <label for="kontak" class="form-control-label text-sm">Kontak <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('kontak') is-invalid @enderror" id="kontak" name="kontak" value="{{ old('kontak', $peminjaman->kontak) }}" required placeholder="No. HP/Email">
                                            @error('kontak')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-4">
                                            <label for="tanggal_pinjam" class="form-control-label text-sm">Tanggal Pinjam <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @error('tanggal_pinjam') is-invalid @enderror" id="tanggal_pinjam" name="tanggal_pinjam" value="{{ old('tanggal_pinjam', $peminjaman->tanggal_pinjam->format('Y-m-d')) }}" required>
                                            @error('tanggal_pinjam')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group mb-4">
                                            <label for="batas_waktu" class="form-control-label text-sm">Batas Waktu Pengembalian <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @error('batas_waktu') is-invalid @enderror" id="batas_waktu" name="batas_waktu" value="{{ old('batas_waktu', $peminjaman->batas_waktu->format('Y-m-d')) }}" required>
                                            @error('batas_waktu')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group mb-4">
                                            <label for="status" class="form-control-label text-sm">Status <span class="text-danger">*</span></label>
                                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                                <option value="pending" {{ old('status', $peminjaman->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="dipinjam" {{ old('status', $peminjaman->status) == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                                <option value="dikembalikan" {{ old('status', $peminjaman->status) == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                                                <option value="terlambat" {{ old('status', $peminjaman->status) == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-4">
                                            <label for="tujuan_peminjaman" class="form-control-label text-sm">Tujuan Peminjaman</label>
                                            <textarea class="form-control @error('tujuan_peminjaman') is-invalid @enderror" id="tujuan_peminjaman" name="tujuan_peminjaman" rows="2">{{ old('tujuan_peminjaman', $peminjaman->tujuan_peminjaman) }}</textarea>
                                            @error('tujuan_peminjaman')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="catatan" class="form-control-label text-sm">Catatan</label>
                                    <textarea class="form-control @error('catatan') is-invalid @enderror" id="catatan" name="catatan" rows="3">{{ old('catatan', $peminjaman->catatan) }}</textarea>
                                    @error('catatan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-end mt-4">
                                    <a href="{{ route('peminjaman.index') }}" class="btn btn-light me-3">Batal</a>
                                    <button type="submit" class="btn btn-warning">
                                        <span class="btn-inner--icon">
                                            <i class="fas fa-save me-2"></i>
                                        </span>
                                        Update Peminjaman
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
