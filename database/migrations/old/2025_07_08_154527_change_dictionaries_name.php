<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    protected array $tables = [
        'adrs',
        'additional_equipment_brands',
        'additional_equipment_models',
        'additional_equipment_types',
        'cargo_types',
        'cell_statuses',
        'company_categories',
        'company_statuses',
        'company_types',
        'container_types',
        'countries',
        'delivery_types',
        'doctype_statuses',
        'document_statuses',
        'download_zones',
        'exception_types',
        'legal_types',
        'measurement_units',
        'package_types',
        'positions',
        'regions',
        'register_statuses',
        'service_categories',
        'settlements',
        'goods_categories',
        'streets',
        'storage_types',
        'transport_brands',
        'transport_categories',
        'transport_downloads',
        'transport_models',
        'transport_planning_failure_types',
        'transport_planning_statuses',
        'transport_types',
        'user_statuses',
        'warehouse_erp',
        'warehouse_types',
    ];

    public function up(): void
    {

        if (Schema::hasTable('sku_categories')) {
            Schema::rename('sku_categories', 'goods_categories');
        }

        if (Schema::hasTable('sku_modifications')) {
            Schema::rename('sku_modifications', 'goods_modifications');
        }

        if (Schema::hasTable('sku_by_documents')) {
            Schema::rename('sku_by_documents', 'goods_by_documents');
        }

        foreach ($this->tables as $table) {
            $newName = '_d_' . $table;

            if (Schema::hasTable($table) && !Schema::hasTable($newName)) {
                Schema::rename($table, $newName);
            }
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            $newName = '_d_' . $table;

            if (Schema::hasTable($newName) && !Schema::hasTable($table)) {
                Schema::rename($newName, $table);
            }
        }
    }
};
