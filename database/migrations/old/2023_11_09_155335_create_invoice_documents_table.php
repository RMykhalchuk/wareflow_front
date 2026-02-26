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
        Schema::create('invoice_documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('local_id')->default(1);

            $table->uuid('invoice_id')->index();
            $table->uuid('document_id')->index();

            $table->foreign('invoice_id')->references('id')->on('invoices');
            $table->foreign('document_id')->references('id')->on('documents');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_documents', function (Blueprint $table) {
            $table->dropForeign(['invoice_id']);
            $table->dropForeign(['document_id']);
        });

        Schema::dropIfExists('invoice_documents');
    }
};
