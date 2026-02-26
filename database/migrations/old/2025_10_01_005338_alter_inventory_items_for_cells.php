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
        DB::statement('ALTER TABLE inventory_items ALTER COLUMN leftover_id DROP NOT NULL;');

        Schema::table('inventory_items', function (Blueprint $table) {
            if (!Schema::hasColumn('inventory_items', 'cell_id')) {
                $table->uuid('cell_id')->nullable()->after('leftover_id');
                $table->foreign('cell_id')->references('id')->on('cells')->cascadeOnDelete();
                $table->index('cell_id', 'inventory_items_cell_id_index');
            }

            // На всяк випадок: якщо потрібні індекси
            if (!Schema::hasColumn('inventory_items', 'creator_id')) {
                // у тебе вже є creator_id NOT NULL; пропусти якщо існує
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_items', function (Blueprint $table) {
            if (Schema::hasColumn('inventory_items', 'cell_id')) {
                $table->dropForeign(['cell_id']);
                $table->dropIndex('inventory_items_cell_id_index');
                $table->dropColumn('cell_id');
            }
        });
    }
};
