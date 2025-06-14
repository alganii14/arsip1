<?php

require_once 'vendor/autoload.php';

use App\Helpers\ClassificationFormatter;
use App\Helpers\DocumentNumberExtractor;

// Test KU.16 and KU.17 classifications
$testCodes = [
    'KU.16',
    'KU.16.01',
    'KU.16.01.01',
    'KU.16.01.10',
    'KU.16.01.10.01',
    'KU.16.01.12',
    'KU.16.01.12.03.01',
    'KU.17',
    'KU.17.01',
    'KU.17.02',
    'KU.17.02.01',
    'KU.17.03'
];

echo "=== Testing KU.16 and KU.17 Classification Descriptions ===\n\n";

foreach ($testCodes as $code) {
    $description = ClassificationFormatter::getDescription($code);
    $badgeClass = ClassificationFormatter::getBadgeClass($code);
    echo "Code: $code\n";
    echo "Description: $description\n";
    echo "Badge Class: $badgeClass\n";
    echo "---\n";
}

// Test document number formatting
echo "\n=== Testing Document Number Extraction and Formatting ===\n\n";

$testDocNumbers = [
    'KU.16.01.01/001-Kec.Cddp/VI/2024',
    'KU.17.02.04/010-Pemkot.Cimahi/XII/2024',
    'Nomor: KU.16.01.12.03.01/005-Bappeda/III/2025'
];

foreach ($testDocNumbers as $docNumber) {
    $formatted = DocumentNumberExtractor::formatDocumentNumber($docNumber);
    echo "Original: $docNumber\n";
    echo "Formatted: $formatted\n";
    echo "---\n";
}

echo "\nTest completed!\n";
