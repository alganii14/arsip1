<?php

require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Jre;
use App\Models\Arsip;
use Illuminate\Support\Facades\DB;

echo "=== MEMBERSIHKAN DUPLIKASI JRE ===\n\n";

try {
    // 1. Cari JRE duplikat
    echo "1. Mencari JRE duplikat...\n";

    $duplicates = Jre::select('arsip_id', DB::raw('COUNT(*) as count'))
        ->groupBy('arsip_id')
        ->having('count', '>', 1)
        ->get();

    if ($duplicates->count() == 0) {
        echo "   ✅ Tidak ada duplikasi JRE ditemukan\n";
        exit(0);
    }

    echo "   Ditemukan " . $duplicates->count() . " arsip dengan JRE duplikat\n\n";

    DB::beginTransaction();
    try {
        foreach ($duplicates as $duplicate) {
            $arsipId = $duplicate->arsip_id;
            $arsip = Arsip::find($arsipId);

            echo "2. Membersihkan duplikasi untuk Arsip ID {$arsipId}:\n";
            echo "   Arsip: {$arsip->nama_dokumen}\n";

            // Ambil semua JRE untuk arsip ini, urutkan dari yang terbaru
            $allJresForArsip = Jre::where('arsip_id', $arsipId)
                ->orderBy('created_at', 'desc')
                ->get();

            echo "   Total JRE: " . $allJresForArsip->count() . "\n";

            // Ambil JRE terbaru (yang pertama dalam urutan desc)
            $latestJre = $allJresForArsip->first();
            $jresToDelete = $allJresForArsip->slice(1); // Ambil sisanya untuk dihapus

            echo "   JRE yang akan dipertahankan: ID {$latestJre->id} (Created: " . $latestJre->created_at->format('Y-m-d H:i:s') . ")\n";
            echo "   JRE yang akan dihapus: " . $jresToDelete->count() . " record\n";

            // Hapus JRE duplikat
            foreach ($jresToDelete as $jreToDelete) {
                echo "     - Menghapus JRE ID {$jreToDelete->id} (Created: " . $jreToDelete->created_at->format('Y-m-d H:i:s') . ")\n";
                $jreToDelete->delete();
            }

            echo "   ✅ Selesai membersihkan arsip ID {$arsipId}\n\n";
        }

        DB::commit();
        echo "✅ PEMBERSIHAN SELESAI!\n\n";

    } catch (\Exception $e) {
        DB::rollBack();
        echo "❌ ERROR: " . $e->getMessage() . "\n";
        throw $e;
    }

    // 3. Verifikasi hasil
    echo "3. Verifikasi hasil pembersihan...\n";

    $remainingJres = Jre::count();
    $uniqueArsips = Jre::distinct('arsip_id')->count('arsip_id');

    echo "   Total JRE setelah pembersihan: {$remainingJres}\n";
    echo "   Arsip unik di JRE: {$uniqueArsips}\n";

    if ($remainingJres == $uniqueArsips) {
        echo "   ✅ Perfect! Setiap arsip hanya memiliki 1 JRE\n";
    } else {
        echo "   ⚠️  Masih ada duplikasi yang tersisa\n";
    }

    // 4. Tampilkan JRE yang tersisa
    echo "\n4. JRE yang tersisa:\n";
    $remainingJreList = Jre::with(['arsip'])->orderBy('id')->get();
    foreach ($remainingJreList as $jre) {
        echo "   JRE ID {$jre->id}: {$jre->arsip->nama_dokumen} (Status: {$jre->status})\n";
    }

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    exit(1);
}

?>
