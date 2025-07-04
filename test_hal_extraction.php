<?php

require_once 'vendor/autoload.php';

use App\Helpers\DocumentNumberExtractor;

echo "=== Testing HAL Field Document Name Extraction ===\n\n";

// Test content dengan format yang sesuai dengan dokumen asli
$testContent = "PEMERINTAH KOTA BANDUNG
BADAN KEPEGAWAIAN DAN PENGEMBANGAN
SUMBER DAYA MANUSIA
Jl. Wastukancana No.2, Babakan Ciamis, Sumur Bandung, Kota Bandung,
Jawa Barat 40117 Telp. 02224234793, Fax 02224234793
e – mail : bkpsdm@bandung.go.id

                                                    Bandung,  20 November 2024

Nomor   : B/KP.06.01/11035-BKPSDM/XI/
          2024
Sifat   : Biasa                      Yth.  Kepala Perangkat Daerah
Lampiran: -                               (Daftar Terlampir)
Hal     : Penyerahan SK Kenaikan Pangkat
          Periode Desember 2024

        Disampaikan     dengan      hormat,     sehubungan     telah
terselesaikannya proses kenaikan pangkat periode Desember 2024
dengan terbitnya Persetujuan Teknis Kenaikan Pangkat yang";

// Test dengan konten page 2 yang berbeda
$page2Content = "DAFTAR UNDANGAN

1. SEKRETARIAT DAERAH
2. INSPEKTORAT DAERAH
3. BADAN KEPEGAWAIAN DAN PENGEMBANGAN SUMBER DAYA MANUSIA
4. BADAN PENDAPATAN DAERAH
5. DINAS ARSIP DAN PERPUSTAKAAN
6. DINAS KEBAKARAN DAN PENANGGULANGAN BENCANA
7. DINAS KEBUDAYAAN DAN PARIWISATA
8. DINAS KESEHATAN
9. DINAS KETAHANAN PANGAN DAN PERTANIAN
10. DINAS KETENAGAKERJAAN
11. DINAS KOMUNIKASI DAN INFORMATIKA
12. DINAS PEMBERDAYAAN PEREMPUAN DAN PERLINDUNGAN ANAK
13. DINAS PEMUDA DAN OLAHRAGA
14. DINAS PENDIDIKAN
15. DINAS PENGENDALIAN PENDUDUK DAN KELUARGA BERENCANA";

echo "=== Testing Document Name Extraction from Page 1 ===\n";
$documentName = DocumentNumberExtractor::findDocumentName($testContent);
echo "Extracted Document Name: " . ($documentName ?? 'NULL') . "\n";

echo "\n=== Testing Document Name Extraction from Page 2 ===\n";
$documentName2 = DocumentNumberExtractor::findDocumentName($page2Content);
echo "Extracted Document Name from Page 2: " . ($documentName2 ?? 'NULL') . "\n";

echo "\n=== Testing Combined Content (Page 1 + Page 2) ===\n";
$combinedContent = $testContent . "\n\n" . $page2Content;
$documentNameCombined = DocumentNumberExtractor::findDocumentName($combinedContent);
echo "Extracted Document Name from Combined: " . ($documentNameCombined ?? 'NULL') . "\n";

echo "\n=== Testing Multi-line Hal Field Pattern ===\n";
$multilineHal = "Hal     : Penyerahan SK Kenaikan Pangkat
          Periode Desember 2024";
$multilineDocName = DocumentNumberExtractor::findDocumentName($multilineHal);
echo "Multi-line Hal extraction: " . ($multilineDocName ?? 'NULL') . "\n";

echo "\n=== Results Analysis ===\n";
if ($documentName) {
    echo "✅ Successfully extracted document name from Page 1: '$documentName'\n";
} else {
    echo "❌ Failed to extract document name from Page 1\n";
}

if ($documentName2) {
    echo "⚠️  Extracted document name from Page 2 (should be NULL): '$documentName2'\n";
} else {
    echo "✅ Correctly found no document name in Page 2\n";
}

if ($documentNameCombined) {
    echo "✅ Successfully extracted document name from combined content: '$documentNameCombined'\n";
} else {
    echo "❌ Failed to extract document name from combined content\n";
}

if ($multilineDocName) {
    echo "✅ Successfully extracted multi-line Hal field: '$multilineDocName'\n";
} else {
    echo "❌ Failed to extract multi-line Hal field\n";
}

echo "\n=== Test Complete ===\n";
echo "The system should now properly extract document names from the 'Hal' field,\n";
echo "even when it spans multiple lines, and prioritize the first page content.\n";
