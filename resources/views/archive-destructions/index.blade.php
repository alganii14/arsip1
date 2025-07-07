<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-12">
                    <div class="card card-background card-background-after-none align-items-start mt-4 mb-5">
                        <div class="full-background" style="background-image: url('../assets/img/header-blue-purple.jpg')"></div>
                        <div class="card-body text-start p-4 w-100">
                            <h3 class="text-white mb-2">Riwayat Pemusnahan</h3>
                            <p class="mb-4 font-weight-semibold text-white">
                                Daftar arsip yang telah dimusnahkan secara permanen
                            </p>
                            <div class="d-flex">
                                <a href="{{ route('jre.index') }}" class="btn btn-outline-white btn-blur btn-icon d-flex align-items-center mb-0">
                                    <span class="btn-inner--icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block me-2">
                                            <path d="M19 12H5M12 19l-7-7 7-7"/>
                                        </svg>
                                    </span>
                                    <span class="btn-inner--text">Kembali ke JRE</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border shadow-xs">
                        <div class="card-header border-bottom pb-0">
                            <div class="d-sm-flex align-items-center">
                                <div>
                                    <h6 class="font-weight-semibold text-lg mb-0">Daftar Arsip yang Telah Dimusnahkan</h6>
                                    <p class="text-sm">Arsip yang telah dimusnahkan secara permanen dan tidak dapat dipulihkan</p>
                                </div>
                                <div class="ms-auto d-flex">
                                    <div class="input-group w-sm-25 ms-auto">
                                        <span class="input-group-text text-body">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16px" height="16px" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"></path>
                                            </svg>
                                        </span>
                                        <input type="text" class="form-control" placeholder="Cari arsip yang dimusnahkan...">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 py-0">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7">Kode Arsip</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Nama Dokumen</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Metode Pemusnahan</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Tanggal Pemusnahan</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Petugas</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($destructions as $destruction)
                                        <tr>
                                            <td class="ps-2">
                                                <p class="text-sm font-weight-semibold mb-0">{{ $destruction->arsip->kode }}</p>
                                            </td>
                                            <td>
                                                <p class="text-sm font-weight-semibold mb-0">{{ $destruction->arsip->nama_dokumen }}</p>
                                                <p class="text-xs text-secondary mb-0">{{ $destruction->arsip->kategori }}</p>
                                            </td>
                                            <td>
                                                <span class="badge badge-sm bg-danger text-white">
                                                    {{ $destruction->destruction_method_text }}
                                                </span>
                                            </td>
                                            <td>
                                                <p class="text-sm font-weight-normal mb-0">{{ $destruction->destroyed_at->format('d/m/Y H:i') }}</p>
                                            </td>
                                            <td>
                                                <p class="text-sm font-weight-normal mb-0">{{ $destruction->user->name }}</p>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <a href="{{ route('archive-destructions.show', $destruction->id) }}" class="btn btn-sm btn-info me-2">
                                                        <i class="fas fa-eye me-1"></i> Detail
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                <div class="text-center">
                                                    <i class="fas fa-fire fa-3x text-muted mb-3"></i>
                                                    <p class="text-muted mb-0">Belum ada arsip yang dimusnahkan</p>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <x-app.footer />
        </div>
    </main>
</x-app-layout>
