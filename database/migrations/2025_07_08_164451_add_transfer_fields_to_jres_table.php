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
            $table->timestamp('transferred_at')->nullable();
            $table->unsignedBigInteger('transferred_by')->nullable();
            $table->foreign('transferred_by')->references('id')->on('users');
            $table->text('transfer_notes')->nullable();
            $table->string('transfer_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jres', function (Blueprint $table) {
            $table->dropForeign(['transferred_by']);
            $table->dropColumn(['transferred_at', 'transferred_by', 'transfer_notes', 'transfer_status']);
        });
    }
};
