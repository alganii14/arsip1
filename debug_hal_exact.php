<?php

// Test with exact content structure from document
echo "=== Testing Multi-line HAL Field with Exact Structure ===\n\n";

$test = "Hal     : Penyerahan SK Kenaikan Pangkat
          Periode Desember 2024";

echo "Testing the pattern that should work:\n";
$pattern = '/Hal\s*:\s*([^\n\r]+(?:\n\s+[^\n\r:]+)*)/i';

echo "Pattern: " . $pattern . "\n";

if (preg_match($pattern, $test, $matches)) {
    echo "✅ MATCH!\n";
    echo "Full match: '" . $matches[0] . "'\n";
    echo "Captured group 1: '" . $matches[1] . "'\n";

    // Check if there are more capture groups
    for ($i = 2; $i < count($matches); $i++) {
        echo "Captured group $i: '" . $matches[$i] . "'\n";
    }

    echo "\nMatch details:\n";
    echo "Match starts at position: " . strpos($test, $matches[0]) . "\n";
    echo "Match length: " . strlen($matches[0]) . "\n";

    // Now let's see if the pattern really captures the second line
    echo "\nChecking if second line is included:\n";
    $secondLinePos = strpos($test, "Periode Desember 2024");
    $matchEndPos = strpos($test, $matches[0]) + strlen($matches[0]);

    echo "Second line starts at: " . $secondLinePos . "\n";
    echo "Match ends at: " . $matchEndPos . "\n";

    if ($matchEndPos > $secondLinePos) {
        echo "✅ Second line IS included in match!\n";
    } else {
        echo "❌ Second line is NOT included in match\n";
    }

} else {
    echo "❌ No match\n";
}

// Let's try a different approach - manual parsing
echo "\n=== Manual Pattern Testing ===\n";
$manualPattern = '/Hal\s*:\s*(.+?)(?=\n\s*$|\n\s*[A-Z][a-z]*\s*:)/s';
echo "Manual pattern: " . $manualPattern . "\n";

if (preg_match($manualPattern, $test, $matches)) {
    echo "✅ Manual pattern MATCH!\n";
    echo "Full match: '" . $matches[0] . "'\n";
    echo "Captured: '" . $matches[1] . "'\n";

    // Clean it up
    $clean = preg_replace('/\s*\n\s*/', ' ', $matches[1]);
    $clean = preg_replace('/\s+/', ' ', $clean);
    $clean = trim($clean);

    echo "Cleaned: '" . $clean . "'\n";
} else {
    echo "❌ Manual pattern failed\n";
}

?>
