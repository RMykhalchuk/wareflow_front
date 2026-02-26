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
        $dictionariesArray = ['additional_equipment_brands', 'additional_equipment_models', 'adrs',
            'legal_types', 'streets','settlements', 'transport_brands', 'transport_models'];
        foreach ($dictionariesArray as $dictionary) {
            Schema::table($dictionary, function (Blueprint $table) {
                $table->string('key', 40);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dictionaries', function (Blueprint $table) {
            //
        });
    }
};
