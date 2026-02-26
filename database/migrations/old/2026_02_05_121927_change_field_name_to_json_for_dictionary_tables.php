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
        $tables = [
            '_d_additional_equipment_brands',
            '_d_additional_equipment_models',
            '_d_additional_equipment_types',
            '_d_adrs',
            '_d_cargo_types',
            '_d_company_categories',
            '_d_company_statuses',
            '_d_company_types',
            '_d_container_types',
            '_d_countries',
            '_d_delivery_types',
            '_d_doctype_statuses',
            '_d_document_statuses',
            '_d_download_zones',
            '_d_exception_types',
            '_d_goods_categories',
            '_d_legal_types',
            '_d_measurement_units',
            '_d_package_types',
            '_d_positions',
            '_d_register_statuses',
            '_d_service_categories',
            '_d_storage_types',
            '_d_task_types',
            '_d_transport_categories',
            '_d_transport_downloads',
            '_d_transport_planning_failure_types',
            '_d_transport_planning_statuses',
            '_d_transport_types',
            '_d_user_statuses',
            '_d_warehouse_types',
            '_d_zone_subtypes',
            '_d_zone_types',
        ];

        foreach ($tables as $table) {
            DB::statement("
                ALTER TABLE \"$table\"
                ALTER COLUMN \"name\"
                TYPE jsonb
                USING \"name\"::jsonb
            ");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            '_d_additional_equipment_brands',
            '_d_additional_equipment_models',
            '_d_additional_equipment_types',
            '_d_adrs',
            '_d_cargo_types',
            '_d_company_categories',
            '_d_company_statuses',
            '_d_company_types',
            '_d_container_types',
            '_d_countries',
            '_d_delivery_types',
            '_d_doctype_statuses',
            '_d_document_statuses',
            '_d_download_zones',
            '_d_exception_types',
            '_d_goods_categories',
            '_d_legal_types',
            '_d_measurement_units',
            '_d_package_types',
            '_d_positions',
            '_d_register_statuses',
            '_d_service_categories',
            '_d_storage_types',
            '_d_task_types',
            '_d_transport_categories',
            '_d_transport_downloads',
            '_d_transport_planning_failure_types',
            '_d_transport_planning_statuses',
            '_d_transport_types',
            '_d_user_statuses',
            '_d_warehouse_types',
            '_d_zone_subtypes',
            '_d_zone_types',
        ];

        foreach ($tables as $table) {
            DB::statement("
                ALTER TABLE \"$table\"
                ALTER COLUMN \"name\"
                TYPE text
            ");
        }
    }
};
