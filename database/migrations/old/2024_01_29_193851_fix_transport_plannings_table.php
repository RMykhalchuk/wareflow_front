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
        Schema::table('transport_plannings', function (Blueprint $table) {
            $table->uuid('company_carrier_id')->index()->nullable()->change();
            $table->uuid('transport_id')->index()->nullable()->change();
            $table->uuid('additional_equipment_id')->index()->nullable()->change();
            $table->uuid('driver_id')->index()->nullable()->change();
            $table->double('price')->default(0)->nullable()->change();
            $table->boolean('with_pdv')->default(0)->nullable()->change();
            $table->text('comment')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
