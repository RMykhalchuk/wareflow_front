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
        Schema::create('packagings', function (Blueprint $table) {
            $table->id();
            $table->uuid('sku_id');
            $table->foreign('sku_id')->references('id')->on('sku');
            $table->unsignedBigInteger('type_id');
            $table->foreign('type_id')->references('id')->on('measurement_units');
            $table->integer('units_number');
            $table->string('bar_code', 100);
            $table->float('weight', 11);
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
        Schema::dropIfExists('packagings');
    }
};
