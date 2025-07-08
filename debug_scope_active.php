<?php

require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Models\Arsip;
use App\Models\Pemindahan;

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

echo "=== DEBUG SCOPE ACTIVE ===\n\n";

// Check all arsip
$allArsips = Arsip::all();
echo "Semua arsip di database:\n";
foreach ($allArsips as $arsip) {
    echo "- ID: {$arsip->id}, Nama: {$arsip->nama_dokumen}\n";
    echo "  is_archived_to_jre: " . ($arsip->is_archived_to_jre ? 'true' : 'false') . "\n";

    // Check pemindahan
    $pemindahans = Pemindahan::where('arsip_id', $arsip->id)->get();
    echo "  Total pemindahan: " . $pemindahans->count() . "\n";

    foreach ($pemindahans as $pemindahan) {
        echo "    - Pemindahan ID: {$pemindahan->id}, Status: {$pemindahan->status}\n";
    }

    // Check pemindahan dengan status approved/completed
    $pemindahanCompleted = Pemindahan::where('arsip_id', $arsip->id)
                                   ->whereIn('status', ['approved', 'completed'])
                                   ->count();
    echo "  Pemindahan approved/completed: {$pemindahanCompleted}\n";

    // Manual check for scope active conditions
    $condition1 = !$arsip->is_archived_to_jre;  // is_archived_to_jre = false
    $condition2 = $pemindahanCompleted == 0;     // no completed pemindahan

    echo "  Condition 1 (is_archived_to_jre = false): " . ($condition1 ? 'PASS' : 'FAIL') . "\n";
    echo "  Condition 2 (no completed pemindahan): " . ($condition2 ? 'PASS' : 'FAIL') . "\n";
    echo "  Should be active: " . ($condition1 && $condition2 ? 'YES' : 'NO') . "\n";
    echo "\n";
}

// Test scope manual
echo "=== TEST SCOPE MANUAL ===\n";
$activeQuery1 = Arsip::where('is_archived_to_jre', false);
echo "Arsip dengan is_archived_to_jre = false: " . $activeQuery1->count() . "\n";

$activeQuery2 = Arsip::where('is_archived_to_jre', false)
                    ->whereDoesntHave('pemindahan');
echo "Arsip dengan is_archived_to_jre = false dan tidak ada pemindahan: " . $activeQuery2->count() . "\n";

$activeQuery3 = Arsip::where('is_archived_to_jre', false)
                    ->whereDoesntHave('pemindahan', function($q) {
                        $q->whereIn('status', ['approved', 'completed']);
                    });
echo "Arsip dengan is_archived_to_jre = false dan tidak ada pemindahan approved/completed: " . $activeQuery3->count() . "\n";

// Show actual scope active result
echo "\nHasil scope active: " . Arsip::active()->count() . "\n";

echo "\n=== TEST COMPLETE ===\n";
