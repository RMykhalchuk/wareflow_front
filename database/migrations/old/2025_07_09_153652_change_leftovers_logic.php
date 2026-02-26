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
        // Використовуємо CASCADE для видалення таблиць з усіма залежностями
        DB::statement('DROP TABLE IF EXISTS leftovers CASCADE');
        DB::statement('DROP TABLE IF EXISTS cells CASCADE');

        Schema::create('cells', function (Blueprint $table) {
            $table->id();

            $table->string('code', 50);
            $table->float('height');
            $table->float('width');
            $table->float('deep');
            $table->float('max_weight');
            $table->integer('type');

            $table->unsignedBigInteger("status_id")->index();
            $table->foreign('status_id')->references('id')->on('_d_cell_statuses');
        });

        Schema::create('cell_allocations', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger("cell_id")->index();
            $table->uuid("sector_id")->index();
            $table->unsignedBigInteger("row_id")->index();

            $table->integer('rack');
            $table->integer('floor');
            $table->integer('column');

            $table->foreign('cell_id')->references('id')->on('cells');
            $table->foreign('sector_id')->references('id')->on('sectors');
            $table->foreign('row_id')->references('id')->on('rows');
        });

        Schema::create('leftovers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('local_id')->default(1);

            $table->uuid("goods_id")->index();
            $table->unsignedBigInteger("cell_id")->index();
            $table->uuid("container_id")->index();
            $table->unsignedBigInteger("package_id")->index();

            $table->integer("quantity");

            $table->string('batch', 50)->fulltext();

            $table->date("manufacture_date");
            $table->date("expiration_date");
            $table->date("bb_date");

            $table->foreign('goods_id')->references('id')->on('goods');
            $table->foreign('cell_id')->references('id')->on('cells');
            $table->foreign('container_id')->references('id')->on('containers');
            $table->foreign('package_id')->references('id')->on('packages');
        });

        $this->recreateForeignKeys();
    }

    private function recreateForeignKeys(): void
    {
        Schema::table('pallets', function (Blueprint $table) {
            $table->foreign('cell_id')->references('id')->on('cells');
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
