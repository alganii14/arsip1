<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('peminjaman_arsips', function (Blueprint $table) {
            // Drop the existing enum column and recreate it with the new values
            $table->dropColumn('status');
        });

        Schema::table('peminjaman_arsips', function (Blueprint $table) {
            // Add the new enum column with pending option
            $table->enum('status', ['pending', 'dipinjam', 'dikembalikan', 'terlambat'])->default('pending')->after('batas_waktu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman_arsips', function (Blueprint $table) {
            // Revert back to the original enum
            $table->dropColumn('status');
        });

        Schema::table('peminjaman_arsips', function (Blueprint $table) {
            $table->enum('status', ['dipinjam', 'dikembalikan', 'terlambat'])->default('dipinjam')->after('batas_waktu');
        });
    }
};
