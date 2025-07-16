<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Riwayat Pemusnahan Arsip</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #4F46E5;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            color: #4F46E5;
            font-size: 18px;
        }
        .header h2 {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 14px;
            font-weight: normal;
        }
        .meta-info {
            margin-bottom: 20px;
            text-align: right;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10px;
        }
        th {
            background-color: #4F46E5;
            color: white;
            padding: 8px 5px;
            text-align: center;
            border: 1px solid #ddd;
            font-weight: bold;
        }
        td {
            padding: 6px 5px;
            border: 1px solid #ddd;
            text-align: left;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .summary {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-left: 4px solid #4F46E5;
        }
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            color: white;
        }
        .badge-danger {
            background-color: #dc3545;
        }
        .badge-info {
            background-color: #17a2b8;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN RIWAYAT PEMUSNAHAN ARSIP</h1>
        <h2>Sistem Manajemen Arsip Elektronik</h2>
        @if($destructions->count() > 0)
            @php
                $unitKerjaList = $destructions->map(function($d) {
                    return $d->user->department ?? $d->user->name ?? 'N/A';
                })->unique()->filter(function($value) {
                    return $value !== 'N/A';
                })->values();
            @endphp
            @if($unitKerjaList->count() > 0)
                <p style="margin: 10px 0 0 0; font-size: 12px; color: #333; font-style: italic;">
                    Dimusnahkan oleh: {{ $unitKerjaList->implode(', ') }}
                </p>
            @endif
        @endif
    </div>

    <div class="meta-info">
        <strong>Tanggal Laporan:</strong> {{ date('d/m/Y H:i') }} WIB<br>
        <strong>Total Arsip Dimusnahkan:</strong> {{ $destructions->count() }} arsip
    </div>

    <div class="summary">
        <h3 style="margin-top: 0;">Ringkasan Laporan</h3>
        <p>Laporan ini berisi daftar lengkap arsip yang telah dimusnahkan secara permanen dalam sistem manajemen arsip.
        Setiap arsip yang dimusnahkan telah melalui proses sesuai dengan ketentuan dan prosedur yang berlaku.</p>
    </div>

    @if($destructions->count() > 0)
    <table>
        <thead>
            <tr>
                <th style="width: 6%">No</th>
                <th style="width: 15%">Kode Arsip</th>
                <th style="width: 25%">Nama Dokumen</th>
                <th style="width: 12%">Kategori</th>
                <th style="width: 12%">Tgl Arsip</th>
                <th style="width: 15%">Tgl Pemusnahan</th>
                <th style="width: 15%">Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($destructions as $index => $destruction)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $destruction->arsip->kode }}</td>
                <td>{{ $destruction->arsip->nama_dokumen }}</td>
                <td>{{ $destruction->arsip->kategori }}</td>
                <td class="text-center">{{ $destruction->arsip->tanggal_arsip ? $destruction->arsip->tanggal_arsip->format('d/m/Y') : '-' }}</td>
                <td class="text-center">{{ $destruction->destroyed_at->format('d/m/Y H:i') }}</td>
                <td>{{ Str::limit($destruction->destruction_notes, 80) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="text-center" style="padding: 50px; color: #666;">
        <h3>Tidak Ada Data</h3>
        <p>Belum ada arsip yang dimusnahkan dalam sistem.</p>
    </div>
    @endif

    <div class="footer">
        <p>Laporan ini dihasilkan secara otomatis oleh Sistem Manajemen Arsip Elektronik pada {{ date('d/m/Y H:i') }} WIB</p>
        <p>Dokumen ini bersifat rahasia dan hanya untuk keperluan internal organisasi</p>
    </div>
</body>
</html>
