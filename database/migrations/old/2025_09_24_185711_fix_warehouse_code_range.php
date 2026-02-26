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
        Schema::table('warehouse_zones', function (Blueprint $table) {
            $table->string('name',100)->change();
        });

        Schema::table('sectors', function (Blueprint $table) {
            $table->string('name',100)->change();
        });

        Schema::table('rows', function (Blueprint $table) {
            $table->string('name',100)->change();
        });

        Schema::table('cells', function (Blueprint $table) {
            $table->string('code',100)->change();
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
