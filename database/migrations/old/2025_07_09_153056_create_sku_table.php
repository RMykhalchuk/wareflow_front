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
        DB::statement('DROP TABLE IF EXISTS goods CASCADE');

        Schema::create('goods', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('local_id')->default(1);
            $table->string('name');
            $table->unsignedBigInteger('category_id')->index();
            $table->unsignedBigInteger('manufacturer_country_id')->index();
            $table->unsignedBigInteger('adr_id')->index();
            $table->text('comment');
            $table->float('weight');
            $table->float('temp_from')->nullable();
            $table->float('temp_to')->nullable();
            $table->float('height');
            $table->float('width');
            $table->float('depth');

            $table->uuid('creator_company_id')->nullable();

            $table->foreign('manufacturer_country_id')->references('id')->on('_d_countries');
            $table->foreign('category_id')->references('id')->on('_d_goods_categories');
            $table->foreign('creator_company_id')->references('id')->on('companies');
            $table->foreign('adr_id')->references('id')->on('_d_adrs');

            $table->timestamps();
            $table->softDeletes();
        });

        // Відновити зв'язки на залежних таблицях
        $this->recreateForeignKeys();
    }

    private function recreateForeignKeys(): void
    {
        Schema::table('barcodes', function (Blueprint $table) {
            $table->foreign('goods_id')->references('id')->on('goods');
        });

        Schema::table('packages', function (Blueprint $table) {
            $table->foreign('goods_id')->references('id')->on('goods');
        });

        Schema::table('goods_by_documents', function (Blueprint $table) {
            $table->foreign('goods_id')->references('id')->on('goods');
        });

        Schema::table('leftovers', function (Blueprint $table) {
            $table->foreign('goods_id')->references('id')->on('goods');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sku');
    }
};
