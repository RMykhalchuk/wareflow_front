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
        Schema::create('registers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('local_id')->default(1);

            $table->time('time_arrival');
            $table->string('auto_name', 50);
            $table->string('licence_plate')->nullable();
            $table->smallInteger('mono_pallet')->default(0);
            $table->smallInteger('collect_pallet')->default(0);
            $table->unsignedBigInteger('download_method_id')->nullable();
            $table->foreign('download_method_id')->references('id')->on('transport_downloads');
            $table->unsignedBigInteger('download_zone_id')->nullable();
            $table->foreign('download_zone_id')->references('id')->on('download_zones');
            $table->uuid('storekeeper_id')->index()->nullable();
            $table->foreign('storekeeper_id')->references('id')->on('users');
            $table->uuid('manager_id')->index()->nullable();
            $table->foreign('manager_id')->references('id')->on('users');
            $table->unsignedBigInteger('status_id')->index()->nullable();
            $table->foreign('status_id')->references('id')->on('register_statuses');
            $table->timestamp('register')->nullable();
            $table->timestamp('entrance')->nullable();
            $table->timestamp('departure')->nullable();

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
        Schema::dropIfExists('registers');
    }
};
