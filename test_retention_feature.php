<?php

require_once 'vendor/autoload.php';
require_once 'bootstrap/app.php';

use App\Models\Arsip;
use Carbon\Carbon;

echo "=== TEST RETENTION FEATURE ===\n\n";

// Test 1: Manual retention with string input (simulating form input)
echo "1. Testing manual retention with string input:\n";
$testArsip = new Arsip();
$testArsip->tanggal_arsip = '2024-01-01';
$testArsip->retention_years = '7'; // String from form

echo "   - Tanggal Arsip: " . $testArsip->tanggal_arsip . "\n";
echo "   - Retention Years: " . $testArsip->retention_years . " (type: " . gettype($testArsip->retention_years) . ")\n";

// Test calculateRetentionDate with manual years
try {
    $testArsip->calculateRetentionDate('7'); // String input
    echo "   - SUCCESS: Manual retention calculation works\n";
} catch (Exception $e) {
    echo "   - ERROR: " . $e->getMessage() . "\n";
}

// Test 2: Auto retention (default 5 years)
echo "\n2. Testing auto retention (default 5 years):\n";
$testArsip2 = new Arsip();
$testArsip2->tanggal_arsip = '2024-01-01';
$testArsip2->retention_years = null;

echo "   - Tanggal Arsip: " . $testArsip2->tanggal_arsip . "\n";
echo "   - Retention Years: " . ($testArsip2->retention_years ?? 'null (default)') . "\n";

try {
    $testArsip2->calculateRetentionDate();
    echo "   - SUCCESS: Auto retention calculation works\n";
} catch (Exception $e) {
    echo "   - ERROR: " . $e->getMessage() . "\n";
}

// Test 3: Edge case - very high retention years
echo "\n3. Testing edge case - high retention years:\n";
$testArsip3 = new Arsip();
$testArsip3->tanggal_arsip = '2024-01-01';

try {
    $testArsip3->calculateRetentionDate('100'); // 100 years
    echo "   - SUCCESS: High retention years works\n";
} catch (Exception $e) {
    echo "   - ERROR: " . $e->getMessage() . "\n";
}

// Test 4: Type conversion verification
echo "\n4. Testing type conversion:\n";
$stringYear = '15';
$intYear = (int) $stringYear;
echo "   - String year: '$stringYear' (type: " . gettype($stringYear) . ")\n";
echo "   - Int year: $intYear (type: " . gettype($intYear) . ")\n";

$testDate = Carbon::createFromFormat('Y-m-d', '2024-01-01');
try {
    $result = $testDate->addYears($intYear);
    echo "   - SUCCESS: addYears with int works: " . $result->format('Y-m-d') . "\n";
} catch (Exception $e) {
    echo "   - ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== END OF TEST ===\n";
