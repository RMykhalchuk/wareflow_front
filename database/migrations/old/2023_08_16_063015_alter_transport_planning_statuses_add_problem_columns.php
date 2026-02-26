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
            $table->string('cause_failure')->nullable();
            $table->string('culprit_of_failure')->nullable();
            $table->string('cost_of_fines')->nullable();
            $table->unsignedTinyInteger('type')->default(0);
            $table->unsignedBigInteger('address_id')->nullable()->change();
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
            $table->dropColumn('cause_failure');
            $table->dropColumn('culprit_of_failure');
            $table->dropColumn('cost_of_fines');
            $table->dropColumn('type');
            $table->unsignedBigInteger('address_id')->change();
        });
    }
};
