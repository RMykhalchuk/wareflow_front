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
        Schema::table('warehouses', function (Blueprint $table) {
            $table->dropColumn('company_id');

            $table->renameColumn('warehouses_erp_id', 'warehouse_erp_id');
            $table->dropForeign(['workspace_id']);
            $table->dropColumn('workspace_id');
        });

        Schema::table('warehouse_zones', function (Blueprint $table) {
            $table->string('color',25)->nullable()->after('name');
        });

        Schema::table('sectors', function (Blueprint $table) {
            $table->string('name',5)->nullable()->after('id');
            $table->string('color',25)->nullable()->after('name');
            $table->boolean('has_temp')->default(false)->after('color');
            $table->float('temp_from')->nullable();
            $table->float('temp_to')->nullable();

            $table->boolean('has_humidity')->default(false)->after('temp_to');
            $table->float('humidity_from')->nullable();
            $table->float('humidity_to')->nullable();
            $table->dropColumn(['storage_type_id','is_active']);

            $table->softDeletes();
        });

        Schema::table('cells', function (Blueprint $table) {
            $table->softDeletes();
           $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warehouses', function (Blueprint $table) {
            //
        });
    }
};
