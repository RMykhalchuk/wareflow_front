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
        Schema::table('leftovers_erp', function (Blueprint $table) {

            try {
                $table->dropForeign(['goods_erp_id']);
            } catch (\Exception $e) {
                DB::statement("SELECT 1;");
            }
            try {
                $table->dropForeign(['warehouse_erp_id']);
            } catch (\Exception $e) {
                DB::statement("SELECT 1;");
            }

            $table->string('goods_erp_id', 100)->change();
            $table->string('warehouse_erp_id', 100)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leftovers_erp', function (Blueprint $table) {
            try {
                $table->dropForeign(['goods_erp_id']);
            } catch (\Exception $e) {
                DB::statement("SELECT 1;");
            }
            try {
                $table->dropForeign(['warehouse_erp_id']);
            } catch (\Exception $e) {
                DB::statement("SELECT 1;");
            }

            $table->uuid('goods_erp_id')->change();
            $table->uuid('warehouse_erp_id')->change();
        });
    }
};
