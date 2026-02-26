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
            $table->foreignUuid('update_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_items', function (Blueprint $table) {
            // Drop FK + column safely across Laravel versions
            if (method_exists($table, 'dropConstrainedForeignIdIfExists')) {
                $table->dropConstrainedForeignIdIfExists('update_id');
            } else {
                $table->dropForeign(['update_id']);
                $table->dropColumn('update_id');
            }
        });
    }
};
