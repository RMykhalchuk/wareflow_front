<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
            $table->bigInteger('ipn')->nullable()->change();
            $table->string('bank')->nullable()->change();
            $table->string('iban')->nullable()->change();
            $table->integer('mfo')->nullable()->change();
            $table->string('about')->nullable()->change();
            $table->string('currency')->nullable()->change();
        });

        Schema::table('physical_companies', function (Blueprint $table) {
            $table->string('patronymic')->nullable()->change();
        });

        Schema::table('legal_companies', function (Blueprint $table) {
            $table->bigInteger('edrpou')->nullable()->change();
            $table->bigInteger('legal_type_id')->nullable()->change();
            $table->bigInteger('legal_address_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('email')->nullable(false)->change();
            $table->bigInteger('ipn')->nullable(false)->change();
            $table->string('bank')->nullable(false)->change();
            $table->string('iban')->nullable(false)->change();
            $table->integer('mfo')->nullable(false)->change();
            $table->string('about')->nullable(false)->change();
            $table->string('currency')->nullable(false)->change();
        });

        Schema::table('physical_companies', function (Blueprint $table) {
            $table->string('patronymic')->nullable(false)->change();
        });

        Schema::table('legal_companies', function (Blueprint $table) {
            $table->bigInteger('edrpou')->nullable(false)->change();
            $table->bigInteger('legal_type_id')->nullable(false)->change();
            $table->bigInteger('legal_address_id')->nullable(false)->change();
        });
    }
};
