<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('goods', function (Blueprint $table) {
            $table->dropColumn('categories_id');
        });

        Schema::table('goods', function (Blueprint $table) {
            $table->dropColumn('category_id');
        });

        Schema::table('goods', function (Blueprint $table) {
            $table->foreignUuid('category_id')
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
        if (Schema::hasColumn('goods', 'category_id')) {
            Schema::table('goods', function (Blueprint $table) {
                try { $table->dropForeign(['category_id']); } catch (\Throwable $e) {}
                $table->dropColumn('category_id');
            });
        }

        Schema::table('goods', function (Blueprint $table) {
            $table->foreignUuid('categories_id')
                ->nullable()
                ->constrained('categories')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }
};
