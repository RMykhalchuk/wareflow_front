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
        DB::table('goods')->update([
            'provider'     => null,
            'manufacturer' => null,
            'brand'        => null,
        ]);

        DB::statement(<<<SQL
ALTER TABLE goods
    ALTER COLUMN provider DROP DEFAULT,
    ALTER COLUMN provider TYPE uuid USING provider::uuid,
    ALTER COLUMN provider DROP NOT NULL
SQL
        );

        DB::statement(<<<SQL
ALTER TABLE goods
    ALTER COLUMN manufacturer DROP DEFAULT,
    ALTER COLUMN manufacturer TYPE uuid USING manufacturer::uuid,
    ALTER COLUMN manufacturer DROP NOT NULL
SQL
        );

        DB::statement(<<<SQL
ALTER TABLE goods
    ALTER COLUMN brand DROP DEFAULT,
    ALTER COLUMN brand TYPE uuid USING brand::uuid,
    ALTER COLUMN brand DROP NOT NULL
SQL
        );

        Schema::table('goods', function (Blueprint $table) {
            $table->foreign('provider')
                ->references('id')
                ->on('companies')
                ->nullOnDelete();

            $table->foreign('manufacturer')
                ->references('id')
                ->on('companies')
                ->nullOnDelete();

            $table->foreign('brand')
                ->references('id')
                ->on('companies')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('goods', function (Blueprint $table) {
            $table->dropForeign(['provider']);
            $table->dropForeign(['manufacturer']);
            $table->dropForeign(['brand']);
        });

        DB::statement(<<<SQL
ALTER TABLE goods
    ALTER COLUMN provider TYPE varchar(255),
    ALTER COLUMN provider DROP DEFAULT
SQL
        );

        DB::statement(<<<SQL
ALTER TABLE goods
    ALTER COLUMN manufacturer TYPE varchar(255),
    ALTER COLUMN manufacturer DROP DEFAULT
SQL
        );

        DB::statement(<<<SQL
ALTER TABLE goods
    ALTER COLUMN brand TYPE varchar(255),
    ALTER COLUMN brand DROP DEFAULT
SQL
        );
    }
};
