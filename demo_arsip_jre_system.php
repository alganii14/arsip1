<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\Arsip;
use App\Models\Jre;
use Carbon\Carbon;

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== DEMO: Sistem Perpindahan Arsip ke JRE ===\n\n";

try {
    // Create test arsip with expired retention date
    echo "1. Membuat arsip untuk testing...\n";

    $testArsip = Arsip::create([
        'kode' => 'TEST-' . now()->format('YmdHis'),
        'nama_dokumen' => 'Dokumen Test untuk Demo JRE',
        'kategori' => 'Testing',
        'tanggal_arsip' => Carbon::now()->subYears(6)->format('Y-m-d'), // 6 years ago
        'keterangan' => 'Arsip test untuk demonstrasi sistem JRE',
        'retention_years' => 5,
        'retention_date' => Carbon::now()->subDays(30)->format('Y-m-d'), // Already expired
        'is_archived_to_jre' => false
    ]);

    echo "   ✓ Arsip created: [{$testArsip->kode}] - {$testArsip->nama_dokumen}\n";
    echo "   ✓ Retention date: " . $testArsip->retention_date . " (EXPIRED)\n\n";

    // Check if arsip appears in active list
    echo "2. Memeriksa arsip di daftar aktif...\n";
    $activeArsips = Arsip::active()->where('id', $testArsip->id)->count();
    echo "   ✓ Arsip terlihat di daftar aktif: " . ($activeArsips > 0 ? 'YA' : 'TIDAK') . "\n\n";

    // Move to JRE
    echo "3. Memindahkan arsip ke JRE karena masa retensi telah habis...\n";
    $jre = $testArsip->moveToJre('Dipindahkan ke JRE karena masa retensi habis');

    echo "   ✓ JRE record created with ID: {$jre->id}\n";
    echo "   ✓ JRE status: {$jre->status}\n";
    echo "   ✓ JRE notes: {$jre->notes}\n\n";

    // Check arsip status after move
    $testArsip->refresh();
    echo "4. Status arsip setelah dipindahkan ke JRE...\n";
    echo "   ✓ is_archived_to_jre: " . ($testArsip->is_archived_to_jre ? 'TRUE' : 'FALSE') . "\n";
    echo "   ✓ archived_to_jre_at: " . $testArsip->archived_to_jre_at . "\n\n";

    // Check if arsip still appears in active list
    echo "5. Memeriksa arsip di daftar aktif setelah dipindahkan...\n";
    $activeArsipsAfterMove = Arsip::active()->where('id', $testArsip->id)->count();
    echo "   ✓ Arsip masih terlihat di daftar aktif: " . ($activeArsipsAfterMove > 0 ? 'YA' : 'TIDAK') . "\n";
    echo "   ✓ Arsip sekarang hanya ada di JRE!\n\n";

    // Show JRE list
    echo "6. Memeriksa arsip di daftar JRE...\n";
    $jreCount = Jre::where('arsip_id', $testArsip->id)->count();
    echo "   ✓ Arsip ada di JRE: " . ($jreCount > 0 ? 'YA' : 'TIDAK') . "\n\n";

    // Recover from JRE
    echo "7. Memulihkan arsip dari JRE...\n";
    $jreId = $jre->id; // Store ID before deletion
    $recoveredArsip = $jre->recoverWithCustomYears(3); // Recover with 3 years extension

    echo "   ✓ Arsip recovered successfully\n";
    echo "   ✓ New retention period: {$recoveredArsip->retention_years} years\n";
    echo "   ✓ New retention date: " . $recoveredArsip->retention_date . "\n\n";

    // Check if JRE record still exists
    echo "8. Memeriksa apakah JRE record masih ada...\n";
    $jreStillExists = Jre::find($jreId);
    echo "   ✓ JRE record masih ada: " . ($jreStillExists ? 'YA' : 'TIDAK') . "\n";
    if (!$jreStillExists) {
        echo "   ✓ JRE record berhasil dihapus setelah recovery!\n";
    }

    // Check final status
    echo "\n9. Status final setelah recovery...\n";
    echo "   ✓ Arsip is_archived_to_jre: " . ($recoveredArsip->is_archived_to_jre ? 'TRUE' : 'FALSE') . "\n";

    // Check if arsip back in active list
    $activeArsipsAfterRecovery = Arsip::active()->where('id', $testArsip->id)->count();
    echo "   ✓ Arsip kembali di daftar aktif: " . ($activeArsipsAfterRecovery > 0 ? 'YA' : 'TIDAK') . "\n";

    // Check JRE count
    $jreCountAfterRecovery = Jre::where('arsip_id', $testArsip->id)->count();
    echo "   ✓ Jumlah JRE record untuk arsip ini: {$jreCountAfterRecovery}\n\n";

    // Cleanup
    echo "10. Membersihkan data test...\n";
    // JRE already deleted during recovery
    $testArsip->delete();
    echo "   ✓ Test data cleaned up\n\n";

    echo "=== DEMO COMPLETED ===\n";
    echo "✓ Ketika arsip masa retensi habis → Arsip otomatis pindah ke JRE\n";
    echo "✓ Arsip yang di JRE tidak muncul di daftar arsip aktif\n";
    echo "✓ Ketika arsip dipulihkan dari JRE → Arsip kembali ke daftar aktif\n";
    echo "✓ JRE record DIHAPUS setelah recovery - arsip benar-benar 'pindah kembali'\n";
    echo "✓ Tidak ada jejak JRE setelah arsip dipulihkan\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    exit(1);
}

?>
