<?php

// Test with working multi-line pattern
echo "=== Testing Working Multi-line HAL Field Pattern ===\n\n";

$test = "Hal     : Penyerahan SK Kenaikan Pangkat
          Periode Desember 2024";

echo "Original content:\n";
echo "'" . $test . "'\n\n";

// Test patterns one by one with detailed debugging
$patterns = [
    '/Hal\s*:\s*([^\n\r]+(?:\n\s+[^\n\r:]+)*)/i',
    '/Hal\s*:\s*([^\n\r]+(?:\n\s*[^\n\r:]+)*)/i',
    '/Hal\s*:\s*(.*?)(?=\n\s*$|\n\s*[A-Z]|\n\n)/s',
    '/Hal\s*:\s*([^\n\r]+(?:\n[ \t]+[^\n\r:]+)*)/i',
    '/Hal\s*:\s*([^\n\r]+(?:\n[ \t]*[^\n\r:]+)*)/i',
];

foreach ($patterns as $i => $pattern) {
    echo "Pattern " . ($i + 1) . ": " . $pattern . "\n";

    if (preg_match($pattern, $test, $matches)) {
        echo "✅ MATCH!\n";
        echo "Full match: '" . $matches[0] . "'\n";
        echo "Captured: '" . $matches[1] . "'\n";

        // Clean it up
        $clean = preg_replace('/\s*\n\s*/', ' ', $matches[1]);
        $clean = preg_replace('/\s+/', ' ', $clean);
        $clean = trim($clean);

        echo "Cleaned: '" . $clean . "'\n";

        // Check if it includes both lines
        if (strpos($matches[0], "Periode Desember 2024") !== false) {
            echo "✅ Includes second line!\n";
        } else {
            echo "❌ Does not include second line\n";
        }
    } else {
        echo "❌ No match\n";
    }
    echo "\n";
}

// Test with the most promising pattern using DOTALL flag
echo "=== Test with DOTALL flag ===\n";
$dotallPattern = '/Hal\s*:\s*(.*?)(?=\n\s*$|\n\s*[A-Z][a-z]*\s*:|$)/s';
echo "Pattern: " . $dotallPattern . "\n";

if (preg_match($dotallPattern, $test, $matches)) {
    echo "✅ DOTALL MATCH!\n";
    echo "Full match: '" . $matches[0] . "'\n";
    echo "Captured: '" . $matches[1] . "'\n";

    // Clean it up
    $clean = preg_replace('/\s*\n\s*/', ' ', $matches[1]);
    $clean = preg_replace('/\s+/', ' ', $clean);
    $clean = trim($clean);

    echo "Cleaned: '" . $clean . "'\n";
} else {
    echo "❌ DOTALL pattern failed\n";
}

?>
