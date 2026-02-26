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
            $table->dropColumn('category_subcategory');
        });

        Schema::table('inventories', function (Blueprint $table) {
            $table->foreignUuid('category_subcategory')
                ->nullable()
                ->constrained('categories')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('inventories', 'category_subcategory')) {
            Schema::table('inventories', function (Blueprint $table) {
                try { $table->dropForeign(['category_subcategory']); } catch (\Throwable $e) {}
                $table->dropColumn('category_subcategory');
            });
        }

        Schema::table('goods', function (Blueprint $table) {
            $table->foreignUuid('category_subcategory')
                ->nullable()
                ->constrained('categories')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }
};
