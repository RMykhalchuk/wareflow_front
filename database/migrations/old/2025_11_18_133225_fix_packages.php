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
        DB::table('barcodes')->where('entity_type', 'App\\Models\\Package')
            ->update(['entity_type' => 'package']);

        DB::table('barcodes')->where('entity_type', 'App\\Models\\Goods')
            ->update(['entity_type' => 'goods']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
