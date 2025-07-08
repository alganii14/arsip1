<?php

require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Jre;
use App\Models\Arsip;
use App\Models\Pemindahan;

echo "=== DIAGNOSA LENGKAP MASALAH JRE ===\n\n";

try {
    // 1. Tampilkan semua JRE dengan detail lengkap
    echo "1. DAFTAR SEMUA JRE DENGAN STATUS:\n";
    $allJres = Jre::with(['arsip'])->orderBy('id')->get();

    foreach ($allJres as $jre) {
        echo "   JRE ID: {$jre->id}\n";
        echo "   Arsip: {$jre->arsip->nama_dokumen} (ID: {$jre->arsip_id})\n";
        echo "   Status JRE: {$jre->status}\n";
        echo "   Arsip is_archived_to_jre: " . ($jre->arsip->is_archived_to_jre ? 'true' : 'false') . "\n";

        // Cek pemindahan
        $pemindahan = Pemindahan::where('arsip_id', $jre->arsip_id)->latest()->first();
        if ($pemindahan) {
            echo "   Ada Pemindahan: ID {$pemindahan->id} (Status: {$pemindahan->status})\n";
            echo "   Tanggal Pemindahan: " . ($pemindahan->completed_at ? $pemindahan->completed_at->format('Y-m-d H:i:s') : 'N/A') . "\n";
        } else {
            echo "   Tidak ada pemindahan\n";
        }
        echo "   ---\n";
    }

    echo "\n2. JRE YANG MUNCUL DI DAFTAR (ACTIVE):\n";
    $activeJres = Jre::active()->with(['arsip'])->get();

    if ($activeJres->count() > 0) {
        foreach ($activeJres as $jre) {
            echo "   - JRE ID {$jre->id}: {$jre->arsip->nama_dokumen} (Status: {$jre->status})\n";
        }
    } else {
        echo "   Tidak ada JRE aktif\n";
    }

    echo "\n3. JRE YANG SUDAH TRANSFERRED (TIDAK MUNCUL DI DAFTAR):\n";
    $transferredJres = Jre::where('status', 'transferred')->with(['arsip'])->get();

    if ($transferredJres->count() > 0) {
        foreach ($transferredJres as $jre) {
            echo "   - JRE ID {$jre->id}: {$jre->arsip->nama_dokumen} (Status: {$jre->status})\n";
            echo "     Transferred at: " . ($jre->transferred_at ? $jre->transferred_at->format('Y-m-d H:i:s') : 'N/A') . "\n";
        }
    } else {
        echo "   Tidak ada JRE yang transferred\n";
    }

    echo "\n4. ARSIP YANG ADA DI DAFTAR PEMINDAHAN:\n";
    $pemindahans = Pemindahan::with(['arsip'])->where('status', 'completed')->get();

    if ($pemindahans->count() > 0) {
        foreach ($pemindahans as $pemindahan) {
            echo "   - Pemindahan ID {$pemindahan->id}: {$pemindahan->arsip->nama_dokumen}\n";
            echo "     Arsip ID: {$pemindahan->arsip_id}\n";
            echo "     Status: {$pemindahan->status}\n";
            echo "     Tanggal: " . ($pemindahan->completed_at ? $pemindahan->completed_at->format('Y-m-d H:i:s') : 'N/A') . "\n";

            // Cek apakah ada JRE untuk arsip ini
            $jreForThis = Jre::where('arsip_id', $pemindahan->arsip_id)->first();
            if ($jreForThis) {
                echo "     JRE terkait: ID {$jreForThis->id} (Status: {$jreForThis->status})\n";
            } else {
                echo "     Tidak ada JRE terkait\n";
            }
            echo "     ---\n";
        }
    } else {
        echo "   Tidak ada pemindahan yang completed\n";
    }

    echo "\n5. ANALISIS MASALAH:\n";

    // Cari arsip yang sudah dipindahkan tapi masih muncul di JRE
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

    if ($problematicJres->count() > 0) {
        echo "   ❌ MASALAH DITEMUKAN!\n";
        echo "   JRE yang masih aktif padahal arsipnya sudah dipindahkan:\n";
        foreach ($problematicJres as $jre) {
            echo "   - JRE ID {$jre->id}: {$jre->arsip->nama_dokumen}\n";
        }
        echo "\n   KEMUNGKINAN PENYEBAB:\n";
        echo "   - Proses pemindahan tidak mengupdate status JRE dengan benar\n";
        echo "   - Ada error saat transaksi database\n";
        echo "   - Cache browser yang belum di-refresh\n";
    } else {
        echo "   ✅ TIDAK ADA MASALAH DITEMUKAN\n";
        echo "   Semua JRE sudah sesuai dengan status pemindahan\n";
        echo "\n   KEMUNGKINAN PENYEBAB MASALAH USER:\n";
        echo "   - Cache browser belum di-refresh (tekan Ctrl+F5)\n";
        echo "   - User melihat data lama sebelum perbaikan\n";
        echo "   - Ada session/cache yang tersimpan\n";
    }

    echo "\n6. REKOMENDASI:\n";
    echo "   1. Refresh halaman JRE dengan Ctrl+F5 (hard refresh)\n";
    echo "   2. Clear cache browser\n";
    echo "   3. Logout dan login kembali jika perlu\n";
    echo "   4. Jika masalah masih ada, hubungi developer\n";

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    exit(1);
}

?>
