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
            $table->datetime('tanggal_pinjam')->change();
            $table->datetime('batas_waktu')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman_arsips', function (Blueprint $table) {
            $table->date('tanggal_pinjam')->change();
            $table->date('batas_waktu')->change();
        });
    }
};
