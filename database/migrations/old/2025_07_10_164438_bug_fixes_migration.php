<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Для PostgreSQL потрібно явно вказати як конвертувати тип
        DB::statement('ALTER TABLE legal_companies ALTER COLUMN three_pl TYPE smallint USING three_pl::int');
        DB::statement('ALTER TABLE legal_companies ALTER COLUMN three_pl DROP NOT NULL');

        Schema::table('companies', function (Blueprint $table) {
            $table->uuid("creator_company_id")->nullable()->change();
        });

        Schema::table('address_details', function (Blueprint $table) {
            $table->string("building_number", 10)->nullable()->change();
        });

        // Для порожньої таблиці просто пересоздаємо стовпець
        Schema::table('file_loads', function (Blueprint $table) {
            $table->dropColumn('id');
        });

        Schema::table('file_loads', function (Blueprint $table) {
            $table->uuid('id')->primary();
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
