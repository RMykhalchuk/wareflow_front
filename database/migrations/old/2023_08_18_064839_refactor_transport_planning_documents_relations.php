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
        Schema::rename('transport_planning_documents', 'transport_planing_documents');

        Schema::table('transport_planing_documents', function (Blueprint $table) {
            $table->dropForeign(['document_id']);
        });
        Schema::rename('transport_planing_documents', 'transport_planning_documents');

        Schema::table('transport_planning_documents', function (Blueprint $table) {
            $table->foreign('document_id')->references('id')->on('documents')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transport_planning_documents', function (Blueprint $table) {
            $table->dropForeign(['document_id']);
            $table->foreign('document_id')->references('id')->on('documents');
        });
    }
};
