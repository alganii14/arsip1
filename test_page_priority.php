<?php

require_once 'vendor/autoload.php';

use App\Helpers\DocumentNumberExtractor;

echo "=== Testing Page Priority for Document Name Extraction ===\n\n";

// Test dengan konten yang mengandung nama dokumen di halaman pertama dan kedua
$testContent = "
PEMERINTAH KOTA BANDUNG
BADAN KEPEGAWAIAN DAN PENGEMBANGAN SUMBER DAYA MANUSIA
Jl. Wastukancana No.2, Babakan Ciamis, Sumur Bandung, Kota Bandung,
Jawa Barat 40117 Telp. 02222423479, Fax 02222423479
e – mail : bkpsdm@bandung.go.id

Bandung, 20 November 2024

Nomor: B/KP.06.01/11035-BKPSDM/XI/2024
Sifat: Biasa
Lampiran: -
Hal: Penyerahan SK Kenaikan Pangkat Periode Desember 2024

Kepada
Yth. Kepala Perangkat Daerah
(Daftar Terlampir)
Di Bandung

Disampaikan dengan hormat, sehubungan telah terselesaikannya proses kenaikan pangkat periode Desember 2024 dengan terbitnya Persetujuan Teknis Kenaikan Pangkat yang menjadi kewenangan Pemerintah Kota Bandung oleh Kantor Regional III Badan Kepegawaian Negara, maka selanjutnya akan dilaksanakan Penyerahan Petikan SK Kenaikan Pangkat periode Desember 2024 yang akan dilaksanakan pada:

Hari/Tanggal: Jumat, 29 November 2024
Pukul: 09.30 WIB
Tempat: Auditorium Balai Kota Bandung, Jl. Wastukancana No. 2 Bandung

Sehubungan dengan hal tersebut di atas, bersama ini kami mohon kepada Bapak/Ibu Kepala Perangkat Daerah untuk dapat menghadirkan Kepala Sub Bagian Umum dan Kepegawaian di masing-masing SKPD pada waktu dan tempat yang telah ditetapkan.

Demikian disampaikan, atas perhatian dan kerjasamanya diucapkan terima kasih.

Kepala Badan,
[Signature]
Drs. H. ASEP JAMALUDIN, M.Si
NIP. 19631015 198503 1 007
";

echo "=== Testing Document Name Extraction ===\n";

// Test ekstraksi nama dokumen
$documentName = DocumentNumberExtractor::findDocumentName($testContent);
echo "Extracted Document Name: " . ($documentName ?? 'NULL') . "\n";

// Test ekstraksi nomor dokumen
$documentNumber = DocumentNumberExtractor::findDocumentNumber($testContent);
echo "Extracted Document Number: " . ($documentNumber ?? 'NULL') . "\n";

// Test ekstraksi tanggal
$documentDate = DocumentNumberExtractor::findDocumentDate($testContent);
echo "Extracted Document Date: " . ($documentDate ?? 'NULL') . "\n";

echo "\n=== Testing Multi-Page Scenario ===\n";

// Test dengan konten yang memiliki informasi berbeda di halaman 1 dan 2
$page1Content = "
PEMERINTAH KOTA BANDUNG
BADAN KEPEGAWAIAN DAN PENGEMBANGAN SUMBER DAYA MANUSIA

Nomor: B/KP.06.01/11035-BKPSDM/XI/2024
Hal: Penyerahan SK Kenaikan Pangkat Periode Desember 2024

Kepada Yth. Kepala Perangkat Daerah
";

$page2Content = "
DAFTAR UNDANGAN
1. SEKRETARIAT DAERAH
2. INSPEKTORAT DAERAH
3. BADAN KEPEGAWAIAN DAN PENGEMBANGAN SUMBER DAYA MANUSIA
4. BADAN PENDAPATAN DAERAH
5. DINAS ARSIP DAN PERPUSTAKAAN
";

echo "Page 1 Content Analysis:\n";
$page1Name = DocumentNumberExtractor::findDocumentName($page1Content);
echo "Page 1 Document Name: " . ($page1Name ?? 'NULL') . "\n";

echo "\nPage 2 Content Analysis:\n";
$page2Name = DocumentNumberExtractor::findDocumentName($page2Content);
echo "Page 2 Document Name: " . ($page2Name ?? 'NULL') . "\n";

echo "\nCombined Content Analysis:\n";
$combinedContent = $page1Content . "\n" . $page2Content;
$combinedName = DocumentNumberExtractor::findDocumentName($combinedContent);
echo "Combined Document Name: " . ($combinedName ?? 'NULL') . "\n";

echo "\n=== Test Results ===\n";
echo "✅ The system should now prioritize document names from the first page\n";
echo "✅ When multiple pages exist, first page content takes priority\n";
echo "✅ Only if no document name is found on first page, search full content\n";

echo "\n=== Test Complete ===\n";
