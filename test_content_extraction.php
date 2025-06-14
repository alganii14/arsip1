<?php

// Test content extraction with improved patterns
echo "=== Improved Content Extraction Test ===\n\n";

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

// Test extraction patterns with more flexible matching
$extractionPatterns = [
    '/Nomor\s*:\s*((KU)\.(\d{2})\.(\d{2})\.(\d{2})\/(\d+)\-([A-Za-z\.]+)\/([A-Z]+)\/(\d{4}))/i' => 'KU Nomor pattern (4 level)',
    '/Nomor\s*:\s*((KU)\.(\d{2})\.(\d{2})\/(\d+)\-([A-Za-z\.]+)\/([A-Z]+)\/(\d{4}))/i' => 'KU Nomor pattern (3 level)',
    '/Nomor\s*:\s*((KU)[^\n\r]*)/i' => 'KU Nomor pattern (flexible)',
    '/Nomor\s*:\s*([A-Z]{2}\.\d{2}\.\d{2}[^\n\r]*)/i' => 'Generic pattern',
    '/(KU)\.(\d{2})\.(\d{2})\.(\d{2})\/(\d+)\-([A-Za-z\.]+)\/([A-Z]+)\/(\d{4})/i' => 'KU full pattern (4 level)',
    '/(KU)\.(\d{2})\.(\d{2})\/(\d+)\-([A-Za-z\.]+)\/([A-Z]+)\/(\d{4})/i' => 'KU full pattern (3 level)',
];

foreach ($extractionPatterns as $pattern => $description) {
    echo "Testing: $description\n";
    echo "Pattern: $pattern\n";
    if (preg_match($pattern, $testContent, $matches)) {
        echo "  FOUND: " . $matches[0] . "\n";
        echo "  Captures: " . json_encode($matches) . "\n";
    } else {
        echo "  No match\n";
    }
    echo "\n";
}

// Test the actual line that contains Nomor
echo "=== Line by Line Analysis ===\n";
$lines = explode("\n", $testContent);
foreach ($lines as $i => $line) {
    if (stripos($line, 'Nomor') !== false) {
        echo "Line " . ($i + 1) . ": " . trim($line) . "\n";

        // Test against this specific line
        $testPatterns = [
            '/Nomor\s*:\s*((KU)[^\n\r]*)/i' => 'Flexible KU pattern',
            '/Nomor\s*:\s*([A-Z]{2}\.\d{2}\.\d{2}[^\n\r]*)/i' => 'Generic pattern'
        ];

        foreach ($testPatterns as $pattern => $desc) {
            if (preg_match($pattern, $line, $matches)) {
                echo "  $desc MATCHES: " . $matches[1] . "\n";
            }
        }
        echo "\n";
    }
}

echo "=== Test Complete ===\n";
