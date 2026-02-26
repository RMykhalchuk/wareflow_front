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
        DB::statement('CREATE EXTENSION IF NOT EXISTS "pgcrypto";');

        Schema::create('inventories', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->bigIncrements('local_id');
            $table->boolean('show_leftovers')->default(false);
            $table->boolean('restrict_goods_movement')->default(false);
            $table->integer('process_cell')->default(0);
            $table->integer('status')->default(1);

            $table->string('type', 16)->index();

            $table->uuid('creator_id')->nullable()->index();
            $table->uuid('performer_id')->nullable()->index();
            $table->uuid('warehouse_id')->index();
            $table->uuid('warehouse_erp_id')->nullable()->index();

            $table->uuid('zone_id')->nullable()->index();
            $table->uuid('sector_id')->nullable()->index();
            $table->uuid('row_id')->nullable()->index();

            $table->bigInteger('cell_id')->nullable()->index();

            $table->bigInteger('category_subcategory')->nullable();

            $table->uuid('manufacturer_id')->nullable()->index();
            $table->uuid('supplier_id')->nullable()->index();
            $table->uuid('nomenclature_id')->nullable()->index();

            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->text('comment')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('creator_id')->references('id')->on('users');
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            $table->foreign('warehouse_erp_id')->references('id')->on('warehouses_erp')
                ->nullOnDelete();
            $table->foreign('zone_id')->references('id')->on('warehouse_zones')
                ->nullOnDelete();
            $table->foreign('cell_id')->references('id')->on('cells')
                ->nullOnDelete();
            $table->foreign('sector_id')->references('id')->on('sectors')
                ->nullOnDelete();
            $table->foreign('row_id')->references('id')->on('rows')
                ->nullOnDelete();
            $table->foreign('category_subcategory')->references('id')->on('_d_goods_categories')
                ->nullOnDelete();

            $table->foreign('manufacturer_id')->references('id')->on('companies')
                ->nullOnDelete();
            $table->foreign('performer_id')->references('id')->on('companies')
                ->nullOnDelete();
            $table->foreign('supplier_id')->references('id')->on('companies')
                ->nullOnDelete();
            $table->foreign('nomenclature_id')->references('id')->on('goods')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
