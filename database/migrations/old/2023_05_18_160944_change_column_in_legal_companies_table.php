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
        Schema::table('legal_companies', function (Blueprint $table) {
            $table->dropForeign(['legal_address_id']);
            $table->foreign('legal_address_id')->references('id')
                ->on('address_details')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('legal_companies', function (Blueprint $table) {
            //
        });
    }
};
