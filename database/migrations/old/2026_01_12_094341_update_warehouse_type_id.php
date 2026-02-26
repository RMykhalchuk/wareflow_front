<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('warehouses')->update([
            'type_id' => 1
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
