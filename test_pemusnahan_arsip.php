<?php

require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Models\Jre;
use App\Models\ArchiveDestruction;

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

echo "=== TEST FITUR PEMUSNAHAN ARSIP ===\n\n";

// Find a JRE that can be destroyed (not already destroyed or transferred)
$jreForDestruction = Jre::where('status', '!=', 'destroyed')
                        ->where('status', '!=', 'transferred')
                        ->with('arsip')
                        ->first();

if (!$jreForDestruction) {
    echo "Tidak ada JRE yang bisa dimusnahkan untuk test.\n";
    echo "Status JRE yang ada:\n";
    $allJres = Jre::with('arsip')->get();
    foreach ($allJres as $jre) {
        echo "  JRE ID: {$jre->id}, Status: {$jre->status}, Arsip: {$jre->arsip->nama_dokumen}\n";
    }
    exit;
}

echo "JRE untuk test pemusnahan:\n";
echo "  ID: {$jreForDestruction->id}\n";
echo "  Status: {$jreForDestruction->status}\n";
echo "  Arsip: {$jreForDestruction->arsip->nama_dokumen}\n";
echo "  Kode: {$jreForDestruction->arsip->kode}\n\n";

// Test data untuk pemusnahan
$destructionData = [
    'destruction_notes' => 'Test pemusnahan otomatis - arsip telah melewati masa retensi dan tidak memiliki nilai historis',
    'destruction_method' => 'shredding',
    'destruction_location' => 'Ruang Pemusnahan Gedung A',
    'destruction_witnesses' => 'John Doe, Jane Smith',
    'destroyed_by' => 1, // Assuming user ID 1 exists
    'destroyed_at' => now()
];

try {
    echo "=== MULAI PROSES PEMUSNAHAN ===\n";

    // Create destruction record
    $destruction = ArchiveDestruction::create([
        'arsip_id' => $jreForDestruction->arsip_id,
        'jre_id' => $jreForDestruction->id,
        'destruction_notes' => $destructionData['destruction_notes'],
        'destruction_method' => $destructionData['destruction_method'],
        'destruction_location' => $destructionData['destruction_location'],
        'destruction_witnesses' => $destructionData['destruction_witnesses'],
        'user_id' => $destructionData['destroyed_by'],
        'destroyed_at' => $destructionData['destroyed_at']
    ]);

    echo "✅ Destruction record dibuat dengan ID: {$destruction->id}\n";

    // Update JRE status
    $oldNotes = $jreForDestruction->notes;
    $newNotes = $oldNotes . "\n\n[DIMUSNAHKAN] " . $destructionData['destruction_notes'];

    $jreForDestruction->update([
        'status' => 'destroyed',
        'notes' => $newNotes
    ]);

    echo "✅ JRE status berhasil diupdate ke 'destroyed'\n";

    // Verify the destruction
    $jreForDestruction->refresh();
    echo "\n=== VERIFIKASI HASIL ===\n";
    echo "JRE Status: {$jreForDestruction->status}\n";
    echo "JRE Notes: " . substr($jreForDestruction->notes, -100) . "...\n";

    // Check destruction record
    $destructionRecord = ArchiveDestruction::where('jre_id', $jreForDestruction->id)->first();
    if ($destructionRecord) {
        echo "Destruction Record ID: {$destructionRecord->id}\n";
        echo "Destruction Method: {$destructionRecord->destruction_method_text}\n";
        echo "Destroyed At: " . $destructionRecord->destroyed_at . "\n";
        echo "Destroyed By: {$destructionRecord->user_id}\n";
    }

    // Test scope - pastikan JRE yang destroyed tidak muncul di active
    $activeJres = Jre::active()->count();
    $allJres = Jre::count();
    $destroyedJres = Jre::where('status', 'destroyed')->count();

    echo "\n=== STATISTIK JRE ===\n";
    echo "Total JRE: {$allJres}\n";
    echo "JRE Aktif: {$activeJres}\n";
    echo "JRE Dimusnahkan: {$destroyedJres}\n";

    echo "\n✅ SUCCESS: Pemusnahan arsip berhasil!\n";

} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== TEST COMPLETE ===\n";
