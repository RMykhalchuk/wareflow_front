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
        Schema::create('warehouses', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->unsignedBigInteger('local_id')->default(1);
            $table->unsignedBigInteger('type_id');
            $table->foreign('type_id')->references('id')->on('warehouse_types');

            $table->unsignedBigInteger('address_id');
            $table->foreign('address_id')->references('id')->on('address_details');
            $table->json('coordinates')->nullable();

            $table->uuid('user_id')->index();
            $table->foreign('user_id')->references('id')->on('users');

            $table->uuid('company_id')->index();
            $table->foreign('company_id')->references('id')->on('companies');
            $table->string('email', 60);
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('warehouses');
    }
};
