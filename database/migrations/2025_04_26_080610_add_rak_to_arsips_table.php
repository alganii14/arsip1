<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('arsips', function (Blueprint $table) {
            $table->string('rak')->nullable()->after('keterangan');
        });
    }

    public function down()
    {
        Schema::table('arsips', function (Blueprint $table) {
            $table->dropColumn('rak');
        });
    }
};