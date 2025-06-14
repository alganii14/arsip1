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
            // Tambahkan kolom untuk relasi dengan user
            $table->unsignedBigInteger('peminjam_user_id')->nullable()->after('arsip_id');
            $table->foreign('peminjam_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman_arsips', function (Blueprint $table) {
            $table->dropForeign(['peminjam_user_id']);
            $table->dropColumn('peminjam_user_id');
        });
    }
};