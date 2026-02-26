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
        Schema::table('companies', function (Blueprint $table) {
            $table->removeColumn('three_pl');
        });

        Schema::table('leftovers', function (Blueprint $table) {
            $table->uuid('goods_id')->index()->nullable()->change();
            $table->uuid('container_id')->index()->nullable();
            $table->foreign('container_id')->references('id')->on('containers');

            $table->string('leftovers_type', 25)->nullable();
        });

        Schema::table('containers', function (Blueprint $table) {
            $table->string('erp_id', 25);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
