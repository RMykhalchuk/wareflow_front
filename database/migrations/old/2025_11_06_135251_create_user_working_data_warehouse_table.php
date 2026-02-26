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
        Schema::create('user_working_data_warehouse', function (Blueprint $table) {
            $table->uuid('user_working_data_id');
            $table->uuid('warehouse_id');
            $table->timestamps();

            $table->primary(['user_working_data_id', 'warehouse_id']);

            $table->foreign('user_working_data_id')
                ->references('id')->on('user_working_data')
                ->cascadeOnDelete();

            $table->foreign('warehouse_id')
                ->references('id')->on('warehouses')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_working_data_warehouse');
    }
};
