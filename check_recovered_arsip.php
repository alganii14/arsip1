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

echo "=== CHECK RECOVERED ARSIP STATUS ===\n\n";

$arsip = Arsip::find(1);
if ($arsip) {
    echo "Arsip found: {$arsip->nama_dokumen}\n";
    echo "is_archived_to_jre: " . ($arsip->is_archived_to_jre ? 'Yes' : 'No') . "\n";

    $pemindahan = Pemindahan::where('arsip_id', 1)->first();
    if ($pemindahan) {
        echo "Has pemindahan record: Yes\n";
        echo "Pemindahan status: {$pemindahan->status}\n";
    } else {
        echo "Has pemindahan record: No\n";
    }

    $activeCount = Arsip::active()->where('id', 1)->count();
    echo "Appears in active scope: " . ($activeCount > 0 ? 'Yes' : 'No') . "\n";
} else {
    echo "Arsip not found\n";
}

echo "\n=== END CHECK ===\n";
