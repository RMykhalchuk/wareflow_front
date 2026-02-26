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
        Schema::rename('transport_planing', 'transport_plannings');
        Schema::rename('transport_planing_documents', 'transport_planning_documents');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('transport_plannings', 'transport_planing');
        Schema::rename('transport_planning_documents', 'transport_planing_documents');
    }
};
