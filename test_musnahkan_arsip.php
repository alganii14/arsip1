<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Test database connection
try {
    $connection = DB::connection();
    echo "Database connection: OK\n";

    // Check if archive_destructions table exists
    if (Schema::hasTable('archive_destructions')) {
        echo "archive_destructions table: EXISTS\n";
    } else {
        echo "archive_destructions table: NOT EXISTS\n";
    }

    // Test ArchiveDestruction model
    $model = new \App\Models\ArchiveDestruction();
    echo "ArchiveDestruction model: OK\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
