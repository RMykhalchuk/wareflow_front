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
        Schema::table('additional_equipment', function (Blueprint $table) {
            $table->unsignedBigInteger('workspace_id')->nullable();
            $table->foreign('workspace_id')->references('id')->on('workspaces');
        });

        Schema::table('doctype_fields', function (Blueprint $table) {
            $table->unsignedBigInteger('workspace_id')->nullable();
            $table->foreign('workspace_id')->references('id')->on('workspaces');
        });

        Schema::table('document_types', function (Blueprint $table) {
            $table->unsignedBigInteger('workspace_id')->nullable();
            $table->foreign('workspace_id')->references('id')->on('workspaces');
        });
        Schema::table('transports', function (Blueprint $table) {
            $table->unsignedBigInteger('workspace_id')->nullable();
            $table->foreign('workspace_id')->references('id')->on('workspaces');
        });
        Schema::table('registers', function (Blueprint $table) {
            $table->unsignedBigInteger('workspace_id')->nullable();
            $table->foreign('workspace_id')->references('id')->on('workspaces');
        });

        Schema::table('warehouses', function (Blueprint $table) {
            $table->unsignedBigInteger('workspace_id')->nullable();
            $table->foreign('workspace_id')->references('id')->on('workspaces');
        });


        Schema::dropIfExists('packagings');
        Schema::dropIfExists('sku_modifications');
        Schema::dropIfExists('sku');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
