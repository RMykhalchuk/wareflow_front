<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\Cells\CellStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cells', function (Blueprint $table) {
            $table->unsignedTinyInteger('status')
                ->default(CellStatus::ACTIVE->value)
                ->comment('1 = Доступно, 2 = Заблоковано');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cells', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
