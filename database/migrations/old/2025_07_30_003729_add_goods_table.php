<?php

use App\Enums\Containers\ContainerStatus;
use App\Enums\Goods\GoodsStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('DROP TABLE IF EXISTS goods CASCADE');
        // Drop and recreate 'goods'

        Schema::create('goods', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('local_id')->default(1);

            $table->string('name', 50);
            $table->string('brand', 50);
            $table->string('manufacturer', 50);
            $table->json('expiration_date');

            $table->tinyInteger('is_batch_accounting')->default(0);
            $table->tinyInteger('is_weight')->default(0);

            $table->float('weight_netto');
            $table->float('weight_brutto');
            $table->float('height');
            $table->float('width');
            $table->float('length');

            $table->integer('temp_from')->nullable();
            $table->integer('temp_to')->nullable();
            $table->integer('humidity_from')->nullable();
            $table->integer('humidity_to')->nullable();
            $table->integer('dustiness_from')->nullable();
            $table->integer('dustiness_to')->nullable();
            $table->enum("status_id", GoodsStatus::values())->default(GoodsStatus::ACTIVE->value)->index();
            $table->unsignedBigInteger('measurement_unit_id')->index();
            $table->foreign('measurement_unit_id')->references('id')->on('_d_measurement_units');

            $table->unsignedBigInteger('adr_id')->index();
            $table->foreign('adr_id')->references('id')->on('_d_adrs');

            $table->unsignedBigInteger('manufacturer_country_id');
            $table->foreign('manufacturer_country_id')->references('id')->on('_d_countries');

            $table->unsignedBigInteger('category_id')->index();
            $table->foreign('category_id')->references('id')->on('_d_goods_categories');

            $table->uuid('creator_company_id')->index();
            $table->foreign('creator_company_id')->references('id')->on('companies');

            $table->softDeletes();
            $table->timestamps();
        });

        // Update 'barcodes' table
        Schema::table('barcodes', function (Blueprint $table) {
            if (!Schema::hasColumn('barcodes', 'entity_id')) {
                $table->uuidMorphs('entity'); // entity_type + entity_id
            }
        });

        Schema::table('packages', function (Blueprint $table) {
            if (Schema::hasColumn('packages', 'barcode')) {
                $table->dropColumn('barcode');
            }

            if (!Schema::hasColumn('packages', 'goods_id')) {
                $table->uuid('goods_id')->index();
                $table->foreign('goods_id')->references('id')->on('goods');
            }
        });

        $this->recreateForeignKeys();
    }

    private function recreateForeignKeys(): void
    {
        Schema::table('barcodes', function (Blueprint $table) {
            $table->foreign('goods_id')->references('id')->on('goods');
        });

        Schema::table('goods_by_documents', function (Blueprint $table) {
            $table->foreign('goods_id')->references('id')->on('goods');
        });

        Schema::table('leftovers', function (Blueprint $table) {
            $table->foreign('goods_id')->references('id')->on('goods');
        });

        Schema::table('packages', function (Blueprint $table) {
            $table->foreign('goods_id')->references('id')->on('goods');
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
