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
        DB::statement('ALTER TABLE public.leftovers DROP CONSTRAINT IF EXISTS leftovers_status_id_check');

        // Додаємо той самий вираз, але з "3"
        DB::statement("
            ALTER TABLE public.leftovers
            ADD CONSTRAINT leftovers_status_id_check
            CHECK (
                (status_id)::text = ANY (
                    ARRAY[
                        ('1'::character varying)::text,
                        ('2'::character varying)::text,
                        ('3'::character varying)::text
                    ]
                )
            )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE public.leftovers DROP CONSTRAINT IF EXISTS leftovers_status_id_check');

        DB::statement("
            ALTER TABLE public.leftovers
            ADD CONSTRAINT leftovers_status_id_check
            CHECK (
                (status_id)::text = ANY (
                    ARRAY[
                        ('1'::character varying)::text,
                        ('2'::character varying)::text
                    ]
                )
            )
        ");
    }
};
