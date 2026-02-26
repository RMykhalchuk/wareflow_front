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
        Schema::disableForeignKeyConstraints();
        Schema::create('additional_equipment', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('local_id')->default(1);

            $table->unsignedBigInteger('brand_id');
            $table->foreign('brand_id')->references('id')->on('additional_equipment_brands');
            $table->unsignedBigInteger('model_id');
            $table->foreign('model_id')->references('id')->on('additional_equipment_models');
            $table->unsignedBigInteger('type_id');
            $table->foreign('type_id')->references('id')->on('transport_types');

            $table->string('license_plate', 8);

            $table->json('download_methods')->nullable();
            $table->float('length');
            $table->float('width');
            $table->float('height');
            $table->float('volume');
            $table->float('weight');
            $table->float('capacity_eu');
            $table->float('capacity_am');
            $table->unsignedBigInteger('adr_id')->nullable();
            $table->foreign('adr_id')->references('id')->on('adrs');
            $table->integer('manufacture_year');
            $table->unsignedBigInteger('country_id');
            $table->foreign('country_id')->references('id')->on('countries');

            $table->uuid('company_id');
            $table->foreign('company_id')->references('id')->on('companies');

            $table->uuid('transport_id');
            $table->index('transport_id');
            $table->string('img_type', 5)->nullable();

            $table->timestamps();
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('additional_equipment');
        Schema::enableForeignKeyConstraints();
    }
};
