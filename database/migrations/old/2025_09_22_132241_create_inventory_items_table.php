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
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('qty')->nullable();
            $table->integer('real_qty')->nullable();
            $table->timestamps();

            $table->foreignUuid('creator_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignUuid('inventory_id')
                ->constrained('inventories')
                ->cascadeOnDelete();
            $table->foreignUuid('leftover_id')
                ->constrained('leftovers')
                ->cascadeOnDelete();

            $table->index(['inventory_id', 'leftover_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};
