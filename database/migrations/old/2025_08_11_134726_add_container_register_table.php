<?php

use App\Enums\ContainerRegister\ContainerRegisterStatus;
use App\Enums\ContainerRegister\GoodsCondition;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('container_registers', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->unsignedBigInteger('local_id')->default(1);

            $table->string('code')->nullable();

            $table->uuid('container_id')->index();
            $table->foreign('container_id')->references('id')->on('containers');

            $table->unsignedBigInteger('cell_id')->index();
            $table->foreign('cell_id')->references('id')->on('cell_allocations');

            $table->uuid('creator_company_id')->index();
            $table->foreign('creator_company_id')->references('id')->on('companies');

            $table->enum('status_id', ContainerRegisterStatus::values())->default(ContainerRegisterStatus::EMPTY);

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('leftover_to_container_registers', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('leftover_id')->index();
            $table->foreign('leftover_id')->references('id')->on('leftovers');

            $table->uuid('container_register_id')->index();
            $table->foreign('container_register_id')->references('id')->on('container_registers');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_to_container_registers');
        Schema::dropIfExists('container_registers');
    }
};
