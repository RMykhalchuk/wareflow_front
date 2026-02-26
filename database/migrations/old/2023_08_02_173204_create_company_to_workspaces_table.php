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
        Schema::create('company_to_workspaces', function (Blueprint $table) {
            $table->id();
            $table->uuid('company_id')->index()->nullable();
            $table->foreign('company_id')->references('id')->on('companies');
            $table->unsignedBigInteger('workspace_id')->index()->nullable();
            $table->foreign('workspace_id')->references('id')->on('workspaces');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_to_workspaces');
    }
};
