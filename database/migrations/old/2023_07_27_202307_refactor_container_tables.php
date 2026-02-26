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
        Schema::table('containers', function (Blueprint $table) {
            $table->dropColumn('draft');
            $table->boolean('is_draft')->default(0);
        });
        Schema::table('container_types', function (Blueprint $table) {
            $table->string('key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('containers', function (Blueprint $table) {
            $table->dropColumn('is_draft');
            $table->boolean('draft')->default(0);
        });
        Schema::table('container_types', function (Blueprint $table) {
            $table->dropColumn(['key']);
        });
    }
};
