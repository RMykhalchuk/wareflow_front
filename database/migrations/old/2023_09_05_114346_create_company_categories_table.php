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
        Schema::create('company_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->unsignedBigInteger('workspace_id');
            $table->foreign('workspace_id')->references('id')->on('workspaces');
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->index()->nullable();
            $table->foreign('category_id')->references('id')->on('company_categories');
        });

        Schema::table('address_details', function (Blueprint $table) {
            $table->string('comment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_categories');
    }
};
