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
        Schema::table('_d_company_statuses', function (Blueprint $table) {
            $table->string('name', 256)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('_d_company_statuses', function (Blueprint $table) {
            $table->string('name', 30)->change();
        });
    }
};
