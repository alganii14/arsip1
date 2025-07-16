<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Pemindahan Arsip</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            font-size: 10pt;
            line-height: 1.3;
            margin: 0;
            padding: 10px;
            color: #000;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }

        .logo-section {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }

        .logo {
            display: table-cell;
            width: 60px;
            vertical-align: middle;
        }

        .logo img {
            width: 50px;
            height: auto;
        }

        .header-text {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            padding-left: 10px;
        }

        .header-text h1 {
            font-size: 14pt;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }

        .header-text h2 {
            font-size: 12pt;
            font-weight: bold;
            margin: 2px 0;
        }

        .header-text p {
            font-size: 9pt;
            margin: 1px 0;
        }

        .nomor-surat {
            text-align: center;
            margin: 15px 0;
            font-weight: bold;
            text-decoration: underline;
            font-size: 10pt;
        }

        .content {
            text-align: justify;
            margin: 10px 0;
        }

        .content p {
            margin-bottom: 8px;
            text-align: justify;
        }

        .content table {
            border-collapse: collapse;
            margin: 10px 0;
        }

        .content table td {
            padding: 3px 2px;
            vertical-align: top;
        }

        .date-location {
            text-align: right;
            margin: 10px 0;
        }

        .signature-section {
            margin-top: 20px;
            display: table;
            width: 100%;
        }

        .signature-left {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 10px;
        }

        .signature-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-left: 10px;
        }

        .signature-box {
            margin-top: 10px;
        }

        .signature-line {
            margin-top: 60px;
            border-bottom: 1px solid #000;
            width: 160px;
            margin-left: auto;
            margin-right: auto;
        }

        .content table {
            border-collapse: collapse;
            margin: 20px 0;
        }

        .content table td {
            padding: 8px 5px;
            vertical-align: top;
        }

        .date-location {
            text-align: right;
            margin-bottom: 20px;
        }

        @media print {
            body {
                margin: 0;
                padding: 10px;
                font-size: 10pt;
                line-height: 1.3;
            }
            
            .header {
                margin-bottom: 15px;
                padding-bottom: 8px;
            }
            
            .nomor-surat {
                margin: 15px 0;
                font-size: 10pt;
            }
            
            .content {
                margin: 10px 0;
            }
            
            .content p {
                margin-bottom: 8px;
            }
            
            .content table {
                margin: 12px 0;
            }
            
            .signature-section {
                margin-top: 20px;
            }
            
            .signature-box br {
                line-height: 0.8;
            }
        }
    </style>
</head>
<body>
    <!-- Header Surat -->
    <div class="header">
        <div class="logo-section">
            <div class="logo">
                <img src="{{ public_path('foto/kecamatan_cidadap.png') }}" alt="Logo Kecamatan">
            </div>
            <div class="header-text">
                <h1>PEMERINTAH KOTA BANDUNG</h1>
                <h2>KECAMATAN CIDADAP</h2>
                <p>Jalan Hegarmanah Tengah No. 1</p>
                <p>Telp. 2033396 Bandung</p>
            </div>
        </div>
    </div>

    <!-- Nomor Surat -->
    <div class="nomor-surat">
        BERITA ACARA PEMINDAHAN ARSIP<br>
        Nomor: {{ $nomor_surat }}
    </div>

    <!-- Isi Surat -->
    <div class="content">
        <p>Pada hari ini {{ \Carbon\Carbon::parse($tanggal_surat)->locale('id')->isoFormat('dddd') }} tanggal {{ \Carbon\Carbon::parse($tanggal_surat)->locale('id')->isoFormat('D') }} bulan {{ \Carbon\Carbon::parse($tanggal_surat)->locale('id')->isoFormat('MMMM') }} tahun {{ \Carbon\Carbon::parse($tanggal_surat)->locale('id')->isoFormat('Y') }}</p>
        <p>Yang bertanda tangan dibawah ini,</p>
        
        <table style="margin: 20px 0; width: 100%;">
            <tr>
                <td style="width: 15%; vertical-align: top;">Nama</td>
                <td style="width: 5%; vertical-align: top;">:</td>
                <td style="width: 80%; min-height: 20px; padding-bottom: 2px;">{{ $pihak_pertama['nama'] }}</td>
            </tr>
            <tr>
                <td style="vertical-align: top;">NIP</td>
                <td style="vertical-align: top;">:</td>
                <td style="min-height: 20px; padding-bottom: 2px;">{{ $pihak_pertama['nip'] }}</td>
            </tr>
            <tr>
                <td style="vertical-align: top;">Jabatan</td>
                <td style="vertical-align: top;">:</td>
                <td style="min-height: 20px; padding-bottom: 2px;">{{ $pihak_pertama['jabatan'] }}</td>
            </tr>
        </table>

        <p style="margin-top: 15px;">Dalam hal ini bertindak untuk dan atas nama {{ $pihak_pertama['nama'] }} yang selanjutnya disebut <strong>Pihak Pertama</strong>.</p>

        <table style="margin: 15px 0; width: 100%;">
            <tr>
                <td style="width: 15%; vertical-align: top;">Nama</td>
                <td style="width: 5%; vertical-align: top;">:</td>
                <td style="width: 80%; min-height: 12px; padding-bottom: 2px;">{{ $pihak_kedua['nama'] }}</td>
            </tr>
            <tr>
                <td style="vertical-align: top;">NIP</td>
                <td style="vertical-align: top;">:</td>
                <td style="min-height: 12px; padding-bottom: 2px;">{{ $pihak_kedua['nip'] }}</td>
            </tr>
            <tr>
                <td style="vertical-align: top;">Jabatan</td>
                <td style="vertical-align: top;">:</td>
                <td style="min-height: 12px; padding-bottom: 2px;">{{ $pihak_kedua['jabatan'] }}</td>
            </tr>
        </table>

        <p style="margin-top: 15px;">Dalam hal ini bertindak untuk dan atas nama {{ $pihak_kedua['nama'] }} yang selanjutnya disebut <strong>Pihak Kedua</strong>.</p>

        <p style="margin-top: 40px; text-align: justify;">Telah melaksanakan penilaian arsip berdasarkan Jadwal Retensi Arsip dan /atau berdasarkan Nilai guna, dan akan melaksanakan pemindahan arsip dari Unit Pengolah ke Unit Kearsipan sebanyak sebagaimana Daftar Arsip terlampir.</p>

        <p style="text-align: justify;">Berita acara ini dibuat dalam rangkap 2 (dua) dan PARA PIHAK menerima satu rangkap yang mempunyai kekuatan hukum sama.</p>
    </div>

    <!-- Tanggal dan Tempat -->
    <div class="date-location" style="margin-top: 20px;">
        Dibuat di Bandung, {{ \Carbon\Carbon::parse($tanggal_surat)->locale('id')->isoFormat('D MMMM Y') }}
    </div>

    <!-- Tanda Tangan -->
    <div class="signature-section">
        <div class="signature-left">
            <div class="signature-box">
                <p style="text-align: center;"><strong>PIHAK YANG MENERIMA</strong></p>
                <p style="text-align: center;">Pimpinan Unit Kearsipan</p>
                <br><br><br><br><br>
                <div style="text-align: center;">
                    <div style="border-bottom: 1px solid #000; width: 180px; margin: 0 auto;"></div>
                    <p style="margin-top: 6px; font-weight: bold;">{{ $pihak_kedua['nama'] }}</p>
                    <p style="margin-top: 2px;">NIP. {{ $pihak_kedua['nip'] }}</p>
                </div>
            </div>
        </div>
        <div class="signature-right">
            <div class="signature-box">
                <p style="text-align: center;"><strong>PIHAK YANG MEMINDAHKAN</strong></p>
                <p style="text-align: center;">Pimpinan Unit Pengolah</p>
                <br><br><br><br><br>
                <div style="text-align: center;">
                    <div style="border-bottom: 1px solid #000; width: 180px; margin: 0 auto;"></div>
                    <p style="margin-top: 6px; font-weight: bold;">{{ $pihak_pertama['nama'] }}</p>
                    <p style="margin-top: 2px;">NIP. {{ $pihak_pertama['nip'] }}</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
