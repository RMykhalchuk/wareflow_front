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
        Schema::create('sectors', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->unsignedBigInteger('local_id')->default(1);
            $table->uuid('warehouse_id');
            $table->unsignedBigInteger('storage_type_id');
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            $table->foreign('storage_type_id')->references('id')->on('storage_types');
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
        Schema::dropIfExists('sectors');
    }
};
