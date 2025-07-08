<?php

require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Jre;
use App\Models\Arsip;
use App\Models\Pemindahan;
use Illuminate\Support\Facades\DB;

echo "=== PERBAIKAN STATUS JRE YANG SUDAH DIPINDAHKAN ===\n\n";

try {
    // 1. Cari JRE yang arsipnya sudah dipindahkan tapi status JRE belum 'transferred'
    echo "1. Mencari JRE yang belum sinkron dengan status pemindahan...\n";

    $problemJres = Jre::with(['arsip'])
        ->whereNotIn('status', ['transferred'])
        ->whereHas('arsip', function($query) {
            $query->where('is_archived_to_jre', false);
        })
        ->get();

    echo "   Ditemukan " . $problemJres->count() . " JRE yang perlu diperbaiki\n\n";

    if ($problemJres->count() > 0) {
        echo "2. Detail JRE yang bermasalah:\n";
        foreach ($problemJres as $jre) {
            echo "   - JRE ID: {$jre->id}\n";
            echo "     Arsip: {$jre->arsip->nama_dokumen}\n";
            echo "     Status JRE: {$jre->status}\n";
            echo "     Arsip is_archived_to_jre: " . ($jre->arsip->is_archived_to_jre ? 'true' : 'false') . "\n";

            // Cek apakah ada record pemindahan untuk arsip ini
            $pemindahan = Pemindahan::where('arsip_id', $jre->arsip_id)
                ->whereIn('status', ['completed'])
                ->latest()
                ->first();

            if ($pemindahan) {
                echo "     Ada pemindahan: ID {$pemindahan->id} (Status: {$pemindahan->status})\n";
            } else {
                echo "     Tidak ada record pemindahan\n";
            }
            echo "\n";
        }

        echo "3. Memperbaiki status JRE...\n";
        $fixedCount = 0;

        DB::beginTransaction();
        try {
            foreach ($problemJres as $jre) {
                // Cek apakah arsip sudah dipindahkan
                $pemindahan = Pemindahan::where('arsip_id', $jre->arsip_id)
                    ->whereIn('status', ['completed'])
                    ->latest()
                    ->first();

                if ($pemindahan && !$jre->arsip->is_archived_to_jre) {
                    // Update status JRE menjadi 'transferred'
                    $jre->update([
                        'status' => 'transferred',
                        'transferred_at' => $pemindahan->completed_at ?? now(),
                        'transferred_by' => $pemindahan->completed_by ?? $pemindahan->user_id,
                        'transfer_notes' => 'Status disinkronkan: Arsip sudah dipindahkan (Pemindahan ID: ' . $pemindahan->id . ')'
                    ]);

                    echo "   ✓ Fixed JRE ID {$jre->id} - Status diubah ke 'transferred'\n";
                    $fixedCount++;
                }
            }

            DB::commit();
            echo "\n   Total JRE yang diperbaiki: {$fixedCount}\n\n";

        } catch (\Exception $e) {
            DB::rollBack();
            echo "   ERROR: " . $e->getMessage() . "\n";
            throw $e;
        }
    }

    // 4. Verifikasi hasil perbaikan
    echo "4. Verifikasi hasil perbaikan...\n";

    $activeJres = Jre::active()->count();
    $transferredJres = Jre::where('status', 'transferred')->count();
    $totalJres = Jre::count();

    echo "   Total JRE: {$totalJres}\n";
    echo "   JRE Aktif (yang muncul di daftar): {$activeJres}\n";
    echo "   JRE Transferred (tidak muncul di daftar): {$transferredJres}\n\n";

    // 5. Cek apakah masih ada JRE yang bermasalah
    $remainingProblems = Jre::with(['arsip'])
        ->whereNotIn('status', ['transferred'])
        ->whereHas('arsip', function($query) {
            $query->where('is_archived_to_jre', false);
        })
        ->count();

    if ($remainingProblems > 0) {
        echo "⚠️  PERHATIAN: Masih ada {$remainingProblems} JRE yang bermasalah!\n";
        echo "   Silahkan periksa manual atau jalankan script ini lagi.\n\n";
    } else {
        echo "✅ SELESAI: Semua JRE sudah tersinkronasi dengan benar!\n\n";
    }

    echo "=== PERBAIKAN SELESAI ===\n";
    echo "Silahkan refresh halaman JRE untuk melihat hasilnya.\n";

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    exit(1);
}

?>
