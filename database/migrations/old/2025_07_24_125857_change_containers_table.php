<?php


use App\Enums\Containers\ContainerStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('DROP TABLE IF EXISTS containers CASCADE');

        Schema::create('containers', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('name', 50);
            $table->string('code_format', 5);
            $table->float('weight');
            $table->float('height');
            $table->float('length');
            $table->float('width');
            $table->float('max_weight');
            $table->tinyInteger('reversible')->default(0);

            $table->enum("status_id", ContainerStatus::values())->index();

            $table->unsignedBigInteger("type_id")->index();
            $table->foreign('type_id')->references('id')->on('_d_container_types');
            $table->uuid('creator_company_id')->index();
            $table->foreign('creator_company_id')->references('id')->on('companies');

            $table->string('erp_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        $this->recreateForeignKeys();
    }

    private function recreateForeignKeys(): void
    {
        Schema::table('container_by_documents', function (Blueprint $table) {
            $table->foreign('container_id')->references('id')->on('containers');
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
