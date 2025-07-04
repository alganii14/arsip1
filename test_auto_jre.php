<?php

require_once 'vendor/autoload.php';

use App\Models\Arsip;
use Carbon\Carbon;

echo "Testing Auto JRE System\n";
echo "======================\n\n";

// Test 1: Check if there are any expired arsips
echo "1. Checking for expired arsips...\n";
$expiredArsips = Arsip::where('is_archived_to_jre', false)
    ->whereNotNull('retention_date')
    ->whereDate('retention_date', '<=', Carbon::now())
    ->get();

echo "Found " . $expiredArsips->count() . " expired arsips.\n\n";

// Test 2: List expired arsips
if ($expiredArsips->count() > 0) {
    echo "2. Expired arsips details:\n";
    foreach ($expiredArsips as $arsip) {
        echo "- Kode: {$arsip->kode}\n";
        echo "  Nama: {$arsip->nama_dokumen}\n";
        $retentionDate = $arsip->retention_date ? $arsip->retention_date->format('Y-m-d') : 'N/A';
        echo "  Retention Date: {$retentionDate}\n";
        echo "  Should Move to JRE: " . ($arsip->shouldMoveToJre() ? 'Yes' : 'No') . "\n";
        echo "  Already in JRE: " . ($arsip->is_archived_to_jre ? 'Yes' : 'No') . "\n\n";
    }
}

// Test 3: Check JRE records
echo "3. Checking JRE records...\n";
$jreCount = \App\Models\Jre::count();
echo "Total JRE records: {$jreCount}\n\n";

// Test 4: Test middleware cache
echo "4. Testing cache system...\n";
$cacheKey = 'arsip_retention_check_' . now()->format('Y-m-d-H-i');
$cached = cache()->get($cacheKey);
echo "Cache status: " . ($cached ? 'Active' : 'Not active') . "\n";

echo "\nTest completed.\n";
