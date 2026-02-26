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
        Schema::create('regulations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('local_id')->default(1);

            $table->string('name');
            $table->unsignedBigInteger('workspace_id');
            $table->unsignedTinyInteger('type');
            $table->unsignedTinyInteger('service_side');
            $table->integer('parent_id');
            $table->unsignedInteger('_lft');
            $table->unsignedInteger('_rgt');
            $table->json('settings');
            $table->boolean('draft')->default(0);
            $table->timestamps();
            $table->softDeletes();

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
        Schema::table('regulations', function (Blueprint $table) {
            $table->dropForeign(['workspace_id']);
        });
        Schema::dropIfExists('regulations');
    }
};
