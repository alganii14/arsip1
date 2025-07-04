<?php

// Test the updated classification logic

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
                $testCode = "$prefix.$mainCode.$subCode1";

                // Simulate checking if this code exists in the ClassificationFormatter
                $validCodes = [
                    'KU.03.01', 'KU.03.02', 'KU.03.03', // Valid KU.03 subdivisions
                    'KU.01.01', 'KU.01.02', 'KU.01.03', // Valid KU.01 subdivisions
                    'KU.02.01', 'KU.02.02', 'KU.02.03', // Valid KU.02 subdivisions
                    'AR.01.01', 'AR.01.02', 'AR.01.03', // Valid AR.01 subdivisions
                    'KP.01.01', 'KP.01.02', 'KP.01.03', // Valid KP.01 subdivisions
                    'RT.01.01', 'RT.01.02', 'RT.01.03', // Valid RT.01 subdivisions
                ];

                if (in_array($testCode, $validCodes)) {
                    $code = $testCode;

                    if ($subCode2) {
                        // We have the second subdivision (e.g., AR.01.01.01, KU.01.02.01)
                        $testCode2 = "$prefix.$mainCode.$subCode1.$subCode2";
                        $validCodes2 = [
                            'KU.03.01.01', 'KU.03.01.02', 'KU.03.01.03',
                            'KU.03.02.01', 'KU.03.02.02', 'KU.03.02.03',
                            'KU.03.03.01', 'KU.03.03.02', 'KU.03.03.03',
                        ];

                        if (in_array($testCode2, $validCodes2)) {
                            $code = $testCode2;
                        }
                    }
                }
                // If the subdivision doesn't exist, keep the main code (e.g., KU.03 instead of KU.03.07)
            }
        }
    }

    return [
        'category' => $category,
        'type' => $type,
        'code' => $code
    ];
}

// Test with various document numbers
$testNumbers = [
    "B/KU.03.07/4009-BKAD/XI/2024",  // Should return KU.03 (invalid subdivision)
    "B/KU.03.01/4009-BKAD/XI/2024",  // Should return KU.03.01 (valid subdivision)
    "B/KU.03.02/4009-BKAD/XI/2024",  // Should return KU.03.02 (valid subdivision)
    "B/KU.03.99/4009-BKAD/XI/2024",  // Should return KU.03 (invalid subdivision)
    "B/KU.01.01/4009-BKAD/XI/2024",  // Should return KU.01.01 (valid subdivision)
    "B/KU.01.99/4009-BKAD/XI/2024",  // Should return KU.01 (invalid subdivision)
];

echo "=== Testing Updated Classification Logic ===\n\n";

foreach ($testNumbers as $docNum) {
    echo "Document Number: $docNum\n";
    $result = determineClassificationFromNumber($docNum);
    echo "  Category: " . $result['category'] . "\n";
    echo "  Type: " . $result['type'] . "\n";
    echo "  Code: " . ($result['code'] ?? 'NULL') . "\n";
    echo "  Expected: " . (strpos($docNum, 'KU.03.07') !== false ? 'KU.03' : 'varies') . "\n";
    echo "---\n";
}

echo "\n=== Key Test Case ===\n";
echo "Document B/KU.03.07/4009-BKAD/XI/2024 should return KU.03 (not KU.03.07)\n";
$result = determineClassificationFromNumber("B/KU.03.07/4009-BKAD/XI/2024");
echo "Result: " . ($result['code'] ?? 'NULL') . "\n";
echo "Success: " . ($result['code'] === 'KU.03' ? 'YES' : 'NO') . "\n\n";

echo "=== Done ===\n";
