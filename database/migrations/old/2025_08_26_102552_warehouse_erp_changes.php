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
        Schema::dropIfExists('warehouse_to_erp');

        Schema::dropIfExists('_d_warehouse_erp');

        Schema::create('warehouses_erp', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('local_id')->default(1);

            $table->string('name',100);

            $table->string('id_erp', 100);

            $table->uuid('creator_company_id')->index();
            $table->foreign('creator_company_id')->references('id')->on('companies');

            $table->softDeletes();
            $table->timestamps();

        });

        Schema::table('warehouses', function (Blueprint $table) {
            $table->dropColumn(['coordinates','addition_to_address']);
            $table->uuid('warehouses_erp_id')->nullable();
            $table->foreign('warehouses_erp_id')->references('id')->on('warehouses_erp');
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
