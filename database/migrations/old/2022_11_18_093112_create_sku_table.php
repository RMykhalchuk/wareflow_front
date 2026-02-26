<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('sku', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Додаткове поле для локального автоінкременту в межах workspace
            $table->unsignedBigInteger('local_id')->default(1);

            $table->string('id_erp', 50)->nullable();
            $table->string('name', 200);
            $table->boolean('1c_status')->nullable();
            $table->float('weight');
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('sku_categories');
            $table->unsignedBigInteger('measurement_unit_id');
            $table->foreign('measurement_unit_id')->references('id')->on('measurement_units');
            $table->unsignedInteger('expiration_date');
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('sku');
        Schema::enableForeignKeyConstraints();
    }
};
