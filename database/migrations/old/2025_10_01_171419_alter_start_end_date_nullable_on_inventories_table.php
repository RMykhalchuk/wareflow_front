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
        Schema::table('inventories', function (Blueprint $table) {
            // Робимо обидва поля nullable (timestamp(0))
            $table->timestamp('start_date', 0)->nullable()->change();
            $table->timestamp('end_date', 0)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Щоб відкотити до NOT NULL без помилок — заповнимо null поточним часом
        DB::table('inventories')->whereNull('start_date')->update(['start_date' => now()]);
        DB::table('inventories')->whereNull('end_date')->update(['end_date' => now()]);

        Schema::table('inventories', function (Blueprint $table) {
            $table->timestamp('start_date', 0)->nullable(false)->change();
            $table->timestamp('end_date', 0)->nullable(false)->change();
        });
    }
};
