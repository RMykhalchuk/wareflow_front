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
        Schema::create('workspace_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('local_id')->default(1);

            $table->unsignedBigInteger('workspace_id')->index();
            $table->uuid('user_id')->index();
            $table->uuid('requested_id')->index();
            $table->unsignedTinyInteger('status')->default(0);
            $table->timestamps();

            $table->foreign('workspace_id')->references('id')->on('workspaces')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('requested_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('workspace_requests', function (Blueprint $table) {
            $table->dropForeign(['workspace_id']);
            $table->dropForeign(['user_id']);
            $table->dropForeign(['requested_id']);
        });

        Schema::dropIfExists('workspace_requests');
    }
};
