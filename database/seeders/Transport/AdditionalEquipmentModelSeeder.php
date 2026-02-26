<?php

namespace Database\Seeders\Transport;

use App\Models\Dictionaries\AdditionalEquipmentModel;
use Illuminate\Database\Seeder;

class AdditionalEquipmentModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AdditionalEquipmentModel::updateOrCreate(
            ['key' => 'model1', 'brand_id' => 1],
            ['name' => ['uk' => 'Модель 1', 'en' => 'Model 1']]
        );

        AdditionalEquipmentModel::updateOrCreate(
            ['key' => 'model1_2', 'brand_id' => 1],
            ['name' => ['uk' => 'Модель 1-2', 'en' => 'Model 1-2']]
        );

        AdditionalEquipmentModel::updateOrCreate(
            ['key' => 'model1_3', 'brand_id' => 1],
            ['name' => ['uk' => 'Модель 1-3', 'en' => 'Model 1-3']]
        );

        AdditionalEquipmentModel::updateOrCreate(
            ['key' => 'model2_1', 'brand_id' => 2],
            ['name' => ['uk' => 'Модель 2-1', 'en' => 'Model 2-1']]
        );

        AdditionalEquipmentModel::updateOrCreate(
            ['key' => 'model2_2', 'brand_id' => 2],
            ['name' => ['uk' => 'Модель 2-2', 'en' => 'Model 2-2']]
        );

        AdditionalEquipmentModel::updateOrCreate(
            ['key' => 'model2_3', 'brand_id' => 2],
            ['name' => ['uk' => 'Модель 2-3', 'en' => 'Model 2-3']]
        );

        AdditionalEquipmentModel::updateOrCreate(
            ['key' => 'model3_1', 'brand_id' => 3],
            ['name' => ['uk' => 'Модель 3-1', 'en' => 'Model 3-1']]
        );

        AdditionalEquipmentModel::updateOrCreate(
            ['key' => 'model4_1', 'brand_id' => 4],
            ['name' => ['uk' => 'Модель 4-1', 'en' => 'Model 4-1']]
        );

        AdditionalEquipmentModel::updateOrCreate(
            ['key' => 'model5_1', 'brand_id' => 5],
            ['name' => ['uk' => 'Модель 5-1', 'en' => 'Model 5-1']]
        );
    }
}
