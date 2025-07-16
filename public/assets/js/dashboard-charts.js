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
@if(auth()->user()->role === 'peminjam')
    // Dataset untuk peminjam (hanya arsip dan peminjaman)
    statsData.daily.datasets = [
        {
            label: 'Arsip',
            data: @json($statsDaily['arsip'] ?? []),
            borderColor: '#5e72e4',
            backgroundColor: '#5e72e4',
            tension: 0.4
        },
        {
            label: 'Peminjaman',
            data: @json($statsDaily['peminjaman'] ?? []),
            borderColor: '#fb6340',
            backgroundColor: '#fb6340',
            tension: 0.4
        }
    ];
    
    statsData.monthly.datasets = [
        {
            label: 'Arsip',
            data: @json($statsMonthly['arsip'] ?? []),
            borderColor: '#5e72e4',
            backgroundColor: '#5e72e4',
            tension: 0.4
        },
        {
            label: 'Peminjaman',
            data: @json($statsMonthly['peminjaman'] ?? []),
            borderColor: '#fb6340',
            backgroundColor: '#fb6340',
            tension: 0.4
        }
    ];
    
    statsData.yearly.datasets = [
        {
            label: 'Arsip',
            data: @json($statsYearly['arsip'] ?? []),
            borderColor: '#5e72e4',
            backgroundColor: '#5e72e4',
            tension: 0.4
        },
        {
            label: 'Peminjaman',
            data: @json($statsYearly['peminjaman'] ?? []),
            borderColor: '#fb6340',
            backgroundColor: '#fb6340',
            tension: 0.4
        }
    ];
@else
    // Dataset untuk admin/petugas (lengkap)
    statsData.daily.datasets = [
        {
            label: 'Arsip',
            data: @json($statsDaily['arsip'] ?? []),
            borderColor: '#5e72e4',
            backgroundColor: '#5e72e4',
            tension: 0.4
        },
        {
            label: 'Pengguna',
            data: @json($statsDaily['users'] ?? []),
            borderColor: '#2dce89',
            backgroundColor: '#2dce89',
            tension: 0.4
        },
        {
            label: 'Peminjaman',
            data: @json($statsDaily['peminjaman'] ?? []),
            borderColor: '#fb6340',
            backgroundColor: '#fb6340',
            tension: 0.4
        },
        {
            label: 'Arsip JRE',
            data: @json($statsDaily['jre'] ?? []),
            borderColor: '#11cdef',
            backgroundColor: '#11cdef',
            tension: 0.4
        },
        {
            label: 'Pemusnahan',
            data: @json($statsDaily['destruction'] ?? []),
            borderColor: '#f5365c',
            backgroundColor: '#f5365c',
            tension: 0.4
        }
    ];
    
    statsData.monthly.datasets = [
        {
            label: 'Arsip',
            data: @json($statsMonthly['arsip'] ?? []),
            borderColor: '#5e72e4',
            backgroundColor: '#5e72e4',
            tension: 0.4
        },
        {
            label: 'Pengguna',
            data: @json($statsMonthly['users'] ?? []),
            borderColor: '#2dce89',
            backgroundColor: '#2dce89',
            tension: 0.4
        },
        {
            label: 'Peminjaman',
            data: @json($statsMonthly['peminjaman'] ?? []),
            borderColor: '#fb6340',
            backgroundColor: '#fb6340',
            tension: 0.4
        },
        {
            label: 'Arsip JRE',
            data: @json($statsMonthly['jre'] ?? []),
            borderColor: '#11cdef',
            backgroundColor: '#11cdef',
            tension: 0.4
        },
        {
            label: 'Pemusnahan',
            data: @json($statsMonthly['destruction'] ?? []),
            borderColor: '#f5365c',
            backgroundColor: '#f5365c',
            tension: 0.4
        }
    ];
    
    statsData.yearly.datasets = [
        {
            label: 'Arsip',
            data: @json($statsYearly['arsip'] ?? []),
            borderColor: '#5e72e4',
            backgroundColor: '#5e72e4',
            tension: 0.4
        },
        {
            label: 'Pengguna',
            data: @json($statsYearly['users'] ?? []),
            borderColor: '#2dce89',
            backgroundColor: '#2dce89',
            tension: 0.4
        },
        {
            label: 'Peminjaman',
            data: @json($statsYearly['peminjaman'] ?? []),
            borderColor: '#fb6340',
            backgroundColor: '#fb6340',
            tension: 0.4
        },
        {
            label: 'Arsip JRE',
            data: @json($statsYearly['jre'] ?? []),
            borderColor: '#11cdef',
            backgroundColor: '#11cdef',
            tension: 0.4
        },
        {
            label: 'Pemusnahan',
            data: @json($statsYearly['destruction'] ?? []),
            borderColor: '#f5365c',
            backgroundColor: '#f5365c',
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
