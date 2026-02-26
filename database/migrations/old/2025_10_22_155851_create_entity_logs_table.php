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
        Schema::create('entity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('log_type',50);
            $table->string('entity_type', 50);
            $table->string('model_type');
            $table->uuid('model_id');
            $table->json('data')->nullable();
            $table->uuid('user_id')->nullable();
            $table->uuid('creator_company_id')->nullable();

            $table->string('ip_address',100)->nullable(); // IP користувача
            $table->string('source',20)->nullable(); // наприклад: api, web, system, queue

            $table->timestamps();


            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('creator_company_id')->references('id')->on('companies')->nullOnDelete();

            $table->index(['model_type', 'model_id']);
            $table->index('log_type');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entity_logs');
    }
};
