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
        Schema::table('warehouse_zones', function (Blueprint $table) {
            $table->unsignedBigInteger('zone_type')
                ->nullable()
                ->after('name');

            $table->unsignedBigInteger('zone_subtype')
                ->nullable()
                ->after('zone_type');

            $table->foreign('zone_type')
                ->references('id')
                ->on('_d_zone_types')
                ->onDelete('set null');

            $table->foreign('zone_subtype')
                ->references('id')
                ->on('_d_zone_subtypes')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warehouse_zones', function (Blueprint $table) {
            $table->dropForeign(['zone_type']);
            $table->dropForeign(['zone_subtype']);

            $table->dropColumn(['zone_type', 'zone_subtype']);
        });
    }
};
