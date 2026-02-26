<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leftovers', function (Blueprint $table) {
            $table->dropColumn('container_id');
        });

        Schema::table('leftovers', function (Blueprint $table) {

             $table->uuid('container_id')->nullable()->after('cell_id');


            // створюємо новий foreign key
            $table->foreign('container_id')
                ->references('id')
                ->on('container_registers')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('leftovers', function (Blueprint $table) {

            $table->dropForeign(['container_id']);

            $table->foreign('container_id')
                ->references('id')
                ->on('containers')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }
};
