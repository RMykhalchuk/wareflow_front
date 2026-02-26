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
        Schema::create('inventory_goods', function (Blueprint $table) {
            $table->id();
            $table->uuid('inventory_id');
            $table->uuid('goods_id')->nullable();
            $table->timestamps();

            $table->foreign('inventory_id')
                ->references('id')
                ->on('inventories')
                ->cascadeOnDelete();

            $table->foreign('goods_id')
                ->references('id')
                ->on('goods')
                ->restrictOnDelete();

            $table->unique(['inventory_id', 'goods_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_goods');
    }
};
