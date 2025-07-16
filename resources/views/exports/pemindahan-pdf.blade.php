<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Pemindahan Arsip</title>
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
        .no-data {
            text-align: center;
            padding: 50px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PEMINDAHAN ARSIP</h1>
        <h2>Sistem Manajemen Arsip Elektronik</h2>
        @if($pemindahans->count() > 0)
            @php
                $unitKerjaList = $pemindahans->map(function($p) {
                    return $p->user->department ?? $p->user->name ?? 'N/A';
                })->unique()->filter(function($value) {
                    return $value !== 'N/A';
                })->values();
            @endphp
            @if($unitKerjaList->count() > 0)
                <p style="margin: 10px 0 0 0; font-size: 12px; color: #333; font-style: italic;">
                    Dipindahkan oleh: {{ $unitKerjaList->implode(', ') }}
                </p>
            @endif
        @endif
    </div>

    <div class="meta-info">
        <strong>Tanggal Laporan:</strong> {{ date('d/m/Y H:i') }} WIB<br>
        <strong>Total Pemindahan:</strong> {{ $pemindahans->count() }} arsip
        @if($search)
            <br><strong>Filter Pencarian:</strong> "{{ $search }}"
        @endif
    </div>

    <div class="summary">
        <h3 style="margin-top: 0;">Ringkasan Laporan</h3>
        <p>Laporan ini berisi daftar lengkap arsip yang telah dipindahkan dalam sistem manajemen arsip.
        Setiap pemindahan arsip telah melalui proses sesuai dengan ketentuan dan prosedur yang berlaku.</p>
        <p><strong>Catatan:</strong> Setiap pemindahan mencantumkan unit kerja yang bertanggung jawab melakukan pemindahan.</p>
    </div>

    @if($pemindahans->count() > 0)
    <table>
        <thead>
            <tr>
                <th style="width: 7%">No</th>
                <th style="width: 14%">Kode Arsip</th>
                <th style="width: 25%">Nama Arsip</th>
                <th style="width: 10%">Tahun</th>
                <th style="width: 10%">Jumlah</th>
                <th style="width: 16%">Tingkat Perkembangan</th>
                <th style="width: 14%">Tanggal</th>
                <th style="width: 14%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pemindahans as $index => $pemindahan)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $pemindahan->arsip->kode ?? $pemindahan->arsip->nomor_dokumen ?? 'N/A' }}</td>
                <td>{{ $pemindahan->arsip->nama_dokumen }}</td>
                <td class="text-center">{{ $pemindahan->arsip->tanggal_arsip ? \Carbon\Carbon::parse($pemindahan->arsip->tanggal_arsip)->format('Y') : 'N/A' }}</td>
                <td class="text-center">{{ $pemindahan->jumlah_folder }} folder</td>
                <td>{{ $pemindahan->tingkat_perkembangan_text }}</td>
                <td class="text-center">{{ $pemindahan->created_at->format('d/m/Y') }}</td>
                <td>{{ $pemindahan->keterangan ?: '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="text-center no-data">
        <h3>Tidak Ada Data</h3>
        <p>Belum ada pemindahan arsip dalam sistem.</p>
    </div>
    @endif

    <div class="footer">
        <p>Laporan ini dihasilkan secara otomatis oleh Sistem Manajemen Arsip Elektronik pada {{ date('d/m/Y H:i') }} WIB</p>
        <p>Dokumen ini bersifat rahasia dan hanya untuk keperluan internal organisasi</p>
    </div>
</body>
</html>
