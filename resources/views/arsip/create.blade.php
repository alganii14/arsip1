<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-12">
                    <div class="card card-background card-background-after-none align-items-start mt-4 mb-5">
                        <div class="full-background" style="background-image: url('../assets/img/header-blue-purple.jpg')"></div>
                        <div class="card-body text-start p-4 w-100">
                            <h3 class="text-white mb-2">Tambah Arsip Baru</h3>
                            <p class="mb-4 font-weight-semibold text-white">
                                Isi formulir berikut untuk menambahkan arsip dokumen baru
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
                                    <h6 class="font-weight-semibold text-lg mb-0">Form Tambah Arsip</h6>
                                    <p class="text-sm mb-sm-0">Silahkan isi semua field yang diperlukan</p>
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

                            <form action="{{ route('arsip.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label for="kode" class="form-control-label text-sm">Kode <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('kode') is-invalid @enderror" id="kode" name="kode" value="{{ old('kode') }}" required>
                                            @error('kode')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label for="nama_dokumen" class="form-control-label text-sm">Nama Dokumen <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('nama_dokumen') is-invalid @enderror" id="nama_dokumen" name="nama_dokumen" value="{{ old('nama_dokumen') }}" required>
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
                                                <option value="" selected disabled>Pilih Kategori</option>
                                                <!-- Kearsipan - AR -->
                                                <optgroup label="Kearsipan (AR)">
                                                    <!-- 01 - Kebijakan -->
                                                    <option value="AR.01" {{ old('kategori') == 'AR.01' ? 'selected' : '' }}>01 - Kebijakan</option>
                                                    <option value="AR.01.01" {{ old('kategori') == 'AR.01.01' ? 'selected' : '' }}>01.01 - Peraturan Daerah</option>
                                                    <option value="AR.01.01.01" {{ old('kategori') == 'AR.01.01.01' ? 'selected' : '' }}>01.01.01 - Pengkajian dan Pengusulan</option>
                                                    <option value="AR.01.01.02" {{ old('kategori') == 'AR.01.01.02' ? 'selected' : '' }}>01.01.02 - Penyusunan Rancangan Peraturan Daerah</option>
                                                    <option value="AR.01.01.03" {{ old('kategori') == 'AR.01.01.03' ? 'selected' : '' }}>01.01.03 - Pembahasan Rancangan Peraturan Daerah dan Persetujuan Rancangan Peraturan Daerah</option>
                                                    <option value="AR.01.01.04" {{ old('kategori') == 'AR.01.01.04' ? 'selected' : '' }}>01.01.04 - Penetapan Peraturan Daerah</option>
                                                    <option value="AR.01.01.05" {{ old('kategori') == 'AR.01.01.05' ? 'selected' : '' }}>01.01.05 - Sosialisasi Peraturan Daerah</option>

                                                    <option value="AR.01.02" {{ old('kategori') == 'AR.01.02' ? 'selected' : '' }}>01.02 - Peraturan Wali Kota</option>
                                                    <option value="AR.01.02.01" {{ old('kategori') == 'AR.01.02.01' ? 'selected' : '' }}>01.02.01 - Pengkajian dan Pembahasan Rancangan Peraturan Wali Kota</option>
                                                    <option value="AR.01.02.02" {{ old('kategori') == 'AR.01.02.02' ? 'selected' : '' }}>01.02.02 - Pengusulan dan Penetapan Peraturan Wali Kota</option>
                                                    <option value="AR.01.02.03" {{ old('kategori') == 'AR.01.02.03' ? 'selected' : '' }}>01.02.03 - Sosialisasi Peraturan Wali Kota</option>

                                                    <option value="AR.01.03" {{ old('kategori') == 'AR.01.03' ? 'selected' : '' }}>01.03 - Penetapan Organisasi Kearsipan</option>
                                                    <option value="AR.01.03.01" {{ old('kategori') == 'AR.01.03.01' ? 'selected' : '' }}>01.03.01 - Unit Pengolah</option>
                                                    <option value="AR.01.03.02" {{ old('kategori') == 'AR.01.03.02' ? 'selected' : '' }}>01.03.02 - Unit Kearsipan</option>

                                                    <!-- 02 - Pembinaan Kearsipan -->
                                                    <option value="AR.02" {{ old('kategori') == 'AR.02' ? 'selected' : '' }}>02 - Pembinaan Kearsipan</option>
                                                    <option value="AR.02.01" {{ old('kategori') == 'AR.02.01' ? 'selected' : '' }}>02.01 - Bina Arsiparis</option>
                                                    <option value="AR.02.01.01" {{ old('kategori') == 'AR.02.01.01' ? 'selected' : '' }}>02.01.01 - Formasi Jabatan Arsiparis</option>
                                                    <option value="AR.02.01.02" {{ old('kategori') == 'AR.02.01.02' ? 'selected' : '' }}>02.01.02 - Standar Kompetensi Arsiparis</option>
                                                    <option value="AR.02.01.03" {{ old('kategori') == 'AR.02.01.03' ? 'selected' : '' }}>02.01.03 - Bimbingan Konsultasi Arsiparis</option>
                                                    <option value="AR.02.01.04" {{ old('kategori') == 'AR.02.01.04' ? 'selected' : '' }}>02.01.04 - Penilaian Arsiparis</option>
                                                    <option value="AR.02.01.05" {{ old('kategori') == 'AR.02.01.05' ? 'selected' : '' }}>02.01.05 - Penyelenggaraan Pemilihan Arsiparis Teladan</option>
                                                    <option value="AR.02.01.06" {{ old('kategori') == 'AR.02.01.06' ? 'selected' : '' }}>02.01.06 - Berkas Penetapan Arsiparis Teladan</option>
                                                    <option value="AR.02.01.07" {{ old('kategori') == 'AR.02.01.07' ? 'selected' : '' }}>02.01.07 - Data Base Arsiparis</option>

                                                    <option value="AR.02.02" {{ old('kategori') == 'AR.02.02' ? 'selected' : '' }}>02.02 - Bimbingan dan Konsultasi</option>
                                                    <option value="AR.02.02.01" {{ old('kategori') == 'AR.02.02.01' ? 'selected' : '' }}>02.02.01 - Penerapan Sistem Kearsipan</option>
                                                    <option value="AR.02.02.02" {{ old('kategori') == 'AR.02.02.02' ? 'selected' : '' }}>02.02.02 - Penggunaan Sarana dan Prasarana Kearsipan</option>
                                                    <option value="AR.02.02.03" {{ old('kategori') == 'AR.02.02.03' ? 'selected' : '' }}>02.02.03 - Unit Kearsipan</option>
                                                    <option value="AR.02.02.04" {{ old('kategori') == 'AR.02.02.04' ? 'selected' : '' }}>02.02.04 - Sumber Daya Manusia</option>

                                                    <option value="AR.02.03" {{ old('kategori') == 'AR.02.03' ? 'selected' : '' }}>02.03 - Supervisi dan Evaluasi</option>
                                                    <option value="AR.02.03.01" {{ old('kategori') == 'AR.02.03.01' ? 'selected' : '' }}>02.03.01 - Perencanaan</option>
                                                    <option value="AR.02.03.02" {{ old('kategori') == 'AR.02.03.02' ? 'selected' : '' }}>02.03.02 - Pelaksanaan</option>
                                                    <option value="AR.02.03.03" {{ old('kategori') == 'AR.02.03.03' ? 'selected' : '' }}>02.03.03 - Laporan Hasil Supervisi dan Evaluasi</option>

                                                    <option value="AR.02.04" {{ old('kategori') == 'AR.02.04' ? 'selected' : '' }}>02.04 - Data Base Bimbingan, Supervisi dan Evaluasi</option>

                                                    <option value="AR.02.05" {{ old('kategori') == 'AR.02.05' ? 'selected' : '' }}>02.05 - Fasilitasi Kearsipan</option>
                                                    <option value="AR.02.05.01" {{ old('kategori') == 'AR.02.05.01' ? 'selected' : '' }}>02.05.01 - Sumber Daya Manusia Kearsipan</option>
                                                    <option value="AR.02.05.02" {{ old('kategori') == 'AR.02.05.02' ? 'selected' : '' }}>02.05.02 - Prasarana dan Sarana</option>

                                                    <option value="AR.02.06" {{ old('kategori') == 'AR.02.06' ? 'selected' : '' }}>02.06 - Lembaga/Unit Kearsipan Teladan</option>
                                                    <option value="AR.02.06.01" {{ old('kategori') == 'AR.02.06.01' ? 'selected' : '' }}>02.06.01 - Penyelenggaraan</option>
                                                    <option value="AR.02.06.02" {{ old('kategori') == 'AR.02.06.02' ? 'selected' : '' }}>02.06.02 - Berkas Penetapan Lembaga/Unit Kearsipan Teladan</option>

                                                    <!-- 03 - Pengelolaan Arsip Dinamis -->
                                                    <option value="AR.03" {{ old('kategori') == 'AR.03' ? 'selected' : '' }}>03 - Pengelolaan Arsip Dinamis</option>
                                                    <option value="AR.03.01" {{ old('kategori') == 'AR.03.01' ? 'selected' : '' }}>03.01 - Penciptaan</option>
                                                    <option value="AR.03.01.01" {{ old('kategori') == 'AR.03.01.01' ? 'selected' : '' }}>03.01.01 - Pencatatan (Buku Agenda, Kartu Kendali dan Lembar Pengantar/Ekspedisi)</option>
                                                    <option value="AR.03.01.02" {{ old('kategori') == 'AR.03.01.02' ? 'selected' : '' }}>03.01.02 - Pendistribusian</option>

                                                    <option value="AR.03.02" {{ old('kategori') == 'AR.03.02' ? 'selected' : '' }}>03.02 - Penggunaan</option>
                                                    <option value="AR.03.02.01" {{ old('kategori') == 'AR.03.02.01' ? 'selected' : '' }}>03.02.01 - Pengklasifikasian Pengamanan dan Akses Arsip Dinamis</option>
                                                    <option value="AR.03.02.02" {{ old('kategori') == 'AR.03.02.02' ? 'selected' : '' }}>03.02.02 - Peminjaman</option>

                                                    <option value="AR.03.03" {{ old('kategori') == 'AR.03.03' ? 'selected' : '' }}>03.03 - Pemeliharaan</option>
                                                    <option value="AR.03.03.01" {{ old('kategori') == 'AR.03.03.01' ? 'selected' : '' }}>03.03.01 - Pemberkasan: Daftar Arsip Aktif (Daftar Berkas dan Isi Berkas)</option>
                                                    <option value="AR.03.03.02" {{ old('kategori') == 'AR.03.03.02' ? 'selected' : '' }}>03.03.02 - Penataan Arsip Inaktif: Pengaturan Fisik, Pengolahan Informasi Arsip, Penyusunan Daftar Arsip Inaktif</option>

                                                    <option value="AR.03.04" {{ old('kategori') == 'AR.03.04' ? 'selected' : '' }}>03.04 - Penyimpanan</option>
                                                    <option value="AR.03.04.01" {{ old('kategori') == 'AR.03.04.01' ? 'selected' : '' }}>03.04.01 - Skema Penyimpanan Arsip Aktif dan Inaktif</option>
                                                    <option value="AR.03.04.02" {{ old('kategori') == 'AR.03.04.02' ? 'selected' : '' }}>03.04.02 - Pengamanan</option>

                                                    <option value="AR.03.05" {{ old('kategori') == 'AR.03.05' ? 'selected' : '' }}>03.05 - Alih Media</option>
                                                    <option value="AR.03.05.01" {{ old('kategori') == 'AR.03.05.01' ? 'selected' : '' }}>03.05.01 - Kebijakan Alih Media</option>
                                                    <option value="AR.03.05.02" {{ old('kategori') == 'AR.03.05.02' ? 'selected' : '' }}>03.05.02 - Autentikasi</option>
                                                    <option value="AR.03.05.03" {{ old('kategori') == 'AR.03.05.03' ? 'selected' : '' }}>03.05.03 - Berita Acara</option>
                                                    <option value="AR.03.05.04" {{ old('kategori') == 'AR.03.05.04' ? 'selected' : '' }}>03.05.04 - Daftar Arsip Alih Media</option>

                                                    <option value="AR.03.06" {{ old('kategori') == 'AR.03.06' ? 'selected' : '' }}>03.06 - Program Arsip Vital</option>
                                                    <option value="AR.03.06.01" {{ old('kategori') == 'AR.03.06.01' ? 'selected' : '' }}>03.06.01 - Identifikasi</option>
                                                    <option value="AR.03.06.02" {{ old('kategori') == 'AR.03.06.02' ? 'selected' : '' }}>03.06.02 - Pelindungan dan Pengamanan</option>
                                                    <option value="AR.03.06.03" {{ old('kategori') == 'AR.03.06.03' ? 'selected' : '' }}>03.06.03 - Penyelamatan dan Pemulihan</option>

                                                    <option value="AR.03.07" {{ old('kategori') == 'AR.03.07' ? 'selected' : '' }}>03.07 - Autentikasi Arsip Dinamis</option>
                                                    <option value="AR.03.07.01" {{ old('kategori') == 'AR.03.07.01' ? 'selected' : '' }}>03.07.01 - Pembuktian Autentisitas</option>
                                                    <option value="AR.03.07.02" {{ old('kategori') == 'AR.03.07.02' ? 'selected' : '' }}>03.07.02 - Pendapat Tenaga Ahli</option>
                                                    <option value="AR.03.07.03" {{ old('kategori') == 'AR.03.07.03' ? 'selected' : '' }}>03.07.03 - Pengujian</option>
                                                    <option value="AR.03.07.04" {{ old('kategori') == 'AR.03.07.04' ? 'selected' : '' }}>03.07.04 - Penetapan Autentisitas Arsip Statis/Surat Pernyataan Pencipta Arsip</option>

                                                    <option value="AR.03.08" {{ old('kategori') == 'AR.03.08' ? 'selected' : '' }}>03.08 - Penyusutan</option>
                                                    <option value="AR.03.08.01" {{ old('kategori') == 'AR.03.08.01' ? 'selected' : '' }}>03.08.01 - Pemindahan Arsip Inaktif (Berita Acara dan Daftar Arsip yang Dipindahkan)</option>
                                                    <option value="AR.03.08.02" {{ old('kategori') == 'AR.03.08.02' ? 'selected' : '' }}>03.08.02 - Pemusnahan Arsip yang Tidak Bernilai Guna</option>
                                                    <option value="AR.03.08.02.01" {{ old('kategori') == 'AR.03.08.02.01' ? 'selected' : '' }}>03.08.02.01 - Panitia Penilai</option>
                                                    <option value="AR.03.08.02.02" {{ old('kategori') == 'AR.03.08.02.02' ? 'selected' : '' }}>03.08.02.02 - Penilaian Panitia Penilai</option>
                                                    <option value="AR.03.08.02.03" {{ old('kategori') == 'AR.03.08.02.03' ? 'selected' : '' }}>03.08.02.03 - Permintaan Persetujuan (Kepala ANRI, Kepala Lembaga Kearsipan)</option>
                                                    <option value="AR.03.08.02.04" {{ old('kategori') == 'AR.03.08.02.04' ? 'selected' : '' }}>03.08.02.04 - Penetapan Arsip yang Dimusnakan</option>
                                                    <option value="AR.03.08.02.05" {{ old('kategori') == 'AR.03.08.02.05' ? 'selected' : '' }}>03.08.02.05 - Berita Acara Pemusnahan Arsip</option>
                                                    <option value="AR.03.08.02.06" {{ old('kategori') == 'AR.03.08.02.06' ? 'selected' : '' }}>03.08.02.06 - Daftar Arsip yang Dimusnakan</option>
                                                    <option value="AR.03.08.03" {{ old('kategori') == 'AR.03.08.03' ? 'selected' : '' }}>03.08.03 - Penyerahan Arsip Statis</option>
                                                    <option value="AR.03.08.03.01" {{ old('kategori') == 'AR.03.08.03.01' ? 'selected' : '' }}>03.08.03.01 - Pembentukan Panitia Penilai</option>
                                                    <option value="AR.03.08.03.02" {{ old('kategori') == 'AR.03.08.03.02' ? 'selected' : '' }}>03.08.03.02 - Notulen Rapat</option>
                                                    <option value="AR.03.08.03.03" {{ old('kategori') == 'AR.03.08.03.03' ? 'selected' : '' }}>03.08.03.03 - Surat Pertimbangan Panitia Penilai</option>
                                                    <option value="AR.03.08.03.04" {{ old('kategori') == 'AR.03.08.03.04' ? 'selected' : '' }}>03.08.03.04 - Surat Persetujuan dari Kepala Lembaga Kearsipan</option>
                                                    <option value="AR.03.08.03.05" {{ old('kategori') == 'AR.03.08.03.05' ? 'selected' : '' }}>03.08.03.05 - Surat Pernyataan Autentik, Terpercaya, Utuh, dan dapat Digunakan dari Pencipta Arsip</option>
                                                    <option value="AR.03.08.03.06" {{ old('kategori') == 'AR.03.08.03.06' ? 'selected' : '' }}>03.08.03.06 - Keputusan Penetapan Penyerahan</option>
                                                    <option value="AR.03.08.03.07" {{ old('kategori') == 'AR.03.08.03.07' ? 'selected' : '' }}>03.08.03.07 - Berita Acara Penyerahan Arsip</option>
                                                    <option value="AR.03.08.03.08" {{ old('kategori') == 'AR.03.08.03.08' ? 'selected' : '' }}>03.08.03.08 - Daftar Arsip yang Diserahkan</option>
                                                    <option value="AR.03.09" {{ old('kategori') == 'AR.03.09' ? 'selected' : '' }}>03.09 - Data Base Pengelolaan Arsip Dinamis</option>
                                                    <option value="AR.03.09.01" {{ old('kategori') == 'AR.03.09.01' ? 'selected' : '' }}>03.09.01 - Data Base Pengelolaan Arsip Aktif</option>
                                                    <option value="AR.03.09.02" {{ old('kategori') == 'AR.03.09.02' ? 'selected' : '' }}>03.09.02 - Data Base Pengelolaan Arsip Inaktif</option>

                                                    <option value="AR.04" {{ old('kategori') == 'AR.04' ? 'selected' : '' }}>04 - Pengelolaan Arsip Statis</option>
                                                    <option value="AR.04.01" {{ old('kategori') == 'AR.04.01' ? 'selected' : '' }}>04.01 - Akuisisi</option>
                                                    <option value="AR.04.01.01" {{ old('kategori') == 'AR.04.01.01' ? 'selected' : '' }}>04.01.01 - Monitoring Fisik dan Daftar</option>
                                                    <option value="AR.04.01.02" {{ old('kategori') == 'AR.04.01.02' ? 'selected' : '' }}>04.01.02 - Verifikasi Terhadap Daftar Arsip</option>
                                                    <option value="AR.04.01.03" {{ old('kategori') == 'AR.04.01.03' ? 'selected' : '' }}>04.01.03 - Menetapkan Status Arsip Statis</option>
                                                    <option value="AR.04.01.04" {{ old('kategori') == 'AR.04.01.04' ? 'selected' : '' }}>04.01.04 - Persetujuan untuk Penyerahan</option>
                                                    <option value="AR.04.01.05" {{ old('kategori') == 'AR.04.01.05' ? 'selected' : '' }}>04.01.05 - Penetapan Arsip yang Diserahkan</option>
                                                    <option value="AR.04.01.06" {{ old('kategori') == 'AR.04.01.06' ? 'selected' : '' }}>04.01.06 - Berita Acara Penyerahan Arsip</option>
                                                    <option value="AR.04.01.07" {{ old('kategori') == 'AR.04.01.07' ? 'selected' : '' }}>04.01.07 - Daftar Arsip yang Diserahkan</option>
                                                    <option value="AR.04.02" {{ old('kategori') == 'AR.04.02' ? 'selected' : '' }}>04.02 - Sejarah Lisan</option>
                                                    <option value="AR.04.02.01" {{ old('kategori') == 'AR.04.02.01' ? 'selected' : '' }}>04.02.01 - Administrasi Pelaksanaan Sejarah Lisan</option>
                                                    <option value="AR.04.02.02" {{ old('kategori') == 'AR.04.02.02' ? 'selected' : '' }}>04.02.02 - Hasil Wawancara Sejarah Lisan</option>
                                                    <option value="AR.04.02.02.01" {{ old('kategori') == 'AR.04.02.02.01' ? 'selected' : '' }}>04.02.02.01 - Berita Acara Wawancara Sejarah Lisan</option>
                                                    <option value="AR.04.02.02.02" {{ old('kategori') == 'AR.04.02.02.02' ? 'selected' : '' }}>04.02.02.02 - Laporan Kegiatan</option>
                                                    <option value="AR.04.02.02.03" {{ old('kategori') == 'AR.04.02.02.03' ? 'selected' : '' }}>04.02.02.03 - Hasil Wawancara (kaset atau CD) dan Transkrip</option>
                                                    <option value="AR.04.03" {{ old('kategori') == 'AR.04.03' ? 'selected' : '' }}>04.03 - Daftar Pencarian Arsip Statis</option>
                                                    <option value="AR.04.03.01" {{ old('kategori') == 'AR.04.03.01' ? 'selected' : '' }}>04.03.01 - Pengumuman</option>
                                                    <option value="AR.04.03.02" {{ old('kategori') == 'AR.04.03.02' ? 'selected' : '' }}>04.03.02 - Akuisisi Daftar Pencarian Arsip Statis</option>
                                                    <option value="AR.04.04" {{ old('kategori') == 'AR.04.04' ? 'selected' : '' }}>04.04 - Penghargaan dan Imbalan</option>
                                                    <option value="AR.04.05" {{ old('kategori') == 'AR.04.05' ? 'selected' : '' }}>04.05 - Pengolahan</option>
                                                    <option value="AR.04.05.01" {{ old('kategori') == 'AR.04.05.01' ? 'selected' : '' }}>04.05.01 - Menata Informasi</option>
                                                    <option value="AR.04.05.02" {{ old('kategori') == 'AR.04.05.02' ? 'selected' : '' }}>04.05.02 - Menata Fisik</option>
                                                    <option value="AR.04.05.03" {{ old('kategori') == 'AR.04.05.03' ? 'selected' : '' }}>04.05.03 - Menyusun Sarana Bantu Temu Balik: Daftar Arsip Statis, Inventaris Arsip Statis dan Petunjuk</option>
                                                    <option value="AR.04.06" {{ old('kategori') == 'AR.04.06' ? 'selected' : '' }}>04.06 - Preservasi Preventif</option>
                                                    <option value="AR.04.06.01" {{ old('kategori') == 'AR.04.06.01' ? 'selected' : '' }}>04.06.01 - Penyimpanan</option>
                                                    <option value="AR.04.06.02" {{ old('kategori') == 'AR.04.06.02' ? 'selected' : '' }}>04.06.02 - Pengendalian Hama Terpadu</option>
                                                    <option value="AR.04.06.03" {{ old('kategori') == 'AR.04.06.03' ? 'selected' : '' }}>04.06.03 - Reproduksi (Alih Media): Berita Acara Alih Media dan Daftar Arsip Alih Media</option>
                                                    <option value="AR.04.06.04" {{ old('kategori') == 'AR.04.06.04' ? 'selected' : '' }}>04.06.04 - Perencanaan dan Penanggulangan Bencana</option>
                                                    <option value="AR.04.07" {{ old('kategori') == 'AR.04.07' ? 'selected' : '' }}>04.07 - Preventif Kuratif</option>
                                                    <option value="AR.04.07.01" {{ old('kategori') == 'AR.04.07.01' ? 'selected' : '' }}>04.07.01 - Perawatan Arsip</option>
                                                    <option value="AR.04.07.02" {{ old('kategori') == 'AR.04.07.02' ? 'selected' : '' }}>04.07.02 - Laporan Hasil Pengujian Preservasi</option>
                                                    <option value="AR.04.08" {{ old('kategori') == 'AR.04.08' ? 'selected' : '' }}>04.08 - Autentikasi Arsip Statis</option>
                                                    <option value="AR.04.08.01" {{ old('kategori') == 'AR.04.08.01' ? 'selected' : '' }}>04.08.01 - Pembuktian Autentisitas</option>
                                                    <option value="AR.04.08.02" {{ old('kategori') == 'AR.04.08.02' ? 'selected' : '' }}>04.08.02 - Pendapat Tenaga Ahli</option>
                                                    <option value="AR.04.08.03" {{ old('kategori') == 'AR.04.08.03' ? 'selected' : '' }}>04.08.03 - Pengujian</option>
                                                    <option value="AR.04.08.04" {{ old('kategori') == 'AR.04.08.04' ? 'selected' : '' }}>04.08.04 - Penetapan Autentisitas Arsip Statis/Surat Pernyataan</option>
                                                    <option value="AR.04.09" {{ old('kategori') == 'AR.04.09' ? 'selected' : '' }}>04.09 - Akses Arsip Statis</option>
                                                    <option value="AR.04.09.01" {{ old('kategori') == 'AR.04.09.01' ? 'selected' : '' }}>04.09.01 - Layanan Arsip Statis</option>
                                                    <option value="AR.04.09.02" {{ old('kategori') == 'AR.04.09.02' ? 'selected' : '' }}>04.09.02 - Administrasi dan Proses Penyusunan Penerbitan Naskah Sumber</option>
                                                    <option value="AR.04.09.03" {{ old('kategori') == 'AR.04.09.03' ? 'selected' : '' }}>04.09.03 - Hasil Naskah Sumber Arsip</option>
                                                    <option value="AR.04.09.04" {{ old('kategori') == 'AR.04.09.04' ? 'selected' : '' }}>04.09.04 - Pameran Arsip</option>

                                                    <option value="AR.05" {{ old('kategori') == 'AR.05' ? 'selected' : '' }}>05 - Jasa Kearsipan</option>
                                                    <option value="AR.05.01" {{ old('kategori') == 'AR.05.01' ? 'selected' : '' }}>05.01 - Konsultasi Kearsipan</option>
                                                    <option value="AR.05.02" {{ old('kategori') == 'AR.05.02' ? 'selected' : '' }}>05.02 - Manual Kearsipan</option>
                                                    <option value="AR.05.03" {{ old('kategori') == 'AR.05.03' ? 'selected' : '' }}>05.03 - Penataan Arsip</option>
                                                    <option value="AR.05.04" {{ old('kategori') == 'AR.05.04' ? 'selected' : '' }}>05.04 - Otomasi Kearsipan</option>
                                                    <option value="AR.05.05" {{ old('kategori') == 'AR.05.05' ? 'selected' : '' }}>05.05 - Penyimpanan Arsip/Dokumen</option>
                                                    <option value="AR.05.06" {{ old('kategori') == 'AR.05.06' ? 'selected' : '' }}>05.06 - Perawatan Arsip/Dokumen</option>
                                                    <option value="AR.05.07" {{ old('kategori') == 'AR.05.07' ? 'selected' : '' }}>05.07 - Data Base Jasa Kearsipan</option>

                                                    <option value="AR.06" {{ old('kategori') == 'AR.06' ? 'selected' : '' }}>06 - Pembinaan dan Pengawasan Kearsipan</option>
                                                    <option value="AR.06.01" {{ old('kategori') == 'AR.06.01' ? 'selected' : '' }}>06.01 - Pembinaan Internal</option>
                                                    <option value="AR.06.01.01" {{ old('kategori') == 'AR.06.01.01' ? 'selected' : '' }}>06.01.01 - Kegiatan Pembinaan terhadap Perangkat Daerah</option>
                                                    <option value="AR.06.01.02" {{ old('kategori') == 'AR.06.01.02' ? 'selected' : '' }}>06.01.02 - Laporan Hasil Pembinaan Perangkat Daerah</option>
                                                    <option value="AR.06.02" {{ old('kategori') == 'AR.06.02' ? 'selected' : '' }}>06.02 - Pembinaan Eksternal</option>
                                                    <option value="AR.06.02.01" {{ old('kategori') == 'AR.06.02.01' ? 'selected' : '' }}>06.02.01 - Kegiatan Pembinaan terhadap BUMD, Orpol, Ormas, Swasta, dan Masyarakat</option>
                                                    <option value="AR.06.02.02" {{ old('kategori') == 'AR.06.02.02' ? 'selected' : '' }}>06.02.02 - Laporan Hasil Pembinaan Eksternal</option>
                                                    <option value="AR.06.03" {{ old('kategori') == 'AR.06.03' ? 'selected' : '' }}>06.03 - Pengawasan Internal</option>
                                                    <option value="AR.06.03.01" {{ old('kategori') == 'AR.06.03.01' ? 'selected' : '' }}>06.03.01 - Kegiatan Pengawasan terhadap Perangkat Daerah</option>
                                                    <option value="AR.06.03.02" {{ old('kategori') == 'AR.06.03.02' ? 'selected' : '' }}>06.03.02 - Laporan Hasil Audit Kearsipan Internal terhadap Perangkat Daerah</option>
                                                    <option value="AR.06.04" {{ old('kategori') == 'AR.06.04' ? 'selected' : '' }}>06.04 - Pengawasan Eksternal</option>
                                                    <option value="AR.06.04.01" {{ old('kategori') == 'AR.06.04.01' ? 'selected' : '' }}>06.04.01 - Kegiatan Pengawasan Kearsipan Eksternal terhadap BUMD, Orpol, Ormas, Swasta, dan Masyarakat</option>
                                                    <option value="AR.06.04.02" {{ old('kategori') == 'AR.06.04.02' ? 'selected' : '' }}>06.04.02 - Laporan Hasil Audit Kearsipan Eksternal</option>
                                                </optgroup>

                                                <!-- Kepegawaian - KP -->
                                                <optgroup label="Kepegawaian (KP)">
                                                    <!-- 01 - Persediaan Pegawai -->
                                                    <option value="KP.01" {{ old('kategori') == 'KP.01' ? 'selected' : '' }}>01 - Persediaan Pegawai</option>

                                                    <!-- 02 - Formasi Pegawai -->
                                                    <option value="KP.02" {{ old('kategori') == 'KP.02' ? 'selected' : '' }}>02 - Formasi Pegawai</option>
                                                    <option value="KP.02.01" {{ old('kategori') == 'KP.02.01' ? 'selected' : '' }}>02.01 - Usulan Unit Kerja</option>
                                                    <option value="KP.02.02" {{ old('kategori') == 'KP.02.02' ? 'selected' : '' }}>02.02 - Usulan Formasi</option>
                                                    <option value="KP.02.03" {{ old('kategori') == 'KP.02.03' ? 'selected' : '' }}>02.03 - Persetujuan/Penetapan Formasi</option>
                                                    <option value="KP.02.04" {{ old('kategori') == 'KP.02.04' ? 'selected' : '' }}>02.04 - Penetapan Formasi Khusus</option>

                                                    <!-- 03 - Pengadaan Formasi -->
                                                    <option value="KP.03" {{ old('kategori') == 'KP.03' ? 'selected' : '' }}>03 - Pengadaan Formasi</option>
                                                    <option value="KP.03.01" {{ old('kategori') == 'KP.03.01' ? 'selected' : '' }}>03.01 - Penerimaan</option>
                                                    <option value="KP.03.02" {{ old('kategori') == 'KP.03.02' ? 'selected' : '' }}>03.02 - Pengangkatan CPNS dan PNS</option>
                                                    <option value="KP.03.03" {{ old('kategori') == 'KP.03.03' ? 'selected' : '' }}>03.03 - Prajabatan</option>

                                                    <!-- 04 - Ujian Kenaikan Pangkat/Jabatan -->
                                                    <option value="KP.04" {{ old('kategori') == 'KP.04' ? 'selected' : '' }}>04 - Ujian Kenaikan Pangkat/Jabatan</option>
                                                    <option value="KP.04.01" {{ old('kategori') == 'KP.04.01' ? 'selected' : '' }}>04.01 - Ujian Penyasuaian Ijasah</option>
                                                    <option value="KP.04.02" {{ old('kategori') == 'KP.04.02' ? 'selected' : '' }}>04.02 - Ujian Dinas</option>


                                                    <!-- 05 - Ujian Kompetensi -->
                                                    <option value="KP.05" {{ old('kategori') == 'KP.05' ? 'selected' : '' }}>05 - Ujian Kompetensi</option>
                                                    <option value="KP.05.01" {{ old('kategori') == 'KP.05.01' ? 'selected' : '' }}>05.01 - Assessment Test Pegawai</option>
                                                    <option value="KP.05.02" {{ old('kategori') == 'KP.05.02' ? 'selected' : '' }}>05.02 - Talent Mapping/Pemetaan Pegawai</option>

                                                    <!-- 06 - Mutasi -->
                                                    <option value="KP.06" {{ old('kategori') == 'KP.06' ? 'selected' : '' }}>06 - Mutasi</option>
                                                    <option value="KP.06.01" {{ old('kategori') == 'KP.06.01' ? 'selected' : '' }}>06.01 - Kenaikan Pangkat/Golongan</option>
                                                    <option value="KP.06.02" {{ old('kategori') == 'KP.06.02' ? 'selected' : '' }}>06.02 - Kenaikan Gaji Berkala</option>
                                                    <option value="KP.06.03" {{ old('kategori') == 'KP.06.03' ? 'selected' : '' }}>06.03 - Penyesuaian Masa Kerja</option>
                                                    <option value="KP.06.04" {{ old('kategori') == 'KP.06.04' ? 'selected' : '' }}>06.04 - Penyesuaian Tunjangan Keluarga</option>
                                                    <option value="KP.06.05" {{ old('kategori') == 'KP.06.05' ? 'selected' : '' }}>06.05 - Penyesuaian Kelas Jabatan</option>
                                                    <option value="KP.06.06" {{ old('kategori') == 'KP.06.06' ? 'selected' : '' }}>06.06 - Alih Tugas</option>

                                                    <!-- 07 - Pengangkatan dan Pemberhentian Jabatan -->
                                                    <option value="KP.07" {{ old('kategori') == 'KP.07' ? 'selected' : '' }}>07 - Pengangkatan dan Pemberhentian Jabatan</option>
                                                    <option value="KP.07.01" {{ old('kategori') == 'KP.07.01' ? 'selected' : '' }}>07.01 - Pengangkatan Jabatan</option>
                                                    <option value="KP.07.02" {{ old('kategori') == 'KP.07.02' ? 'selected' : '' }}>07.02 - Pemberhentian Jabatan Struktural</option>

                                                    <!-- 08 - Pendelegasian Wewenang -->
                                                    <option value="KP.08" {{ old('kategori') == 'KP.08' ? 'selected' : '' }}>08 - Pendelegasian Wewenang</option>
                                                    <option value="KP.08.01" {{ old('kategori') == 'KP.08.01' ? 'selected' : '' }}>08.01 - Penjabat (Pj)</option>
                                                    <option value="KP.08.02" {{ old('kategori') == 'KP.08.02' ? 'selected' : '' }}>08.02 - Pelaksana Tugas (Plt)</option>
                                                    <option value="KP.08.03" {{ old('kategori') == 'KP.08.03' ? 'selected' : '' }}>08.03 - Pelaksana Harian (Plh)</option>

                                                    <!-- 09 - Pendidikan dan Pelatihan Pegawai -->
                                                    <option value="KP.09" {{ old('kategori') == 'KP.09' ? 'selected' : '' }}>09 - Pendidikan dan Pelatihan Pegawai</option>
                                                    <option value="KP.09.01" {{ old('kategori') == 'KP.09.01' ? 'selected' : '' }}>09.01 - Program Diploma</option>
                                                    <option value="KP.09.02" {{ old('kategori') == 'KP.09.02' ? 'selected' : '' }}>09.02 - Program Sarjana</option>
                                                    <option value="KP.09.03" {{ old('kategori') == 'KP.09.03' ? 'selected' : '' }}>09.03 - Program Pasca Sarjana</option>
                                                    <option value="KP.09.04" {{ old('kategori') == 'KP.09.04' ? 'selected' : '' }}>09.04 - Pendidikan dan Pelatihan Penjenjangan</option>
                                                    <option value="KP.09.05" {{ old('kategori') == 'KP.09.05' ? 'selected' : '' }}>09.05 - Kursus/Diklat Fungsional</option>
                                                    <option value="KP.09.06" {{ old('kategori') == 'KP.09.06' ? 'selected' : '' }}>09.06 - Kurus/Diklat Teknis</option>
                                                    <option value="KP.09.07" {{ old('kategori') == 'KP.09.07' ? 'selected' : '' }}>09.07 - Orientasi CPNS</option>

                                                    <!-- 10 - Administrasi Pegawai -->
                                                    <option value="KP.10" {{ old('kategori') == 'KP.10' ? 'selected' : '' }}>10 - Administrasi Pegawai</option>
                                                    <option value="KP.10.01" {{ old('kategori') == 'KP.10.01' ? 'selected' : '' }}>10.01 - Data/Keterangan Pegawai</option>
                                                    <option value="KP.10.02" {{ old('kategori') == 'KP.10.02' ? 'selected' : '' }}>10.02 - Kartu Pegawai</option>
                                                    <option value="KP.10.03" {{ old('kategori') == 'KP.10.03' ? 'selected' : '' }}>10.03 - Karis/Karsu</option>
                                                    <option value="KP.10.04" {{ old('kategori') == 'KP.10.04' ? 'selected' : '' }}>10.04 - Kartu Taspen</option>
                                                    <option value="KP.10.05" {{ old('kategori') == 'KP.10.05' ? 'selected' : '' }}>10.05 - Kartu Jaminan Kesehatan</option>
                                                    <option value="KP.10.06" {{ old('kategori') == 'KP.10.06' ? 'selected' : '' }}>10.06 - Tanda Jasa</option>
                                                    <option value="KP.10.07" {{ old('kategori') == 'KP.10.07' ? 'selected' : '' }}>10.07 - Keterangan Penerimaan Pembiayaan Penghasilan Pegawai (KP4)</option>
                                                    <option value="KP.10.08" {{ old('kategori') == 'KP.10.08' ? 'selected' : '' }}>10.08 - Laporan Harta Kekayaan Penyelenggara Negara (LHKPN)</option>
                                                    <option value="KP.10.09" {{ old('kategori') == 'KP.10.09' ? 'selected' : '' }}>10.09 - Tunjangan Kinerja dan Uang Makan</option>

                                                    <!-- 11 - Cuti Pegawai -->
                                                    <option value="KP.11" {{ old('kategori') == 'KP.11' ? 'selected' : '' }}>11 - Cuti Pegawai</option>
                                                    <option value="KP.11.01" {{ old('kategori') == 'KP.11.01' ? 'selected' : '' }}>11.01 - Cuti Tahunan</option>
                                                    <option value="KP.11.02" {{ old('kategori') == 'KP.11.02' ? 'selected' : '' }}>11.02 - Cuti Besar</option>
                                                    <option value="KP.11.03" {{ old('kategori') == 'KP.11.03' ? 'selected' : '' }}>11.03 - Cuti Sakit</option>
                                                    <option value="KP.11.04" {{ old('kategori') == 'KP.11.04' ? 'selected' : '' }}>11.04 - Cuti Bersalin</option>
                                                    <option value="KP.11.05" {{ old('kategori') == 'KP.11.05' ? 'selected' : '' }}>11.05 - Cuti Karena Alasan Penting</option>
                                                    <option value="KP.11.06" {{ old('kategori') == 'KP.11.06' ? 'selected' : '' }}>11.06 - Cuti di Luar Tanggungan Negara</option>

                                                    <!-- 12 - Pembinaan Pegawai -->
                                                    <option value="KP.12" {{ old('kategori') == 'KP.12' ? 'selected' : '' }}>12 - Pembinaan Pegawai</option>
                                                    <option value="KP.12.01" {{ old('kategori') == 'KP.12.01' ? 'selected' : '' }}>12.01 - Penilaian Prestasi Kerja</option>
                                                    <option value="KP.12.02" {{ old('kategori') == 'KP.12.02' ? 'selected' : '' }}>12.02 - Sasaran Kerja Pegawai</option>
                                                    <option value="KP.12.03" {{ old('kategori') == 'KP.12.03' ? 'selected' : '' }}>12.03 - Pembinaan Mental</option>
                                                    <option value="KP.12.04" {{ old('kategori') == 'KP.12.04' ? 'selected' : '' }}>12.04 - Hukuman Disiplin</option>

                                                    <!-- 13 - Pembinaan Jabatan Fungsional -->
                                                    <option value="KP.13" {{ old('kategori') == 'KP.13' ? 'selected' : '' }}>13 - Pembinaan Jabatan Fungsional</option>
                                                    <option value="KP.13.01" {{ old('kategori') == 'KP.13.01' ? 'selected' : '' }}>13.01 - Pengangkatan Jabatan Fungsional Tertentu</option>
                                                    <option value="KP.13.02" {{ old('kategori') == 'KP.13.02' ? 'selected' : '' }}>13.02 - Kenaikan Jenjang Jabatan</option>
                                                    <option value="KP.13.03" {{ old('kategori') == 'KP.13.03' ? 'selected' : '' }}>13.03 - Pemindahan Jabatan Fungsional Tertentu</option>
                                                    <option value="KP.13.04" {{ old('kategori') == 'KP.13.04' ? 'selected' : '' }}>13.04 - Pengangkatan Jabatan Fungsional Umum</option>
                                                    <option value="KP.13.05" {{ old('kategori') == 'KP.13.05' ? 'selected' : '' }}>13.05 - Pemindahan Jabatan Fungsional Umum</option>
                                                    <option value="KP.13.06" {{ old('kategori') == 'KP.13.06' ? 'selected' : '' }}>13.06 - Pemberhentian</option>

                                                    <!-- 14 - Kesejahteraan -->
                                                    <option value="KP.14" {{ old('kategori') == 'KP.14' ? 'selected' : '' }}>14 - Kesejahteraan</option>
                                                    <option value="KP.14.01" {{ old('kategori') == 'KP.14.01' ? 'selected' : '' }}>14.01 - Kesehatan</option>
                                                    <option value="KP.14.02" {{ old('kategori') == 'KP.14.02' ? 'selected' : '' }}>14.02 - Rekreasi/Kesenian/Olahraga</option>
                                                    <option value="KP.14.03" {{ old('kategori') == 'KP.14.03' ? 'selected' : '' }}>14.03 - Bantuan Sosial</option>
                                                    <option value="KP.14.04" {{ old('kategori') == 'KP.14.04' ? 'selected' : '' }}>14.04 - Perumahan</option>

                                                    <!-- 15 - Pemberhentian Pegawai -->
                                                    <option value="KP.15" {{ old('kategori') == 'KP.15' ? 'selected' : '' }}>15 - Pemberhentian Pegawai</option>
                                                    <option value="KP.15.01" {{ old('kategori') == 'KP.15.01' ? 'selected' : '' }}>15.01 - Dengan Hormat</option>
                                                    <option value="KP.15.02" {{ old('kategori') == 'KP.15.02' ? 'selected' : '' }}>15.02 - Tidak dengan Hormat</option>

                                                    <!-- 16 - Pemberhentian dan Penetapan Pensiun -->
                                                    <option value="KP.16" {{ old('kategori') == 'KP.16' ? 'selected' : '' }}>16 - Pemberhentian dan Penetapan Pensiun Pegawai/Janda/Duda/PNS yang Tewas</option>

                                                    <!-- 17 - Perselisihan/Sengketa Pegawai -->
                                                    <option value="KP.17" {{ old('kategori') == 'KP.17' ? 'selected' : '' }}>17 - Perselisihan/Sengketa Pegawai</option>

                                                    <!-- 18 - Organisasi Non Kedinasan -->
                                                    <option value="KP.18" {{ old('kategori') == 'KP.18' ? 'selected' : '' }}>18 - Organisasi Non Kedinasan</option>
                                                    <option value="KP.18.01" {{ old('kategori') == 'KP.18.01' ? 'selected' : '' }}>18.01 - KORPRI</option>
                                                    <option value="KP.18.02" {{ old('kategori') == 'KP.18.02' ? 'selected' : '' }}>18.02 - Dharma Wanita</option>
                                                    <option value="KP.18.03" {{ old('kategori') == 'KP.18.03' ? 'selected' : '' }}>18.03 - Koperasi</option>
                                                    <option value="KP.18.04" {{ old('kategori') == 'KP.18.04' ? 'selected' : '' }}>18.04 - Lain-lain</option>
                                                </optgroup>

                                                <!-- Kerumahtanggaan - RT -->
                                                <optgroup label="Kerumahtanggaan (RT)">
                                                    <!-- 01 - Perjalanan Dinas Pimpinan -->
                                                    <option value="RT.01" {{ old('kategori') == 'RT.01' ? 'selected' : '' }}>01 - Perjalanan Dinas Pimpinan</option>
                                                    <option value="RT.01.01" {{ old('kategori') == 'RT.01.01' ? 'selected' : '' }}>01.01 - Dalam Negeri</option>
                                                    <option value="RT.01.02" {{ old('kategori') == 'RT.01.02' ? 'selected' : '' }}>01.02 - Luar Negeri</option>

                                                    <!-- 02 - Rapat Pimpinan -->
                                                    <option value="RT.02" {{ old('kategori') == 'RT.02' ? 'selected' : '' }}>02 - Rapat Pimpinan</option>
                                                    <option value="RT.02.01" {{ old('kategori') == 'RT.02.01' ? 'selected' : '' }}>02.01 - Sarana dan Prasarana</option>
                                                    <option value="RT.02.02" {{ old('kategori') == 'RT.02.02' ? 'selected' : '' }}>02.02 - Jamuan Rapat</option>

                                                    <!-- 03 - Kantor -->
                                                    <option value="RT.03" {{ old('kategori') == 'RT.03' ? 'selected' : '' }}>03 - Kantor</option>
                                                    <option value="RT.03.01" {{ old('kategori') == 'RT.03.01' ? 'selected' : '' }}>03.01 - Pemeliharaan Gedung</option>
                                                    <option value="RT.03.02" {{ old('kategori') == 'RT.03.02' ? 'selected' : '' }}>03.02 - Perlengkapan Kantor</option>
                                                    <option value="RT.03.03" {{ old('kategori') == 'RT.03.03' ? 'selected' : '' }}>03.03 - Air, Listrik dan Telekomunikasi</option>
                                                    <option value="RT.03.04" {{ old('kategori') == 'RT.03.04' ? 'selected' : '' }}>03.04 - Keamanan Kantor</option>
                                                    <option value="RT.03.05" {{ old('kategori') == 'RT.03.05' ? 'selected' : '' }}>03.05 - Kebersihan Kantor</option>
                                                    <option value="RT.03.06" {{ old('kategori') == 'RT.03.06' ? 'selected' : '' }}>03.06 - Jamuan Tamu</option>
                                                    <option value="RT.03.07" {{ old('kategori') == 'RT.03.07' ? 'selected' : '' }}>03.07 - Halaman dan Taman</option>

                                                    <!-- 05 - Fasilitas Pimpinan -->
                                                    <option value="RT.05" {{ old('kategori') == 'RT.05' ? 'selected' : '' }}>05 - Fasilitas Pimpinan</option>
                                                    <option value="RT.05.01" {{ old('kategori') == 'RT.05.01' ? 'selected' : '' }}>05.01 - Kendaraan Dinas</option>
                                                    <option value="RT.05.02" {{ old('kategori') == 'RT.05.02' ? 'selected' : '' }}>05.02 - Pengawalan dan Pengamanan</option>
                                                    <option value="RT.05.03" {{ old('kategori') == 'RT.05.03' ? 'selected' : '' }}>05.03 - Telekomunikasi</option>
                                                </optgroup>

                                                <!-- Traditional categories (maintaining backward compatibility) -->
                                                <optgroup label="Kategori Tradisional">
                                                    <option value="Surat Masuk" {{ old('kategori') == 'Surat Masuk' ? 'selected' : '' }}>Surat Masuk</option>
                                                    <option value="Surat Keluar" {{ old('kategori') == 'Surat Keluar' ? 'selected' : '' }}>Surat Keluar</option>
                                                    <option value="Dokumen Penting" {{ old('kategori') == 'Dokumen Penting' ? 'selected' : '' }}>Dokumen Penting</option>
                                                    <option value="Laporan" {{ old('kategori') == 'Laporan' ? 'selected' : '' }}>Laporan</option>
                                                    <option value="Lainnya" {{ old('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
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
                                            <input type="date" class="form-control @error('tanggal_arsip') is-invalid @enderror" id="tanggal_arsip" name="tanggal_arsip" value="{{ old('tanggal_arsip') }}" required>
                                            @error('tanggal_arsip')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                Anda dapat memasukkan tanggal dari tahun-tahun sebelumnya
                                            </small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label for="lokasi" class="form-control-label text-sm">Lokasi Penyimpanan</label>
                                            <input type="text" class="form-control @error('lokasi') is-invalid @enderror" id="lokasi" name="lokasi" value="{{ old('lokasi') }}">
                                            @error('lokasi')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                Lokasi penyimpanan fisik arsip (opsional)
                                            </small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label for="keterangan" class="form-control-label text-sm">Keterangan</label>
                                            <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
                                            @error('keterangan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- File Upload Section -->
                                <div class="form-group mb-4">
                                    <label for="file" class="form-control-label text-sm">Upload File</label>
                                    <div class="input-group">
                                        <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file">
                                        <button type="button" class="btn btn-outline-primary" id="extractButton" style="display: none;">
                                            <span id="extractButtonText">Ekstrak Data Dokumen</span>
                                            <span id="extractButtonSpinner" class="spinner-border spinner-border-sm ms-2" role="status" style="display: none;"></span>
                                        </button>
                                        @error('file')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">
                                        Format yang didukung: JPG, PNG, PDF, Excel (XLSX, XLS), Word (DOC, DOCX). Ukuran maksimal: 10MB<br>
                                        <span class="text-info">💡 Sistem akan otomatis mengekstrak nomor dokumen, tanggal, dan nama dokumen dari file yang diupload</span>
                                    </small>
                                    <div id="extractionResult" class="mt-2" style="display: none;">
                                        <div class="alert alert-success mb-0">
                                            <strong>✅ Data dokumen berhasil diekstrak!</strong>
                                            <div id="extractedInfo"></div>
                                        </div>
                                    </div>
                                    <div id="extractionError" class="mt-2" style="display: none;">
                                        <div class="alert alert-warning mb-0">
                                            <strong>⚠️ Tidak dapat mengekstrak data dokumen secara otomatis</strong>
                                            <div>Silakan masukkan data dokumen secara manual</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-4">
                                    <a href="{{ route('arsip.index') }}" class="btn btn-light me-3">Batal</a>
                                    <button type="submit" class="btn btn-dark">
                                        <span class="btn-inner--icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2">
                                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                                <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                                <polyline points="7 3 7 8 15 8"></polyline>
                                            </svg>
                                        </span>
                                        Simpan Data
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

    <!-- JavaScript for Document Number and Date Extraction -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('file');
        const kodeInput = document.getElementById('kode');
        const tanggalArsipInput = document.getElementById('tanggal_arsip');
        const namaDocumenInput = document.getElementById('nama_dokumen');
        const extractButton = document.getElementById('extractButton');
        const extractButtonText = document.getElementById('extractButtonText');
        const extractButtonSpinner = document.getElementById('extractButtonSpinner');
        const extractionResult = document.getElementById('extractionResult');
        const extractionError = document.getElementById('extractionError');
        const extractedInfo = document.getElementById('extractedInfo');

        // Show extract button when file is selected
        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                // Check if file type is supported for extraction
                const supportedTypes = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
                const fileExtension = file.name.split('.').pop().toLowerCase();

                if (supportedTypes.includes(fileExtension)) {
                    extractButton.style.display = 'block';
                    // Auto-extract on file selection
                    extractDocumentData();
                } else {
                    extractButton.style.display = 'none';
                }

                // Hide previous results
                extractionResult.style.display = 'none';
                extractionError.style.display = 'none';
            } else {
                extractButton.style.display = 'none';
                extractionResult.style.display = 'none';
                extractionError.style.display = 'none';
            }
        });

        // Manual extract button click
        extractButton.addEventListener('click', function() {
            extractDocumentData();
        });

        function extractDocumentData() {
            const file = fileInput.files[0];
            if (!file) {
                alert('Silakan pilih file terlebih dahulu');
                return;
            }

            // Show loading state
            extractButtonText.textContent = 'Mengekstrak...';
            extractButtonSpinner.style.display = 'inline-block';
            extractButton.disabled = true;

            // Hide previous results
            extractionResult.style.display = 'none';
            extractionError.style.display = 'none';

            // Create FormData
            const formData = new FormData();
            formData.append('file', file);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            // Make AJAX request
            fetch('{{ route("arsip.extract-number") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Reset button state
                extractButtonText.textContent = 'Ekstrak Data Dokumen';
                extractButtonSpinner.style.display = 'none';
                extractButton.disabled = false;                if (data.success && (data.documentNumber || data.documentDate || data.documentName)) {
                    // Show success message
                    extractionResult.style.display = 'block';
                    let infoHtml = '';

                    if (data.documentNumber) {
                        infoHtml += `<div><strong>Nomor Dokumen:</strong> ${data.documentNumber}</div>`;
                        // Auto-fill the kode field
                        kodeInput.value = data.documentNumber;
                        kodeInput.dispatchEvent(new Event('input', { bubbles: true }));

                        // Highlight the field to show it was auto-filled
                        kodeInput.style.backgroundColor = '#e8f5e8';
                        setTimeout(() => {
                            kodeInput.style.backgroundColor = '';
                        }, 2000);
                    }

                    if (data.documentDate) {
                        infoHtml += `<div><strong>Tanggal Dokumen:</strong> ${data.documentDate}</div>`;
                        // Auto-fill the tanggal_arsip field
                        tanggalArsipInput.value = data.documentDate;
                        tanggalArsipInput.dispatchEvent(new Event('input', { bubbles: true }));

                        // Highlight the field to show it was auto-filled
                        tanggalArsipInput.style.backgroundColor = '#e8f5e8';
                        setTimeout(() => {
                            tanggalArsipInput.style.backgroundColor = '';
                        }, 2000);
                    }

                    if (data.documentName) {
                        infoHtml += `<div><strong>Nama Dokumen:</strong> ${data.documentName}</div>`;
                        // Auto-fill the nama_dokumen field
                        namaDocumenInput.value = data.documentName;
                        namaDocumenInput.dispatchEvent(new Event('input', { bubbles: true }));

                        // Highlight the field to show it was auto-filled
                        namaDocumenInput.style.backgroundColor = '#e8f5e8';
                        setTimeout(() => {
                            namaDocumenInput.style.backgroundColor = '';
                        }, 2000);
                    }

                    if (data.classification) {
                        let classificationInfo = data.classification.category || 'Tidak diketahui';

                        // Add code and description if available
                        if (data.classification.code) {
                            classificationInfo = `${data.classification.code}`;
                            if (data.classification.description) {
                                classificationInfo += ` (${data.classification.description})`;
                            }
                        }

                        infoHtml += `<div><strong>Klasifikasi:</strong> ${classificationInfo}</div>`;

                        // If we have a classification code, select it in the dropdown
                        if (data.classification.code) {
                            const kategoriSelect = document.getElementById('kategori');
                            const options = kategoriSelect.options;
                            for (let i = 0; i < options.length; i++) {
                                if (options[i].value === data.classification.code) {
                                    kategoriSelect.value = data.classification.code;
                                    kategoriSelect.dispatchEvent(new Event('change', { bubbles: true }));

                                    // Highlight the field to show it was auto-filled
                                    kategoriSelect.style.backgroundColor = '#e8f5e8';
                                    setTimeout(() => {
                                        kategoriSelect.style.backgroundColor = '';
                                    }, 2000);

                                    break;
                                }
                            }
                        }
                    }

                    extractedInfo.innerHTML = infoHtml;

                    // Show success notification
                    if (data.documentNumber && data.documentDate && data.documentName) {
                        showNotification('Berhasil mengekstrak nomor dokumen, tanggal, dan nama dokumen!', 'success');
                    } else if (data.documentNumber && data.documentDate) {
                        showNotification('Berhasil mengekstrak nomor dokumen dan tanggal!', 'success');
                    } else if (data.documentNumber && data.documentName) {
                        showNotification('Berhasil mengekstrak nomor dokumen dan nama dokumen!', 'success');
                    } else if (data.documentDate && data.documentName) {
                        showNotification('Berhasil mengekstrak tanggal dan nama dokumen!', 'success');
                    } else if (data.documentNumber) {
                        showNotification('Berhasil mengekstrak nomor dokumen!', 'success');
                    } else if (data.documentDate) {
                        showNotification('Berhasil mengekstrak tanggal dokumen!', 'success');
                    } else if (data.documentName) {
                        showNotification('Berhasil mengekstrak nama dokumen!', 'success');
                    }
                } else {
                    // Show error message
                    extractionError.style.display = 'block';
                    showNotification('Tidak dapat mengekstrak data dari dokumen ini', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);

                // Reset button state
                extractButtonText.textContent = 'Ekstrak Data Dokumen';
                extractButtonSpinner.style.display = 'none';
                extractButton.disabled = false;

                // Show error message
                extractionError.style.display = 'block';
                showNotification('Terjadi kesalahan saat mengekstrak data', 'error');
            });
        }

        // Show notification function
        function showNotification(message, type) {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
            notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            notification.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            // Add to body
            document.body.appendChild(notification);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 5000);
        }
    });
    </script>
</x-app-layout>
