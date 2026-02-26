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

        Schema::table('leftovers', function (Blueprint $table) {
            // Drop foreign key and column
            $table->dropForeign(['package_id']);
            $table->dropColumn('package_id');
        });


        DB::statement('DROP TABLE IF EXISTS packages CASCADE');


        Schema::create('packages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('local_id')->default(1)->after('id');

            $table->uuid('parent_id')->nullable();
            $table->unsignedBigInteger("type_id")->index();
            $table->foreign('type_id')->references('id')->on('_d_package_types');
            $table->string('name',50);

            $table->integer('main_units_number');
            $table->integer('package_count')->nullable();

            $table->float('weight_netto');
            $table->float('weight_brutto');
            $table->float('height');
            $table->float('width');
            $table->float('length');


            $table->uuid('goods_id')->index();
            $table->foreign('goods_id')->references('id')->on('goods');

            $table->uuid('creator_company_id')->index();
            $table->foreign('creator_company_id')->references('id')->on('companies');

            $table->softDeletes();
            $table->timestamps();
        });



        Schema::table('leftovers', function (Blueprint $table) {
            // Add new UUID column and foreign key
            $table->uuid('package_id')->after('container_id')->index();
            $table->foreign('package_id')->references('id')->on('packages');
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
