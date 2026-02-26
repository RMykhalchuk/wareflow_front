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
        Schema::create('operational_costs', function (Blueprint $table) {
            $table->id();
            $table->uuid('company_id')->index()->nullable();
            $table->foreign('company_id')->references('id')->on('companies');
            $table->float('distance');
            $table->integer('additional_points_number');
            $table->smallInteger('trip_days')->default(0);
            $table->time('trip_time')->default('00:00:00');
            $table->integer('price');
            $table->smallInteger('currency');
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
        Schema::dropIfExists('operational_costs');
    }
};
