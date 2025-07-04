<?php

// Test the classification logic with the current document number

function determineClassificationFromNumber($documentNumber)
{
    // Default classification if we can't determine it
    $category = 'Dokumen Arsip';
    $type = 'AR';
    $code = null;

    // Try to extract classification code for AR, KP, RT, or KU formats
    // Pattern yang menangkap semua format 3-level dan 4-level
    if (preg_match('/(KU|KP|RT|AR)\.(\d{2})\.(\d{2})(?:\.(\d{2}))?/i', $documentNumber, $matches)) {
        $prefix = strtoupper($matches[1]); // KU, KP, RT, or AR
        $mainCode = isset($matches[2]) ? $matches[2] : null;
        $subCode1 = isset($matches[3]) ? $matches[3] : null;
        $subCode2 = isset($matches[4]) ? $matches[4] : null;

        if ($mainCode) {
            // We have at least the main code (e.g., AR.01, KP.02, RT.03, KU.01)
            $code = "$prefix.$mainCode";
            $type = $prefix;

            // Set category based on type
            switch ($prefix) {
                case 'AR':
                    $category = 'Kearsipan';
                    break;
                case 'KP':
                    $category = 'Kepegawaian';
                    break;
                case 'RT':
                    $category = 'Kerumahtanggaan';
                    break;
                case 'KU':
                    $category = 'Keuangan';
                    break;
            }

            if ($subCode1) {
                // We have the first subdivision (e.g., AR.01.01, KP.02.03, RT.03.01, KU.01.02)
                $code .= ".$subCode1";

                if ($subCode2) {
                    // We have the second subdivision (e.g., AR.01.01.01, KU.01.02.01)
                    $code .= ".$subCode2";
                }
            }
        }
    }

    return [
        'category' => $category,
        'type' => $type,
        'code' => $code
    ];
}

// Test with the current document number
$documentNumber = "B/KU.03.07/4009-BKAD/XI/2024";
echo "=== Testing Classification for Document Number ===\n";
echo "Document Number: $documentNumber\n\n";

$result = determineClassificationFromNumber($documentNumber);

echo "Classification Result:\n";
echo "  Category: " . $result['category'] . "\n";
echo "  Type: " . $result['type'] . "\n";
echo "  Code: " . ($result['code'] ?? 'NULL') . "\n\n";

// Test the regex pattern separately
echo "=== Testing Regex Pattern ===\n";
$pattern = '/(KU|KP|RT|AR)\.(\d{2})\.(\d{2})(?:\.(\d{2}))?/i';
if (preg_match($pattern, $documentNumber, $matches)) {
    echo "Pattern matches!\n";
    echo "Matches:\n";
    foreach ($matches as $i => $match) {
        echo "  [$i]: $match\n";
    }
} else {
    echo "Pattern does not match.\n";
}

echo "\n=== Testing Other Document Numbers ===\n";
$testNumbers = [
    "KU.03.07/4009-BKAD/XI/2024",
    "KU.03.07.01/4009-BKAD/XI/2024",
    "B/KU.03.07.01/4009-BKAD/XI/2024",
    "KU.03.07",
    "KU.03.07.01"
];

foreach ($testNumbers as $testNum) {
    echo "Testing: $testNum\n";
    $result = determineClassificationFromNumber($testNum);
    echo "  Result: " . ($result['code'] ?? 'NULL') . "\n";
}

echo "\n=== Done ===\n";
