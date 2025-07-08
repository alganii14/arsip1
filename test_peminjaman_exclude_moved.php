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

echo "=== TEST ARSIP YANG SUDAH DIPINDAHKAN ===\n\n";

// Check arsip yang sudah dipindahkan
$movedArsips = Arsip::whereHas('pemindahan')->with('pemindahan')->get();
echo "Arsip yang sudah dipindahkan: " . $movedArsips->count() . "\n";

foreach ($movedArsips as $arsip) {
    echo "- Arsip ID: {$arsip->id}, Kode: {$arsip->kode}\n";
    echo "  Pemindahan: {$arsip->pemindahan->status}, Tanggal: {$arsip->pemindahan->transfer_date}\n";
}

echo "\n=== TEST QUERY AVAILABLE ARSIPS (SEPERTI DI CONTROLLER) ===\n\n";

// Test query yang sama dengan di controller
$availableArsipsQuery = Arsip::where('is_archived_to_jre', false)
    ->whereDoesntHave('peminjaman', function($query) {
        $query->whereIn('status', ['dipinjam', 'terlambat']);
    })
    ->whereDoesntHave('pemindahan'); // Exclude archives that have been transferred

$availableArsips = $availableArsipsQuery->with('creator')->latest()->get();

echo "Arsip yang tersedia untuk dipinjam: " . $availableArsips->count() . "\n";

foreach ($availableArsips as $arsip) {
    echo "- Arsip ID: {$arsip->id}, Kode: {$arsip->kode}, Nama: {$arsip->nama_dokumen}\n";
    echo "  Created by: " . ($arsip->creator ? $arsip->creator->name : 'N/A') . "\n";
    echo "  is_archived_to_jre: " . ($arsip->is_archived_to_jre ? 'Yes' : 'No') . "\n";

    // Check if has pemindahan
    $hasPemindahan = $arsip->pemindahan ? 'Yes' : 'No';
    echo "  Has pemindahan: {$hasPemindahan}\n";

    // Check if has active peminjaman
    $activePeminjaman = $arsip->peminjaman()->whereIn('status', ['dipinjam', 'terlambat'])->count();
    echo "  Active peminjaman: {$activePeminjaman}\n\n";
}

echo "\n=== VERIFIKASI: ARSIP YANG DIPINDAHKAN TIDAK MUNCUL DI AVAILABLE ===\n\n";

$movedArsipIds = $movedArsips->pluck('id')->toArray();
$availableArsipIds = $availableArsips->pluck('id')->toArray();

$foundInAvailable = array_intersect($movedArsipIds, $availableArsipIds);

if (empty($foundInAvailable)) {
    echo "✅ SUCCESS: Tidak ada arsip yang sudah dipindahkan yang muncul di available list\n";
} else {
    echo "❌ PROBLEM: Arsip yang sudah dipindahkan masih muncul di available list:\n";
    foreach ($foundInAvailable as $arsipId) {
        $arsip = Arsip::find($arsipId);
        echo "  - Arsip ID: {$arsipId}, Kode: {$arsip->kode}\n";
    }
}

echo "\n=== SUMMARY ===\n";
echo "Total arsip: " . Arsip::count() . "\n";
echo "Arsip di JRE: " . Arsip::where('is_archived_to_jre', true)->count() . "\n";
echo "Arsip yang dipindahkan: " . $movedArsips->count() . "\n";
echo "Arsip tersedia untuk dipinjam: " . $availableArsips->count() . "\n";

echo "\n=== TEST COMPLETE ===\n";
