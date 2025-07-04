<?php
// Test komprehensif untuk memastikan Sifat dan Lampiran tidak diekstrak

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

echo "=== TEST KOMPREHENSIF: SIFAT DAN LAMPIRAN TIDAK DIEKSTRAK ===\n";

// Test cases
$testCases = [
    "Dokumen normal dengan Sifat dan Lampiran" => [
        'content' => "SURAT BIASA\nNomor: 001/2025\nTanggal: 1 Jan 2025\nSifat: Biasa\nLampiran: -\n\nHal: Undangan Sosialisasi E-BMD\n\nYth. Kepala Dinas",
        'expected' => 'Undangan Sosialisasi E-BMD'
    ],
    "Filename dengan Sifat dan Lampiran" => [
        'content' => "Sifat Biasa Lampiran - Undangan Sosialisasi E-BMD.pdf",
        'expected' => 'Undangan Sosialisasi E-BMD'
    ],
    "Konten hanya Sifat" => [
        'content' => "Sifat: Biasa",
        'expected' => ''
    ],
    "Konten hanya Lampiran" => [
        'content' => "Lampiran: -",
        'expected' => ''
    ],
    "Konten dengan Biasa di awal" => [
        'content' => "Biasa - Undangan Sosialisasi",
        'expected' => 'Undangan Sosialisasi'
    ]
];

$allPassed = true;

foreach ($testCases as $scenario => $test) {
    echo "\n=== $scenario ===\n";

    $result = \App\Helpers\DocumentNumberExtractor::findDocumentName($test['content']);

    echo "Input: " . substr(str_replace("\n", "\\n", $test['content']), 0, 50) . "...\n";
    echo "Expected: '{$test['expected']}'\n";
    echo "Actual: '$result'\n";

    // Check for unwanted content
    $containsUnwanted = false;
    $unwantedWords = ['Sifat', 'Lampiran', 'Nomor', 'Tanggal'];
    foreach ($unwantedWords as $word) {
        if (stripos($result, $word) !== false) {
            $containsUnwanted = true;
            echo "❌ BERISI KATA YANG TIDAK DIINGINKAN: '$word'\n";
            break;
        }
    }

    if (!$containsUnwanted) {
        if ($result === $test['expected']) {
            echo "✅ PERFECT MATCH\n";
        } elseif (empty($test['expected']) && empty($result)) {
            echo "✅ CORRECTLY EMPTY\n";
        } elseif (!empty($result) && stripos($result, 'Undangan') !== false) {
            echo "✅ GOOD RESULT (mengandung kata kunci yang benar)\n";
        } else {
            echo "⚠️ HASIL BERBEDA TAPI BERSIH\n";
        }
    } else {
        echo "❌ MASIH BERISI KATA YANG TIDAK DIINGINKAN\n";
        $allPassed = false;
    }
}

echo "\n=== HASIL AKHIR ===\n";
if ($allPassed) {
    echo "🎉 SEMUA TEST LULUS! Sifat dan Lampiran tidak lagi diekstrak.\n";
} else {
    echo "❌ MASIH ADA MASALAH yang perlu diperbaiki.\n";
}

// Test additional cleaning functions
echo "\n=== TEST FUNGSI PEMBERSIH ===\n";
$reflection = new ReflectionClass('App\Helpers\DocumentNumberExtractor');

$cleanMethod = $reflection->getMethod('cleanDocumentName');
$cleanMethod->setAccessible(true);

$filenameMethod = $reflection->getMethod('extractNameFromFilename');
$filenameMethod->setAccessible(true);

$testClean = [
    "Sifat Biasa Lampiran Undangan" => $cleanMethod->invoke(null, "Sifat Biasa Lampiran Undangan"),
    "Biasa - Undangan Sosialisasi" => $cleanMethod->invoke(null, "Biasa - Undangan Sosialisasi"),
    "Lampiran - Undangan E-BMD" => $cleanMethod->invoke(null, "Lampiran - Undangan E-BMD"),
];

foreach ($testClean as $input => $output) {
    echo "Clean: '$input' -> '$output'\n";
}

$testFilename = [
    "Sifat-Biasa-Lampiran-Undangan.pdf" => $filenameMethod->invoke(null, "Sifat-Biasa-Lampiran-Undangan.pdf"),
    "Biasa_Undangan_Sosialisasi.pdf" => $filenameMethod->invoke(null, "Biasa_Undangan_Sosialisasi.pdf"),
];

foreach ($testFilename as $input => $output) {
    echo "Filename: '$input' -> '$output'\n";
}
?>
