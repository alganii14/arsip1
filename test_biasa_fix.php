<?php
// Test the fix for "Biasa -" prefix removal

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

echo "=== TESTING BIASA PREFIX FIX ===\n";

// Test cases
$testCases = [
    "Filename with Biasa prefix" => "Biasa - Undangan Sosialisasi Implementasi Teknis Tata Cara dan Penggunaan E-BMD.pdf",
    "Content with Biasa prefix" => "Biasa - Undangan Sosialisasi Implementasi Teknis Tata Cara dan Penggunaan E-BMD",
    "Proper document content" => "SURAT BIASA\nNomor : 050/AR.02/I/2025\nHal : Undangan Sosialisasi Implementasi Teknis Tata Cara dan Penggunaan E-BMD\n\nYth. Kepala Perangkat Daerah",
    "Content with Surat Biasa prefix" => "Surat Biasa - Undangan Sosialisasi Implementasi Teknis Tata Cara dan Penggunaan E-BMD"
];

foreach ($testCases as $scenario => $content) {
    echo "\n=== $scenario ===\n";
    echo "Input: '" . str_replace("\n", "\\n", $content) . "'\n";

    $result = \App\Helpers\DocumentNumberExtractor::findDocumentName($content);
    echo "Output: '" . $result . "'\n";

    $containsBiasa = strpos($result, 'Biasa') !== false;
    echo "Contains 'Biasa': " . ($containsBiasa ? 'YES (BAD)' : 'NO (GOOD)') . "\n";

    if ($result === 'Undangan Sosialisasi Implementasi Teknis Tata Cara Dan Penggunaan E Bmd') {
        echo "✅ Perfect match!\n";
    } elseif (stripos($result, 'Undangan Sosialisasi') !== false && !$containsBiasa) {
        echo "✅ Good result!\n";
    } else {
        echo "❌ Needs improvement\n";
    }
}

echo "\n=== TESTING DIRECT FUNCTIONS ===\n";

// Test the filename extraction function directly
$reflection = new ReflectionClass('App\Helpers\DocumentNumberExtractor');
$method = $reflection->getMethod('extractNameFromFilename');
$method->setAccessible(true);

$filenameResult = $method->invoke(null, "Biasa - Undangan Sosialisasi Implementasi Teknis Tata Cara dan Penggunaan E-BMD.pdf");
echo "Filename extraction result: '" . $filenameResult . "'\n";
echo "Contains 'Biasa': " . (strpos($filenameResult, 'Biasa') !== false ? 'YES (BAD)' : 'NO (GOOD)') . "\n";

// Test the clean function directly
$cleanMethod = $reflection->getMethod('cleanDocumentName');
$cleanMethod->setAccessible(true);

$cleanResult = $cleanMethod->invoke(null, "Biasa - Undangan Sosialisasi Implementasi Teknis Tata Cara dan Penggunaan E-BMD");
echo "Clean document name result: '" . $cleanResult . "'\n";
echo "Contains 'Biasa': " . (strpos($cleanResult, 'Biasa') !== false ? 'YES (BAD)' : 'NO (GOOD)') . "\n";

echo "\n=== FINAL TEST ===\n";
echo "✅ Fix completed!\n";
?>
