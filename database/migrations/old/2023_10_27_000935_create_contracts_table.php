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
        Schema::create('contracts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('local_id')->default(1);

            $table->unsignedTinyInteger('role');
            $table->unsignedTinyInteger('type_id');
            $table->unsignedTinyInteger('status')->default(0);
            $table->string('file')->nullable();
            $table->text('termination_reasons')->nullable();
            $table->text('decline_reasons')->nullable();

            $table->uuid('company_id')->index();
            $table->unsignedBigInteger('workspace_id');
            $table->uuid('counterparty_id')->index();
            $table->uuid('company_regulation_id')->index()->nullable();
            $table->uuid('counterparty_regulation_id')->index()->nullable();

            $table->timestamp('expired_at');
            $table->timestamp('signed_at')->nullable();
            $table->timestamp('signed_at_counterparty')->nullable();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->foreign('workspace_id')->references('id')->on('workspaces')->cascadeOnDelete();
            $table->foreign('counterparty_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->foreign('company_regulation_id')->references('id')->on('regulations')->cascadeOnDelete();
            $table->foreign('counterparty_regulation_id')->references('id')->on('regulations')->cascadeOnDelete();
        });

        Schema::create('contracts_comments', function (Blueprint $table) {
            $table->id();
            $table->text('comment');
            $table->uuid('contract_id')->index();
            $table->uuid('company_id')->index();
            $table->timestamps();

            $table->foreign('contract_id')->references('id')->on('contracts')->cascadeOnDelete();
            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contracts_comments', function (Blueprint $table) {
            $table->dropForeign(['contract_id']);
            $table->dropForeign(['company_id']);
        });
        Schema::dropIfExists('contracts_comments');

        Schema::table('contracts', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropForeign(['workspace_id']);
            $table->dropForeign(['counterparty_id']);
            $table->dropForeign(['company_regulation_id']);
            $table->dropForeign(['counterparty_regulation_id']);
        });
        Schema::dropIfExists('contracts');
    }
};
