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
        Schema::create('workspaces', function (Blueprint $table) {
            $table->id();

            $table->string('name', 50);
            $table->uuid('user_id')->index();
            $table->string('avatar_type', 3)->nullable();
            $table->string('avatar_color', 7)->nullable();
            $table->json('warehouse')->nullable();
            $table->json('employees')->nullable();
            $table->json('integration')->nullable();
            $table->unsignedSmallInteger('employees_count')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workspaces');
    }
};
