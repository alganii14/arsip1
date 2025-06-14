<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-12">
                    <div class="card card-background card-background-after-none align-items-start mt-4 mb-5">
                        <div class="full-background" style="background-image: url('../assets/img/header-blue-purple.jpg')"></div>
                        <div class="card-body text-start p-4 w-100">
                            <h3 class="text-white mb-2">Tambah Arsip ke JRE</h3>
                            <p class="mb-4 font-weight-semibold text-white">
                                Pindahkan arsip ke Jadwal Retensi Elektronik (JRE)
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
                                    <h6 class="font-weight-semibold text-lg mb-0">Form Tambah JRE</h6>
                                    <p class="text-sm mb-sm-0">Pilih arsip yang akan dipindahkan ke JRE</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(count($arsips) == 0)
                                <div class="alert alert-info" role="alert">
                                    <span class="alert-icon"><i class="fas fa-info-circle"></i></span>
                                    <span class="alert-text">Tidak ada arsip yang tersedia untuk dipindahkan ke JRE.</span>
                                </div>
                            @else
                                <form action="{{ route('jre.store') }}" method="POST">
                                    @csrf
                                    
                                    <div class="form-group mb-4">
                                        <label for="arsip_id" class="form-control-label text-sm">Pilih Arsip <span class="text-danger">*</span></label>
                                        <select class="form-select @error('arsip_id') is-invalid @enderror" id="arsip_id" name="arsip_id" required>
                                            <option value="" selected disabled>Pilih Arsip</option>
                                            @foreach($arsips as $arsip)
                                                <option value="{{ $arsip->id }}">
                                                    {{ $arsip->kode }} - {{ $arsip->nama_dokumen }} 
                                                    ({{ $arsip->tanggal_arsip->format('d/m/Y') }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('arsip_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group mb-4">
                                        <label for="notes" class="form-control-label text-sm">Catatan</label>
                                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="4">{{ old('notes') }}</textarea>
                                        @error('notes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="d-flex justify-content-end mt-4">
                                        <a href="{{ route('jre.index') }}" class="btn btn-light me-3">Batal</a>
                                        <button type="submit" class="btn btn-dark">
                                            <span class="btn-inner--icon">
                                                <i class="fas fa-archive me-2"></i>
                                            </span>
                                            Pindahkan ke JRE
                                        </button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <x-app.footer />
        </div>
    </main>
</x-app-layout>