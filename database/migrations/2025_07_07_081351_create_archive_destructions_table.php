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
        Schema::create('archive_destructions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('arsip_id');
            $table->unsignedBigInteger('jre_id');
            $table->unsignedBigInteger('user_id');
            $table->text('destruction_notes');
            $table->timestamp('destroyed_at');
            $table->timestamps();

            // Add foreign key constraints only if tables exist
            $table->foreign('arsip_id')->references('id')->on('arsips')->onDelete('cascade');
            $table->foreign('jre_id')->references('id')->on('jres')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archive_destructions');
    }
};
