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

        Schema::rename('transport_kinds', 'transport_categories');
        Schema::table('transports', function (Blueprint $table) {
            $table->dropForeign(['kind_id']);
         //   $table->dropIndex('transports_kind_id_foreign');
            $table->dropColumn('kind_id');
            $table->float('carrying_capacity')->nullable();
            $table->boolean('hydroboard')->nullable();
            $table->unsignedBigInteger('category_id')->index();
            $table->foreign('category_id')->references('id')->on('transport_categories');
        });

        Schema::table('additional_equipment', function (Blueprint $table) {
            $table->float('carrying_capacity')->nullable();
            $table->boolean('hydroboard')->nullable();
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
