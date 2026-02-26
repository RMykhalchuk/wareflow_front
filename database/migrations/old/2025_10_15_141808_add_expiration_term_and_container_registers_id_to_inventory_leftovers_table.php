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
        Schema::table('inventory_leftovers', function (Blueprint $table) {
            $table->integer('expiration_term')->nullable()->after('bb_date');
            $table->uuid('container_registers_id')->nullable()->after('expiration_term');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_leftovers', function (Blueprint $table) {
            $table->dropColumn(['expiration_term', 'container_registers_id']);
        });
    }
};
