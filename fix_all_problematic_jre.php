<?php

require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Jre;
use App\Models\Arsip;
use App\Models\Pemindahan;
use Illuminate\Support\Facades\DB;

echo "=== PERBAIKAN SEMUA JRE YANG BERMASALAH ===\n\n";

try {
    // Cari semua JRE yang bermasalah
    echo "1. Mencari semua JRE yang bermasalah...\n";

    $problematicJres = Jre::active()
        ->with(['arsip'])
        ->get()
        ->filter(function($jre) {
            // Cek apakah arsip ini sudah ada di pemindahan
            $hasPemindahan = Pemindahan::where('arsip_id', $jre->arsip_id)
                ->where('status', 'completed')
                ->exists();
            return $hasPemindahan;
        });

    echo "   Ditemukan " . $problematicJres->count() . " JRE yang bermasalah\n\n";

    if ($problematicJres->count() == 0) {
        echo "✅ TIDAK ADA JRE YANG BERMASALAH!\n";
        echo "Semua JRE sudah sinkron dengan status pemindahan.\n";
        exit(0);
    }

    echo "2. Detail JRE yang akan diperbaiki:\n";
    foreach ($problematicJres as $jre) {
        echo "   - JRE ID {$jre->id}: {$jre->arsip->nama_dokumen}\n";
        echo "     Status: {$jre->status}\n";
        echo "     Arsip ID: {$jre->arsip_id}\n";
    }
    echo "\n";

    // Perbaiki satu per satu
    echo "3. Melakukan perbaikan...\n";
    $fixedCount = 0;

    DB::beginTransaction();
    try {
        foreach ($problematicJres as $jre) {
            echo "   Memperbaiki JRE ID {$jre->id}...\n";

            // Cari data pemindahan
            $pemindahan = Pemindahan::where('arsip_id', $jre->arsip_id)
                ->where('status', 'completed')
                ->latest()
                ->first();

            if (!$pemindahan) {
                echo "     ⚠️  Tidak ada data pemindahan, skip\n";
                continue;
            }

            // Update status JRE
            $updateResult = $jre->update([
                'status' => 'transferred',
                'transferred_at' => $pemindahan->completed_at,
                'transferred_by' => $pemindahan->completed_by,
                'transfer_notes' => 'Status diperbaiki otomatis - Arsip sudah dipindahkan (Pemindahan ID: ' . $pemindahan->id . ') pada ' . $pemindahan->completed_at->format('Y-m-d H:i:s')
            ]);

            if (!$updateResult) {
                echo "     ❌ Gagal mengubah status JRE\n";
                continue;
            }

            // Update status arsip jika perlu
            if ($jre->arsip->is_archived_to_jre) {
                $arsipUpdateResult = $jre->arsip->update([
                    'is_archived_to_jre' => false,
                    'archived_to_jre_at' => null
                ]);

                if ($arsipUpdateResult) {
                    echo "     ✓ Status JRE dan arsip berhasil diperbaiki\n";
                } else {
                    echo "     ⚠️  Status JRE diperbaiki, tapi gagal update arsip\n";
                }
            } else {
                echo "     ✓ Status JRE berhasil diperbaiki\n";
            }

            $fixedCount++;
        }

        DB::commit();
        echo "\n   ✅ Berhasil memperbaiki {$fixedCount} JRE\n\n";

    } catch (\Exception $e) {
        DB::rollBack();
        echo "   ❌ ERROR saat perbaikan: " . $e->getMessage() . "\n";
        throw $e;
    }

    // Verifikasi hasil
    echo "4. Verifikasi hasil perbaikan...\n";

    $activeJresAfter = Jre::active()->count();
    $transferredJresAfter = Jre::where('status', 'transferred')->count();
    $totalJres = Jre::count();

    echo "   Total JRE: {$totalJres}\n";
    echo "   JRE Aktif (yang muncul di daftar): {$activeJresAfter}\n";
    echo "   JRE Transferred (tidak muncul di daftar): {$transferredJresAfter}\n\n";

    // Cek apakah masih ada yang bermasalah
    $remainingProblems = Jre::active()
        ->with(['arsip'])
        ->get()
        ->filter(function($jre) {
            $hasPemindahan = Pemindahan::where('arsip_id', $jre->arsip_id)
                ->where('status', 'completed')
                ->exists();
            return $hasPemindahan;
        });

    if ($remainingProblems->count() > 0) {
        echo "⚠️  PERHATIAN: Masih ada " . $remainingProblems->count() . " JRE yang bermasalah!\n";
        foreach ($remainingProblems as $jre) {
            echo "   - JRE ID {$jre->id}: {$jre->arsip->nama_dokumen}\n";
        }
        echo "\n";
    } else {
        echo "✅ SEMPURNA! Semua JRE sudah tidak bermasalah lagi.\n\n";
    }

    echo "=== PERBAIKAN SELESAI ===\n";
    echo "Silahkan refresh halaman JRE (Ctrl+F5) untuk melihat hasilnya.\n";
    echo "Arsip yang sudah dipindahkan seharusnya tidak muncul lagi di daftar JRE.\n";

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    exit(1);
}

?>
