<?php

// Simple test script without Laravel facades
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

// Test regex patterns directly
echo "=== Testing Regex Patterns ===\n";
$testString = "KU.01.02.01/1-Kec.Cddp/XII/2025";
$patterns = [
    '/(KU|KP|RT|AR)\.(\d{2})\.(\d{2})(?:\.(\d{2}))?/i' => 'New pattern (KU first)',
    '/(AR|KP|RT|KU)[\.|\s|\-\/]?(\d{2})[\.|\s|\-\/]?(\d{2})?[\.|\s|\-\/]?(\d{2})?/i' => 'Old pattern (AR first)',
    '/(KU)\.(\d{2})\.(\d{2})\/(\d+)\-([A-Za-z\.]+)\/([A-Z]+)\/(\d{4})/i' => 'KU specific pattern'
];

foreach ($patterns as $pattern => $description) {
    echo "Pattern: $description\n";
    echo "Regex: $pattern\n";
    if (preg_match($pattern, $testString, $matches)) {
        echo "  Matches: " . json_encode($matches) . "\n";
        echo "  Prefix: " . $matches[1] . "\n";
        echo "  Main Code: " . $matches[2] . "\n";
        echo "  Sub Code 1: " . (isset($matches[3]) ? $matches[3] : 'N/A') . "\n";
        echo "  Sub Code 2: " . (isset($matches[4]) ? $matches[4] : 'N/A') . "\n";
    } else {
        echo "  No match\n";
    }
    echo "\n";
}

// Test extraction from full content
echo "=== Testing Content Extraction ===\n";
$extractionPatterns = [
    '/Nomor\s*:\s*((KU)\.(\d{2})\.(\d{2})\/(\d+)\-([A-Za-z\.]+)\/([A-Z]+)\/(\d{4}))/i' => 'KU Nomor pattern',
    '/Nomor\s*:\s*((AR)\.(\d{2})\.(\d{2})\/(\d+)\-([A-Za-z\.]+)\/([A-Z]+)\/(\d{4}))/i' => 'AR Nomor pattern',
    '/(KU)\.(\d{2})\.(\d{2})\/(\d+)\-([A-Za-z\.]+)\/([A-Z]+)\/(\d{4})/i' => 'KU full pattern',
    '/(AR)\.(\d{2})\.(\d{2})\/(\d+)\-([A-Za-z\.]+)\/([A-Z]+)\/(\d{4})/i' => 'AR full pattern'
];

foreach ($extractionPatterns as $pattern => $description) {
    echo "Testing: $description\n";
    if (preg_match($pattern, $testContent, $matches)) {
        echo "  FOUND: " . $matches[0] . "\n";
        echo "  Type: " . $matches[1] . "\n";
    } else {
        echo "  No match\n";
    }
    echo "\n";
}

echo "=== Test Complete ===\n";
