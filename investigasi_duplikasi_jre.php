<?php

require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Jre;
use App\Models\Arsip;
use App\Models\Pemindahan;
use Illuminate\Support\Facades\DB;

echo "=== INVESTIGASI DUPLIKASI JRE ===\n\n";

try {
    // 1. Cek jumlah arsip
    echo "1. JUMLAH ARSIP:\n";
    $totalArsip = Arsip::count();
    echo "   Total arsip di database: {$totalArsip}\n\n";

    // 2. Tampilkan semua arsip
    echo "2. DAFTAR SEMUA ARSIP:\n";
    $allArsips = Arsip::all();
    foreach ($allArsips as $arsip) {
        echo "   Arsip ID {$arsip->id}: {$arsip->nama_dokumen}\n";
        echo "   Kode: {$arsip->kode}\n";
        echo "   is_archived_to_jre: " . ($arsip->is_archived_to_jre ? 'true' : 'false') . "\n";
        echo "   created_at: " . $arsip->created_at->format('Y-m-d H:i:s') . "\n";
        echo "   ---\n";
    }

    // 3. Cek jumlah JRE
    echo "\n3. JUMLAH JRE:\n";
    $totalJre = Jre::count();
    echo "   Total JRE di database: {$totalJre}\n\n";

    // 4. Tampilkan semua JRE dengan detail
    echo "4. DAFTAR SEMUA JRE:\n";
    $allJres = Jre::with(['arsip'])->orderBy('id')->get();
    foreach ($allJres as $jre) {
        echo "   JRE ID {$jre->id}:\n";
        echo "   - Arsip ID: {$jre->arsip_id}\n";
        echo "   - Arsip: {$jre->arsip->nama_dokumen}\n";
        echo "   - Status: {$jre->status}\n";
        echo "   - Processed at: " . ($jre->processed_at ? $jre->processed_at->format('Y-m-d H:i:s') : 'null') . "\n";
        echo "   - Transferred at: " . ($jre->transferred_at ? $jre->transferred_at->format('Y-m-d H:i:s') : 'null') . "\n";
        echo "   ---\n";
    }

    // 5. Cek duplikasi JRE untuk arsip yang sama
    echo "\n5. ANALISIS DUPLIKASI:\n";
    $duplicates = Jre::select('arsip_id', DB::raw('COUNT(*) as count'))
        ->groupBy('arsip_id')
        ->having('count', '>', 1)
        ->get();

    if ($duplicates->count() > 0) {
        echo "   ❌ DITEMUKAN DUPLIKASI JRE!\n";
        foreach ($duplicates as $duplicate) {
            $arsip = Arsip::find($duplicate->arsip_id);
            echo "   - Arsip ID {$duplicate->arsip_id} ({$arsip->nama_dokumen}) memiliki {$duplicate->count} JRE!\n";

            // Tampilkan semua JRE untuk arsip ini
            $jresForThisArsip = Jre::where('arsip_id', $duplicate->arsip_id)->orderBy('id')->get();
            foreach ($jresForThisArsip as $jre) {
                echo "     * JRE ID {$jre->id} - Status: {$jre->status} - Created: " . $jre->created_at->format('Y-m-d H:i:s') . "\n";
            }
        }
    } else {
        echo "   ✅ Tidak ada duplikasi JRE ditemukan\n";
    }

    // 6. Cek pemindahan
    echo "\n6. DAFTAR PEMINDAHAN:\n";
    $allPemindahans = Pemindahan::with(['arsip'])->get();
    if ($allPemindahans->count() > 0) {
        foreach ($allPemindahans as $pemindahan) {
            echo "   Pemindahan ID {$pemindahan->id}:\n";
            echo "   - Arsip ID: {$pemindahan->arsip_id}\n";
            echo "   - Arsip: {$pemindahan->arsip->nama_dokumen}\n";
            echo "   - Status: {$pemindahan->status}\n";
            echo "   - Created: " . $pemindahan->created_at->format('Y-m-d H:i:s') . "\n";
            echo "   ---\n";
        }
    } else {
        echo "   Tidak ada data pemindahan\n";
    }

    // 7. Kemungkinan penyebab
    echo "\n7. KEMUNGKINAN PENYEBAB DUPLIKASI:\n";
    echo "   1. User melakukan pemindahan berulang kali dari JRE\n";
    echo "   2. Ada bug di proses pemindahan yang membuat JRE baru\n";
    echo "   3. Ada trigger atau observer yang membuat JRE otomatis\n";
    echo "   4. Proses auto-move ke JRE berjalan berulang\n";
    echo "   5. Data tidak di-commit dengan benar saat transaksi\n\n";

    // 8. Solusi yang disarankan
    echo "8. SOLUSI:\n";
    if ($duplicates->count() > 0) {
        echo "   - Hapus JRE duplikat (biarkan yang terbaru)\n";
        echo "   - Perbaiki logic di kode untuk mencegah duplikasi\n";
        echo "   - Tambah unique constraint di database\n";
    }
    echo "   - Review kode pemindahan dan auto-move JRE\n";
    echo "   - Pastikan tidak ada multiple trigger yang berjalan\n";

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    exit(1);
}

?>
