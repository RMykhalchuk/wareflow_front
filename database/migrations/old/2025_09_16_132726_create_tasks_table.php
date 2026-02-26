<?php

use App\Enums\Task\TaskStatus;
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
        Schema::create('tasks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('local_id')->nullable();
            $table->string('processing_type',50)->nullable();
            $table->unsignedInteger('type_id');
            $table->string('kind',50);
            $table->json('executors')->nullable(); // array type
            $table->enum('status', TaskStatus::values())->default(TaskStatus::CREATED);
            $table->uuid('document_id')->nullable();
            $table->integer('priority')->default(1);
            $table->text('comment')->nullable();
            $table->json('task_data')->nullable(); // array type
            $table->uuid('creator_company_id')->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();

            // Indexes
            $table->index('local_id');
            $table->index('processing_type');
            $table->index('type_id');
            $table->index('kind');
            $table->index('status');
            $table->index('creator_company_id');
            $table->index('document_id');

             $table->foreign('creator_company_id')->references('id')->on('companies');
             $table->foreign('document_id')->references('id')->on('documents');
            $table->foreign('type_id')->references('id')->on('_d_task_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
