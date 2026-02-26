<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PackageTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::statement('TRUNCATE TABLE public."_d_package_types" RESTART IDENTITY CASCADE');

        DB::table('_d_package_types')->insert([
            ['key' => 'pallet', 'name' => 'Палета'],
            ['key' => 'box',    'name' => 'Коробка'],
            ['key' => 'pack',   'name' => 'Пачка'],
            ['key' => 'unit',   'name' => 'Одиниця'],
        ]);
    }
}
