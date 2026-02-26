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
        Schema::create('document_leftover_reservations', function (Blueprint $table) {
            $table->id();
            $table->uuid('document_id');
            $table->uuid('goods_id');
            $table->float('quantity');

            $table->foreign('document_id')->references('id')->on('documents')->nullOnDelete();
            $table->foreign('goods_id')->references('id')->on('goods')->nullOnDelete();
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
