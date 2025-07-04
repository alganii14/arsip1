<?php
// Create a comprehensive test to verify the fix works in the Laravel context

// Mock the Laravel Log class to avoid errors
if (!class_exists('Log')) {
    class Log {
        public static function info($message) {
            // Do nothing - just prevent errors
        }

        public static function warning($message) {
            // Do nothing - just prevent errors
        }

        public static function error($message) {
            // Do nothing - just prevent errors
        }
    }
}

// Mock the Laravel Facades
if (!class_exists('Illuminate\Support\Facades\Log')) {
    class_alias('Log', 'Illuminate\Support\Facades\Log');
}

// Include the DocumentNumberExtractor class
require_once 'app/Helpers/DocumentNumberExtractor.php';

echo "=== COMPREHENSIVE TEST OF FIXED EXTRACTION ===\n";

// Test the problematic content that was causing issues
$problematicContent = "Surat Biasa
Nomor : 005/AR.01/XII/2024
Tanggal : 5 Desember 2024

Hal : Penyerahan SK Kenaikan Pangkat
      Periode Desember 2024

Yth. Kepala Perangkat Daerah
     (Daftar Terlampir)

Di
Bandung

Disampaikan dengan hormat, sehubungan telah terselesaikannya proses kenaikan pangkat periode Desember 2024...";

$result = \App\Helpers\DocumentNumberExtractor::findDocumentName($problematicContent);

echo "Result: '" . $result . "'\n";
echo "Expected: 'Penyerahan SK Kenaikan Pangkat Periode Desember 2024'\n";
echo "Match: " . ($result === 'Penyerahan SK Kenaikan Pangkat Periode Desember 2024' ? 'YES' : 'NO') . "\n";

// Test additional scenarios
$testCases = [
    "Subject field" => [
        "content" => "Subject : Annual Report 2024\n\nYth. Manager",
        "expected" => "Annual Report 2024"
    ],
    "Perihal field" => [
        "content" => "Perihal : Permohonan Izin\n\nKepada : Bapak Direktur",
        "expected" => "Permohonan Izin"
    ],
    "Multi-line with line breaks" => [
        "content" => "Hal : Laporan Keuangan\n      Semester I 2024\n\nYth. Kepala Bagian",
        "expected" => "Laporan Keuangan Semester I 2024"
    ]
];

echo "\n=== TESTING ADDITIONAL SCENARIOS ===\n";

foreach ($testCases as $scenario => $test) {
    echo "\nScenario: $scenario\n";
    $result = \App\Helpers\DocumentNumberExtractor::findDocumentName($test['content']);
    echo "Result: '" . $result . "'\n";
    echo "Expected: '" . $test['expected'] . "'\n";
    echo "Match: " . ($result === $test['expected'] ? 'YES' : 'NO') . "\n";
}

echo "\n=== TEST COMPLETED ===\n";
?>
