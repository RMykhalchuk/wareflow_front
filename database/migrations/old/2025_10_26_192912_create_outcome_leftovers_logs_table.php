<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('outcome_document_leftovers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('package_id');
            $table->integer('quantity');
            $table->uuid('document_id');
            $table->uuid('leftover_id');
            $table->uuid('creator_id');
            $table->string('processing_type', 20);
            $table->dateTime('processing_at');
            $table->timestamps();

            $table->foreign('creator_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('document_id')->references('id')->on('documents')->nullOnDelete();
            $table->foreign('leftover_id')->references('id')->on('leftovers')->nullOnDelete();
            $table->foreign('package_id')->references('id')->on('packages')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outcome_document_leftovers');
    }
};
