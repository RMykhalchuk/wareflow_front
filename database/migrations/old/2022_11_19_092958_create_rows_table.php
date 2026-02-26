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
        Schema::disableForeignKeyConstraints();
        Schema::create('rows', function (Blueprint $table) {
            $table->id();
            $table->uuid('sector_id');
            $table->foreign('sector_id')->references('id')->on('sectors')->onDelete('cascade');
            $table->index('sector_id');
            $table->integer('floors_number');
            $table->integer('racks_number');
            $table->integer('rack_cells_number');
            //    $table->foreign('numeration_id')->constrained();
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('rows');
    }
};
