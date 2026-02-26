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
        Schema::dropIfExists('containers');

        Schema::create('container_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('containers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('local_id')->default(1);

            $table->string('name');
            $table->string('uniq_id');
            $table->unsignedBigInteger('workspace_id');
            $table->uuid('company_id')->index();
            $table->unsignedBigInteger('type_id');
            $table->boolean('draft')->default(0);

            $table->boolean('reversible')->default(0);
            $table->text('comment')->nullable();
            $table->double('weight')->nullable();
            $table->double('height')->nullable();
            $table->double('width')->nullable();
            $table->double('depth')->nullable();
            $table->timestamps();

            $table->foreign('workspace_id')->references('id')->on('workspaces');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('type_id')->references('id')->on('container_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('containers', function (Blueprint $table) {
            $table->dropForeign(['workspace_id']);
            $table->dropForeign(['company_id']);
            $table->dropForeign(['type_id']);
        });

        Schema::dropIfExists('container_types');
        Schema::dropIfExists('containers');

        Schema::create('containers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }
};
