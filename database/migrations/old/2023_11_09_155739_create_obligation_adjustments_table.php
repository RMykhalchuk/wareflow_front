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
        Schema::create('obligation_adjustments', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Додаткове поле для локального автоінкременту в межах workspace
            $table->unsignedBigInteger('local_id')->default(1);

            $table->uuid('invoice_obligation_id')->index();
            $table->unsignedTinyInteger('type_id');
            $table->unsignedBigInteger('failure_id');
            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('address_id');
            $table->double('sum');
            $table->text('comment')->nullable();

            $table->foreign('invoice_obligation_id')->references('id')->on('invoice_documents');
            $table->foreign('failure_id')->references('id')->on('transport_planning_failure_types');
            $table->foreign('status_id')->references('id')->on('transport_planning_statuses');
            $table->foreign('address_id')->references('id')->on('address_details');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('obligation_adjustments', function (Blueprint $table) {
            $table->dropForeign(['invoice_obligation_id']);
            $table->dropForeign(['failure_id']);
            $table->dropForeign(['status_id']);
            $table->dropForeign(['address_id']);
        });

        Schema::dropIfExists('obligation_adjustments');
    }
};
