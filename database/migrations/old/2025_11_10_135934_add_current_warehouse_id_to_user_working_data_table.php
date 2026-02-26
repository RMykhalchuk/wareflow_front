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
        Schema::table('user_working_data', function (Blueprint $table) {
            $table->foreignUuid('current_warehouse_id')
                ->nullable()
                ->after('workspace_id')
                ->constrained('warehouses') // links to warehouses.id
                ->nullOnDelete();           // set NULL if warehouse is deleted
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_working_data', function (Blueprint $table) {
            $table->dropForeign(['current_warehouse_id']);
            $table->dropColumn('current_warehouse_id');
        });
    }
};
