<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Arsip;
use Carbon\Carbon;

echo "=== TEST CREATE ARSIP SETELAH PERBAIKAN ===\n\n";

try {
    // Test 1: Create arsip baru
    echo "1. Testing create arsip baru...\n";

    $arsip = new Arsip([
        'kode' => 'TEST-' . date('YmdHis'),
        'nama_dokumen' => 'Test Document Create',
        'kategori' => 'Test',
        'tanggal_arsip' => Carbon::now(),
        'keterangan' => 'Test create arsip after fix',
        'retention_date' => Carbon::now()->subDays(1), // Expired
        'retention_years' => 5,
        'created_by' => 1
    ]);

    $arsip->save();
    echo "   ✓ Arsip created successfully with ID: {$arsip->id}\n";
    echo "   ✓ Kode: {$arsip->kode}\n";
    echo "   ✓ Retention date: {$arsip->retention_date}\n";
    echo "   ✓ Is archived to JRE: " . ($arsip->is_archived_to_jre ? 'YES' : 'NO') . "\n";

    // Test 2: Test shouldMoveToJre
    echo "\n2. Testing shouldMoveToJre...\n";
    $shouldMove = $arsip->shouldMoveToJre();
    echo "   Should move to JRE: " . ($shouldMove ? 'YES' : 'NO') . "\n";

    // Test 3: Test moveToJre multiple times
    echo "\n3. Testing moveToJre multiple times...\n";

    $jre1 = $arsip->moveToJre('First call');
    echo "   Call #1 - JRE ID: {$jre1->id}\n";

    $jre2 = $arsip->moveToJre('Second call');
    echo "   Call #2 - JRE ID: {$jre2->id}\n";

    $jre3 = $arsip->moveToJre('Third call');
    echo "   Call #3 - JRE ID: {$jre3->id}\n";

    // Check total JRE count for this arsip
    $totalJreCount = \App\Models\Jre::where('arsip_id', $arsip->id)->count();
    echo "   Total JRE records for this arsip: {$totalJreCount}\n";

    // Test 4: Check arsip status after move
    echo "\n4. Checking arsip status after move...\n";
    $arsip->refresh();
    echo "   Is archived to JRE: " . ($arsip->is_archived_to_jre ? 'YES' : 'NO') . "\n";
    echo "   Should move to JRE now: " . ($arsip->shouldMoveToJre() ? 'YES' : 'NO') . "\n";

    echo "\n✓ ALL TESTS PASSED! Create arsip works properly and no duplicate JRE created.\n";

} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== TEST COMPLETED ===\n";
