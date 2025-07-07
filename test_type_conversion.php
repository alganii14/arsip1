<?php

require_once 'vendor/autoload.php';

use Carbon\Carbon;

echo "=== TEST TYPE CONVERSION FOR RETENTION ===\n\n";

// Test 1: String to int conversion
echo "1. Testing string to int conversion:\n";
$stringYear = '7';
$intYear = (int) $stringYear;
echo "   - String year: '$stringYear' (type: " . gettype($stringYear) . ")\n";
echo "   - Int year: $intYear (type: " . gettype($intYear) . ")\n";

// Test 2: addYears with int
echo "\n2. Testing Carbon addYears with int:\n";
$testDate = Carbon::createFromFormat('Y-m-d', '2024-01-01');
try {
    $result = $testDate->addYears($intYear);
    echo "   - SUCCESS: addYears with int works: " . $result->format('Y-m-d') . "\n";
} catch (Exception $e) {
    echo "   - ERROR: " . $e->getMessage() . "\n";
}

// Test 3: addYears with string (should fail)
echo "\n3. Testing Carbon addYears with string (should fail):\n";
$testDate2 = Carbon::createFromFormat('Y-m-d', '2024-01-01');
try {
    $result = $testDate2->addYears($stringYear);
    echo "   - UNEXPECTED SUCCESS: addYears with string works: " . $result->format('Y-m-d') . "\n";
} catch (Exception $e) {
    echo "   - EXPECTED ERROR: " . $e->getMessage() . "\n";
}

// Test 4: Various input types
echo "\n4. Testing various input types:\n";
$inputs = ['5', 5, '10', 10, '0', 0];
foreach ($inputs as $input) {
    $converted = (int) $input;
    echo "   - Input: '$input' (type: " . gettype($input) . ") -> Converted: $converted (type: " . gettype($converted) . ")\n";
}

echo "\n=== END OF TEST ===\n";
