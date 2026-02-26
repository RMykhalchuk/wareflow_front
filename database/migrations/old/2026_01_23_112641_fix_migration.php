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
        Schema::table('task_logs', function (Blueprint $table) {
            $table->foreign('creator_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('document_id')->references('id')->on('documents')->nullOnDelete();
            $table->foreign('task_id')->references('id')->on('tasks')->nullOnDelete();
            $table->foreign('creator_company_id')->references('id')->on('companies')->nullOnDelete();
        });

        Schema::table('leftovers', function (Blueprint $table) {
            $table->uuid('document_id')->nullable();
            $table->foreign('document_id')->references('id')->on('documents')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
