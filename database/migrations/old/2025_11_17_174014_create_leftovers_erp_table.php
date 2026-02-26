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
        Schema::create('leftovers_erp', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('warehouse_erp_id')->index();
            $table->uuid('goods_erp_id')->index();

            $table->string('batch', 50)->nullable();
            $table->decimal('quantity', 15, 3)->default(0);

            $table->uuid('creator_company_id')->index();

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['goods_erp_id', 'batch', 'warehouse_erp_id'], 'unique_leftover_key');

            $table->foreign('goods_erp_id')->references('id')->on('goods');
            $table->foreign('creator_company_id')->references('id')->on('companies');
            $table->foreign('warehouse_erp_id')->references('id')->on('warehouses_erp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leftovers_erp');
    }
};
