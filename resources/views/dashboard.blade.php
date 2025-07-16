<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />

        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="d-md-flex align-items-center mb-3 mx-2">
                        <div class="mb-md-0 mb-3">
                            <h3 class="font-weight-bold mb-0">Hello, {{ auth()->user()->name }}</h3>
                            @if(auth()->user()->role === 'unit_pengelola')
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
                                    @if(auth()->user()->role === 'unit_pengelola')
                                        <h6 class="font-weight-semibold text-lg mb-0">Selamat Datang Unit Pengelola</h6>
                                        <p class="text-sm mb-0">Kelola arsip {{ auth()->user()->department ?? 'departemen Anda' }} dan akses arsip dari departemen lain sesuai kebutuhan</p>
                                    @else
                                        <h6 class="font-weight-semibold text-lg mb-0">Selamat Datang di Sistem E-Arsip</h6>
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
                                        @if(auth()->user()->role === 'unit_pengelola')
                                            <li class="list-group-item border-0 px-0">
                                                <i class="fa fa-check text-success me-2"></i>
                                                Kelola arsip {{ auth()->user()->department ?? 'departemen' }} Anda
                                            </li>
                                            <li class="list-group-item border-0 px-0">
                                                <i class="fa fa-check text-success me-2"></i>
                                                Sistem peminjaman arsip antar departemen
                                            </li>
                                            <li class="list-group-item border-0 px-0">
                                                <i class="fa fa-check text-success me-2"></i>
                                                Akses digital arsip yang dipinjam
                                            </li>
                                            <li class="list-group-item border-0 px-0">
                                                <i class="fa fa-check text-success me-2"></i>
                                                Pencarian arsip lintas departemen
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
                                            <i class="fa fa-archive me-1"></i>Lihat Arsip Departemen
                                        </a>
                                        @if(auth()->user()->role === 'unit_pengelola')
                                            <a href="{{ route('peminjaman.index') }}" class="btn btn-info btn-sm">
                                                <i class="fa fa-hand-holding me-1"></i>Kelola Peminjaman
                                            </a>
                                            <a href="{{ route('arsip.create') }}" class="btn btn-success btn-sm">
                                                <i class="fa fa-plus me-1"></i>Tambah Arsip Baru
                                            </a>
                                            <a href="{{ route('peminjaman.create') }}" class="btn btn-warning btn-sm">
                                                <i class="fa fa-search me-1"></i>Cari Arsip Lain
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
                    if(auth()->user()->role === 'unit_pengelola') {
                        // Kartu untuk Unit Pengelola (Peminjam) - dengan warna yang berbeda
                        $cards = [
                            ['title' => 'Arsip Seksi Anda', 'icon' => 'folder-open', 'value' => $totalArsip, 'bg' => 'gradient-primary', 'note' => 'arsip seksi yang aktif'],
                            ['title' => 'Pinjaman Anda', 'icon' => 'hand-holding-heart', 'value' => $totalPeminjam, 'bg' => 'gradient-warning', 'note' => 'arsip yang Anda pinjam'],
                            ['title' => 'Menunggu Persetujuan', 'icon' => 'hourglass-half', 'value' => $totalPending, 'bg' => 'gradient-info', 'note' => 'arsip belum disetujui'],
                            ['title' => 'Arsip Tersedia', 'icon' => 'search-plus', 'value' => $totalArsipTersedia, 'bg' => 'gradient-success', 'note' => 'dari seksi lain']
                        ];
                    } else {
                        // Kartu untuk Unit Kerja (Admin/Petugas)
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
                    <div class="col-xl-{{ auth()->user()->role === 'unit_pengelola' ? '3' : (count($cards) == 5 ? '2' : '3') }} col-sm-6 mb-xl-0">
                        <div class="card border shadow-xs mb-4">
                            <div class="card-body text-start p-3 w-100">
                                <div class="icon icon-shape icon-sm bg-{{ str_contains($card['bg'], 'gradient') ? 'dark' : $card['bg'] }} text-white text-center border-radius-sm d-flex align-items-center justify-content-center mb-3">
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

            <!-- Statistics Charts -->
            <div class="row mb-5">
                <div class="col-lg-12">
                    <div class="card shadow-xs border">
                        <div class="card-header pb-0">
                            <div class="d-sm-flex align-items-center mb-3">
                                <div>
                                    <h6 class="font-weight-semibold text-lg mb-0">Statistik Data</h6>
                                    <p class="text-sm mb-sm-0 mb-2">Visualisasi data arsip dan aktivitas pengguna</p>
                                </div>
                                <div class="ms-auto d-flex">
                                    <button type="button" class="btn btn-sm btn-outline-secondary mb-0 me-2 chart-period" data-period="daily">Harian</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary mb-0 me-2 chart-period" data-period="monthly">Bulanan</button>
                                    <button type="button" class="btn btn-sm btn-outline-primary mb-0 chart-period active" data-period="yearly">Tahunan</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="chart">
                                <canvas id="statisticsChart" class="chart-canvas" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <x-app.footer />
        </div>
    </main>
    
    <!-- Script untuk Chart.js -->
    <script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>
    <script>
        // Inisialisasi data statistik untuk chart
        let statsData = {
            daily: {
                labels: @json($statsDaily['labels'] ?? []),
                datasets: []
            },
            monthly: {
                labels: @json($statsMonthly['labels'] ?? []),
                datasets: []
            },
            yearly: {
                labels: @json($statsYearly['labels'] ?? []),
                datasets: []
            }
        };

        // Persiapkan dataset sesuai dengan role pengguna
        @if(auth()->user()->role === 'unit_pengelola')
            // Dataset untuk unit pengelola (hanya arsip dan peminjaman)
            statsData.daily.datasets = [
                {
                    label: 'Arsip',
                    data: @json($statsDaily['arsip'] ?? []),
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#007bff',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    tension: 0.4
                },
                {
                    label: 'Peminjaman',
                    data: @json($statsDaily['peminjaman'] ?? []),
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#dc3545',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    tension: 0.4
                }
            ];
            
            statsData.monthly.datasets = [
                {
                    label: 'Arsip',
                    data: @json($statsMonthly['arsip'] ?? []),
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#007bff',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    tension: 0.4
                },
                {
                    label: 'Peminjaman',
                    data: @json($statsMonthly['peminjaman'] ?? []),
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#dc3545',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    tension: 0.4
                }
            ];
            
            statsData.yearly.datasets = [
                {
                    label: 'Arsip',
                    data: @json($statsYearly['arsip'] ?? []),
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#007bff',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    tension: 0.4
                },
                {
                    label: 'Peminjaman',
                    data: @json($statsYearly['peminjaman'] ?? []),
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#dc3545',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    tension: 0.4
                }
            ];
        @else
            // Dataset untuk admin/petugas (lengkap)
            statsData.daily.datasets = [
                {
                    label: 'Arsip',
                    data: @json($statsDaily['arsip'] ?? []),
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#007bff',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    tension: 0.4
                },
                {
                    label: 'Pengguna',
                    data: @json($statsDaily['users'] ?? []),
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#28a745',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    tension: 0.4
                },
                {
                    label: 'Peminjaman',
                    data: @json($statsDaily['peminjaman'] ?? []),
                    borderColor: '#fd7e14',
                    backgroundColor: 'rgba(253, 126, 20, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#fd7e14',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    tension: 0.4
                },
                {
                    label: 'Arsip JRE',
                    data: @json($statsDaily['jre'] ?? []),
                    borderColor: '#6f42c1',
                    backgroundColor: 'rgba(111, 66, 193, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#6f42c1',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    tension: 0.4
                },
                {
                    label: 'Pemusnahan',
                    data: @json($statsDaily['destruction'] ?? []),
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#dc3545',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    tension: 0.4
                }
            ];
            
            statsData.monthly.datasets = [
                {
                    label: 'Arsip',
                    data: @json($statsMonthly['arsip'] ?? []),
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#007bff',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    tension: 0.4
                },
                {
                    label: 'Pengguna',
                    data: @json($statsMonthly['users'] ?? []),
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#28a745',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    tension: 0.4
                },
                {
                    label: 'Peminjaman',
                    data: @json($statsMonthly['peminjaman'] ?? []),
                    borderColor: '#fd7e14',
                    backgroundColor: 'rgba(253, 126, 20, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#fd7e14',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    tension: 0.4
                },
                {
                    label: 'Arsip JRE',
                    data: @json($statsMonthly['jre'] ?? []),
                    borderColor: '#6f42c1',
                    backgroundColor: 'rgba(111, 66, 193, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#6f42c1',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    tension: 0.4
                },
                {
                    label: 'Pemusnahan',
                    data: @json($statsMonthly['destruction'] ?? []),
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#dc3545',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    tension: 0.4
                }
            ];
            
            statsData.yearly.datasets = [
                {
                    label: 'Arsip',
                    data: @json($statsYearly['arsip'] ?? []),
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#007bff',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    tension: 0.4
                },
                {
                    label: 'Pengguna',
                    data: @json($statsYearly['users'] ?? []),
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#28a745',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    tension: 0.4
                },
                {
                    label: 'Peminjaman',
                    data: @json($statsYearly['peminjaman'] ?? []),
                    borderColor: '#fd7e14',
                    backgroundColor: 'rgba(253, 126, 20, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#fd7e14',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    tension: 0.4
                },
                {
                    label: 'Arsip JRE',
                    data: @json($statsYearly['jre'] ?? []),
                    borderColor: '#6f42c1',
                    backgroundColor: 'rgba(111, 66, 193, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#6f42c1',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    tension: 0.4
                },
                {
                    label: 'Pemusnahan',
                    data: @json($statsYearly['destruction'] ?? []),
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#dc3545',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    tension: 0.4
                }
            ];
        @endif

        // Konfigurasi chart
        const chartConfig = {
            type: 'line',
            data: statsData.yearly, // Default ke tampilan tahunan
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            boxWidth: 10,
                            font: {
                                size: 11,
                                family: 'Open Sans',
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'white',
                        titleColor: '#5c5c5c',
                        bodyColor: '#5c5c5c',
                        borderColor: '#e9ecef',
                        borderWidth: 1,
                        usePointStyle: true,
                        boxWidth: 5,
                        boxHeight: 5,
                        boxPadding: 3,
                        cornerRadius: 5,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#e9ecef',
                            drawBorder: false,
                            borderDash: [2, 2]
                        },
                        ticks: {
                            stepSize: 1,
                            color: '#5c5c5c',
                            font: {
                                size: 11,
                                family: 'Open Sans',
                            }
                        }
                    },
                    x: {
                        grid: {
                            color: '#e9ecef',
                            drawBorder: false,
                            borderDash: [2, 2]
                        },
                        ticks: {
                            color: '#5c5c5c',
                            font: {
                                size: 11,
                                family: 'Open Sans',
                            }
                        }
                    }
                }
            }
        };

        // Inisialisasi chart
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('statisticsChart').getContext('2d');
            const statsChart = new Chart(ctx, chartConfig);
            
            // Handler untuk tombol periode
            document.querySelectorAll('.chart-period').forEach(button => {
                button.addEventListener('click', function() {
                    // Update status aktif pada tombol
                    document.querySelectorAll('.chart-period').forEach(btn => {
                        btn.classList.remove('btn-outline-primary', 'active');
                        btn.classList.add('btn-outline-secondary');
                    });
                    this.classList.remove('btn-outline-secondary');
                    this.classList.add('btn-outline-primary', 'active');
                    
                    // Update data chart sesuai periode yang dipilih
                    const period = this.getAttribute('data-period');
                    statsChart.data.labels = statsData[period].labels;
                    statsChart.data.datasets = statsData[period].datasets;
                    statsChart.update();
                });
            });
            
            // Set default ke yearly view
            document.querySelector('.chart-period[data-period="yearly"]').click();
        });
    </script>
</x-app-layout>
