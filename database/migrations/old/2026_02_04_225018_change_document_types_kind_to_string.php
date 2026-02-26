<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Видаляємо CHECK constraint (якщо існує)
        DB::statement("
            ALTER TABLE document_types
            DROP CONSTRAINT IF EXISTS document_types_kind_check
        ");

        // 2. Міняємо тип enum -> string
        DB::statement("
            ALTER TABLE document_types
            ALTER COLUMN kind TYPE varchar(50)
            USING kind::text
        ");
    }

    public function down(): void
    {
        // ⚠️ rollback можливий тільки якщо ти знаєш допустимі значення

        // 1. Створюємо enum заново
        DB::statement("
            DO $$
            BEGIN
                IF NOT EXISTS (
                    SELECT 1 FROM pg_type WHERE typname = 'document_types_kind'
                ) THEN
                    CREATE TYPE document_types_kind AS ENUM (
                        'income',
                        'outcome',
                        'internal'
                    );
                END IF;
            END$$;
        ");

        // 2. Міняємо string -> enum
        DB::statement("
            ALTER TABLE document_types
            ALTER COLUMN kind TYPE document_types_kind
            USING kind::document_types_kind
        ");

        // 3. Повертаємо CHECK (якщо він був)
        DB::statement("
            ALTER TABLE document_types
            ADD CONSTRAINT document_types_kind_check
            CHECK (kind IN ('income', 'outcome', 'internal'))
        ");
    }
};
