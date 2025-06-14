<?php

require_once 'vendor/autoload.php';

// Load Laravel configuration
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Http\Controllers\ArsipController;
use App\Helpers\ClassificationFormatter;

echo "=== Testing ArsipController Classification ===\n\n";

$controller = new ArsipController();

// Test the private method using reflection
$reflection = new ReflectionClass($controller);
$method = $reflection->getMethod('determineClassificationFromNumber');
$method->setAccessible(true);

$testDocumentNumber = 'KU.01.02.01/1-Kec.Cddp/XII/2025';
echo "Testing document number: $testDocumentNumber\n\n";

$classification = $method->invoke($controller, $testDocumentNumber);

echo "Classification result:\n";
echo "  Category: " . $classification['category'] . "\n";
echo "  Type: " . $classification['type'] . "\n";
echo "  Code: " . ($classification['code'] ?? 'NULL') . "\n\n";

// Test description
if (isset($classification['code'])) {
    $description = ClassificationFormatter::getDescription($classification['code']);
    echo "Description: $description\n\n";
}

// Simulate full API response
$classification['description'] = $description ?? '';

$apiResponse = [
    'success' => true,
    'documentNumber' => $testDocumentNumber,
    'documentDate' => '2024-12-05',
    'documentName' => 'Laporan Monev SRIKANDI',
    'classification' => $classification
];

echo "Full API Response (JSON):\n";
echo json_encode($apiResponse, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n";

// Test specific fields that JavaScript will use
echo "JavaScript will look for:\n";
echo "  data.classification.code = '" . ($classification['code'] ?? 'NULL') . "'\n";
echo "  Expected dropdown option value = 'KU.01.02.01'\n";

if ($classification['code'] === 'KU.01.02.01') {
    echo "✅ MATCH: Classification code matches expected dropdown value\n";
} else {
    echo "❌ MISMATCH: Classification code does not match expected value\n";
}

echo "\n=== Test Complete ===\n";
