<?php

// Debug the exact content structure
echo "=== Debugging Multi-line HAL Field Structure ===\n\n";

$test2 = "Hal     : Penyerahan SK Kenaikan Pangkat
          Periode Desember 2024";

echo "Content length: " . strlen($test2) . "\n";
echo "Content bytes: ";
for ($i = 0; $i < strlen($test2); $i++) {
    echo ord($test2[$i]) . " ";
}
echo "\n";

echo "Content with visible whitespace (. = space, \\n = newline):\n";
$visible = str_replace([' ', "\n", "\r"], ['.', '\\n', '\\r'], $test2);
echo "'" . $visible . "'\n\n";

echo "Line by line breakdown:\n";
$lines = explode("\n", $test2);
foreach ($lines as $i => $line) {
    echo "Line $i: '" . $line . "' (length: " . strlen($line) . ")\n";
    echo "  Leading spaces: " . (strlen($line) - strlen(ltrim($line))) . "\n";
}

echo "\n=== Testing Various Patterns ===\n";

$patterns = [
    '/Hal\s*:\s*([^\n\r]+(?:\n\s{6,}[^\n\r:]+)*)/i',
    '/Hal\s*:\s*([^\n\r]+(?:\n\s+[^\n\r:]+)*)/i',
    '/Hal\s*:\s*([^\n\r]+(?:\n\s*[^\n\r:]+)*)/i',
    '/Hal\s*:\s*([^\n\r]+(?:\n[\s]*[^\n\r:]+)*)/i',
    '/Hal\s*:\s*([^\n\r]+(?:\n.*?[^\n\r:]+)*?)(?=\n\s*$|\n\s*[A-Z]|\n\s*\w+\s*:)/i',
];

foreach ($patterns as $i => $pattern) {
    echo "Pattern " . ($i + 1) . ": " . $pattern . "\n";
    if (preg_match($pattern, $test2, $matches)) {
        echo "✅ MATCH!\n";
        echo "  Full match: '" . $matches[0] . "'\n";
        echo "  Captured: '" . $matches[1] . "'\n";
    } else {
        echo "❌ No match\n";
    }
    echo "\n";
}

echo "=== Final Test ===\n";
// Test the most promising pattern
$finalPattern = '/Hal\s*:\s*([^\n\r]+(?:\n\s+[^\n\r:]+)*)/i';
if (preg_match($finalPattern, $test2, $matches)) {
    echo "✅ Final pattern works!\n";
    echo "Raw match: '" . $matches[0] . "'\n";
    echo "Captured: '" . $matches[1] . "'\n";

    // Clean it up
    $clean = preg_replace('/\s*\n\s*/', ' ', $matches[1]);
    $clean = preg_replace('/\s+/', ' ', $clean);
    $clean = trim($clean);

    echo "Cleaned: '" . $clean . "'\n";
} else {
    echo "❌ Final pattern failed\n";
}

?>
