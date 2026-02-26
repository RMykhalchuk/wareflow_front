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
        Schema::create('inventory_manual_leftover_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignUuid('leftover_id')
                ->constrained('leftovers')
                ->cascadeOnDelete();
            $table->decimal('quantity_before', 18, 3)->nullable();
            $table->decimal('quantity_after', 18, 3)->nullable();
            $table->string('area', 64)->nullable();
            $table->uuid('executor_id')->nullable()->index();
            $table->timestamp('created_at')->useCurrent()->index();
            $table->index(['leftover_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_manual_leftover_logs');
    }
};
