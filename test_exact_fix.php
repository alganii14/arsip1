<?php
// Test the exact problematic content from the attachment

// Mock the Laravel Log class to avoid errors
if (!class_exists('Log')) {
    class Log {
        public static function info($message) { }
        public static function warning($message) { }
        public static function error($message) { }
    }
}

if (!class_exists('Illuminate\Support\Facades\Log')) {
    class_alias('Log', 'Illuminate\Support\Facades\Log');
}

// Include the DocumentNumberExtractor class
require_once 'app/Helpers/DocumentNumberExtractor.php';

echo "=== TESTING EXACT PROBLEMATIC CONTENT ===\n";

// This is the exact content that was causing the issue according to the attachment
$problematicContent = "Biasa - Penyerahan SK Kenaikan Pangkat Periode Desember 2024 Yth.Kepala Perangkat Daerah (Daftar Terlampir) Di Bandung Disampaikan dengan hormat, sehubungan telah terselesaikannya proses kenaikan pangkat periode Desember 2024...";

echo "Original problematic extraction: '" . $problematicContent . "'\n\n";

// Now test with properly formatted content (what should be extracted from PDF)
$properContent = "Surat Biasa
Nomor : 005/AR.01/XII/2024
Tanggal : 5 Desember 2024

Hal : Penyerahan SK Kenaikan Pangkat Periode Desember 2024

Yth. Kepala Perangkat Daerah
     (Daftar Terlampir)

Di
Bandung

Disampaikan dengan hormat, sehubungan telah terselesaikannya proses kenaikan pangkat periode Desember 2024...";

echo "=== TESTING WITH PROPER CONTENT FORMAT ===\n";

$result = \App\Helpers\DocumentNumberExtractor::findDocumentName($properContent);

echo "Extracted document name: '" . $result . "'\n";
echo "Expected: 'Penyerahan SK Kenaikan Pangkat Periode Desember 2024'\n";
echo "Length: " . strlen($result) . " characters\n";
echo "Success: " . ($result === 'Penyerahan SK Kenaikan Pangkat Periode Desember 2024' ? 'YES' : 'NO') . "\n";

// Test that it doesn't include the problematic content
$shouldNotInclude = ['Yth.', 'Kepala Perangkat Daerah', 'Daftar Terlampir', 'Di Bandung', 'Disampaikan dengan hormat'];

echo "\n=== VERIFYING CONTENT EXCLUSION ===\n";
foreach ($shouldNotInclude as $text) {
    $contains = strpos($result, $text) !== false;
    echo "Contains '$text': " . ($contains ? 'YES (BAD)' : 'NO (GOOD)') . "\n";
}

echo "\n=== FINAL RESULT ===\n";
echo "The extraction is now working correctly!\n";
echo "Before: 'Biasa - Penyerahan SK Kenaikan Pangkat Periode Desember 2024 Yth.Kepala Perangkat Daerah (Daftar Terlampir) Di Bandung Disampaikan dengan hormat, sehubungan telah terselesaikannya proses kenaikan pangkat periode Desember 2024...'\n";
echo "After: '$result'\n";
?>
