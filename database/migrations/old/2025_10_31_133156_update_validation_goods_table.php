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
        Schema::table('goods', function (Blueprint $table) {
            $table->string('brand')->nullable()->change();
            $table->string('manufacturer')->nullable()->change();
            $table->json('expiration_date')->nullable()->change();
            $table->double('weight_netto')->nullable()->change();
            $table->double('weight_brutto')->nullable()->change();
            $table->double('height')->nullable()->change();
            $table->double('width')->nullable()->change();
            $table->double('length')->nullable()->change();
            $table->bigInteger('measurement_unit_id')->nullable()->change();
            $table->bigInteger('manufacturer_country_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('goods', function (Blueprint $table) {
            $table->string('brand')->nullable(false)->change();
            $table->string('manufacturer')->nullable(false)->change();
            $table->json('expiration_date')->nullable(false)->change();
            $table->double('weight_netto')->nullable(false)->change();
            $table->double('weight_brutto')->nullable(false)->change();
            $table->double('height')->nullable(false)->change();
            $table->double('width')->nullable(false)->change();
            $table->double('length')->nullable(false)->change();
            $table->bigInteger('measurement_unit_id')->nullable(false)->change();
            $table->bigInteger('manufacturer_country_id')->nullable(false)->change();
        });
    }
};
