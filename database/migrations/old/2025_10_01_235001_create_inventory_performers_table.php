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
        Schema::create('inventory_performers', function (Blueprint $table) {
            $table->uuid('inventory_id');
            $table->uuid('user_id');
            $table->timestamps();

            $table->primary(['inventory_id', 'user_id']);

            $table->foreign('inventory_id')
                ->references('id')->on('inventories')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_performers');
    }
};
