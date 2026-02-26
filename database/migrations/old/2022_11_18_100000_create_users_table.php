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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->unsignedBigInteger('local_id')->default(1);
            $table->string('name', 50);
            $table->string('surname', 50);
            $table->string('patronymic', 50);
            $table->date('birthday');
            $table->string('phone', 15);
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->unsignedBigInteger('position_id');
            $table->foreign('position_id')->references('id')->on('positions');
            $table->string('key_pass_card', 16)->nullable();
            $table->string('driving_license_number', 9)->nullable();
            $table->string('health_book_number', 20)->nullable();
            $table->string('driving_license_doctype', 5)->nullable();
            $table->string('health_book_doctype', 5)->nullable();
            $table->string('avatar_type', 10)->nullable();
            $table->boolean('new_user')->default(1);
            $table->date('last_seen')->nullable();
            $table->softDeletes();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
        Schema::enableForeignKeyConstraints();
    }
};
