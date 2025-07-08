<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-section table {
            width: 100%;
        }
        .info-section td {
            padding: 3px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            font-size: 9px;
        }
        th {
            background-color: #4472C4;
            color: white;
            font-weight: bold;
            text-align: center;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .status-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
            text-align: center;
        }
        .status-dipinjam { background-color: #007bff; color: white; }
        .status-dikembalikan { background-color: #28a745; color: white; }
        .status-terlambat { background-color: #dc3545; color: white; }
        .status-pending { background-color: #ffc107; color: black; }
        .confirmation-pending { background-color: #ffc107; color: black; }
        .confirmation-approved { background-color: #28a745; color: white; }
        .confirmation-rejected { background-color: #dc3545; color: white; }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 9px;
            color: #666;
        }
        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Sistem Arsip Digital</p>
        <p>Tanggal Cetak: {{ $date }}</p>
        @if($user->role === 'peminjam')
            <p>Peminjam: {{ $user->name }} ({{ $user->department }})</p>
        @endif
    </div>

    <div class="info-section">
        <table style="border: none;">
            <tr>
                <td style="border: none;"><strong>Total Peminjaman:</strong></td>
                <td style="border: none;">{{ $peminjamans->count() }} record</td>
                <td style="border: none;"><strong>Sedang Dipinjam:</strong></td>
                <td style="border: none;">{{ $peminjamans->where('status', 'dipinjam')->count() }} record</td>
            </tr>
            <tr>
                <td style="border: none;"><strong>Sudah Dikembalikan:</strong></td>
                <td style="border: none;">{{ $peminjamans->where('status', 'dikembalikan')->count() }} record</td>
                <td style="border: none;"><strong>Terlambat:</strong></td>
                <td style="border: none;">{{ $peminjamans->where('status', 'terlambat')->count() }} record</td>
            </tr>
        </table>
    </div>

    @if($peminjamans->count() > 0)
        <table>
            <thead>
                <tr>
                    <th width="3%">No</th>
                    <th width="8%">Kode Arsip</th>
                    <th width="15%">Nama Dokumen</th>
                    <th width="10%">Peminjam</th>
                    <th width="8%">Jabatan</th>
                    <th width="8%">Departemen</th>
                    <th width="8%">Tgl Pinjam</th>
                    <th width="8%">Batas Waktu</th>
                    <th width="8%">Tgl Kembali</th>
                    <th width="7%">Status</th>
                    <th width="7%">Konfirmasi</th>
                    <th width="5%">Durasi</th>
                    <th width="15%">Tujuan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($peminjamans as $index => $peminjaman)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $peminjaman->arsip->kode ?? 'N/A' }}</td>
                    <td>{{ $peminjaman->arsip->nama_dokumen ?? 'N/A' }}</td>
                    <td>{{ $peminjaman->peminjam }}</td>
                    <td>{{ $peminjaman->jabatan ?? '-' }}</td>
                    <td>{{ $peminjaman->departemen ?? '-' }}</td>
                    <td>{{ $peminjaman->tanggal_pinjam->format('d/m/Y') }}</td>
                    <td>{{ $peminjaman->batas_waktu->format('d/m/Y') }}</td>
                    <td>{{ $peminjaman->tanggal_kembali ? $peminjaman->tanggal_kembali->format('d/m/Y') : '-' }}</td>
                    <td>
                        @php
                            $statusClass = 'status-pending';
                            $statusText = 'Pending';

                            if($peminjaman->status === 'dipinjam') {
                                $statusClass = 'status-dipinjam';
                                $statusText = 'Dipinjam';
                            } elseif($peminjaman->status === 'dikembalikan') {
                                $statusClass = 'status-dikembalikan';
                                $statusText = 'Dikembalikan';
                            } elseif($peminjaman->status === 'terlambat') {
                                $statusClass = 'status-terlambat';
                                $statusText = 'Terlambat';
                            }
                        @endphp
                        <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                    </td>
                    <td>
                        @php
                            $confirmationClass = 'confirmation-pending';
                            $confirmationText = 'Pending';

                            if($peminjaman->confirmation_status === 'approved') {
                                $confirmationClass = 'confirmation-approved';
                                $confirmationText = 'Disetujui';
                            } elseif($peminjaman->confirmation_status === 'rejected') {
                                $confirmationClass = 'confirmation-rejected';
                                $confirmationText = 'Ditolak';
                            }
                        @endphp
                        <span class="status-badge {{ $confirmationClass }}">{{ $confirmationText }}</span>
                    </td>
                    <td style="text-align: center;">{{ $peminjaman->getDurasiPinjam() ?? '-' }}</td>
                    <td>{{ $peminjaman->tujuan_peminjaman ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            <p>Tidak ada data peminjaman arsip yang tersedia</p>
        </div>
    @endif

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }} WIB</p>
        <p>Dicetak oleh: {{ $user->name }} ({{ $user->role }})</p>
    </div>
</body>
</html>
