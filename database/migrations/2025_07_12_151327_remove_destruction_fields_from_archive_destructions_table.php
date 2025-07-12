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
        Schema::table('archive_destructions', function (Blueprint $table) {
            if (Schema::hasColumn('archive_destructions', 'destruction_method')) {
                $table->dropColumn('destruction_method');
            }
            if (Schema::hasColumn('archive_destructions', 'destruction_location')) {
                $table->dropColumn('destruction_location');
            }
            if (Schema::hasColumn('archive_destructions', 'destruction_witnesses')) {
                $table->dropColumn('destruction_witnesses');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('archive_destructions', function (Blueprint $table) {
            $table->string('destruction_method')->after('destruction_notes');
            $table->string('destruction_location')->nullable()->after('destruction_method');
            $table->string('destruction_witnesses')->nullable()->after('destruction_location');
        });
    }
};
