<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContainerTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('TRUNCATE TABLE public."_d_container_types" RESTART IDENTITY CASCADE');

        $now = now();

        DB::table('_d_container_types')->insert([
            ['key' => 'euro_pallet',      'name' => 'Європалета',           'created_at' => $now, 'updated_at' => $now],
            ['key' => 'american_pallet',  'name' => 'Американська палета',  'created_at' => $now, 'updated_at' => $now],
            ['key' => 'plastic_container','name' => 'Пластиковий контейнер','created_at' => $now, 'updated_at' => $now],
            ['key' => 'wooden_crate',     'name' => "Дерев'яний ящик",      'created_at' => $now, 'updated_at' => $now],
            ['key' => 'cardboard_box',    'name' => 'Картонна коробка',     'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
