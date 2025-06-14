<?php

require_once 'vendor/autoload.php';

use App\Http\Controllers\ArsipController;
use App\Helpers\ClassificationFormatter;
use App\Helpers\DocumentNumberExtractor;
use Illuminate\Support\Facades\Log;

// Test the KU document number extraction issue
echo "=== Test KU Document Number Extraction ===\n\n";

// Test document content from the PDF
$testContent = "PEMERINTAH KOTA BANDUNG
KECAMATAN CIDADAP
Jalan Hegarmanah Tengah No. 1 Bandung
Telp. 022-203339 ;Laman: multisite.bandung.go.id/kecamatan-cidadap
Pos-el (E-mail): kec.cidadap@gmail.com

NOTA DINAS

Kepada    : Kepala Sub Bagian Umum dan Kepegawaian, Data dan Informasi
Dari      : Arsiparis Terampil
Tanggal   : 5 Desember 2024
Nomor     : KU.01.02.01/1-Kec.Cddp/XII/2025
Sifat     : Biasa
Lampiran  : -
Hal       : Laporan Monev SRIKANDI";

echo "Document Content:\n";
echo $testContent . "\n\n";

// Test extraction
echo "=== Testing Document Number Extraction ===\n";
$extractedNumber = DocumentNumberExtractor::findDocumentNumber($testContent);
echo "Extracted Number: " . ($extractedNumber ?? 'NULL') . "\n\n";

// Test classification determination
echo "=== Testing Classification Determination ===\n";
$controller = new ArsipController();
$reflection = new ReflectionClass($controller);
$method = $reflection->getMethod('determineClassificationFromNumber');
$method->setAccessible(true);

$testNumbers = [
    'KU.01.02.01/1-Kec.Cddp/XII/2025',
    'KU.01.02.01',
    'AR.03.09.20',
    'KP.01.01.01',
    'RT.02.01.01'
];

foreach ($testNumbers as $number) {
    echo "Testing: $number\n";
    $result = $method->invoke($controller, $number);
    echo "  Category: " . $result['category'] . "\n";
    echo "  Type: " . $result['type'] . "\n";
    echo "  Code: " . ($result['code'] ?? 'NULL') . "\n";

    // Test description
    if ($result['code']) {
        $description = ClassificationFormatter::getDescription($result['code']);
        echo "  Description: $description\n";
    }
    echo "\n";
}

// Test regex patterns specifically
echo "=== Testing Regex Patterns ===\n";
$testString = "KU.01.02.01/1-Kec.Cddp/XII/2025";
$patterns = [
    '/(KU|KP|RT|AR)\.(\d{2})\.(\d{2})(?:\.(\d{2}))?/i',
    '/(AR|KP|RT|KU)[\.|\s|\-\/]?(\d{2})[\.|\s|\-\/]?(\d{2})?[\.|\s|\-\/]?(\d{2})?/i',
    '/(KU)\.(\d{2})\.(\d{2})\/(\d+)\-([A-Za-z\.]+)\/([A-Z]+)\/(\d{4})/i'
];

foreach ($patterns as $i => $pattern) {
    echo "Pattern " . ($i + 1) . ": $pattern\n";
    if (preg_match($pattern, $testString, $matches)) {
        echo "  Matches: " . json_encode($matches) . "\n";
    } else {
        echo "  No match\n";
    }
    echo "\n";
}

echo "=== Test Complete ===\n";
