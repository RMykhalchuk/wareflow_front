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
        Schema::create('pallet_registers', function (Blueprint $table) {
            $table->id();
            $table->integer('code');
            $table->integer('supply_code');
            $table->uuid('pallet_id');
            $table->foreign('pallet_id')->references('id')->on('pallets');
            $table->boolean('is_active');
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
        Schema::dropIfExists('pallet_registers');
    }
};
