<?php

require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Jre;
use App\Models\Arsip;
use App\Models\Pemindahan;
use Illuminate\Support\Facades\DB;

echo "=== PERBAIKAN SPESIFIK JRE YANG BERMASALAH ===\n\n";

try {
    // Target JRE yang bermasalah
    $jreId = 2;

    echo "1. Mencari JRE ID {$jreId}...\n";
    $jre = Jre::with(['arsip'])->find($jreId);

    if (!$jre) {
        echo "   ERROR: JRE ID {$jreId} tidak ditemukan!\n";
        exit(1);
    }

    echo "   ✓ JRE ditemukan: {$jre->arsip->nama_dokumen}\n";
    echo "   Status saat ini: {$jre->status}\n";
    echo "   Arsip ID: {$jre->arsip_id}\n\n";

    // Cek pemindahan untuk arsip ini
    echo "2. Mencari data pemindahan...\n";
    $pemindahan = Pemindahan::where('arsip_id', $jre->arsip_id)
        ->where('status', 'completed')
        ->latest()
        ->first();

    if (!$pemindahan) {
        echo "   ERROR: Tidak ada data pemindahan yang completed untuk arsip ini!\n";
        exit(1);
    }

    echo "   ✓ Pemindahan ditemukan: ID {$pemindahan->id}\n";
    echo "   Status pemindahan: {$pemindahan->status}\n";
    echo "   Tanggal completed: " . $pemindahan->completed_at->format('Y-m-d H:i:s') . "\n";
    echo "   Completed by: User ID {$pemindahan->completed_by}\n\n";

    // Cek status arsip
    echo "3. Status arsip terkait...\n";
    echo "   is_archived_to_jre: " . ($jre->arsip->is_archived_to_jre ? 'true' : 'false') . "\n";
    echo "   archived_to_jre_at: " . ($jre->arsip->archived_to_jre_at ? $jre->arsip->archived_to_jre_at->format('Y-m-d H:i:s') : 'null') . "\n\n";

    // Perbaiki masalah
    echo "4. Melakukan perbaikan...\n";

    DB::beginTransaction();
    try {
        // Update status JRE menjadi 'transferred'
        $updateResult = $jre->update([
            'status' => 'transferred',
            'transferred_at' => $pemindahan->completed_at,
            'transferred_by' => $pemindahan->completed_by,
            'transfer_notes' => 'Status diperbaiki - Arsip sudah dipindahkan (Pemindahan ID: ' . $pemindahan->id . ') pada ' . $pemindahan->completed_at->format('Y-m-d H:i:s')
        ]);

        if ($updateResult) {
            echo "   ✓ Status JRE berhasil diubah ke 'transferred'\n";
        } else {
            throw new \Exception("Gagal mengubah status JRE");
        }

        // Pastikan arsip juga sudah tidak di JRE
        if ($jre->arsip->is_archived_to_jre) {
            $arsipUpdateResult = $jre->arsip->update([
                'is_archived_to_jre' => false,
                'archived_to_jre_at' => null
            ]);

            if ($arsipUpdateResult) {
                echo "   ✓ Status arsip juga diperbaiki (is_archived_to_jre = false)\n";
            } else {
                throw new \Exception("Gagal mengubah status arsip");
            }
        } else {
            echo "   ✓ Status arsip sudah benar (is_archived_to_jre = false)\n";
        }

        DB::commit();
        echo "   ✓ Semua perubahan berhasil disimpan\n\n";

    } catch (\Exception $e) {
        DB::rollBack();
        echo "   ERROR: " . $e->getMessage() . "\n";
        throw $e;
    }

    // Verifikasi hasil
    echo "5. Verifikasi hasil perbaikan...\n";
    $jre->refresh();

    echo "   Status JRE sekarang: {$jre->status}\n";
    echo "   Transferred at: " . ($jre->transferred_at ? $jre->transferred_at->format('Y-m-d H:i:s') : 'null') . "\n";
    echo "   Transferred by: User ID " . ($jre->transferred_by ?? 'null') . "\n";
    echo "   Transfer notes: " . ($jre->transfer_notes ?? 'null') . "\n\n";

    // Cek apakah JRE masih muncul di daftar aktif
    $isStillActive = Jre::active()->where('id', $jreId)->exists();
    echo "   Masih muncul di daftar JRE aktif: " . ($isStillActive ? 'YA (MASALAH!)' : 'TIDAK (BAGUS!)') . "\n\n";

    echo "✅ PERBAIKAN SELESAI!\n";
    echo "Silahkan refresh halaman JRE untuk melihat hasilnya.\n";
    echo "JRE ID {$jreId} seharusnya sudah tidak muncul di daftar JRE lagi.\n";

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    exit(1);
}

?>
