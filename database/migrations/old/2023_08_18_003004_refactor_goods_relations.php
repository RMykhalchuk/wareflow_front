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
        Schema::table('barcodes', function (Blueprint $table) {
            $table->dropForeign(['goods_id']);
            $table->foreign('goods_id')->references('id')->on('goods')->cascadeOnDelete();
        });
        Schema::table('packages', function (Blueprint $table) {
            $table->dropForeign(['goods_id']);
            $table->foreign('goods_id')->references('id')->on('goods')->cascadeOnDelete();
        });

        Schema::table('goods', function (Blueprint $table) {
            $table->dropForeign(['manufacturer']);
            $table->dropColumn('manufacturer');
            $table->uuid('manufacturer_id');

            $table->foreign('manufacturer_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('barcodes', function (Blueprint $table) {
            $table->dropForeign(['goods_id']);
            $table->foreign('goods_id')->references('id')->on('goods');
        });
        Schema::table('packages', function (Blueprint $table) {
            $table->dropForeign(['goods_id']);
            $table->foreign('goods_id')->references('id')->on('goods');
        });
        Schema::table('goods', function (Blueprint $table) {
            $table->dropForeign(['manufacturer_id']);
            $table->dropColumn('manufacturer_id');
            $table->unsignedBigInteger('manufacturer');

            $table->foreign('manufacturer')->references('id')->on('companies');
        });
    }
};
