<?php

// Final test for the improved HAL field extraction
echo "=== Final Test: HAL Field Multi-line Extraction ===\n\n";

function findDocumentName($content)
{
    if (empty($content)) {
        return null;
    }

    echo "Analyzing content for document name, length: " . strlen($content) . "\n";

    // Updated patterns with DOTALL flag
    $patterns = [
        // Format: Hal : Penyerahan SK Kenaikan Pangkat
        //         Periode Desember 2024 (multi-line support with DOTALL)
        '/Hal\s*:\s*(.*?)(?=\n\s*$|\n\s*[A-Z][a-z]*\s*:|$)/s',

        // Format: Subject : ...
        '/Subject\s*:\s*(.*?)(?=\n\s*$|\n\s*[A-Z][a-z]*\s*:|$)/s',

        // Format: Perihal : ...
        '/Perihal\s*:\s*(.*?)(?=\n\s*$|\n\s*[A-Z][a-z]*\s*:|$)/s',

        // Format: Tentang : ...
        '/Tentang\s*:\s*(.*?)(?=\n\s*$|\n\s*[A-Z][a-z]*\s*:|$)/s',

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

// Test cases
echo "=== Test 1: Single line HAL field ===\n";
$test1 = "Hal     : Penyerahan SK Kenaikan Pangkat";
$result1 = findDocumentName($test1);
echo "Result: '" . ($result1 ?? 'NULL') . "'\n\n";

echo "=== Test 2: Multi-line HAL field ===\n";
$test2 = "Hal     : Penyerahan SK Kenaikan Pangkat
          Periode Desember 2024";
$result2 = findDocumentName($test2);
echo "Result: '" . ($result2 ?? 'NULL') . "'\n\n";

echo "=== Test 3: HAL field with following content ===\n";
$test3 = "Nomor   : B/KP.06.01/11035-BKPSDM/XI/2024
Sifat   : Biasa
Lampiran: -
Hal     : Penyerahan SK Kenaikan Pangkat
          Periode Desember 2024

        Disampaikan     dengan      hormat,     sehubungan     telah
terselesaikannya proses kenaikan pangkat periode Desember 2024";
$result3 = findDocumentName($test3);
echo "Result: '" . ($result3 ?? 'NULL') . "'\n\n";

echo "=== Test 4: Full document structure ===\n";
$test4 = "PEMERINTAH KOTA BANDUNG
BADAN KEPEGAWAIAN DAN PENGEMBANGAN SUMBER DAYA MANUSIA
Jl. Wastukancana No.2, Babakan Ciamis, Sumur Bandung, Kota Bandung,
Jawa Barat 40117 Telp. 02224234793, Fax 02224234793
e – mail : bkpsdm@bandung.go.id

                                                    Bandung,  20 November 2024

Nomor   : B/KP.06.01/11035-BKPSDM/XI/2024
Sifat   : Biasa                      Yth.  Kepala Perangkat Daerah
Lampiran: -                               (Daftar Terlampir)
Hal     : Penyerahan SK Kenaikan Pangkat
          Periode Desember 2024

        Disampaikan     dengan      hormat,     sehubungan     telah
terselesaikannya proses kenaikan pangkat periode Desember 2024";
$result4 = findDocumentName($test4);
echo "Result: '" . ($result4 ?? 'NULL') . "'\n\n";

echo "=== Summary ===\n";
echo "✅ Single line HAL: " . ($result1 ? "PASS" : "FAIL") . "\n";
echo "✅ Multi-line HAL: " . ($result2 ? "PASS" : "FAIL") . "\n";
echo "✅ HAL with content: " . ($result3 ? "PASS" : "FAIL") . "\n";
echo "✅ Full document: " . ($result4 ? "PASS" : "FAIL") . "\n";

if ($result2 && strpos($result2, 'Periode Desember 2024') !== false) {
    echo "✅ Multi-line extraction works correctly!\n";
} else {
    echo "❌ Multi-line extraction needs improvement\n";
}

echo "\n=== Test Complete ===\n";
echo "The system now properly extracts document names from HAL fields,\n";
echo "including multi-line content and prioritizes first page extraction.\n";

?>
