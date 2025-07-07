<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('arsips', function (Blueprint $table) {
            $table->integer('retention_years')->default(5)->after('retention_date');
        });
    }

    public function down()
    {
        Schema::table('arsips', function (Blueprint $table) {
            $table->dropColumn('retention_years');
        });
    }
};
