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

        Schema::table('container_registers', function (Blueprint $table) {
            $table->dropForeign(['cell_id']);
            $table->foreign('cell_id')->references('id')->on('cells');
        });

        Schema::dropIfExists("cell_allocations");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
