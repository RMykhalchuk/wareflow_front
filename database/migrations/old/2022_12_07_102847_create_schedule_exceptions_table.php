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
        Schema::create('schedule_exceptions', function (Blueprint $table) {
            $table->id();
            $table->date('date_from');
            $table->date('date_to')->nullable();
            $table->unsignedBigInteger('type_id');
            $table->foreign('type_id')->references('id')->on('exception_types');
            $table->string('work_from', 5)->nullable();
            $table->string('work_to', 5)->nullable();
            $table->string('break_from', 5)->nullable();
            $table->string('break_to', 5)->nullable();
            $table->uuid('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->uuid('warehouse_id')->nullable();
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
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
        Schema::dropIfExists('schedule_exceptions');
    }
};
