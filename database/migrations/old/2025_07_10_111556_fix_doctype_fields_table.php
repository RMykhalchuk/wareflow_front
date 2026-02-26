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
        Schema::table('doctype_fields', function (Blueprint $table) {
            $table->uuid('creator_company_id')->nullable()->index()->after('workspace_id');
            $table->foreign('creator_company_id')->references('id')->on('companies');
            $table->dropForeign(['workspace_id']);
            $table->dropColumn('workspace_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
