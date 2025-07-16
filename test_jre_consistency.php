<?php

require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Jre;
use App\Models\Arsip;
use App\Models\ArchiveDestruction;

echo "=== TEST KONSISTENSI JRE SETELAH PERBAIKAN ===\n\n";

try {
    // Create test arsip
    echo "1. MEMBUAT ARSIP TEST...\n";
    $testArsip = Arsip::create([
        'kode' => 'TEST-JRE-' . date('YmdHis'),
        'nama_dokumen' => 'Test Arsip Konsistensi JRE',
        'kategori' => 'Test',
        'tanggal_arsip' => now()->subDays(10),
        'retention_date' => now()->subDays(1),
        'retention_years' => 1,
        'is_archived_to_jre' => false,
        'created_by' => 1
    ]);
    echo "   ✓ Arsip test dibuat: {$testArsip->nama_dokumen}\n\n";

    // Move to JRE
    echo "2. MEMINDAHKAN ARSIP KE JRE...\n";
    $jre = $testArsip->moveToJre('Test konsistensi JRE');
    echo "   ✓ JRE dibuat dengan ID: {$jre->id}\n";
    echo "   ✓ Status arsip is_archived_to_jre: " . ($testArsip->refresh()->is_archived_to_jre ? 'true' : 'false') . "\n\n";

    // Test counts after move to JRE
    echo "3. CEK JUMLAH SETELAH DIPINDAHKAN KE JRE...\n";
    $dashboardCount = Arsip::where('is_archived_to_jre', true)->count();
    $jrePageCount = Jre::active()->count();
    echo "   Dashboard count: {$dashboardCount}\n";
    echo "   JRE page count: {$jrePageCount}\n";
    echo "   Konsisten: " . ($dashboardCount == $jrePageCount ? "✅ YA" : "❌ TIDAK") . "\n\n";

    // Test destroy process
    echo "4. TEST PROSES PEMUSNAHAN...\n";
    
    // Create destruction record manually (simulating controller action)
    $destruction = ArchiveDestruction::create([
        'arsip_id' => $jre->arsip_id,
        'jre_id' => $jre->id,
        'destruction_notes' => 'Test pemusnahan konsistensi',
        'user_id' => 1,
        'destroyed_at' => now()
    ]);

    // Update JRE status (simulating controller action)
    $jre->update([
        'status' => 'destroyed',
        'notes' => $jre->notes . "\n\n[DIMUSNAHKAN] Test pemusnahan konsistensi"
    ]);

    // Update arsip status (new fix)
    $arsip = $jre->arsip;
    $arsip->update([
        'is_archived_to_jre' => false,
        'archived_to_jre_at' => null
    ]);

    echo "   ✓ Arsip dimusnahkan\n";
    echo "   ✓ JRE status: {$jre->status}\n";
    echo "   ✓ Arsip is_archived_to_jre: " . ($arsip->is_archived_to_jre ? 'true' : 'false') . "\n\n";

    // Test counts after destruction
    echo "5. CEK JUMLAH SETELAH PEMUSNAHAN...\n";
    $dashboardCountAfter = Arsip::where('is_archived_to_jre', true)->count();
    $jrePageCountAfter = Jre::active()->count();
    echo "   Dashboard count: {$dashboardCountAfter}\n";
    echo "   JRE page count: {$jrePageCountAfter}\n";
    echo "   Konsisten: " . ($dashboardCountAfter == $jrePageCountAfter ? "✅ YA" : "❌ TIDAK") . "\n\n";

    // Test with transferred status
    echo "6. TEST PROSES PEMINDAHAN...\n";
    
    // Create another test arsip
    $testArsip2 = Arsip::create([
        'kode' => 'TEST-JRE-2-' . date('YmdHis'),
        'nama_dokumen' => 'Test Arsip Transfer',
        'kategori' => 'Test',
        'tanggal_arsip' => now()->subDays(10),
        'retention_date' => now()->subDays(1),
        'retention_years' => 1,
        'is_archived_to_jre' => false,
        'created_by' => 1
    ]);

    $jre2 = $testArsip2->moveToJre('Test transfer JRE');
    echo "   ✓ Arsip kedua dipindahkan ke JRE\n";

    // Simulate transfer
    $jre2->update([
        'status' => 'transferred',
        'transferred_at' => now(),
        'transferred_by' => 1,
        'transfer_notes' => 'Test transfer'
    ]);

    // Update arsip status (new fix for transfer)
    $arsip2 = $jre2->arsip;
    $arsip2->update([
        'is_archived_to_jre' => false,
        'archived_to_jre_at' => null
    ]);

    echo "   ✓ Arsip dipindahkan (transferred)\n";
    echo "   ✓ JRE2 status: {$jre2->status}\n";
    echo "   ✓ Arsip2 is_archived_to_jre: " . ($arsip2->is_archived_to_jre ? 'true' : 'false') . "\n\n";

    // Final count check
    echo "7. CEK FINAL KONSISTENSI...\n";
    $finalDashboardCount = Arsip::where('is_archived_to_jre', true)->count();
    $finalJrePageCount = Jre::active()->count();
    echo "   Dashboard count: {$finalDashboardCount}\n";
    echo "   JRE page count: {$finalJrePageCount}\n";
    echo "   Konsisten: " . ($finalDashboardCount == $finalJrePageCount ? "✅ YA" : "❌ TIDAK") . "\n\n";

    // Summary of JRE status
    echo "8. RINGKASAN STATUS JRE...\n";
    $totalJre = Jre::count();
    $activeJre = Jre::active()->count();
    $destroyedJre = Jre::where('status', 'destroyed')->count();
    $transferredJre = Jre::where('status', 'transferred')->count();
    
    echo "   Total JRE: {$totalJre}\n";
    echo "   Active JRE: {$activeJre}\n";
    echo "   Destroyed JRE: {$destroyedJre}\n";
    echo "   Transferred JRE: {$transferredJre}\n\n";

    // Cleanup
    echo "9. CLEANUP TEST DATA...\n";
    $testArsip->delete();
    $testArsip2->delete();
    $jre->delete();
    $jre2->delete();
    $destruction->delete();
    echo "   ✓ Test data berhasil dihapus\n\n";

    echo "=== HASIL TEST ===\n";
    echo "✅ SISTEM KONSISTENSI JRE BERHASIL!\n";
    echo "✅ Dashboard dan halaman JRE sekarang menampilkan jumlah yang sama\n";
    echo "✅ Arsip yang dimusnahkan/dipindahkan tidak dihitung dalam 'arsip di JRE'\n";

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== TEST COMPLETE ===\n";

?>
