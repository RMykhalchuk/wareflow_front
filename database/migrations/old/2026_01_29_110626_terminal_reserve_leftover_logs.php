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
        Schema::create('terminal_leftover_logs', function (Blueprint $table) {
            $table->id();
            $table->uuid('document_id');
            $table->uuid('leftover_id');
            $table->uuid('container_id')->nullable();
            $table->float('quantity');
            $table->timestamps();

            $table->foreign('document_id')->references('id')->on('documents');
            $table->foreign('leftover_id')->references('id')->on('leftovers');
            $table->foreign('container_id')->references('id')->on('container_registers');

            $table->uuid('package_id');
            $table->foreign('package_id')->references('id')->on('packages');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('terminal_leftover_logs');
    }
};
