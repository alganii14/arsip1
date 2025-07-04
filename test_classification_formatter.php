<?php

// Test the actual ClassificationFormatter

require_once 'app/Helpers/ClassificationFormatter.php';

use App\Helpers\ClassificationFormatter;

echo "=== Testing ClassificationFormatter ===\n\n";

$testCodes = [
    'KU.03',        // Should return "Pelaksanaan Anggaran"
    'KU.03.01',     // Should return "Pelaksanaan Anggaran - Pendapatan"
    'KU.03.02',     // Should return "Pelaksanaan Anggaran - Belanja"
    'KU.03.03',     // Should return "Pelaksanaan Anggaran - Pembiayaan"
    'KU.03.07',     // Should return empty (doesn't exist)
];

foreach ($testCodes as $code) {
    echo "Code: $code\n";
    $description = ClassificationFormatter::getDescription($code);
    echo "Description: '$description'\n";
    echo "Valid: " . (empty($description) ? 'NO' : 'YES') . "\n";
    echo "---\n";
}

echo "\n=== Testing Fallback Logic ===\n";
echo "For document KU.03.07, the system should:\n";
echo "1. Try KU.03.07 -> Empty (doesn't exist)\n";
echo "2. Fall back to KU.03 -> 'Pelaksanaan Anggaran' (exists)\n";
echo "3. Select KU.03 in dropdown\n\n";

$ku03Description = ClassificationFormatter::getDescription('KU.03');
echo "KU.03 Description: '$ku03Description'\n";
echo "KU.03 Valid: " . (empty($ku03Description) ? 'NO' : 'YES') . "\n";

echo "\n=== Done ===\n";
