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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('weekday', 10);
            $table->boolean('is_day_off')->nullable();
            $table->string('start_at', 5)->nullable();
            $table->string('end_at', 5)->nullable();
            $table->string('break_start_at', 5)->nullable();
            $table->string('break_end_at', 5)->nullable();
            $table->uuid('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->uuid('warehouse_id')->nullable();
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
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
        Schema::dropIfExists('schedules');
        Schema::enableForeignKeyConstraints();
    }
};
