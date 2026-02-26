<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\Leftovers\LeftoversStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('leftovers', function (Blueprint $table) {
            $table->dropColumn('status_id');
        });

        Schema::table('leftovers', function (Blueprint $table) {
            $table->enum('status_id', LeftoversStatus::values())
                ->default(LeftoversStatus::ACTIVE->value)
                ->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leftovers', function (Blueprint $table) {
            $table->dropColumn('status_id');
        });
    }
};
