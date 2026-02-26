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
        Schema::create('warehouse_to_erp', function (Blueprint $table) {
            $table->id();
            $table->uuid('warehouse_id')->index()->nullable();
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            $table->unsignedBigInteger('erp_id')->index()->nullable();
            $table->foreign('erp_id')->references('id')->on('warehouse_erp');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouse_to_erp');
    }
};
