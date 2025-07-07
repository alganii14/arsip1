<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

use App\Models\Arsip;
use Carbon\Carbon;

echo "Fixing retention dates for existing arsips...\n";

$arsips = Arsip::all();

foreach ($arsips as $arsip) {
    echo "Processing arsip: {$arsip->kode}\n";
    echo "  Tanggal Arsip: " . ($arsip->tanggal_arsip ? $arsip->tanggal_arsip : 'NULL') . "\n";
    echo "  Current Retention Date: " . ($arsip->retention_date ? $arsip->retention_date : 'NULL') . "\n";
    echo "  Retention Years: " . ($arsip->retention_years ?? 'NULL') . "\n";

    // Skip if no tanggal_arsip
    if (!$arsip->tanggal_arsip) {
        echo "  ⚠️  Skipping - No tanggal_arsip\n\n";
        continue;
    }

    // Recalculate retention date
    try {
        $years = $arsip->retention_years ?? 5; // Default 5 years if not set
        $tanggalArsip = Carbon::parse($arsip->tanggal_arsip);
        $newRetentionDate = $tanggalArsip->copy()->addYears($years);

        $arsip->update([
            'retention_date' => $newRetentionDate->format('Y-m-d'),
            'retention_years' => $years
        ]);

        echo "  New Retention Date: " . $newRetentionDate->format('Y-m-d') . "\n";
        echo "  ✅ Fixed!\n\n";
    } catch (\Exception $e) {
        echo "  ❌ Error: " . $e->getMessage() . "\n\n";
    }
}

echo "Done!\n";
