<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('companies')->where('company_type', 'App\\Models\\LegalCompany')
            ->update(['company_type' => 'legal_company']);

        DB::table('companies')->where('company_type', 'App\\Models\\PhysicalCompany')
            ->update(['company_type' => 'physical_company']);

        Schema::table('cells', function (Blueprint $table) {
            $table->dropColumn('model_type');
        });

        Schema::table('entity_logs', function (Blueprint $table) {
            $table->dropColumn('entity_type');
        });

        DB::table('model_has_roles')->where('model_type', 'App\\Models\\UserWorkingData')
            ->update(['model_type' => 'user_working_data']);

        DB::table('entity_logs')->where('model_type', 'App\\Models\\Task\\Task')
            ->update(['model_type' => 'task']);

        DB::table('entity_logs')->where('model_type', 'App\\Models\\Document\\Document')
            ->update(['model_type' => 'document']);

        DB::table('entity_logs')->where('model_type', 'App\\Models\\Leftover')
            ->update(['model_type' => 'leftover']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
