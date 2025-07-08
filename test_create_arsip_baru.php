<?php

require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Models\Arsip;

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

echo "=== CREATE NEW TEST ARSIP ===\n\n";

// Create a new arsip that hasn't been moved
$arsip = new Arsip();
$arsip->kode = 'NEW-' . date('YmdHis');
$arsip->nama_dokumen = 'New Test Document - Not Moved';
$arsip->kategori = 'Test';
$arsip->tanggal_arsip = now();
$arsip->retention_date = now()->addYears(2); // Not expired yet
$arsip->retention_years = 2;
$arsip->is_archived_to_jre = false;
$arsip->save();

echo "Created new arsip:\n";
echo "ID: {$arsip->id}\n";
echo "Nama: {$arsip->nama_dokumen}\n";
echo "is_archived_to_jre: " . ($arsip->is_archived_to_jre ? 'true' : 'false') . "\n";

// Test scope active now
$activeCount = Arsip::active()->count();
echo "\nTotal arsip aktif setelah create: {$activeCount}\n";

$activeArsips = Arsip::active()->get();
foreach ($activeArsips as $arsip) {
    echo "- Active Arsip ID: {$arsip->id}, Nama: {$arsip->nama_dokumen}\n";
}

if ($activeCount > 0) {
    echo "\n✅ SCOPE ACTIVE BEKERJA DENGAN BENAR\n";
} else {
    echo "\n❌ SCOPE ACTIVE MASIH BERMASALAH\n";
}

echo "\n=== TEST COMPLETE ===\n";
