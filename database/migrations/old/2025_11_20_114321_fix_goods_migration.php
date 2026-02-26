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
        Schema::table('goods', function (Blueprint $table) {
            $table->float('temp_from')->nullable()->change();
            $table->float('temp_to')->nullable()->change();
            $table->float('humidity_from')->nullable()->change();
            $table->float('humidity_to')->nullable()->change();
            $table->float('dustiness_from')->nullable()->change();
            $table->float('dustiness_to')->nullable()->change();
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
