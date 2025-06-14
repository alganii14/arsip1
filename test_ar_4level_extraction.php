<?php

require_once 'bootstrap/app.php';

use App\Helpers\DocumentNumberExtractor;

echo "=== Testing AR 4-Level Code Extraction Priority ===\n\n";

// Test 1: Teks yang berisi AR 4-level dan AR 3-level
$testContent1 = "
NOTA DINAS
Nomor: AR.01.01.02/1-Kec.Cddp/XII/2025
Tanggal: 15 Desember 2025

Kepada: Bupati Cidrap
Dari: Camat Cidrap

Perihal: Laporan Kegiatan MONEV SRIKANDI

Dalam rangka pelaksanaan monitoring dan evaluasi program SRIKANDI...

Footer dokumen: AR.03.09/2082-Disarpus/XI/2024
";

echo "Test 1: Konten dengan AR 4-level dan AR 3-level\n";
echo "Content preview: " . substr(str_replace("\n", " ", $testContent1), 0, 100) . "...\n";

$extracted1 = DocumentNumberExtractor::findDocumentNumber($testContent1);
echo "Extracted: " . ($extracted1 ?: 'NULL') . "\n";

if ($extracted1) {
    $formatted1 = DocumentNumberExtractor::formatDocumentNumber($extracted1);
    echo "Formatted: " . $formatted1 . "\n";

    // Verifikasi apakah ini AR 4-level
    if (preg_match('/^AR\.\d{2}\.\d{2}\.\d{2}/', $formatted1)) {
        echo "✅ PASS: AR 4-level correctly prioritized\n";
    } else {
        echo "❌ FAIL: AR 4-level NOT prioritized\n";
    }
} else {
    echo "❌ FAIL: No document number extracted\n";
}

echo "\n" . str_repeat("-", 50) . "\n\n";

// Test 2: Teks yang hanya berisi AR 4-level
$testContent2 = "
SURAT KEPUTUSAN
Nomor: AR.01.01.02/1-Kec.Cddp/XII/2025

Tentang: Penetapan Tim Monitoring
";

echo "Test 2: Konten dengan hanya AR 4-level\n";
echo "Content preview: " . substr(str_replace("\n", " ", $testContent2), 0, 100) . "...\n";

$extracted2 = DocumentNumberExtractor::findDocumentNumber($testContent2);
echo "Extracted: " . ($extracted2 ?: 'NULL') . "\n";

if ($extracted2) {
    $formatted2 = DocumentNumberExtractor::formatDocumentNumber($extracted2);
    echo "Formatted: " . $formatted2 . "\n";

    // Verifikasi apakah ini AR 4-level
    if (preg_match('/^AR\.\d{2}\.\d{2}\.\d{2}/', $formatted2)) {
        echo "✅ PASS: AR 4-level correctly extracted\n";
    } else {
        echo "❌ FAIL: AR 4-level NOT extracted correctly\n";
    }
} else {
    echo "❌ FAIL: No document number extracted\n";
}

echo "\n" . str_repeat("-", 50) . "\n\n";

// Test 3: Multiple AR codes dalam konten
$testContent3 = "
Referensi dokumen sebelumnya: AR.03.09/2082-Disarpus/XI/2024

NOTA DINAS BARU
Nomor: AR.01.01.02/1-Kec.Cddp/XII/2025
Tanggal: 15 Desember 2025

Terkait juga dengan AR.02.01/500-Sekda/X/2024
";

echo "Test 3: Multiple AR codes - should prioritize AR 4-level\n";
echo "Content preview: " . substr(str_replace("\n", " ", $testContent3), 0, 100) . "...\n";

$extracted3 = DocumentNumberExtractor::findDocumentNumber($testContent3);
echo "Extracted: " . ($extracted3 ?: 'NULL') . "\n";

if ($extracted3) {
    $formatted3 = DocumentNumberExtractor::formatDocumentNumber($extracted3);
    echo "Formatted: " . $formatted3 . "\n";

    // Verifikasi apakah ini AR 4-level yang benar
    if ($formatted3 === 'AR.01.01.02/1-Kec.Cddp/XII/2025') {
        echo "✅ PASS: Correct AR 4-level prioritized\n";
    } else {
        echo "❌ FAIL: Wrong AR code prioritized\n";
    }
} else {
    echo "❌ FAIL: No document number extracted\n";
}

echo "\n" . str_repeat("-", 50) . "\n\n";

// Test 4: Test dengan filename yang mengandung pola khusus
$testFilename = "DRAFT NODIN MONEV SRIKANDI AR.01.01.02 Kec.Cddp.pdf";

echo "Test 4: Filename analysis\n";
echo "Filename: " . $testFilename . "\n";

$extractedFromFilename = DocumentNumberExtractor::findDocumentNumber($testFilename);
echo "Extracted from filename: " . ($extractedFromFilename ?: 'NULL') . "\n";

if ($extractedFromFilename) {
    $formattedFromFilename = DocumentNumberExtractor::formatDocumentNumber($extractedFromFilename);
    echo "Formatted: " . $formattedFromFilename . "\n";
    echo "✅ PASS: Successfully extracted from filename\n";
} else {
    echo "❌ FAIL: No document number extracted from filename\n";
}

echo "\n=== Test Complete ===\n";
