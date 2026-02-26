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
        Schema::create('legal_companies', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Додаткове поле для локального автоінкременту в межах workspace
            $table->unsignedBigInteger('local_id')->default(1);

            $table->string('name', 50);
            $table->unsignedBigInteger('edrpou');
            $table->unsignedBigInteger('legal_type_id');
            $table->foreign('legal_type_id')->references('id')->on('legal_types');
            $table->unsignedBigInteger('legal_address_id');
            $table->foreign('legal_address_id')->references('id')->on('address_details');
            $table->string('install_doctype', 5)->nullable();
            $table->string('reg_doctype', 5)->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('legal_companies');
        Schema::enableForeignKeyConstraints();
    }
};
