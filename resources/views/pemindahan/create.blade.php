<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-12">
                    <div class="card card-background card-background-after-none align-items-start mt-4 mb-5">
                        <div class="full-background" style="background-image: url('../assets/img/header-blue-purple.jpg')"></div>
                        <div class="card-body text-start p-4 w-100">
                            <h3 class="text-white mb-2">Tambah Pemindahan Arsip</h3>
                            <p class="mb-4 font-weight-semibold text-white">
                                Buat permintaan pemindahan arsip dengan tingkat perkembangan
                            </p>
                            <a href="{{ route('pemindahan.index') }}" class="btn btn-outline-white btn-blur btn-icon d-flex align-items-center mb-0">
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
                                    <h6 class="font-weight-semibold text-lg mb-0">Form Pemindahan Arsip</h6>
                                    <p class="text-sm mb-sm-0">Isi informasi pemindahan arsip di bawah ini</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('pemindahan.store') }}" method="POST">
                                @csrf

                                <div class="row">
                                    <!-- Data Arsip Section -->
                                    <div class="col-12 mb-4">
                                        <h6 class="font-weight-semibold mb-3">Data Arsip</h6>
                                        <div class="card bg-gray-100 border-0">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-control-label text-sm">Pilih Arsip <span class="text-danger">*</span></label>
                                                        <select class="form-select @error('arsip_id') is-invalid @enderror" id="arsip_select" name="arsip_id" required>
                                                            <option value="">Pilih Arsip</option>
                                                            @foreach(\App\Models\Arsip::whereDoesntHave('pemindahan', function($query) {
                                                                $query->whereIn('status', ['approved', 'completed']);
                                                            })->get() as $arsipItem)
                                                                <option value="{{ $arsipItem->id }}" {{ (old('arsip_id') == $arsipItem->id || (isset($arsip) && $arsip->id == $arsipItem->id)) ? 'selected' : '' }}>
                                                                    {{ $arsipItem->nomor_dokumen }} - {{ $arsipItem->nama_dokumen }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('arsip_id')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-control-label text-sm">Kode Arsip</label>
                                                        <input type="text" class="form-control" id="kode_arsip" readonly placeholder="Akan terisi otomatis" value="{{ isset($arsip) ? $arsip->nomor_dokumen : '' }}">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-control-label text-sm">Nama Dokumen</label>
                                                        <input type="text" class="form-control" id="nama_dokumen" readonly placeholder="Akan terisi otomatis" value="{{ isset($arsip) ? $arsip->nama_dokumen : '' }}">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-control-label text-sm">Kategori</label>
                                                        <input type="text" class="form-control" id="kategori" readonly placeholder="Akan terisi otomatis" value="{{ isset($arsip) ? $arsip->kategori : '' }}">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-control-label text-sm">Dibuat oleh</label>
                                                        <input type="text" class="form-control" id="dibuat_oleh" readonly placeholder="Akan terisi otomatis" value="{{ isset($arsip) ? ($arsip->user->name ?? 'N/A') : '' }}">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-control-label text-sm">Tanggal Arsip</label>
                                                        <input type="text" class="form-control" id="tanggal_arsip" readonly placeholder="Akan terisi otomatis" value="{{ isset($arsip) ? (\Carbon\Carbon::parse($arsip->tanggal_dokumen)->format('d M Y')) : '' }}">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12 mb-3">
                                                        <label class="form-control-label text-sm">Keterangan Arsip</label>
                                                        <textarea class="form-control" id="keterangan_arsip" readonly rows="2" placeholder="Akan terisi otomatis">{{ isset($arsip) ? $arsip->keterangan : '' }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Data Pemindahan Section -->
                                    <div class="col-12 mb-4">
                                        <h6 class="font-weight-semibold mb-3">Data Pemindahan</h6>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="tingkat_perkembangan" class="form-control-label text-sm">Tingkat Perkembangan <span class="text-danger">*</span></label>
                                                <select class="form-select @error('tingkat_perkembangan') is-invalid @enderror" id="tingkat_perkembangan" name="tingkat_perkembangan" required>
                                                    <option value="">Pilih Tingkat Perkembangan</option>
                                                    <option value="asli" {{ old('tingkat_perkembangan') == 'asli' ? 'selected' : '' }}>Asli</option>
                                                    <option value="copy" {{ old('tingkat_perkembangan') == 'copy' ? 'selected' : '' }}>Copy</option>
                                                    <option value="asli_dan_copy" {{ old('tingkat_perkembangan') == 'asli_dan_copy' ? 'selected' : '' }}>Asli dan Copy</option>
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
                                                        <option value="{{ $i }}" {{ old('jumlah_folder') == $i ? 'selected' : '' }}>{{ $i }} Folder</option>
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
                                                <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="4" placeholder="Tambahkan keterangan atau alasan pemindahan...">{{ old('keterangan') }}</textarea>
                                                @error('keterangan')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('pemindahan.index') }}" class="btn btn-light me-2">Batal</a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>
                                        Simpan Pemindahan
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const arsipSelect = document.getElementById('arsip_select');

            console.log('Page loaded');
            console.log('Selected arsip value:', arsipSelect.value);

            // Auto-load data if arsip is pre-selected
            if (arsipSelect.value) {
                console.log('Auto-loading data for arsip ID:', arsipSelect.value);
                loadArsipData(arsipSelect.value);
            }

            arsipSelect.addEventListener('change', function() {
                const arsipId = this.value;

                if (arsipId) {
                    loadArsipData(arsipId);
                } else {
                    clearArsipData();
                }
            });

            function loadArsipData(arsipId) {
                console.log('Loading arsip data for ID:', arsipId);

                // Reset fields first
                clearArsipData();

                fetch(`{{ route('pemindahan.get-arsip-data') }}?arsip_id=${arsipId}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    console.log('Response headers:', response.headers.get('content-type'));

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error('Response bukan JSON yang valid');
                    }

                    return response.json();
                })
                .then(data => {
                    console.log('Received data:', data);

                    if (data.error) {
                        alert(data.error);
                        arsipSelect.value = ''; // Reset the select
                        return;
                    }

                    // Fill the form fields
                    document.getElementById('kode_arsip').value = data.kode_arsip || '';
                    document.getElementById('nama_dokumen').value = data.nama_dokumen || '';
                    document.getElementById('kategori').value = data.kategori || '';
                    document.getElementById('dibuat_oleh').value = data.dibuat_oleh || '';

                    // Format tanggal jika ada
                    if (data.tanggal_arsip) {
                        try {
                            const date = new Date(data.tanggal_arsip);
                            if (!isNaN(date.getTime())) {
                                const formattedDate = date.toLocaleDateString('id-ID', {
                                    day: '2-digit',
                                    month: 'short',
                                    year: 'numeric'
                                });
                                document.getElementById('tanggal_arsip').value = formattedDate;
                            } else {
                                document.getElementById('tanggal_arsip').value = data.tanggal_arsip;
                            }
                        } catch (e) {
                            document.getElementById('tanggal_arsip').value = data.tanggal_arsip;
                        }
                    }

                    document.getElementById('keterangan_arsip').value = data.keterangan || '';

                    console.log('Data berhasil dimuat');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memuat data arsip: ' + error.message);
                    clearArsipData();
                });
            }

            function clearArsipData() {
                document.getElementById('kode_arsip').value = '';
                document.getElementById('nama_dokumen').value = '';
                document.getElementById('kategori').value = '';
                document.getElementById('dibuat_oleh').value = '';
                document.getElementById('tanggal_arsip').value = '';
                document.getElementById('keterangan_arsip').value = '';
            }
        });
    </script>
</x-app-layout>
