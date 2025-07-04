<?php
// Debug untuk mencari tahu di mana masih ada ekstraksi Sifat dan Lampiran

// Mock the Log class
if (!class_exists('Log')) {
    class Log {
        public static function info($message) {
            echo "[INFO] " . $message . "\n";
        }
        public static function warning($message) {
            echo "[WARNING] " . $message . "\n";
        }
        public static function error($message) {
            echo "[ERROR] " . $message . "\n";
        }
    }
}

if (!class_exists('Illuminate\Support\Facades\Log')) {
    class_alias('Log', 'Illuminate\Support\Facades\Log');
}

// Include the DocumentNumberExtractor class
require_once 'app/Helpers/DocumentNumberExtractor.php';

echo "=== DEBUG: MENCARI SUMBER EKSTRAKSI SIFAT DAN LAMPIRAN ===\n";

// Test dengan konten yang bermasalah
$problematicContent = "PEMERINTAH KOTA BANDUNG
DINAS KOMUNIKASI DAN INFORMATIKA

SURAT BIASA
Nomor    : 050/AR.02/I/2025
Tanggal  : 3 Januari 2025
Sifat    : Biasa
Lampiran : -

Hal : Undangan Sosialisasi Implementasi Teknis Tata Cara dan Penggunaan E-BMD

Yth. Kepala Perangkat Daerah
     Kota Bandung";

echo "=== TESTING FIND DOCUMENT NAME ===\n";
$docName = \App\Helpers\DocumentNumberExtractor::findDocumentName($problematicContent);
echo "findDocumentName result: '" . $docName . "'\n";

echo "\n=== TESTING EXTRACT FROM FILENAME ===\n";
$reflection = new ReflectionClass('App\Helpers\DocumentNumberExtractor');
$method = $reflection->getMethod('extractFromFilename');
$method->setAccessible(true);

$filenameResult = $method->invoke(null, "Sifat Biasa Lampiran - Undangan.pdf");
echo "extractFromFilename result: '" . $filenameResult . "'\n";

echo "\n=== TESTING EXTRACT NAME FROM FILENAME ===\n";
$nameMethod = $reflection->getMethod('extractNameFromFilename');
$nameMethod->setAccessible(true);

$nameResult = $nameMethod->invoke(null, "Sifat Biasa Lampiran - Undangan.pdf");
echo "extractNameFromFilename result: '" . $nameResult . "'\n";

echo "\n=== TESTING DENGAN BERBAGAI PATTERN ===\n";
$testPatterns = [
    '/Sifat\s*:\s*([^\n\r]+)/i',
    '/Lampiran\s*:\s*([^\n\r]+)/i',
    '/Hal\s*:\s*(.*?)(?=\n\s*Yth\.|\n\s*Kepada|\n\s*\n)/s',
];

foreach ($testPatterns as $index => $pattern) {
    echo "Pattern " . ($index + 1) . ": " . $pattern . "\n";
    if (preg_match($pattern, $problematicContent, $matches)) {
        echo "  MATCH: '" . $matches[1] . "'\n";
    } else {
        echo "  NO MATCH\n";
    }
}

echo "\n=== MENCARI SEMUA PATTERN : FIELD ===\n";
if (preg_match_all('/(\w+)\s*:\s*([^\n\r]+)/i', $problematicContent, $matches, PREG_SET_ORDER)) {
    foreach ($matches as $match) {
        echo "Field: '" . $match[1] . "' = '" . $match[2] . "'\n";
    }
}
?>
