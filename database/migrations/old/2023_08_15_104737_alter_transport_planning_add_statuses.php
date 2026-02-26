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
        Schema::create('transport_planning_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('key');
            $table->timestamps();
        });

        Schema::table('transport_planing', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::create('transport_planning_to_statuses', function (Blueprint $table) {
            $table->id();
            $table->uuid('transport_planning_id')->index();
            $table->unsignedBigInteger('status_id')->index();
            $table->unsignedBigInteger('address_id');
            $table->timestamp('date')->useCurrent();
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->foreign('transport_planning_id')->references('id')->on('transport_planing');
            $table->foreign('status_id')->references('id')->on('transport_planning_statuses');
            $table->foreign('address_id')->references('id')->on('address_details');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transport_planing', function (Blueprint $table) {
            $table->unsignedTinyInteger('status')->default(0);
        });

        Schema::table('transport_planning_to_statuses', function (Blueprint $table) {
            $table->dropForeign(['transport_planning_id']);
            $table->dropForeign(['status_id']);
            $table->dropForeign(['address_id']);
        });

        Schema::dropIfExists('transport_planning_to_statuses');

        Schema::dropIfExists('transport_planning_statuses');
    }
};
