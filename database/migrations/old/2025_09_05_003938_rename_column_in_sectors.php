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
        Schema::table('sectors', function (Blueprint $table) {
            $table->renameColumn('warehouse_zone_id', 'zone_id');
        });

        Schema::table('sectors', function (Blueprint $table) {
        //    $table->dropForeign('sectors_zone_id_foreign');
            $table->foreign('zone_id')
                ->references('id')
                ->on('warehouse_zones')
                ->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sectors', function (Blueprint $table) {
            //
        });
    }
};
