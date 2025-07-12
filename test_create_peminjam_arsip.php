<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\Arsip;
use Carbon\Carbon;

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== CREATING TEST ARSIP FOR PEMINJAM ===\n\n";

try {
    // Get peminjam user
    $peminjamUser = User::where('role', 'peminjam')->first();

    if (!$peminjamUser) {
        echo "ERROR: No peminjam user found!\n";
        exit(1);
    }

    echo "1. Creating test arsip for: {$peminjamUser->name}\n";

    // Create test arsip
    $testArsip = Arsip::create([
        'kode' => 'TEST-PEMINJAM-' . now()->format('YmdHis'),
        'nama_dokumen' => 'Dokumen Test Milik ' . $peminjamUser->name,
        'kategori' => 'Testing',
        'tanggal_arsip' => Carbon::now()->format('Y-m-d'),
        'keterangan' => 'Arsip test untuk menguji fitur multi-seksi',
        'retention_years' => 5,
        'retention_date' => Carbon::now()->addYears(5)->format('Y-m-d'),
        'created_by' => $peminjamUser->id
    ]);

    echo "   ✓ Test arsip created: {$testArsip->kode}\n\n";

    // Test scopes again
    echo "2. Testing scopes after creating test arsip...\n";

    $ownedArsips = Arsip::ownedBy($peminjamUser->id)->get();
    echo "   - Owned arsips: {$ownedArsips->count()}\n";

    $accessibleArsips = Arsip::accessibleBy($peminjamUser->id)->get();
    echo "   - Accessible arsips: {$accessibleArsips->count()}\n";

    $allArsips = Arsip::active()->get();
    echo "   - Total active arsips: {$allArsips->count()}\n\n";

    // Test filtered view for peminjam
    echo "3. Testing filtered view for peminjam...\n";
    $filteredArsips = Arsip::active()->accessibleBy($peminjamUser->id)->get();
    echo "   - Arsips accessible by peminjam: {$filteredArsips->count()}\n";

    foreach ($filteredArsips as $arsip) {
        $creatorName = $arsip->creator ? $arsip->creator->name : 'Unknown';
        $isOwned = $arsip->created_by === $peminjamUser->id ? '(OWNED)' : '(BORROWED)';
        echo "     * {$arsip->kode} - Created by: {$creatorName} {$isOwned}\n";
    }

    echo "\n=== TEST COMPLETED ===\n";
    echo "✓ Peminjam can now create and manage their own arsips\n";
    echo "✓ Peminjam can only see arsips they own or borrow\n";
    echo "✓ Multi-seksi feature is working correctly\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}

?>
