<?php

use App\Enums\Documents\DoctypeFieldType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctype_fields', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('local_id')->default(1);

            $table->string('key', 200);
            $table->string('title', 200);
            $table->string('description')->nullable();
            $table->enum('type', DoctypeFieldType::values());
            $table->boolean('system');
            $table->string('model')->nullable();
            $table->json('parameters')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doctype_fields');
    }
};
