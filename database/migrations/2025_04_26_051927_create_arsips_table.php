<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_create_arsips_table.php
    // Update your migration file to include file columns
    public function up()
    {
        Schema::create('arsips', function (Blueprint $table) {
            $table->id();
            $table->string('kode');
            $table->string('nama_dokumen');
            $table->string('kategori');
            $table->date('tanggal_arsip');
            $table->text('keterangan')->nullable();
            $table->string('file_path')->nullable(); // Add this line to store file path
            $table->string('file_type')->nullable(); // Add this line to store file type
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arsips');
    }
};
