<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('task_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('local_id')->nullable();
            $table->uuid('task_id')->nullable();
            $table->uuid('leftover_id')->nullable();
            $table->uuid('goods_id')->nullable();
            $table->json('data')->nullable(); // array type
            $table->string('package')->nullable();
            $table->string('container_id')->nullable();
            $table->decimal('main_unit_quantity', 15, 3)->nullable();
            $table->decimal('package_quantity', 15, 3)->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();

            // Indexes
            $table->index('local_id');
            $table->index('task_id');
            $table->index('leftover_id');
            $table->index('goods_id');
            $table->index('container_id');

            // Foreign keys
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');

            $table->foreign('leftover_id')->references('id')->on('leftovers');
            $table->foreign('goods_id')->references('id')->on('goods');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_items');
    }
};
