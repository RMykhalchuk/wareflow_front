<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('income_document_leftovers', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->smallInteger('table_id');
            $table->string('batch', 100)->nullable();
            $table->boolean('has_condition');
            $table->date('manufacture_date');
            $table->date('bb_date');
            $table->uuid('package_id');
            $table->uuid('container_id')->nullable();
            $table->integer('quantity');
            $table->uuid('document_id');
            $table->uuid('goods_id');
            $table->uuid('creator_id');
            $table->timestamps();

            $table->foreign('creator_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('document_id')->references('id')->on('documents')->nullOnDelete();
            $table->foreign('container_id')->references('id')->on('container_registers')->nullOnDelete();
            $table->foreign('package_id')->references('id')->on('packages')->nullOnDelete();
            $table->foreign('goods_id')->references('id')->on('goods')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('income_document_leftovers');
    }
};
