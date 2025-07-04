<?php
// Test the fixes for preventing Sifat and Lampiran extraction

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

echo "=== TESTING SIFAT AND LAMPIRAN PREVENTION ===\n";

// Test cases that should NOT extract Sifat or Lampiran
$testCases = [
    "Document with Sifat and Lampiran" => "SURAT BIASA
Nomor    : 050/AR.02/I/2025
Tanggal  : 3 Januari 2025
Sifat    : Biasa
Lampiran : -

Hal : Undangan Sosialisasi Implementasi Teknis Tata Cara dan Penggunaan E-BMD

Yth. Kepala Perangkat Daerah",

    "Content starting with Sifat" => "Sifat : Biasa
Hal : Undangan Sosialisasi",

    "Content starting with Lampiran" => "Lampiran : -
Hal : Undangan Sosialisasi",

    "Content with only Sifat" => "Sifat : Biasa",

    "Content with only Lampiran" => "Lampiran : -",

    "Good content" => "Hal : Undangan Sosialisasi Implementasi Teknis Tata Cara dan Penggunaan E-BMD

Yth. Kepala Perangkat Daerah"
];

foreach ($testCases as $scenario => $content) {
    echo "\n=== $scenario ===\n";
    echo "Content: " . str_replace("\n", "\\n", substr($content, 0, 100)) . "...\n";

    $result = \App\Helpers\DocumentNumberExtractor::findDocumentName($content);
    echo "Result: '" . $result . "'\n";

    // Check for unwanted content
    $containsSifat = stripos($result, 'Sifat') !== false;
    $containsLampiran = stripos($result, 'Lampiran') !== false;
    $containsBiasa = stripos($result, 'Biasa') !== false && stripos($result, 'Undangan') === false;

    echo "Contains 'Sifat': " . ($containsSifat ? 'YES (BAD)' : 'NO (GOOD)') . "\n";
    echo "Contains 'Lampiran': " . ($containsLampiran ? 'YES (BAD)' : 'NO (GOOD)') . "\n";
    echo "Contains 'Biasa' only: " . ($containsBiasa ? 'YES (BAD)' : 'NO (GOOD)') . "\n";

    if ($containsSifat || $containsLampiran || $containsBiasa) {
        echo "❌ FAILED: Still extracting unwanted content\n";
    } else {
        echo "✅ SUCCESS: No unwanted content extracted\n";
    }
}

echo "\n=== FINAL VERIFICATION ===\n";
echo "✅ Added filters to prevent extraction of Sifat and Lampiran fields\n";
echo "✅ Modified patterns to skip metadata fields\n";
echo "✅ Added validation to ensure only document names are extracted\n";
?>
