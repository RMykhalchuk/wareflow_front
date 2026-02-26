<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // 1. Видаляємо CHECK constraint
        DB::statement('ALTER TABLE tasks DROP CONSTRAINT IF EXISTS tasks_status_check');

        // 2. Міняємо тип на звичайний string
        Schema::table('tasks', function (Blueprint $table) {
            $table->string('status')->change();
        });
    }

    public function down()
    {
        // Повернути назад (опціонально)
        Schema::table('tasks', function (Blueprint $table) {
            $table->enum('status', ['on_arrival', 'created', 'in_progress', 'done'])->change();
        });

        DB::statement(
            "ALTER TABLE tasks ADD CONSTRAINT tasks_status_check CHECK (status IN ('on_arrival','created','in_progress','done'))"
        );
    }
};
