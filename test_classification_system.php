<?php

require_once 'vendor/autoload.php';
require_once 'app/Helpers/ClassificationFormatter.php';
require_once 'app/Helpers/DocumentNumberExtractor.php';

use App\Helpers\ClassificationFormatter;
use App\Helpers\DocumentNumberExtractor;

echo "=== TESTING CLASSIFICATION SYSTEM ===\n\n";

// Test classification codes and their descriptions
$testCodes = [
    // AR codes
    'AR.01' => 'AR - Kebijakan',
    'AR.01.01' => 'AR - Kebijakan - Peraturan Daerah',
    'AR.01.01.01' => 'AR - Kebijakan - Peraturan Daerah - Pengkajian dan Pengusulan',
    'AR.02.01' => 'AR - Pembinaan Kearsipan - Bina Arsiparis',

    // KP codes
    'KP.01' => 'KP - Persediaan Pegawai',
    'KP.02' => 'KP - Formasi Pegawai',
    'KP.02.01' => 'KP - Formasi Pegawai - Usulan Unit Kerja',
    'KP.06.01' => 'KP - Mutasi - Kenaikan Pangkat/Golongan',
    'KP.18.01' => 'KP - Organisasi Non Kedinasan - KORPRI',

    // RT codes
    'RT.01' => 'RT - Perjalanan Dinas Pimpinan',
    'RT.01.01' => 'RT - Perjalanan Dinas Pimpinan - Dalam Negeri',
    'RT.03' => 'RT - Kantor',
    'RT.03.01' => 'RT - Kantor - Pemeliharaan Gedung',
    'RT.05.01' => 'RT - Fasilitas Pimpinan - Kendaraan Dinas',

    // KU codes
    'KU.01' => 'KU - Rencana Anggaran Pendapatan dan Belanja Daerah, dan Anggaran Pendapatan dan Belanja Daerah Perubahan',
    'KU.01.01' => 'KU - RAPBD - Penyusunan Prioritas Plafon Anggaran',
    'KU.01.01.01' => 'KU - RAPBD - Prioritas Plafon - Kebijakan Umum, Strategi, Prioritas dan Renstra',
    'KU.02' => 'KU - Penyusunan Anggaran',
    'KU.02.01' => 'KU - Penyusunan Anggaran - Hasil Musyawarah Rencana Pembangunan (Musrenbang) Kelurahan',
];

echo "1. TESTING CLASSIFICATION DESCRIPTIONS:\n";
echo "=" . str_repeat("=", 80) . "\n";

foreach ($testCodes as $code => $expected) {
    $description = ClassificationFormatter::getDescription($code);
    $status = !empty($description) ? "✓ PASS" : "✗ FAIL";

    echo sprintf("%-15s | %-60s | %s\n", $code,
        strlen($description) > 60 ? substr($description, 0, 57) . "..." : $description,
        $status
    );
}

echo "\n2. TESTING BADGE COLORS:\n";
echo "=" . str_repeat("=", 50) . "\n";

$badgeTests = [
    'AR.01.01' => 'border-primary text-primary bg-primary',
    'KP.02.01' => 'border-success text-success bg-success',
    'RT.03.01' => 'border-warning text-warning bg-warning',
    'KU.01.01' => 'border-info text-info bg-info'
];

foreach ($badgeTests as $code => $expected) {
    $actual = ClassificationFormatter::getBadgeClass($code);
    $status = $actual === $expected ? "✓ PASS" : "✗ FAIL";

    echo sprintf("%-10s | %-40s | %s\n", $code, $actual, $status);
}

echo "\n3. TESTING DOCUMENT NUMBER EXTRACTION:\n";
echo "=" . str_repeat("=", 70) . "\n";

$extractionTests = [
    'Nomor: AR.01.01/123-Kec.Cddp/XII/2024' => 'AR.01.01/123-Kec.Cddp/XII/2024',
    'Nomor: KP.02.01/456-Kec.Cddp/I/2024' => 'KP.02.01/456-Kec.Cddp/I/2024',
    'Nomor: RT.03.01/789-Kec.Cddp/VI/2024' => 'RT.03.01/789-Kec.Cddp/VI/2024',
    'Nomor: KU.01.01/101-Kec.Cddp/III/2024' => 'KU.01.01/101-Kec.Cddp/III/2024',
    'No: AR.02.03/222-Kec.Test/V/2023' => 'AR.02.03/222-Kec.Test/V/2023',
    'KP.06.01/333-Kec.Test/XI/2024' => 'KP.06.01/333-Kec.Test/XI/2024'
];

foreach ($extractionTests as $input => $expected) {
    $extracted = DocumentNumberExtractor::findDocumentNumber($input);
    $status = $extracted === $expected ? "✓ PASS" : "✗ FAIL";

    echo sprintf("%-40s | %-30s | %s\n",
        strlen($input) > 40 ? substr($input, 0, 37) . "..." : $input,
        $extracted ?: 'NULL',
        $status
    );
}

echo "\n4. TESTING DOCUMENT NUMBER FORMATTING:\n";
echo "=" . str_repeat("=", 70) . "\n";

$formatTests = [
    'AR.01.01' => true,
    'KP.02.01' => true,
    'RT.03.01' => true,
    'KU.01.01' => true,
    'Nomor: AR.01.01/123-Kec.Cddp/XII/2024' => true,
    'AR01.01/123' => true
];

foreach ($formatTests as $input => $shouldFormat) {
    $formatted = DocumentNumberExtractor::formatDocumentNumber($input);
    $status = !empty($formatted) ? "✓ PASS" : "✗ FAIL";

    echo sprintf("%-40s | %-25s | %s\n",
        strlen($input) > 40 ? substr($input, 0, 37) . "..." : $input,
        strlen($formatted) > 25 ? substr($formatted, 0, 22) . "..." : $formatted,
        $status
    );
}

echo "\n=== SUMMARY ===\n";
echo "Classification system test completed.\n";
echo "Review the results above to identify any issues that need fixing.\n";

// Additional validation for edge cases
echo "\n5. TESTING EDGE CASES:\n";
echo "=" . str_repeat("=", 50) . "\n";

$edgeCases = [
    '' => 'Empty string',
    'INVALID.CODE' => 'Invalid format',
    'AR' => 'Code without numbers',
    'KP.99' => 'Non-existent main code',
    'RT.01.99' => 'Non-existent sub code',
    'KU.01.01.99' => 'Non-existent sub-sub code'
];

foreach ($edgeCases as $code => $description) {
    $result = ClassificationFormatter::getDescription($code);
    $badge = ClassificationFormatter::getBadgeClass($code);

    echo sprintf("%-15s | %-20s | Desc: %-10s | Badge: %s\n",
        $code,
        $description,
        !empty($result) ? "Found" : "Empty",
        $badge
    );
}

echo "\nTest completed!\n";
?>
