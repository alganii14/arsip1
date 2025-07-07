<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('peminjaman_arsips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('arsip_id')->constrained('arsips')->onDelete('cascade');
            $table->string('peminjam');
            $table->string('jabatan')->nullable();
            $table->string('departemen')->nullable();
            $table->string('kontak');
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali')->nullable();
            $table->date('batas_waktu');
            $table->enum('status', ['dipinjam', 'dikembalikan', 'terlambat'])->default('dipinjam');
            $table->text('tujuan_peminjaman')->nullable();
            $table->text('catatan')->nullable();
            $table->string('petugas_peminjaman');
            $table->string('petugas_pengembalian')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('peminjaman_arsips');
    }
};
