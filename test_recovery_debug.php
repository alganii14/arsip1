<?php

require_once 'vendor/autoload.php';

use Carbon\Carbon;

echo "Testing Carbon addYears() function with different inputs:\n\n";

// Test with string numbers
$tests = ['1', '2', '3', '5', '7', '10', '15', '20', '25', '30', 'permanent'];

foreach ($tests as $value) {
    echo "Testing value: '{$value}'\n";

    try {
        if ($value === 'permanent') {
            echo "  Result: null (permanent)\n";
        } else {
            $yearsInt = intval($value);
            echo "  Converted to int: {$yearsInt}\n";

            $date = Carbon::now();
            echo "  Current date: " . $date->format('Y-m-d H:i:s') . "\n";

            $futureDate = $date->addYears($yearsInt);
            echo "  Future date: " . $futureDate->format('Y-m-d H:i:s') . "\n";
        }
    } catch (Exception $e) {
        echo "  ERROR: " . $e->getMessage() . "\n";
    }

    echo "\n";
}

echo "Testing with direct integer values:\n\n";

$directTests = [1, 2, 3, 5, 7, 10, 15, 20, 25, 30];

foreach ($directTests as $value) {
    echo "Testing integer value: {$value}\n";

    try {
        $date = Carbon::now();
        $futureDate = $date->addYears($value);
        echo "  Result: " . $futureDate->format('Y-m-d H:i:s') . "\n";
    } catch (Exception $e) {
        echo "  ERROR: " . $e->getMessage() . "\n";
    }

    echo "\n";
}
