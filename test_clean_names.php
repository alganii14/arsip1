<?php

// Simple test for document name cleaning function

function cleanDocumentName($name) {
    // Remove common prefixes
    $name = preg_replace('/^(Hal\s*:\s*|Subject\s*:\s*|Perihal\s*:\s*|Tentang\s*:\s*|Re\s*:\s*)/i', '', $name);

    // Remove document type prefixes and malformed prefixes
    $name = preg_replace('/^(Biasa\s*[-\s]*|Surat\s*Biasa\s*[-\s]*|Sifat\s*[-\s]*|Lampiran\s*[-\s]*)/i', '', $name);

    // Remove malformed prefixes like ": : Biasa -", ": Biasa -", etc.
    $name = preg_replace('/^[:;\s]*[:;\s]*\s*(Biasa|Sifat|Lampiran)\s*[-\s]*/i', '', $name);

    // More specific pattern for cases like ": : Biasa -"
    $name = preg_replace('/^:\s*:\s*(Biasa|Sifat|Lampiran)\s*-\s*/i', '', $name);

    // Remove any remaining colons at the beginning
    $name = preg_replace('/^[:;\s]+/', '', $name);

    // Handle multi-line text - replace newlines with spaces
    $name = preg_replace('/\s*\n\s*/', ' ', $name);

    // Remove extra whitespace
    $name = preg_replace('/\s+/', ' ', $name);

    // Remove unwanted words from the beginning
    $unwantedWords = ['Sifat', 'Biasa', 'Lampiran', 'Nomor', 'Tanggal'];
    $words = explode(' ', $name);
    while (!empty($words) && in_array(ucfirst(strtolower($words[0])), $unwantedWords)) {
        array_shift($words);
    }
    $name = implode(' ', $words);

    // Trim
    $name = trim($name);

    // Remove trailing punctuation
    $name = rtrim($name, '.,;:-');

    return $name;
}

echo "=== Testing Document Name Cleaning ===\n\n";

// Test the problematic case from the screenshot
$testName = ": : Biasa - Penyerahan SK Kenaikan Pangkat Periode Desember 2024";
echo "Input: '$testName'\n";
$result = cleanDocumentName($testName);
echo "Output: '$result'\n";
echo "Success: " . ($result === "Penyerahan SK Kenaikan Pangkat Periode Desember 2024" ? "YES" : "NO") . "\n\n";

// Test other cases
$testCases = [
    ": Biasa - Test Document",
    ":: Biasa - Another Test",
    ": : Sifat - Confidential Document",
    ": : Lampiran - Attachment Document",
    "Biasa - Regular Document",
    "Sifat - Secret Document",
    "Lampiran - Attachment Only",
    "Hal : Normal Document",
    "Penyerahan SK Kenaikan Pangkat Periode Desember 2024"
];

foreach ($testCases as $test) {
    echo "Input: '$test'\n";
    $result = cleanDocumentName($test);
    echo "Output: '$result'\n";
    echo "---\n";
}

echo "\n=== All tests completed ===\n";
