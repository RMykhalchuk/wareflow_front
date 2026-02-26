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
        Schema::create('document_relations', function (Blueprint $table) {
            $table->id();
            $table->uuid('document_id')->index();
            $table->uuid('related_id')->index();
            $table->foreign('document_id')->references('id')->on('documents');
            $table->foreign('related_id')->references('id')->on('documents');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('document_relations');
    }
};
