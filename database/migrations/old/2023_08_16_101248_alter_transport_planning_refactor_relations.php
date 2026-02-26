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
        Schema::table('transport_planning_to_statuses', function (Blueprint $table) {
            $table->dropForeign(['transport_planning_id']);
        });
        Schema::table('transport_planning_to_statuses', function (Blueprint $table) {
            $table->foreign('transport_planning_id')->references('id')->on('transport_plannings')->onDelete('CASCADE');
        });
        Schema::rename('transport_planning_documents', 'transport_planing_documents');

        Schema::table('transport_planing_documents', function (Blueprint $table) {
            $table->dropForeign(['transport_planing_id']);
        });
        Schema::rename('transport_planing_documents', 'transport_planning_documents');

        Schema::table('transport_planning_documents', function (Blueprint $table) {
            $table->foreign('transport_planing_id')->references('id')->on('transport_plannings')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transport_planning_to_statuses', function (Blueprint $table) {
            $table->dropForeign(['transport_planning_id']);
            $table->foreign('transport_planning_id')->references('id')->on('transport_plannings');
        });
        Schema::table('transport_planning_documents', function (Blueprint $table) {
            $table->dropForeign(['transport_planing_id']);
            $table->foreign('transport_planing_id')->references('id')->on('transport_plannings');
        });
    }
};
