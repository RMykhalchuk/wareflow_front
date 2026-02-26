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
        Schema::table('inventory_items', function (Blueprint $table) {
            try {
                $table->dropForeign(['leftover_id']);
            } catch (\Throwable $e) {
            }

            try {
                $table->dropIndex('inventory_items_inventory_id_leftover_id_index');
            } catch (\Throwable $e) {
            }

            if (Schema::hasColumn('inventory_items', 'leftover_id')) {
                $table->dropColumn('leftover_id');
            }
        });

        Schema::table('inventory_items', function (Blueprint $table) {
            try {
                $table->unique(['inventory_id', 'cell_id'], 'inventory_items_inventory_id_cell_id_unique');
            } catch (\Throwable $e) {
            }
        });

        Schema::create('inventory_leftovers', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('inventory_item_id');

            $table->uuid('leftover_id')->nullable();

            $table->uuid('goods_id');
            $table->uuid('package_id');
            $table->integer('quantity');
            $table->string('batch', 50);
            $table->date('manufacture_date');
            $table->date('bb_date');

            $table->unsignedTinyInteger('source_type')->default(1);

            $table->uuid('creator_id');

            $table->timestamp('approved_at')->nullable();

            $table->timestamps();

            $table->foreign('inventory_item_id')
                ->references('id')->on('inventory_items')
                ->onDelete('cascade');

            $table->foreign('leftover_id')
                ->references('id')->on('leftovers')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('goods_id')
                ->references('id')->on('goods');

            $table->foreign('package_id')
                ->references('id')->on('packages');

            $table->foreign('creator_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->index(['inventory_item_id']);
            $table->unique(['inventory_item_id', 'leftover_id'], 'inventory_leftovers_item_leftover_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_leftovers');

        Schema::table('inventory_items', function (Blueprint $table) {
            if (!Schema::hasColumn('inventory_items', 'leftover_id')) {
                $table->uuid('leftover_id')->nullable();
            }
        });

        Schema::table('inventory_items', function (Blueprint $table) {
            try {
                $table->foreign('leftover_id')
                    ->references('id')->on('leftovers')
                    ->onDelete('cascade');
            } catch (\Throwable $e) {
            }

            try {
                $table->index(['inventory_id', 'leftover_id'], 'inventory_items_inventory_id_leftover_id_index');
            } catch (\Throwable $e) {
            }

            try {
                $table->dropUnique('inventory_items_inventory_id_cell_id_unique');
            } catch (\Throwable $e) {
            }
        });
    }
};
