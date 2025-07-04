<?php

// Test script to verify the regex pattern for removing malformed prefixes

function testCleanDocumentName($name) {
    echo "Testing: '$name'\n";

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

    echo "Result: '$name'\n";
    echo "---\n";

    return $name;
}

// Test cases
echo "=== Testing Malformed Prefix Removal ===\n\n";

testCleanDocumentName(": : Biasa - Penyerahan SK Kenaikan Pangkat Periode Desember 2024");
testCleanDocumentName(": Biasa - Test Document");
testCleanDocumentName(":: Biasa - Another Test");
testCleanDocumentName(": : Sifat - Confidential Document");
testCleanDocumentName(": : Lampiran - Attachment Document");
testCleanDocumentName("Biasa - Regular Document");
testCleanDocumentName("Sifat - Secret Document");
testCleanDocumentName("Lampiran - Attachment Only");
testCleanDocumentName("Hal : Normal Document");
testCleanDocumentName("Penyerahan SK Kenaikan Pangkat Periode Desember 2024");

echo "\n=== All tests completed ===\n";
