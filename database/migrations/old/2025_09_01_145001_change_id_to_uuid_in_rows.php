<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Чистимо таблиці
        DB::table('leftovers')->truncate();
        DB::table('cells')->truncate();
        DB::table('rows')->truncate();

        // 2. Спочатку знімаємо foreign key у cells
        Schema::table('cells', function (Blueprint $table) {
            try {
                $table->dropForeign(['row_id']);
            } catch (\Exception $e) {
                // FK може вже бути відсутній
            }
        });

        // 3. Міняємо rows.id на uuid
        Schema::table('rows', function (Blueprint $table) {
            $table->dropPrimary(['id']); // зняти PK
            $table->dropColumn('id');    // видалити старий int
        });

        Schema::table('rows', function (Blueprint $table) {
            $table->uuid('id')->primary(); // новий PK як uuid
        });

        // 4. Міняємо cells.row_id на uuid
        Schema::table('cells', function (Blueprint $table) {
            $table->dropColumn('row_id');
        });

        Schema::table('cells', function (Blueprint $table) {
            $table->uuid('row_id')->nullable();
            $table->foreign('row_id')->references('id')->on('rows');
        });

        Schema::table('rows', function (Blueprint $table) {
            $table->renameColumn('cells','cell_count');
        });
    }

};
