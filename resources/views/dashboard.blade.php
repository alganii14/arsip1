<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />

        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="d-md-flex align-items-center mb-3 mx-2">
                        <div class="mb-md-0 mb-3">
                            <h3 class="font-weight-bold mb-0">Hello, {{ auth()->user()->name }}</h3>
                            @if(auth()->user()->role === 'peminjam')
                                <p class="mb-0">Selamat datang di Sistem E-Arsip Seksi {{ auth()->user()->department ?? 'Kesejahteraan Sosial' }}!</p>
                            @else
                                <p class="mb-0">Selamat datang di Sistem E-Arsip!</p>
                            @endif
                        </div>


                    </div>
                </div>
            </div>

            <hr class="my-0">

            <!-- Welcome Section -->
            <div class="row my-4">
                <div class="col-md-12">
                    <div class="card shadow-xs border h-100">
                        <div class="card-header pb-0">
                            <div class="d-md-flex align-items-center mb-3">
                                <div>
                                    <h6 class="font-weight-semibold text-lg mb-0">Selamat Datang di Sistem E-Arsip</h6>
                                    @if(auth()->user()->role === 'peminjam')
                                        <p class="text-sm mb-0">Kelola arsip seksi {{ auth()->user()->department ?? 'Anda' }} dan pinjam arsip dari seksi lain</p>
                                    @else
                                        <p class="text-sm mb-0">Kelola arsip dokumen dengan mudah dan aman</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="font-weight-semibold mb-3">Fitur Utama:</h6>
                                    <ul class="list-group list-group-flush">
                                        @if(auth()->user()->role === 'peminjam')
                                            <li class="list-group-item border-0 px-0">
                                                <i class="fa fa-check text-success me-2"></i>
                                                Kelola arsip seksi Anda
                                            </li>
                                            <li class="list-group-item border-0 px-0">
                                                <i class="fa fa-check text-success me-2"></i>
                                                Sistem peminjaman arsip dari seksi lain
                                            </li>
                                            <li class="list-group-item border-0 px-0">
                                                <i class="fa fa-check text-success me-2"></i>
                                                Akses digital arsip yang dipinjam
                                            </li>
                                            <li class="list-group-item border-0 px-0">
                                                <i class="fa fa-check text-success me-2"></i>
                                                Pencarian dokumen yang cepat
                                            </li>
                                        @else
                                            <li class="list-group-item border-0 px-0">
                                                <i class="fa fa-check text-success me-2"></i>
                                                Penyimpanan dokumen digital yang aman
                                            </li>
                                            <li class="list-group-item border-0 px-0">
                                                <i class="fa fa-check text-success me-2"></i>
                                                Sistem peminjaman arsip
                                            </li>
                                            <li class="list-group-item border-0 px-0">
                                                <i class="fa fa-check text-success me-2"></i>
                                                Manajemen JRE (Jadwal Retensi Elektronik)
                                            </li>
                                            <li class="list-group-item border-0 px-0">
                                                <i class="fa fa-check text-success me-2"></i>
                                                Pencarian dokumen yang cepat
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="font-weight-semibold mb-3">Quick Actions:</h6>
                                    <div class="d-flex flex-column gap-2">
                                        <a href="{{ route('arsip.index') }}" class="btn btn-primary btn-sm">
                                            <i class="fa fa-archive me-1"></i>Lihat Arsip
                                        </a>
                                        @if(auth()->user()->role === 'peminjam')
                                            <a href="{{ route('peminjaman.index') }}" class="btn btn-info btn-sm">
                                                <i class="fa fa-hand-holding me-1"></i>Kelola Peminjaman
                                            </a>
                                        @else
                                            @if(auth()->user()->isAdmin() || auth()->user()->isPetugas())
                                                <a href="{{ route('arsip.create') }}" class="btn btn-success btn-sm">
                                                    <i class="fa fa-plus me-1"></i>Tambah Arsip Baru
                                                </a>
                                            @endif
                                            <a href="{{ route('peminjaman.index') }}" class="btn btn-info btn-sm">
                                                <i class="fa fa-hand-holding me-1"></i>Kelola Peminjaman
                                            </a>
                                            @if(auth()->user()->isAdmin() || auth()->user()->isPetugas())
                                                <a href="{{ route('jre.index') }}" class="btn btn-warning btn-sm">
                                                    <i class="fa fa-database me-1"></i>Manajemen JRE
                                                </a>
                                                <a href="{{ route('archive-destructions.index') }}" class="btn btn-danger btn-sm">
                                                    <i class="fa fa-fire me-1"></i>Riwayat Pemusnahan
                                                </a>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row">
                @php
                    if(auth()->user()->role === 'peminjam') {
                        // Kartu untuk Peminjam
                        $cards = [
                            ['title' => 'Total Arsip Seksi', 'icon' => 'archive', 'value' => $totalArsip, 'bg' => 'primary', 'note' => 'arsip yang Anda buat'],
                            ['title' => 'Total Pinjaman Anda', 'icon' => 'hand-holding', 'value' => $totalPeminjam, 'bg' => 'warning', 'note' => 'arsip yang Anda pinjam']
                        ];
                    } else {
                        // Kartu untuk Admin/Petugas
                        $cards = [
                            ['title' => 'Total Arsip', 'icon' => 'archive', 'value' => $totalArsip, 'bg' => 'primary', 'note' => 'dokumen tersimpan'],
                            ['title' => 'Total Pengguna', 'icon' => 'users', 'value' => $totalPengguna, 'bg' => 'success', 'note' => 'pengguna terdaftar'],
                            ['title' => 'Total Peminjam', 'icon' => 'hand-holding', 'value' => $totalPeminjam, 'bg' => 'warning', 'note' => 'pengguna peminjam'],
                            ['title' => 'Arsip di JRE', 'icon' => 'clock', 'value' => $totalArsipJre, 'bg' => 'info', 'note' => 'arsip masa retensi'],
                            ['title' => 'Arsip Dimusnahkan', 'icon' => 'fire', 'value' => $totalArsipMusnahkan, 'bg' => 'danger', 'note' => 'arsip dimusnahkan']
                        ];
                    }
                @endphp

                @foreach($cards as $index => $card)
                    <div class="col-xl-{{ auth()->user()->role === 'peminjam' ? '6' : (count($cards) == 5 ? '2' : '3') }} col-sm-6 mb-xl-0">
                        <div class="card border shadow-xs mb-4">
                            <div class="card-body text-start p-3 w-100">
                                <div class="icon icon-shape icon-sm bg-{{ $card['bg'] }} text-white text-center border-radius-sm d-flex align-items-center justify-content-center mb-3">
                                    <i class="fa fa-{{ $card['icon'] }}"></i>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="w-100">
                                            <p class="text-sm text-secondary mb-1">{{ $card['title'] }}</p>
                                            <h4 class="mb-2 font-weight-bold">{{ number_format($card['value']) }}</h4>
                                            <div class="d-flex align-items-center">
                                                <span class="text-sm text-{{ $card['bg'] }} font-weight-bolder">
                                                    <i class="fa fa-{{ $card['icon'] }} text-xs me-1"></i>
                                                </span>
                                                <span class="text-sm ms-1">{{ $card['note'] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <x-app.footer />
        </div>
    </main>
</x-app-layout>
