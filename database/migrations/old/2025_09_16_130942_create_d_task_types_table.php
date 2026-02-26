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
        Schema::create('_d_task_types', function (Blueprint $table) {
            $table->id();

            $table->string('key',100);
            $table->string('name',100);

            $table->boolean('is_system')->default(false);

            $table->uuid('creator_company_id')->nullable();
            $table->foreign('creator_company_id')->references('id')->on('companies');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('d_task_types');
    }
};
