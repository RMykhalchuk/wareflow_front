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
        Schema::create('service_by_documents', function (Blueprint $table) {
            $table->id();
            $table->uuid('service_id')->index()->nullable();
            $table->foreign('service_id')->references('id')->on('services');
            $table->uuid('document_id')->index()->nullable();
            $table->foreign('document_id')->references('id')->on('documents');
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
        Schema::dropIfExists('service_by_documents');
    }
};
