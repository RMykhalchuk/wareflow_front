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
        Schema::table('inventory_manual_leftover_logs', function (Blueprint $table) {
            $table->uuid('group_id')->nullable()->index()->after('executor_id');
            $table->string('group_type', 64)->nullable()->after('group_id');
            $table->index(['group_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_manual_leftover_logs', function (Blueprint $table) {
            $table->dropIndex(['group_id', 'created_at']);
            $table->dropColumn(['group_id', 'group_type']);
        });
    }
};
