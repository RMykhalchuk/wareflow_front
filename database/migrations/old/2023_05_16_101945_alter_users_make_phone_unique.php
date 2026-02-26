<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->string('phone', 15)->unique()->nullable()->change();
            $table->string('email', 100)->nullable()->change();

            $table->string('name', 50)->nullable()->change();
            $table->string('surname', 50)->nullable()->change();
            $table->string('patronymic', 50)->nullable()->change();
            $table->date('birthday')->nullable()->change();

            $table->dropForeign(['position_id']);
            $table->unsignedBigInteger('position_id')->nullable()->change();
            $table->foreign('position_id')->references('id')->on('positions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function ($table) {
            $table->dropUnique(['phone']);
        });
    }
};
