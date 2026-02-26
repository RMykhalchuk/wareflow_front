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
        Schema::create('_d_zone_type_subtype', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('zone_type_id');
            $table->unsignedBigInteger('zone_subtype_id');

            $table
                ->foreign('zone_type_id')
                ->references('id')
                ->on('_d_zone_types')
                ->onDelete('cascade');

            $table
                ->foreign('zone_subtype_id')
                ->references('id')
                ->on('_d_zone_subtypes')
                ->onDelete('cascade');

            $table->unique(['zone_type_id', 'zone_subtype_id'], '_d_ztts_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_d_zone_type_subtype');
    }
};
