<?php

require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Jre;
use App\Models\Arsip;
use Illuminate\Support\Facades\DB;

echo "=== FIX INCONSISTENT JRE DATA ===\n\n";

try {
    DB::beginTransaction();

    // 1. Cari arsip yang bermasalah
    echo "1. MENCARI ARSIP YANG BERMASALAH...\n";
    
    $problematicArsips = Arsip::where('is_archived_to_jre', true)
        ->whereHas('jre', function($query) {
            $query->whereIn('status', ['destroyed', 'transferred']);
        })
        ->with('jre')
        ->get();

    if ($problematicArsips->count() > 0) {
        echo "   ❌ DITEMUKAN {$problematicArsips->count()} ARSIP BERMASALAH:\n";
        
        foreach ($problematicArsips as $arsip) {
            echo "   - Arsip ID {$arsip->id}: {$arsip->nama_dokumen}\n";
            echo "     is_archived_to_jre: true\n";
            echo "     JRE Status: {$arsip->jre->status}\n";
            echo "     Masalah: Arsip sudah dimusnahkan/dipindahkan tapi is_archived_to_jre masih true\n";
            echo "   ---\n";
        }
        
        // 2. Perbaiki data
        echo "\n2. MEMPERBAIKI DATA...\n";
        
        foreach ($problematicArsips as $arsip) {
            echo "   Memperbaiki Arsip ID {$arsip->id}:\n";
            
            // Update is_archived_to_jre menjadi false untuk arsip yang sudah destroyed/transferred
            $arsip->update([
                'is_archived_to_jre' => false,
                'archived_to_jre_at' => null
            ]);
            
            echo "     ✓ Set is_archived_to_jre = false\n";
            echo "     ✓ Set archived_to_jre_at = null\n";
        }
        
        DB::commit();
        echo "\n   ✅ SEMUA DATA BERHASIL DIPERBAIKI!\n";
        
    } else {
        echo "   ✅ TIDAK ADA ARSIP YANG BERMASALAH\n";
        DB::rollBack();
    }

    // 3. Verifikasi hasil
    echo "\n3. VERIFIKASI HASIL PERBAIKAN...\n";
    
    $totalArsipInJre = Arsip::where('is_archived_to_jre', true)->count();
    $totalJreActive = Jre::active()->count();
    
    echo "   Dashboard akan menampilkan: {$totalArsipInJre} arsip di JRE\n";
    echo "   Halaman JRE akan menampilkan: {$totalJreActive} arsip di JRE\n";
    
    if ($totalArsipInJre == $totalJreActive) {
        echo "   ✅ KONSISTENSI DATA TERCAPAI!\n";
        echo "   Dashboard dan halaman JRE akan menampilkan angka yang sama\n";
    } else {
        echo "   ⚠️  Masih ada ketidakkonsistenan\n";
    }

    // 4. Penjelasan untuk user
    echo "\n4. PENJELASAN UNTUK USER:\n";
    echo "   MASALAH SEBELUMNYA:\n";
    echo "   - Dashboard menghitung dari tabel 'arsips' (is_archived_to_jre = true)\n";
    echo "   - Halaman JRE menghitung dari tabel 'jres' (status != destroyed/transferred)\n";
    echo "   - Ketika arsip dimusnahkan, JRE status berubah ke 'destroyed'\n";
    echo "   - Tapi is_archived_to_jre di tabel arsips tidak diupdate ke false\n\n";
    
    echo "   PERBAIKAN YANG DILAKUKAN:\n";
    echo "   - Arsip yang JRE-nya sudah destroyed/transferred → is_archived_to_jre = false\n";
    echo "   - Arsip yang dimusnahkan tidak lagi dihitung sebagai 'arsip di JRE'\n";
    echo "   - Dashboard dan halaman JRE sekarang konsisten\n\n";
    
    echo "   HASIL:\n";
    echo "   - Arsip yang sudah dimusnahkan → tidak muncul di hitungan JRE\n";
    echo "   - Hanya arsip yang benar-benar masih aktif di JRE yang dihitung\n";

} catch (\Exception $e) {
    DB::rollBack();
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== FIX COMPLETE ===\n";

?>
