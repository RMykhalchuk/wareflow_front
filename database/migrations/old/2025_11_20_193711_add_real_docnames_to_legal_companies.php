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
        Schema::table('legal_companies', function (Blueprint $table) {
            $table->string('reg_docname')->nullable()->after('reg_doctype');
            $table->string('install_docname')->nullable()->after('install_doctype');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('legal_companies', function (Blueprint $table) {
            $table->dropColumn('reg_docname');
            $table->dropColumn('install_docname');
        });
    }
};
