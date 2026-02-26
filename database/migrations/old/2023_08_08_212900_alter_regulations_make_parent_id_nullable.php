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
        Schema::table('regulations', function (Blueprint $table) {
            $table->dropColumn('parent_id');
        });
        Schema::table('regulations', function (Blueprint $table) {
            $table->uuid('parent_id')->index()->nullable();

            $table->foreign('parent_id')->references('id')->on('regulations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('regulations', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
        });
        Schema::table('regulations', function (Blueprint $table) {
            $table->dropColumn('parent_id');
            $table->integer('parent_id');
        });
    }
};
