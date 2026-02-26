<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pallets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('local_id')->default(1);

            $table->string('product', 20);
            $table->uuid('warehouse_id');
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            $table->unsignedBigInteger('cell_id');
            $table->foreign('cell_id')->references('id')->on('cells');
            $table->integer('consigment');
            $table->integer('amount');
            $table->integer('series');
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pallets');
    }
};
