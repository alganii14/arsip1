<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('arsip_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('active'); // active, inactive, destroyed
            $table->text('notes')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jres');
    }
};