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
        Schema::dropIfExists('leftover_to_container_registers');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leftover_to_container_registers', function (Blueprint $table) {
            //
        });
    }
};
