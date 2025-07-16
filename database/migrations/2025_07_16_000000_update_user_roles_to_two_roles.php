<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, modify the enum to include all old and new values temporarily
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'petugas', 'peminjam', 'unit_kerja', 'unit_pengelola'])->default('unit_pengelola')->after('email');
        });

        // Update existing data
        // Convert 'admin' and 'petugas' to 'unit_kerja'
        DB::table('users')
            ->whereIn('role', ['admin', 'petugas'])
            ->update(['role' => 'unit_kerja']);
        
        // Convert 'peminjam' to 'unit_pengelola'
        DB::table('users')
            ->where('role', 'peminjam')
            ->update(['role' => 'unit_pengelola']);

        // Finally, modify the enum to only have the new values
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['unit_kerja', 'unit_pengelola'])->default('unit_pengelola')->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse the changes
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'petugas', 'peminjam'])->default('peminjam')->after('email');
        });

        // Restore original data
        DB::table('users')
            ->where('role', 'unit_kerja')
            ->update(['role' => 'admin']);
        
        DB::table('users')
            ->where('role', 'unit_pengelola')
            ->update(['role' => 'peminjam']);
    }
};
