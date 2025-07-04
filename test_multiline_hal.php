<?php

// Test with actual multi-line format like in the document
echo "=== Testing Multi-line HAL Field Extraction ===\n\n";

function findDocumentName($content)
{
    if (empty($content)) {
        return null;
    }

    echo "Analyzing content for document name, length: " . strlen($content) . "\n";

    // Improved pattern for multi-line HAL field
    $patterns = [
        // Format: Hal : Penyerahan SK Kenaikan Pangkat
        //         Periode Desember 2024 (multi-line support)
        '/Hal\s*:\s*([^\n\r]+(?:\n\s{6,}[^\n\r:]+)*)/i',

        // Alternative pattern to catch continuation lines
        '/Hal\s*:\s*([^\n\r]+(?:\n\s+[^\n\r:]+)*)/i',
    ];

    foreach ($patterns as $index => $pattern) {
        echo "Testing pattern #" . ($index + 1) . ": " . $pattern . "\n";

        if (preg_match($pattern, $content, $matches)) {
            echo "✅ Found match with pattern #" . ($index + 1) . "\n";
            echo "Raw match: '" . $matches[0] . "'\n";
            echo "Captured group: '" . $matches[1] . "'\n";

            $documentName = trim($matches[1]);

            // Clean up the document name
            $documentName = cleanDocumentName($documentName);

            if (!empty($documentName)) {
                echo "Final cleaned document name: '{$documentName}'\n";
                return $documentName;
            }
        } else {
            echo "❌ No match with pattern #" . ($index + 1) . "\n";
        }
    }

    return null;
}

function cleanDocumentName($name)
{
    // Remove common prefixes
    $name = preg_replace('/^(Hal\s*:\s*|Subject\s*:\s*|Perihal\s*:\s*|Tentang\s*:\s*|Re\s*:\s*)/i', '', $name);

    // Handle multi-line text - replace newlines with spaces
    $name = preg_replace('/\s*\n\s*/', ' ', $name);

    // Remove extra whitespace
    $name = preg_replace('/\s+/', ' ', $name);

    // Trim
    $name = trim($name);

    // Remove trailing punctuation
    $name = rtrim($name, '.,;:-');

    return $name;
}

// Test case 1: Single line
echo "=== Test 1: Single line HAL field ===\n";
$test1 = "Hal     : Penyerahan SK Kenaikan Pangkat";
$result1 = findDocumentName($test1);
echo "Result: " . ($result1 ?? 'NULL') . "\n\n";

// Test case 2: Multi-line (actual format from document)
echo "=== Test 2: Multi-line HAL field ===\n";
$test2 = "Hal     : Penyerahan SK Kenaikan Pangkat
          Periode Desember 2024";
$result2 = findDocumentName($test2);
echo "Result: " . ($result2 ?? 'NULL') . "\n\n";

// Test case 3: Multi-line with following content
echo "=== Test 3: Multi-line HAL field with following content ===\n";
$test3 = "Nomor   : B/KP.06.01/11035-BKPSDM/XI/2024
Sifat   : Biasa
Lampiran: -
Hal     : Penyerahan SK Kenaikan Pangkat
          Periode Desember 2024

        Disampaikan     dengan      hormat,     sehubungan     telah
terselesaikannya proses kenaikan pangkat periode Desember 2024";
$result3 = findDocumentName($test3);
echo "Result: " . ($result3 ?? 'NULL') . "\n\n";

echo "=== Test Complete ===\n";
