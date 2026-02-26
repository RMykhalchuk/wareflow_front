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
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
        });

        Schema::table('settlements', function (Blueprint $table) {
            $table->string('name', 255)->change();
            $table->unsignedBigInteger('region_id')->nullable();
            $table->foreign('region_id')->references('id')->on('regions')->cascadeOnDelete();
            $table->dropColumn('key');
            $table->dropTimestamps();
        });

        Schema::table('streets', function (Blueprint $table) {
            $table->string('name', 100)->change();
            $table->dropColumn('key');
            $table->dropTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists('regions');
    }
};
