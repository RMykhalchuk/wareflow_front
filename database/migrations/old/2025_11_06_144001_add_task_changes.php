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
        DB::table('tasks')->truncate();

        Schema::table('tasks', function (Blueprint $table) {
            $table->uuid('cell_id');

            $table->foreign('cell_id')->references('id')->on('cells');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
