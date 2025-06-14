<?php

// Test the updated extraction and classification
echo "=== Testing Updated Extraction and Classification ===\n\n";

// Test document content
$testContent = "Nomor     : KU.01.02.01/1-Kec.Cddp/XII/2025";

// Test extraction patterns
echo "Testing extraction patterns:\n";
$patterns = [
    '/Nomor\s*:\s*((KU)\.(\d{2})\.(\d{2})\.(\d{2})\/(\d+)\-([A-Za-z\.]+)\/([A-Z]+)\/(\d{4}))/i' => 'KU 4-level Nomor pattern',
    '/(KU)\.(\d{2})\.(\d{2})\.(\d{2})\/(\d+)\-([A-Za-z\.]+)\/([A-Z]+)\/(\d{4})/i' => 'KU 4-level pattern',
];

foreach ($patterns as $pattern => $desc) {
    echo "\n$desc:\n";
    echo "Pattern: $pattern\n";
    if (preg_match($pattern, $testContent, $matches)) {
        echo "MATCH: " . $matches[0] . "\n";
        echo "Captures: " . json_encode($matches) . "\n";
    } else {
        echo "No match\n";
    }
}

// Test classification patterns
echo "\n\nTesting classification patterns:\n";
$testNumbers = [
    'KU.01.02.01/1-Kec.Cddp/XII/2025',
    'KU.01.02.01',
    'AR.03.09.20',
];

$classificationPatterns = [
    '/(KU|KP|RT|AR)\.(\d{2})\.(\d{2})\.(\d{2})(?:\.(\d{2}))?/i' => '4-level pattern',
    '/(KU|KP|RT|AR)\.(\d{2})\.(\d{2})(?:\.(\d{2}))?/i' => '3-level pattern',
];

foreach ($testNumbers as $number) {
    echo "\nTesting number: $number\n";
    foreach ($classificationPatterns as $pattern => $desc) {
        if (preg_match($pattern, $number, $matches)) {
            echo "  $desc MATCHES:\n";
            echo "    Prefix: " . $matches[1] . "\n";
            echo "    Main: " . $matches[2] . "\n";
            echo "    Sub1: " . (isset($matches[3]) ? $matches[3] : 'N/A') . "\n";
            echo "    Sub2: " . (isset($matches[4]) ? $matches[4] : 'N/A') . "\n";
            echo "    Sub3: " . (isset($matches[5]) ? $matches[5] : 'N/A') . "\n";
            break; // Only show first match
        }
    }
}

echo "\n=== Test Complete ===\n";
