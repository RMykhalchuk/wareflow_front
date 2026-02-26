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
        Schema::table('users', function (Blueprint $table) {
            // Drop the 'key_pass_card' column
            $table->dropColumn('key_pass_card');

            $table->boolean('sex')->after('email')->nullable();
        });

        Schema::dropIfExists('brigades');

        Schema::dropIfExists('units');
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
