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
        Schema::table('leftovers', function (Blueprint $table) {
            $table->dropColumn('expiration_date');
            $table->integer('expiration_term')->default(30)->after('manufacture_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('goods', function (Blueprint $table) {
            $table->dropColumn('expiration_term');
            $table->json('expiration_date')->nullable()->after('manufacture_date');
        });
    }
};
