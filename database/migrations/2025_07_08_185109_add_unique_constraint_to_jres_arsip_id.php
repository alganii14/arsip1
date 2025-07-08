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
        Schema::table('jres', function (Blueprint $table) {
            // Add unique constraint to arsip_id to prevent duplicates
            $table->unique('arsip_id', 'jres_arsip_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jres', function (Blueprint $table) {
            // Drop the unique constraint
            $table->dropUnique('jres_arsip_id_unique');
        });
    }
};
