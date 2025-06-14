<?php

// Test the document extraction API response
echo "=== Testing Document Extraction API Response ===\n\n";

// Simulate the classification function
function determineClassificationFromNumber($documentNumber) {
    $category = 'Dokumen Arsip';
    $type = 'AR';
    $code = null;

    // Test the pattern that should match KU.01.02.01
    if (preg_match('/(KU|KP|RT|AR)\.(\d{2})\.(\d{2})\.(\d{2})(?:\.(\d{2}))?/i', $documentNumber, $matches)) {
        $prefix = strtoupper($matches[1]);
        $mainCode = isset($matches[2]) ? $matches[2] : null;
        $subCode1 = isset($matches[3]) ? $matches[3] : null;
        $subCode2 = isset($matches[4]) ? $matches[4] : null;
        $subCode3 = isset($matches[5]) ? $matches[5] : null;

        if ($mainCode) {
            $code = "$prefix.$mainCode";
            $type = $prefix;

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

            if ($subCode1) {
                $code .= ".$subCode1";
                if ($subCode2) {
                    $code .= ".$subCode2";
                    if ($subCode3) {
                        $code .= ".$subCode3";
                    }
                }
            }
        }
    }

    return [
        'category' => $category,
        'type' => $type,
        'code' => $code
    ];
}

// Test with the KU document number
$testDocumentNumber = 'KU.01.02.01/1-Kec.Cddp/XII/2025';
echo "Testing document number: $testDocumentNumber\n\n";

$classification = determineClassificationFromNumber($testDocumentNumber);

echo "Classification result:\n";
echo "  Category: " . $classification['category'] . "\n";
echo "  Type: " . $classification['type'] . "\n";
echo "  Code: " . ($classification['code'] ?? 'NULL') . "\n\n";

// Mock description function
$description = '';
if ($classification['code'] == 'KU.01.02.01') {
    $description = 'Rencana Anggaran Pendapatan dan Belanja Daerah, dan Anggaran Pendapatan dan Belanja Daerah Perubahan - Penyusunan Rencana Kerja Anggaran Satuan Kerja Perangkat Daerah (RKA-SKPD) - Dokumen Pedoman Penyusunan RKA-SKPD yang telah disetujui Sekretaris Daerah';
}

$classification['description'] = $description;

// Simulate the API response
$apiResponse = [
    'success' => true,
    'documentNumber' => $testDocumentNumber,
    'documentDate' => '2024-12-05',
    'documentName' => 'Laporan Monev SRIKANDI',
    'classification' => $classification
];

echo "Expected API Response:\n";
echo json_encode($apiResponse, JSON_PRETTY_PRINT) . "\n\n";

// Test if the classification code would match the dropdown option
$dropdownOptions = [
    'KU.01.02.01' => '01.02.01 - Dokumen Pedoman Penyusunan RKA-SKPD yang telah disetujui Sekretaris Daerah'
];

echo "Testing dropdown matching:\n";
if (isset($dropdownOptions[$classification['code']])) {
    echo "✅ FOUND: Option for '{$classification['code']}' exists in dropdown\n";
    echo "   Option text: " . $dropdownOptions[$classification['code']] . "\n";
} else {
    echo "❌ NOT FOUND: Option for '{$classification['code']}' missing in dropdown\n";
}

echo "\n=== Test Complete ===\n";
