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
            $table->dropColumn(['jabatan', 'catatan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman_arsips', function (Blueprint $table) {
            $table->string('jabatan')->nullable()->after('peminjam');
            $table->text('catatan')->nullable()->after('tujuan_peminjaman');
        });
    }
};
