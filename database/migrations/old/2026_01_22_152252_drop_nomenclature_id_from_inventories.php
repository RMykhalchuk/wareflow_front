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
        Schema::table('inventories', function (Blueprint $table) {
            $table->dropForeign(['nomenclature_id']);
            $table->dropColumn('nomenclature_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->uuid('nomenclature_id')->nullable()->index();

            $table->foreign('nomenclature_id')
                ->references('id')
                ->on('goods')
                ->nullOnDelete();
        });
    }
};
