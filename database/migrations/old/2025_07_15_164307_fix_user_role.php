<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_working_data', function (Blueprint $table) {

            // 2. Drop index if it still exists after dropping foreign key
            $table->dropIndex('user_working_data_role_id_index');

            // 3. Add foreign key to `d_roles`
            $table->foreign('role_id')
                ->references('id')
                ->on('_d_roles')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('user_working_data', function (Blueprint $table) {
            // Drop new foreign key
            $table->dropForeign(['role_id']);

            // Restore index
            $table->index('role_id', 'user_working_data_role_id_index');
        });
    }
};

