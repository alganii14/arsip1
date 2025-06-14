<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('arsips', function (Blueprint $table) {
            $table->date('retention_date')->nullable()->after('tanggal_arsip');
            $table->boolean('is_archived_to_jre')->default(false)->after('file_type');
            $table->timestamp('archived_to_jre_at')->nullable()->after('is_archived_to_jre');
            $table->boolean('has_retention_notification')->default(false)->after('archived_to_jre_at');
        });
    }

    public function down()
    {
        Schema::table('arsips', function (Blueprint $table) {
            $table->dropColumn('retention_date');
            $table->dropColumn('is_archived_to_jre');
            $table->dropColumn('archived_to_jre_at');
            $table->dropColumn('has_retention_notification');
        });
    }
};