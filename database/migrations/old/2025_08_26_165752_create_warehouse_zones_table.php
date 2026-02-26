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
        Schema::create('warehouse_zones', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('local_id')->default(1);

            $table->string('name',10);

            $table->boolean('has_temp');
            $table->float('temp_from')->nullable();
            $table->float('temp_to')->nullable();

            $table->boolean('has_humidity');
            $table->float('humidity_from')->nullable();
            $table->float('humidity_to')->nullable();

            $table->uuid('warehouse_id')->index();
            $table->foreign('warehouse_id')->references('id')->on('warehouses');

            $table->uuid('creator_company_id')->index();
            $table->foreign('creator_company_id')->references('id')->on('companies');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('sectors', function (Blueprint $table) {
            $table->dropForeign(['warehouse_id']);
            $table->dropColumn('warehouse_id');
            $table->uuid('warehouse_zone_id')->index()->nullable();
            $table->foreign('warehouse_zone_id')->references('id')->on('warehouse_zones');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_zones');
    }
};
