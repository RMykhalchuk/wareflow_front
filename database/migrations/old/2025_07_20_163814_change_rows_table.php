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
        Schema::table('rows', function (Blueprint $table) {
            $table->string('name',5)->nullable()->after('id');
            $table->renameColumn('floors_number','floors');
            $table->renameColumn('racks_number','racks');
            $table->renameColumn('rack_cells_number','cells');
            $table->smallInteger('save_type')->default(1)->after('cells');
            $table->smallInteger('numeration_type')->default(1)->after('save_type');
        });

        Schema::table('cells', function (Blueprint $table) {
            $table->float('height')->nullable()->change();
            $table->float('width')->nullable()->change();
            $table->float('deep')->nullable()->change();
            $table->float('max_weight')->nullable()->change();
            $table->float('type')->nullable()->change();
            $table->unsignedBigInteger('status_id')->nullable()->change();
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
