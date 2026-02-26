<?php

namespace Database\Seeders\Transport;

use App\Models\Dictionaries\AdditionalEquipmentBrand;
use Illuminate\Database\Seeder;

class AdditionalEquipmentBrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AdditionalEquipmentBrand::updateOrCreate(
            ['key' => 'brand1'],
            [
                'name' => [
                    'uk' => 'Бренд 1',
                    'en' => 'Brand 1',
                ],
            ]
        );

        AdditionalEquipmentBrand::updateOrCreate(
            ['key' => 'brand2'],
            [
                'name' => [
                    'uk' => 'Бренд 2',
                    'en' => 'Brand 2',
                ],
            ]
        );

        AdditionalEquipmentBrand::updateOrCreate(
            ['key' => 'brand3'],
            [
                'name' => [
                    'uk' => 'Бренд 3',
                    'en' => 'Brand 3',
                ],
            ]
        );

        AdditionalEquipmentBrand::updateOrCreate(
            ['key' => 'brand4'],
            [
                'name' => [
                    'uk' => 'Бренд 4',
                    'en' => 'Brand 4',
                ],
            ]
        );

        AdditionalEquipmentBrand::updateOrCreate(
            ['key' => 'brand5'],
            [
                'name' => [
                    'uk' => 'Бренд 5',
                    'en' => 'Brand 5',
                ],
            ]
        );
    }
}
