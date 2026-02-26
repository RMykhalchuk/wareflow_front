<?php

namespace Database\Seeders\Storage;

use App\Models\Dictionaries\WarehouseType;
use Illuminate\Database\Seeder;

class WarehouseTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WarehouseType::updateOrCreate(
            ['key' => 'type1'],
            ['name' => ['uk' => 'Тип 1', 'en' => 'Type 1']]
        );

        WarehouseType::updateOrCreate(
            ['key' => 'type2'],
            ['name' => ['uk' => 'Тип 2', 'en' => 'Type 2']]
        );

        WarehouseType::updateOrCreate(
            ['key' => 'type3'],
            ['name' => ['uk' => 'Тип 3', 'en' => 'Type 3']]
        );

        WarehouseType::updateOrCreate(
            ['key' => 'type4'],
            ['name' => ['uk' => 'Тип 4', 'en' => 'Type 4']]
        );
    }
}
