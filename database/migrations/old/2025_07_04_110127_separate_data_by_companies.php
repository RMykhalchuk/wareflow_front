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
        Schema::table('barcodes', function (Blueprint $table) {
            $table->uuid('creator_company_id')->index()->after('user_id');
            $table->foreign('creator_company_id')->references('id')->on('companies');
        });

        Schema::table('file_loads', function (Blueprint $table) {
            $table->uuid('creator_company_id')->index()->after('user_id');
            $table->foreign('creator_company_id')->references('id')->on('companies');
            $table->dropForeign(['workspace_id']);
            $table->dropColumn('workspace_id');
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->uuid('creator_company_id')->index()->after('workspace_id');
        });

        Schema::table('containers', function (Blueprint $table) {
            $table->uuid('creator_company_id')->index()->after('workspace_id');
            $table->foreign('creator_company_id')->references('id')->on('companies');
            $table->dropForeign(['workspace_id']);
            $table->dropColumn('workspace_id');
        });

        Schema::table('contracts', function (Blueprint $table) {
            $table->uuid('creator_company_id')->index()->after('workspace_id');
            $table->foreign('creator_company_id')->references('id')->on('companies');
            $table->dropForeign(['workspace_id']);
            $table->dropColumn('workspace_id');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->uuid('creator_company_id')->index()->after('workspace_id');
            $table->foreign('creator_company_id')->references('id')->on('companies');
            $table->dropForeign(['workspace_id']);
            $table->dropColumn('workspace_id');
        });

        Schema::table('document_types', function (Blueprint $table) {
            $table->uuid('creator_company_id')->index()->after('workspace_id')->nullable();
            $table->foreign('creator_company_id')->references('id')->on('companies');
            $table->dropForeign(['workspace_id']);
            $table->dropColumn('workspace_id');
        });

        Schema::table('goods', function (Blueprint $table) {
            $table->uuid('creator_company_id')->index()->after('workspace_id');
            $table->foreign('creator_company_id')->references('id')->on('companies');
            $table->dropForeign(['workspace_id']);
            $table->dropColumn('workspace_id');
        });

        Schema::table('integrations', function (Blueprint $table) {
            $table->uuid('creator_company_id')->index()->after('workspace_id');
            $table->foreign('creator_company_id')->references('id')->on('companies');
            $table->dropForeign(['workspace_id']);
            $table->dropColumn('workspace_id');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->uuid('creator_company_id')->index()->after('workspace_id');
            $table->foreign('creator_company_id')->references('id')->on('companies');
            $table->dropForeign(['workspace_id']);
            $table->dropColumn('workspace_id');
        });

        Schema::table('leftovers', function (Blueprint $table) {
            $table->uuid('creator_company_id')->index()->after('warehouse_id');
            $table->foreign('creator_company_id')->references('id')->on('companies');
        });

        Schema::table('regulations', function (Blueprint $table) {
            $table->uuid('creator_company_id')->index()->after('workspace_id');
            $table->foreign('creator_company_id')->references('id')->on('companies');
            $table->dropForeign(['workspace_id']);
            $table->dropColumn('workspace_id');
        });

        Schema::table('registers', function (Blueprint $table) {
            $table->uuid('creator_company_id')->index()->after('workspace_id');
            $table->foreign('creator_company_id')->references('id')->on('companies');
            $table->dropForeign(['workspace_id']);
            $table->dropColumn('workspace_id');
        });

        Schema::table('_d_roles', function (Blueprint $table) {
            $table->uuid('creator_company_id')->index()->nullable()->after('workspace_id');
            $table->foreign('creator_company_id')->references('id')->on('companies');
            $table->dropForeign(['workspace_id']);
            $table->dropColumn('workspace_id');
        });

        Schema::table('company_categories', function (Blueprint $table) {
            $table->uuid('creator_company_id')->index()->nullable()->after('workspace_id');
            $table->foreign('creator_company_id')->references('id')->on('companies');
            $table->dropForeign(['workspace_id']);
            $table->dropColumn('workspace_id');
        });

        Schema::table('schedule_patterns', function (Blueprint $table) {
            $table->uuid('creator_company_id')->index()->nullable()->after('workspace_id');
            $table->foreign('creator_company_id')->references('id')->on('companies');
            $table->dropForeign(['workspace_id']);
            $table->dropColumn('workspace_id');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->uuid('creator_company_id')->index()->after('workspace_id');
            $table->foreign('creator_company_id')->references('id')->on('companies');
            $table->dropForeign(['workspace_id']);
            $table->dropColumn('workspace_id');
        });

        Schema::table('transport_plannings', function (Blueprint $table) {
            $table->uuid('creator_company_id')->index()->after('creator_id');
            $table->foreign('creator_company_id')->references('id')->on('companies');
        });

        Schema::table('additional_equipment', function (Blueprint $table) {
            $table->uuid('creator_company_id')->index()->after('workspace_id');
            $table->foreign('creator_company_id')->references('id')->on('companies');
            $table->dropForeign(['workspace_id']);
            $table->dropColumn('workspace_id');
        });

        Schema::table('transports', function (Blueprint $table) {
            $table->uuid('creator_company_id')->index()->after('workspace_id');
            $table->foreign('creator_company_id')->references('id')->on('companies');
            $table->dropForeign(['workspace_id']);
            $table->dropColumn('workspace_id');
        });

        Schema::table('user_working_data', function (Blueprint $table) {
            $table->uuid('creator_company_id')->index()->after('workspace_id');
            $table->foreign('creator_company_id')->references('id')->on('companies');
        });

        Schema::table('warehouses', function (Blueprint $table) {
            $table->uuid('creator_company_id')->index()->after('workspace_id');
            $table->foreign('creator_company_id')->references('id')->on('companies');
        });

        Schema::table('workspaces', function (Blueprint $table) {
            $table->uuid('creator_company_id')->index()->after('user_id');
            $table->foreign('creator_company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barcodes', function (Blueprint $table) {
            $table->dropForeign(['creator_company_id']);
            $table->dropColumn('creator_company_id');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['creator_company_id']);
            $table->dropColumn('creator_company_id');
        });

        Schema::table('document_types', function (Blueprint $table) {
            $table->dropForeign(['creator_company_id']);
            $table->dropColumn('creator_company_id');
        });

        Schema::table('goods', function (Blueprint $table) {
            $table->dropForeign(['creator_company_id']);
            $table->dropColumn('creator_company_id');
        });

        Schema::table('integrations', function (Blueprint $table) {
            $table->dropForeign(['creator_company_id']);
            $table->dropColumn('creator_company_id');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['creator_company_id']);
            $table->dropColumn('creator_company_id');
        });

        Schema::table('leftovers', function (Blueprint $table) {
            $table->dropForeign(['creator_company_id']);
            $table->dropColumn('creator_company_id');
        });

        Schema::table('regulations', function (Blueprint $table) {
            $table->dropForeign(['creator_company_id']);
            $table->dropColumn('creator_company_id');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign(['creator_company_id']);
            $table->dropColumn('creator_company_id');
        });

        Schema::table('transport_plannings', function (Blueprint $table) {
            $table->dropForeign(['creator_company_id']);
            $table->dropForeign(['workspace_id']);
            $table->dropColumn(['creator_company_id', 'workspace_id']);
        });

        Schema::table('warehouses', function (Blueprint $table) {
            $table->dropForeign(['creator_company_id']);
            $table->dropColumn('creator_company_id');
        });
    }
};
