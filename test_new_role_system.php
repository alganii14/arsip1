<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// Bootstrap Laravel application
$app = new Application($_ENV['APP_BASE_PATH'] ?? dirname(__DIR__));

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

// Bootstrap the application
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Create a request instance for console
$request = Request::create('/', 'GET');

// Handle the request
$response = $kernel->handle($request);

// Now we can access models
use App\Models\User;

echo "=== TESTING NEW ROLE SYSTEM ===\n\n";

try {
    // Get all users and their roles
    $users = User::all();
    
    echo "Current users and their roles:\n";
    echo "--------------------------------\n";
    
    foreach ($users as $user) {
        echo "ID: {$user->id}\n";
        echo "Name: {$user->name}\n";
        echo "Email: {$user->email}\n";
        echo "Role: {$user->role}\n";
        echo "Department: " . ($user->department ?? 'N/A') . "\n";
        
        // Test new role checking methods
        echo "Is Unit Kerja: " . ($user->isUnitKerja() ? 'Yes' : 'No') . "\n";
        echo "Is Unit Pengelola: " . ($user->isUnitPengelola() ? 'Yes' : 'No') . "\n";
        
        // Test backward compatibility
        echo "Is Admin (compat): " . ($user->isAdmin() ? 'Yes' : 'No') . "\n";
        echo "Is Petugas (compat): " . ($user->isPetugas() ? 'Yes' : 'No') . "\n";
        echo "Is Peminjam (compat): " . ($user->isPeminjam() ? 'Yes' : 'No') . "\n";
        
        echo "--------------------------------\n";
    }
    
    // Count roles
    $unitKerjaCount = User::where('role', 'unit_kerja')->count();
    $unitPengelolaCount = User::where('role', 'unit_pengelola')->count();
    
    echo "\nRole Summary:\n";
    echo "Unit Kerja (Admin/Petugas): {$unitKerjaCount}\n";
    echo "Unit Pengelola (Peminjam): {$unitPengelolaCount}\n";
    echo "Total Users: " . ($unitKerjaCount + $unitPengelolaCount) . "\n";
    
    echo "\n=== ROLE SYSTEM UPDATE SUCCESSFUL ===\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

// Terminate the application
$kernel->terminate($request, $response);
