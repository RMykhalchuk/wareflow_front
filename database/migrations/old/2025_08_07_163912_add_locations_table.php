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
        Schema::create('locations', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->unsignedBigInteger('local_id')->default(1);
            $table->string('name',100);

            $table->uuid('company_id')->index();
            $table->foreign('company_id')->references('id')->on('companies');

            $table->unsignedBigInteger('country_id');
            $table->foreign('country_id')->references('id')->on('_d_countries');

            $table->unsignedBigInteger('settlement_id');
            $table->foreign('settlement_id')->references('id')->on('_d_settlements');

            $table->json('street_info');

            $table->json('coordinates');

            $table->uuid('creator_company_id')->index();
            $table->foreign('creator_company_id')->references('id')->on('companies');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('warehouses', function (Blueprint $table) {
            $table->dropForeign(['address_id']);
            $table->dropColumn('address_id');
            $table->uuid('location_id')->index()->nullable()->after('type_id');
            $table->foreign('location_id')->references('id')->on('locations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
