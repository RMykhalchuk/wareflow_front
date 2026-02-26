<?php

use App\Enums\Documents\DocumentKind;
use App\Models\Entities\Document\DocumentType;
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
        DocumentType::truncate();

        Schema::table('doctype_fields', function (Blueprint $table) {
            $table->dropColumn(['system']);
        });

        Schema::table('document_types', function (Blueprint $table) {
            $table->dropColumn(['status_id', 'key']);
        });

        Schema::create('doctype_structure', function (Blueprint $table) {
            $table->id();
            $table->enum('kind', DocumentKind::values());
            $table->json('settings');
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
