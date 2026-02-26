<?php

use App\Enums\Leftovers\LeftoversStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('leftovers', function (Blueprint $table) {
            $table->uuid('creator_company_id')->index();
            $table->foreign('creator_company_id')->references('id')->on('companies');

            $table->uuid('warehouse_id')->index();
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            $table->enum("status_id", LeftoversStatus::values())->default(LeftoversStatus::ACTIVE->value)->index();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('local_id');
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
