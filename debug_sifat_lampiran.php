<?php
// Test to reproduce the Sifat and Lampiran extraction issue

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

echo "=== DEBUGGING SIFAT AND LAMPIRAN EXTRACTION ===\n";

// Test content that includes Sifat and Lampiran
$testContent = "PEMERINTAH KOTA BANDUNG
DINAS KOMUNIKASI DAN INFORMATIKA

SURAT BIASA
Nomor    : 050/AR.02/I/2025
Tanggal  : 3 Januari 2025
Sifat    : Biasa
Lampiran : -

Hal : Undangan Sosialisasi Implementasi Teknis Tata Cara dan Penggunaan E-BMD

Yth. Kepala Perangkat Daerah
     Kota Bandung

Di
Tempat

Dalam rangka implementasi sistem E-BMD...";

echo "Test content:\n";
echo $testContent . "\n\n";

echo "=== TESTING DOCUMENT NAME EXTRACTION ===\n";
$docName = \App\Helpers\DocumentNumberExtractor::findDocumentName($testContent);
echo "Document name extracted: '" . $docName . "'\n";

// Check if it's capturing Sifat or Lampiran
$containsSifat = strpos($docName, 'Sifat') !== false || strpos($docName, 'Biasa') !== false;
$containsLampiran = strpos($docName, 'Lampiran') !== false;

echo "Contains 'Sifat/Biasa': " . ($containsSifat ? 'YES (BAD)' : 'NO (GOOD)') . "\n";
echo "Contains 'Lampiran': " . ($containsLampiran ? 'YES (BAD)' : 'NO (GOOD)') . "\n";

echo "\n=== TESTING FULL DATA EXTRACTION ===\n";

// Create a mock file object for testing
class MockFile {
    public function getClientOriginalExtension() { return 'txt'; }
    public function getClientOriginalName() { return 'test.txt'; }
    public function getPathname() { return '/tmp/test.txt'; }
}

// We can't test the full extractDocumentData without a real file
// But we can test individual patterns

echo "=== TESTING INDIVIDUAL PATTERNS ===\n";

// Test if any of the patterns in findDocumentName are capturing Sifat or Lampiran
$patterns = [
    '/Hal\s*:\s*(.*?)(?=\n\s*Yth\.|\n\s*Kepada|\n\s*\n)/s',
    '/Subject\s*:\s*(.*?)(?=\n\s*Yth\.|\n\s*Kepada|\n\s*\n)/s',
    '/Perihal\s*:\s*(.*?)(?=\n\s*Yth\.|\n\s*Kepada|\n\s*\n)/s',
    '/Tentang\s*:\s*(.*?)(?=\n\s*Yth\.|\n\s*Kepada|\n\s*\n)/s',
    '/Re\s*:\s*([^\n\r]+)/i',
];

foreach ($patterns as $index => $pattern) {
    echo "Pattern " . ($index + 1) . ": " . $pattern . "\n";
    if (preg_match($pattern, $testContent, $matches)) {
        echo "  Match found: '" . $matches[1] . "'\n";
    } else {
        echo "  No match\n";
    }
}

echo "\n=== CHECKING FOR UNWANTED PATTERNS ===\n";
// Check if there are any patterns that might be capturing Sifat or Lampiran
$unwantedPatterns = [
    '/Sifat\s*:\s*([^\n\r]+)/i',
    '/Lampiran\s*:\s*([^\n\r]+)/i',
];

foreach ($unwantedPatterns as $index => $pattern) {
    echo "Unwanted pattern " . ($index + 1) . ": " . $pattern . "\n";
    if (preg_match($pattern, $testContent, $matches)) {
        echo "  WARNING: This pattern matches: '" . $matches[1] . "'\n";
    } else {
        echo "  Good: No match\n";
    }
}
?>
