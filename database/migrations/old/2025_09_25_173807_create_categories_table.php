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
        Schema::create('categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 255);
            $table->boolean('active')->default(true);
            $table->uuid('parent_id')->nullable();
            $table->bigInteger('goods_category_id')->nullable();
            $table->bigInteger('workspace_id');
            $table->timestamps();

            $table->foreign('workspace_id')
                ->references('id')
                ->on('workspaces')
                ->nullOnDelete();

            $table->foreign('goods_category_id')
                ->references('id')
                ->on('_d_goods_categories')
                ->nullOnDelete();

            $table->index('parent_id');
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->foreign('parent_id')
                ->references('id')
                ->on('categories')
                ->nullOnDelete();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
