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
        Schema::create('sku_modifications', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->unsignedBigInteger('local_id')->default(1);

            $table->string('name', 11);
            $table->uuid('sku_id');
            $table->foreign('sku_id')->references('id')->on('sku');
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
        Schema::dropIfExists('sku_modifications');
    }
};
