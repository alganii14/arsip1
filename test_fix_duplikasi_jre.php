<?php

require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Models\Arsip;
use App\Models\Jre;

// Setup database connection
$capsule = new Capsule();
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'arsip3',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

echo "=== TEST PERBAIKAN DUPLIKASI JRE ===\n\n";

// Find an arsip that might trigger auto-move to JRE
$arsipExpired = Arsip::where('retention_date', '<=', now())
                    ->where('is_archived_to_jre', false)
                    ->first();

if (!$arsipExpired) {
    echo "Tidak ada arsip yang expired untuk ditest.\n";

    // Create a test arsip with expired retention date
    $testArsip = new Arsip();
    $testArsip->kode = 'TEST-' . date('YmdHis');
    $testArsip->nama_dokumen = 'Test Dokumen Duplikasi JRE';
    $testArsip->kategori = 'Test';
    $testArsip->tanggal_arsip = now()->subDays(10);
    $testArsip->retention_date = now()->subDays(1); // Already expired
    $testArsip->retention_years = 1;
    $testArsip->is_archived_to_jre = false;
    $testArsip->save();

    $arsipExpired = $testArsip;
    echo "Created test arsip with ID: {$arsipExpired->id}\n";
}

echo "Testing dengan Arsip ID: {$arsipExpired->id}\n";
echo "Nama Dokumen: {$arsipExpired->nama_dokumen}\n";
echo "Retention Date: " . $arsipExpired->retention_date . "\n";
echo "Is Archived to JRE: " . ($arsipExpired->is_archived_to_jre ? 'Yes' : 'No') . "\n\n";

// Check current JRE count for this arsip
$currentJreCount = Jre::where('arsip_id', $arsipExpired->id)->count();
echo "Current JRE count for this arsip: {$currentJreCount}\n";

// Test shouldMoveToJre method
echo "\n=== TEST shouldMoveToJre() ===\n";
$shouldMove = $arsipExpired->shouldMoveToJre();
echo "shouldMoveToJre(): " . ($shouldMove ? 'YES' : 'NO') . "\n";

// If it should move, test the moveToJre method multiple times
if ($shouldMove) {
    echo "\n=== TEST moveToJre() - Multiple Calls ===\n";

    // Call moveToJre multiple times to see if it creates duplicates
    for ($i = 1; $i <= 3; $i++) {
        echo "Call #$i to moveToJre():\n";

        $jre = $arsipExpired->moveToJre("Test call #$i");

        if ($jre) {
            echo "  JRE created/returned with ID: {$jre->id}\n";
            echo "  JRE status: {$jre->status}\n";
        } else {
            echo "  No JRE returned\n";
        }

        // Check total JRE count after each call
        $jreCount = Jre::where('arsip_id', $arsipExpired->id)->count();
        echo "  Total JRE count for this arsip: {$jreCount}\n";

        // Refresh arsip data
        $arsipExpired->refresh();
        echo "  Arsip is_archived_to_jre: " . ($arsipExpired->is_archived_to_jre ? 'Yes' : 'No') . "\n";
        echo "  shouldMoveToJre() after move: " . ($arsipExpired->shouldMoveToJre() ? 'YES' : 'NO') . "\n\n";
    }
}

// Test autoMoveToJreIfExpired method
echo "=== TEST autoMoveToJreIfExpired() ===\n";
$result = $arsipExpired->autoMoveToJreIfExpired();
if ($result) {
    echo "autoMoveToJreIfExpired() returned JRE ID: {$result->id}\n";
} else {
    echo "autoMoveToJreIfExpired() returned null (no move needed)\n";
}

// Final check
$finalJreCount = Jre::where('arsip_id', $arsipExpired->id)->count();
echo "\nFinal JRE count for arsip ID {$arsipExpired->id}: {$finalJreCount}\n";

if ($finalJreCount === 1) {
    echo "✅ SUCCESS: Only one JRE record exists (no duplicates)\n";
} else {
    echo "❌ PROBLEM: Multiple JRE records still exist\n";

    // Show all JRE records for this arsip
    $allJres = Jre::where('arsip_id', $arsipExpired->id)->get();
    foreach ($allJres as $jre) {
        echo "  JRE ID: {$jre->id}, Status: {$jre->status}, Created: {$jre->created_at}\n";
    }
}

echo "\n=== TEST COMPLETE ===\n";
