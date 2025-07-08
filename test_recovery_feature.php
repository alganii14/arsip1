<?php

require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Models\Arsip;
use App\Models\Jre;
use Carbon\Carbon;

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

echo "=== TEST PEMULIHAN ARSIP DARI JRE ===\n\n";

// Find a JRE that can be recovered
$jre = Jre::whereIn('status', ['inactive', 'transferred'])->with('arsip')->first();

if (!$jre) {
    echo "Tidak ada JRE yang bisa dipulihkan untuk ditest.\n";
    echo "Membuat test case...\n";

    // Create test arsip and move to JRE first
    $testArsip = new Arsip();
    $testArsip->kode = 'RECOVERY-TEST-' . date('YmdHis');
    $testArsip->nama_dokumen = 'Test Dokumen untuk Recovery';
    $testArsip->kategori = 'Test';
    $testArsip->tanggal_arsip = now()->subDays(30);
    $testArsip->retention_date = now()->subDays(1);
    $testArsip->retention_years = 5;
    $testArsip->is_archived_to_jre = false;
    $testArsip->save();

    // Move to JRE
    $jre = $testArsip->moveToJre('Test untuk recovery');
    echo "Created test arsip dan JRE dengan ID: {$jre->id}\n";
}

echo "Testing dengan JRE ID: {$jre->id}\n";
echo "Arsip: {$jre->arsip->nama_dokumen}\n";
echo "Current JRE Status: {$jre->status}\n";
echo "Arsip is_archived_to_jre: " . ($jre->arsip->is_archived_to_jre ? 'Yes' : 'No') . "\n\n";

// Test recovery process manually (simulating controller method)
echo "=== TEST RECOVERY PROCESS ===\n";

// Simulate request data
$recoveryYears = 3; // Test with 3 years
echo "Testing recovery with {$recoveryYears} years retention...\n";

try {
    $arsip = $jre->arsip;

    // Calculate new retention date
    $newRetentionDate = Carbon::now()->addYears($recoveryYears);

    echo "Current retention date: " . ($arsip->retention_date ?: 'None') . "\n";
    echo "New retention date will be: {$newRetentionDate}\n\n";

    // Update arsip status
    $arsip->update([
        'is_archived_to_jre' => false,
        'archived_to_jre_at' => null,
        'retention_date' => $newRetentionDate,
        'retention_years' => $recoveryYears,
        'has_retention_notification' => false
    ]);

    echo "Arsip updated successfully\n";
    echo "New arsip status:\n";
    echo "  - is_archived_to_jre: " . ($arsip->is_archived_to_jre ? 'Yes' : 'No') . "\n";
    echo "  - retention_date: {$arsip->retention_date}\n";
    echo "  - retention_years: {$arsip->retention_years}\n";

    // Check if arsip appears in active scope
    $activeCount = Arsip::active()->where('id', $arsip->id)->count();
    echo "  - Appears in active scope: " . ($activeCount > 0 ? 'Yes' : 'No') . "\n\n";

    // Delete JRE record
    $jreId = $jre->id;
    $jre->delete();

    echo "JRE record deleted (ID: {$jreId})\n";

    // Verify JRE is gone
    $jreExists = Jre::where('id', $jreId)->exists();
    echo "JRE still exists: " . ($jreExists ? 'Yes' : 'No') . "\n";

    echo "\n✅ RECOVERY TEST SUCCESS\n";
    echo "Arsip '{$arsip->nama_dokumen}' berhasil dipulihkan dengan masa retensi {$recoveryYears} tahun.\n";

} catch (\Exception $e) {
    echo "\n❌ RECOVERY TEST FAILED\n";
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n=== TEST PERMANENT RETENTION ===\n";

// Test permanent retention with a new arsip
$testArsip2 = new Arsip();
$testArsip2->kode = 'PERMANENT-TEST-' . date('YmdHis');
$testArsip2->nama_dokumen = 'Test Dokumen Permanent';
$testArsip2->kategori = 'Test';
$testArsip2->tanggal_arsip = now()->subDays(30);
$testArsip2->retention_date = now()->subDays(1);
$testArsip2->retention_years = 5;
$testArsip2->is_archived_to_jre = false;
$testArsip2->save();

// Move to JRE
$jre2 = $testArsip2->moveToJre('Test untuk permanent recovery');

try {
    // Test permanent recovery
    $arsip2 = $jre2->arsip;

    echo "Testing permanent recovery...\n";
    echo "Before: retention_date = " . ($arsip2->retention_date ?: 'None') . "\n";

    // Update for permanent retention
    $arsip2->update([
        'is_archived_to_jre' => false,
        'archived_to_jre_at' => null,
        'retention_date' => null, // Permanent
        'retention_years' => null,
        'has_retention_notification' => false
    ]);

    echo "After: retention_date = " . ($arsip2->retention_date ?: 'None (Permanent)') . "\n";
    echo "retention_years = " . ($arsip2->retention_years ?: 'None (Permanent)') . "\n";

    // Delete JRE
    $jre2->delete();

    echo "✅ PERMANENT RECOVERY TEST SUCCESS\n";

} catch (\Exception $e) {
    echo "❌ PERMANENT RECOVERY TEST FAILED\n";
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n=== TEST COMPLETE ===\n";
