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
      //  Schema::drop('cell_allocations');

        Schema::table('cells', function (Blueprint $table) {
            $table->integer('rack')->default(1)->after('code');
            $table->integer('floor')->default(1)->after('rack');
            $table->integer('column')->default(1)->after('floor');
            $table->unsignedBigInteger("row_id")->default(1)->index()->after('column');
            $table->foreign('row_id')->references('id')->on('rows');
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
