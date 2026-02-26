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
        Schema::table('transports', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('additional_equipment', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('containers', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('goods', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('services', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('transport_plannings', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('leftovers', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('contracts', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('registers', function (Blueprint $table) {
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

    }
};
