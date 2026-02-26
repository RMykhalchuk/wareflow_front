<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {

            $dropArray = ['role_id', 'company_id', 'position_id'];

            foreach ($dropArray as $column) {
                DB::statement("ALTER TABLE users DROP CONSTRAINT users_{$column}_foreign");
            }

            $table->dropColumn(['position_id', 'driving_license_number', 'health_book_number', 'driving_license_doctype',
                'health_book_doctype', 'role_id', 'company_id', 'driver_license_date', 'health_book_date']);
        });

        Schema::create('user_working_data', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('local_id')->default(1);

            $table->uuid('company_id')->index()->nullable();
            $table->foreign('company_id')->references('id')->on('companies');

            $table->unsignedBigInteger('role_id')->index()->nullable();
            $table->foreign('role_id')->references('id')->on('roles');

            $table->unsignedBigInteger('position_id')->index()->nullable();
            $table->foreign('position_id')->references('id')->on('positions');

            $table->string('driving_license_number', 9)->nullable();
            $table->string('health_book_number', 20)->nullable();
            $table->string('driving_license_doctype', 5)->nullable();
            $table->string('health_book_doctype', 5)->nullable();
            $table->date('driver_license_date')->nullable();
            $table->date('health_book_date')->nullable();

            $table->uuid('user_id')->index()->nullable();
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedBigInteger('workspace_id')->index()->nullable();
            $table->foreign('workspace_id')->references('id')->on('workspaces');
            $table->softDeletes();
            $table->timestamps();
        });


        Schema::table('schedules', function (Blueprint $table) {
            $table->dropForeign(['user_id']);

            // Create a new foreign key constraint with the desired changes
            $table->foreign('user_id')
                ->references('id')
                ->on('user_working_data')
                ->onDelete('cascade');
        });

        Schema::table('schedule_exceptions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);

            // Create a new foreign key constraint with the desired changes
            $table->foreign('user_id')
                ->references('id')
                ->on('user_working_data')
                ->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_working_data');
    }
};
