<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Pemindahan Arsip</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 12px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
            padding: 8px;
            font-size: 11px;
        }
        td {
            padding: 6px;
            font-size: 10px;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 11px;
        }
        .page-break {
            page-break-after: always;
        }
        .no-data {
            text-align: center;
            padding: 30px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">LAPORAN PEMINDAHAN ARSIP</div>
        <div class="subtitle">
            Tanggal Export: {{ now()->format('d-m-Y H:i:s') }}
            @if($search)
                - Filter: "{{ $search }}"
            @endif
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="10%">Kode Arsip</th>
                <th width="25%">Nama Arsip</th>
                <th width="7%">Tahun</th>
                <th width="8%">Jumlah</th>
                <th width="15%">Tingkat Perkembangan</th>
                <th width="20%">Keterangan</th>
                <th width="10%">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pemindahans as $index => $pemindahan)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $pemindahan->arsip->kode ?? $pemindahan->arsip->nomor_dokumen ?? 'N/A' }}</td>
                <td>{{ $pemindahan->arsip->nama_dokumen }}</td>
                <td style="text-align: center;">{{ $pemindahan->arsip->tanggal_arsip ? \Carbon\Carbon::parse($pemindahan->arsip->tanggal_arsip)->format('Y') : 'N/A' }}</td>
                <td style="text-align: center;">{{ $pemindahan->jumlah_folder }} folder</td>
                <td>{{ $pemindahan->tingkat_perkembangan_text }}</td>
                <td>{{ $pemindahan->keterangan }}</td>
                <td style="text-align: center;">{{ $pemindahan->created_at->format('d-m-Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="no-data">Tidak ada data pemindahan arsip</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini dicetak pada {{ now()->format('d-m-Y H:i:s') }}</p>
    </div>
</body>
</html>
