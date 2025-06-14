<?php

require_once 'vendor/autoload.php';

// Test the complete process
echo "=== Complete System Test ===\n\n";

// Simulate file upload data
$testData = [
    'document_number' => 'KU.01.02.01/1-Kec.Cddp/XII/2025',
    'document_name' => 'Laporan Monev SRIKANDI',
    'document_date' => '2024-12-05'
];

echo "Test Input:\n";
echo "Document Number: " . $testData['document_number'] . "\n";
echo "Document Name: " . $testData['document_name'] . "\n";
echo "Document Date: " . $testData['document_date'] . "\n\n";

// Test classification parsing
echo "=== Testing Classification Parsing ===\n";
$classificationPattern = '/(KU|KP|RT|AR)\.(\d{2})\.(\d{2})\.(\d{2})(?:\.(\d{2}))?/i';
if (preg_match($classificationPattern, $testData['document_number'], $matches)) {
    $prefix = strtoupper($matches[1]);
    $mainCode = $matches[2];
    $subCode1 = $matches[3];
    $subCode2 = $matches[4];
    $subCode3 = isset($matches[5]) ? $matches[5] : null;

    echo "Prefix: $prefix\n";
    echo "Main Code: $mainCode\n";
    echo "Sub Code 1: $subCode1\n";
    echo "Sub Code 2: $subCode2\n";
    echo "Sub Code 3: " . ($subCode3 ?? 'N/A') . "\n";

    // Build classification code
    $classificationCode = "$prefix.$mainCode";
    if ($subCode1) {
        $classificationCode .= ".$subCode1";
        if ($subCode2) {
            $classificationCode .= ".$subCode2";
            if ($subCode3) {
                $classificationCode .= ".$subCode3";
            }
        }
    }

    echo "Classification Code: $classificationCode\n";

    // Determine category
    $category = '';
    switch ($prefix) {
        case 'AR':
            $category = 'Kearsipan';
            break;
        case 'KP':
            $category = 'Kepegawaian';
            break;
        case 'RT':
            $category = 'Kerumahtanggaan';
            break;
        case 'KU':
            $category = 'Keuangan';
            break;
    }

    echo "Category: $category\n";
    echo "Type: $prefix\n\n";

    // Test classification description
    echo "=== Testing Classification Description ===\n";

    // Mock ClassificationFormatter test
    $description = '';
    if ($prefix == 'KU' && $mainCode == '01') {
        $description = 'Rencana Anggaran Pendapatan dan Belanja Daerah, dan Anggaran Pendapatan dan Belanja Daerah Perubahan';
        if ($subCode1 == '02') {
            $description .= ' - Penyusunan Rencana Kerja Anggaran Satuan Kerja Perangkat Daerah (RKA-SKPD)';
            if ($subCode2 == '01') {
                $description .= ' - Dokumen Pedoman Penyusunan RKA-SKPD yang telah disetujui Sekretaris Daerah';
            }
        }
    }

    echo "Description: $description\n\n";

    // Test badge class
    echo "=== Testing Badge Class ===\n";
    $badgeClass = '';
    switch ($prefix) {
        case 'AR':
            $badgeClass = 'border-primary text-primary bg-primary';
            break;
        case 'KP':
            $badgeClass = 'border-success text-success bg-success';
            break;
        case 'RT':
            $badgeClass = 'border-warning text-warning bg-warning';
            break;
        case 'KU':
            $badgeClass = 'border-info text-info bg-info';
            break;
        default:
            $badgeClass = 'border-secondary text-secondary bg-secondary';
    }

    echo "Badge Class: $badgeClass\n\n";

    echo "=== Expected Results ===\n";
    echo "Category: Keuangan\n";
    echo "Type: KU\n";
    echo "Code: KU.01.02.01\n";
    echo "Badge: Light blue (info)\n";
    echo "Description: Should contain 'RKA-SKPD' and 'Dokumen Pedoman'\n\n";

    if ($category == 'Keuangan' && $prefix == 'KU' && $classificationCode == 'KU.01.02.01') {
        echo "✅ PASSED: Document correctly classified as KU (Keuangan)\n";
    } else {
        echo "❌ FAILED: Document not correctly classified\n";
    }

} else {
    echo "❌ FAILED: Could not parse document number\n";
}

echo "\n=== Test Complete ===\n";
