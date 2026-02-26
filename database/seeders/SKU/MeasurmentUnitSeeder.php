<?php

namespace Database\Seeders\SKU;

use App\Models\Dictionaries\MeasurementUnit;
use Illuminate\Database\Seeder;

class MeasurmentUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $units = [
            ['key' => 'piece', 'name' => ['uk' => 'шт', 'en' => 'pcs']],
            ['key' => 'kg',    'name' => ['uk' => 'кг', 'en' => 'kg']],
            ['key' => 'g',     'name' => ['uk' => 'г', 'en' => 'g']],
            ['key' => 't',     'name' => ['uk' => 'т', 'en' => 't']],
            ['key' => 'l',     'name' => ['uk' => 'л', 'en' => 'l']],
            ['key' => 'ml',    'name' => ['uk' => 'мл', 'en' => 'ml']],
            ['key' => 'm',     'name' => ['uk' => 'м', 'en' => 'm']],
            ['key' => 'cm',    'name' => ['uk' => 'см', 'en' => 'cm']],
            ['key' => 'm2',    'name' => ['uk' => 'м²', 'en' => 'm²']],
            ['key' => 'm3',    'name' => ['uk' => 'м³', 'en' => 'm³']],
        ];

        foreach ($units as $unit) {
            MeasurementUnit::updateOrCreate(
                [
                    'key' => $unit['key'],
                ], [
                    'name' => $unit['name'],
                ]);
        }
    }
}
