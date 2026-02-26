<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('_d_zone_types', function (Blueprint $table) {
            $table->string('key', 64)->unique()->nullable()->after('id');
        });

        Schema::table('_d_zone_subtypes', function (Blueprint $table) {
            $table->string('key', 64)->unique()->nullable()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('_d_zone_types', function (Blueprint $table) {
            $table->dropColumn('key');
        });

        Schema::table('_d_zone_subtypes', function (Blueprint $table) {
            $table->dropColumn('key');
        });
    }
};