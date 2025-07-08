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

echo "=== TEST SIMULATION RECOVERY FORM ===\n\n";

// Create test JRE
$testArsip = new Arsip();
$testArsip->kode = 'TEST-FORM-' . date('YmdHis');
$testArsip->nama_dokumen = 'Test Form Recovery';
$testArsip->kategori = 'Test';
$testArsip->tanggal_arsip = now()->subDays(10);
$testArsip->retention_date = now()->subDays(1);
$testArsip->retention_years = 1;
$testArsip->is_archived_to_jre = false;
$testArsip->save();

$jre = $testArsip->moveToJre('Test form recovery');
echo "Created test JRE with ID: {$jre->id}\n";

// Simulate form submission data
$formData = [
    'recovery_years' => '5',
    'notes' => 'Test recovery via form simulation'
];

echo "Simulating form data: " . json_encode($formData) . "\n\n";

try {
    // Validate recovery_years
    $recoveryYears = $formData['recovery_years'];

    echo "=== VALIDATION ===\n";
    echo "Recovery years: {$recoveryYears}\n";

    if ($recoveryYears === 'permanent') {
        echo "Recovery type: Permanent\n";
        $years = null;
    } else {
        $years = (int) $recoveryYears;
        echo "Recovery type: {$years} years\n";

        if ($years < 1 || $years > 30) {
            throw new Exception("Invalid recovery years: must be between 1-30 or 'permanent'");
        }
    }

    echo "\n=== PROCESSING RECOVERY ===\n";

    // Perform recovery
    $recoveredArsip = $jre->recoverToArsip($years);

    if ($recoveredArsip) {
        echo "✅ Recovery successful!\n";
        echo "Recovered Arsip ID: {$recoveredArsip->id}\n";
        echo "Name: {$recoveredArsip->nama_dokumen}\n";

        if ($years === null) {
            echo "Retention: Permanent\n";
        } else {
            echo "Retention years: {$recoveredArsip->retention_years}\n";
            echo "Retention date: {$recoveredArsip->retention_date}\n";
        }

        echo "Is archived to JRE: " . ($recoveredArsip->is_archived_to_jre ? 'Yes' : 'No') . "\n";

        // Check if JRE still exists
        $jreExists = Jre::find($jre->id);
        if ($jreExists) {
            echo "JRE status: {$jreExists->status}\n";
        } else {
            echo "JRE record deleted\n";
        }

    } else {
        echo "❌ Recovery failed - method returned null\n";
    }

} catch (Exception $e) {
    echo "❌ Error during recovery: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== TESTING PERMANENT RECOVERY ===\n";

// Create another test for permanent recovery
$testArsip2 = new Arsip();
$testArsip2->kode = 'TEST-PERM-' . date('YmdHis');
$testArsip2->nama_dokumen = 'Test Permanent Recovery';
$testArsip2->kategori = 'Test';
$testArsip2->tanggal_arsip = now()->subDays(10);
$testArsip2->retention_date = now()->subDays(1);
$testArsip2->retention_years = 1;
$testArsip2->is_archived_to_jre = false;
$testArsip2->save();

$jre2 = $testArsip2->moveToJre('Test permanent recovery');

try {
    $permanentRecovery = $jre2->recoverToArsip(null); // null = permanent

    if ($permanentRecovery) {
        echo "✅ Permanent recovery successful!\n";
        echo "Arsip ID: {$permanentRecovery->id}\n";
        echo "Retention years: " . ($permanentRecovery->retention_years ?? 'NULL (Permanent)') . "\n";
        echo "Retention date: " . ($permanentRecovery->retention_date ?? 'NULL (Permanent)') . "\n";
    } else {
        echo "❌ Permanent recovery failed\n";
    }

} catch (Exception $e) {
    echo "❌ Permanent recovery error: " . $e->getMessage() . "\n";
}

echo "\n=== TEST COMPLETE ===\n";
