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
        Schema::create('address_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->foreign('country_id')->references('id')->on('countries');

            $table->unsignedBigInteger('settlement_id')->nullable();
            $table->foreign('settlement_id')->references('id')->on('settlements');

            $table->unsignedBigInteger('street_id')->nullable();
            $table->foreign('street_id')->references('id')->on('streets');

            $table->integer('building_number')->nullable();
            $table->string('apartment_number', 5)->nullable();
            $table->string('legal_address', 50)->nullable();
            $table->unsignedBigInteger('gln')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('address_details');
        Schema::enableForeignKeyConstraints();
    }
};
