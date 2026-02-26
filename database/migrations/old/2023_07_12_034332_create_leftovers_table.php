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
        Schema::create('leftovers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('local_id')->default(1);

            $table->uuid('goods_id');
            $table->uuid('document_id');
            $table->double('count')->nullable();
            $table->json('data')->nullable();
            $table->timestamps();

            $table->foreign('goods_id')->references('id')->on('goods');
            $table->foreign('document_id')->references('id')->on('documents');
        });

        Schema::table('barcodes', function (Blueprint $table) {
            $table->uuid('user_id')->nullable();

            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('packages', function (Blueprint $table) {
            $table->double('weight')->default(0);
            $table->double('weight_netto')->default(0);
            $table->double('weight_brutto')->default(0);

            $table->double('height')->nullable();
            $table->double('width')->nullable();
            $table->double('depth')->nullable();
        });

        Schema::table('sku_by_documents', function (Blueprint $table) {
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
        Schema::table('sku_by_documents', function (Blueprint $table) {
            $table->dropForeign(['goods_id']);
            $table->dropColumn('goods_id');

            $table->unsignedBigInteger('sku_id');
            $table->foreign('sku_id')->references('id')->on('sku');
        });

        Schema::table('barcodes', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('weight');
            $table->dropColumn('weight_netto');
            $table->dropColumn('weight_brutto');

            $table->dropColumn('height');
            $table->dropColumn('width');
            $table->dropColumn('depth');
        });

        Schema::table('leftovers', function (Blueprint $table) {
            $table->dropForeign(['goods_id']);
            $table->dropForeign(['document_id']);
        });

        Schema::dropIfExists('leftovers');
    }
};
