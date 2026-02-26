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
        Schema::table('goods', function (Blueprint $table) {
            $table->unsignedBigInteger('adr_id')->nullable()->change();
        });

        Schema::table('packages', function (Blueprint $table) {
            if (!Schema::hasColumn('packages', 'local_id')) {
                // якщо немає — додаємо
                $table->unsignedBigInteger('local_id')->default(1);
            }
        });

        // окремо, бо change() не можна поєднувати з умовною перевіркою в одному Schema::table()
        if (Schema::hasColumn('packages', 'local_id')) {
            Schema::table('packages', function (Blueprint $table) {
                $table->unsignedBigInteger('local_id')->default(1)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('goods', function (Blueprint $table) {
            //
        });
    }
};
