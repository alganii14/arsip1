<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\Arsip;

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TESTING MULTI-SEKSI ARSIP FEATURE ===\n\n";

try {
    // Check users
    echo "1. Checking users in database...\n";
    $users = User::all();
    foreach ($users as $user) {
        echo "   - {$user->name} ({$user->role}) - Department: {$user->department}\n";
    }
    echo "\n";

    // Check arsips
    echo "2. Checking arsips in database...\n";
    $arsips = Arsip::with('creator')->get();
    foreach ($arsips as $arsip) {
        $creatorName = $arsip->creator ? $arsip->creator->name : 'Unknown';
        echo "   - {$arsip->kode} - {$arsip->nama_dokumen} - Created by: {$creatorName}\n";
    }
    echo "\n";

    // Test scope accessible by
    echo "3. Testing accessibility scopes...\n";
    $peminjamUser = User::where('role', 'peminjam')->first();
    if ($peminjamUser) {
        echo "   Testing for peminjam: {$peminjamUser->name}\n";

        $ownedArsips = Arsip::ownedBy($peminjamUser->id)->get();
        echo "   - Owned arsips: {$ownedArsips->count()}\n";

        $accessibleArsips = Arsip::accessibleBy($peminjamUser->id)->get();
        echo "   - Accessible arsips: {$accessibleArsips->count()}\n";
    } else {
        echo "   No peminjam user found.\n";
    }
    echo "\n";

    echo "=== TEST COMPLETED ===\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}

?>
