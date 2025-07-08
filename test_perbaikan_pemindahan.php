<?php

require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Models\Arsip;
use App\Models\Jre;
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

echo "=== TEST PERBAIKAN PEMINDAHAN ARSIP ===\n\n";

// Check arsip yang sudah dipindahkan
$arsipDipindah = Pemindahan::with('arsip')->whereIn('status', ['approved', 'completed'])->get();

echo "Arsip yang sudah dipindahkan:\n";
foreach ($arsipDipindah as $pemindahan) {
    if ($pemindahan->arsip) {
        echo "- Arsip ID: {$pemindahan->arsip->id}, Nama: {$pemindahan->arsip->nama_dokumen}\n";
        echo "  Status pemindahan: {$pemindahan->status}\n";
        echo "  is_archived_to_jre: " . ($pemindahan->arsip->is_archived_to_jre ? 'true' : 'false') . "\n";

        // Check apakah masih muncul di scope active
        $isInActive = Arsip::active()->where('id', $pemindahan->arsip->id)->exists();
        echo "  Muncul di scope active: " . ($isInActive ? 'YA (MASALAH!)' : 'TIDAK (BENAR)') . "\n";

        // Check JRE
        $jreCount = Jre::where('arsip_id', $pemindahan->arsip->id)->count();
        echo "  JRE count: {$jreCount}\n";

        if ($jreCount > 0) {
            $jres = Jre::where('arsip_id', $pemindahan->arsip->id)->get();
            foreach ($jres as $jre) {
                echo "    JRE ID: {$jre->id}, Status: {$jre->status}\n";
            }
        }
        echo "\n";
    }
}

echo "\n=== TEST SCOPE ACTIVE ===\n";
$activeArsips = Arsip::active()->get();
echo "Total arsip aktif (scope active): " . $activeArsips->count() . "\n";

foreach ($activeArsips as $arsip) {
    echo "- ID: {$arsip->id}, Nama: {$arsip->nama_dokumen}\n";
    echo "  is_archived_to_jre: " . ($arsip->is_archived_to_jre ? 'true' : 'false') . "\n";

    // Check pemindahan
    $pemindahanCount = Pemindahan::where('arsip_id', $arsip->id)
                                ->whereIn('status', ['approved', 'completed'])
                                ->count();
    echo "  Pemindahan completed/approved: {$pemindahanCount}\n";

    if ($pemindahanCount > 0) {
        echo "  ❌ MASALAH: Arsip ini seharusnya tidak muncul di aktif karena sudah dipindahkan!\n";
    } else {
        echo "  ✅ OK: Arsip belum dipindahkan\n";
    }
    echo "\n";
}

echo "\n=== SUMMARY ===\n";
$totalArsip = Arsip::count();
$totalJre = Jre::count();
$totalPemindahan = Pemindahan::whereIn('status', ['approved', 'completed'])->count();
$totalAktif = Arsip::active()->count();

echo "Total arsip: {$totalArsip}\n";
echo "Total JRE: {$totalJre}\n";
echo "Total pemindahan (completed/approved): {$totalPemindahan}\n";
echo "Total arsip aktif (scope): {$totalAktif}\n";

$expectedActive = $totalArsip - $totalPemindahan;
if ($totalAktif == $expectedActive) {
    echo "✅ SCOPE ACTIVE BEKERJA DENGAN BENAR\n";
} else {
    echo "❌ MASALAH DENGAN SCOPE ACTIVE\n";
    echo "Expected active: {$expectedActive}, Actual: {$totalAktif}\n";
}

echo "\n=== TEST COMPLETE ===\n";
