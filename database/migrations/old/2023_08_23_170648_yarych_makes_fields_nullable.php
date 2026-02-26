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
//        Schema::table('companies', function (Blueprint $table) {
//            $table->string('email', 50)->nullable()->change();
//            $table->unsignedBigInteger('status_id')->nullable()->change();
//            $table->unsignedBigInteger('ipn')->nullable()->change();
//            $table->string('bank', 50)->nullable()->change();
//            $table->string('iban', 29)->nullable()->change();
//            $table->integer('mfo')->nullable()->change();
//            $table->text('about')->nullable()->change();
//            $table->string('currency', 5)->nullable()->change();
//            $table->string('erp_id')->nullable();
//        });
//
//
//        Schema::table('legal_companies', function (Blueprint $table) {
//            $table->string('name', 255)->nullable()->change();
//            $table->unsignedBigInteger('edrpou')->nullable()->change();
//            $table->unsignedBigInteger('legal_type_id')->nullable()->change();
//            $table->unsignedBigInteger('legal_address_id')->nullable()->change();
//            $table->boolean('three_pl')->nullable()->change();
//        });
//
//
//        Schema::table('goods', function (Blueprint $table) {
//            $table->string('party', 40)->useCurrent()->nullable()->change();
//            $table->unsignedBigInteger('adr_id')->nullable()->change();
//            $table->text('comment')->nullable()->change();
//            $table->string('erp_id')->nullable();
//            $table->double('weight_netto')->nullable()->change();
//            $table->double('weight_brutto')->nullable()->change();
//
//            $table->double('temp_from')->nullable()->change();
//            $table->double('temp_to')->nullable()->change();
//
//            $table->double('height')->nullable()->change();
//            $table->double('width')->nullable()->change();
//            $table->double('depth')->nullable()->change();
//        });
//
//        Schema::table('packages', function (Blueprint $table) {
//            $table->double('weight')->nullable()->change();
//            $table->double('weight_netto')->nullable()->change();
//            $table->double('weight_brutto')->nullable()->change();
//        });
//
//        Schema::table('warehouses', function (Blueprint $table) {
//            $table->text('addition_to_address')->nullable();
//            $table->unsignedBigInteger('type_id')->nullable()->change();
//            $table->uuid('user_id')->nullable()->change();
//            $table->string('erp_id')->nullable();
//            $table->string('name', 255)->change();
//        });
//
//        Schema::table('address_details', function (Blueprint $table) {
//            $table->dropColumn(['legal_address', 'deleted_at']);
//            $table->string('building_number', 5)->change();
//        });
//
//        Schema::table('countries', function (Blueprint $table) {
//            $table->string('key', 20);
//        });
//
//        Schema::table('packages', function (Blueprint $table) {
//            $table->integer('number')->nullable()->change();
//        });
//
//        Schema::table('leftovers', function (Blueprint $table) {
//            $table->string('consignment', 40)->useCurrent()
//                ->nullable()->change();
//            $table->uuid('document_id')->nullable()->change();
//            $table->string('erp_id')->nullable();
//        });
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
