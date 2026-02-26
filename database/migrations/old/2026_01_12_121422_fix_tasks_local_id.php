<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE tasks ALTER COLUMN local_id TYPE BIGINT USING local_id::BIGINT');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE tasks ALTER COLUMN local_id TYPE VARCHAR(255) USING local_id::VARCHAR');
    }
};
