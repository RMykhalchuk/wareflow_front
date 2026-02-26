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
        Schema::create('cells', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('row_id');
            $table->foreign('row_id')->references('id')->on('cell_statuses');
            $table->index('row_id');
            $table->bigInteger('code');
            $table->unsignedBigInteger('status_id');
            $table->foreign('status_id')->references('id')->on('cell_statuses');
            $table->boolean('is_active')->default(1);
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
        Schema::dropIfExists('cells');
    }
};
