<?php

require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Jre;
use App\Models\Arsip;
use App\Models\ArchiveDestruction;

echo "=== DEBUG JRE COUNT ISSUE ===\n\n";

try {
    // 1. Total arsip di database
    $totalArsip = Arsip::count();
    echo "1. TOTAL ARSIP DI DATABASE: {$totalArsip}\n\n";

    // 2. Arsip dengan is_archived_to_jre = true
    $arsipArchivedToJre = Arsip::where('is_archived_to_jre', true)->count();
    echo "2. ARSIP DENGAN is_archived_to_jre = true: {$arsipArchivedToJre}\n";
    
    // Detail arsip yang is_archived_to_jre = true
    $arsipsInJre = Arsip::where('is_archived_to_jre', true)->get();
    if ($arsipsInJre->count() > 0) {
        echo "   Detail arsip yang is_archived_to_jre = true:\n";
        foreach ($arsipsInJre as $arsip) {
            echo "   - ID: {$arsip->id}, Nama: {$arsip->nama_dokumen}\n";
        }
    } else {
        echo "   Tidak ada arsip dengan is_archived_to_jre = true\n";
    }
    echo "\n";

    // 3. Total JRE di database
    $totalJre = Jre::count();
    echo "3. TOTAL JRE DI DATABASE: {$totalJre}\n\n";

    // 4. JRE dengan berbagai status
    $jreActive = Jre::active()->count();
    $jreDestroyed = Jre::where('status', 'destroyed')->count();
    $jreTransferred = Jre::where('status', 'transferred')->count();
    $jreInactive = Jre::where('status', 'inactive')->count();
    
    echo "4. JRE BERDASARKAN STATUS:\n";
    echo "   - Active (scope): {$jreActive}\n";
    echo "   - Inactive: {$jreInactive}\n";
    echo "   - Destroyed: {$jreDestroyed}\n";
    echo "   - Transferred: {$jreTransferred}\n\n";

    // 5. Detail JRE yang masih active
    $activeJres = Jre::active()->with('arsip')->get();
    echo "5. DETAIL JRE YANG ACTIVE:\n";
    if ($activeJres->count() > 0) {
        foreach ($activeJres as $jre) {
            echo "   - JRE ID: {$jre->id}, Status: {$jre->status}\n";
            echo "     Arsip: {$jre->arsip->nama_dokumen}\n";
            echo "     Arsip ID: {$jre->arsip_id}\n";
            echo "     Arsip is_archived_to_jre: " . ($jre->arsip->is_archived_to_jre ? 'true' : 'false') . "\n";
            echo "   ---\n";
        }
    } else {
        echo "   Tidak ada JRE yang active\n";
    }
    echo "\n";

    // 6. Detail JRE yang destroyed
    $destroyedJres = Jre::where('status', 'destroyed')->with('arsip')->get();
    echo "6. DETAIL JRE YANG DESTROYED:\n";
    if ($destroyedJres->count() > 0) {
        foreach ($destroyedJres as $jre) {
            echo "   - JRE ID: {$jre->id}, Status: {$jre->status}\n";
            echo "     Arsip: {$jre->arsip->nama_dokumen}\n";
            echo "     Arsip ID: {$jre->arsip_id}\n";
            echo "     Arsip is_archived_to_jre: " . ($jre->arsip->is_archived_to_jre ? 'true' : 'false') . "\n";
            echo "   ---\n";
        }
    } else {
        echo "   Tidak ada JRE yang destroyed\n";
    }
    echo "\n";

    // 7. Penjelasan masalah
    echo "7. ANALISIS MASALAH:\n";
    echo "   Dashboard menghitung: Arsip::where('is_archived_to_jre', true)->count() = {$arsipArchivedToJre}\n";
    echo "   Halaman JRE menampilkan: Jre::active()->count() = {$jreActive}\n\n";
    
    if ($arsipArchivedToJre > $jreActive) {
        echo "   ❌ MASALAH DITEMUKAN!\n";
        echo "   Ada {$arsipArchivedToJre} arsip dengan is_archived_to_jre=true\n";
        echo "   Tapi hanya {$jreActive} JRE yang active\n\n";
        
        echo "   KEMUNGKINAN PENYEBAB:\n";
        echo "   1. Ada arsip yang sudah dimusnahkan tapi is_archived_to_jre masih true\n";
        echo "   2. Ada JRE yang statusnya 'destroyed' atau 'transferred' tapi arsipnya masih is_archived_to_jre=true\n\n";
        
        // Cek arsip yang bermasalah
        echo "   ARSIP YANG BERMASALAH:\n";
        foreach ($arsipsInJre as $arsip) {
            $jreForArsip = Jre::where('arsip_id', $arsip->id)->first();
            if ($jreForArsip) {
                if (!in_array($jreForArsip->status, ['inactive'])) {
                    echo "   - Arsip ID {$arsip->id}: is_archived_to_jre=true, tapi JRE status={$jreForArsip->status}\n";
                    echo "     Arsip ini seharusnya is_archived_to_jre=false karena sudah dimusnahkan/dipindahkan\n";
                }
            }
        }
    } else {
        echo "   ✅ Tidak ada masalah ditemukan\n";
    }

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== DEBUG COMPLETE ===\n";

?>
