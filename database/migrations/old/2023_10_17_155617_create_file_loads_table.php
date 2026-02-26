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
        Schema::create('file_loads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('path');
            $table->string('new_name', 50);
            $table->uuid('user_id')->index()->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('workspace_id')->index()->nullable();
            $table->foreign('workspace_id')->references('id')->on('workspaces');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_loads');
    }
};
