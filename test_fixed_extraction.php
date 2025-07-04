<?php
// Test the fixed extraction logic

// Simulate the actual content that's causing issues
$testContent = "Surat Biasa
Nomor : 005/AR.01/XII/2024
Tanggal : 5 Desember 2024

Hal : Penyerahan SK Kenaikan Pangkat
      Periode Desember 2024

Yth. Kepala Perangkat Daerah
     (Daftar Terlampir)

Di
Bandung

Disampaikan dengan hormat, sehubungan telah terselesaikannya proses kenaikan pangkat periode Desember 2024...";

// Include the DocumentNumberExtractor class
require_once 'app/Helpers/DocumentNumberExtractor.php';

echo "=== TESTING FIXED EXTRACTION ===\n";

// Test the findDocumentName function
$result = \App\Helpers\DocumentNumberExtractor::findDocumentName($testContent);

echo "Extracted document name: '" . $result . "'\n";

// Test various scenarios
$testCases = [
    "Single line Hal" => "Hal : Permohonan Izin Cuti\n\nYth. Kepala Bagian",
    "Multi-line Hal" => "Hal : Penyerahan SK Kenaikan Pangkat\n      Periode Desember 2024\n\nYth. Kepala Perangkat Daerah",
    "With Kepada" => "Hal : Laporan Bulanan\n\nKepada : Bapak Direktur",
    "With empty line" => "Hal : Undangan Rapat\n\n\nIsi surat..."
];

echo "\n=== TESTING VARIOUS SCENARIOS ===\n";

foreach ($testCases as $scenario => $content) {
    echo "\nScenario: $scenario\n";
    echo "Content: " . str_replace("\n", "\\n", $content) . "\n";

    $result = \App\Helpers\DocumentNumberExtractor::findDocumentName($content);
    echo "Result: '" . $result . "'\n";
}
?>
