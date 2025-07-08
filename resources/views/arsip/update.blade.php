<x-app-layout>
    <div class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <!-- Navbar -->
        <x-app.navbar />
        <!-- End Navbar -->

        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-12">
                    <div class="card card-background card-background-after-none align-items-start mt-4 mb-5">
                        <div class="full-background" style="background-image: url('{{ asset('assets/img/header-blue-purple.jpg') }}')"></div>
                        <div class="card-body text-start p-4 w-100">
                            <h3 class="text-white mb-2">Edit Arsip</h3>
                            <p class="mb-4 font-weight-semibold text-white">
                                Perbarui informasi arsip dokumen
                            </p>
                            <a href="{{ route('arsip.index') }}" class="btn btn-outline-white btn-blur btn-icon d-flex align-items-center mb-0">
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
                                    <h6 class="font-weight-semibold text-lg mb-0">Form Edit Arsip</h6>
                                    <p class="text-sm mb-sm-0">Edit informasi arsip dengan kode: <span class="font-weight-bold">{{ $arsip->kode }}</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Custom style for the classification dropdown -->
                            <style>
                                .form-select option {
                                    padding: 2px 5px;
                                }
                                select[name="kategori"] {
                                    max-height: 300px;
                                }
                                select[name="kategori"] option:nth-child(odd) {
                                    background-color: rgba(0,0,0,0.02);
                                }
                                select[name="kategori"] optgroup + optgroup {
                                    margin-top: 10px;
                                    border-top: 1px solid #ddd;
                                    padding-top: 5px;
                                }
                            </style>

                            <form action="{{ route('arsip.update', $arsip->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label for="kode" class="form-control-label text-sm">Kode <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('kode') is-invalid @enderror" id="kode" name="kode" value="{{ old('kode', $arsip->kode) }}" required>
                                            @error('kode')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label for="nama_dokumen" class="form-control-label text-sm">Nama Dokumen <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('nama_dokumen') is-invalid @enderror" id="nama_dokumen" name="nama_dokumen" value="{{ old('nama_dokumen', $arsip->nama_dokumen) }}" required>
                                            @error('nama_dokumen')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label for="kategori" class="form-control-label text-sm">Kategori <span class="text-danger">*</span></label>
                                            <select class="form-select @error('kategori') is-invalid @enderror" id="kategori" name="kategori" required>
                                                <option value="" disabled>Pilih Kategori</option>
                                                <!-- Kearsipan - AR -->
                                                <optgroup label="Kearsipan (AR)">
                                                    <!-- 01 - Kebijakan -->
                                                    <option value="AR.01" {{ old('kategori', $arsip->kategori) == 'AR.01' ? 'selected' : '' }}>01 - Kebijakan</option>
                                                    <option value="AR.01.01" {{ old('kategori', $arsip->kategori) == 'AR.01.01' ? 'selected' : '' }}>01.01 - Peraturan Daerah</option>
                                                    <option value="AR.01.01.01" {{ old('kategori', $arsip->kategori) == 'AR.01.01.01' ? 'selected' : '' }}>01.01.01 - Pengkajian dan Pengusulan</option>
                                                    <option value="AR.01.01.02" {{ old('kategori', $arsip->kategori) == 'AR.01.01.02' ? 'selected' : '' }}>01.01.02 - Penyusunan Rancangan Peraturan Daerah</option>
                                                    <option value="AR.01.01.03" {{ old('kategori', $arsip->kategori) == 'AR.01.01.03' ? 'selected' : '' }}>01.01.03 - Pembahasan Rancangan Peraturan Daerah dan Persetujuan Rancangan Peraturan Daerah</option>
                                                    <option value="AR.01.01.04" {{ old('kategori', $arsip->kategori) == 'AR.01.01.04' ? 'selected' : '' }}>01.01.04 - Penetapan Peraturan Daerah</option>
                                                    <option value="AR.01.01.05" {{ old('kategori', $arsip->kategori) == 'AR.01.01.05' ? 'selected' : '' }}>01.01.05 - Sosialisasi Peraturan Daerah</option>

                                                    <option value="AR.01.02" {{ old('kategori', $arsip->kategori) == 'AR.01.02' ? 'selected' : '' }}>01.02 - Peraturan Wali Kota</option>
                                                    <option value="AR.01.02.01" {{ old('kategori', $arsip->kategori) == 'AR.01.02.01' ? 'selected' : '' }}>01.02.01 - Pengkajian dan Pembahasan Rancangan Peraturan Wali Kota</option>
                                                    <option value="AR.01.02.02" {{ old('kategori', $arsip->kategori) == 'AR.01.02.02' ? 'selected' : '' }}>01.02.02 - Pengusulan dan Penetapan Peraturan Wali Kota</option>
                                                    <option value="AR.01.02.03" {{ old('kategori', $arsip->kategori) == 'AR.01.02.03' ? 'selected' : '' }}>01.02.03 - Sosialisasi Peraturan Wali Kota</option>

                                                    <option value="AR.01.03" {{ old('kategori', $arsip->kategori) == 'AR.01.03' ? 'selected' : '' }}>01.03 - Penetapan Organisasi Kearsipan</option>
                                                    <option value="AR.01.03.01" {{ old('kategori', $arsip->kategori) == 'AR.01.03.01' ? 'selected' : '' }}>01.03.01 - Unit Pengolah</option>
                                                    <option value="AR.01.03.02" {{ old('kategori', $arsip->kategori) == 'AR.01.03.02' ? 'selected' : '' }}>01.03.02 - Unit Kearsipan</option>

                                                    <!-- 02 - Pembinaan Kearsipan -->
                                                    <option value="AR.02" {{ old('kategori', $arsip->kategori) == 'AR.02' ? 'selected' : '' }}>02 - Pembinaan Kearsipan</option>
                                                    <option value="AR.02.01" {{ old('kategori', $arsip->kategori) == 'AR.02.01' ? 'selected' : '' }}>02.01 - Bina Arsiparis</option>
                                                    <option value="AR.02.01.01" {{ old('kategori', $arsip->kategori) == 'AR.02.01.01' ? 'selected' : '' }}>02.01.01 - Formasi Jabatan Arsiparis</option>
                                                    <option value="AR.02.01.02" {{ old('kategori', $arsip->kategori) == 'AR.02.01.02' ? 'selected' : '' }}>02.01.02 - Standar Kompetensi Arsiparis</option>
                                                    <option value="AR.02.01.03" {{ old('kategori', $arsip->kategori) == 'AR.02.01.03' ? 'selected' : '' }}>02.01.03 - Bimbingan Konsultasi Arsiparis</option>
                                                    <option value="AR.02.01.04" {{ old('kategori', $arsip->kategori) == 'AR.02.01.04' ? 'selected' : '' }}>02.01.04 - Penilaian Arsiparis</option>
                                                    <option value="AR.02.01.05" {{ old('kategori', $arsip->kategori) == 'AR.02.01.05' ? 'selected' : '' }}>02.01.05 - Penyelenggaraan Pemilihan Arsiparis Teladan</option>
                                                    <option value="AR.02.01.06" {{ old('kategori', $arsip->kategori) == 'AR.02.01.06' ? 'selected' : '' }}>02.01.06 - Berkas Penetapan Arsiparis Teladan</option>
                                                    <option value="AR.02.01.07" {{ old('kategori', $arsip->kategori) == 'AR.02.01.07' ? 'selected' : '' }}>02.01.07 - Data Base Arsiparis</option>

                                                    <option value="AR.02.02" {{ old('kategori', $arsip->kategori) == 'AR.02.02' ? 'selected' : '' }}>02.02 - Bimbingan dan Konsultasi</option>
                                                    <option value="AR.02.02.01" {{ old('kategori', $arsip->kategori) == 'AR.02.02.01' ? 'selected' : '' }}>02.02.01 - Penerapan Sistem Kearsipan</option>
                                                    <option value="AR.02.02.02" {{ old('kategori', $arsip->kategori) == 'AR.02.02.02' ? 'selected' : '' }}>02.02.02 - Penggunaan Sarana dan Prasarana Kearsipan</option>
                                                    <option value="AR.02.02.03" {{ old('kategori', $arsip->kategori) == 'AR.02.02.03' ? 'selected' : '' }}>02.02.03 - Unit Kearsipan</option>
                                                    <option value="AR.02.02.04" {{ old('kategori', $arsip->kategori) == 'AR.02.02.04' ? 'selected' : '' }}>02.02.04 - Sumber Daya Manusia</option>

                                                    <option value="AR.02.03" {{ old('kategori', $arsip->kategori) == 'AR.02.03' ? 'selected' : '' }}>02.03 - Supervisi dan Evaluasi</option>
                                                    <option value="AR.02.03.01" {{ old('kategori', $arsip->kategori) == 'AR.02.03.01' ? 'selected' : '' }}>02.03.01 - Perencanaan</option>
                                                    <option value="AR.02.03.02" {{ old('kategori', $arsip->kategori) == 'AR.02.03.02' ? 'selected' : '' }}>02.03.02 - Pelaksanaan</option>
                                                    <option value="AR.02.03.03" {{ old('kategori', $arsip->kategori) == 'AR.02.03.03' ? 'selected' : '' }}>02.03.03 - Laporan Hasil Supervisi dan Evaluasi</option>

                                                    <option value="AR.02.04" {{ old('kategori', $arsip->kategori) == 'AR.02.04' ? 'selected' : '' }}>02.04 - Data Base Bimbingan, Supervisi dan Evaluasi</option>

                                                    <option value="AR.02.05" {{ old('kategori', $arsip->kategori) == 'AR.02.05' ? 'selected' : '' }}>02.05 - Fasilitasi Kearsipan</option>
                                                    <option value="AR.02.05.01" {{ old('kategori', $arsip->kategori) == 'AR.02.05.01' ? 'selected' : '' }}>02.05.01 - Sumber Daya Manusia Kearsipan</option>
                                                    <option value="AR.02.05.02" {{ old('kategori', $arsip->kategori) == 'AR.02.05.02' ? 'selected' : '' }}>02.05.02 - Prasarana dan Sarana</option>

                                                    <option value="AR.02.06" {{ old('kategori', $arsip->kategori) == 'AR.02.06' ? 'selected' : '' }}>02.06 - Lembaga/Unit Kearsipan Teladan</option>
                                                    <option value="AR.02.06.01" {{ old('kategori', $arsip->kategori) == 'AR.02.06.01' ? 'selected' : '' }}>02.06.01 - Penyelenggaraan</option>
                                                    <option value="AR.02.06.02" {{ old('kategori', $arsip->kategori) == 'AR.02.06.02' ? 'selected' : '' }}>02.06.02 - Berkas Penetapan Lembaga/Unit Kearsipan Teladan</option>

                                                    <!-- 03 - Pengelolaan Arsip Dinamis -->
                                                    <option value="AR.03" {{ old('kategori', $arsip->kategori) == 'AR.03' ? 'selected' : '' }}>03 - Pengelolaan Arsip Dinamis</option>
                                                    <option value="AR.03.01" {{ old('kategori', $arsip->kategori) == 'AR.03.01' ? 'selected' : '' }}>03.01 - Penciptaan</option>
                                                    <option value="AR.03.01.01" {{ old('kategori', $arsip->kategori) == 'AR.03.01.01' ? 'selected' : '' }}>03.01.01 - Pencatatan (Buku Agenda, Kartu Kendali dan Lembar Pengantar/Ekspedisi)</option>
                                                    <option value="AR.03.01.02" {{ old('kategori', $arsip->kategori) == 'AR.03.01.02' ? 'selected' : '' }}>03.01.02 - Pendistribusian</option>

                                                    <option value="AR.03.02" {{ old('kategori', $arsip->kategori) == 'AR.03.02' ? 'selected' : '' }}>03.02 - Penggunaan</option>
                                                    <option value="AR.03.02.01" {{ old('kategori', $arsip->kategori) == 'AR.03.02.01' ? 'selected' : '' }}>03.02.01 - Pengklasifikasian Pengamanan dan Akses Arsip Dinamis</option>
                                                    <option value="AR.03.02.02" {{ old('kategori', $arsip->kategori) == 'AR.03.02.02' ? 'selected' : '' }}>03.02.02 - Peminjaman</option>

                                                    <option value="AR.03.03" {{ old('kategori', $arsip->kategori) == 'AR.03.03' ? 'selected' : '' }}>03.03 - Pemeliharaan</option>
                                                    <option value="AR.03.03.01" {{ old('kategori', $arsip->kategori) == 'AR.03.03.01' ? 'selected' : '' }}>03.03.01 - Pemberkasan: Daftar Arsip Aktif (Daftar Berkas dan Isi Berkas)</option>
                                                    <option value="AR.03.03.02" {{ old('kategori', $arsip->kategori) == 'AR.03.03.02' ? 'selected' : '' }}>03.03.02 - Penataan Arsip Inaktif: Pengaturan Fisik, Pengolahan Informasi Arsip, Penyusunan Daftar Arsip Inaktif</option>

                                                    <option value="AR.03.04" {{ old('kategori', $arsip->kategori) == 'AR.03.04' ? 'selected' : '' }}>03.04 - Penyimpanan</option>
                                                    <option value="AR.03.04.01" {{ old('kategori', $arsip->kategori) == 'AR.03.04.01' ? 'selected' : '' }}>03.04.01 - Skema Penyimpanan Arsip Aktif dan Inaktif</option>
                                                    <option value="AR.03.04.02" {{ old('kategori', $arsip->kategori) == 'AR.03.04.02' ? 'selected' : '' }}>03.04.02 - Pengamanan</option>

                                                    <option value="AR.03.05" {{ old('kategori', $arsip->kategori) == 'AR.03.05' ? 'selected' : '' }}>03.05 - Alih Media</option>
                                                    <option value="AR.03.05.01" {{ old('kategori', $arsip->kategori) == 'AR.03.05.01' ? 'selected' : '' }}>03.05.01 - Kebijakan Alih Media</option>
                                                    <option value="AR.03.05.02" {{ old('kategori', $arsip->kategori) == 'AR.03.05.02' ? 'selected' : '' }}>03.05.02 - Autentikasi</option>
                                                    <option value="AR.03.05.03" {{ old('kategori', $arsip->kategori) == 'AR.03.05.03' ? 'selected' : '' }}>03.05.03 - Berita Acara</option>
                                                    <option value="AR.03.05.04" {{ old('kategori', $arsip->kategori) == 'AR.03.05.04' ? 'selected' : '' }}>03.05.04 - Daftar Arsip Alih Media</option>

                                                    <option value="AR.03.06" {{ old('kategori', $arsip->kategori) == 'AR.03.06' ? 'selected' : '' }}>03.06 - Program Arsip Vital</option>
                                                    <option value="AR.03.06.01" {{ old('kategori', $arsip->kategori) == 'AR.03.06.01' ? 'selected' : '' }}>03.06.01 - Identifikasi</option>
                                                    <option value="AR.03.06.02" {{ old('kategori', $arsip->kategori) == 'AR.03.06.02' ? 'selected' : '' }}>03.06.02 - Pelindungan dan Pengamanan</option>
                                                    <option value="AR.03.06.03" {{ old('kategori', $arsip->kategori) == 'AR.03.06.03' ? 'selected' : '' }}>03.06.03 - Penyelamatan dan Pemulihan</option>

                                                    <option value="AR.03.07" {{ old('kategori', $arsip->kategori) == 'AR.03.07' ? 'selected' : '' }}>03.07 - Autentikasi Arsip Dinamis</option>
                                                    <option value="AR.03.07.01" {{ old('kategori', $arsip->kategori) == 'AR.03.07.01' ? 'selected' : '' }}>03.07.01 - Pembuktian Autentisitas</option>
                                                    <option value="AR.03.07.02" {{ old('kategori', $arsip->kategori) == 'AR.03.07.02' ? 'selected' : '' }}>03.07.02 - Pendapat Tenaga Ahli</option>
                                                    <option value="AR.03.07.03" {{ old('kategori', $arsip->kategori) == 'AR.03.07.03' ? 'selected' : '' }}>03.07.03 - Pengujian</option>
                                                    <option value="AR.03.07.04" {{ old('kategori', $arsip->kategori) == 'AR.03.07.04' ? 'selected' : '' }}>03.07.04 - Penetapan Autentisitas Arsip Statis/Surat Pernyataan Pencipta Arsip</option>

                                                    <option value="AR.03.08" {{ old('kategori', $arsip->kategori) == 'AR.03.08' ? 'selected' : '' }}>03.08 - Penyusutan</option>
                                                    <option value="AR.03.08.01" {{ old('kategori', $arsip->kategori) == 'AR.03.08.01' ? 'selected' : '' }}>03.08.01 - Pemindahan Arsip Inaktif (Berita Acara dan Daftar Arsip yang Dipindahkan)</option>
                                                    <option value="AR.03.08.02" {{ old('kategori', $arsip->kategori) == 'AR.03.08.02' ? 'selected' : '' }}>03.08.02 - Pemusnahan Arsip yang Tidak Bernilai Guna</option>
                                                    <option value="AR.03.08.02.01" {{ old('kategori', $arsip->kategori) == 'AR.03.08.02.01' ? 'selected' : '' }}>03.08.02.01 - Panitia Penilai</option>
                                                    <option value="AR.03.08.02.02" {{ old('kategori', $arsip->kategori) == 'AR.03.08.02.02' ? 'selected' : '' }}>03.08.02.02 - Penilaian Panitia Penilai</option>
                                                    <option value="AR.03.08.02.03" {{ old('kategori', $arsip->kategori) == 'AR.03.08.02.03' ? 'selected' : '' }}>03.08.02.03 - Permintaan Persetujuan (Kepala ANRI, Kepala Lembaga Kearsipan)</option>
                                                    <option value="AR.03.08.02.04" {{ old('kategori', $arsip->kategori) == 'AR.03.08.02.04' ? 'selected' : '' }}>03.08.02.04 - Penetapan Arsip yang Dimusnakan</option>
                                                    <option value="AR.03.08.02.05" {{ old('kategori', $arsip->kategori) == 'AR.03.08.02.05' ? 'selected' : '' }}>03.08.02.05 - Berita Acara Pemusnahan Arsip</option>
                                                    <option value="AR.03.08.02.06" {{ old('kategori', $arsip->kategori) == 'AR.03.08.02.06' ? 'selected' : '' }}>03.08.02.06 - Daftar Arsip yang Dimusnakan</option>
                                                    <option value="AR.03.08.03" {{ old('kategori', $arsip->kategori) == 'AR.03.08.03' ? 'selected' : '' }}>03.08.03 - Penyerahan Arsip Statis</option>
                                                    <option value="AR.03.08.03.01" {{ old('kategori', $arsip->kategori) == 'AR.03.08.03.01' ? 'selected' : '' }}>03.08.03.01 - Pembentukan Panitia Penilai</option>
                                                    <option value="AR.03.08.03.02" {{ old('kategori', $arsip->kategori) == 'AR.03.08.03.02' ? 'selected' : '' }}>03.08.03.02 - Notulen Rapat</option>
                                                    <option value="AR.03.08.03.03" {{ old('kategori', $arsip->kategori) == 'AR.03.08.03.03' ? 'selected' : '' }}>03.08.03.03 - Surat Pertimbangan Panitia Penilai</option>
                                                    <option value="AR.03.08.03.04" {{ old('kategori', $arsip->kategori) == 'AR.03.08.03.04' ? 'selected' : '' }}>03.08.03.04 - Surat Persetujuan dari Kepala Lembaga Kearsipan</option>
                                                    <option value="AR.03.08.03.05" {{ old('kategori', $arsip->kategori) == 'AR.03.08.03.05' ? 'selected' : '' }}>03.08.03.05 - Surat Pernyataan Autentik, Terpercaya, Utuh, dan dapat Digunakan dari Pencipta Arsip</option>
                                                    <option value="AR.03.08.03.06" {{ old('kategori', $arsip->kategori) == 'AR.03.08.03.06' ? 'selected' : '' }}>03.08.03.06 - Keputusan Penetapan Penyerahan</option>
                                                    <option value="AR.03.08.03.07" {{ old('kategori', $arsip->kategori) == 'AR.03.08.03.07' ? 'selected' : '' }}>03.08.03.07 - Berita Acara Penyerahan Arsip</option>
                                                    <option value="AR.03.08.03.08" {{ old('kategori', $arsip->kategori) == 'AR.03.08.03.08' ? 'selected' : '' }}>03.08.03.08 - Daftar Arsip yang Diserahkan</option>
                                                    <option value="AR.03.09" {{ old('kategori', $arsip->kategori) == 'AR.03.09' ? 'selected' : '' }}>03.09 - Data Base Pengelolaan Arsip Dinamis</option>
                                                    <option value="AR.03.09.01" {{ old('kategori', $arsip->kategori) == 'AR.03.09.01' ? 'selected' : '' }}>03.09.01 - Data Base Pengelolaan Arsip Aktif</option>
                                                    <option value="AR.03.09.02" {{ old('kategori', $arsip->kategori) == 'AR.03.09.02' ? 'selected' : '' }}>03.09.02 - Data Base Pengelolaan Arsip Inaktif</option>

                                                    <option value="AR.04" {{ old('kategori', $arsip->kategori) == 'AR.04' ? 'selected' : '' }}>04 - Pengelolaan Arsip Statis</option>
                                                    <option value="AR.04.01" {{ old('kategori', $arsip->kategori) == 'AR.04.01' ? 'selected' : '' }}>04.01 - Akuisisi</option>
                                                    <option value="AR.04.01.01" {{ old('kategori', $arsip->kategori) == 'AR.04.01.01' ? 'selected' : '' }}>04.01.01 - Monitoring Fisik dan Daftar</option>
                                                    <option value="AR.04.01.02" {{ old('kategori', $arsip->kategori) == 'AR.04.01.02' ? 'selected' : '' }}>04.01.02 - Verifikasi Terhadap Daftar Arsip</option>
                                                    <option value="AR.04.01.03" {{ old('kategori', $arsip->kategori) == 'AR.04.01.03' ? 'selected' : '' }}>04.01.03 - Menetapkan Status Arsip Statis</option>
                                                    <option value="AR.04.01.04" {{ old('kategori', $arsip->kategori) == 'AR.04.01.04' ? 'selected' : '' }}>04.01.04 - Persetujuan untuk Penyerahan</option>
                                                    <option value="AR.04.01.05" {{ old('kategori', $arsip->kategori) == 'AR.04.01.05' ? 'selected' : '' }}>04.01.05 - Penetapan Arsip yang Diserahkan</option>
                                                    <option value="AR.04.01.06" {{ old('kategori', $arsip->kategori) == 'AR.04.01.06' ? 'selected' : '' }}>04.01.06 - Berita Acara Penyerahan Arsip</option>
                                                    <option value="AR.04.01.07" {{ old('kategori', $arsip->kategori) == 'AR.04.01.07' ? 'selected' : '' }}>04.01.07 - Daftar Arsip yang Diserahkan</option>
                                                    <option value="AR.04.02" {{ old('kategori', $arsip->kategori) == 'AR.04.02' ? 'selected' : '' }}>04.02 - Sejarah Lisan</option>
                                                    <option value="AR.04.02.01" {{ old('kategori', $arsip->kategori) == 'AR.04.02.01' ? 'selected' : '' }}>04.02.01 - Administrasi Pelaksanaan Sejarah Lisan</option>
                                                    <option value="AR.04.02.02" {{ old('kategori', $arsip->kategori) == 'AR.04.02.02' ? 'selected' : '' }}>04.02.02 - Hasil Wawancara Sejarah Lisan</option>
                                                    <option value="AR.04.02.02.01" {{ old('kategori', $arsip->kategori) == 'AR.04.02.02.01' ? 'selected' : '' }}>04.02.02.01 - Berita Acara Wawancara Sejarah Lisan</option>
                                                    <option value="AR.04.02.02.02" {{ old('kategori', $arsip->kategori) == 'AR.04.02.02.02' ? 'selected' : '' }}>04.02.02.02 - Laporan Kegiatan</option>
                                                    <option value="AR.04.02.02.03" {{ old('kategori', $arsip->kategori) == 'AR.04.02.02.03' ? 'selected' : '' }}>04.02.02.03 - Hasil Wawancara (kaset atau CD) dan Transkrip</option>
                                                    <option value="AR.04.03" {{ old('kategori', $arsip->kategori) == 'AR.04.03' ? 'selected' : '' }}>04.03 - Daftar Pencarian Arsip Statis</option>
                                                    <option value="AR.04.03.01" {{ old('kategori', $arsip->kategori) == 'AR.04.03.01' ? 'selected' : '' }}>04.03.01 - Pengumuman</option>
                                                    <option value="AR.04.03.02" {{ old('kategori', $arsip->kategori) == 'AR.04.03.02' ? 'selected' : '' }}>04.03.02 - Akuisisi Daftar Pencarian Arsip Statis</option>
                                                    <option value="AR.04.04" {{ old('kategori', $arsip->kategori) == 'AR.04.04' ? 'selected' : '' }}>04.04 - Penghargaan dan Imbalan</option>
                                                    <option value="AR.04.05" {{ old('kategori', $arsip->kategori) == 'AR.04.05' ? 'selected' : '' }}>04.05 - Pengolahan</option>
                                                    <option value="AR.04.05.01" {{ old('kategori', $arsip->kategori) == 'AR.04.05.01' ? 'selected' : '' }}>04.05.01 - Menata Informasi</option>
                                                    <option value="AR.04.05.02" {{ old('kategori', $arsip->kategori) == 'AR.04.05.02' ? 'selected' : '' }}>04.05.02 - Menata Fisik</option>
                                                    <option value="AR.04.05.03" {{ old('kategori', $arsip->kategori) == 'AR.04.05.03' ? 'selected' : '' }}>04.05.03 - Menyusun Sarana Bantu Temu Balik: Daftar Arsip Statis, Inventaris Arsip Statis dan Petunjuk</option>
                                                    <option value="AR.04.06" {{ old('kategori', $arsip->kategori) == 'AR.04.06' ? 'selected' : '' }}>04.06 - Preservasi Preventif</option>
                                                    <option value="AR.04.06.01" {{ old('kategori', $arsip->kategori) == 'AR.04.06.01' ? 'selected' : '' }}>04.06.01 - Penyimpanan</option>
                                                    <option value="AR.04.06.02" {{ old('kategori', $arsip->kategori) == 'AR.04.06.02' ? 'selected' : '' }}>04.06.02 - Pengendalian Hama Terpadu</option>
                                                    <option value="AR.04.06.03" {{ old('kategori', $arsip->kategori) == 'AR.04.06.03' ? 'selected' : '' }}>04.06.03 - Reproduksi (Alih Media): Berita Acara Alih Media dan Daftar Arsip Alih Media</option>
                                                    <option value="AR.04.06.04" {{ old('kategori', $arsip->kategori) == 'AR.04.06.04' ? 'selected' : '' }}>04.06.04 - Perencanaan dan Penanggulangan Bencana</option>
                                                    <option value="AR.04.07" {{ old('kategori', $arsip->kategori) == 'AR.04.07' ? 'selected' : '' }}>04.07 - Preventif Kuratif</option>
                                                    <option value="AR.04.07.01" {{ old('kategori', $arsip->kategori) == 'AR.04.07.01' ? 'selected' : '' }}>04.07.01 - Perawatan Arsip</option>
                                                    <option value="AR.04.07.02" {{ old('kategori', $arsip->kategori) == 'AR.04.07.02' ? 'selected' : '' }}>04.07.02 - Laporan Hasil Pengujian Preservasi</option>
                                                    <option value="AR.04.08" {{ old('kategori', $arsip->kategori) == 'AR.04.08' ? 'selected' : '' }}>04.08 - Autentikasi Arsip Statis</option>
                                                    <option value="AR.04.08.01" {{ old('kategori', $arsip->kategori) == 'AR.04.08.01' ? 'selected' : '' }}>04.08.01 - Pembuktian Autentisitas</option>
                                                    <option value="AR.04.08.02" {{ old('kategori', $arsip->kategori) == 'AR.04.08.02' ? 'selected' : '' }}>04.08.02 - Pendapat Tenaga Ahli</option>
                                                    <option value="AR.04.08.03" {{ old('kategori', $arsip->kategori) == 'AR.04.08.03' ? 'selected' : '' }}>04.08.03 - Pengujian</option>
                                                    <option value="AR.04.08.04" {{ old('kategori', $arsip->kategori) == 'AR.04.08.04' ? 'selected' : '' }}>04.08.04 - Penetapan Autentisitas Arsip Statis/Surat Pernyataan</option>
                                                    <option value="AR.04.09" {{ old('kategori', $arsip->kategori) == 'AR.04.09' ? 'selected' : '' }}>04.09 - Akses Arsip Statis</option>
                                                    <option value="AR.04.09.01" {{ old('kategori', $arsip->kategori) == 'AR.04.09.01' ? 'selected' : '' }}>04.09.01 - Layanan Arsip Statis</option>
                                                    <option value="AR.04.09.02" {{ old('kategori', $arsip->kategori) == 'AR.04.09.02' ? 'selected' : '' }}>04.09.02 - Administrasi dan Proses Penyusunan Penerbitan Naskah Sumber</option>
                                                    <option value="AR.04.09.03" {{ old('kategori', $arsip->kategori) == 'AR.04.09.03' ? 'selected' : '' }}>04.09.03 - Hasil Naskah Sumber Arsip</option>
                                                    <option value="AR.04.09.04" {{ old('kategori', $arsip->kategori) == 'AR.04.09.04' ? 'selected' : '' }}>04.09.04 - Pameran Arsip</option>

                                                    <option value="AR.05" {{ old('kategori', $arsip->kategori) == 'AR.05' ? 'selected' : '' }}>05 - Jasa Kearsipan</option>
                                                    <option value="AR.05.01" {{ old('kategori', $arsip->kategori) == 'AR.05.01' ? 'selected' : '' }}>05.01 - Konsultasi Kearsipan</option>
                                                    <option value="AR.05.02" {{ old('kategori', $arsip->kategori) == 'AR.05.02' ? 'selected' : '' }}>05.02 - Manual Kearsipan</option>
                                                    <option value="AR.05.03" {{ old('kategori', $arsip->kategori) == 'AR.05.03' ? 'selected' : '' }}>05.03 - Penataan Arsip</option>
                                                    <option value="AR.05.04" {{ old('kategori', $arsip->kategori) == 'AR.05.04' ? 'selected' : '' }}>05.04 - Otomasi Kearsipan</option>
                                                    <option value="AR.05.05" {{ old('kategori', $arsip->kategori) == 'AR.05.05' ? 'selected' : '' }}>05.05 - Penyimpanan Arsip/Dokumen</option>
                                                    <option value="AR.05.06" {{ old('kategori', $arsip->kategori) == 'AR.05.06' ? 'selected' : '' }}>05.06 - Perawatan Arsip/Dokumen</option>
                                                    <option value="AR.05.07" {{ old('kategori', $arsip->kategori) == 'AR.05.07' ? 'selected' : '' }}>05.07 - Data Base Jasa Kearsipan</option>

                                                    <option value="AR.06" {{ old('kategori', $arsip->kategori) == 'AR.06' ? 'selected' : '' }}>06 - Pembinaan dan Pengawasan Kearsipan</option>
                                                    <option value="AR.06.01" {{ old('kategori', $arsip->kategori) == 'AR.06.01' ? 'selected' : '' }}>06.01 - Pembinaan Internal</option>
                                                    <option value="AR.06.01.01" {{ old('kategori', $arsip->kategori) == 'AR.06.01.01' ? 'selected' : '' }}>06.01.01 - Kegiatan Pembinaan terhadap Perangkat Daerah</option>
                                                    <option value="AR.06.01.02" {{ old('kategori', $arsip->kategori) == 'AR.06.01.02' ? 'selected' : '' }}>06.01.02 - Laporan Hasil Pembinaan Perangkat Daerah</option>
                                                    <option value="AR.06.02" {{ old('kategori', $arsip->kategori) == 'AR.06.02' ? 'selected' : '' }}>06.02 - Pembinaan Eksternal</option>
                                                    <option value="AR.06.02.01" {{ old('kategori', $arsip->kategori) == 'AR.06.02.01' ? 'selected' : '' }}>06.02.01 - Kegiatan Pembinaan terhadap BUMD, Orpol, Ormas, Swasta, dan Masyarakat</option>
                                                    <option value="AR.06.02.02" {{ old('kategori', $arsip->kategori) == 'AR.06.02.02' ? 'selected' : '' }}>06.02.02 - Laporan Hasil Pembinaan Eksternal</option>
                                                    <option value="AR.06.03" {{ old('kategori', $arsip->kategori) == 'AR.06.03' ? 'selected' : '' }}>06.03 - Pengawasan Internal</option>
                                                    <option value="AR.06.03.01" {{ old('kategori', $arsip->kategori) == 'AR.06.03.01' ? 'selected' : '' }}>06.03.01 - Kegiatan Pengawasan terhadap Perangkat Daerah</option>
                                                    <option value="AR.06.03.02" {{ old('kategori', $arsip->kategori) == 'AR.06.03.02' ? 'selected' : '' }}>06.03.02 - Laporan Hasil Audit Kearsipan Internal terhadap Perangkat Daerah</option>
                                                    <option value="AR.06.04" {{ old('kategori', $arsip->kategori) == 'AR.06.04' ? 'selected' : '' }}>06.04 - Pengawasan Eksternal</option>
                                                    <option value="AR.06.04.01" {{ old('kategori', $arsip->kategori) == 'AR.06.04.01' ? 'selected' : '' }}>06.04.01 - Kegiatan Pengawasan Kearsipan Eksternal terhadap BUMD, Orpol, Ormas, Swasta, dan Masyarakat</option>
                                                    <option value="AR.06.04.02" {{ old('kategori', $arsip->kategori) == 'AR.06.04.02' ? 'selected' : '' }}>06.04.02 - Laporan Hasil Audit Kearsipan Eksternal</option>
                                                </optgroup>

                                                <!-- Kepegawaian - KP -->
                                                <optgroup label="Kepegawaian (KP)">
                                                    <!-- 01 - Persediaan Pegawai -->
                                                    <option value="KP.01" {{ old('kategori', $arsip->kategori) == 'KP.01' ? 'selected' : '' }}>01 - Persediaan Pegawai</option>

                                                    <!-- 02 - Formasi Pegawai -->
                                                    <option value="KP.02" {{ old('kategori', $arsip->kategori) == 'KP.02' ? 'selected' : '' }}>02 - Formasi Pegawai</option>
                                                    <option value="KP.02.01" {{ old('kategori', $arsip->kategori) == 'KP.02.01' ? 'selected' : '' }}>02.01 - Usulan Unit Kerja</option>
                                                    <option value="KP.02.02" {{ old('kategori', $arsip->kategori) == 'KP.02.02' ? 'selected' : '' }}>02.02 - Usulan Formasi</option>
                                                    <option value="KP.02.03" {{ old('kategori', $arsip->kategori) == 'KP.02.03' ? 'selected' : '' }}>02.03 - Persetujuan/Penetapan Formasi</option>
                                                    <option value="KP.02.04" {{ old('kategori', $arsip->kategori) == 'KP.02.04' ? 'selected' : '' }}>02.04 - Penetapan Formasi Khusus</option>

                                                    <!-- 03 - Pengadaan Formasi -->
                                                    <option value="KP.03" {{ old('kategori', $arsip->kategori) == 'KP.03' ? 'selected' : '' }}>03 - Pengadaan Formasi</option>
                                                    <option value="KP.03.01" {{ old('kategori', $arsip->kategori) == 'KP.03.01' ? 'selected' : '' }}>03.01 - Penerimaan</option>
                                                    <option value="KP.03.02" {{ old('kategori', $arsip->kategori) == 'KP.03.02' ? 'selected' : '' }}>03.02 - Pengangkatan CPNS dan PNS</option>
                                                    <option value="KP.03.03" {{ old('kategori', $arsip->kategori) == 'KP.03.03' ? 'selected' : '' }}>03.03 - Prajabatan</option>

                                                    <!-- 04 - Ujian Kenaikan Pangkat/Jabatan -->
                                                    <option value="KP.04" {{ old('kategori', $arsip->kategori) == 'KP.04' ? 'selected' : '' }}>04 - Ujian Kenaikan Pangkat/Jabatan</option>
                                                    <option value="KP.04.01" {{ old('kategori', $arsip->kategori) == 'KP.04.01' ? 'selected' : '' }}>04.01 - Ujian Penyasuaian Ijasah</option>
                                                    <option value="KP.04.02" {{ old('kategori', $arsip->kategori) == 'KP.04.02' ? 'selected' : '' }}>04.02 - Ujian Dinas</option>

                                                    <!-- 05 - Ujian Kompetensi -->
                                                    <option value="KP.05" {{ old('kategori', $arsip->kategori) == 'KP.05' ? 'selected' : '' }}>05 - Ujian Kompetensi</option>
                                                    <option value="KP.05.01" {{ old('kategori', $arsip->kategori) == 'KP.05.01' ? 'selected' : '' }}>05.01 - Assessment Test Pegawai</option>
                                                    <option value="KP.05.02" {{ old('kategori', $arsip->kategori) == 'KP.05.02' ? 'selected' : '' }}>05.02 - Talent Mapping/Pemetaan Pegawai</option>

                                                    <!-- 06 - Mutasi -->
                                                    <option value="KP.06" {{ old('kategori', $arsip->kategori) == 'KP.06' ? 'selected' : '' }}>06 - Mutasi</option>
                                                    <option value="KP.06.01" {{ old('kategori', $arsip->kategori) == 'KP.06.01' ? 'selected' : '' }}>06.01 - Kenaikan Pangkat/Golongan</option>
                                                    <option value="KP.06.02" {{ old('kategori', $arsip->kategori) == 'KP.06.02' ? 'selected' : '' }}>06.02 - Kenaikan Gaji Berkala</option>
                                                    <option value="KP.06.03" {{ old('kategori', $arsip->kategori) == 'KP.06.03' ? 'selected' : '' }}>06.03 - Penyesuaian Masa Kerja</option>
                                                    <option value="KP.06.04" {{ old('kategori', $arsip->kategori) == 'KP.06.04' ? 'selected' : '' }}>06.04 - Penyesuaian Tunjangan Keluarga</option>
                                                    <option value="KP.06.05" {{ old('kategori', $arsip->kategori) == 'KP.06.05' ? 'selected' : '' }}>06.05 - Penyesuaian Kelas Jabatan</option>
                                                    <option value="KP.06.06" {{ old('kategori', $arsip->kategori) == 'KP.06.06' ? 'selected' : '' }}>06.06 - Alih Tugas</option>

                                                    <!-- 07 - Pengangkatan dan Pemberhentian Jabatan -->
                                                    <option value="KP.07" {{ old('kategori', $arsip->kategori) == 'KP.07' ? 'selected' : '' }}>07 - Pengangkatan dan Pemberhentian Jabatan</option>
                                                    <option value="KP.07.01" {{ old('kategori', $arsip->kategori) == 'KP.07.01' ? 'selected' : '' }}>07.01 - Pengangkatan Jabatan</option>
                                                    <option value="KP.07.02" {{ old('kategori', $arsip->kategori) == 'KP.07.02' ? 'selected' : '' }}>07.02 - Pemberhentian Jabatan Struktural</option>

                                                    <!-- 08 - Pendelegasian Wewenang -->
                                                    <option value="KP.08" {{ old('kategori', $arsip->kategori) == 'KP.08' ? 'selected' : '' }}>08 - Pendelegasian Wewenang</option>
                                                    <option value="KP.08.01" {{ old('kategori', $arsip->kategori) == 'KP.08.01' ? 'selected' : '' }}>08.01 - Penjabat (Pj)</option>
                                                    <option value="KP.08.02" {{ old('kategori', $arsip->kategori) == 'KP.08.02' ? 'selected' : '' }}>08.02 - Pelaksana Tugas (Plt)</option>
                                                    <option value="KP.08.03" {{ old('kategori', $arsip->kategori) == 'KP.08.03' ? 'selected' : '' }}>08.03 - Pelaksana Harian (Plh)</option>

                                                    <!-- 09 - Pendidikan dan Pelatihan Pegawai -->
                                                    <option value="KP.09" {{ old('kategori', $arsip->kategori) == 'KP.09' ? 'selected' : '' }}>09 - Pendidikan dan Pelatihan Pegawai</option>
                                                    <option value="KP.09.01" {{ old('kategori', $arsip->kategori) == 'KP.09.01' ? 'selected' : '' }}>09.01 - Program Diploma</option>
                                                    <option value="KP.09.02" {{ old('kategori', $arsip->kategori) == 'KP.09.02' ? 'selected' : '' }}>09.02 - Program Sarjana</option>
                                                    <option value="KP.09.03" {{ old('kategori', $arsip->kategori) == 'KP.09.03' ? 'selected' : '' }}>09.03 - Program Pasca Sarjana</option>
                                                    <option value="KP.09.04" {{ old('kategori', $arsip->kategori) == 'KP.09.04' ? 'selected' : '' }}>09.04 - Pendidikan dan Pelatihan Penjenjangan</option>
                                                    <option value="KP.09.05" {{ old('kategori', $arsip->kategori) == 'KP.09.05' ? 'selected' : '' }}>09.05 - Kursus/Diklat Fungsional</option>
                                                    <option value="KP.09.06" {{ old('kategori', $arsip->kategori) == 'KP.09.06' ? 'selected' : '' }}>09.06 - Kurus/Diklat Teknis</option>
                                                    <option value="KP.09.07" {{ old('kategori', $arsip->kategori) == 'KP.09.07' ? 'selected' : '' }}>09.07 - Orientasi CPNS</option>

                                                    <!-- 10 - Administrasi Pegawai -->
                                                    <option value="KP.10" {{ old('kategori', $arsip->kategori) == 'KP.10' ? 'selected' : '' }}>10 - Administrasi Pegawai</option>
                                                    <option value="KP.10.01" {{ old('kategori', $arsip->kategori) == 'KP.10.01' ? 'selected' : '' }}>10.01 - Data/Keterangan Pegawai</option>
                                                    <option value="KP.10.02" {{ old('kategori', $arsip->kategori) == 'KP.10.02' ? 'selected' : '' }}>10.02 - Kartu Pegawai</option>
                                                    <option value="KP.10.03" {{ old('kategori', $arsip->kategori) == 'KP.10.03' ? 'selected' : '' }}>10.03 - Karis/Karsu</option>
                                                    <option value="KP.10.04" {{ old('kategori', $arsip->kategori) == 'KP.10.04' ? 'selected' : '' }}>10.04 - Kartu Taspen</option>
                                                    <option value="KP.10.05" {{ old('kategori', $arsip->kategori) == 'KP.10.05' ? 'selected' : '' }}>10.05 - Kartu Jaminan Kesehatan</option>
                                                    <option value="KP.10.06" {{ old('kategori', $arsip->kategori) == 'KP.10.06' ? 'selected' : '' }}>10.06 - Tanda Jasa</option>
                                                    <option value="KP.10.07" {{ old('kategori', $arsip->kategori) == 'KP.10.07' ? 'selected' : '' }}>10.07 - Keterangan Penerimaan Pembiayaan Penghasilan Pegawai (KP4)</option>
                                                    <option value="KP.10.08" {{ old('kategori', $arsip->kategori) == 'KP.10.08' ? 'selected' : '' }}>10.08 - Laporan Harta Kekayaan Penyelenggara Negara (LHKPN)</option>
                                                    <option value="KP.10.09" {{ old('kategori', $arsip->kategori) == 'KP.10.09' ? 'selected' : '' }}>10.09 - Tunjangan Kinerja dan Uang Makan</option>

                                                    <!-- 11 - Cuti Pegawai -->
                                                    <option value="KP.11" {{ old('kategori', $arsip->kategori) == 'KP.11' ? 'selected' : '' }}>11 - Cuti Pegawai</option>
                                                    <option value="KP.11.01" {{ old('kategori', $arsip->kategori) == 'KP.11.01' ? 'selected' : '' }}>11.01 - Cuti Tahunan</option>
                                                    <option value="KP.11.02" {{ old('kategori', $arsip->kategori) == 'KP.11.02' ? 'selected' : '' }}>11.02 - Cuti Besar</option>
                                                    <option value="KP.11.03" {{ old('kategori', $arsip->kategori) == 'KP.11.03' ? 'selected' : '' }}>11.03 - Cuti Sakit</option>
                                                    <option value="KP.11.04" {{ old('kategori', $arsip->kategori) == 'KP.11.04' ? 'selected' : '' }}>11.04 - Cuti Bersalin</option>
                                                    <option value="KP.11.05" {{ old('kategori', $arsip->kategori) == 'KP.11.05' ? 'selected' : '' }}>11.05 - Cuti Karena Alasan Penting</option>
                                                    <option value="KP.11.06" {{ old('kategori', $arsip->kategori) == 'KP.11.06' ? 'selected' : '' }}>11.06 - Cuti di Luar Tanggungan Negara</option>

                                                    <!-- 12 - Pembinaan Pegawai -->
                                                    <option value="KP.12" {{ old('kategori', $arsip->kategori) == 'KP.12' ? 'selected' : '' }}>12 - Pembinaan Pegawai</option>
                                                    <option value="KP.12.01" {{ old('kategori', $arsip->kategori) == 'KP.12.01' ? 'selected' : '' }}>12.01 - Penilaian Prestasi Kerja</option>
                                                    <option value="KP.12.02" {{ old('kategori', $arsip->kategori) == 'KP.12.02' ? 'selected' : '' }}>12.02 - Sasaran Kerja Pegawai</option>
                                                    <option value="KP.12.03" {{ old('kategori', $arsip->kategori) == 'KP.12.03' ? 'selected' : '' }}>12.03 - Pembinaan Mental</option>
                                                    <option value="KP.12.04" {{ old('kategori', $arsip->kategori) == 'KP.12.04' ? 'selected' : '' }}>12.04 - Hukuman Disiplin</option>

                                                    <!-- 13 - Pembinaan Jabatan Fungsional -->
                                                    <option value="KP.13" {{ old('kategori', $arsip->kategori) == 'KP.13' ? 'selected' : '' }}>13 - Pembinaan Jabatan Fungsional</option>
                                                    <option value="KP.13.01" {{ old('kategori', $arsip->kategori) == 'KP.13.01' ? 'selected' : '' }}>13.01 - Pengangkatan Jabatan Fungsional Tertentu</option>
                                                    <option value="KP.13.02" {{ old('kategori', $arsip->kategori) == 'KP.13.02' ? 'selected' : '' }}>13.02 - Kenaikan Jenjang Jabatan</option>
                                                    <option value="KP.13.03" {{ old('kategori', $arsip->kategori) == 'KP.13.03' ? 'selected' : '' }}>13.03 - Pemindahan Jabatan Fungsional Tertentu</option>
                                                    <option value="KP.13.04" {{ old('kategori', $arsip->kategori) == 'KP.13.04' ? 'selected' : '' }}>13.04 - Pengangkatan Jabatan Fungsional Umum</option>
                                                    <option value="KP.13.05" {{ old('kategori', $arsip->kategori) == 'KP.13.05' ? 'selected' : '' }}>13.05 - Pemindahan Jabatan Fungsional Umum</option>
                                                    <option value="KP.13.06" {{ old('kategori', $arsip->kategori) == 'KP.13.06' ? 'selected' : '' }}>13.06 - Pemberhentian</option>

                                                    <!-- 14 - Kesejahteraan -->
                                                    <option value="KP.14" {{ old('kategori', $arsip->kategori) == 'KP.14' ? 'selected' : '' }}>14 - Kesejahteraan</option>
                                                    <option value="KP.14.01" {{ old('kategori', $arsip->kategori) == 'KP.14.01' ? 'selected' : '' }}>14.01 - Kesehatan</option>
                                                    <option value="KP.14.02" {{ old('kategori', $arsip->kategori) == 'KP.14.02' ? 'selected' : '' }}>14.02 - Rekreasi/Kesenian/Olahraga</option>
                                                    <option value="KP.14.03" {{ old('kategori', $arsip->kategori) == 'KP.14.03' ? 'selected' : '' }}>14.03 - Bantuan Sosial</option>
                                                    <option value="KP.14.04" {{ old('kategori', $arsip->kategori) == 'KP.14.04' ? 'selected' : '' }}>14.04 - Perumahan</option>

                                                    <!-- 15 - Pemberhentian Pegawai -->
                                                    <option value="KP.15" {{ old('kategori', $arsip->kategori) == 'KP.15' ? 'selected' : '' }}>15 - Pemberhentian Pegawai</option>
                                                    <option value="KP.15.01" {{ old('kategori', $arsip->kategori) == 'KP.15.01' ? 'selected' : '' }}>15.01 - Dengan Hormat</option>
                                                    <option value="KP.15.02" {{ old('kategori', $arsip->kategori) == 'KP.15.02' ? 'selected' : '' }}>15.02 - Tidak dengan Hormat</option>

                                                    <!-- 16 - Pemberhentian dan Penetapan Pensiun -->
                                                    <option value="KP.16" {{ old('kategori', $arsip->kategori) == 'KP.16' ? 'selected' : '' }}>16 - Pemberhentian dan Penetapan Pensiun Pegawai/Janda/Duda/PNS yang Tewas</option>

                                                    <!-- 17 - Perselisihan/Sengketa Pegawai -->
                                                    <option value="KP.17" {{ old('kategori', $arsip->kategori) == 'KP.17' ? 'selected' : '' }}>17 - Perselisihan/Sengketa Pegawai</option>

                                                    <!-- 18 - Organisasi Non Kedinasan -->
                                                    <option value="KP.18" {{ old('kategori', $arsip->kategori) == 'KP.18' ? 'selected' : '' }}>18 - Organisasi Non Kedinasan</option>
                                                    <option value="KP.18.01" {{ old('kategori', $arsip->kategori) == 'KP.18.01' ? 'selected' : '' }}>18.01 - KORPRI</option>
                                                    <option value="KP.18.02" {{ old('kategori', $arsip->kategori) == 'KP.18.02' ? 'selected' : '' }}>18.02 - Dharma Wanita</option>
                                                    <option value="KP.18.03" {{ old('kategori', $arsip->kategori) == 'KP.18.03' ? 'selected' : '' }}>18.03 - Koperasi</option>
                                                    <option value="KP.18.04" {{ old('kategori', $arsip->kategori) == 'KP.18.04' ? 'selected' : '' }}>18.04 - Lain-lain</option>
                                                </optgroup>

                                                <!-- Kerumahtanggaan - RT -->
                                                <optgroup label="Kerumahtanggaan (RT)">
                                                    <!-- 01 - Perjalanan Dinas Pimpinan -->
                                                    <option value="RT.01" {{ old('kategori', $arsip->kategori) == 'RT.01' ? 'selected' : '' }}>01 - Perjalanan Dinas Pimpinan</option>
                                                    <option value="RT.01.01" {{ old('kategori', $arsip->kategori) == 'RT.01.01' ? 'selected' : '' }}>01.01 - Dalam Negeri</option>
                                                    <option value="RT.01.02" {{ old('kategori', $arsip->kategori) == 'RT.01.02' ? 'selected' : '' }}>01.02 - Luar Negeri</option>

                                                    <!-- 02 - Rapat Pimpinan -->
                                                    <option value="RT.02" {{ old('kategori', $arsip->kategori) == 'RT.02' ? 'selected' : '' }}>02 - Rapat Pimpinan</option>
                                                    <option value="RT.02.01" {{ old('kategori', $arsip->kategori) == 'RT.02.01' ? 'selected' : '' }}>02.01 - Sarana dan Prasarana</option>
                                                    <option value="RT.02.02" {{ old('kategori', $arsip->kategori) == 'RT.02.02' ? 'selected' : '' }}>02.02 - Jamuan Rapat</option>

                                                    <!-- 03 - Kantor -->
                                                    <option value="RT.03" {{ old('kategori', $arsip->kategori) == 'RT.03' ? 'selected' : '' }}>03 - Kantor</option>
                                                    <option value="RT.03.01" {{ old('kategori', $arsip->kategori) == 'RT.03.01' ? 'selected' : '' }}>03.01 - Pemeliharaan Gedung</option>
                                                    <option value="RT.03.02" {{ old('kategori', $arsip->kategori) == 'RT.03.02' ? 'selected' : '' }}>03.02 - Perlengkapan Kantor</option>
                                                    <option value="RT.03.03" {{ old('kategori', $arsip->kategori) == 'RT.03.03' ? 'selected' : '' }}>03.03 - Air, Listrik dan Telekomunikasi</option>
                                                    <option value="RT.03.04" {{ old('kategori', $arsip->kategori) == 'RT.03.04' ? 'selected' : '' }}>03.04 - Keamanan Kantor</option>
                                                    <option value="RT.03.05" {{ old('kategori', $arsip->kategori) == 'RT.03.05' ? 'selected' : '' }}>03.05 - Kebersihan Kantor</option>
                                                    <option value="RT.03.06" {{ old('kategori', $arsip->kategori) == 'RT.03.06' ? 'selected' : '' }}>03.06 - Jamuan Tamu</option>
                                                    <option value="RT.03.07" {{ old('kategori', $arsip->kategori) == 'RT.03.07' ? 'selected' : '' }}>03.07 - Halaman dan Taman</option>

                                                    <!-- 05 - Fasilitas Pimpinan -->
                                                    <option value="RT.05" {{ old('kategori', $arsip->kategori) == 'RT.05' ? 'selected' : '' }}>05 - Fasilitas Pimpinan</option>
                                                    <option value="RT.05.01" {{ old('kategori', $arsip->kategori) == 'RT.05.01' ? 'selected' : '' }}>05.01 - Kendaraan Dinas</option>
                                                    <option value="RT.05.02" {{ old('kategori', $arsip->kategori) == 'RT.05.02' ? 'selected' : '' }}>05.02 - Pengawalan dan Pengamanan</option>
                                                    <option value="RT.05.03" {{ old('kategori', $arsip->kategori) == 'RT.05.03' ? 'selected' : '' }}>05.03 - Telekomunikasi</option>
                                                </optgroup>

                                                <!-- Keuangan - KU -->
                                                <optgroup label="Keuangan (KU)">
                                                    <!-- 01 - Rencana Anggaran Pendapatan dan Belanja Daerah -->
                                                    <option value="KU.01" {{ old('kategori', $arsip->kategori) == 'KU.01' ? 'selected' : '' }}>01 - Rencana Anggaran Pendapatan dan Belanja Daerah, dan Anggaran Pendapatan dan Belanja Daerah Perubahan</option>

                                                    <!-- 01.01 - Penyusunan Prioritas Plafon Anggaran -->
                                                    <option value="KU.01.01" {{ old('kategori', $arsip->kategori) == 'KU.01.01' ? 'selected' : '' }}>01.01 - Penyusunan Prioritas Plafon Anggaran</option>
                                                    <option value="KU.01.01.01" {{ old('kategori', $arsip->kategori) == 'KU.01.01.01' ? 'selected' : '' }}>01.01.01 - Kebijakan Umum, Strategi, Prioritas dan Renstra</option>
                                                    <option value="KU.01.01.02" {{ old('kategori', $arsip->kategori) == 'KU.01.01.02' ? 'selected' : '' }}>01.01.02 - Dokumen Rancangan kebijakan Umum Anggaran (KUA) yang telah dibahas bersama antara DPRD dan Pemerintah Daerah</option>
                                                    <option value="KU.01.01.03" {{ old('kategori', $arsip->kategori) == 'KU.01.01.03' ? 'selected' : '' }}>01.01.03 - KUA beserta Nota Kesepakatan</option>
                                                    <option value="KU.01.01.04" {{ old('kategori', $arsip->kategori) == 'KU.01.01.04' ? 'selected' : '' }}>01.01.04 - Dokumen Rancangan Prioritas Plafon Anggaran Sementara (PPAS)</option>
                                                    <option value="KU.01.01.05" {{ old('kategori', $arsip->kategori) == 'KU.01.01.05' ? 'selected' : '' }}>01.01.05 - Nota Kesepakatan PPA</option>
                                                    <option value="KU.01.01.06" {{ old('kategori', $arsip->kategori) == 'KU.01.01.06' ? 'selected' : '' }}>01.01.06 - Prioritas Plafon Anggaran</option>

                                                    <!-- 01.02 - Penyusunan RKA-SKPD -->
                                                    <option value="KU.01.02" {{ old('kategori', $arsip->kategori) == 'KU.01.02' ? 'selected' : '' }}>01.02 - Penyusunan Rencana Kerja Anggaran Satuan Kerja Perangkat Daerah (RKA-SKPD)</option>
                                                    <option value="KU.01.02.01" {{ old('kategori', $arsip->kategori) == 'KU.01.02.01' ? 'selected' : '' }}>01.02.01 - Dokumen Pedoman Penyusunan RKA-SKPD yang telah disetujui Sekretaris Daerah</option>
                                                    <option value="KU.01.02.02" {{ old('kategori', $arsip->kategori) == 'KU.01.02.02' ? 'selected' : '' }}>01.02.02 - Dokumen RKA-SKPD</option>

                                                    <!-- 01.03 - Penyampaian RAPBD -->
                                                    <option value="KU.01.03" {{ old('kategori', $arsip->kategori) == 'KU.01.03' ? 'selected' : '' }}>01.03 - Penyampaian Rancangan Anggaran Pendapatan dan Belanja Daerah kepada Dewan Perwakilan Rakyat Daerah</option>
                                                    <option value="KU.01.03.01" {{ old('kategori', $arsip->kategori) == 'KU.01.03.01' ? 'selected' : '' }}>01.03.01 - Pengantar Nota Keuangan Pemerintah dan Rancangan Peraturan Daerah APBD</option>
                                                    <option value="KU.01.03.02" {{ old('kategori', $arsip->kategori) == 'KU.01.03.02' ? 'selected' : '' }}>01.03.02 - Hasil Pembahasan Rancangan Anggaran Pendapatan dan Belanja Daerah (RAPBD) oleh Dewan Perwakilan Rakyat Daerah (DPRD) dan Pemerintah Daerah</option>
                                                    <option value="KU.01.03.03" {{ old('kategori', $arsip->kategori) == 'KU.01.03.03' ? 'selected' : '' }}>01.03.03 - Dokumen Persetujuan Evaluasi kepada Gubernur tentang Rancangan Peraturan Daerah APBD Perubahan</option>
                                                    <option value="KU.01.03.04" {{ old('kategori', $arsip->kategori) == 'KU.01.03.04' ? 'selected' : '' }}>01.03.04 - Dokumen Rancangan Penjabaran APBD beserta Lampirannya</option>
                                                    <option value="KU.01.03.05" {{ old('kategori', $arsip->kategori) == 'KU.01.03.05' ? 'selected' : '' }}>01.03.05 - Penyampaian Permohonan Evaluasi kepada Gubernur tentang RAPBD beserta penjabarannya</option>
                                                    <option value="KU.01.03.06" {{ old('kategori', $arsip->kategori) == 'KU.01.03.06' ? 'selected' : '' }}>01.03.06 - Hasil Evaluasi Gubernur tentang RAPBD</option>
                                                    <option value="KU.01.03.07" {{ old('kategori', $arsip->kategori) == 'KU.01.03.07' ? 'selected' : '' }}>01.03.07 - Penetapan Peraturan Daerah APBD oleh Wali Kota</option>
                                                    <option value="KU.01.03.08" {{ old('kategori', $arsip->kategori) == 'KU.01.03.08' ? 'selected' : '' }}>01.03.08 - Peraturan Daerah tentang APBD</option>

                                                    <!-- 01.04 - RAPBD Perubahan -->
                                                    <option value="KU.01.04" {{ old('kategori', $arsip->kategori) == 'KU.01.04' ? 'selected' : '' }}>01.04 - Anggaran Pendapatan dan Belanja Daerah Perubahan (RAPBD-P)</option>
                                                    <option value="KU.01.04.01" {{ old('kategori', $arsip->kategori) == 'KU.01.04.01' ? 'selected' : '' }}>01.04.01 - Kebijakan Umum, Strategi, Prioritas, dan Renstra</option>
                                                    <option value="KU.01.04.02" {{ old('kategori', $arsip->kategori) == 'KU.01.04.02' ? 'selected' : '' }}>01.04.02 - Dokumen Rancangan Kebijakan Umum Anggaran
                                                    <option value="KU.01.04.03" {{ old('kategori', $arsip->kategori) == 'KU.01.04.03' ? 'selected' : '' }}>01.04.03 - KUA Perubahan beserta Nota Kesepakatan</option>
                                                    <option value="KU.01.04.04" {{ old('kategori', $arsip->kategori) == 'KU.01.04.04' ? 'selected' : '' }}>01.04.04 - Dokumen Rancangan Prioritas Plafon Anggaran Sementara (PPAS) Perubahan</option>
                                                    <option value="KU.01.04.05" {{ old('kategori', $arsip->kategori) == 'KU.01.04.05' ? 'selected' : '' }}>01.04.05 - Nota Kesepakatan Prioritas Plafon Anggaran</option>
                                                    <option value="KU.01.04.06" {{ old('kategori', $arsip->kategori) == 'KU.01.04.06' ? 'selected' : '' }}>01.04.06 - Prioritas Plafon Anggaran Perubahan</option>

                                                    <!-- 01.05 - RKA-SKPD Perubahan -->
                                                    <option value="KU.01.05" {{ old('kategori', $arsip->kategori) == 'KU.01.05' ? 'selected' : '' }}>01.05 - Penyusunan Rencana Kerja Anggaran Satuan Kerja Perangkat Daerah (RKA-SKPD) Perubahan</option>
                                                    <option value="KU.01.05.01" {{ old('kategori', $arsip->kategori) == 'KU.01.05.01' ? 'selected' : '' }}>01.05.01 - Dokumen Pedoman Penyusunan RKA-SKPD Perubahan yang telah disetujui Sekretaris Daerah</option>
                                                    <option value="KU.01.05.02" {{ old('kategori', $arsip->kategori) == 'KU.01.05.02' ? 'selected' : '' }}>01.05.02 - Dokumen RKA-SKPD Perubahan</option>

                                                    <!-- 01.06 - Penyampaian RAPBD Perubahan -->
                                                    <option value="KU.01.06" {{ old('kategori', $arsip->kategori) == 'KU.01.06' ? 'selected' : '' }}>01.06 - Penyampaian Rancangan Anggaran Pendapatan dan Belanja Daerah Perubahan kepada Dewan Perwakilan Rakyat Daerah (DPRD)</option>
                                                    <option value="KU.01.06.01" {{ old('kategori', $arsip->kategori) == 'KU.01.06.01' ? 'selected' : '' }}>01.06.01 - Pengantar Nota Keuangan Pemerintah dan Rancangan Peraturan Daerah RAPBD Perubahan, Nota Keuangan Pemerintah dan Materi RAPBD Perubahan</option>
                                                    <option value="KU.01.06.02" {{ old('kategori', $arsip->kategori) == 'KU.01.06.02' ? 'selected' : '' }}>01.06.02 - Hasil Pembahasan Rencana Anggaran Pendapatan dan Belanja Daerah (RAPBD) Perubahan oleh Dewan Perwakilan Rakyat Daerah (DPRD) dan Pemerintah Daerah</option>
                                                    <option value="KU.01.06.03" {{ old('kategori', $arsip->kategori) == 'KU.01.06.03' ? 'selected' : '' }}>01.06.03 - Dokumen Persetujuan Bersama antara DPRD dan Kepala Daerah tentang Rancangan Peraturan Daerah APBD Perubahan</option>
                                                    <option value="KU.01.06.04" {{ old('kategori', $arsip->kategori) == 'KU.01.06.04' ? 'selected' : '' }}>01.06.04 - Dokumen Rancangan Penjabaran APBD beserta Lampirannya</option>
                                                    <option value="KU.01.06.05" {{ old('kategori', $arsip->kategori) == 'KU.01.06.05' ? 'selected' : '' }}>01.06.05 - Penyampaian Permohonan Evaluasi kepada Gubernur tentang RAPBD Perubahan beserta Penjabarannya</option>
                                                    <option value="KU.01.06.06" {{ old('kategori', $arsip->kategori) == 'KU.01.06.06' ? 'selected' : '' }}>01.06.06 - Hasil Evaluasi Gubernur tentang RAPBD Perubahan</option>
                                                    <option value="KU.01.06.07" {{ old('kategori', $arsip->kategori) == 'KU.01.06.07' ? 'selected' : '' }}>01.06.07 - Penetapan Peraturan Daerah APBD Perubahan oleh Wali Kota beserta Penjabarannya</option>
                                                    <option value="KU.01.06.08" {{ old('kategori', $arsip->kategori) == 'KU.01.06.08' ? 'selected' : '' }}>01.06.08 - Peraturan Daerah tentang APBD Perubahan</option>

                                                    <!-- 02 - Penyusunan Anggaran -->
                                                    <option value="KU.02" {{ old('kategori', $arsip->kategori) == 'KU.02' ? 'selected' : '' }}>02 - Penyusunan Anggaran</option>
                                                    <option value="KU.02.01" {{ old('kategori', $arsip->kategori) == 'KU.02.01' ? 'selected' : '' }}>02.01 - Hasil Musyawarah Rencana Pembangunan (Musrenbang) Kelurahan</option>
                                                    <option value="KU.02.02" {{ old('kategori', $arsip->kategori) == 'KU.02.02' ? 'selected' : '' }}>02.02 - Hasil Musyawarah Rencana Pembangunan (Musrenbang) Kecamatan</option>
                                                    <option value="KU.02.03" {{ old('kategori', $arsip->kategori) == 'KU.02.03' ? 'selected' : '' }}>02.03 - Hasil Musyawarah Rencana Pembangunan (Musrenbang) Kota</option>
                                                    <option value="KU.02.04" {{ old('kategori', $arsip->kategori) == 'KU.02.04' ? 'selected' : '' }}>02.04 - Rancangan Dokumen Pelaksanaan Anggaran (RDPA) SKPD yang telah disetujui Sekretaris Daerah</option>
                                                    <option value="KU.02.05" {{ old('kategori', $arsip->kategori) == 'KU.02.05' ? 'selected' : '' }}>02.05 - Dokumen Pelaksanaan Anggaran (DPA) SKPD yang telah disahkan oleh Pejabat Pengelola Keuangan Daerah</option>

                                                    <option value="KU.03" {{ old('kategori', $arsip->kategori) == 'KU.03' ? 'selected' : '' }}>03 - Pelaksanaan Anggaran</option>
                                                    <option value="KU.03.01" {{ old('kategori', $arsip->kategori) == 'KU.03.01' ? 'selected' : '' }}>03.01 - Pendapatan</option>
                                                    <option value="KU.03.01.01" {{ old('kategori', $arsip->kategori) == 'KU.03.01.01' ? 'selected' : '' }}>03.01.01 - Pendapatan Asli Daerah</option>
                                                    <option value="KU.03.01.01.01" {{ old('kategori', $arsip->kategori) == 'KU.03.01.01.01' ? 'selected' : '' }}>03.01.01.01 - Retribusi Daerah</option>
                                                    <option value="KU.03.01.01.02" {{ old('kategori', $arsip->kategori) == 'KU.03.01.01.02' ? 'selected' : '' }}>03.01.01.02 - Hasil Perusahaan Milik Daerah dan Hasil Pengelolaan Kekayaan Daerah yang Dipisahkan</option>
                                                    <option value="KU.03.01.01.03" {{ old('kategori', $arsip->kategori) == 'KU.03.01.01.03' ? 'selected' : '' }}>03.01.01.03 - Lain-Lain Pendapatan Asli Daerah Yang Sah</option>
                                                    <option value="KU.03.01.02" {{ old('kategori', $arsip->kategori) == 'KU.03.01.02' ? 'selected' : '' }}>03.01.02 - Dana Perimbangan</option>
                                                    <option value="KU.03.01.02.01" {{ old('kategori', $arsip->kategori) == 'KU.03.01.02.01' ? 'selected' : '' }}>03.01.02.01 - Dana Bagi Hasil</option>
                                                    <option value="KU.03.01.02.02" {{ old('kategori', $arsip->kategori) == 'KU.03.01.02.02' ? 'selected' : '' }}>03.01.02.02 - Dana Alokasi Umum</option>
                                                    <option value="KU.03.01.02.03" {{ old('kategori', $arsip->kategori) == 'KU.03.01.02.03' ? 'selected' : '' }}>03.01.02.03 - Dana Alokasi Khusus</option>
                                                    <option value="KU.03.01.03" {{ old('kategori', $arsip->kategori) == 'KU.03.01.03' ? 'selected' : '' }}>03.01.03 - Lain-Lain Pendapatan Daerah Yang Sah</option>
                                                    <option value="KU.03.01.03.01" {{ old('kategori', $arsip->kategori) == 'KU.03.01.03.01' ? 'selected' : '' }}>03.01.03.01 - Hibah</option>
                                                    <option value="KU.03.01.03.02" {{ old('kategori', $arsip->kategori) == 'KU.03.01.03.02' ? 'selected' : '' }}>03.01.03.02 - Dana Darurat</option>
                                                    <option value="KU.03.01.03.03" {{ old('kategori', $arsip->kategori) == 'KU.03.01.03.03' ? 'selected' : '' }}>03.01.03.03 - Dana Bagi Hasil Pajak dari Provinsi dan Pemerintah Daerah Lainnya</option>
                                                    <option value="KU.03.01.03.04" {{ old('kategori', $arsip->kategori) == 'KU.03.01.03.04' ? 'selected' : '' }}>03.01.03.04 - Dana Penyesuaian dan Otonomi Khusus</option>
                                                    <option value="KU.03.01.03.05" {{ old('kategori', $arsip->kategori) == 'KU.03.01.03.05' ? 'selected' : '' }}>03.01.03.05 - Bantuan Keuangan dari Provinsi atau Pemerintah Daerah Lainnya</option>

                                                    <option value="KU.03.02" {{ old('kategori', $arsip->kategori) == 'KU.03.02' ? 'selected' : '' }}>03.02 - Belanja</option>
                                                    <option value="KU.03.02.01" {{ old('kategori', $arsip->kategori) == 'KU.03.02.01' ? 'selected' : '' }}>03.02.01 - Belanja Operasi</option>
                                                    <option value="KU.03.02.01.01" {{ old('kategori', $arsip->kategori) == 'KU.03.02.01.01' ? 'selected' : '' }}>03.02.01.01 - Belanja Pegawai</option>
                                                    <option value="KU.03.02.01.02" {{ old('kategori', $arsip->kategori) == 'KU.03.02.01.02' ? 'selected' : '' }}>03.02.01.02 - Belanja Barang dan Jasa</option>
                                                    <option value="KU.03.02.01.03" {{ old('kategori', $arsip->kategori) == 'KU.03.02.01.03' ? 'selected' : '' }}>03.02.01.03 - Belanja Bunga</option>
                                                    <option value="KU.03.02.01.04" {{ old('kategori', $arsip->kategori) == 'KU.03.02.01.04' ? 'selected' : '' }}>03.02.01.04 - Belanja Subsidi</option>
                                                    <option value="KU.03.02.01.05" {{ old('kategori', $arsip->kategori) == 'KU.03.02.01.05' ? 'selected' : '' }}>03.02.01.05 - Belanja Hibah</option>
                                                    <option value="KU.03.02.01.06" {{ old('kategori', $arsip->kategori) == 'KU.03.02.01.06' ? 'selected' : '' }}>03.02.01.06 - Belanja Bantuan Sosial</option>
                                                    <option value="KU.03.02.02" {{ old('kategori', $arsip->kategori) == 'KU.03.02.02' ? 'selected' : '' }}>03.02.02 - Belanja Modal</option>
                                                    <option value="KU.03.02.02.01" {{ old('kategori', $arsip->kategori) == 'KU.03.02.02.01' ? 'selected' : '' }}>03.02.02.01 - Belanja Tanah</option>
                                                    <option value="KU.03.02.02.02" {{ old('kategori', $arsip->kategori) == 'KU.03.02.02.02' ? 'selected' : '' }}>03.02.02.02 - Belanja Peralatan dan Mesin</option>
                                                    <option value="KU.03.02.02.03" {{ old('kategori', $arsip->kategori) == 'KU.03.02.02.03' ? 'selected' : '' }}>03.02.02.03 - Belanja Gedung dan Bangunan</option>
                                                    <option value="KU.03.02.02.04" {{ old('kategori', $arsip->kategori) == 'KU.03.02.02.04' ? 'selected' : '' }}>03.02.02.04 - Belanja Jalan, Irigasi dan Jaringan</option>
                                                    <option value="KU.03.02.02.05" {{ old('kategori', $arsip->kategori) == 'KU.03.02.02.05' ? 'selected' : '' }}>03.02.02.05 - Belanja Aset Tetap Lainnya</option>
                                                    <option value="KU.03.02.02.06" {{ old('kategori', $arsip->kategori) == 'KU.03.02.02.06' ? 'selected' : '' }}>03.02.02.06 - Belanja Aset Lainnya</option>
                                                    <option value="KU.03.02.03" {{ old('kategori', $arsip->kategori) == 'KU.03.02.03' ? 'selected' : '' }}>03.02.03 - Belanja Tak Terduga</option>
                                                    <option value="KU.03.02.03.01" {{ old('kategori', $arsip->kategori) == 'KU.03.02.03.01' ? 'selected' : '' }}>03.02.03.01 - Belanja Tak Terduga</option>

                                                    <option value="KU.03.03" {{ old('kategori', $arsip->kategori) == 'KU.03.03' ? 'selected' : '' }}>03.03 - Pembiayaan</option>
                                                    <option value="KU.03.03.01" {{ old('kategori', $arsip->kategori) == 'KU.03.03.01' ? 'selected' : '' }}>03.03.01 - Penerimaan Pembiayaan</option>
                                                    <option value="KU.03.03.01.01" {{ old('kategori', $arsip->kategori) == 'KU.03.03.01.01' ? 'selected' : '' }}>03.03.01.01 - Sisa Lebih Perhitungan Anggaran Tahun Anggaran Sebelumnya</option>
                                                    <option value="KU.03.03.01.02" {{ old('kategori', $arsip->kategori) == 'KU.03.03.01.02' ? 'selected' : '' }}>03.03.01.02 - Pencairan Dana Cadangan</option>
                                                    <option value="KU.03.03.01.03" {{ old('kategori', $arsip->kategori) == 'KU.03.03.01.03' ? 'selected' : '' }}>03.03.01.03 - Hasil Penjualan Kekayaan Daerah yang Dipisahkan</option>
                                                    <option value="KU.03.03.01.04" {{ old('kategori', $arsip->kategori) == 'KU.03.03.01.04' ? 'selected' : '' }}>03.03.01.04 - Penerimaan Pinjaman Daerah</option>
                                                    <option value="KU.03.03.01.05" {{ old('kategori', $arsip->kategori) == 'KU.03.03.01.05' ? 'selected' : '' }}>03.03.01.05 - Penerimaan Kembali Pemberian Pinjaman</option>
                                                    <option value="KU.03.03.01.06" {{ old('kategori', $arsip->kategori) == 'KU.03.03.01.06' ? 'selected' : '' }}>03.03.01.06 - Penerimaan Piutang Daerah</option>
                                                    <option value="KU.03.03.02" {{ old('kategori', $arsip->kategori) == 'KU.03.03.02' ? 'selected' : '' }}>03.03.02 - Pengeluaran Pembiayaan</option>
                                                    <option value="KU.03.03.02.01" {{ old('kategori', $arsip->kategori) == 'KU.03.03.02.01' ? 'selected' : '' }}>03.03.02.01 - Pembentukan Dana Cadangan</option>
                                                    <option value="KU.03.03.02.02" {{ old('kategori', $arsip->kategori) == 'KU.03.03.02.02' ? 'selected' : '' }}>03.03.02.02 - Penyertaan Modal (Investasi) Pemerintah Daerah</option>
                                                    <option value="KU.03.03.02.03" {{ old('kategori', $arsip->kategori) == 'KU.03.03.02.03' ? 'selected' : '' }}>03.03.02.03 - Pembayaran Pokok Utang</option>
                                                    <option value="KU.03.03.02.04" {{ old('kategori', $arsip->kategori) == 'KU.03.03.02.04' ? 'selected' : '' }}>03.03.02.04 - Pemberian Pinjaman Daerah</option>

                                                    <option value="KU.04" {{ old('kategori', $arsip->kategori) == 'KU.04' ? 'selected' : '' }}>04 - Dokumen Penerimaan Lain-Lain Pendapatan yang Sah</option>
                                                    <option value="KU.04.01" {{ old('kategori', $arsip->kategori) == 'KU.04.01' ? 'selected' : '' }}>04.01 - Bukti Penerimaan Pembiayaan</option>
                                                    <option value="KU.04.01.01" {{ old('kategori', $arsip->kategori) == 'KU.04.01.01' ? 'selected' : '' }}>04.01.01 - SiLPA</option>
                                                    <option value="KU.04.01.02" {{ old('kategori', $arsip->kategori) == 'KU.04.01.02' ? 'selected' : '' }}>04.01.02 - Dana Cadangan</option>
                                                    <option value="KU.04.01.03" {{ old('kategori', $arsip->kategori) == 'KU.04.01.03' ? 'selected' : '' }}>04.01.03 - Dana Berguir</option>
                                                    <option value="KU.04.01.04" {{ old('kategori', $arsip->kategori) == 'KU.04.01.04' ? 'selected' : '' }}>04.01.04 - Pinjaman Daerah</option>
                                                    <option value="KU.04.01.05" {{ old('kategori', $arsip->kategori) == 'KU.04.01.05' ? 'selected' : '' }}>04.01.05 - Pengalihan Piutang PBB P2 menjadi PAD</option>
                                                    <option value="KU.04.02" {{ old('kategori', $arsip->kategori) == 'KU.04.02' ? 'selected' : '' }}>04.02 - Bukti Pengeluaran Pembiayaan</option>
                                                    <option value="KU.04.02.01" {{ old('kategori', $arsip->kategori) == 'KU.04.02.01' ? 'selected' : '' }}>04.02.01 - Investasi Jangka Panjang dalam Bentuk Dana Berguir</option>
                                                    <option value="KU.04.02.02" {{ old('kategori', $arsip->kategori) == 'KU.04.02.02' ? 'selected' : '' }}>04.02.02 - Penyertaan Modal pada BUMD</option>
                                                    <option value="KU.04.02.03" {{ old('kategori', $arsip->kategori) == 'KU.04.02.03' ? 'selected' : '' }}>04.02.03 - Penambahan Penyertaan Modal pada BUMD</option>
                                                    <option value="KU.04.02.04" {{ old('kategori', $arsip->kategori) == 'KU.04.02.04' ? 'selected' : '' }}>04.02.04 - Pengeluaran dari Dana Cadangan</option>
                                                    <option value="KU.04.02.05" {{ old('kategori', $arsip->kategori) == 'KU.04.02.05' ? 'selected' : '' }}>04.02.05 - Pembiayaan bagi Usaha Masyarakat Kecil dan Menengah (UMKM)</option>
                                                    <option value="KU.04.02.06" {{ old('kategori', $arsip->kategori) == 'KU.04.02.06' ? 'selected' : '' }}>04.02.06 - Penyertaan Modal pada Bank Perkreditan Rakyat (BPR) milik Pemerintah Daerah</option>

                                                    <option value="KU.05" {{ old('kategori', $arsip->kategori) == 'KU.05' ? 'selected' : '' }}>05 - Dokumen Penatausahaan Keuangan</option>
                                                    <option value="KU.05.01" {{ old('kategori', $arsip->kategori) == 'KU.05.01' ? 'selected' : '' }}>05.01 - Surat Penyediaan Dana (SPD)</option>
                                                    <option value="KU.05.02" {{ old('kategori', $arsip->kategori) == 'KU.05.02' ? 'selected' : '' }}>05.02 - Surat Permohonan Pembayaran (SPP)</option>
                                                    <option value="KU.05.03" {{ old('kategori', $arsip->kategori) == 'KU.05.03' ? 'selected' : '' }}>05.03 - Surat Perintah Membayar (SPM)</option>
                                                    <option value="KU.05.04" {{ old('kategori', $arsip->kategori) == 'KU.05.04' ? 'selected' : '' }}>05.04 - Surat Perintah Pencairan Dana (SP2D)</option>

                                                    <option value="KU.06" {{ old('kategori', $arsip->kategori) == 'KU.06' ? 'selected' : '' }}>06 - Pertanggungjawaban Penggunaan Dana</option>
                                                    <option value="KU.06.01" {{ old('kategori', $arsip->kategori) == 'KU.06.01' ? 'selected' : '' }}>06.01 - Buku Kas Umum (BKU)</option>
                                                    <option value="KU.06.02" {{ old('kategori', $arsip->kategori) == 'KU.06.02' ? 'selected' : '' }}>06.02 - Buku Kas Pembantu (BKP)</option>
                                                    <option value="KU.06.03" {{ old('kategori', $arsip->kategori) == 'KU.06.03' ? 'selected' : '' }}>06.03 - Ringkasan Perincian Pengeluaran Objek</option>
                                                    <option value="KU.06.04" {{ old('kategori', $arsip->kategori) == 'KU.06.04' ? 'selected' : '' }}>06.04 - Rekening Koran Bank</option>
                                                    <option value="KU.06.05" {{ old('kategori', $arsip->kategori) == 'KU.06.05' ? 'selected' : '' }}>06.05 - Pertanggungjawaban Fungsionalitas dan Administrasi</option>
                                                    <option value="KU.06.06" {{ old('kategori', $arsip->kategori) == 'KU.06.06' ? 'selected' : '' }}>06.06 - Bukti Penyetoran Pajak</option>
                                                    <option value="KU.06.07" {{ old('kategori', $arsip->kategori) == 'KU.06.07' ? 'selected' : '' }}>06.07 - Register Penutupan Kas</option>
                                                    <option value="KU.06.08" {{ old('kategori', $arsip->kategori) == 'KU.06.08' ? 'selected' : '' }}>06.08 - Berita Acara Pemeriksaan</option>
                                                    <option value="KU.06.09" {{ old('kategori', $arsip->kategori) == 'KU.06.09' ? 'selected' : '' }}>06.09 - Laporan Realisasi Anggaran (LRA), Neraca, Laporan Operasional (LO), Laporan Perubahan Ekuitas (LPE), Catatan Atas Laporan Keuangan (CaLK), Arsip Data Komputer (ADK)</option>
                                                    <option value="KU.06.10" {{ old('kategori', $arsip->kategori) == 'KU.06.10' ? 'selected' : '' }}>06.10 - Laporan Pendapatan Daerah</option>
                                                    <option value="KU.06.11" {{ old('kategori', $arsip->kategori) == 'KU.06.11' ? 'selected' : '' }}>06.11 - Laporan Keadaan Kredit Anggaran</option>
                                                    <option value="KU.06.12" {{ old('kategori', $arsip->kategori) == 'KU.06.12' ? 'selected' : '' }}>06.12 - Laporan Realisasi Anggaran, Laporan Operasional, Neraca Bulanan/Triwulan/Semesteran</option>
                                                    <option value="KU.06.13" {{ old('kategori', $arsip->kategori) == 'KU.06.13' ? 'selected' : '' }}>06.13 - Berita Acara Rekonsiliasi Data Realisasi Pendapatan Daerah</option>
                                                    <option value="KU.06.14" {{ old('kategori', $arsip->kategori) == 'KU.06.14' ? 'selected' : '' }}>06.14 - Berita Acara Rekonsiliasi Data Realisasi Belanja Daerah dan Pembiayaan Daerah</option>

                                                    <option value="KU.07" {{ old('kategori', $arsip->kategori) == 'KU.07' ? 'selected' : '' }}>07 - Daftar Gaji</option>
                                                    <option value="KU.08" {{ old('kategori', $arsip->kategori) == 'KU.08' ? 'selected' : '' }}>08 - Kartu Gaji</option>
                                                    <option value="KU.09" {{ old('kategori', $arsip->kategori) == 'KU.09' ? 'selected' : '' }}>09 - Data Rekening Bendahara Umum Daerah (BUD)</option>

                                                    <option value="KU.10" {{ old('kategori', $arsip->kategori) == 'KU.10' ? 'selected' : '' }}>10 - Laporan Keuangan Tahunan</option>
                                                    <option value="KU.10.01" {{ old('kategori', $arsip->kategori) == 'KU.10.01' ? 'selected' : '' }}>10.01 - Laporan Realisasi Anggaran (LRA)</option>
                                                    <option value="KU.10.02" {{ old('kategori', $arsip->kategori) == 'KU.10.02' ? 'selected' : '' }}>10.02 - Laporan Perubahan Saldo Anggaran Lebih (LP-SAL)</option>
                                                    <option value="KU.10.03" {{ old('kategori', $arsip->kategori) == 'KU.10.03' ? 'selected' : '' }}>10.03 - Neraca</option>
                                                    <option value="KU.10.04" {{ old('kategori', $arsip->kategori) == 'KU.10.04' ? 'selected' : '' }}>10.04 - Laporan Operasional (LO)</option>
                                                    <option value="KU.10.05" {{ old('kategori', $arsip->kategori) == 'KU.10.05' ? 'selected' : '' }}>10.05 - Laporan Arus Kas (LAK)</option>
                                                    <option value="KU.10.06" {{ old('kategori', $arsip->kategori) == 'KU.10.06' ? 'selected' : '' }}>10.06 - Laporan Perubahan Ekuitas (LPE)</option>
                                                    <option value="KU.10.07" {{ old('kategori', $arsip->kategori) == 'KU.10.07' ? 'selected' : '' }}>10.07 - Catatan Atas Laporan Keuangan (CaLK)</option>

                                                    <option value="KU.11" {{ old('kategori', $arsip->kategori) == 'KU.11' ? 'selected' : '' }}>11 - Bantuan/Pinjaman Luar Negeri</option>
                                                    <option value="KU.11.01" {{ old('kategori', $arsip->kategori) == 'KU.11.01' ? 'selected' : '' }}>11.01 - Permohonan Pinjaman Luar Negeri (Blue Book)</option>
                                                    <option value="KU.11.02" {{ old('kategori', $arsip->kategori) == 'KU.11.02' ? 'selected' : '' }}>11.02 - Dokumen Kesanggupan Negara Donor untuk Membiayai (Green Book)</option>
                                                    <option value="KU.11.03" {{ old('kategori', $arsip->kategori) == 'KU.11.03' ? 'selected' : '' }}>11.03 - Dokumen Memorandum of Understanding (MoU), dan Dokumen Sejenisnya</option>
                                                    <option value="KU.11.04" {{ old('kategori', $arsip->kategori) == 'KU.11.04' ? 'selected' : '' }}>11.04 - Dokumen Loan Agreement (PLHN) seperti Draft Agreement, Legal Opinion, Surat Menyurat dengan Lender</option>
                                                    <option value="KU.11.05" {{ old('kategori', $arsip->kategori) == 'KU.11.05' ? 'selected' : '' }}>11.05 - Alokasi dan Relokasi Penggunaan Dana Luar Negeri, antara lain Usulan Luncuran Dana</option>
                                                    <option value="KU.11.06" {{ old('kategori', $arsip->kategori) == 'KU.11.06' ? 'selected' : '' }}>11.06 - Aplikasi Penarikan Dana Bantuan Luar Negeri berikut Lampirannya</option>
                                                    <option value="KU.11.06.01" {{ old('kategori', $arsip->kategori) == 'KU.11.06.01' ? 'selected' : '' }}>11.06.01 - Reimbursement</option>
                                                    <option value="KU.11.06.02" {{ old('kategori', $arsip->kategori) == 'KU.11.06.02' ? 'selected' : '' }}>11.06.02 - Direct Payment/Transfer Procedure</option>
                                                    <option value="KU.11.06.03" {{ old('kategori', $arsip->kategori) == 'KU.11.06.03' ? 'selected' : '' }}>11.06.03 - Special Commitment/L/C Opening</option>
                                                    <option value="KU.11.06.04" {{ old('kategori', $arsip->kategori) == 'KU.11.06.04' ? 'selected' : '' }}>11.06.04 - Special Account/Impress Fund</option>
                                                    <option value="KU.11.07" {{ old('kategori', $arsip->kategori) == 'KU.11.07' ? 'selected' : '' }}>11.07 - Dokumen Otorisasi Penarikan Dana (Payment Advice)</option>
                                                    <option value="KU.11.08" {{ old('kategori', $arsip->kategori) == 'KU.11.08' ? 'selected' : '' }}>11.08 - Dokumen Realisasi Pencairan Dana Bantuan Luar Negeri, yaitu: Surat Perintah Pencairan Dana, SPM beserta lampirannya, antara lain SPP, Kontrak, BA, dan Data Pendukung Lainnya</option>
                                                    <option value="KU.11.09" {{ old('kategori', $arsip->kategori) == 'KU.11.09' ? 'selected' : '' }}>11.09 - Replenishment (Permintaan Penarikan Dana dari Negara Donor) meliputi antara lain No Object Letter (NOL), Project Implementation, Notification of Contract, Withdrawal Authorization (WA), Statement of Expenditure (SE)</option>
                                                    <option value="KU.11.10" {{ old('kategori', $arsip->kategori) == 'KU.11.10' ? 'selected' : '' }}>11.10 - Staff Appraisal Report</option>
                                                    <option value="KU.11.11" {{ old('kategori', $arsip->kategori) == 'KU.11.11' ? 'selected' : '' }}>11.11 - Report/Laporan yang terdiri dari</option>
                                                    <option value="KU.11.11.01" {{ old('kategori', $arsip->kategori) == 'KU.11.11.01' ? 'selected' : '' }}>11.11.01 - Progress Report</option>
                                                    <option value="KU.11.11.02" {{ old('kategori', $arsip->kategori) == 'KU.11.11.02' ? 'selected' : '' }}>11.11.02 - Monthly Report</option>
                                                    <option value="KU.11.11.03" {{ old('kategori', $arsip->kategori) == 'KU.11.11.03' ? 'selected' : '' }}>11.11.03 - Quarterly Report</option>
                                                    <option value="KU.11.12" {{ old('kategori', $arsip->kategori) == 'KU.11.12' ? 'selected' : '' }}>11.12 - Laporan Hutang Daerah</option>
                                                    <option value="KU.11.12.01" {{ old('kategori', $arsip->kategori) == 'KU.11.12.01' ? 'selected' : '' }}>11.12.01 - Laporan Pembayaran Hutang Daerah</option>
                                                    <option value="KU.11.12.02" {{ old('kategori', $arsip->kategori) == 'KU.11.12.02' ? 'selected' : '' }}>11.12.02 - Laporan Posisi Hutang Daerah</option>
                                                    <option value="KU.11.13" {{ old('kategori', $arsip->kategori) == 'KU.11.13' ? 'selected' : '' }}>11.13 - Completion Report/Annual Report</option>
                                                    <option value="KU.11.14" {{ old('kategori', $arsip->kategori) == 'KU.11.14' ? 'selected' : '' }}>11.14 - Ketentuan/Peraturan yang Menyangkut Bantuan/Pinjaman Luar Negeri</option>

                                                    <option value="KU.12" {{ old('kategori', $arsip->kategori) == 'KU.12' ? 'selected' : '' }}>12 - Pengelolaan APBD/Dana Pinjaman/Hibah Luar Negeri (PHLN)</option>
                                                    <option value="KU.12.01" {{ old('kategori', $arsip->kategori) == 'KU.12.01' ? 'selected' : '' }}>12.01 - Keputusan Kepala Daerah tentang Penetapan</option>
                                                    <option value="KU.12.01.01" {{ old('kategori', $arsip->kategori) == 'KU.12.01.01' ? 'selected' : '' }}>12.01.01 - Kuasa Penggunaan Anggaran</option>
                                                    <option value="KU.12.01.02" {{ old('kategori', $arsip->kategori) == 'KU.12.01.02' ? 'selected' : '' }}>12.01.02 - Kuasa Pengguna Barang/Jasa</option>
                                                    <option value="KU.12.01.03" {{ old('kategori', $arsip->kategori) == 'KU.12.01.03' ? 'selected' : '' }}>12.01.03 - Pejabat Pembuat Komitmen</option>
                                                    <option value="KU.12.01.04" {{ old('kategori', $arsip->kategori) == 'KU.12.01.04' ? 'selected' : '' }}>12.01.04 - Pejabat Pembuat Daftar Gaji</option>
                                                    <option value="KU.12.01.05" {{ old('kategori', $arsip->kategori) == 'KU.12.01.05' ? 'selected' : '' }}>12.01.05 - Pejabat Penandatanganan SPM</option>
                                                    <option value="KU.12.01.06" {{ old('kategori', $arsip->kategori) == 'KU.12.01.06' ? 'selected' : '' }}>12.01.06 - Bendahara Penerimaan/Pengeluaran</option>
                                                    <option value="KU.12.01.07" {{ old('kategori', $arsip->kategori) == 'KU.12.01.07' ? 'selected' : '' }}>12.01.07 - Pengelola Barang</option>
                                                    <option value="KU.12.01.08" {{ old('kategori', $arsip->kategori) == 'KU.12.01.08' ? 'selected' : '' }}>12.01.08 - Berita Acara Serah Terima Jabatan</option>

                                                    <option value="KU.13" {{ old('kategori', $arsip->kategori) == 'KU.13' ? 'selected' : '' }}>13 - Akuntansi Pemerintah Daerah</option>
                                                    <option value="KU.13.01" {{ old('kategori', $arsip->kategori) == 'KU.13.01' ? 'selected' : '' }}>13.01 - Kebijakan Akuntansi Pemerintah Daerah</option>
                                                    <option value="KU.13.02" {{ old('kategori', $arsip->kategori) == 'KU.13.02' ? 'selected' : '' }}>13.02 - Sistem Akuntansi Pemerintah Daerah</option>
                                                    <option value="KU.13.03" {{ old('kategori', $arsip->kategori) == 'KU.13.03' ? 'selected' : '' }}>13.03 - Bagan Akun Standar</option>
                                                    <option value="KU.13.04" {{ old('kategori', $arsip->kategori) == 'KU.13.04' ? 'selected' : '' }}>13.04 - Arsip Data Komputer</option>

                                                    <option value="KU.14" {{ old('kategori', $arsip->kategori) == 'KU.14' ? 'selected' : '' }}>14 - Penyaluran Anggaran Tugas Pembantuan</option>
                                                    <option value="KU.14.01" {{ old('kategori', $arsip->kategori) == 'KU.14.01' ? 'selected' : '' }}>14.01 - Penetapan Pemimpin Proyek/Bagian Proyek, Bendahara, atas Penggunaan Anggaran Kegiatan Pembantuan, termasuk Specimen Tanda Tangan</option>
                                                    <option value="KU.14.02" {{ old('kategori', $arsip->kategori) == 'KU.14.02' ? 'selected' : '' }}>14.02 - Berkas Permintaan Pembayaran (SPP) dan lampirannya:</option>
                                                    <option value="KU.14.02.01" {{ old('kategori', $arsip->kategori) == 'KU.14.02.01' ? 'selected' : '' }}>14.02.01 - SPP-SPP-Daftar Perincian Penggunaan SPPR-SPDR-L, SPM-LS, SPM-DUA, Bilyet giro, SPM Nihil</option>
                                                    <option value="KU.14.02.02" {{ old('kategori', $arsip->kategori) == 'KU.14.02.02' ? 'selected' : '' }}>14.02.02 - Penagihan/Invoice, Faktur Pajak, Bukti Penerimaan Kas/Bank beserta Bukti Pendukungnya antara lain Copy Faktur Pajak dan Nota Kredit Bank</option>
                                                    <option value="KU.14.02.03" {{ old('kategori', $arsip->kategori) == 'KU.14.02.03' ? 'selected' : '' }}>14.02.03 - Permintaan Pelayanan Jasa/Service Report dan Berita Acara Penyelesaian Pekerjaan</option>
                                                    <option value="KU.14.03" {{ old('kategori', $arsip->kategori) == 'KU.14.03' ? 'selected' : '' }}>14.03 - Buku Rekening Bank</option>
                                                    <option value="KU.14.04" {{ old('kategori', $arsip->kategori) == 'KU.14.04' ? 'selected' : '' }}>14.04 - Keputusan Pembukuan Rekening</option>
                                                    <option value="KU.14.05" {{ old('kategori', $arsip->kategori) == 'KU.14.05' ? 'selected' : '' }}>14.05 - Pembukuan Anggaran terdiri dari:</option>
                                                    <option value="KU.14.05.01" {{ old('kategori', $arsip->kategori) == 'KU.14.05.01' ? 'selected' : '' }}>14.05.01 - Buku Kas Umum (BKU)</option>
                                                    <option value="KU.14.05.02" {{ old('kategori', $arsip->kategori) == 'KU.14.05.02' ? 'selected' : '' }}>14.05.02 - Buku Kas Pembantu</option>
                                                    <option value="KU.14.05.03" {{ old('kategori', $arsip->kategori) == 'KU.14.05.03' ? 'selected' : '' }}>14.05.03 - Register dan Buku Tambahan</option>
                                                    <option value="KU.14.05.04" {{ old('kategori', $arsip->kategori) == 'KU.14.05.04' ? 'selected' : '' }}>14.05.04 - Daftar Pembukuan Selama Rekening masih aktif</option>
                                                    <option value="KU.14.05.05" {{ old('kategori', $arsip->kategori) == 'KU.14.05.05' ? 'selected' : '' }}>14.05.05 - Pencairan/Pengeluaran (DPP)</option>
                                                    <option value="KU.14.05.06" {{ old('kategori', $arsip->kategori) == 'KU.14.05.06' ? 'selected' : '' }}>14.05.06 - Daftar Pembukuan Pencairan/Pengeluaran (DPP)</option>
                                                    <option value="KU.14.05.07" {{ old('kategori', $arsip->kategori) == 'KU.14.05.07' ? 'selected' : '' }}>14.05.07 - Daftar Himpunan Pencairan (DHIP)</option>
                                                    <option value="KU.14.05.08" {{ old('kategori', $arsip->kategori) == 'KU.14.05.08' ? 'selected' : '' }}>14.05.08 - Rekening Koran</option>

                                                    <option value="KU.15" {{ old('kategori', $arsip->kategori) == 'KU.15' ? 'selected' : '' }}>15 - Penerimaan Anggaran Tugas Pembantuan</option>
                                                    <option value="KU.15.01" {{ old('kategori', $arsip->kategori) == 'KU.15.01' ? 'selected' : '' }}>15.01 - Berkas Penerimaan Keuangan Pelaksanaan dan Tugas Pembantuan Termasuk Dana Sisa atau Pengeluaran Lainnya</option>
                                                    <option value="KU.15.02" {{ old('kategori', $arsip->kategori) == 'KU.15.02' ? 'selected' : '' }}>15.02 - Berkas Penerimaan Pajak termasuk PPh 21, PPh 22, PPh 23, dan PPn, dan Denda Keterlambatan Menyelesaikan Pekerjaan</option>

                                                    <option value="KU.16" {{ old('kategori', $arsip->kategori) == 'KU.16' ? 'selected' : '' }}>16 - Pengelolaan Anggaran Pemilu</option>
                                                    <option value="KU.16.01" {{ old('kategori', $arsip->kategori) == 'KU.16.01' ? 'selected' : '' }}>16.01 - Penyusunan Anggaran Pilkada dan Biaya Bantuan Pemilu dari APBD</option>
                                                    <option value="KU.16.01.01" {{ old('kategori', $arsip->kategori) == 'KU.16.01.01' ? 'selected' : '' }}>16.01.01 - Kebijakan Keuangan Pilkada dan Penyusunan Anggaran Bantuan Pemilu</option>
                                                    <option value="KU.16.01.02" {{ old('kategori', $arsip->kategori) == 'KU.16.01.02' ? 'selected' : '' }}>16.01.02 - Peraturan/Pedoman/Standar Belanja Pegawai, Barang dan Jasa, Operasional dan Kontingensi untuk Biaya Pilkada dan Bantuan Pemilu</option>
                                                    <option value="KU.16.01.03" {{ old('kategori', $arsip->kategori) == 'KU.16.01.03' ? 'selected' : '' }}>16.01.03 - Bahan Usulan Rencana Kegiatan dan Anggaran (RKA) Pilkada KPUD dan Panitia Pengawas Daerah Provinsi, PPK, PPS, KPPS dan Permohonan Pengajuan RKA KPUD dan Panitia Pengawas</option>
                                                    <option value="KU.16.01.04" {{ old('kategori', $arsip->kategori) == 'KU.16.01.04' ? 'selected' : '' }}>16.01.04 - Berkas Pembahasan RKA Pilkada dan Bantuan Pemilu</option>
                                                    <option value="KU.16.01.05" {{ old('kategori', $arsip->kategori) == 'KU.16.01.05' ? 'selected' : '' }}>16.01.05 - Rencana Anggaran Satuan Kerja (RASK) Pilkada dan Bantuan Pemilu Provinsi</option>
                                                    <option value="KU.16.01.06" {{ old('kategori', $arsip->kategori) == 'KU.16.01.06' ? 'selected' : '' }}>16.01.06 - Dokumen Rancangan Anggaran Satuan Kerja (DRASK) Pilkada KPUD dan Panitia Pengawas Provinsi dan Bantuan Biaya Pemilu dari APBD</option>
                                                    <option value="KU.16.01.07" {{ old('kategori', $arsip->kategori) == 'KU.16.01.07' ? 'selected' : '' }}>16.01.07 - Berkas Pembentukan Dana Cadangan Pilkada</option>
                                                    <option value="KU.16.01.08" {{ old('kategori', $arsip->kategori) == 'KU.16.01.08' ? 'selected' : '' }}>16.01.08 - Bahan Rapat Rancangan Peraturan Daerah tentang Pilkada, dan Bantuan Biaya Pemilu dari APBD</option>
                                                    <option value="KU.16.01.09" {{ old('kategori', $arsip->kategori) == 'KU.16.01.09' ? 'selected' : '' }}>16.01.09 - Nota Persetujuan DPRD tentang Peraturan Daerah Pilkada dan Bantuan Biaya Pemilu dari APBD</option>
                                                    <option value="KU.16.01.10" {{ old('kategori', $arsip->kategori) == 'KU.16.01.10' ? 'selected' : '' }}>16.01.10 - Pelaksanaan Anggaran Pilkada dan Anggaran Biaya Bantuan Pemilu</option>
                                                    <option value="KU.16.01.10.01" {{ old('kategori', $arsip->kategori) == 'KU.16.01.10.01' ? 'selected' : '' }}>16.01.10.01 - Berkas Penetapan Bendahara dan Atasan Langsung Bendahara KPUD, Bendahara Panitia Pengawas Daerah dan Bendahara pada Panitia Pilkada dan Pemilu</option>
                                                    <option value="KU.16.01.10.02" {{ old('kategori', $arsip->kategori) == 'KU.16.01.10.02' ? 'selected' : '' }}>16.01.10.02 - Berkas Penerimaan Komisi, Rabat Pembayaran Pengadaan Jasa, Bunga, Pelaksanaan Pilkada/Pemilu</option>
                                                    <option value="KU.16.01.10.03" {{ old('kategori', $arsip->kategori) == 'KU.16.01.10.03' ? 'selected' : '' }}>16.01.10.03 - Berkas Setor Sisa Dana Pilkada/Pemilu</option>
                                                    <option value="KU.16.01.10.04" {{ old('kategori', $arsip->kategori) == 'KU.16.01.10.04' ? 'selected' : '' }}>16.01.10.04 - Berkas Penyaluran Biaya Pemilu Termasuk Di antaranya Bukti Transfer Bank</option>
                                                    <option value="KU.16.01.10.05" {{ old('kategori', $arsip->kategori) == 'KU.16.01.10.05' ? 'selected' : '' }}>16.01.10.05 - Pedoman Dokumen Penyediaan Pembiayaan Kegiatan Operasional (PPKO) Pemilu Termasuk Perubahan/Pergeseran/Revisinya</option>
                                                    <option value="KU.16.01.11" {{ old('kategori', $arsip->kategori) == 'KU.16.01.11' ? 'selected' : '' }}>16.01.11 - Pelaksanaan Anggaran Operasional Pemilu</option>
                                                    <option value="KU.16.01.11.01" {{ old('kategori', $arsip->kategori) == 'KU.16.01.11.01' ? 'selected' : '' }}>16.01.11.01 - Dokumen Penyediaan Pembiayaan Kegiatan Operasional (PPKO) Pemilu termasuk Perubahan/Pergeseran/Revisinya</option>
                                                    <option value="KU.16.01.11.02" {{ old('kategori', $arsip->kategori) == 'KU.16.01.11.02' ? 'selected' : '' }}>16.01.11.02 - Berkas Penetapan Bendahara dan Atasan Langsung Bendahara KPUD Provinsi, Panitia Pengawas Daerah dan Pemegang Uang Muka Cabang (PUMC) PPK dan Panitia Pengawas</option>
                                                    <option value="KU.16.01.11.03" {{ old('kategori', $arsip->kategori) == 'KU.16.01.11.03' ? 'selected' : '' }}>16.01.11.03 - Berkas Penyaluran Biaya Pemilu ke PPK, PPS, dan KPPS Termasuk di antaranya Bukti Transfer Bank</option>
                                                    <option value="KU.16.01.12" {{ old('kategori', $arsip->kategori) == 'KU.16.01.12' ? 'selected' : '' }}>16.01.12 - Pemeriksaan/ Pengawasan Keuangan Daerah</option>
                                                    <option value="KU.16.01.12.01" {{ old('kategori', $arsip->kategori) == 'KU.16.01.12.01' ? 'selected' : '' }}>16.01.12.01 - Laporan Hasil Pemeriksaan Badan Pemeriksa Keuangan</option>
                                                    <option value="KU.16.01.12.02" {{ old('kategori', $arsip->kategori) == 'KU.16.01.12.02' ? 'selected' : '' }}>16.01.12.02 - Hasil Pengawasan dan Pemeriksaan Internal</option>
                                                    <option value="KU.16.01.12.03" {{ old('kategori', $arsip->kategori) == 'KU.16.01.12.03' ? 'selected' : '' }}>16.01.12.03 - Laporan Aparat Pemeriksaan Fungsional</option>
                                                    <option value="KU.16.01.12.03.01" {{ old('kategori', $arsip->kategori) == 'KU.16.01.12.03.01' ? 'selected' : '' }}>16.01.12.03.01 - LHP (Laporan Hasil Pemeriksaan)</option>
                                                    <option value="KU.16.01.12.03.02" {{ old('kategori', $arsip->kategori) == 'KU.16.01.12.03.02' ? 'selected' : '' }}>16.01.12.03.02 - MHP (Memorandum Hasil Pemeriksaan)</option>
                                                    <option value="KU.16.01.12.03.03" {{ old('kategori', $arsip->kategori) == 'KU.16.01.12.03.03' ? 'selected' : '' }}>16.01.12.03.03 - Tindak Lanjut/Tanggapan LHP</option>
                                                    <option value="KU.16.01.12.04" {{ old('kategori', $arsip->kategori) == 'KU.16.01.12.04' ? 'selected' : '' }}>16.01.12.04 - Dokumen Penyelesaian Kerugian Daerah</option>
                                                    <option value="KU.16.01.12.04.01" {{ old('kategori', $arsip->kategori) == 'KU.16.01.12.04.01' ? 'selected' : '' }}>16.01.12.04.01 - Tuntutan Perbendaharaan</option>
                                                    <option value="KU.16.01.12.04.02" {{ old('kategori', $arsip->kategori) == 'KU.16.01.12.04.02' ? 'selected' : '' }}>16.01.12.04.02 - Tuntutan Ganti Rugi</option>

                                                    <option value="KU.17" {{ old('kategori', $arsip->kategori) == 'KU.17' ? 'selected' : '' }}>17 - Pengadaan Barang/Jasa</option>
                                                    <option value="KU.17.01" {{ old('kategori', $arsip->kategori) == 'KU.17.01' ? 'selected' : '' }}>17.01 - Rencana Umum Pengadaan (RUP)</option>
                                                    <option value="KU.17.02" {{ old('kategori', $arsip->kategori) == 'KU.17.02' ? 'selected' : '' }}>17.02 - Pelaksanaan Pengadaan</option>
                                                    <option value="KU.17.02.01" {{ old('kategori', $arsip->kategori) == 'KU.17.02.01' ? 'selected' : '' }}>17.02.01 - Swakelola</option>
                                                    <option value="KU.17.02.02" {{ old('kategori', $arsip->kategori) == 'KU.17.02.02' ? 'selected' : '' }}>17.02.02 - Pengadaan Langsung</option>
                                                    <option value="KU.17.02.03" {{ old('kategori', $arsip->kategori) == 'KU.17.02.03' ? 'selected' : '' }}>17.02.03 - Penunjukan Langsung</option>
                                                    <option value="KU.17.02.04" {{ old('kategori', $arsip->kategori) == 'KU.17.02.04' ? 'selected' : '' }}>17.02.04 - Tender</option>
                                                    <option value="KU.17.02.05" {{ old('kategori', $arsip->kategori) == 'KU.17.02.05' ? 'selected' : '' }}>17.02.05 - E-Purchasing</option>
                                                    <option value="KU.17.03" {{ old('kategori', $arsip->kategori) == 'KU.17.03' ? 'selected' : '' }}>17.03 - Laporan Pengadaan Barang/Jasa</option>

                                                    </optgroup>

                                                <!-- Traditional categories (maintaining backward compatibility) -->
                                                <optgroup label="Kategori Tradisional">
                                                    <option value="Surat Masuk" {{ old('kategori', $arsip->kategori) == 'Surat Masuk' ? 'selected' : '' }}>Surat Masuk</option>
                                                    <option value="Surat Keluar" {{ old('kategori', $arsip->kategori) == 'Surat Keluar' ? 'selected' : '' }}>Surat Keluar</option>
                                                    <option value="Dokumen Penting" {{ old('kategori', $arsip->kategori) == 'Dokumen Penting' ? 'selected' : '' }}>Dokumen Penting</option>
                                                    <option value="Laporan" {{ old('kategori', $arsip->kategori) == 'Laporan' ? 'selected' : '' }}>Laporan</option>
                                                    <option value="Lainnya" {{ old('kategori', $arsip->kategori) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                                </optgroup>
                                            </select>
                                            @error('kategori')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label for="tanggal_arsip" class="form-control-label text-sm">Tanggal Arsip <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @error('tanggal_arsip') is-invalid @enderror" id="tanggal_arsip" name="tanggal_arsip" value="{{ old('tanggal_arsip', $arsip->tanggal_arsip) }}" required>
                                            @error('tanggal_arsip')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label for="retention_type" class="form-control-label text-sm">Masa Retensi <span class="text-danger">*</span></label>
                                            <select class="form-select @error('retention_type') is-invalid @enderror" id="retention_type" name="retention_type" required>
                                                @php
                                                    $currentRetentionType = ($arsip->retention_years == 5) ? 'auto' : 'manual';
                                                    $selectedType = old('retention_type', $currentRetentionType);
                                                @endphp
                                                <option value="auto" {{ $selectedType == 'auto' ? 'selected' : '' }}>Otomatis (5 Tahun)</option>
                                                <option value="manual" {{ $selectedType == 'manual' ? 'selected' : '' }}>Manual</option>
                                            </select>
                                            @error('retention_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                Pilih otomatis untuk masa retensi 5 tahun atau manual untuk menentukan sendiri
                                            </small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-4" id="manual_retention_section" style="display: {{ $selectedType == 'manual' ? 'block' : 'none' }};">
                                            <label for="retention_years" class="form-control-label text-sm">Masa Retensi (Tahun) <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control @error('retention_years') is-invalid @enderror" id="retention_years" name="retention_years" value="{{ old('retention_years', $arsip->retention_years ?? 5) }}" min="1" max="50">
                                            @error('retention_years')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                Masukkan jumlah tahun untuk masa retensi (1-50 tahun)
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label for="keterangan" class="form-control-label text-sm">Keterangan</label>
                                            <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="3">{{ old('keterangan', $arsip->keterangan) }}</textarea>
                                            @error('keterangan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label for="rak" class="form-control-label text-sm">Rak Penyimpanan</label>
                                            <input type="text" class="form-control @error('rak') is-invalid @enderror" id="rak" name="rak" value="{{ old('rak', $arsip->rak) }}">
                                            @error('rak')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                Informasi lokasi penyimpanan fisik arsip
                                            </small>

                                <!-- File Upload Section -->
                                <div class="form-group mb-4">
                                    <label for="file" class="form-control-label text-sm">Upload File</label>

                                    @if(isset($arsip->file_path) && $arsip->file_path)
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center p-3 border rounded bg-gray-100">
                                            <div class="icon-box me-3">
                                                @php
                                                    $fileType = isset($arsip->file_type) ? $arsip->file_type : (isset($arsip->file_path) ? pathinfo($arsip->file_path, PATHINFO_EXTENSION) : '');
                                                    $iconClass = 'fa-file';

                                                    if (in_array($fileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                                                        $iconClass = 'fa-file-image';
                                                    } elseif (in_array($fileType, ['pdf'])) {
                                                        $iconClass = 'fa-file-pdf';
                                                    } elseif (in_array($fileType, ['xlsx', 'xls', 'csv'])) {
                                                        $iconClass = 'fa-file-excel';
                                                    } elseif (in_array($fileType, ['doc', 'docx'])) {
                                                        $iconClass = 'fa-file-word';
                                                    }
                                                @endphp

                                                <i class="fas {{ $iconClass }} fa-2x text-primary"></i>
                                            </div>
                                            <div class="file-info flex-grow-1">
                                                <h6 class="mb-0 font-weight-semibold">File saat ini</h6>
                                                <p class="mb-0 text-sm text-secondary">{{ basename($arsip->file_path) }}</p>
                                            </div>
                                            <div class="file-actions">
                                                <!-- Download feature removed as per requirement -->
                                                <span class="text-muted small">
                                                    <i class="fas fa-file me-1"></i> File tersedia
                                                </span>
                                            </div>
                                        </div>
                                        <small class="form-text text-muted mt-1">
                                            Upload file baru untuk mengganti file yang ada
                                        </small>
                                    </div>
                                    @endif

                                    <div class="input-group">
                                        <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file">
                                        @error('file')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">
                                        Format yang didukung: JPG, PNG, PDF, Excel (XLSX, XLS), Word (DOC, DOCX). Ukuran maksimal: 10MB
                                    </small>
                                </div>

                                <div class="d-flex justify-content-end mt-4">
                                    <a href="{{ route('arsip.index') }}" class="btn btn-light me-3">Batal</a>
                                    <button type="submit" class="btn btn-warning">
                                        <span class="btn-inner--icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2">
                                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                                <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                                <polyline points="7 3 7 8 15 8"></polyline>
                                            </svg>
                                        </span>
                                        Update Data
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <x-app.footer />
        </div>
    </div>

    <!-- JavaScript for Retention Type -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle retention type selection
        const retentionTypeSelect = document.getElementById('retention_type');
        const manualRetentionSection = document.getElementById('manual_retention_section');
        const retentionYearsInput = document.getElementById('retention_years');

        function handleRetentionTypeChange() {
            if (retentionTypeSelect.value === 'manual') {
                manualRetentionSection.style.display = 'block';
                retentionYearsInput.required = true;
            } else {
                manualRetentionSection.style.display = 'none';
                retentionYearsInput.required = false;
            }
        }

        // Initialize retention type on page load
        handleRetentionTypeChange();

        // Handle retention type change
        retentionTypeSelect.addEventListener('change', handleRetentionTypeChange);
    });
    </script>
</x-app-layout>
