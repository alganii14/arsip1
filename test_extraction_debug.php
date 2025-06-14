<?php

require_once 'vendor/autoload.php';
require_once 'app/Helpers/DocumentNumberExtractor.php';
require_once 'app/Helpers/ClassificationFormatter.php';

use App\Helpers\DocumentNumberExtractor;
use App\Helpers\ClassificationFormatter;

// Test cases
$testCases = [
    'Nomor : KP.03.03/1-Kec.Cddp/XII/2024',
    'Nomor : RT.03.03/1-Kec.Cddp/XII/2024',
    'Nomor : AR.03.09/2082-Disarpus/XI/2024',
    'KP.03.03/1-Kec.Cddp/XII/2024',
    'RT.03.03/1-Kec.Cddp/XII/2024',
];

echo "=== TESTING DOCUMENT NUMBER EXTRACTION ===\n\n";

foreach ($testCases as $i => $testCase) {
    echo "Test Case " . ($i + 1) . ": {$testCase}\n";

    // Test extraction
    $extracted = DocumentNumberExtractor::findDocumentNumber($testCase);
    echo "Extracted: " . ($extracted ?? 'NULL') . "\n";

    // Test formatting
    if ($extracted) {
        $formatted = DocumentNumberExtractor::formatDocumentNumber($extracted);
        echo "Formatted: {$formatted}\n";

        // Test classification
        $description = ClassificationFormatter::getDescription($formatted);
        echo "Classification: {$description}\n";

        $badgeClass = ClassificationFormatter::getBadgeClass($formatted);
        echo "Badge Class: {$badgeClass}\n";
    }

    echo "\n" . str_repeat("-", 50) . "\n\n";
}
