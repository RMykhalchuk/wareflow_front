<?php

use App\Enums\ContainerRegister\ContainerRegisterStatus;
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
        Schema::table('container_registers', function (Blueprint $table) {
            $table->dropColumn('status_id');
        });

        Schema::table('container_registers', function (Blueprint $table) {
            $table->enum('status_id', ContainerRegisterStatus::values())
                ->default(ContainerRegisterStatus::EMPTY);
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
