<?php

namespace Database\Seeders\Transport;


use App\Models\Dictionaries\AdditionalEquipmentType;
use Illuminate\Database\Seeder;

class AdditionalEquipmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AdditionalEquipmentType::updateOrCreate(
            ['key' => 'a_type1'],
            ['name' => ['uk' => 'Тип А 1', 'en' => 'A Type 1']]
        );

        AdditionalEquipmentType::updateOrCreate(
            ['key' => 'a_type2'],
            ['name' => ['uk' => 'Тип А 2', 'en' => 'A Type 2']]
        );

        AdditionalEquipmentType::updateOrCreate(
            ['key' => 'a_type3'],
            ['name' => ['uk' => 'Тип А 3', 'en' => 'A Type 3']]
        );

        AdditionalEquipmentType::updateOrCreate(
            ['key' => 'a_type4'],
            ['name' => ['uk' => 'Тип А 4', 'en' => 'A Type 4']]
        );

        AdditionalEquipmentType::updateOrCreate(
            ['key' => 'a_type5'],
            ['name' => ['uk' => 'Тип А 5', 'en' => 'A Type 5']]
        );
    }
}
