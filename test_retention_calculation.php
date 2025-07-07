<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

use App\Models\Arsip;
use Carbon\Carbon;

echo "Testing retention calculation...\n\n";

// Test 1: Create arsip with manual retention
echo "Test 1: Manual retention (3 years)\n";
$arsip = new Arsip([
    'kode' => 'TEST-001',
    'nama_dokumen' => 'Test Document',
    'kategori' => 'biasa',
    'tanggal_arsip' => '2023-01-15',
    'retention_years' => 3
]);
$arsip->save();

// Calculate retention date
$arsip->calculateRetentionDate(3);
$arsip->refresh();

echo "Tanggal Arsip: " . $arsip->tanggal_arsip . "\n";
echo "Retention Years: " . $arsip->retention_years . "\n";
echo "Retention Date: " . $arsip->retention_date . "\n";
echo "Expected: 2026-01-15\n";
$actualDate = substr($arsip->retention_date, 0, 10); // Get YYYY-MM-DD part
echo "Actual formatted: " . $actualDate . "\n";
echo "Match: " . ($actualDate == '2026-01-15' ? 'YES' : 'NO') . "\n\n";

// Test 2: Create arsip with auto retention (5 years)
echo "Test 2: Auto retention (5 years)\n";
$arsip2 = new Arsip([
    'kode' => 'TEST-002',
    'nama_dokumen' => 'Test Document 2',
    'kategori' => 'biasa',
    'tanggal_arsip' => '2024-03-10',
    'retention_years' => 5
]);
$arsip2->save();

// Calculate retention date
$arsip2->calculateRetentionDate();
$arsip2->refresh();

echo "Tanggal Arsip: " . $arsip2->tanggal_arsip . "\n";
echo "Retention Years: " . $arsip2->retention_years . "\n";
echo "Retention Date: " . $arsip2->retention_date . "\n";
echo "Expected: 2029-03-10\n";
$actualDate2 = substr($arsip2->retention_date, 0, 10); // Get YYYY-MM-DD part
echo "Actual formatted: " . $actualDate2 . "\n";
echo "Match: " . ($actualDate2 == '2029-03-10' ? 'YES' : 'NO') . "\n\n";

// Clean up test data
$arsip->delete();
$arsip2->delete();

echo "Test completed! Both test records deleted.\n";
