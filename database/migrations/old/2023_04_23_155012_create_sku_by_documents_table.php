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
        Schema::create('sku_by_documents', function (Blueprint $table) {
            $table->id();
            $table->uuid('sku_id')->index()->nullable();
            $table->foreign('sku_id')->references('id')->on('sku');
            $table->uuid('document_id')->index()->nullable();
            $table->foreign('document_id')->references('id')->on('documents');
            $table->integer('count');
            $table->json('data')->nullable();
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
        Schema::dropIfExists('sku_by_documents');
    }
};
