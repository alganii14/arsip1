<?php

require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'arsip3', // sesuaikan dengan nama database Anda
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
]);

$capsule->setAsGlobal();

$capsule->bootEloquent();

echo "=== TESTING NEW ROLE SYSTEM ===\n\n";

try {
    // Query users directly from database
    $users = $capsule->table('users')->get();
    
    echo "Current users and their roles:\n";
    echo "--------------------------------\n";
    
    foreach ($users as $user) {
        echo "ID: {$user->id}\n";
        echo "Name: {$user->name}\n";
        echo "Email: {$user->email}\n";
        echo "Role: {$user->role}\n";
        echo "Department: " . ($user->department ?? 'N/A') . "\n";
        echo "Created: {$user->created_at}\n";
        echo "--------------------------------\n";
    }
    
    // Count roles
    $unitKerjaCount = $capsule->table('users')->where('role', 'unit_kerja')->count();
    $unitPengelolaCount = $capsule->table('users')->where('role', 'unit_pengelola')->count();
    $oldRolesCount = $capsule->table('users')->whereIn('role', ['admin', 'petugas', 'peminjam'])->count();
    
    echo "\nRole Summary:\n";
    echo "Unit Kerja (Admin/Petugas): {$unitKerjaCount}\n";
    echo "Unit Pengelola (Peminjam): {$unitPengelolaCount}\n";
    echo "Old roles remaining: {$oldRolesCount}\n";
    echo "Total Users: " . ($unitKerjaCount + $unitPengelolaCount + $oldRolesCount) . "\n";
    
    if ($oldRolesCount == 0) {
        echo "\n✅ ROLE SYSTEM UPDATE SUCCESSFUL - All old roles converted!\n";
    } else {
        echo "\n⚠️  WARNING: Some old roles still exist in database\n";
    }
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
