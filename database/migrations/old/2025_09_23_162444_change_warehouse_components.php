<?php


use App\Models\Entities\Container\ContainerRegister;
use App\Models\Entities\Inventory\Inventory;
use App\Models\Entities\Leftover\Leftover;
use App\Models\Entities\Pallet\Pallet;
use App\Models\Entities\WarehouseComponents\Cell;
use App\Models\Entities\WarehouseComponents\Row;
use App\Models\Entities\WarehouseComponents\Sector;
use App\Models\Entities\WarehouseComponents\WarehouseZone;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Leftover::truncate();
        Pallet::truncate();
        ContainerRegister::truncate();
        Inventory::truncate();
        Pallet::truncate();
        Cell::truncate();
        Row::truncate();
        Sector::truncate();
        WarehouseZone::truncate();

        // -----------------------
        // 1. Зовнішні ключі, що посилаються на cells.id
        // -----------------------
        Schema::table('leftovers', fn($t) => $t->dropForeign(['cell_id']));
        Schema::table('pallets', fn($t) => $t->dropForeign(['cell_id']));
        Schema::table('container_registers', fn($t) => $t->dropForeign(['cell_id']));
        Schema::table('inventories', fn($t) => $t->dropForeign(['cell_id']));

        // -----------------------
        // 2. Cells: заміна id на UUID
        // -----------------------
        Schema::table('cells', function (Blueprint $table) {
            $table->dropColumn('id'); // дропаємо старий integer id
        });

        Schema::table('cells', function (Blueprint $table) {
            $table->uuid('id')->primary(); // новий UUID id
            $table->dropColumn(
                [
                    'width', 'deep', 'height', 'max_weight', 'rack', 'floor', 'column', 'created_at', 'updated_at', 'row_id'
                ]
            );
            $table->enum('parent_type', ['zone', 'sector', 'row'])->nullable();
            $table->string('model_type', 100)->nullable()->index();
            $table->uuid('model_id')->nullable()->index();
        });

        // -----------------------
        // 3. Зовнішні ключі (заміна cell_id на UUID)
        // -----------------------
        Schema::table('leftovers', function (Blueprint $table) {
            $table->dropColumn('cell_id');
            $table->uuid('cell_id')->nullable();
            $table->foreign('cell_id')->references('id')->on('cells');
        });

        Schema::table('pallets', function (Blueprint $table) {
            $table->dropColumn('cell_id');
            $table->uuid('cell_id')->nullable();
            $table->foreign('cell_id')->references('id')->on('cells');
        });

        Schema::table('container_registers', function (Blueprint $table) {
            $table->dropColumn('cell_id');
            $table->uuid('cell_id')->nullable();
            $table->foreign('cell_id')->references('id')->on('cells');
        });

        Schema::table('inventories', function (Blueprint $table) {
            $table->dropColumn('cell_id');
            $table->uuid('cell_id')->nullable();
            $table->foreign('cell_id')->references('id')->on('cells');
        });

        // -----------------------
        // 4. Створення row_cell_info
        // -----------------------
        Schema::create('row_cell_info', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->float('height')->nullable();
            $table->float('width')->nullable();
            $table->float('deep')->nullable();
            $table->float('max_weight')->nullable();

            $table->integer('rack')->default(1);
            $table->integer('floor')->default(1);
            $table->integer('column')->default(1);

            $table->uuid('cell_id')->index();
            $table->foreign('cell_id')->references('id')->on('cells');

            $table->softDeletes();
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
