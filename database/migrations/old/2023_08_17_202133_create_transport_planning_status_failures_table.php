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
            $table->dropColumn('cause_failure');
            $table->dropColumn('culprit_of_failure');
            $table->dropColumn('cost_of_fines');
            $table->dropColumn('type');
        });

        Schema::create('transport_planning_failure_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('key');
            $table->timestamps();
        });

        Schema::create('transport_planning_failures', function (Blueprint $table) {
            $table->id();
            $table->string('cause_failure')->nullable();
            $table->string('culprit_of_failure')->nullable();
            $table->string('cost_of_fines')->nullable();
            $table->unsignedBigInteger('type_id')->nullable();
            $table->unsignedBigInteger('status_id');
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->foreign('type_id')->references('id')->on('transport_planning_failure_types')->nullOnDelete();
            $table->foreign('status_id')->references('id')->on('transport_planning_to_statuses')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transport_planning_failure_types');

        Schema::table('transport_planning_status_failures', function (Blueprint $table) {
            $table->dropForeign(['type_id']);
            $table->dropForeign(['status_id']);
        });

        Schema::dropIfExists('transport_planning_status_failures');

        Schema::table('transport_planning_to_statuses', function (Blueprint $table) {
            $table->string('cause_failure')->nullable();
            $table->string('culprit_of_failure')->nullable();
            $table->string('cost_of_fines')->nullable();
            $table->unsignedTinyInteger('type')->default(0);
        });
    }
};
