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
        Schema::create('companies', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->unsignedBigInteger('local_id')->default(1);
            $table->string('email', 50);
            $table->uuid('company_id');
            $table->string('company_type');
            $table->unsignedBigInteger('company_type_id');
            $table->foreign('company_type_id')->references('id')->on('company_types');
            $table->unsignedBigInteger('status_id')->default(1);
            $table->foreign('status_id')->references('id')->on('company_statuses');
            $table->unsignedBigInteger('ipn')->nullable();
            $table->unsignedBigInteger('address_id');
            $table->foreign('address_id')->references('id')->on('address_details');
            $table->string('bank', 50);
            $table->string('iban', 29);
            $table->integer('mfo');
            $table->text('about');
            $table->string('img_type', 5)->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('companies');
    }
};
