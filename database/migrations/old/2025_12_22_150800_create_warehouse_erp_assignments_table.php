<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('warehouse_erp_assignments')) {
            Schema::create('warehouse_erp_assignments', function (Blueprint $table) {
                $table->uuid('warehouse_id');
                $table->uuid('warehouse_erp_id');

                $table->primary(['warehouse_id', 'warehouse_erp_id']);
                $table->foreign('warehouse_id')
                    ->references('id')->on('warehouses')
                    ->onDelete('cascade');
                $table->foreign('warehouse_erp_id')
                    ->references('id')->on('warehouses_erp')
                    ->onDelete('cascade');
            });
        }

        if (Schema::hasColumn('warehouses', 'warehouse_erp_id')) {
            // Insert existing pairs, ignore duplicates
            DB::table('warehouses')
                ->whereNotNull('warehouse_erp_id')
                ->orderBy('id')
                ->chunkById(500, function ($rows) {
                    $inserts = [];
                    foreach ($rows as $r) {
                        $inserts[] = [
                            'warehouse_id' => $r->id,
                            'warehouse_erp_id' => $r->warehouse_erp_id,
                        ];
                    }
                    if (!empty($inserts)) {
                        DB::table('warehouse_erp_assignments')->insertOrIgnore($inserts);
                    }
                });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouse_erp_assignments');
    }
};
