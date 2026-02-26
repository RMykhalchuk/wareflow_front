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
        Schema::create('goods', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('local_id')->default(1);

            $table->string('name');
            $table->uuid('company_id');
            $table->timestamp('party')->useCurrent();
            $table->unsignedBigInteger('sku_category_id');
            $table->uuid('manufacturer');
            $table->unsignedBigInteger('manufacturer_country_id');
            $table->unsignedBigInteger('adr_id');
            $table->unsignedBigInteger('measurement_unit_id');
            $table->text('comment')->nullable();

            $table->double('weight_netto');
            $table->double('weight_brutto');

            $table->double('temp_from');
            $table->double('temp_to');

            $table->double('height');
            $table->double('width');
            $table->double('depth');

            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('sku_category_id')->references('id')->on('sku_categories');
            $table->foreign('manufacturer')->references('id')->on('companies');
            $table->foreign('manufacturer_country_id')->references('id')->on('countries');
            $table->foreign('adr_id')->references('id')->on('adrs');
            $table->foreign('measurement_unit_id')->references('id')->on('measurement_units');

            $table->timestamps();
        });

        Schema::table('barcodes', function (Blueprint $table) {
            $table->dropForeign(['sku_id']);
            $table->dropColumn('sku_id');

            $table->uuid('goods_id');

            $table->foreign('goods_id')->references('id')->on('goods');
        });

        Schema::table('packages', function (Blueprint $table) {
            $table->dropForeign(['sku_id']);
            $table->dropColumn('sku_id');

            $table->uuid('goods_id');

            $table->foreign('goods_id')->references('id')->on('goods');
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
            $table->dropColumn('goods_id');

            $table->unsignedBigInteger('sku_id');
            $table->foreign('sku_id')->references('id')->on('sku');
        });

        Schema::table('packages', function (Blueprint $table) {
            $table->dropForeign(['goods_id']);
            $table->dropColumn('goods_id');

            $table->unsignedBigInteger('sku_id');
            $table->foreign('sku_id')->references('id')->on('sku');
        });

        Schema::table('goods', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropForeign(['sku_category_id']);
            $table->dropForeign(['manufacturer']);
            $table->dropForeign(['manufacturer_country_id']);
            $table->dropForeign(['adr_id']);
            $table->dropForeign(['measurement_unit_id']);
        });

        Schema::dropIfExists('goods');
    }
};
