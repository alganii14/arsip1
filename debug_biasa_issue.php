<?php
// Test to reproduce the "Biasa -" issue

// Mock the Log class
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

echo "=== TESTING BIASA PREFIX ISSUE ===\n";

// Test content that might include "Biasa -"
$testContent = "PEMERINTAH KOTA BANDUNG
DINAS KOMUNIKASI DAN INFORMATIKA

SURAT BIASA
Nomor : 050/AR.02/I/2025
Tanggal : 3 Januari 2025

Hal : Undangan Sosialisasi Implementasi Teknis Tata Cara dan Penggunaan E-BMD

Yth. Kepala Perangkat Daerah
     Kota Bandung

Di
Tempat

Dalam rangka implementasi sistem E-BMD...";

echo "Test content:\n";
echo $testContent . "\n\n";

$result = \App\Helpers\DocumentNumberExtractor::findDocumentName($testContent);

echo "=== RESULTS ===\n";
echo "Extracted: '" . $result . "'\n";
echo "Expected: 'Undangan Sosialisasi Implementasi Teknis Tata Cara dan Penggunaan E-BMD'\n";
echo "Contains 'Biasa': " . (strpos($result, 'Biasa') !== false ? 'YES (BAD)' : 'NO (GOOD)') . "\n";

// Test another variation
echo "\n=== TESTING VARIATION ===\n";
$testContent2 = "Biasa - Undangan Sosialisasi Implementasi Teknis Tata Cara dan Penggunaan E-BMD";
echo "Direct test content: '" . $testContent2 . "'\n";

$result2 = \App\Helpers\DocumentNumberExtractor::findDocumentName($testContent2);
echo "Result: '" . $result2 . "'\n";
echo "Contains 'Biasa': " . (strpos($result2, 'Biasa') !== false ? 'YES (BAD)' : 'NO (GOOD)') . "\n";

// Test if it's coming from filename extraction
echo "\n=== TESTING IF FROM FILENAME ===\n";
$filename = "Biasa - Undangan Sosialisasi Implementasi Teknis Tata Cara dan Penggunaan E-BMD.pdf";
echo "Filename: '" . $filename . "'\n";

$result3 = \App\Helpers\DocumentNumberExtractor::findDocumentName($filename);
echo "Result: '" . $result3 . "'\n";
?>
