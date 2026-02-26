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
        Schema::create('goods_kit_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('goods_parent_id');
            $table->uuid('goods_id');
            $table->uuid('package_id');
            $table->integer('quantity');

            $table->foreign('goods_parent_id')->references('id')->on('goods')->onDelete('cascade');
            $table->foreign('goods_id')->references('id')->on('goods')->onDelete('cascade');
            $table->foreign('package_id')->references('id')->on('packages');

            $table->timestamps();
        });

        Schema::table('goods', function (Blueprint $table) {
            $table->boolean('is_kit')
                ->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('goods', function (Blueprint $table) {
            $table->dropColumn('is_kit');
        });

        Schema::dropIfExists('goods_kits');
    }
};
