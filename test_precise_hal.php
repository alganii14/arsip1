<?php

// Test to verify the improved HAL field extraction stops at the right point
echo "=== Testing Precise HAL Field Extraction ===\n\n";

function findDocumentName($content)
{
    if (empty($content)) {
        return null;
    }

    echo "Analyzing content for document name, length: " . strlen($content) . "\n";

    // Updated patterns with proper stopping points
    $patterns = [
        // Format: Hal : Penyerahan SK Kenaikan Pangkat
        //         Periode Desember 2024 (multi-line support, stop at empty line or next section)
        '/Hal\s*:\s*(.*?)(?=\n\s*\n|\n\s*Kepada|\n\s*Yth\.|\n\s*[A-Z][a-z]*\s*:|$)/s',

        // Format: Subject : ...
        '/Subject\s*:\s*(.*?)(?=\n\s*\n|\n\s*Kepada|\n\s*Yth\.|\n\s*[A-Z][a-z]*\s*:|$)/s',

        // Format: Perihal : ...
        '/Perihal\s*:\s*(.*?)(?=\n\s*\n|\n\s*Kepada|\n\s*Yth\.|\n\s*[A-Z][a-z]*\s*:|$)/s',

        // Format: Tentang : ...
        '/Tentang\s*:\s*(.*?)(?=\n\s*\n|\n\s*Kepada|\n\s*Yth\.|\n\s*[A-Z][a-z]*\s*:|$)/s',

        // Format: Re : ...
        '/Re\s*:\s*([^\n\r]+)/i',
    ];

    foreach ($patterns as $index => $pattern) {
        if (preg_match($pattern, $content, $matches)) {
            echo "✅ Found document name with pattern #" . ($index + 1) . "\n";
            echo "Raw match: '" . $matches[0] . "'\n";
            echo "Captured: '" . $matches[1] . "'\n";

            $documentName = trim($matches[1]);

            // Clean up the document name
            $documentName = cleanDocumentName($documentName);

            if (!empty($documentName)) {
                echo "Final document name: '{$documentName}'\n";
                return $documentName;
            }
        }
    }

    echo "❌ No document name patterns matched\n";
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

// Test case from the actual document structure
echo "=== Test: Document with Kepada section ===\n";
$testContent = "Nomor   : B/KP.06.01/11035-BKPSDM/XI/2024
Sifat   : Biasa
Lampiran: -
Hal     : Penyerahan SK Kenaikan Pangkat
          Periode Desember 2024

Kepada
Yth. Kepala Perangkat Daerah
(Daftar Terlampir)
Di Bandung

Disampaikan dengan hormat, sehubungan telah terselesaikannya proses kenaikan pangkat periode Desember 2024 dengan terbitnya Persetujuan Teknis Kenaikan Pangkat yang menjadi kewenangan Pemerintah Kota Bandung oleh Kantor Regional III Badan Kepegawaian Negara, maka selanjutnya akan dilaksanakan Penyerahan Petikan SK Kenaikan Pangkat periode Desember 2024 yang akan dilaksanakan pada:

Hari/Tanggal: Jumat, 29 November 2024";

$result = findDocumentName($testContent);
echo "Result: '" . ($result ?? 'NULL') . "'\n\n";

echo "=== Test: Document with Yth. section ===\n";
$testContent2 = "Nomor   : B/KP.06.01/11035-BKPSDM/XI/2024
Sifat   : Biasa
Lampiran: -
Hal     : Penyerahan SK Kenaikan Pangkat
          Periode Desember 2024

Yth. Kepala Perangkat Daerah
(Daftar Terlampir)
Di Bandung

Disampaikan dengan hormat, sehubungan telah terselesaikannya proses";

$result2 = findDocumentName($testContent2);
echo "Result: '" . ($result2 ?? 'NULL') . "'\n\n";

echo "=== Test: Document with empty line after HAL ===\n";
$testContent3 = "Hal     : Penyerahan SK Kenaikan Pangkat
          Periode Desember 2024

Disampaikan dengan hormat, sehubungan telah terselesaikannya proses";

$result3 = findDocumentName($testContent3);
echo "Result: '" . ($result3 ?? 'NULL') . "'\n\n";

echo "=== Summary ===\n";
if ($result && !strpos($result, 'Kepada') && !strpos($result, 'Yth') && !strpos($result, 'Disampaikan')) {
    echo "✅ Test 1 PASSED: Correctly stopped at 'Kepada' section\n";
} else {
    echo "❌ Test 1 FAILED: Still capturing content after HAL field\n";
}

if ($result2 && !strpos($result2, 'Yth') && !strpos($result2, 'Disampaikan')) {
    echo "✅ Test 2 PASSED: Correctly stopped at 'Yth.' section\n";
} else {
    echo "❌ Test 2 FAILED: Still capturing content after HAL field\n";
}

if ($result3 && !strpos($result3, 'Disampaikan')) {
    echo "✅ Test 3 PASSED: Correctly stopped at empty line\n";
} else {
    echo "❌ Test 3 FAILED: Still capturing content after HAL field\n";
}

echo "\nExpected result: 'Penyerahan SK Kenaikan Pangkat Periode Desember 2024'\n";
echo "Actual result: '" . ($result ?? 'NULL') . "'\n";

echo "\n=== Test Complete ===\n";

?>
